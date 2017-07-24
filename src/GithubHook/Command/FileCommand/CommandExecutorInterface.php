<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GithubHook\Command\FileCommand;

interface CommandExecutorInterface
{

    /**
     * @param \GithubHook\Command\FileCommand\FileCommandInterface[] $fileCommands
     * @param array $committedFiles
     *
     * @return void
     */
    public function execute();

}
