<?php

use dee\angular\Angular;
use biz\client\ModuleAsset;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $module biz\client\Module */
$module = Yii::$app->controller->module;

if ($module->masterUrl !== null) {
    Yii::$app->getAssetManager()->assetMap[Yii::getAlias('@biz/client/assets/js/master.app.js', false)] = $module->masterUrl;
}
$options = Json::htmlEncode($module->clientOptions);

ModuleAsset::register($this);
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
        '/sales' => [
            'view' => 'sales/index',
            'di' => ['Sales',],
        ],
        '/sales/view/:id' => [
            'view' => 'sales/view',
            'di' => ['Sales',],
        ],
        '/sales/update/:id' => [
            'view' => 'sales/update',
            'di' => ['Sales',],
        ],
        '/sales/create' => [
            'view' => 'sales/create',
            'di' => ['Sales',],
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
        '/movement/create' => [
            'view' => 'movement/create',
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