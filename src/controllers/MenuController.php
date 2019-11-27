<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\form\MenuForm;
use twsihan\admin\models\form\MenuIndex;
use Yii;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class MenuController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class MenuController extends ActiveController
{
    public $formModel = MenuForm::class;
    public $indexModel = MenuIndex::class;


    public function actionCreate()
    {
        /* @var MenuForm $model */
        $model = Yii::createObject($this->formModel);
        $model->load(Yii::$app->request->post(), '');
        if ($model->handle(0)) {
            return Yii::$app->response->setStatusCode(201);
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    public function actionDelete($id)
    {
        if (empty($id)) {
            throw new UnprocessableEntityHttpException('缺少参数');
        }
        $class = ParamsHelper::getMenuClass();
        $model = $class::findOne(['id' => $id]);
        if ($model->delete()) {
            return Yii::$app->response->setStatusCode(204);
        }
        throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
    }

    public function actionUpdate($id)
    {
        /* @var MenuForm $model */
        $model = Yii::createObject($this->formModel);
        $model->load(Yii::$app->request->post(), '');
        if ($model->handle($id)) {
            return;
        } else if (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;
    }

    public function actionIndex()
    {
        /* @var MenuIndex $model */
        $model = Yii::createObject($this->indexModel);
        $model->load(Yii::$app->request->get(), '');
        $this->serializer = [
            'class' => $this->serializer,
            'collectionEnvelope' => 'items',
        ];
        return $model->handle();
    }
}
