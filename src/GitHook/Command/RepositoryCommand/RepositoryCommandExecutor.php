<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\RepositoryCommand;

use GitHook\Command\CommandConfiguration;
use GitHook\Helper\ConsoleHelper;

class RepositoryCommandExecutor
{
    /**
     * @var \GitHook\Command\RepositoryCommand\RepositoryCommandInterface[]
     */
    protected $commands;

    /**
     * @var \GitHook\Helper\ConsoleHelper
     */
    protected $consoleHelper;

    /**
     * @param \GitHook\Command\FileCommand\FileCommandInterface[] $commands
     * @param \GitHook\Helper\ConsoleHelper $consoleHelper
     */
    public function __construct(array $commands, ConsoleHelper $consoleHelper)
    {
        $this->commands = $commands;
        $this->consoleHelper = $consoleHelper;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $success = true;
        foreach ($this->commands as $command) {
            $configuration = new CommandConfiguration();
            $configuration = $command->configure($configuration);

            $this->consoleHelper->commandInfo($configuration->getName(), $configuration->getDescription());

            $commandResult = $command->run();
            if (!$commandResult->isSuccess()) {
                $this->consoleHelper->errors((array)$commandResult->getMessage());
                $success = false;
            }
        }

        return $success;
    }
}
