<?php

use dee\angular\Angular;
use biz\client\ModuleAsset;
use yii\helpers\Url;
use yii\helpers\Json;

/* @var $this yii\web\View */

ModuleAsset::register($this);

$options = Json::htmlEncode([
    'baseUrl'=>Yii::$app->homeUrl,
    'apiPrefix'=>Url::to(['/api']).'/',
]);

$this->registerJs("yii.app.initProperties({$options});", yii\web\View::POS_END);
?>
<?=
Angular::widget([
    'requires' => ['app.angular', 'ui.bootstrap',],
    'routes' => [
        '/site' => [
            'view' => 'site/index'
        ],
        '/purchase' => [
            'view' => 'purchase/index',
            'di' => ['Purchase',],
        ],
        '/purchase/view/:id' => [
            'view' => 'purchase/view',
            'di' => ['Purchase',],
        ],
        '/purchase/update/:id' => [
            'view' => 'purchase/update',
            'di' => ['Purchase',],
        ],
        '/purchase/create' => [
            'view' => 'purchase/create',
            'di' => ['Purchase',],
        ],
        '/movement' => [
            'view' => 'movement/index',
            'di' => ['Movement',],
        ],
        '/movement/view/:id' => [
            'view' => 'movement/view',
            'di' => ['Movement',],
        ],
        '/movement/update/:id' => [
            'view' => 'movement/update',
            'di' => ['Movement',],
        ],
        '/movement/create/:reff/:id' => [
            'view' => 'movement/create',
            'di' => ['Movement',],
        ],
    ],
    'defaultPath' => '/site',
    'jsFile' => 'main.js',
    'useNgApp' => false,
]);
?>