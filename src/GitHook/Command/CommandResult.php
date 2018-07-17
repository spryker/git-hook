<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $error
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return ($this->message === null && $this->error === null);
    }
}
