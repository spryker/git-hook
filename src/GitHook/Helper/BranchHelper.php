<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace GitHook\Helper;

trait BranchHelper
{
    /**
     * @return string
     */
    public function getBranch(): string
    {
        return exec('git branch | grep \* | cut -d \' \' -f2 2> /dev/null');
    }
}
