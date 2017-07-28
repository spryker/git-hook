<?php declare(strict_types = 1);

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\RepositoryCommand;

use GitHook\Command\CommandConfiguration;
use GitHook\Command\CommandExecutorInterface;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Helper\ConsoleHelper;

class RepositoryCommandExecutor implements CommandExecutorInterface
{

    /**
     * @var \GitHook\Helper\ConsoleHelper
     */
    protected $consoleHelper;

    /**
     * @param \GitHook\Helper\ConsoleHelper $consoleHelper
     */
    public function __construct(ConsoleHelper $consoleHelper)
    {
        $this->consoleHelper = $consoleHelper;
    }

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return bool
     */
    public function execute(CommandContextInterface $context): bool
    {
        $success = true;
        foreach ($context->getConfig()->getRepositoryCommands() as $command) {
            $configuration = new CommandConfiguration();
            $configuration = $command->configure($configuration);

            $this->consoleHelper->commandInfo($configuration->getName(), $configuration->getDescription());

            $commandResult = $command->run($context);
            if (!$commandResult->isSuccess()) {
                $this->consoleHelper->errors((array)$commandResult->getMessage());
                $success = false;
            }
        }

        return $success;
    }

}
