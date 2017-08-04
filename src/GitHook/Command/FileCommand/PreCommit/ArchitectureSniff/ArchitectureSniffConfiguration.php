<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\FileCommand\PreCommit\ArchitectureSniff;

class ArchitectureSniffConfiguration
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
