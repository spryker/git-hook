<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Helper;

use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleHelper extends SymfonyStyle
{
    /**
     * @param string $hookName
     *
     * @return void
     */
    public function gitHookHeader(string $hookName): void
    {
        $output = PHP_EOL . PHP_EOL . '  ' . $hookName . PHP_EOL;
        $this->writeln(sprintf('<fg=white;options=bold;bg=green>%s</fg=white;options=bold;bg=green>', $output));
    }

    /**
     * @param string $name
     * @param string $description
     *
     * @return void
     */
    public function commandInfo(string $name, string $description): void
    {
        $this->newLine(3);
        $this->section($name);

        if ($description) {
            $this->comment($description);
        }
    }

    /**
     * @param array $messages
     *
     * @return void
     */
    public function errors(array $messages): void
    {
        $this->newLine(2);
        foreach ($messages as $message) {
            $message = PHP_EOL . PHP_EOL . $message . PHP_EOL;
            $this->writeln(sprintf('<fg=white;bg=red>%s</fg=white;bg=red>', $message));
        }
    }
}
