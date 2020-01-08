<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command;

use GitHook\Command\CommandResultInterface as CommandCommandResultInterface;

interface CommandResultInterface
{
    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage(string $message): CommandCommandResultInterface;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @param string $error
     *
     * @return $this
     */
    public function setError(string $error): CommandCommandResultInterface;

    /**
     * @return string
     */
    public function getError(): string;

    /**
     * @return bool
     */
    public function isSuccess(): bool;
}
