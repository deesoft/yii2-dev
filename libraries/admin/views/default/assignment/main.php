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
            'injection' => ['data', '$location', 'doFocus'],
            'resolve' => ['data' => 'js:ResolveAssignmnetQuery']
        ],
        ':id' => [
            'view' => 'view',
            'js' => 'view.js',
            'injection' => ['model', '$filter'],
            'resolve' => ['model' => 'js:ResolveAssignmnetView']
        ]
    ]
]);
