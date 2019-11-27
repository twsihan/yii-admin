<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rbac\DbManager;
use twsihan\admin\components\rbac\Item;
use twsihan\admin\models\entity\RoleEntity;
use Yii;
use yii\db\Exception;
use yii\db\Query;

/**
 * Class RoleForm
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class RoleForm extends RoleEntity
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['name', 'description', 'rules'],
                'required',
            ],
        ];
    }

    public function handle($id)
    {
        if ($this->validate()) {
            /* @var DbManager $authManager */
            $authManager = ParamsHelper::getAuthManager();
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $role = $authManager->createRole($this->name);
                $role->description = $this->description;
                if ($id) {
                    $authManager->update($id, $role);
                    $authManager->removeChildren($role);
                } else {
                    $exists = (new Query())->from($authManager->itemTable)
                        ->where('name = :name and type = :type', [':name' => $this->name, ':type' => Item::TYPE_ROLE])
                        ->exists();
                    if ($exists) {
                        $this->addError('name', '属性已存在，请重新输入！~');
                        $transaction->rollBack();
                        return false;
                    }
                    $authManager->add($role);
                }

                $rules = $this->rules;
                $parent = isset($rules['parent']) ? $rules['parent'] : [];
                $child = isset($rules['child']) ? $rules['child'] : [];
                foreach ($parent as $rule) {
                    $item = $authManager->createPermission($rule);
                    $authManager->addChild($role, $item);
                }
                foreach ($child as $index => $value) {
                    if (!array_key_exists($index, $parent)) {
                        foreach ($value as $rule) {
                            $item = $authManager->createPermission($rule);
                            $authManager->addChild($role, $item);
                        }
                    }
                }

                $transaction->commit();

                return true;
            } catch (Exception $e) {
                Yii::error($e, __FUNCTION__);

                $transaction->rollBack();
            }
        }
        return false;
    }
}
