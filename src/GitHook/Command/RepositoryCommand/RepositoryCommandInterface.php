<?php declare(strict_types = 1);

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command\RepositoryCommand;

use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResultInterface;

interface RepositoryCommandInterface extends CommandInterface
{

    /**
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(): CommandResultInterface;

}
