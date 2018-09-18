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
    ];

    /**
     * @var array
     */
    protected static $sprykerShopHooks = [
        'pre-commit',
    ];

    /**
     * @var array
     */
    protected static $ecoHooks = [
        'pre-commit',
    ];

    /**
     * @var array
     */
    protected static $gitHookHooks = [
        'pre-commit',
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
            $src = realpath($hookDirectory . $hook);
            $dist = realpath($gitHookDirectory) . '/' . $hook;

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
            $src = realpath($hookDirectory . $hook);
            $dist = realpath($gitHookDirectory) . '/' . $hook;

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
    public static function installSprykerShopHooks(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $hookDirectory = $vendorDir . '/spryker/git-hook/hooks/spryker-shop/';
        $gitHookDirectory = $vendorDir . '/spryker/spryker-shop/.git/hooks/';

        foreach (static::$sprykerShopHooks as $hook) {
            $src = realpath($hookDirectory . $hook);
            $dist = realpath($gitHookDirectory) . '/' . $hook;

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
    public static function installGitHook(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');

        $hookDirectory = $vendorDir . '/spryker/git-hook/hooks/git-hook/';
        $gitHookDirectory = $vendorDir . '/../.git/hooks/';

        foreach (static::$gitHookHooks as $hook) {
            $src = realpath($hookDirectory . $hook);
            $dist = realpath($gitHookDirectory) . '/' . $hook;

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
    public static function installEcoHooks(Event $event)
    {
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');

        $ecoBaseDirectory = $vendorDir . '/spryker-eco/';
        $hookDirectory = $vendorDir . '/spryker/git-hook/hooks/eco/';
        $modulesDirs = array_filter(glob($ecoBaseDirectory . '*'), 'is_dir');

        foreach (static::$ecoHooks as $hook) {
            $src = realpath($hookDirectory . DIRECTORY_SEPARATOR . $hook);
            foreach ($modulesDirs as $dirname) {
                $dist = realpath($dirname . '/.git/hooks') . DIRECTORY_SEPARATOR . $hook;

                copy($src, $dist);
                chmod($dist, 0755);

                $event->getIO()->write(sprintf('<info>Copied "%s" to "%s"</info>', $src, $dist));
            }
        }

        return true;
    }
}
