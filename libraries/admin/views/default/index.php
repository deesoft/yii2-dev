<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
$css = <<<CSS
.box-solid .form-control-feedback{
    color: #444;
}
CSS;
$this->registerCss($css);
?>
<?=
NgView::widget([
    'name'=>'dAdmin',
    'requires' => ['ui.bootstrap','ngResource','dee.angular'],
    'routes' => [
        '/assignment' => [
            'view' => 'assignment/index',
            'di' => ['Assignment'],
        ],
        '/assignment/:id' => [
            'view' => 'assignment/view',
            'di' => ['Assignment'],
        ],
        //
        '/role' => [
            'view' => 'role/index',
            'di' => ['Item'],
        ],
        '/role/create'=>[
            'show'=>false,
            'view'=>'role/create',
            'di'=>['$modalInstance','type','Item']
        ],
        '/role/:id*' => [
            'view' => 'role/view',
            'di' => ['Item'],
        ],
    ],
    'otherwise' => [
        'view'=>'not-found',
    ],
    'jsFile' => 'index.js',
    'useNgApp' => false,
]);
?>
<?php
$url = yii\helpers\Url::canonical();
$url = yii\helpers\Json::htmlEncode(rtrim($url, '/').'/');
$js = <<<JS
    dAdmin.prefixUrl = {$url};
        
JS;
$this->registerJs($js,  \yii\web\View::POS_END);
