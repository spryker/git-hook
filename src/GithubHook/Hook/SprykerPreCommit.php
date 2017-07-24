<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHubHook\Hook;

use GitHubHook\FileCommands\PreCommit\ArchitectureCheckCommand;
use GitHubHook\FileCommands\PreCommit\CodeStyleCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SprykerPreCommit extends Application
{

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    private $output;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

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
        $this->input = $input;
        $this->output = $output;

        $committedFiles = $this->extractCommittedFiles();

        $output->writeln('<fg=white;options=bold;bg=red>Spryker Github pre-commit hook</fg=white;options=bold;bg=red>');

        $success = true;

        foreach ($this->getFileCommands() as $fileCommand) {
            foreach ($committedFiles as $committedFile) {
                $output->writeln(sprintf('<info>%s</info>', $committedFile));

                if (!$fileCommand->run($committedFile, $output)) {
                    $success = false;
                }
            }

        }

        if (!$success) {
            throw new Exception('There are some errors! If you can not fix them you can append "--no-validation" to your commit command.');
        }

        $output->writeln('<info>Good job dude!</info>');
    }

    /**
     * @return array
     */
    private function extractCommittedFiles()
    {
        $output = [];
        $rc = 0;

        exec('git rev-parse --verify HEAD 2> /dev/null', $output, $rc);

        $against = '4b825dc642cb6eb9a060e54bf8d69288fbee4904';
        if ($rc == 0) {
            $against = 'HEAD';
        }

        exec('git diff-index --cached --name-status ' . $against . ' | egrep \'^(A|M)\' | awk \'{print $2;}\'', $output);

        $filterFilesCallback = function ($file) {
            return is_file($file);
        };

        $committedFiles = array_filter($output, $filterFilesCallback);

        $prepareFilePathCallback = function ($file) {
            return PATH_PREFIX . $file;
        };

        $committedFiles = array_map($prepareFilePathCallback, $committedFiles);

        return $committedFiles;
    }

    /**
     * @return \GitHubHook\FileCommands\PreCommit\FileCommandInterface[]
     */
    private function getFileCommands()
    {
        return [
            new CodeStyleCommand(),
            new ArchitectureCheckCommand(),
        ];
    }

}
