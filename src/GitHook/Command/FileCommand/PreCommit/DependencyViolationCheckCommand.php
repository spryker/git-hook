<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command\FileCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\CommandResultInterface;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Helper\ProcessBuilderHelper;
use Laminas\Filter\FilterChain;
use Laminas\Filter\Word\DashToCamelCase;

class DependencyViolationCheckCommand implements CommandInterface
{
    use ProcessBuilderHelper;

    /**
     * @var array
     */
    protected $checkedModules = [];

    /**
     * @param \GitHook\Command\CommandConfigurationInterface $commandConfiguration
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $commandConfiguration): CommandConfigurationInterface
    {
        $commandConfiguration
            ->setName('Dependency violation check.')
            ->setDescription('Find dependency violations.');

        return $commandConfiguration;
    }

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(CommandContextInterface $context): CommandResultInterface
    {
        $commandResult = new CommandResult();

        if (!$this->isCheckAble($context->getFile())) {
            return $commandResult;
        }

        $module = $this->getModuleNameFromFile($context->getFile());

        if ($this->isAlreadyChecked($module)) {
            return $commandResult;
        }

        $organization = $this->getOrganizationNameFromFile($context->getFile());

        $processDefinition = ['vendor/bin/console', 'dev:dependency:find', sprintf('%s.%s', $organization, $module), '-vv'];
        $process = $this->buildProcess($processDefinition, PROJECT_ROOT);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        $this->checkedModules[] = $module;

        return $commandResult;
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    private function getModuleNameFromFile(string $fileName): string
    {
        $filePathParts = explode(DIRECTORY_SEPARATOR, $fileName);
        $namespacePosition = array_search('Bundles', $filePathParts);

        return $filePathParts[$namespacePosition + 1];
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    private function getOrganizationNameFromFile(string $fileName): string
    {
        $filePathParts = explode(DIRECTORY_SEPARATOR, $fileName);
        $vendorPosition = array_search('vendor', $filePathParts);

        $organizationName = $filePathParts[$vendorPosition + 2];
        $filterChain = new FilterChain();
        $filterChain->attach(new DashToCamelCase());

        return $filterChain->filter($organizationName);
    }

    /**
     * @param string $fileName
     *
     * @return bool
     */
    private function isCheckAble(string $fileName): bool
    {
        $filePathParts = explode(DIRECTORY_SEPARATOR, $fileName);

        return (array_search('Bundles', $filePathParts) !== false);
    }

    /**
     * @param string $module
     *
     * @return bool
     */
    private function isAlreadyChecked(string $module): bool
    {
        return (in_array($module, $this->checkedModules));
    }
}
