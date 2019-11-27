<?php

namespace twsihan\admin\models\entity;

use twsihan\admin\components\base\EntityModel;

/**
 * Class RoleEntity
 *
 * @package twsihan\admin\models\entity
 * @author twsihan <twsihan@gmail.com>
 */
class RoleEntity extends EntityModel
{
    public $name;
    public $description;
    public $items;
    public $rules;


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '角色名称',
            'description' => '角色简介',
            'rules' => '角色权限',
        ];
    }
}
