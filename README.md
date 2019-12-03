Yii 2 Admin
===========
~~~
php yii migrate --migrationPath=@yii/rbac/migrations
php yii migrate --migrationPath=@twsihan/admin/migrations
~~~

Config Set
==========
~~~
'modules' => [
    'admin' => 'twsihan\admin\Module',
],
'components' => [
    'authManager' => [
        'class' => 'twsihan\admin\components\rbac\DbManager',
    ],
    'user' => [
        'class' => 'yii\web\User',
        'enableAutoLogin' => false,
        'loginUrl' => null,
    ],
],
'as access' => [
    'class' => 'twsihan\admin\components\filters\AccessControl',
    'allowAction' => [
        'admin/admin/profile',
        'admin/default/*',
        'site/*',
    ],
],
~~~

URl Route
=========
~~~
<?php

$pluralize = true;

return [
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => $pluralize,
        'controller' => ['admin/admin'],
        'extraPatterns' => [
            'PUT,PATCH profile' => 'profile',
            'PUT,PATCH password' => 'password',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => $pluralize,
        'controller' => ['admin/default'],
        'extraPatterns' => [
            'POST login' => 'login',
            'POST logout' => 'logout',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => $pluralize,
        'controller' => ['admin/item'],
        'extraPatterns' => [
            'GET parent' => 'parent',
            'DELETE' => 'delete',
            'PUT,PATCH' => 'update',
            'GET,HEAD view' => 'view',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => $pluralize,
        'controller' => ['admin/menu'],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => $pluralize,
        'controller' => ['admin/role'],
        'extraPatterns' => [
            'GET auth' => 'auth',
            'DELETE' => 'delete',
            'PUT,PATCH' => 'update',
            'GET,HEAD view' => 'view',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'pluralize' => $pluralize,
        'controller' => ['admin/rule'],
    ],
];
~~~
