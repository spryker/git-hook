<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @return string
     */
    public function getConfigPath(): string
    {
        return (isset($this->config['config'])) ? $this->config['config'] : '';
    }

}
