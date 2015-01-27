<?php

namespace dee\composer;

use Composer\Package\PackageInterface;
use Composer\Util\Filesystem;
use Composer\Script\CommandEvent;

/**
 * Configure
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Installer extends \yii\composer\Installer
{

    protected function generateDefaultAlias(PackageInterface $package)
    {
        $fs = new Filesystem;
        $autoload = $package->getAutoload();
        $baseDir = $fs->normalizePath(realpath(getcwd()));
        $aliases = [];

        if (!empty($autoload['psr-0'])) {
            foreach ($autoload['psr-0'] as $name => $path) {
                $name = str_replace('\\', '/', trim($name, '\\'));
                if (!$fs->isAbsolutePath($path)) {
                    $path = $baseDir . '/' . $path;
                }
                $path = $fs->normalizePath($path);
                $aliases["@$name"] = $path . '/' . $name;
            }
        }

        if (!empty($autoload['psr-4'])) {
            foreach ($autoload['psr-4'] as $name => $path) {
                $name = str_replace('\\', '/', trim($name, '\\'));
                if (!$fs->isAbsolutePath($path)) {
                    $path = $baseDir . '/' . $path;
                }
                $path = $fs->normalizePath($path);
                $aliases["@$name"] = $path;
            }
        }

        return $aliases;
    }
    
    public static function apply(CommandEvent $event)
    {
        $composer = $event->getComposer();
        $installer = new static($event->getIO(), $composer);
        $installer->addPackage($composer->getPackage());
    }
}