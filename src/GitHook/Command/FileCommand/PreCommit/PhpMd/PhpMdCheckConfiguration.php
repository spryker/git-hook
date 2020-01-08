<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command\FileCommand\PreCommit\PhpMd;

class PhpMdCheckConfiguration
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
    public function getMinimumPriority(): int
    {
        $defaultPriority = 2;
        if (!$this->config) {
            return $defaultPriority;
        }

        return (isset($this->config['priority'])) ? $this->config['priority'] : $defaultPriority;
    }
}
