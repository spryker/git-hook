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
    protected const CORE_MODULE_PATH_REGEX = '#.*/vendor/spryker/.+/Bundles/.+/src/#';

    /**
     * @var int
     */
    protected const CS_STRICT_LEVEL = 2;

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return string
     */
    public function resolvePath(CommandContextInterface $context): string
    {
        $modulePath = $this->getModulePath($context);

        if ($modulePath === null) {
            return 'config/ruleset.xml';
        }

        $moduleConfiguration = $this->getModuleConfiguration(dirname($modulePath) . DIRECTORY_SEPARATOR . 'tooling.yml');

        if ($this->isStrictLevelConfigured($moduleConfiguration)) {
            return 'vendor/spryker/code-sniffer/SprykerStrict/ruleset.xml';
        }

        return 'vendor/spryker/code-sniffer/Spryker/ruleset.xml';
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
     * @param array<string, string> $moduleConfiguration
     *
     * @return bool
     */
    protected function isStrictLevelConfigured(array $moduleConfiguration): bool
    {
        return (isset($moduleConfiguration[static::MODULE_CONFIG_TOOL_KEY]['level'])
            && $moduleConfiguration[static::MODULE_CONFIG_TOOL_KEY]['level'] === static::CS_STRICT_LEVEL);
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
