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
class Bootstrap implements BootstrapInterface
{

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $file = Yii::getAlias('@vendor/' . Installer::BOOTSTRAP_FILE);
        $bootstraps = is_file($file) ? include($file) : [];

        foreach ($bootstraps as $bootstrap) {
            $component = Yii::createObject($bootstrap);
            if ($component instanceof BootstrapInterface) {
                $component->bootstrap($app);
            }
        }
    }
}