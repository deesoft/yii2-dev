<?php

namespace dee;

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
    protected $bootstraps = [
        'dee\\angular\\Bootstrap',
        'dee\\console\\Bootstrap',
    ];

    public function bootstrap($app)
    {
        foreach ($this->bootstraps as $bootstrap) {
            $component = Yii::createObject($bootstrap);
            if ($component instanceof BootstrapInterface) {
                $component->bootstrap($app);
            }
        }
    }
}