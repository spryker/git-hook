<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command;

interface CommandConfigurationInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name);

    /**
     * @return string
     */
    public function getDescription(): string;

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description);

    /**
     * @return array
     */
    public function getAcceptedFileExtensions(): array;

    /**
     * @param array|string $fileExtensions
     *
     * @return $this
     */
    public function setAcceptedFileExtensions($fileExtensions);
}
