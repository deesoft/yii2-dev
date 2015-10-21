<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */

$widget->add([
    'prefix' => 'menu',
    'js' => 'main.js',
    'routes' => [
        '' => [
            'view' => 'index',
            'js' => 'index.js',
            'injection' => ['data', 'Item', 'doFocus', '$modal', '$filter'],
            'resolve' => ['data' => 'ResolveMenuQuery']
        ],
        'form' => [
            'visible' => false,
            'view' => 'form',
            'js' => 'form.js',
            'injection' => ['$modalInstance', 'type', 'Item', 'rules'],
        ]
    ]
]);
