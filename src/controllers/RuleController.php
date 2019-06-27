<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\web\Controller;
use Yii;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Response;

/**
 * Class RoleController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class RuleController extends Controller
{


    /**
     * @return array|string
     */
    public function actionIndex()
    {
        $searchModel = new Model();
        $searchModel->load(Yii::$app->request->get());

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($searchModel);
        }

        $auth = Yii::$app->getAuthManager();
        $query = (new Query())->from("{$auth->ruleTable}");
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
