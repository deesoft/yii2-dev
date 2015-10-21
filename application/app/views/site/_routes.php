<?php
return [
    '/' => [
        'redirectTo' => '/site/',
    ],
    'site/main',
    '/user/login' => [
        'visible' => false,
        'view' => 'user/login',
        'js' => 'user/js/login.js',
        'injection' => ['$modalInstance', '$http'],
    ],
    '/user/signup' => [
        'view' => 'user/signup',
        'js' => 'user/js/signup.js',
        'injection' => ['$http'],
    ],
    'purchase/main',
    '/sales' => [
        'view' => 'sales/index',
        'js' => 'sales/js/index.js',
        'injection' => ['Sales',],
    ],
    '/sales/new' => [
        'view' => 'sales/create',
        'js' => 'sales/js/create.js',
        'injection' => ['Sales',],
    ],
    '/sales/:id/edit' => [
        'view' => 'sales/update',
        'js' => 'sales/js/update.js',
        'injection' => ['Sales',],
    ],
    '/sales/:id' => [
        'view' => 'sales/view',
        'js' => 'sales/js/view.js',
        'injection' => ['Sales',],
    ],
    '/transfer' => [
        'view' => 'transfer/index',
        'js' => 'transfer/js/index.js',
        'injection' => ['Transfer',],
    ],
    '/transfer/new' => [
        'view' => 'transfer/create',
        'js' => 'transfer/js/create.js',
        'injection' => ['Transfer',],
    ],
    '/transfer/:id/edit' => [
        'view' => 'transfer/update',
        'js' => 'transfer/js/update.js',
        'injection' => ['Transfer',],
    ],
    '/transfer/:id' => [
        'view' => 'transfer/view',
        'js' => 'transfer/js/view.js',
        'injection' => ['Transfer'],
    ],
    '/movement' => [
        'view' => 'movement/index',
        'js' => 'movement/js/index.js',
        'injection' => ['Movement',],
    ],
    '/movement/new' => [
        'view' => 'movement/create',
        'js' => 'movement/js/create.js',
        'injection' => ['Movement',],
    ],
    '/movement/new/:reff/:id' => [
        'view' => 'movement/create',
        'js' => 'movement/js/create.js',
        'injection' => ['Movement',],
    ],
    '/movement/:id/edit' => [
        'view' => 'movement/update',
        'js' => 'movement/js/update.js',
        'injection' => ['Movement',],
    ],
    '/movement/:id' => [
        'view' => 'movement/view',
        'js' => 'movement/js/view.js',
        'injection' => ['Movement',],
    ],
    'product/main',
    'otherwise' => [
        'view' => 'site/error'
    ],
];
