<?php

namespace twsihan\admin\models\logic;

use twsihan\yii\helpers\ArrayHelper;
use twsihan\admin\models\form\RoleForm;
use Yii;
use yii\db\Exception;
use yii\db\Query;

/**
 * Class RoleLogic
 *
 * @package twsihan\admin\models\logic
 * @author twsihan <twsihan@gmail.com>
 */
class RoleLogic extends RoleForm
{


    /**
     * @param string $name
     * @return array
     */
    public static function select($name = '')
    {
        $result = (new Query())->from(Yii::$app->getAuthManager()->itemTable)
            ->where(['type' => [1]])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $list = ArrayHelper::map($result, 'name', 'description');

        return (isset($list[$name]) && !empty($list[$name])) ? $list[$name] : $list;
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function save()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $auth = Yii::$app->authManager;
                $role = $auth->createRole($this->name);

                $role->description = $this->description;
                $auth->add($role);

                $rules = $this->rules;
                $parent = isset($rules['parent']) ? $rules['parent'] : [];
                $child = isset($rules['child']) ? $rules['child'] : [];
                foreach ($parent as $rule) {
                    $item = $auth->createPermission($rule);

                    $auth->addChild($role, $item);
                }
                foreach ($child as $index => $value) {
                    if (!array_key_exists($index, $parent)) {
                        foreach ($value as $rule) {
                            $item = $auth->createPermission($rule);

                            $auth->addChild($role, $item);
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

    /**
     * @param $name
     * @return bool
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function update($name)
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $auth = Yii::$app->authManager;
                $role = $auth->createRole($this->name);

                $role->description = $this->description;
                $auth->update($name, $role);
                $auth->removeChildren($role);

                $rules = $this->rules;
                $parent = isset($rules['parent']) ? $rules['parent'] : [];
                $child = isset($rules['child']) ? $rules['child'] : [];
                foreach ($parent as $rule) {
                    $item = $auth->createPermission($rule);
                    $auth->addChild($role, $item);
                }
                foreach ($child as $index => $value) {
                    if (!array_key_exists($index, $parent)) {
                        foreach ($value as $rule) {
                            $item = $auth->createPermission($rule);
                            $auth->addChild($role, $item);
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
