<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rbac\DbManager;
use twsihan\admin\components\rbac\Item;
use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\form\RoleForm;
use twsihan\admin\models\form\RoleIndex;
use twsihan\yii\helpers\ArrayHelper;
use Yii;
use yii\db\Query;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class RoleController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class RoleController extends ActiveController
{
    public $formModel = RoleForm::class;
    public $indexModel = RoleIndex::class;


    public function actionCreate()
    {
        /* @var RoleForm $model */
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
        /* @var DbManager $authManager */
        $authManager = ParamsHelper::getAuthManager();
        $count = (new Query())->from($authManager->assignmentTable)
            ->where('item_name = :name', [':name' => $id])
            ->count('item_name');
        if ($count) {
            throw new ServerErrorHttpException("无法删除，{$id} 正在使用");
        }
        $item = $authManager->createPermission($id);
        if ($authManager->remove($item)) {
            return Yii::$app->response->setStatusCode(204);
        }

        throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
    }

    public function actionUpdate($id)
    {
        /* @var RoleForm $model */
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
        /* @var RoleIndex $model */
        $model = Yii::createObject($this->indexModel);
        $model->load(Yii::$app->request->get(), '');
        $this->serializer = [
            'class' => $this->serializer,
            'collectionEnvelope' => 'items',
        ];
        return $model->handle();
    }

    public function actionAuth()
    {
        /* @var DbManager $authManager */
        $authManager = ParamsHelper::getAuthManager();
        $result = (new Query())->from($authManager->itemTable)
            ->where(['type' => [Item::TYPE_ROLE]])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        $list = ArrayHelper::map($result, 'name', 'description');

        return $list;
    }
}
