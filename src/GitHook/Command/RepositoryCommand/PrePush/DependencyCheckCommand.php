<?php declare(strict_types = 1);

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\RepositoryCommand\PrePush;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\CommandResultInterface;
use GitHook\Command\RepositoryCommand\RepositoryCommandInterface;
use GitHook\Helper\ProcessBuilderHelper;
use Symfony\Component\Process\ProcessBuilder;

class DependencyCheckCommand implements RepositoryCommandInterface
{

    use ProcessBuilderHelper;

    /**
     * @param \GitHook\Command\CommandConfigurationInterface $commandConfiguration
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $commandConfiguration): CommandConfigurationInterface
    {
        $commandConfiguration
            ->setName('Dependency Violation check command')
            ->setDescription('Checks if all composer.json\'s contain the correct require(-dev) statements');

        return $commandConfiguration;
    }

    /**
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(): CommandResultInterface
    {
        $commandResult = new CommandResult();

        $processDefinition = ['vendor/bin/console', 'dev:dependency:find-violations'];
        $processBuilder = new ProcessBuilder($processDefinition);
        $processBuilder->setTimeout(300);
        $processBuilder->setWorkingDirectory(PROJECT_ROOT);
        $process = $processBuilder->getProcess();
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        return $commandResult;
    }

}
