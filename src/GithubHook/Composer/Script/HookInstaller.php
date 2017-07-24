<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHubHook\Composer\Scripts;

use Composer\Script\Event;

class HookInstaller
{

    /**
     * @var array
     */
    protected static $hooks = [
        'pre-commit',
    ];

    /**
     * @param \Composer\Script\Event $event
     *
     * @return bool
     */
    public static function installHooks(Event $event)
    {
        self::installProjectHooks($event);
        self::installSprykerHooks($event);

        return true;
    }

    /**
     * @param \Composer\Script\Event $event
     *
     * @return void
     */
    protected static function installProjectHooks(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $hookDirectory = $vendorDir . '/spryker/github-hooks/hooks/';
        $gitHookDirectory = $vendorDir . '/../.git/hooks/';

        foreach (static::$hooks as $hook) {
            $src = $hookDirectory . $hook;
            $dist = $gitHookDirectory . $hookDirectory;
            copy($src, $dist);

            $event->getIO()->write('<info>Copied "' . $hook . '" to .git/hooks</info>');
        }
    }

    /**
     * @param \Composer\Script\Event $event
     *
     * @return void
     */
    protected static function installSprykerHooks(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $hookDirectory = $vendorDir . '/spryker/github-hooks/hooks/';
        $gitHookDirectory = $vendorDir . '/spryker/spryker/.git/hooks/';

        foreach (static::$hooks as $hook) {
            $src = $hookDirectory . $hook;
            $dist = $gitHookDirectory . $hookDirectory;
            copy($src, $dist);

            $event->getIO()->write('<info>Copied "' . $hook . '" to .git/hooks</info>');
        }
    }

}
