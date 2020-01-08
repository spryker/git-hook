<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Helper;

use Exception;
use GitHook\Command\Context\CommandContext;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Config\ConfigLoader;

trait ContextHelper
{
    /**
     * @throws \Exception
     *
     * @return \GitHook\Command\Context\CommandContextInterface
     */
    public function createContext(): CommandContextInterface
    {
        $context = new CommandContext();
        $configLoader = new ConfigLoader();
        $configFilePath = realpath(PROJECT_ROOT) . '/.githook';

        if (!file_exists($configFilePath)) {
            throw new Exception('Could not load ".githook" configuration file. Please add one to the root of your project.');
        }

        $config = $configLoader->getConfig();

        $context->setConfig($config);

        return $context;
    }
}
