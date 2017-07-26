<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Command;

interface CommandInterface
{

    /**
     * @param \GitHook\Command\CommandConfigurationInterface $configuration
     *
     * @return \GitHook\Command\CommandConfigurationInterface
     */
    public function configure(CommandConfigurationInterface $configuration);

}
