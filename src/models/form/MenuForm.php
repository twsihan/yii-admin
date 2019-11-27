<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\models\entity\MenuEntity;
use twsihan\yii\helpers\ArrayHelper;

/**
 * Class MenuForm
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class MenuForm extends MenuEntity
{


    public function handle($id)
    {
        if ($this->validate()) {
            $class = ParamsHelper::getMenuClass();
            if ($id) {
                $model = $class::findOne(['id' => $id]);
            } else {
                $model = new $class;
            }
            $params = [
                'name' => $this->name,
                'icon' => $this->icon,
                'sort' => $this->sort ? $this->sort : 0,
                'data' => $this->data,
                'route' => $this->route,
            ];
            if ($this->parentId) {
                $params = ArrayHelper::merge($params, ['parent_id' => $this->parentId]);
            }
            $model->setAttributes($params, false);
            return $model->save();
        }
        return false;
    }
}
