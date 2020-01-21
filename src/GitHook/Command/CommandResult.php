<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command;

class CommandResult implements CommandResultInterface
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var string
     */
    protected $error;

    /**
     * @param string $message
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function setMessage(string $message): CommandResultInterface
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $error
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function setError(string $error): CommandResultInterface
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return ($this->message === null && $this->error === null);
    }
}
