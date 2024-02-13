<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command\Context;

use GitHook\Config\GitHookConfig;

interface CommandContextInterface
{
    /**
     * @param \GitHook\Config\GitHookConfig $config
     *
     * @return $this
     */
    public function setConfig(GitHookConfig $config);

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
     * @return $this
     */
    public function setFile(string $file);

    /**
     * Returns an absolute file path.
     *
     * @return string
     */
    public function getFile(): string;

    /**
     * @return array<\GitHook\Command\CommandInterface>
     */
    public function getCommands(): array;

    /**
     * @param array<\GitHook\Command\CommandInterface> $commands
     *
     * @return $this
     */
    public function setCommands(array $commands);

    /**
     * @return string
     */
    public function getBranch(): string;

    /**
     * @param string $branch
     *
     * @return $this
     */
    public function setBranch(string $branch);
}
