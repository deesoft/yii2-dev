<?php

use dee\angular\NgView;

/* @var $this yii\web\View */
/* @var $widget NgView */

$widget->add([
    'js' => 'main.js',
    'routes' => [
        '' => [
            'view' => 'index',
            'js' => 'index.js',
            'injection' => ['Route'],
        ],
    ],
    'templates' => [
        'form' => [              // modal
            'view' => 'form',
            'js' => 'form.js',
            'injection' => ['$modalInstance', 'Route']
        ],
    ]
]);
