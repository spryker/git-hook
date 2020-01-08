<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Hook;

use Exception;
use GitHook\Command\RepositoryCommand\RepositoryCommandExecutor;
use GitHook\Helper\ConsoleHelper;
use GitHook\Helper\ContextHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SprykerPrePush extends Application
{
    use ContextHelper;

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
     * @return int
     */
    public function doRun(InputInterface $input, OutputInterface $output): int
    {
        $context = $this->createContext();
        $commands = $context->getConfig()->getPrePushRepositoryCommands();
        $context->setCommands($commands);

        $consoleHelper = new ConsoleHelper($input, $output);

        if (count($commands) === 0) {
            return 0;
        }

        $consoleHelper->gitHookHeader('Spryker Git pre-push hook');

        $repositoryCommandExecutor = new RepositoryCommandExecutor($consoleHelper);
        $repositoryCommandSuccess = $repositoryCommandExecutor->execute($context);

        if (!$repositoryCommandSuccess) {
            throw new Exception(PHP_EOL . PHP_EOL . 'There are some errors! If you can not fix them you can append "--no-verify (-n)" to your push command.');
        }

        $consoleHelper->newLine(2);
        $consoleHelper->success('Good job dude!');

        return 0;
    }
}
