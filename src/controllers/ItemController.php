<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\rbac\Item;
use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\form\ItemDelete;
use twsihan\admin\models\form\ItemForm;
use twsihan\admin\models\form\ItemIndex;
use twsihan\admin\models\form\ItemView;
use twsihan\yii\helpers\ArrayHelper;
use Yii;
use yii\db\Query;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class ItemController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class ItemController extends ActiveController
{
    public $formModel = ItemForm::class;
    public $deleteModel = ItemDelete::class;
    public $indexModel = ItemIndex::class;
    public $viewModel = ItemView::class;


    public function actionParent()
    {
        $auth = Yii::$app->getAuthManager();
        $result = (new Query())->from($auth->itemTable)
            ->where(['type' => Item::TYPE_PERMISSION_GROUP])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
        return ['parentItems' => ArrayHelper::map($result, 'name', 'description')];
    }

    public function actionCreate()
    {
        /* @var ItemForm $model */
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
        /* @var ItemDelete $model */
        $model = Yii::createObject($this->deleteModel);
        if ($model->handle($id)) {
            return Yii::$app->response->setStatusCode(204);
        }
        throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
    }

    public function actionUpdate($id)
    {
        /* @var ItemForm $model */
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
        /* @var ItemIndex $model */
        $model = Yii::createObject($this->indexModel);
        $model->load(Yii::$app->request->get(), '');
        $this->serializer = [
            'class' => $this->serializer,
            'collectionEnvelope' => 'items',
        ];
        return $model->handle();
    }

    public function actionView($id)
    {
        if (empty($id)) {
            throw new UnprocessableEntityHttpException('缺少参数');
        }
        /* @var ItemView $model */
        $model = Yii::createObject($this->viewModel);
        return $model->handle($id);
    }
}
