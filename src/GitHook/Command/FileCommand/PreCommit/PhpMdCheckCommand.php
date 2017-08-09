<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\FileCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Command\FileCommand\PreCommit\PhpMd\PhpMdCheckConfiguration;
use GitHook\Helper\ProcessBuilderHelper;

class PhpMdCheckCommand implements CommandInterface
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
            ->setName('PHP Mess detector check.')
            ->setDescription('Checks the PHPMd rules.')
            ->setAcceptedFileExtensions('php');

        return $commandConfiguration;
    }

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(CommandContextInterface $context)
    {
        $configuration = new PhpMdCheckConfiguration($context->getCommandConfig('phpmd'));
        $commandResult = new CommandResult();

        $processDefinition = [
            'vendor/bin/phpmd',
            $context->getFile(),
            'xml',
            'vendor/spryker/spryker/Bundles/Development/src/Spryker/Zed/Development/Business/PhpMd/ruleset.xml',
            '--minimumpriority',
            $configuration->getMinimumPriority(),
        ];

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
