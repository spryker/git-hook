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
use GitHook\Command\FileCommand\PreCommit\ArchitectureSniff\ArchitectureSniffConfiguration;
use GitHook\Helper\ProcessBuilderHelper;

class ArchitectureCheckCommand implements CommandInterface
{
    use ProcessBuilderHelper;

    /**
     * @var bool[]
     */
    protected $processedDirectories;

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
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(CommandContextInterface $context)
    {
        $configuration = new ArchitectureSniffConfiguration($context->getCommandConfig('architecture'));
        $commandResult = new CommandResult();

        $directory = dirname($context->getFile());

        if (isset($this->processedDirectories[$directory])) {
            return $commandResult;
        }

        $processDefinition = [
            'vendor/bin/phpmd',
            $directory,
            'xml',
            'vendor/spryker/architecture-sniffer/src/ruleset.xml',
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

        $this->processedDirectories[$directory] = true;

        return $commandResult;
    }
}
