<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command\FileCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\CommandResultInterface;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Helper\ProcessBuilderHelper;
use UnexpectedValueException;

class CodeStyleCheckCommand implements CommandInterface
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
            ->setName('CodeStyle check')
            ->setDescription('Checks for not automatically fixable CodeStyle bugs.')
            ->setAcceptedFileExtensions('php');

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

        $standard = $this->findStandard(__DIR__);
        $processDefinition = ['vendor/bin/phpcs', $context->getFile(), '--standard=' . $standard];
        $process = $this->buildProcess($processDefinition);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        return $commandResult;
    }

    /**
     * @param string $directory
     *
     * @throws \UnexpectedValueException if no file found
     *
     * @return string
     */
    private function findStandard(string $directory): string
    {
        $candidates = [
            implode(DIRECTORY_SEPARATOR, [PROJECT_ROOT, 'config', 'ruleset.xml']),
            implode(DIRECTORY_SEPARATOR, [PROJECT_ROOT, 'phpcs.xml']),
        ];

        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                return $candidate;
            }
        }

        throw new UnexpectedValueException('Unable to locate phpcs standard');
    }
}
