<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GithubHook\Command\RepositoryCommand\PreCommit;

use GithubHook\Command\CommandConfigurationInterface;
use GithubHook\Command\CommandResult;
use GithubHook\Command\RepositoryCommand\RepositoryCommandInterface;
use GithubHook\Helper\ProcessBuilderHelper;
use Symfony\Component\Process\ProcessBuilder;

class GitAddCommand implements RepositoryCommandInterface
{

    use ProcessBuilderHelper;

    /**
     * @param \GithubHook\Command\CommandConfigurationInterface $commandConfiguration
     *
     * @return \GithubHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $commandConfiguration)
    {
        $commandConfiguration
            ->setName('Git add command')
            ->setDescription('If one of the other commands changed a file we need to add it otherwise it will be ignored in this commit.');

        return $commandConfiguration;
    }

    /**
     * @return \GithubHook\Command\CommandResult
     */
    public function run()
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
