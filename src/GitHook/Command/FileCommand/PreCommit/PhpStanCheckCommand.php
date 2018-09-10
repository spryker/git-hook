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
        $fileName = $context->getFile();

        if (!$this->isFileAllowed($fileName, $phpStanConfiguration->getDirectories())) {
            return $commandResult;
        }

        $command = 'vendor/bin/phpstan analyse ' . $fileName . ' -l ' . $phpStanConfiguration->getLevel();

        if ($phpStanConfiguration->getConfigPath()) {
            $command .= ' -c ' . $phpStanConfiguration->getConfigPath();
        }

        $process = new Process($command, PROJECT_ROOT);
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
        $fileDir = dirname(realpath($filePath));

        foreach ($allowedDirectories as $excludedPath) {
            if (realpath($excludedPath) === $fileDir) {
                return true;
            }
        }

        return false;
    }
}
