<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\rbac\Item;
use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * Class RoleForm
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class RoleForm extends Model
{
    public $name;
    public $description;
    public $items;
    public $rules;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['name', 'description', 'rules'],
                'required',
                'on' => ['save', 'update'],
                'message' => '*必填',
            ],
            [
                'name',
                function ($attribute) {
                    $exists = (new Query())->from(Yii::$app->getAuthManager()->itemTable)
                        ->where('name = :name and type = :type', [':name' => $this->$attribute, ':type' => Item::TYPE_ROLE])
                        ->exists();
                    if ($exists) {
                        $this->addError($attribute, '属性已存在，请重新输入！~');
                    }
                },
                'on' => ['save'],
            ],
        ];
    }

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
