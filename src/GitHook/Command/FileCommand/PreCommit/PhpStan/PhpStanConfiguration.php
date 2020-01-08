<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command\FileCommand\PreCommit\PhpStan;

class PhpStanConfiguration
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
     * @return int
     */
    public function getLevel(): int
    {
        $defaultLevel = 0;
        if (!$this->config) {
            return $defaultLevel;
        }

        return (isset($this->config['level'])) ? $this->config['level'] : $defaultLevel;
    }

    /**
     * @return string[]
     */
    public function getDirectories(): array
    {
        return array_merge([$this->getSourceDirectory()], $this->getAdditionalDirectories());
    }

    /**
     * @return string
     */
    public function getSourceDirectory(): string
    {
        return 'src' . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string[]
     */
    public function getAdditionalDirectories(): array
    {
        return $this->config['additionalDirectories'] ?? [];
    }

    /**
     * @return string
     */
    public function getConfigPath(): string
    {
        return (isset($this->config['config'])) ? '.' . PATH_PREFIX . $this->config['config'] : '';
    }
}
