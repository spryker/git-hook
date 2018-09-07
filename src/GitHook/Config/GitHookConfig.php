<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Config;

use InvalidArgumentException;

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
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    public function getExcludedDirs(): array
    {
        $excludedDirs = [];

        if (isset($this->config['excludedDirs'])) {
            $configExcludedDirs = $this->config['excludedDirs'];

            if (!is_array($configExcludedDirs)) {
                throw new InvalidArgumentException("Param 'excludedDirs' should be an array.");
            }

            $excludedDirs = $configExcludedDirs;
        }

        return $excludedDirs;
    }

    /**
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    public function getExcludedFiles(): array
    {
        $excludedFiles = [];

        if (isset($this->config['excludedFiles'])) {
            $configExcludedFiles = $this->config['excludedFiles'];

            if (!is_array($configExcludedFiles)) {
                throw new InvalidArgumentException("Param 'excludedFiles' should be an array.");
            }

            $excludedFiles = $configExcludedFiles;
        }

        return $excludedFiles;
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
