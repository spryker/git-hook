<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace GitHook\Composer\Scripts;

use Composer\Script\Event;

class HookInstaller
{

    /**
     * @var array
     */
    protected static $projectHooks = [
        'pre-commit',
    ];

    /**
     * @var array
     */
    protected static $sprykerHooks = [
        'pre-commit',
        // This one is pretty expensive, do not use it for now.
//        'pre-push',
    ];

    /**
     * @param \Composer\Script\Event $event
     *
     * @return bool
     */
    public static function installProjectHooks(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $hookDirectory = $vendorDir . '/spryker/git-hook/hooks/project/';
        $gitHookDirectory = $vendorDir . '/../.git/hooks/';

        foreach (static::$projectHooks as $hook) {
            $src = $hookDirectory . $hook;
            $dist = $gitHookDirectory . $hook;

            copy($src, $dist);
            chmod($dist, 0755);

            $event->getIO()->write(sprintf('<info>Copied "%s" to "%s"</info>', $src, $dist));
        }

        return true;
    }

    /**
     * @param \Composer\Script\Event $event
     *
     * @return bool
     */
    public static function installSprykerHooks(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $hookDirectory = $vendorDir . '/spryker/git-hook/hooks/spryker/';
        $gitHookDirectory = $vendorDir . '/spryker/spryker/.git/hooks/';

        foreach (static::$sprykerHooks as $hook) {
            $src = $hookDirectory . $hook;
            $dist = $gitHookDirectory . $hook;
            copy($src, $dist);
            chmod($dist, 0755);

            $event->getIO()->write(sprintf('<info>Copied "%s" to "%s"</info>', $src, $dist));
        }

        return true;
    }

}