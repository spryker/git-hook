<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command\RepositoryCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\CommandResultInterface;
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
    public function configure(CommandConfigurationInterface $commandConfiguration): CommandConfigurationInterface
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
    public function run(CommandContextInterface $context): CommandResultInterface
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
