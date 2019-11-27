<?php

return [
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['admin/admin'],
        'extraPatterns' => [
            'POST create' => 'create',
            'DELETE delete' => 'delete',
            'POST update' => 'update',
            'GET index' => 'index',
            'POST edit-profile' => 'edit-profile',
            'POST reset-password' => 'reset-password',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['admin/default'],
        'extraPatterns' => [
            'POST login' => 'login',
            'POST logout' => 'logout',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['admin/item'],
        'extraPatterns' => [
            'GET parent' => 'parent',
            'POST create' => 'create',
            'DELETE delete' => 'delete',
            'POST update' => 'update',
            'GET index' => 'index',
            'GET view' => 'view',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['admin/menu'],
        'extraPatterns' => [
            'POST create' => 'create',
            'DELETE delete' => 'delete',
            'POST update' => 'update',
            'GET index' => 'index',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['admin/role'],
        'extraPatterns' => [
            'POST create' => 'create',
            'DELETE delete' => 'delete',
            'POST update' => 'update',
            'GET index' => 'index',
            'GET auth' => 'auth',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => ['admin/rule'],
        'extraPatterns' => [
            'GET index' => 'index',
        ],
    ],
];
