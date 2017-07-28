<?php declare(strict_types = 1);

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command;

interface CommandResultInterface
{

    /**
     * @param string $message
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function setMessage(string $message): CommandResultInterface;

    /**
     * @return string
     */
    public function getMessage();
    /**
     * @param string $error
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function setError(string $error): CommandResultInterface;

    /**
     * @return string
     */
    public function getError(): string;

    /**
     * @return bool
     */
    public function isSuccess(): bool;

}
