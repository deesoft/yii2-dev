<?php

namespace dee\composer;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Description of Bootstrap
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Bootstrap
{

    /**
     * @param \yii\base\Application $app
     */
    public static function run($app)
    {
        $file = Yii::getAlias('@vendor/deesoft/root_package.php');
        $package = is_file($file) ? include($file) : [];

        if (!empty($package['alias'])) {
            foreach ($package['alias'] as $name => $path) {
                Yii::setAlias($name, $path);
            }
        }
        if (isset($package['bootstrap'])) {
            foreach ((array) $package['bootstrap'] as $bootstrap) {
                $component = Yii::createObject($bootstrap);
                if ($component instanceof BootstrapInterface) {
                    $component->bootstrap($app);
                }
            }
        }
    }
}