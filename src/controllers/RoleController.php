<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\web\Controller;
use twsihan\admin\models\mysql\AuthItem;
use twsihan\admin\models\logic\AuthLogic;
use twsihan\admin\models\logic\RoleLogic;
use Yii;
use yii\base\Model;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\web\Response;

/**
 * Class RoleController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class RoleController extends Controller
{


    public function actionCreate()
    {
        $model = new RoleLogic(['scenario' => 'save']);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                return $this->redirect('index');
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if ($id) {
            if (AuthLogic::isRole($id) > 0) {
                Yii::$app->getSession()->setFlash('error', "无法删除，{$id} 正在使用！~");
            } else {
                $auth = Yii::$app->getAuthManager();
                $item = $auth->createPermission($id);
                $auth->remove($item);
            }
        }
        return $this->redirect('index');
    }

    public function actionUpdate($id)
    {
        if (!$id) {
            return $this->redirect('index');
        }

        $model = new RoleLogic(['scenario' => 'update']);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->update($id)) {
                return $this->redirect('index');
            }
        }

        $item = Yii::$app->getAuthManager()->getRole($id);

        $model->name = $item->name;
        $model->description = $item->description;
        $model->rules = Yii::$app->getAuthManager()->getPermissionsByRole($id);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        $searchModel = new Model();
        $searchModel->load(Yii::$app->request->get());

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($searchModel);
        }

        $query = AuthItem::find()->where('type = 1');
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
