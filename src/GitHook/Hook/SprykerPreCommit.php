<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Hook;

use Exception;
use GitHook\Command\FileCommand\FileCommandExecutor;
use GitHook\Command\RepositoryCommand\RepositoryCommandExecutor;
use GitHook\Helper\CommittedFilesHelper;
use GitHook\Helper\ConsoleHelper;
use GitHook\Helper\ContextHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SprykerPreCommit extends Application
{
    use CommittedFilesHelper;
    use ContextHelper;

    public function __construct()
    {
        parent::__construct('Spryker pre-commit', '1.0.0');
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
        $context = $this->createContext();
        $fileCommands = $context->getConfig()->getPreCommitFileCommands();
        $repositoryCommands = $context->getConfig()->getPreCommitRepositoryCommands();

        if (count($fileCommands) === 0 && count($repositoryCommands) === 0) {
            return;
        }

        $consoleHelper = new ConsoleHelper($input, $output);
        $consoleHelper->gitHookHeader('Spryker Git pre-commit hook');

        $context->setCommands($fileCommands);

        [$committedFiles, $excludedFiles] = $this->getFilteredCommittedFiles(
            $context->getConfig()->getExcludedDirs(),
            $context->getConfig()->getExcludedFiles()
        );

        if ($excludedFiles) {
            $consoleHelper->note(sprintf('File(s) %s were excluded from the check.', implode(', ', $excludedFiles)));
        }

        $fileCommandExecutor = new FileCommandExecutor($committedFiles, $consoleHelper);

        $fileCommandSuccess = $fileCommandExecutor->execute($context);

        $context->setCommands($repositoryCommands);
        $repositoryCommandExecutor = new RepositoryCommandExecutor($consoleHelper);
        $repositoryCommandSuccess = $repositoryCommandExecutor->execute($context);

        if (!$fileCommandSuccess || !$repositoryCommandSuccess) {
            throw new Exception(PHP_EOL . PHP_EOL . 'There are some errors! If you can not fix them you can append "--no-verify (-n)" to your commit command.');
        }

        $consoleHelper->newLine(2);
        $consoleHelper->success('Good job dude!');
    }
}
