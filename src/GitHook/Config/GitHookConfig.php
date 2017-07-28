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
    public function getFileCommands(): array
    {
        $fileCommands = [];
        if (isset($this->config['includedFileCommands'])) {
            foreach ($this->config['includedFileCommands'] as $fileCommand) {
                $fileCommand = '\\' . ltrim($fileCommand, '\\');
                $fileCommands[] = new $fileCommand;
            }
        }

        return $fileCommands;
    }

    /**
     * @return \GitHook\Command\CommandInterface[]
     */
    public function getRepositoryCommands(): array
    {
        $repositoryCommands = [];
        if (isset($this->config['includedRepositoryCommands'])) {
            foreach ($this->config['includedRepositoryCommands'] as $repositoryCommand) {
                $repositoryCommand = '\\' . ltrim($repositoryCommand, '\\');
                $repositoryCommands[] = new $repositoryCommand;
            }
        }

        return $repositoryCommands;
    }

    /**
     * @return array
     */
    public function getExcludedCommands(): array
    {
        $excludedCommands = [];
        if (isset($this->config['excludedCommands'])) {
            $excludedCommands = $this->config['excludedCommands'];
        }

        return $excludedCommands;
    }

    /**
     * @param string $commandName
     *
     * @return array
     */
    public function getCommandConfig(string $commandName): array
    {
        $commandConfig = [];
        if (isset($this->config['config'][$commandName])) {
            $commandConfig = $this->config['config'][$commandName];
        }

        return $commandConfig;
    }

}
