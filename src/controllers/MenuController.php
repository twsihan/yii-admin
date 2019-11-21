<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\mysql\Menu;
use twsihan\admin\models\logic\MenuLogic;
use Yii;
use yii\web\HttpException;

/**
 * Class MenuController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class MenuController extends ActiveController
{


    public function actionCreate()
    {
        $model = new MenuLogic(['scenario' => 'create']);
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            if (!$model->create()) {
                throw new HttpException(500, '创建失败');
            }
        } else {
            return $model;
        }
    }

    public function actionDelete($id)
    {
        if ($id) {
            Menu::findById($id)->delete();
        }
    }

    public function actionUpdate($id)
    {
        $model = new MenuLogic(['scenario' => 'update']);

        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            if (!$model->update($id)) {
                throw new HttpException(500, '更新失败');
            }
        } else {
            return $model;
        }
    }

    public function actionIndex()
    {
        $model = new MenuLogic(['scenario' => 'search']);
        $model->load(Yii::$app->request->get(), '');
        return $model->search();
    }
}
