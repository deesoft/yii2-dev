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
    protected $baseDir;

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

    protected function saveExtensions(array $extensions)
    {
        $file = $this->vendorDir . '/' . self::EXTENSION_FILE;
        if (!file_exists(dirname($file))) {
            mkdir(dirname($file), 0777, true);
        }
        $baseDir = $this->baseDir;
        if (strpos($this->vendorDir . '/', $baseDir . '/') === 0) {
            $ch = substr($this->vendorDir, strlen($baseDir));
            if(strpos($ch, '..') === false){
                $c = substr_count($ch, '/');
                $dir = '$vendorDir';
                for($i=0;$i<$c;$i++){
                    $dir = "dirname($dir)";
                }
            }  else {
                $dir = var_export($this->vendorDir,true);
            }
        }  else {
            $dir = var_export($this->vendorDir,true);
        }
        $array = str_replace("'<vendor-dir>", '$vendorDir . \'', var_export($extensions, true));
        $array = str_replace("'<base-dir>", '$baseDir . \'', var_export($extensions, true));
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
    
    public static function apply(CommandEvent $event)
    {
        $composer = $event->getComposer();
        $installer = new static($event->getIO(), $composer);
        $installer->addPackage($composer->getPackage());
    }
}