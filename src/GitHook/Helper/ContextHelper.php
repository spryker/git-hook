<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Helper;

use Exception;
use GitHook\Command\Context\CommandContext;
use GitHook\Config\ConfigLoader;

trait ContextHelper
{

    /**
     * @throws \Exception
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function createContext()
    {
        $context = new CommandContext();
        $configLoader = new ConfigLoader();
        $configFilePath = realpath(PROJECT_ROOT) . '/.githook';

        if (!file_exists($configFilePath)) {
            throw new Exception('Could not load ".githook" configuration file. Please add one to the root of your project.');
        }

        $config = $configLoader->getConfig(PROJECT_ROOT . '/.githook');

        $context->setConfig($config);

        return $context;
    }

}
