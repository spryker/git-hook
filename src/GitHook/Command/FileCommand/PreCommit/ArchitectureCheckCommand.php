<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\FileCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\FileCommand\FileCommandInterface;
use GitHook\Helper\ProcessBuilderHelper;

class ArchitectureCheckCommand implements FileCommandInterface
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
            ->setName('Architecture check')
            ->setDescription('Checks the Architecture rules.')
            ->setAcceptedFileExtensions('php');

        return $commandConfiguration;
    }

    /**
     * @param string $file
     *
     * @return \GitHook\Command\CommandResult
     */
    public function run($file)
    {
        $commandResult = new CommandResult();

        $processDefinition = ['vendor/bin/phpmd', $file, 'xml', 'vendor/spryker/architecture-sniffer/src/ruleset.xml'];
        $process = $this->buildProcess($processDefinition);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        return $commandResult;
    }

}
