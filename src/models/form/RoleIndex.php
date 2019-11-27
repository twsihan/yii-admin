<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rbac\Item;
use twsihan\admin\models\entity\RoleEntity;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class RoleIndex
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class RoleIndex extends RoleEntity
{


    public function handle()
    {
        $authManager = ParamsHelper::getAuthManager();
        $query = (new Query())->from($authManager->itemTable)->where('type = ' . Item::TYPE_ROLE);
        $query->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $this->limit],
        ]);
        return $dataProvider;
    }
}
