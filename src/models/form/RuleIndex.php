<?php

namespace twsihan\admin\models\form;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\models\entity\ItemEntity;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class RuleIndex
 *
 * @package twsihan\admin\models\form
 * @author twsihan <twsihan@gmail.com>
 */
class RuleIndex extends ItemEntity
{


    public function handle()
    {
        $authManager = ParamsHelper::getAuthManager();
        $query = (new Query())->from($authManager->ruleTable);
        $query->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => $this->limit],
        ]);
        return $dataProvider;
    }
}
