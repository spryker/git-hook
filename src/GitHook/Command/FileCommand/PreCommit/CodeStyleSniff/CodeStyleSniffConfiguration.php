<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command\FileCommand\PreCommit\CodeStyleSniff;

use GitHook\Command\Context\CommandContextInterface;
use Symfony\Component\Yaml\Parser;

class CodeStyleSniffConfiguration
{
    /**
     * @var string
     */
    protected const MODULE_CONFIG_TOOL_KEY = 'code-sniffer';

    /**
     * @var string
     */
    protected const MODULE_CONFIG_LEVEL = 'level';

    /**
     * @var string
     */
    protected const CORE_MODULE_PATH_REGEX = '#.*/vendor/spryker/.+/Bundles/.+/(src|tests)/#';

    /**
     * @var int
     */
    protected const CS_STRICT_LEVEL = 2;

    /**
     * @var string
     */
    protected const DEVELOPMENT_MODULE = 'Development';

    /**
     * @var string
     */
    protected const SPRYKER_FOLDER = 'spryker';

    /**
     * @var string
     */
    protected const BUNDLES_FOLDER = 'Bundles';

    /**
     * @var string
     */
    protected const PHPCS_XML_FILE = 'phpcs.xml';

    /**
     * @var string
     */
    protected const RULESET_XML_FILE = 'ruleset.xml';

    /**
     * @var string
     */
    protected const RULESET_STRICT_XML_FILE = 'rulesetStrict.xml';

    /**
     * @var string
     */
    protected const TOOLING_YML_FILE = 'tooling.yml';

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return string
     */
    public function resolvePath(CommandContextInterface $context): string
    {
        $modulePath = $this->getModulePath($context);

        if ($modulePath === null) {
            return PROJECT_ROOT . DIRECTORY_SEPARATOR . static::PHPCS_XML_FILE;
        }

        $phpcsFilePath = dirname($modulePath) . DIRECTORY_SEPARATOR . static::PHPCS_XML_FILE;
        if (file_exists($phpcsFilePath)) {
            return $phpcsFilePath;
        }

        $moduleConfiguration = $this->getModuleConfiguration(dirname($modulePath) . DIRECTORY_SEPARATOR . static::TOOLING_YML_FILE);
        if ($this->isStrictLevelConfigured($moduleConfiguration)) {
            return $this->getPathToDevelopmentModule($modulePath) . static::RULESET_STRICT_XML_FILE;
        }

        return $this->getPathToDevelopmentModule($modulePath) . static::RULESET_XML_FILE;
    }

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return string|null
     */
    protected function getModulePath(CommandContextInterface $context): ?string
    {
        preg_match(static::CORE_MODULE_PATH_REGEX, $context->getFile(), $matches);

        return $matches[0] ?? null;
    }

    /**
     * @param string $modulePath
     *
     * @return string
     */
    protected function getPathToDevelopmentModule(string $modulePath): string
    {
        return dirname($modulePath, 4) . DIRECTORY_SEPARATOR . static::SPRYKER_FOLDER . DIRECTORY_SEPARATOR . static::BUNDLES_FOLDER . DIRECTORY_SEPARATOR . static::DEVELOPMENT_MODULE . DIRECTORY_SEPARATOR;
    }

    /**
     * @param array<string, mixed> $moduleConfiguration
     *
     * @return bool
     */
    protected function isStrictLevelConfigured(array $moduleConfiguration): bool
    {
        if (isset($moduleConfiguration[static::MODULE_CONFIG_TOOL_KEY][static::MODULE_CONFIG_LEVEL])) {
            return $moduleConfiguration[static::MODULE_CONFIG_TOOL_KEY][static::MODULE_CONFIG_LEVEL] === static::CS_STRICT_LEVEL;
        }

        return false;
    }

    /**
     * @param string $configPath
     *
     * @return array<string, mixed>
     */
    protected function getModuleConfiguration(string $configPath): array
    {
        if (file_exists($configPath)) {
            return (new Parser())->parseFile($configPath);
        }

        return [];
    }
}
