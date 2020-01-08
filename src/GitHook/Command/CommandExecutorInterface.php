<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command;

use GitHook\Command\Context\CommandContextInterface;

interface CommandExecutorInterface
{
    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return bool
     */
    public function execute(CommandContextInterface $context): bool;
}
