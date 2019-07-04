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
     *
     * @return \Symfony\Component\Process\Process
     */
    public function buildProcess(array $processDefinition)
    {
        $process = new Process($processDefinition, PROJECT_ROOT);

        return $process;
    }
}
