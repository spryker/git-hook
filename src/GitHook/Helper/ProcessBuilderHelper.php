<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Helper;

use Symfony\Component\Process\Process;

trait ProcessBuilderHelper
{
    /**
     * @param array $processDefinition
     * @param string $projectRoot
     *
     * @return \Symfony\Component\Process\Process
     */
    public function buildProcess(array $processDefinition, string $projectRoot = PROJECT_ROOT): Process
    {
        $process = new Process($processDefinition, $projectRoot);

        return $process;
    }
}
