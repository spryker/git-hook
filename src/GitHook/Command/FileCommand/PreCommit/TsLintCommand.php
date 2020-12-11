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

class TsLintCommand implements CommandInterface
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
            ->setName('TsLint')
            ->setDescription('Fix style/formatting of ts files according to tslint rules.')
            ->setAcceptedFileExtensions('ts');

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
        $isZedUiModule = strpos($context->getFile(), 'ZedUi');
        $isMerchantPortalModule = strpos($context->getFile(), 'MerchantPortalGui');

        if ($isZedUiModule || $isMerchantPortalModule) {
            $processDefinition = ['node', './frontend/libs/tslint', '--fix', '--config', 'tsconfig.mp.json', '--config-lint', 'tslint.mp-githook.json', '--file-path',  $context->getFile()];
        }
        else {
            $processDefinition = ['node', './frontend/libs/tslint', '--fix', '--config-lint', 'tslint-githook.json', '--file-path',  $context->getFile()];
        }

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
