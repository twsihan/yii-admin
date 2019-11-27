Yii 2 Admin
===========
~~~
php yii migrate --migrationPath=@yii/rbac/migrations
php yii migrate --migrationPath=@twsihan/admin/migrations
~~~

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
