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
            'injection' => ['data', '$modal'],
            'resolve' => ['data' => 'js:ResolveRuleQuery']
        ],
    ],
    'templates' => [
        'form' => [
            'visible' => false,
            'view' => 'form',
            'js' => 'form.js',
            'injection' => ['$modalInstance', 'Rule', 'model'],
        ],
        'view' => [
            'view' => 'view',
            'js' => 'view.js',
            'injection' => ['$modalInstance', '$sce', 'model'],
        ],
    ]
]);
