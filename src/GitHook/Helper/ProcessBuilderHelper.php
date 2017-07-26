<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Helper;

use Symfony\Component\Process\ProcessBuilder;

trait ProcessBuilderHelper
{

    /**
     * @param array $processDefinition
     *
     * @return \Symfony\Component\Process\Process
     */
    public function buildProcess(array $processDefinition)
    {
        $processBuilder = new ProcessBuilder($processDefinition);
        $processBuilder->setWorkingDirectory(PROJECT_ROOT);
        $process = $processBuilder->getProcess();

        return $process;
    }

}
