<?php
/* @var $widget dee\angular\NgView */
$widget->add([
    'prefix'=>'purchase',
    'js'=>'js/main.js',
    'routes' => [
        '' => [
            'view' => 'index',
            'js' => 'js/index.js',
            'injection' => ['Purchase','data'],
            'resolve'=>['data'=>'js:ResolvePurchaseQuery']
        ],
        'new' => [
            'view' => 'create',
            'js' => 'js/create.js',
            'injection' => ['Purchase',],
        ],
        ':id/edit' => [
            'view' => 'update',
            'js' => 'js/update.js',
            'injection' => ['Purchase',],
        ],
        ':id' => [
            'view' => 'view',
            'js' => 'js/view.js',
            'injection' => ['Purchase'],
        ],
    ],
]);
