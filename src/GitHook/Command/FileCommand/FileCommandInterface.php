<?php declare(strict_types = 1);

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\FileCommand;

use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResult;

interface FileCommandInterface extends CommandInterface
{

    /**
     * @param string $file
     *
     * @return \GitHook\Command\CommandResult
     */
    public function run(string $file): CommandResult;

}
