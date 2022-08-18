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
use GitHook\Command\FileCommand\PreCommit\CodeStyleSniff\CodeStyleSniffConfiguration;
use GitHook\Helper\ProcessBuilderHelper;

class CodeStyleCheckCommand implements CommandInterface
{
    use ProcessBuilderHelper;

    /**
     * @param \GitHook\Command\CommandConfigurationInterface $commandConfiguration
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $commandConfiguration): CommandConfigurationInterface
    {
        $commandConfiguration
            ->setName('CodeStyle check')
            ->setDescription('Checks for not automatically fixable CodeStyle bugs.')
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
        $configuration = new CodeStyleSniffConfiguration();
        $rulesetPath = $configuration->resolvePath($context);

        $commandResult = new CommandResult();

        $processDefinition = ['vendor/bin/phpcs', $context->getFile(), '--standard=' . $rulesetPath];
        $process = $this->buildProcess($processDefinition);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        return $commandResult;
    }
}
