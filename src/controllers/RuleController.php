<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\rest\ActiveController;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class RoleController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class RuleController extends ActiveController
{


    /**
     * @return array|string
     */
    public function actionIndex()
    {
        $searchModel = new Model();
        $searchModel->load(Yii::$app->request->get(), '');

        $auth = Yii::$app->getAuthManager();
        $query = (new Query())->from("{$auth->ruleTable}");
        return new ActiveDataProvider(['query' => $query]);
    }
}
