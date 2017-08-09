<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Config;

class GitHookConfig
{

    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return \GitHook\Command\CommandInterface[]
     */
    public function getPreCommitFileCommands()
    {
        $fileCommands = [];
        if (isset($this->config['preCommitFileCommands'])) {
            foreach ($this->config['preCommitFileCommands'] as $fileCommand) {
                $fileCommand = '\\' . ltrim($fileCommand, '\\');
                $fileCommands[] = new $fileCommand;
            }
        }

        return $fileCommands;
    }

    /**
     * @return \GitHook\Command\CommandInterface[]
     */
    public function getPreCommitRepositoryCommands()
    {
        $repositoryCommands = [];
        if (isset($this->config['preCommitRepositoryCommands'])) {
            foreach ($this->config['preCommitRepositoryCommands'] as $repositoryCommand) {
                $repositoryCommand = '\\' . ltrim($repositoryCommand, '\\');
                $repositoryCommands[] = new $repositoryCommand;
            }
        }

        return $repositoryCommands;
    }

    /**
     * @return \GitHook\Command\CommandInterface[]
     */
    public function getPrePushRepositoryCommands()
    {
        $repositoryCommands = [];
        if (isset($this->config['prePushRepositoryCommands'])) {
            foreach ($this->config['prePushRepositoryCommands'] as $repositoryCommand) {
                $repositoryCommand = '\\' . ltrim($repositoryCommand, '\\');
                $repositoryCommands[] = new $repositoryCommand;
            }
        }

        return $repositoryCommands;
    }

    /**
     * @param string $commandName
     *
     * @return array
     */
    public function getCommandConfig($commandName)
    {
        $commandConfig = [];
        if (isset($this->config['config'][$commandName])) {
            $commandConfig = $this->config['config'][$commandName];
        }

        return $commandConfig;
    }

}
