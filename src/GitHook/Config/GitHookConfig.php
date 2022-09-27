<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * @return array<\GitHook\Command\CommandInterface>
     */
    public function getPreCommitFileCommands(): array
    {
        $fileCommands = [];
        if (isset($this->config['preCommitFileCommands'])) {
            foreach ($this->config['preCommitFileCommands'] as $fileCommand) {
                /** @var class-string<\GitHook\Command\CommandInterface> $fileCommand */
                $fileCommand = '\\' . ltrim($fileCommand, '\\');
                $fileCommands[] = new $fileCommand();
            }
        }

        return $fileCommands;
    }

    /**
     * @return array<\GitHook\Command\CommandInterface>
     */
    public function getPreCommitRepositoryCommands(): array
    {
        $repositoryCommands = [];
        if (isset($this->config['preCommitRepositoryCommands'])) {
            foreach ($this->config['preCommitRepositoryCommands'] as $repositoryCommand) {
                /** @var class-string<\GitHook\Command\CommandInterface> $repositoryCommand */
                $repositoryCommand = '\\' . ltrim($repositoryCommand, '\\');
                $repositoryCommands[] = new $repositoryCommand();
            }
        }

        return $repositoryCommands;
    }

    /**
     * @return array<\GitHook\Command\CommandInterface>
     */
    public function getPrePushRepositoryCommands(): array
    {
        $repositoryCommands = [];
        if (isset($this->config['prePushRepositoryCommands'])) {
            foreach ($this->config['prePushRepositoryCommands'] as $repositoryCommand) {
                /** @var class-string<\GitHook\Command\CommandInterface> $repositoryCommand */
                $repositoryCommand = '\\' . ltrim($repositoryCommand, '\\');
                $repositoryCommands[] = new $repositoryCommand();
            }
        }

        return $repositoryCommands;
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
