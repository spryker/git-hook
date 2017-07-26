<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GithubHook\Command\FileCommand\PreCommit;

use GithubHook\Command\CommandConfigurationInterface;
use GithubHook\Command\CommandResult;
use GithubHook\Command\FileCommand\FileCommandInterface;
use GithubHook\Helper\ProcessBuilderHelper;
use Symfony\Component\Process\Process;

/**
 * If you see an error about something can't be autoloaded, check if there is an alias for the given class.
 */
class PhpStanCheckCommand implements FileCommandInterface
{

    use ProcessBuilderHelper;

    /**
     * @param \GithubHook\Command\CommandConfigurationInterface $commandConfiguration
     *
     * @return \GithubHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $commandConfiguration)
    {
        $commandConfiguration
            ->setName('PhpStan check')
            ->setAcceptedFileExtensions('php');

        return $commandConfiguration;
    }

    /**
     * @param string $file
     *
     * @return \GithubHook\Command\CommandResult
     */
    public function run($file)
    {
        $commandResult = new CommandResult();

        $process = new Process('vendor/bin/phpstan analyse --autoload-file=vendor/autoload.php ' . $file, PROJECT_ROOT);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        return $commandResult;
    }

}
