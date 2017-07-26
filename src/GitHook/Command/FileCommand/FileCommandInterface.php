<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\FileCommand;

use GitHook\Command\CommandInterface;

interface FileCommandInterface extends CommandInterface
{

    /**
     * @param string $file
     *
     * @return \GitHook\Command\CommandResult
     */
    public function run($file);

}
