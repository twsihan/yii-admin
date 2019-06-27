<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\rbac\Item;
use twsihan\admin\components\web\Controller;
use twsihan\admin\models\logic\ItemLogic;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\web\Response;

/**
 * Class ItemController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class ItemController extends Controller
{


    public function actionCreate()
    {
        $model = new ItemLogic(['scenario' => 'create']);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->create()) {
                Yii::$app->getSession()->setFlash('success', '创建成功');
                return $this->redirect('index');
            } else {
                Yii::$app->getSession()->setFlash('error', '创建失败');
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete($name)
    {
        if ($name) {
            if ((new ItemLogic())->delete($name)) {
                Yii::$app->getSession()->setFlash('success', '删除成功');
                return $this->redirect('index');
            } else {
                Yii::$app->getSession()->setFlash('error', '删除失败');
            }
        }
        return $this->redirect('index');
    }

    public function actionUpdate($name)
    {
        $model = new ItemLogic(['scenario' => 'update']);
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            if ($model->update($name)) {
                Yii::$app->getSession()->setFlash('success', '创建成功');
                return $this->redirect('index');
            } else {
                Yii::$app->getSession()->setFlash('error', '创建失败');
            }
        }

        $model->reload($name);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        $searchModel = new ItemLogic(['scenario' => 'search']);
        $searchModel->load(Yii::$app->request->get());

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($searchModel);
        }

        $type = [Item::TYPE_PERMISSION, Item::TYPE_PERMISSION_GROUP];
        if ($searchModel->validate()) {
            if ($searchModel->type) {
                $type = $searchModel->type;
            }
        }

        $auth = Yii::$app->getAuthManager();
        $query = (new Query())->from("{$auth->itemTable}")->where(['type' => $type]);
        $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
