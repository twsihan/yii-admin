<?php

use yii\db\Migration;
use twsihan\yii\helpers\ArrayHelper;
use twsihan\admin\components\helpers\ParamsHelper;

/**
 * Class m180817_054033_rbac
 */
class m180817_054033_rbac extends Migration
{
    private $table = '{{%auth_item}}';
    private $itemTable = '{{%auth_item_child}}';
    private $assignmentTable = '{{%auth_assignment}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = time();
        $this->insert($this->table, [
            'name' => 'administrator',
            'type' => 1,
            'description' => '超级管理员',
            'updated_at' => $time,
            'created_at' => $time,
        ]);
        $this->insert($this->table, [
            'name' => 'admin',
            'type' => 1,
            'description' => '管理员',
            'updated_at' => $time,
            'created_at' => $time,
        ]);
        $prefix = ParamsHelper::module();
        foreach ($this->menuPermissionList as $value) {
            $this->insert($this->table, ArrayHelper::merge($value['parent'], [
                'type' => 3,
                'updated_at' => $time,
                'created_at' => $time,
            ]));
            $this->insert($this->itemTable, [
                'parent' => 'administrator',
                'child' => $value['parent']['name'],
            ]);
            foreach ($value['list'] as $permission) {
                $tmpPermissionName = $permission['name'];
                $permission['name'] = $prefix . '/' . $permission['name'];
                $this->insert($this->table, ArrayHelper::merge($permission, [
                    'type' => 2,
                    'updated_at' => $time,
                    'created_at' => $time,
                ]));
                $this->insert($this->itemTable, [
                    'parent' => $value['parent']['name'],
                    'child' => $permission['name'],
                ]);
                if (in_array($tmpPermissionName, $this->defaultPermissionList)) {
                    $this->insert($this->itemTable, [
                        'parent' => 'admin',
                        'child' => $permission['name'],
                    ]);
                }
            }
        }
        $this->batchInsert($this->assignmentTable, [
            'item_name',
            'user_id',
            'created_at'
        ], [
            ['administrator', 1, $time],
            ['admin', 2, $time],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180817_054033_rbac cannot be reverted.\n";
        return false;
    }

    protected $menuPermissionList = [
        [
            'parent' => ['name' => 'menuAuth', 'description' => '菜单权限'],
            'list' => [
                ['name' => 'menus/create', 'description' => '菜单添加'],
                ['name' => 'menus/delete', 'description' => '菜单删除'],
                ['name' => 'menus/index', 'description' => '菜单显示'],
                ['name' => 'menus/update', 'description' => '菜单修改'],
            ],
        ],
        [
            'parent' => ['name' => 'adminAuth', 'description' => '管理员权限'],
            'list' => [
                ['name' => 'admins/create', 'description' => '管理员添加'],
                ['name' => 'admins/delete', 'description' => '管理员删除'],
                ['name' => 'admins/index', 'description' => '管理员显示'],
                ['name' => 'admins/update', 'description' => '管理员修改'],
                ['name' => 'items/create', 'description' => '权限添加'],
                ['name' => 'items/delete', 'description' => '权限删除'],
                ['name' => 'items/index', 'description' => '权限显示'],
                ['name' => 'items/update', 'description' => '权限修改'],
                ['name' => 'roles/create', 'description' => '角色添加'],
                ['name' => 'roles/delete', 'description' => '角色删除'],
                ['name' => 'roles/index', 'description' => '角色显示'],
                ['name' => 'roles/update', 'description' => '角色修改'],
                ['name' => 'rules/index', 'description' => '规则显示'],
            ],
        ],
    ];

    protected $defaultPermissionList = [
        'menus/create',
        'menus/index',
        'menus/update',
        'admins/create',
        'admins/index',
        'admins/update',
    ];
}
