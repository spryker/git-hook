<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command;

use GitHook\Command\Context\CommandContextInterface;

interface CommandInterface
{
    /**
     * @param \GitHook\Command\CommandConfigurationInterface $configuration
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $configuration): CommandConfigurationInterface;

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(CommandContextInterface $context): CommandResultInterface;
}
