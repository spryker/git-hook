<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Config;

class ConfigLoader
{

    /**
     * @return array
     */
    public function parseConfig(string $pathToConfig): array
    {
        $config = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($pathToConfig));
        echo '<pre>' . PHP_EOL . \Symfony\Component\VarDumper\VarDumper::dump($config) . PHP_EOL . 'Line: ' . __LINE__ . PHP_EOL . 'File: ' . __FILE__ . die();
    }
}
