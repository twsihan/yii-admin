<?php

use yii\db\Migration;
use twsihan\admin\components\helpers\ParamsHelper;

/**
 * Class m180817_053731_menu
 */
class m180817_053731_menu extends Migration
{
    private $table = '{{%menu}}';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB comment "菜单表"';
        }

        $this->createTable($this->table, [
            'id' => $this->primaryKey()->notNull()->comment('主键'),
            'parent_id' => $this->integer(11)->notNull()->defaultValue(0)->comment('父类'),
            'name' => $this->string(64)->notNull()->comment('栏目'),
            'route' => $this->string(64)->notNull()->defaultValue('')->comment('地址'),
            'icon' => $this->string(32)->notNull()->defaultValue('icon-desktop')->comment('图标'),
            'sort' => $this->smallInteger(6)->defaultValue(0)->notNull()->comment('排序'),
            'data' => $this->binary()->comment('数据{Json}'),
            'status' => $this->boolean()->notNull()->defaultValue(1)->comment('状态'),
            'updated_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('修改时间'),
            'created_at' => $this->integer(11)->notNull()->defaultValue(0)->comment('创建时间'),
        ], $tableOptions);

        $this->initData([
            [
                'name' => '权限管理',
                'child' => [
                    ['name' => '管理员列表', 'route' => 'admins/index',],
                    ['name' => '创建管理员', 'route' => 'admins/create'],
                    ['name' => '菜单列表', 'route' => 'menus/index'],
                    ['name' => '创建菜单', 'route' => 'menus/create'],
                    ['name' => '角色列表', 'route' => 'roles/index'],
                    ['name' => '创建角色', 'route' => 'roles/create'],
                    ['name' => '权限列表', 'route' => 'items/index'],
                    ['name' => '创建权限', 'route' => 'items/create'],
                    ['name' => '规则列表', 'route' => 'rules/index'],
                ],
            ],
        ], ParamsHelper::module());
    }

    protected function initData($menu, $prefixRoute, $parent = 0)
    {
        if (is_array($menu)) {
            foreach ($menu as $item) {
                $child = null;
                if (isset($item['child'])) {
                    $child = $item['child'];

                    unset($item['child']);
                }

                $item['parent'] = $parent;
                $item['route'] = isset($item['route']) ? ($prefixRoute . '/' . $item['route']) : '';

                $this->insert($this->table, $item);

                if ($child !== null) {
                    $this->initData($child, $prefixRoute, $this->db->getLastInsertID());
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->table);

        echo "m180817_053731_menu cannot be reverted.\n";

        return false;
    }
}
