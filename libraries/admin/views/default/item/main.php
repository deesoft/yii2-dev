<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */

$widget->add([
    'js' => 'main.js',
    'routes' => [
        ':type' => [
            'view' => 'index',
            'js' => 'index.js',
            'injection' => ['data', 'Item', 'doFocus', '$modal', '$filter'],
            'resolve' => ['data' => 'js:ResolveRoleQuery']
        ],
        ':type/:id' => [
            'view' => 'view',
            'js' => 'view.js',
            'injection' => ['model','$filter'],
            'resolve' => ['model' => 'js:ResolveRoleView']
        ],
    ],
    'templates'=>[
        'form' => [
            'view' => 'form',
            'js' => 'form.js',
            'injection' => ['$modalInstance', 'type', 'Item', 'rules'],
        ]
    ]
]);
