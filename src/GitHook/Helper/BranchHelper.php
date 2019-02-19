<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Helper;

trait BranchHelper
{
    /**
     * @return string
     */
    public function getBranch()
    {
        return exec('git branch | grep \* | cut -d \' \' -f2 2> /dev/null');
    }
}
