Yii 2 Admin
===========
~~~
php yii migrate --migrationPath=@yii/rbac/migrations
php yii migrate --migrationPath=@twsihan/admin/migrations
~~~

~~~
'modules' => [
    'admin' => [
        'class' => 'twsihan\admin\Module',
        'layout' => '@twsihan/admin/views/layouts/main',
        'user' => 'user',
        /*
        'controllerMap'   => [ // 重写方法
            'admin' => [
                'class' => 'app\controllers\AdminController',
                'viewPath' => '@app/views/admin',
            ],
        ],
        */
    ],
],
'as access' => [
    'class' => 'twsihan\admin\components\filters\Access',
    'allowAction' => [
        'admin/admin/profile',
        'admin/default/*',
        'site/*',
        '*',
    ],
],
'components' => [
    'authManager' => [
        'class' => 'twsihan\admin\components\rbac\DbManager',
    ],
    'user' => [
        'class' => 'yii\web\User',
        'identityClass' => 'twsihan\admin\models\mysql\Admin',
        'enableAutoLogin' => true,
        'loginUrl' => ['/admin/default/login'],
        'idParam' => '_adminId',
        'identityCookie' => ['name' => '_admin', 'httpOnly' => true],
    ],
],
~~~
