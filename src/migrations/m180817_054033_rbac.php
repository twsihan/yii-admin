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
                ['name' => 'menu/create', 'description' => '菜单添加'],
                ['name' => 'menu/delete', 'description' => '菜单删除'],
                ['name' => 'menu/index', 'description' => '菜单显示'],
                ['name' => 'menu/update', 'description' => '菜单修改'],
            ],
        ],
        [
            'parent' => ['name' => 'adminAuth', 'description' => '管理员权限'],
            'list' => [
                ['name' => 'admin/create', 'description' => '管理员添加'],
                ['name' => 'admin/delete', 'description' => '管理员删除'],
                ['name' => 'admin/index', 'description' => '管理员显示'],
                ['name' => 'admin/update', 'description' => '管理员修改'],
                ['name' => 'item/create', 'description' => '权限添加'],
                ['name' => 'item/delete', 'description' => '权限删除'],
                ['name' => 'item/index', 'description' => '权限显示'],
                ['name' => 'item/update', 'description' => '权限修改'],
                ['name' => 'role/create', 'description' => '角色添加'],
                ['name' => 'role/delete', 'description' => '角色删除'],
                ['name' => 'role/index', 'description' => '角色显示'],
                ['name' => 'role/update', 'description' => '角色修改'],
                ['name' => 'rule/index', 'description' => '规则显示'],
            ],
        ],
    ];

    protected $defaultPermissionList = [
        'menu/create',
        'menu/index',
        'menu/update',
        'admin/create',
        'admin/index',
        'admin/update',
    ];
}
