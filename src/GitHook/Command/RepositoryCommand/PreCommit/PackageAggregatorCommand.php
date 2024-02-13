<?php

namespace GitHook\Command\RepositoryCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\CommandResultInterface;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Helper\ProcessBuilderHelper;

class PackageAggregatorCommand implements CommandInterface
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
            ->setName('Package Aggregator')
            ->setDescription('Aggregates package.json dependencies across the project.');

        return $commandConfiguration;
    }

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(CommandContextInterface $context): CommandResultInterface
    {
        $directory = $context->getProjectPath();

        $commandResult = new CommandResult();

        $process = $this->buildProcess(['vendor/bin/package-aggregator', $directory, '--compare'], PROJECT_ROOT . PATH_PREFIX);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        return $commandResult;
    }
}
