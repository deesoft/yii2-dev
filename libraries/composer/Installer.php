<?php

namespace dee\composer;

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
class Installer implements EventSubscriberInterface
{
    const PACKAGE_FILE = 'deesoft/root_package.php';
    const EXTRA_SYMLINK = 'symlinks';
    const EXTRA_PERMISSION = 'permission';
    const EXTENSION_FILE = 'yiisoft/extensions.php';
    const EXTRA_BOOTSTRAP = 'bootstrap';

    protected $baseDir;
    protected $vendorDir;

    protected function setRootPackage(PackageInterface $package)
    {
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

    public static function generateSymlink(array $links)
    {
        foreach ($links as $dest => $src) {
            echo "symlink('$src', '$dest')...";
            if (!is_dir($dest) && !is_file($dest)) {
                symlink($src, $dest);
                echo "done.\n";
            } else {
                echo "file destination exists.\n";
            }
        }
    }

    /**
     * Sets the correct permission for the files and directories listed in the extra section.
     * @param array $paths the paths (keys) and the corresponding permission octal strings (values)
     */
    public static function setPermission(array $paths)
    {
        foreach ($paths as $path => $permission) {
            echo "chmod('$path', $permission)...";
            if (is_dir($path) || is_file($path)) {
                chmod($path, octdec($permission));
                echo "done.\n";
            } else {
                echo "file not found.\n";
            }
        }
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
        $array = str_replace("'<vendor-dir>", '$vendorDir . \'', var_export($extensions, true));
        file_put_contents($file, "<?php\n\n\$vendorDir = dirname(__DIR__);\n\nreturn $array;\n");
        // invalidate opcache of extensions.php if exists
        if (function_exists('opcache_invalidate')) {
            opcache_invalidate($file, true);
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
        $composer = $event->getComposer();
        $fs = new Filesystem;

        $this->baseDir = $fs->normalizePath(realpath(getcwd()));
        $this->vendorDir = rtrim($composer->getConfig()->get('vendor-dir'), '/');

        $rootPackage = $composer->getPackage();
        $this->setRootPackage($rootPackage, $event);

        if ($event->getName() === ScriptEvents::POST_INSTALL_CMD) {
            $extra = $rootPackage->getExtra();
            if (isset($extra[self::EXTRA_PERMISSION])) {
                static::setPermission($extra[self::EXTRA_PERMISSION]);
            }
            if (isset($extra[self::EXTRA_SYMLINK])) {
                static::generateSymlink($extra[self::EXTRA_SYMLINK]);
            }
        }

        $extensions = $this->loadExtensions();
        $packages = $composer->getRepositoryManager()->getLocalRepository()->findPackages('deesoft/yii2-composer');
        if (!empty($packages)) {
            /* @var $package PackageInterface */
            $package = reset($packages);
            $extensions[$package->getName()] = [
                'name' => $package->getName(),
                'version' => $package->getVersion(),
                'alias' => [
                    '@dee/composer' => '<vendor-dir>/deesoft/yii2-composer'
                ],
                'bootstrap' => 'dee\\composer\\Bootstrap',
            ];
        } else {
            unset($extensions['deesoft/yii2-composer']);
        }
        $this->saveExtensions($extensions);
    }
}