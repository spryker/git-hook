<?php declare(strict_types = 1);

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\RepositoryCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\CommandResultInterface;
use GitHook\Command\RepositoryCommand\RepositoryCommandInterface;
use GitHook\Helper\ProcessBuilderHelper;
use Symfony\Component\Process\ProcessBuilder;

class GitAddCommand implements RepositoryCommandInterface
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
            ->setName('Git add command')
            ->setDescription('If one of the other commands changed a file we need to add it otherwise it will be ignored in this commit.');

        return $commandConfiguration;
    }

    /**
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(): CommandResultInterface
    {
        $commandResult = new CommandResult();

        $processDefinition = ['git', 'add', '.'];
        $processBuilder = new ProcessBuilder($processDefinition);
        $processBuilder->setWorkingDirectory(PROJECT_ROOT . PATH_PREFIX);
        $process = $processBuilder->getProcess();
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        return $commandResult;
    }

}
