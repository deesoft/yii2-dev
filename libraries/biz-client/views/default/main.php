<?php

use dee\angular\NgView;
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
NgView::widget([
    'requires' => ['app.angular', 'ui.bootstrap',],
    'routes' => [
        '/site' => [
            'view' => 'site/index'
        ],
        '/purchase' => [
            'view' => 'purchase/index',
            'di' => ['Purchase',],
        ],
        '/purchase/new' => [
            'view' => 'purchase/create',
            'di' => ['Purchase',],
        ],
        '/purchase/:id/edit' => [
            'view' => 'purchase/update',
            'di' => ['Purchase',],
        ],
        '/purchase/:id' => [
            'view' => 'purchase/view',
            'di' => ['Purchase'],
        ],
        '/sales' => [
            'view' => 'sales/index',
            'di' => ['Sales',],
        ],
        '/sales/new' => [
            'view' => 'sales/create',
            'di' => ['Sales',],
        ],
        '/sales/:id/edit' => [
            'view' => 'sales/update',
            'di' => ['Sales',],
        ],
        '/sales/:id' => [
            'view' => 'sales/view',
            'di' => ['Sales',],
        ],
        '/transfer' => [
            'view' => 'transfer/index',
            'di' => ['Transfer',],
        ],
        '/transfer/new' => [
            'view' => 'transfer/create',
            'di' => ['Transfer',],
        ],
        '/transfer/:id/edit' => [
            'view' => 'transfer/update',
            'di' => ['Transfer',],
        ],
        '/transfer/:id' => [
            'view' => 'transfer/view',
            'di' => ['Transfer'],
        ],
        '/movement' => [
            'view' => 'movement/index',
            'di' => ['Movement',],
        ],
        '/movement/new' => [
            'view' => 'movement/create',
            'di' => ['Movement',],
        ],
        '/movement/new/:reff/:id' => [
            'view' => 'movement/create',
            'di' => ['Movement',],
        ],
        '/movement/:id/edit' => [
            'view' => 'movement/update',
            'di' => ['Movement',],
        ],
        '/movement/:id' => [
            'view' => 'movement/view',
            'di' => ['Movement',],
        ],
    ],
    'defaultPath' => '/site',
    'jsFile' => 'main.js',
    'useNgApp' => false,
]);
?>