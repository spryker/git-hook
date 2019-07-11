<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\FileCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Command\FileCommand\PreCommit\PhpStan\PhpStanConfiguration;
use GitHook\Helper\ProcessBuilderHelper;
use Symfony\Component\Process\Process;

/**
 * If you see an error about something can't be autoloaded, check if there is an alias for the given class.
 */
class PhpStanCheckCommand implements CommandInterface
{

    use ProcessBuilderHelper;

    /**
     * @param \GitHook\Command\CommandConfigurationInterface $commandConfiguration
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $commandConfiguration)
    {
        $commandConfiguration
            ->setName('PhpStan check')
            ->setAcceptedFileExtensions('php');

        return $commandConfiguration;
    }

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(CommandContextInterface $context)
    {
        $phpStanConfiguration = new PhpStanConfiguration($context->getCommandConfig('phpstan'));
        $commandResult = new CommandResult();
        $filePath = $context->getFile();

        if (!$this->isFileAllowed($filePath, $phpStanConfiguration->getDirectories())) {
            return $commandResult;
        }

        $level = $this->getLevel($phpStanConfiguration, $context);

        $processDefinition = ['vendor/bin/phpstan', 'analyse', $filePath, '-l', $level, '-c', $phpStanConfiguration->getConfigPath()];
        $process = $this->buildProcess($processDefinition, PROJECT_ROOT);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        return $commandResult;
    }

    /**
     * @param string $filePath
     * @param array $allowedDirectories
     *
     * @return bool
     */
    protected function isFileAllowed(string $filePath, array $allowedDirectories): bool
    {
        $fileDirectory = dirname($filePath);

        foreach ($allowedDirectories as $allowedDirectory) {
            if (strpos($fileDirectory, $allowedDirectory) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \GitHook\Command\FileCommand\PreCommit\PhpStan\PhpStanConfiguration $phpStanConfiguration
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return int
     */
    protected function getLevel(PhpStanConfiguration $phpStanConfiguration, CommandContextInterface $context): int
    {
        $level = $phpStanConfiguration->getLevel();
        $phpStanConfigFilePath = $this->findPhpStanJson($context->getFile());

        if (!$phpStanConfigFilePath) {
            return $level;
        }

        $moduleConfig = json_decode(file_get_contents($phpStanConfigFilePath), true);
        if (!isset($moduleConfig['defaultLevel'])) {
            return $level;
        }

        return $moduleConfig['defaultLevel'];
    }

    /**
     * @param string $filePath
     *
     * @return string|null
     */
    protected function findPhpStanJson(string $filePath): ?string
    {
        $srcDirectoryPosition = strrpos($filePath, '/src/');

        if (!$srcDirectoryPosition) {
            return null;
        }

        $path = substr($filePath, 0, $srcDirectoryPosition);
        $phpStanConfigFilePath = $path . DIRECTORY_SEPARATOR . 'phpstan.json';

        if (file_exists($phpStanConfigFilePath)) {
            return $phpStanConfigFilePath;
        }

        return null;
    }
}
