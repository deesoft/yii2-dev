<?php

namespace dee\composer;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Package\PackageInterface;
use Composer\Util\Filesystem;
use Composer\Script\CommandEvent;
use Composer\Script\ScriptEvents;
use Composer\EventDispatcher\EventSubscriberInterface;

/**
 * Configure
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Installer extends \yii\composer\Installer implements EventSubscriberInterface
{
    const PACKAGE_FILE = 'deesoft/root_package.php';
    const EXTRA_SYMLINK = 'symlinks';
    const EXTRA_PERMISSION = 'permission';

    protected $baseDir;

    protected function setRootPackage(PackageInterface $package, CommandEvent $event = null)
    {
        $fs = new Filesystem;
        $this->baseDir = $fs->normalizePath(realpath(getcwd()));
        $info = [
            'name' => $package->getName(),
            'version' => $package->getVersion(),
        ];

        $alias = $this->generatePackagetAlias($package);
        if (!empty($alias)) {
            $info['alias'] = $alias;
        }
        $extra = $package->getExtra();

        if (isset($extra[self::EXTRA_BOOTSTRAP])) {
            $info['bootstrap'] = $extra[self::EXTRA_BOOTSTRAP];
        }
        $this->savePackage($info);

        $extensions = $this->loadExtensions();
        $this->saveExtensions($extensions);

        $this->generateSymlink($package);
        $this->setPermission($package);
    }

    protected function generatePackagetAlias(PackageInterface $package)
    {
        $fs = new Filesystem;
        $autoload = $package->getAutoload();
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

    protected function savePackage($info)
    {
        $file = $this->vendorDir . '/' . self::PACKAGE_FILE;
        if (!file_exists(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }
        $baseDir = $this->baseDir;
        if (strpos($this->vendorDir . '/', $baseDir . '/') === 0) {
            $child = substr($this->vendorDir, strlen($baseDir));
            if (strpos($child, '..') === false) {
                $c = substr_count($child, '/');
                $dir = 'dirname(__DIR__)';
                for ($i = 0; $i < $c; $i++) {
                    $dir = "dirname($dir)";
                }
            } else {
                $dir = var_export($this->baseDir, true);
            }
        } else {
            $dir = var_export($this->baseDir, true);
        }
        $array = var_export($info, true);
        $array = str_replace("'<base-dir>", '$baseDir . \'', $array);
        file_put_contents($file, "<?php\n\n\$baseDir = $dir;\n\nreturn $array;");
        // invalidate opcache of extensions.php if exists
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($file, true);
        }
    }

    protected function saveExtensions(array $extensions)
    {
        $file = $this->vendorDir . '/' . self::EXTENSION_FILE;
        if (!file_exists(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }
        $array = str_replace("'<vendor-dir>", '$vendorDir . \'', var_export($extensions, true));
        file_put_contents($file, "<?php\n\n\dee\composer\Bootstrap::run(\$this);\n\$vendorDir = dirname(__DIR__);\n\nreturn $array;\n");
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
                echo "symlink('$src', '$dest')...";
                if (!$fs->isAbsolutePath($dest)) {
                    $dest = $baseDir . '/' . $dest;
                }
                if (!$fs->isAbsolutePath($src)) {
                    $src = $baseDir . '/' . $src;
                }
                if (!is_dir($dest) && !is_file($dest)) {
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

    public static function getSubscribedEvents()
    {
        return array(
            ScriptEvents::POST_INSTALL_CMD => 'apply',
            ScriptEvents::POST_UPDATE_CMD => 'apply'
        );
    }

    public function apply(CommandEvent $event)
    {
        $package = $event->getComposer()->getPackage();
        $this->setRootPackage($package, $event);
    }
}