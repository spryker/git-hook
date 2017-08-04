<?php declare(strict_types = 1);

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\FileCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\CommandResultInterface;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Helper\ProcessBuilderHelper;
use Symfony\Component\Process\ProcessBuilder;

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

        $processDefinition = ['vendor/bin/console', 'dev:dependency:find-violations', $module];
        $processBuilder = new ProcessBuilder($processDefinition);
        $processBuilder->setWorkingDirectory(PROJECT_ROOT);
        $process = $processBuilder->getProcess();
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
