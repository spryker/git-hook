<?php declare(strict_types = 1);

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command;

class CommandConfiguration implements CommandConfigurationInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description = '';

    /**
     * @var array
     */
    protected $acceptedFileExtensions = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function setName(string $name): CommandConfigurationInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function setDescription(string $description): CommandConfigurationInterface
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array
     */
    public function getAcceptedFileExtensions(): array
    {
        return $this->acceptedFileExtensions;
    }

    /**
     * @param string|array $fileExtensions
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function setAcceptedFileExtensions($fileExtensions): CommandConfigurationInterface
    {
        $this->acceptedFileExtensions = (array)$fileExtensions;

        return $this;
    }

}
