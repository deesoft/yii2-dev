<?php

namespace libraries;

use Yii;

/**
 * Bootstrap
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class Bootstrap implements \yii\base\BootstrapInterface
{
    protected $bootstraps = [
//        'biz\\api\\Bootstrap',
    ];

    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        foreach ($this->bootstraps as $bootstrap) {
            $component = Yii::createObject($bootstrap);
            if ($component instanceof \yii\base\BootstrapInterface) {
                $component->bootstrap($app);
            }
        }
    }
}