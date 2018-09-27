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
    public function getCommandConfig(string $commandName): array;

    /**
     * @param string $file
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function setFile(string $file): CommandContextInterface;

    /**
     * Returns an absolute file path.
     *
     * @return string
     */
    public function getFile(): string;

    /**
     * @return bool
     */
    public function isModuleFile(): bool;

    /**
     * @return string
     */
    public function getModuleName(): string;

    /**
     * @return string
     */
    public function getOrganizationName(): string;

    /**
     * @return string
     */
    public function getModuleKey(): string;

    /**
     * Returns an absolute path to the module.
     *
     * @return string
     */
    public function getModulePath(): string;

    /**
     * @return \GitHook\Command\CommandInterface[]
     */
    public function getCommands(): array;

    /**
     * @param \GitHook\Command\CommandInterface[] $commands
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function setCommands(array $commands): CommandContextInterface;
}
