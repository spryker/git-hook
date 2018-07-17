<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command;

interface CommandConfigurationInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function setDescription($description);

    /**
     * @return array
     */
    public function getAcceptedFileExtensions();

    /**
     * @param string|array $fileExtensions
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function setAcceptedFileExtensions($fileExtensions);
}
