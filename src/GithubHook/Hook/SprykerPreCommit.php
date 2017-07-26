<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GithubHook\Hook;

use Exception;
use GithubHook\Command\FileCommand\FileCommandExecutor;
use GithubHook\Command\FileCommand\PreCommit\ArchitectureCheckCommand;
use GithubHook\Command\FileCommand\PreCommit\CodeStyleCheckCommand;
use GithubHook\Command\FileCommand\PreCommit\CodeStyleFixCommand;
use GithubHook\Command\FileCommand\PreCommit\PhpStanCheckCommand;
use GithubHook\Command\RepositoryCommand\PreCommit\GitAddCommand;
use GithubHook\Command\RepositoryCommand\RepositoryCommandExecutor;
use GithubHook\Helper\ConsoleHelper;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SprykerPreCommit extends Application
{

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
        $consoleHelper = new ConsoleHelper($input, $output);
        $consoleHelper->githubhookHeader('Spryker Github pre-commit hook');

        $committedFiles = $this->extractCommittedFiles();

        $fileCommandExecutor = new FileCommandExecutor($this->getFileCommands(), $committedFiles, $consoleHelper);
        $fileCommandSuccess = $fileCommandExecutor->execute();

        $repositoryCommandExecutor = new RepositoryCommandExecutor($this->getRepositoryCommands(), $consoleHelper);
        $repositoryCommandSuccess = $repositoryCommandExecutor->execute();

        if (!$fileCommandSuccess || !$repositoryCommandSuccess) {
            throw new Exception(PHP_EOL . PHP_EOL . 'There are some errors! If you can not fix them you can append "--no-verify (-n)" to your commit command.');
        }

        $consoleHelper->newLine(2);
        $consoleHelper->success('Good job dude!');
    }

    /**
     * @return array
     */
    private function extractCommittedFiles()
    {
        $output = [];
        $check = 0;

        exec('git rev-parse --verify HEAD 2> /dev/null', $output, $check);

        $against = '4b825dc642cb6eb9a060e54bf8d69288fbee4904';
        if ($check === 0) {
            $against = 'HEAD';
        }

        exec('git diff-index --cached --name-status ' . $against . ' | egrep \'^(A|M)\' | awk \'{print $2;}\'', $committedFiles);

        $prepareFilePathCallback = function ($file) {
            return '.' . PATH_PREFIX . $file;
        };

        $committedFiles = array_map($prepareFilePathCallback, $committedFiles);

        $filterFilesCallback = function ($file) {
            return is_file(PROJECT_ROOT . DIRECTORY_SEPARATOR . $file);
        };

        $committedFiles = array_filter($committedFiles, $filterFilesCallback);

        return $committedFiles;
    }

    /**
     * @return \GithubHook\Command\FileCommand\FileCommandInterface[]
     */
    private function getFileCommands()
    {
        return [
            new CodeStyleFixCommand(),
            new CodeStyleCheckCommand(),
            new PhpStanCheckCommand(),
            new ArchitectureCheckCommand(),
        ];
    }

    /**
     * @return \GithubHook\Command\RepositoryCommand\RepositoryCommandInterface[]
     */
    private function getRepositoryCommands()
    {
        return [
            new GitAddCommand(),
        ];
    }

}
