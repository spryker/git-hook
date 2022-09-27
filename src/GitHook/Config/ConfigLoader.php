<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Config;

use Symfony\Component\Yaml\Yaml;

class ConfigLoader
{
    /**
     * @return \GitHook\Config\GitHookConfig
     */
    public function getConfig(): GitHookConfig
    {
        $configLocal = PROJECT_ROOT . '/.githook_local';
        $configDefault = PROJECT_ROOT . '/.githook';

        if (is_file($configLocal)) {
            $config = Yaml::parse((string)file_get_contents($configLocal));

            return new GitHookConfig($config);
        }
        $config = [];

        if (is_file($configDefault)) {
            $config = Yaml::parse((string)file_get_contents($configDefault));
        }

        return new GitHookConfig($config);
    }
}
