<?php

use yii\web\JsExpression;

/* @var $widget dee\angular\NgView */

$widget->add([
    'prefix'=>'site',
    'js'=>'js/main.js',
    'routes' => [
        '' => [
            'view' => 'index',
            'js' => 'js/index.js',
            'resolve' => [
                'test' => 'js:testResolve',
            ],
            'injection' => ['test'],
        ],
        'contact' => [
            'view' => 'contact',
        ],
        '/page/:page' => [
            'templateUrl' => new JsExpression('loadPage'),
        ],
    ],
]);
