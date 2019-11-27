<?php

namespace twsihan\admin\models\entity;

use twsihan\admin\components\base\EntityModel;
use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rbac\Item;
use yii\db\Query;

/**
 * Class ItemEntity
 *
 * @package twsihan\admin\models\entity
 * @author twsihan <twsihan@gmail.com>
 */
class ItemEntity extends EntityModel
{
    public $type;
    public $parentName;
    public $name;
    public $description;


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'type' => '类型',
            'parentName' => '父级名称',
            'name' => '模块名称',
            'description' => '模块简介',
        ];
    }

    public static function itemGroup()
    {
        $authManager = ParamsHelper::getAuthManager();
        $parents = (new Query())->from($authManager->itemTable)
            ->where(['type' => [Item::TYPE_PERMISSION_GROUP]])
            ->all();

        $itemGroup = [];
        foreach ($parents as $parent) {
            $query = (new Query())->from("{$authManager->itemTable} a")
                ->leftJoin("{$authManager->itemChildTable} b", 'a.name = b.child')
                ->where('b.parent = :parent', [':parent' => $parent['name']])
                ->all();

            $parent['items'] = $query;
            $itemGroup[$parent['name']] = $parent;
        }

        return $itemGroup;
    }
}
