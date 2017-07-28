<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\Context;

use GitHook\Config\GitHookConfig;

interface CommandContextInterface
{

    /**
     * @param \GitHook\Config\GitHookConfig $config
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function setConfig(GitHookConfig $config): CommandContextInterface;

    /**
     * @return \GitHook\Config\GitHookConfig
     */
    public function getConfig(): GitHookConfig;

    /**
     * @param string $commandName
     *
     * @return array
     */
    public function getCommandConfig($commandName): array;

    /**
     * @param string $file
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function setFile(string $file): CommandContextInterface;

    /**
     * @return string
     */
    public function getFile(): string;

}
