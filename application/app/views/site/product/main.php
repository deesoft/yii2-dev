<?php
/* @var $widget dee\angular\NgView */
$widget->add([
    'prefix' => 'product',
    'js' => 'js/main.js',
    'routes' => [
        '' => [
            'view' => 'index',
            'js' => 'js/index.js',
            'injection' => ['Product', 'data'],
            'resolve' => ['data' => 'js:resolves.product.query'],
        ],
        'new' => [
            'view' => 'create',
            'js' => 'js/create.js',
            'injection' => ['Product',],
        ],
        ':id/edit' => [
            'view' => 'update',
            'js' => 'js/update.js',
            'injection' => ['Product', 'model'],
            'resolve' => ['model' => 'js:resolves.product.view'],
        ],
        ':id' => [
            'view' => 'view',
            'js' => 'js/view.js',
            'injection' => ['Product', 'model'],
            'resolve' => ['model' => 'js:resolves.product.view'],
        ],
    ],
]);
