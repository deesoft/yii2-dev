<?php

namespace dee\composer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Util\Filesystem;
use Composer\Script\CommandEvent;
use Composer\Script\ScriptEvents;

/**
 * Configure
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Installer extends LibraryInstaller
{
    const EXTRA_BOOTSTRAP = 'bootstrap';
    const EXTENSION_FILE = 'yiisoft/extensions.php';
    const EXTRA_SYMLINK = 'symlinks';
    const EXTRA_PERMISSION = 'permission';

    protected $baseDir;

    protected function addPackage(PackageInterface $package, CommandEvent $event = null)
    {
        $extension = [
            'name' => $package->getName(),
            'version' => $package->getVersion(),
        ];

        $alias = $this->generateDefaultAlias($package);
        if (!empty($alias)) {
            $extension['alias'] = $alias;
        }
        $extra = $package->getExtra();
        if (isset($extra[self::EXTRA_BOOTSTRAP])) {
            $extension['bootstrap'] = $extra[self::EXTRA_BOOTSTRAP];
        }
        $extensions = $this->loadExtensions();
        $extensions[$package->getName()] = $extension;
        $this->saveExtensions($extensions);

        if ($event && $event->getName() == ScriptEvents::POST_INSTALL_CMD) {
            $this->generateSymlink($package);
            $this->setPermission($package);
        }
    }

    protected function generateDefaultAlias(PackageInterface $package)
    {
        $fs = new Filesystem;
        $autoload = $package->getAutoload();
        if ($this->baseDir === null) {
            $this->baseDir = $fs->normalizePath(realpath(getcwd()));
        }
        $baseDir = $this->baseDir;

        $aliases = [];

        if (!empty($autoload['psr-0'])) {
            foreach ($autoload['psr-0'] as $name => $path) {
                $name = str_replace('\\', '/', trim($name, '\\'));
                if (!$fs->isAbsolutePath($path)) {
                    $path = $baseDir . '/' . $path;
                }
                $path = $fs->normalizePath($path);
                if (strpos($path . '/', $baseDir . '/') === 0) {
                    $aliases["@$name"] = '<base-dir>' . substr($path, strlen($baseDir)) . '/' . $name;
                } else {
                    $aliases["@$name"] = $path . '/' . $name;
                }
            }
        }

        if (!empty($autoload['psr-4'])) {
            foreach ($autoload['psr-4'] as $name => $path) {
                $name = str_replace('\\', '/', trim($name, '\\'));
                if (!$fs->isAbsolutePath($path)) {
                    $path = $baseDir . '/' . $path;
                }
                $path = $fs->normalizePath($path);
                if (strpos($path . '/', $baseDir . '/') === 0) {
                    $aliases["@$name"] = '<base-dir>' . substr($path, strlen($baseDir));
                } else {
                    $aliases["@$name"] = $path;
                }
            }
        }

        return $aliases;
    }

    protected function loadExtensions()
    {
        $file = $this->vendorDir . '/' . self::EXTENSION_FILE;
        if (!is_file($file)) {
            return [];
        }
        // invalidate opcache of extensions.php if exists
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($file, true);
        }
        $extensions = require($file);

        $vendorDir = str_replace('\\', '/', $this->vendorDir);
        $n = strlen($vendorDir);

        foreach ($extensions as &$extension) {
            if (isset($extension['alias'])) {
                foreach ($extension['alias'] as $alias => $path) {
                    $path = str_replace('\\', '/', $path);
                    if (strpos($path . '/', $vendorDir . '/') === 0) {
                        $extension['alias'][$alias] = '<vendor-dir>' . substr($path, $n);
                    }
                }
            }
        }

        return $extensions;
    }

    protected function saveExtensions(array $extensions)
    {
        $file = $this->vendorDir . '/' . self::EXTENSION_FILE;
        if (!file_exists(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }
        $baseDir = $this->baseDir;
        if (strpos($this->vendorDir . '/', $baseDir . '/') === 0) {
            $ch = substr($this->vendorDir, strlen($baseDir));
            if (strpos($ch, '..') === false) {
                $c = substr_count($ch, '/');
                $dir = '$vendorDir';
                for ($i = 0; $i < $c; $i++) {
                    $dir = "dirname($dir)";
                }
            } else {
                $dir = var_export($this->vendorDir, true);
            }
        } else {
            $dir = var_export($this->vendorDir, true);
        }
        $array = var_export($extensions, true);
        $array = str_replace("'<vendor-dir>", '$vendorDir . \'', $array);
        $array = str_replace("'<base-dir>", '$baseDir . \'', $array);
        $content = <<<FILE
<?php 

\$vendorDir = dirname(__DIR__);
\$baseDir = $dir;
    
return $array;

FILE;
        file_put_contents($file, $content);
        // invalidate opcache of extensions.php if exists
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($file, true);
        }
    }

    protected function generateSymlink(PackageInterface $package)
    {
        $extra = $package->getExtra();
        if (isset($extra[self::EXTRA_SYMLINK])) {
            $fs = new Filesystem;
            $baseDir = $this->baseDir;
            foreach ($extra[self::EXTRA_SYMLINK] as $dest => $src) {
                if (!$fs->isAbsolutePath($dest)) {
                    $dest = $baseDir . '/' . $dest;
                }
                echo "symlink('$src', '$dest')...";
                if (!is_dir($dest) && !is_file($dest)) {
                    if (!$fs->isAbsolutePath($src)) {
                        $src = $baseDir . '/' . $src;
                    }
                    symlink($src, $dest);
                    echo "done.\n";
                } else {
                    echo "file destination exists.\n";
                }
            }
        }
    }

    protected function setPermission(PackageInterface $package)
    {
        $extra = $package->getExtra();
        if (isset($extra[self::EXTRA_PERMISSION])) {
            $fs = new Filesystem;
            $baseDir = $this->baseDir;
            foreach ($extra[self::EXTRA_PERMISSION] as $path => $permission) {
                if (!$fs->isAbsolutePath($path)) {
                    $path = $baseDir . '/' . $path;
                }
                echo "chmod('$path', $permission)...";
                if (is_dir($path) || is_file($path)) {
                    chmod($path, octdec($permission));
                    echo "done.\n";
                } else {
                    echo "file not found.\n";
                }
            }
        }
    }

    public static function apply(CommandEvent $event)
    {
        $composer = $event->getComposer();
        $installer = new static($event->getIO(), $composer);
        $installer->addPackage($composer->getPackage(), $event);
    }
}