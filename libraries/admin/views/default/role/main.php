<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */

$widget->add([
    'prefix' => '',
    'js' => 'main.js',
    'routes' => [
        '/role' => [
            'view' => 'index',
            'js' => 'index.js',
            'injection' => ['data', 'Item', 'doFocus', '$modal', '$filter'],
            'resolve' => ['data' => 'js:ResolveRoleQuery']
        ],
        '/role/:id*' => [
            'view' => 'view',
            'js' => 'view.js',
            'injection' => ['model','$filter'],
            'resolve' => ['model' => 'js:ResolveRoleView']
        ],
        '/item/form' => [
            'visible' => false,
            'view' => 'form',
            'js' => 'form.js',
            'injection' => ['$modalInstance', 'type', 'Item', 'rules'],
            'resolve'=>['rules'=>'js:ResolveRuleQuery']
        ]
    ]
]);
