<?php declare(strict_types = 1);

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
    public function setName(string $name): CommandConfigurationInterface;

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function setDescription(string $description): CommandConfigurationInterface;

    /**
     * @return array
     */
    public function getAcceptedFileExtensions(): array;

    /**
     * @param string|array $fileExtensions
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function setAcceptedFileExtensions($fileExtensions): CommandConfigurationInterface;

}
