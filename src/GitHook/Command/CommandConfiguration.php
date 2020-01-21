<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
