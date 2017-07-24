<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHubHook\FileCommands\PreCommit;

use Symfony\Component\Console\Output\OutputInterface;

interface FileCommandInterface
{
    /**
     * @param string $file
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return bool
     */
    public function run($file, OutputInterface $output);

}
