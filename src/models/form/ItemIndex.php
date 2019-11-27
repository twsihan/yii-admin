<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rbac\Item;
use twsihan\admin\models\entity\ItemEntity;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class ItemIndex
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class ItemIndex extends ItemEntity
{


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'type',
                'in',
                'range' => [Item::TYPE_PERMISSION, Item::TYPE_PERMISSION_GROUP],
            ],
        ];
    }

    public function handle()
    {
        $authManager = ParamsHelper::getAuthManager();
        $type = [Item::TYPE_PERMISSION, Item::TYPE_PERMISSION_GROUP];
        if ($this->validate()) {
            if ($this->type) {
                $type = $this->type;
            }
        }
        $query = (new Query())->from("{$authManager->itemTable}")->where(['type' => $type]);
        $query->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $this->limit],
        ]);
        return $dataProvider;
    }
}
