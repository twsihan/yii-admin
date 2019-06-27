<?php

namespace twsihan\admin\models\form;

use twsihan\admin\models\logic\ItemLogic;
use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * Class ItemForm
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class ItemForm extends Model
{
    public $type;
    public $parentName;
    public $name;
    public $description;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'parentName',
                function ($attribute) {
                    $exists = (new Query())->from(Yii::$app->getAuthManager()->itemTable)
                        ->where('name = :name and type = :type', [':name' => $this->$attribute, ':type' => 3])
                        ->exists();
                    if (!$exists) {
                        $this->addError($attribute, '属性不存在，请重新输入！~');
                    }
                },
                'on' => ['create', 'update'],
            ],
            [
                ['name', 'description'],
                'required',
                'on' => ['create', 'update'],
                'message' => '*必填',
            ],
            [
                'name',
                function ($attribute) {
                    $exists = (new Query())->from(Yii::$app->getAuthManager()->itemTable)
                        ->where('name = :name and type = :type', [':name' => $this->$attribute, ':type' => 2])
                        ->exists();
                    if ($exists) {
                        $this->addError($attribute, '属性已存在，请重新输入！~');
                    }
                },
                'on' => ['create'],
            ],
            [
                'type',
                'in',
                'range' => array_keys(ItemLogic::typeMap()),
                'on' => ['search'],
            ],
        ];
    }

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
}
