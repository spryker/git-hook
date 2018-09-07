<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Helper;

trait CommittedFilesHelper
{
    /**
     * @return array
     */
    public function getCommittedFiles()
    {
        $output = [];
        $check = 0;

        exec('git rev-parse --verify HEAD 2> /dev/null', $output, $check);

        $against = '4b825dc642cb6eb9a060e54bf8d69288fbee4904';
        if ($check === 0) {
            $against = 'HEAD';
        }

        exec('git diff-index --cached --name-status ' . $against . ' | egrep \'^(A|M)\' | awk \'{print $2;}\'', $committedFiles);

        $prepareFilePathCallback = function ($file) {
            return '.' . PATH_PREFIX . $file;
        };

        $committedFiles = array_map($prepareFilePathCallback, $committedFiles);

        $filterFilesCallback = function ($file) {
            return is_file(PROJECT_ROOT . DIRECTORY_SEPARATOR . $file);
        };

        $committedFiles = array_filter($committedFiles, $filterFilesCallback);

        return $committedFiles;
    }

    /**
     * @param array $configExcludedDirs
     * @param array $configExcludedFiles
     *
     * @return array
     */
    public function getFilteredCommittedFiles(array $configExcludedDirs, array $configExcludedFiles): array
    {
        $committedFiles = $this->getCommittedFiles();
        $excludedFiles = [];

        foreach ($committedFiles as $key => $committedFile) {
            if ($this->isFileExcluded($committedFile, $configExcludedDirs, $configExcludedFiles)) {
                $excludedFiles[] = $committedFiles[$key];
                unset($committedFiles[$key]);
            }
        }

        return [$committedFiles, $excludedFiles];
    }

    /**
     * @param string $filePath
     * @param array $excludedDirs
     * @param array $excludedFiles
     *
     * @return bool
     */
    protected function isFileExcluded(string $filePath, array $excludedDirs, array $excludedFiles): bool
    {
        $fileRealPath = realpath($filePath);
        $fileDir = dirname($fileRealPath);

        foreach ($excludedDirs as $excludedDir) {
            if (realpath($excludedDir) === $fileDir) {
                return true;
            }
        }

        foreach ($excludedFiles as $excludedFile) {
            if (realpath($excludedFile) === $fileRealPath) {
                return true;
            }
        }

        return false;
    }
}
