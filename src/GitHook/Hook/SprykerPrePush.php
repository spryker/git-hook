<?php declare(strict_types = 1);

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Hook;

use Exception;
use GitHook\Command\RepositoryCommand\PrePush\DependencyCheckCommand;
use GitHook\Command\RepositoryCommand\RepositoryCommandExecutor;
use GitHook\Helper\ConsoleHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SprykerPrePush extends Application
{

    public function __construct()
    {
        parent::__construct('Spryker pre-push', '1.0.0');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     *
     * @return void
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $consoleHelper = new ConsoleHelper($input, $output);
        $consoleHelper->gitHookHeader('Spryker Git pre-push hook');

        $repositoryCommandExecutor = new RepositoryCommandExecutor($this->getRepositoryCommands(), $consoleHelper);
        $repositoryCommandSuccess = $repositoryCommandExecutor->execute();

        if (!$repositoryCommandSuccess) {
            throw new Exception(PHP_EOL . PHP_EOL . 'There are some errors! If you can not fix them you can append "--no-verify (-n)" to your push command.');
        }

        $consoleHelper->newLine(2);
        $consoleHelper->success('Good job dude!');
    }

    /**
     * @return \GitHook\Command\RepositoryCommand\RepositoryCommandInterface[]
     */
    private function getRepositoryCommands()
    {
        return [
            new DependencyCheckCommand(),
        ];
    }

}
