<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GithubHook\Command\RepositoryCommand;

use GithubHook\Command\CommandConfiguration;
use GithubHook\Helper\ConsoleHelper;

class RepositoryCommandExecutor
{
    /**
     * @var \GithubHook\Command\RepositoryCommand\RepositoryCommandInterface[]
     */
    protected $commands;

    /**
     * @var \GithubHook\Helper\ConsoleHelper
     */
    protected $consoleHelper;

    /**
     * @param \GithubHook\Command\FileCommand\FileCommandInterface[] $commands
     * @param \GithubHook\Helper\ConsoleHelper $consoleHelper
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
