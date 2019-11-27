<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\models\entity\MenuEntity;
use yii\data\ActiveDataProvider;

/**
 * Class MenuIndex
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class MenuIndex extends MenuEntity
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'parent',
                function ($attribute) {
                    if (!static::parentExists($this->parent)) {
                        $this->addError($attribute, '父类不存在，请重新选择！~');
                    }
                },
            ],
        ];
    }

    public function handle()
    {
        $class = ParamsHelper::getMenuClass();
        $query = $class::find()->alias('a')
            ->leftJoin(['b' => $class::tableName()], 'a.id = b.parent_id')
            ->select([
                'a.id',
                'a.parent_id',
                'parent_name' => 'b.name',
                'a.name',
                'a.route',
                'a.icon',
                'a.sort',
                'a.data',
                'a.status',
                'a.created_at',
                'a.updated_at',
            ])->asArray();

        if ($this->validate()) {
            if ($this->parent) {
                $query->andWhere(['a.parent' => $this->parent]);
            }
        }

        $query->orderBy(['a.created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $this->limit],
        ]);
        return $dataProvider;
    }
}
