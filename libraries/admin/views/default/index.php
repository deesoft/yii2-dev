<?php

use dee\angular\NgView;
use yii\web\View;
use yii\helpers\Url;

/* @var $this View */
$this->title = Yii::t('rbac-admin', 'RBAC Manager');
?>
<div ng-app="dAdmin">
    <ul class="nav nav-tabs">
        <li ng-repeat="menu in headerMenu" ng-class="{active:isRouteActive(menu.id)}">
            <a ng-href="{{menu.url}}" ng-bind="menu.label"></a>
        </li>
    </ul>
    <div style="padding-bottom: 10px;"></div>
    <?=
    NgView::widget([
        'name' => 'dAdmin',
        'requires' => ['ui.bootstrap', 'dee.ui', 'dee.rest'],
        'routes' => [
            '/' => [
                'redirectTo' => '/assignment'
            ],
            '/assignment' => 'assignment/main.php',
            '/item' => 'item/main.php',
            '/rule' => 'rule/main.php',
//            'route/main.php',
//            'menu/main.php',
        ],
        'js' => 'index.js',
        'useNgApp' => false,
        'clientOptions' => [
            'currentUrl' => rtrim(Url::to(['/' . Yii::$app->controller->module->uniqueId]), '/'),
            'headerMenus' => [
                '/assignment' => Yii::t('rbac-admin', 'Assignment'),
                '/item/1' => Yii::t('rbac-admin', 'Role'),
                '/item/2' => Yii::t('rbac-admin', 'Permission'),
                '/rule' => Yii::t('rbac-admin', 'Rule'),
                '/route' => Yii::t('rbac-admin', 'Route'),
                '/menu' => Yii::t('rbac-admin', 'Menu'),
            ],
        ]
    ]);
    ?>
</div>
<?php
$css = <<<CSS
.box-solid .form-control-feedback{
    color: #444;
}
CSS;
$this->registerCss($css);
