<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\rbac\Item;
use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\logic\ItemLogic;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\HttpException;

/**
 * Class ItemController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class ItemController extends ActiveController
{


    public function actionCreate()
    {
        $model = new ItemLogic(['scenario' => 'create']);
        $model->load(Yii::$app->request->post(), '');
        if ($model->validate()) {
            if (!$model->create()) {
                throw new HttpException(500, '更新失败');
            }
        }
        return $model;
    }

    public function actionDelete($name)
    {
        if ($name) {
            if (!(new ItemLogic())->delete($name)) {
                throw new HttpException(500, '更新失败');
            }
        }
        return $this->redirect('index');
    }

    public function actionUpdate($name)
    {
        $model = new ItemLogic(['scenario' => 'update']);
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            if (!$model->update($name)) {
                throw new HttpException(500, '更新失败');
            }
        } else {
            return $model;
        }
    }

    public function actionIndex()
    {
        $searchModel = new ItemLogic(['scenario' => 'search']);
        $searchModel->load(Yii::$app->request->get(), '');

        $type = [Item::TYPE_PERMISSION, Item::TYPE_PERMISSION_GROUP];
        if ($searchModel->validate()) {
            if ($searchModel->type) {
                $type = $searchModel->type;
            }
        }

        $auth = Yii::$app->getAuthManager();
        $query = (new Query())->from("{$auth->itemTable}")->where(['type' => $type]);
        return new ActiveDataProvider(['query' => $query]);
    }
}
