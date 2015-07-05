<?php

namespace biz\client;

use Yii;
use yii\helpers\Url;
use yii\base\BootstrapInterface;

/**
 * Description of Module
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 3.0
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public $layout = 'main';

    /**
     * @var type 
     */
    public $clientOptions = [];
    
    /**
     * @var type
     */
    public $masterUrl;

    public function init()
    {
        parent::init();
        $this->clientOptions = array_merge([
            'baseUrl' => Yii::$app->homeUrl,
            'apiPrefix' => Url::to(['/api']) . '/',
            ], $this->clientOptions);
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\web\Application) {
            
        }
    }
}