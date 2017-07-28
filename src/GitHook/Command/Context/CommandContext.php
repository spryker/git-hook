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
     * @param \GitHook\Config\GitHookConfig $config
     *
     * @return \GitHook\Command\Context\CommandContextInterface
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
    public function getCommandConfig($commandName): array
    {
        return $this->config->getCommandConfig($commandName);
    }

    /**
     * @param string $file
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function setFile(string $file): CommandContextInterface
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

}
