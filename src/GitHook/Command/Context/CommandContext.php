<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\Context;

use GitHook\Config\GitHookConfig;

class CommandContext implements CommandContextInterface
{
    /**
     * @var \GitHook\Config\GitHookConfig
     */
    protected $config;

    /**
     * @var string
     */
    protected $file;

    /**
     * @var \GitHook\Command\CommandInterface[]
     */
    protected $commands;

    /**
     * @param \GitHook\Config\GitHookConfig $config
     *
     * @return $this
     */
    public function setConfig(GitHookConfig $config): CommandContextInterface
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \GitHook\Config\GitHookConfig
     */
    public function getConfig(): GitHookConfig
    {
        return $this->config;
    }

    /**
     * @param string $commandName
     *
     * @return array
     */
    public function getCommandConfig(string $commandName): array
    {
        return $this->config->getCommandConfig($commandName);
    }

    /**
     * @param string $file
     *
     * @return $this
     */
    public function setFile(string $file): CommandContextInterface
    {
        $this->file = $file;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getFile(): string
    {
        if (strpos($this->file, './') === 0) {
            $this->file = $this->getProjectPath() . substr($this->file, 2);
        }

        return $this->file;
    }

    /**
     * @return string
     */
    protected function getProjectPath(): string
    {
        return realpath(PROJECT_ROOT) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return \GitHook\Command\CommandInterface[]
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @param \GitHook\Command\CommandInterface[] $commands
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function setCommands(array $commands): CommandContextInterface
    {
        $this->commands = $commands;

        return $this;
    }
}
