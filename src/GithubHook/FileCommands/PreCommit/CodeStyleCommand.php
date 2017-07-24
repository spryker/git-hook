<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHubHook\FileCommands\PreCommit;

use GitHubHook\Helper\ProcessBuilderHelper;
use Symfony\Component\Console\Output\OutputInterface;

class CodeStyleCommand implements FileCommandInterface
{

    use ProcessBuilderHelper;

    /**
     * @param string $file
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return bool
     */
    public function run($file, OutputInterface $output)
    {
        $this->fixCodeStyle($file);

        $processDefinition = ['vendor/bin/console', 'code:sniff', $file];
        $process = $this->buildProcess($processDefinition);
        $process->run();

        if (!$process->isSuccessful()) {
            $output->writeln(sprintf('<error>%s</error>', trim($process->getErrorOutput())));
            $output->writeln(sprintf('<info>%s</info>', trim($process->getOutput())));

            return false;
        }

        return true;
    }

    /**
     * @param string $file
     *
     * @return void
     */
    protected function fixCodeStyle($file)
    {
        $processDefinition = ['vendor/bin/console', 'code:sniff', '-f', $file];
        $process = $this->buildProcess($processDefinition);
        $process->run();
    }

}
