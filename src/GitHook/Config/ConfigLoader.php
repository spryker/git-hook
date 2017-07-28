<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Config;

use Symfony\Component\Yaml\Yaml;

class ConfigLoader
{

    /**
     * @param string $pathToConfig
     *
     * @return \GitHook\Config\GitHookConfig
     */
    public function getConfig(string $pathToConfig): GitHookConfig
    {
        $config = [];
        if (is_file($pathToConfig)) {
            $config = Yaml::parse(file_get_contents($pathToConfig));
        }

        return new GitHookConfig($config);
    }

}
