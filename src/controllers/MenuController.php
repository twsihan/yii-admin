<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\web\Controller;
use twsihan\admin\models\mysql\Menu;
use twsihan\admin\models\logic\MenuLogic;
use Yii;

/**
 * Class MenuController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class MenuController extends Controller
{


    public function actionCreate()
    {
        $model = new MenuLogic(['scenario' => 'create']);

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->create()) {
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

    public function actionDelete($id)
    {
        if ($id) {
            Menu::findById($id)->delete();
        }
        return $this->redirect('index');
    }

    public function actionUpdate($id)
    {
        $model = new MenuLogic(['scenario' => 'update']);

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post()) && $model->update($id)) {
                Yii::$app->getSession()->setFlash('success', '修改成功');

                return $this->redirect('index');
            } else {
                Yii::$app->getSession()->setFlash('error', '修改失败');
            }
        }

        $model->setAttributes(Menu::findById($id, true), false);
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {
        $model = new MenuLogic(['scenario' => 'search']);
        $model->load(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $model,
            'dataProvider' => $model->search(),
        ]);
    }
}
