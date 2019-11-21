<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\mysql\AuthItem;
use twsihan\admin\models\logic\AuthLogic;
use twsihan\admin\models\logic\RoleLogic;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;

/**
 * Class RoleController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class RoleController extends ActiveController
{


    public function actionCreate()
    {
        $model = new RoleLogic(['scenario' => 'save']);
        if ($model->load(Yii::$app->request->post(), '')) {
            if (!$model->save()) {
                throw new HttpException(500, '创建失败');
            }
        } else {
            return $model;
        }
    }

    public function actionDelete($id)
    {
        if ($id) {
            if (AuthLogic::isRole($id) > 0) {
                throw new HttpException(500, "无法删除，{$id} 正在使用！~");
            } else {
                $auth = Yii::$app->getAuthManager();
                $item = $auth->createPermission($id);
                $auth->remove($item);
            }
        }
    }

    public function actionUpdate($id)
    {
        if (!$id) {
            throw new HttpException(422, '缺少参数');
        }

        $model = new RoleLogic(['scenario' => 'update']);
        $model->load(Yii::$app->request->post(), '');
        if (!$model->update($id)) {
            throw new HttpException(500, '更新失败');
        } else {
            return $model;
        }
    }

    public function actionIndex()
    {
        $searchModel = new Model();
        $searchModel->load(Yii::$app->request->get(), '');
        $query = AuthItem::find()->where('type = 1');
        return new ActiveDataProvider(['query' => $query]);
    }
}
