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
     * @return \GitHook\Config\GitHookConfig
     */
    public function getConfig()
    {
        $configLocal = PROJECT_ROOT . '/.githook_local';
        $configDefault = PROJECT_ROOT . '/.githook';

        if (is_file($configLocal)) {
            $config = Yaml::parse(file_get_contents($configLocal));

            return new GitHookConfig($config);
        }
        $config = [];

        if (is_file($configDefault)) {
            $config = Yaml::parse(file_get_contents($configDefault));
        }

        return new GitHookConfig($config);
    }

}
