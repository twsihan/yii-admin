<?php

namespace twsihan\admin\models\entity;

use twsihan\admin\components\base\EntityModel;
use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\models\mysql\Menu;
use yii\base\InvalidArgumentException;
use yii\db\Query;
use yii\helpers\Json;

/**
 * Class MenuEntity
 *
 * @package twsihan\admin\models\entity
 * @author twsihan <twsihan@gmail.com>
 */
class MenuEntity extends EntityModel
{
    public $parentId;
    public $name;
    public $route;
    public $icon;
    public $sort;
    public $data;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'parentId',
                function ($attribute) {
                    $exists = Menu::find()->where('id = :id AND route = ""', [':id' => $this->parentId])->exists();
                    if (!$exists) {
                        $this->addError($attribute, '父类不存在，请重新选择！~');
                    }
                },
            ],
            [
                'name',
                'required',
            ],
            [
                'data',
                function ($attribute) {
                    try {
                        Json::decode($this->data);
                    } catch (InvalidArgumentException $e) {
                        $this->addError($attribute, 'Json 格式不对！~');
                    }
                },
            ],
            [['route', 'icon', 'sort', 'data'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parentId' => '父级',
            'name' => '名称',
            'route' => '路由',
            'icon' => '图标',
            'sort' => '排序',
            'data' => '参数',
        ];
    }

    public static function parentExists($id)
    {
        return (new Query())->from(ParamsHelper::getMenuTable())->where('id = :id AND route = ""', [':id' => $id])->exists();
    }
}
