<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
    public function buildProcess(array $processDefinition, string $projectRoot = PROJECT_ROOT)
    {
        $process = new Process($processDefinition, $projectRoot);

        return $process;
    }
}
