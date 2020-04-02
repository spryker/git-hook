<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command\FileCommand\PreCommit\PhpMd;

use Exception;

class PhpMdCheckConfiguration
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var string[]
     */
    protected $defaultRuleSetPathsByPriority = [
        'config/phpmd-ruleset.xml',
        'vendor/spryker/spryker/Bundles/Development/src/Spryker/Zed/Development/Business/PhpMd/ruleset.xml',
        'vendor/spryker/development/src/Spryker/Zed/Development/Business/PhpMd/ruleset.xml',
    ];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return int
     */
    public function getMinimumPriority(): int
    {
        $defaultPriority = 2;
        if (!$this->config) {
            return $defaultPriority;
        }

        return (isset($this->config['priority'])) ? $this->config['priority'] : $defaultPriority;
    }

    /**
     * @return string
     */
    public function getRulesetPath(): string
    {
        return $this->config['rulesetPath'] ?? $this->getDefaultRulesetPath();
    }

    /**
     * @throws \Exception
     *
     * @return string
     */
    protected function getDefaultRulesetPath(): string
    {
        foreach ($this->defaultRuleSetPathsByPriority as $ruleSetPath) {
            if (file_exists(PROJECT_ROOT . DIRECTORY_SEPARATOR . $ruleSetPath)) {
                return $ruleSetPath;
            }
        }

        throw new Exception('Ruleset file was not found in any location: ' . PHP_EOL . implode(PHP_EOL, $this->defaultRuleSetPathsByPriority));
    }
}
