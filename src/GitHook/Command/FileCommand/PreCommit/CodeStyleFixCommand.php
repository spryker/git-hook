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
use Symfony\Component\Process\Process;

class CodeStyleFixCommand implements CommandInterface
{
    use ProcessBuilderHelper;

    /**
     * @var string
     */
    protected const BINARY_STYLE_FIXER = 'vendor/bin/phpcbf';

    /**
     * @var int
     */
    protected const EXIT_CODE_SUCCESS = 0;

    /**
     * @var string
     */
    protected const ERROR_FILE_DOES_NOT_EXIST = 'Please add root configuration file. Configuration file does not exist';

    /**
     * @var string
     */
    protected const OPTION_STANDARD = '--standard';

    /**
     * @var string
     */
    protected const RULESET_PATH = 'phpcs.xml';

    /**
     * @var string
     */
    protected const RULESET_LEGACY_PATH = 'config/ruleset.xml';

    /**
     * @param \GitHook\Command\CommandConfigurationInterface $commandConfiguration
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $commandConfiguration): CommandConfigurationInterface
    {
        $commandConfiguration
            ->setName('CodeStyle fixer')
            ->setDescription('Fixes all fixable CodeStyle bugs.')
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
        $processDefinition = [
            static::BINARY_STYLE_FIXER,
            $context->getFile(),
        ];

        $standardOption = $this->getStandardRulesetOption();
        if ($standardOption !== null) {
            $processDefinition[] = $standardOption;
        }

        $process = $this->buildProcess($processDefinition);
        $process->run();

        return $this->createCommandResult($process);
    }

    /**
     * @param \Symfony\Component\Process\Process $process
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    protected function createCommandResult(Process $process): CommandResultInterface
    {
        $commandResult = new CommandResult();

        if ($process->getExitCode() === static::EXIT_CODE_SUCCESS) {
            return $commandResult;
        }

        $errorMessage = $this->getErrorMessage($process);

        $commandResult
            ->setError($errorMessage)
            ->setMessage($process->getOutput());

        return $commandResult;
    }

    /**
     * @param \Symfony\Component\Process\Process $process
     *
     * @return string
     */
    protected function getErrorMessage(Process $process): string
    {
        $errorMessage = $process->getOutput();
        if (strpos($errorMessage, static::RULESET_LEGACY_PATH) !== null) {
            $errorMessage = sprintf(
                '%s: %s',
                static::ERROR_FILE_DOES_NOT_EXIST,
                static::RULESET_LEGACY_PATH,
            );
        }

        return $errorMessage;
    }

    /**
     * @return string|null
     */
    protected function getStandardRulesetOption(): ?string
    {
        if (file_exists(PROJECT_ROOT . DIRECTORY_SEPARATOR . static::RULESET_PATH)) {
            return null;
        }

        return sprintf('%s=%s', static::OPTION_STANDARD, static::RULESET_LEGACY_PATH);
    }
}
