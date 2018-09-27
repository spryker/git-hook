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
     * @return \GitHook\Command\Context\CommandContextInterface
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
     * @return bool
     */
    public function isModuleFile(): bool
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $this->getFile());
        $bundlePosition = array_search('Bundles', $pathFragments, true);
        if ($bundlePosition === false) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getModuleName(): string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $this->getFile());
        $bundlePosition = array_search('Bundles', $pathFragments, true);
        $moduleName = $pathFragments[$bundlePosition + 1];

        return $moduleName;
    }

    /**
     * @return string
     */
    public function getOrganizationName(): string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $this->getFile());
        $bundlePosition = array_search('Bundles', $pathFragments, true);
        $moduleName = $pathFragments[$bundlePosition + 3];

        return $moduleName;
    }

    /**
     * @return string
     */
    public function getModuleKey(): string
    {
        return sprintf('%s.%s', $this->getOrganizationName(), $this->getModuleName());
    }

    /**
     * @return string
     */
    public function getModulePath(): string
    {
        $pathFragments = explode(DIRECTORY_SEPARATOR, $this->getFile());
        $bundlePosition = array_search('Bundles', $pathFragments, true);

        $pathFragments = array_slice($pathFragments, 0, $bundlePosition + 2);

        return implode(DIRECTORY_SEPARATOR, $pathFragments) . DIRECTORY_SEPARATOR;
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
