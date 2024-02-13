<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Command\FileCommand\PreCommit;

use GitHook\Command\CommandConfigurationInterface;
use GitHook\Command\CommandInterface;
use GitHook\Command\CommandResult;
use GitHook\Command\CommandResultInterface;
use GitHook\Command\Context\CommandContextInterface;
use GitHook\Helper\ProcessBuilderHelper;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * The script traverses through the project directory structure, collecting dependencies and devDependencies
 * from all package.json files, excluding those within node_modules directories.
 * The gathered dependencies are stored in the package-aggregated.txt file.
 *
 * The compare mode conducts a comparison between the generated dependency list (without saving)
 * and the existing package-aggregated.txt file. Any difference between the lists result in an error.
 */
class PackageAggregatorCommand implements CommandInterface
{
    use ProcessBuilderHelper;

    /**
     * @param \GitHook\Command\CommandConfigurationInterface $commandConfiguration
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $commandConfiguration): CommandConfigurationInterface
    {
        $commandConfiguration
            ->setName('Package Aggregator')
            ->setDescription('Aggregates package.json dependencies across the project.')
            ->setAcceptedFileExtensions('php');

        return $commandConfiguration;
    }

    /**
     * @param \GitHook\Command\Context\CommandContextInterface $context
     *
     * @return \GitHook\Command\CommandResultInterface
     */
    public function run(CommandContextInterface $context): CommandResultInterface
    {
        $commandResult = new CommandResult();

        $directory = $context->getProjectPath();

        $processDefinition = [
            'vendor/bin/package-aggregator',
            $directory,
        ];

        $process = $this->buildProcess($processDefinition);
        $process->run();

        if (!$process->isSuccessful()) {
            $commandResult
                ->setError(trim($process->getErrorOutput()))
                ->setMessage(trim($process->getOutput()));
        }

        return $commandResult;
    }
}
