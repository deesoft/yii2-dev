<?php
namespace dee\composer;

use Composer\Package\PackageInterface;
use Composer\Util\Filesystem;

/**
 * Configure
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Installer extends \yii\composer\Installer
{

    public static function apply($event)
    {
        $composer = $event->getComposer();
        $installer = new static($event->getIO(), $composer);
        $installer->addPackage($composer->getPackage());
    }
    
    protected function generateDefaultAlias(PackageInterface $package)
    {
        $fs = new Filesystem;
        $autoload = $package->getAutoload();

        $aliases = [];

        if (!empty($autoload['psr-0'])) {
            foreach ($autoload['psr-0'] as $name => $path) {
                $name = str_replace('\\', '/', trim($name, '\\'));
                if (!$fs->isAbsolutePath($path)) {
                    $path = $package->getTargetDir() . '/' . $path;
                }
                $path = $fs->normalizePath($path);
                $aliases["@$name"] = $path . '/' . $name;
            }
        }

        if (!empty($autoload['psr-4'])) {
            foreach ($autoload['psr-4'] as $name => $path) {
                $name = str_replace('\\', '/', trim($name, '\\'));
                if (!$fs->isAbsolutePath($path)) {
                    $path = $package->getTargetDir() . '/' . $path;
                }
                $path = $fs->normalizePath($path);
                $aliases["@$name"] = $path;
            }
        }

        return $aliases;
    }
}