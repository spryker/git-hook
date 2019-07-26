<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\RepositoryCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Helper\ProcessBuilderHelper;

class ValidateBranchNameCommand implements CommandInterface
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
            ->setName('Validate git branch name to be lowercased')
            ->setDescription('Abort commit if branch name is not lowercased.');

        return $commandConfiguration;
    }

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(CommandContextInterface $context)
    {
        $commandResult = new CommandResult();

        $process = $this->buildProcess(['git', 'rev-parse', '--abbrev-ref', 'HEAD'], PROJECT_ROOT . PATH_PREFIX);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        $branchName = trim($process->getOutput());
        if ($branchName !== mb_strtolower($branchName)) {
            $commandResult
                ->setMessage(sprintf('The branch "%s" must contain only lowercase letters. Please create a new branch with a valid name.', $branchName));
        }

        return $commandResult;
    }
}
