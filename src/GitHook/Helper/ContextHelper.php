<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Helper;

use GitHook\Command\Context\CommandContext;
use GitHook\Config\ConfigLoader;

trait ContextHelper
{

    /**
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function createContext()
    {
        $context = new CommandContext();
        $configLoader = new ConfigLoader();
        $config = $configLoader->getConfig(PROJECT_ROOT . '/.githook');

        $context->setConfig($config);

        return $context;
    }

}
