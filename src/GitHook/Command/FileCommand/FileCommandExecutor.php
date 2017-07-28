<?php declare(strict_types = 1);

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\FileCommand;

use GitHook\Command\CommandConfiguration;
use GitHook\Command\CommandConfigurationInterface;
use GitHook\Helper\ConsoleHelper;

class FileCommandExecutor implements CommandExecutorInterface
{

    /**
     * @var \GitHook\Command\FileCommand\FileCommandInterface[]
     */
    protected $commands;

    /**
     * @var array
     */
    protected $committedFiles;

    /**
     * @var \GitHook\Helper\ConsoleHelper
     */
    protected $consoleHelper;

    /**
     * @param \GitHook\Command\FileCommand\FileCommandInterface[] $commands
     * @param array $committedFiles
     * @param \GitHook\Helper\ConsoleHelper $consoleHelper
     */
    public function __construct(array $commands, array $committedFiles, ConsoleHelper $consoleHelper)
    {
        $this->commands = $commands;
        $this->committedFiles = $committedFiles;
        $this->consoleHelper = $consoleHelper;
    }

    /**
     * @return bool
     */
    public function execute(): bool
    {
        $success = true;
        foreach ($this->commands as $command) {
            $configuration = new CommandConfiguration();
            $configuration = $command->configure($configuration);

            $this->consoleHelper->commandInfo($configuration->getName(), $configuration->getDescription());
            $progressBar = $this->consoleHelper->createProgressBar();
            $progressBar->start(count($this->committedFiles));

            $messages = [];
            foreach ($this->committedFiles as $committedFile) {

                if (!$this->acceptsFileExtension($committedFile, $configuration)) {
                    continue;
                }

                $commandResult = $command->run($committedFile);
                if (!$commandResult->isSuccess()) {
                    $messages[] = $commandResult->getMessage();
                    $success = false;
                }

                $progressBar->advance();
            }
            $progressBar->finish();

            if (count($messages) > 0) {
                $this->consoleHelper->errors($messages);
            }
        }

        return $success;
    }

    /**
     * @param string $committedFile
     * @param \GitHook\Command\CommandConfigurationInterface $configuration
     *
     * @return bool
     */
    private function acceptsFileExtension(string $committedFile, CommandConfigurationInterface $configuration): bool
    {
        $pathinfo = pathinfo($committedFile);
        $extension = $pathinfo['extension'];
        $acceptedExtensions = $configuration->getAcceptedFileExtensions();

        if (count($acceptedExtensions) === 0 || in_array($extension, $acceptedExtensions)) {
            return true;
        }

        return false;
    }

}
