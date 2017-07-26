<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GithubHook\Command\FileCommand\PreCommit;

use GithubHook\Command\CommandConfigurationInterface;
use GithubHook\Command\CommandResult;
use GithubHook\Command\FileCommand\FileCommandInterface;
use GithubHook\Helper\ProcessBuilderHelper;
use Symfony\Component\Process\ProcessBuilder;

class CodeStyleFixCommand implements FileCommandInterface
{

    use ProcessBuilderHelper;

    /**
     * @param \GithubHook\Command\CommandConfigurationInterface $commandConfiguration
     *
     * @return \GithubHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $commandConfiguration)
    {
        $commandConfiguration
            ->setName('CodeStyle fixer')
            ->setDescription('Fixes all fixable CodeStyle bugs.')
            ->setAcceptedFileExtensions('php');

        return $commandConfiguration;
    }

    /**
     * @param string $file
     *
     * @return \GithubHook\Command\CommandResult
     */
    public function run($file)
    {
        $commandResult = new CommandResult();

        $processDefinition = ['vendor/bin/phpcbf', $file, '--standard=vendor/spryker/code-sniffer/Spryker/ruleset.xml'];
        $process = $this->buildProcess($processDefinition);
        $process->run();

        return $commandResult;
    }

}
