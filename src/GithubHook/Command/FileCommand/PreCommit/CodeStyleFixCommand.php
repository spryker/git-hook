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
            ->setDescription('Fixes all fixable CodeStyle bugs. !!! If you run commit command with "--no-verify (-n)" CodeStyle fixer has no effect, you need to run it by yourself.');

        return $commandConfiguration;
    }

    /**
     * @param string $file
     *
     * @return bool|\GithubHook\Command\CommandResult
     */
    public function run($file)
    {
        $commandResult = new CommandResult();

        $processDefinition = ['vendor/bin/console', 'code:sniff', '-f', $file];
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
