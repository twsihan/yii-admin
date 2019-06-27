<?php

namespace twsihan\admin\models\logic;

use twsihan\yii\helpers\ArrayHelper;
use twsihan\admin\models\mysql\Menu;
use twsihan\admin\models\form\MenuForm;
use yii\data\ActiveDataProvider;

/**
 * Class MenuLogic
 *
 * @package twsihan\admin\models\logic
 * @author twsihan <twsihan@gmail.com>
 */
class MenuLogic extends MenuForm
{


    /**
     * create
     * @return bool
     */
    public function create()
    {
        if ($this->validate()) {
            $params = [
                'name' => $this->name,
                'icon' => $this->icon,
                'sort' => $this->sort ? $this->sort : 0,
                'data' => $this->data,
                'route' => $this->route,
            ];

            if ($this->parent) {
                $params = ArrayHelper::merge($params, ['parent' => $this->parent]);
            }

            $model = new Menu();
            $model->setAttributes($params, false);
            return $model->save();
        }
        return false;
    }

    /**
     * update
     * @param $id
     * @return bool
     */
    public function update($id)
    {
        if ($this->validate()) {

            $params = [
                'name' => $this->name,
                'sort' => $this->sort,
                'data' => $this->data,
                'route' => $this->route,
            ];

            if ($this->parent) {
                $params = ArrayHelper::merge($params, ['parent' => $this->parent]);
            }

            $model = Menu::findById($id);
            $model->setAttributes($params, false);
            return $model->save();
        }
        return false;
    }

    /**
     * search
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = Menu::find()
            ->from(Menu::tableName() . ' a')
            ->joinWith(['menuParent' => function ($q) {
                $q->from(Menu::tableName() . ' b');
            }]);

        if ($this->validate()) {
            if ($this->parent) {
                $query->andWhere(['a.parent' => $this->parent]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 30],
        ]);

        $sort = $dataProvider->getSort();
        $sort->attributes['menuParent.name'] = [
            'asc' => ['b.name' => SORT_ASC],
            'desc' => ['b.name' => SORT_DESC],
            'label' => 'parent',
        ];
        $sort->attributes['order'] = [
            'asc' => ['b.order' => SORT_ASC, 'a.order' => SORT_ASC],
            'desc' => ['b.order' => SORT_DESC, 'a.order' => SORT_DESC],
            'label' => 'order',
        ];
        $sort->defaultOrder = ['menuParent.name' => SORT_ASC];

        return $dataProvider;
    }
}
