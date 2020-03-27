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
use GitHook\Command\FileCommand\PreCommit\PhpMd\PhpMdCheckConfiguration;
use GitHook\Helper\ProcessBuilderHelper;

class PhpMdCheckCommand implements CommandInterface
{
    use ProcessBuilderHelper;

    /**
     * @var bool[]
     */
    protected $processedDirectories;

    /**
     * @param \GitHook\Command\CommandConfigurationInterface $commandConfiguration
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $commandConfiguration): CommandConfigurationInterface
    {
        $commandConfiguration
            ->setName('PHP Mess detector check.')
            ->setDescription('Checks the PHPMd rules.')
            ->setAcceptedFileExtensions('php');

        return $commandConfiguration;
    }

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(CommandContextInterface $context): CommandResultInterface
    {
        $configuration = new PhpMdCheckConfiguration($context->getCommandConfig('phpmd'));
        $commandResult = new CommandResult();

        $directory = dirname($context->getFile());

        if (isset($this->processedDirectories[$directory])) {
            return $commandResult;
        }

        $processDefinition = [
            'vendor/bin/phpmd',
            $directory,
            'xml',
            $configuration->getRulesetPath(),
            '--minimumpriority',
            $configuration->getMinimumPriority(),
        ];

        $process = $this->buildProcess($processDefinition);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput() ?: $process->getErrorOutput()));
        }

        $this->processedDirectories[$directory] = true;

        return $commandResult;
    }
}
