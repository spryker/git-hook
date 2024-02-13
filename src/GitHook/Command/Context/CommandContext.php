<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * @var array<\GitHook\Command\CommandInterface>
     */
    protected $commands;

    /**
     * @var string
     */
    protected $branch;

    /**
     * @param \GitHook\Config\GitHookConfig $config
     *
     * @return $this
     */
    public function setConfig(GitHookConfig $config)
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
    public function setFile(string $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * {@inheritDoc}
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
    public function getProjectPath(): string
    {
        return realpath(PROJECT_ROOT) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return array<\GitHook\Command\CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

    /**
     * @param array<\GitHook\Command\CommandInterface> $commands
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function setCommands(array $commands): CommandContextInterface
    {
        $this->commands = $commands;

        return $this;
    }

    /**
     * @return string
     */
    public function getBranch(): string
    {
        return $this->branch;
    }

    /**
     * @param string $branch
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function setBranch(string $branch): CommandContextInterface
    {
        $this->branch = $branch;

        return $this;
    }
}
