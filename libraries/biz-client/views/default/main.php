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
    'requires' => ['app.angular', 'ui.bootstrap', 'dee.angular'],
    'routes' => [
        '/site' => [
            'view' => 'site/index',
        ],
        '/purchase' => [
            'view' => 'purchase/index',
            'js' => 'purchase/index.js',
            'injection' => ['Purchase',],
        ],
        '/purchase/new' => [
            'view' => 'purchase/create',
            'js' => 'purchase/create.js',
            'injection' => ['Purchase',],
        ],
        '/purchase/:id/edit' => [
            'view' => 'purchase/update',
            'js' => 'purchase/update.js',
            'injection' => ['Purchase',],
        ],
        '/purchase/:id' => [
            'view' => 'purchase/view',
            'js' => 'purchase/view.js',
            'injection' => ['Purchase'],
        ],
        '/sales' => [
            'view' => 'sales/index',
            'js' => 'sales/index.js',
            'injection' => ['Sales',],
        ],
        '/sales/new' => [
            'view' => 'sales/create',
            'js' => 'sales/create.js',
            'injection' => ['Sales',],
        ],
        '/sales/:id/edit' => [
            'view' => 'sales/update',
            'js' => 'sales/update.js',
            'injection' => ['Sales',],
        ],
        '/sales/:id' => [
            'view' => 'sales/view',
            'js' => 'sales/view.js',
            'injection' => ['Sales',],
        ],
        '/transfer' => [
            'view' => 'transfer/index',
            'js' => 'transfer/index.js',
            'injection' => ['Transfer',],
        ],
        '/transfer/new' => [
            'view' => 'transfer/create',
            'js' => 'transfer/create.js',
            'injection' => ['Transfer',],
        ],
        '/transfer/:id/edit' => [
            'view' => 'transfer/update',
            'js' => 'transfer/update.js',
            'injection' => ['Transfer',],
        ],
        '/transfer/:id' => [
            'view' => 'transfer/view',
            'js' => 'transfer/view.js',
            'injection' => ['Transfer'],
        ],
        '/movement' => [
            'view' => 'movement/index',
            'js' => 'movement/index.js',
            'injection' => ['Movement',],
        ],
        '/movement/new' => [
            'view' => 'movement/create',
            'js' => 'movement/create.js',
            'injection' => ['Movement',],
        ],
        '/movement/new/:reff/:id' => [
            'view' => 'movement/create',
            'js' => 'movement/create.js',
            'injection' => ['Movement',],
        ],
        '/movement/:id/edit' => [
            'view' => 'movement/update',
            'js' => 'movement/update.js',
            'injection' => ['Movement',],
        ],
        '/movement/:id' => [
            'view' => 'movement/view',
            'js' => 'movement/view.js',
            'injection' => ['Movement',],
        ],
        'otherwise' => [
            'view' => 'site/error'
        ]
    ],
    'js' => 'main.js',
    'useNgApp' => false,
]);
?>