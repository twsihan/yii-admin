<?php

namespace twsihan\admin\models\form;

use twsihan\admin\models\mysql\Menu;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\helpers\Json;

/**
 * Class MenuForm
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class MenuForm extends Model
{
    public $parent;
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
                'name',
                'required',
                'message' => '{attribute}必填',
                'on' => ['create', 'update'],
            ],
            [
                'parent',
                function ($attribute) {
                    $exists = Menu::find()->where('id = :id AND route = ""', [':id' => $this->parent])->exists();
                    if (!$exists) {
                        $this->addError($attribute, '父类不存在，请重新选择！~');
                    }
                },
                'on' => ['create', 'update', 'search'],
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
                'on' => ['create', 'update'],
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
            'parent' => '父级',
            'name' => '名称',
            'route' => '路由',
            'icon' => '图标',
            'sort' => '排序',
            'data' => '参数',
        ];
    }
}
