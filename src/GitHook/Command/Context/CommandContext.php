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
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function setConfig(GitHookConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \GitHook\Config\GitHookConfig
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param string $commandName
     *
     * @return array
     */
    public function getCommandConfig($commandName)
    {
        return $this->config->getCommandConfig($commandName);
    }

    /**
     * @param string $file
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return \GitHook\Command\CommandInterface[]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param \GitHook\Command\CommandInterface[] $commands
     *
     * @return $this
     */
    public function setCommands(array $commands)
    {
        $this->commands = $commands;

        return $this;
    }
}
