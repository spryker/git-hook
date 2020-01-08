<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command\FileCommand;

use GitHook\Command\CommandConfiguration;
use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandExecutorInterface;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Helper\ConsoleHelper;

class FileCommandExecutor implements CommandExecutorInterface
{
    /**
     * @var array
     */
    protected $committedFiles;

    /**
     * @var \GitHook\Helper\ConsoleHelper
     */
    protected $consoleHelper;

    /**
     * @param array $committedFiles
     * @param \GitHook\Helper\ConsoleHelper $consoleHelper
     */
    public function __construct(array $committedFiles, ConsoleHelper $consoleHelper)
    {
        $this->committedFiles = $committedFiles;
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
        foreach ($context->getCommands() as $command) {
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

                $context->setFile($committedFile);

                $commandResult = $command->run($context);
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

        if (!isset($pathinfo['extension'])) {
            return false;
        }

        $extension = $pathinfo['extension'];
        $acceptedExtensions = $configuration->getAcceptedFileExtensions();

        if (count($acceptedExtensions) === 0 || in_array($extension, $acceptedExtensions)) {
            return true;
        }

        return false;
    }
}
