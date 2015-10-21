<?php

use yii\web\View;
use dee\angular\NgView;
use yii\helpers\Url;

//use yii\helpers\Html;

/* @var $this View */

$baseApiUrl = Yii::$app->params['rest.baseUrl'];
$this->registerJsFile($baseApiUrl . 'master');
$this->registerJsFile('@web/js/md5.js');
$this->registerCss($this->render('main.css'));

$this->params['sideMenu'] = require (__DIR__ . '/_menu.php');
?>
<div class="dee-view">
    <?=
    NgView::widget([
        'name' => 'dApp',
        'useNgApp' => false,
        'requires' => ['ngResource', 'ui.bootstrap', 'dee.ui', 'dee.rest'],
        'routes' => [
            '/' => [
                'redirectTo' => '/site'
            ],
            'site/main',
//            'purchase/main',
//            'product/main',
        ],
        'routeConfig' => [
            'injection' => ['$scope', '$injector'],
        ],
        'js' => ['_js/app.js', '_js/model.js', '_js/input.js'],
        'clientOptions' => [
            'loginUrl' => Url::to(['site/login']),
            'baseApiUrl' => $baseApiUrl,
            'token' => Yii::$app->user->isGuest ? null : Yii::$app->user->identity->token,
        ],
//    'remote' => true,
    ])
    ?>
    <div ng-if="onLoading" class="overlay"><i class="fa fa-refresh fa-spin"></i></div>
</div>
