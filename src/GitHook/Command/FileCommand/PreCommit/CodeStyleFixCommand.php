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

class CodeStyleFixCommand implements CommandInterface
{
    use ProcessBuilderHelper;

    /**
     * @var string
     */
    protected const BINARY_STYLE_FIXER = 'vendor/bin/phpcbf';

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
        $commandResult = new CommandResult();

        $processDefinition = [
            static::BINARY_STYLE_FIXER,
            $context->getFile(),
            $this->getStandardRulesetOption(),
        ];

        $process = $this->buildProcess($processDefinition);
        $process->run();

        return $commandResult;
    }

    /**
     * @return string
     */
    protected function getStandardRulesetOption(): string
    {
        $filepath = static::RULESET_LEGACY_PATH;
        if (file_exists(PROJECT_ROOT . DIRECTORY_SEPARATOR . static::RULESET_PATH)) {
            $filepath = static::RULESET_PATH;
        }

        return sprintf('%s=%s', static::OPTION_STANDARD, $filepath);
    }
}
