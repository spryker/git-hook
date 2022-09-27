<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Helper;

trait CommittedFilesHelper
{
    /**
     * @return array
     */
    public function getCommittedFiles(): array
    {
        $output = [];
        $check = 0;

        exec('git rev-parse --verify HEAD 2> /dev/null', $output, $check);

        $against = '4b825dc642cb6eb9a060e54bf8d69288fbee4904';
        if ($check === 0) {
            $against = 'HEAD';
        }

        $committedFiles = $this->getAffectedFiles($against);

        $prepareFilePathCallback = function ($file) {
            return '.' . PATH_PREFIX . $file;
        };

        $committedFiles = array_map($prepareFilePathCallback, $committedFiles);

        $filterFilesCallback = function ($file) {
            return is_file(PROJECT_ROOT . DIRECTORY_SEPARATOR . $file);
        };

        return array_filter($committedFiles, $filterFilesCallback);
    }

    /**
     * @param string $revision
     *
     * @return array<string>
     */
    protected function getAffectedFiles(string $revision): array
    {
        exec(sprintf('git diff --name-only --diff-filter=AM %s', $revision), $committedFiles);

        if (!$this->isMergeInProcess()) {
            return $committedFiles;
        }

        exec(sprintf('git diff --name-only --diff-filter=AM MERGE_HEAD...%s', $revision), $mergeConflictFiles);
        exec(sprintf('git diff --name-only --diff-filter=AM %s MERGE_HEAD', $revision), $mergeFiles);

        return array_merge(array_diff($committedFiles, $mergeFiles), $mergeConflictFiles);
    }

    /**
     * @return bool
     */
    protected function isMergeInProcess(): bool
    {
        return trim((string)exec('git rev-parse -q --verify MERGE_HEAD')) !== '';
    }
}
