<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\form\UserForm;
use twsihan\admin\models\form\UserIndex;
use twsihan\admin\models\form\UserPassword;
use Yii;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class AdminController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class AdminController extends ActiveController
{
    /**
     * @var string
     */
    public $uploadModel = 'twsihan\admin\components\web\UploadedFile';
    public $formModel = UserForm::class;
    public $indexModel = UserIndex::class;
    public $passwordModel = UserPassword::class;


    public function actionCreate()
    {
        /* @var UserForm $model */
        $model = Yii::createObject($this->formModel);
        $model->load(Yii::$app->request->post(), '');
        $model->setUploadModel($this->uploadModel);
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
        $webUser = ParamsHelper::getUser();
        /* @var ActiveRecord $class */
        $class = $webUser->identityClass;
        $model = $class::findOne(['id' => $id]);
        if ($model->delete()) {
            return Yii::$app->response->setStatusCode(204);
        }
        throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
    }

    public function actionUpdate($id)
    {
        /* @var UserForm $model */
        $model = Yii::createObject($this->formModel);
        $model->load(Yii::$app->request->post(), '');
        $model->setUploadModel($this->uploadModel);
        if ($model->handle($id)) {
            return;
        } else if (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;
    }

    public function actionIndex()
    {
        /* @var UserIndex $model */
        $model = Yii::createObject($this->indexModel);
        $model->load(Yii::$app->request->get(), '');
        $this->serializer = [
            'class' => $this->serializer,
            'collectionEnvelope' => 'items',
        ];
        return $model->handle();
    }

    public function actionEditProfile()
    {
        /* @var UserForm $model */
        $model = Yii::createObject($this->formModel);
        $model->load(Yii::$app->request->post(), '');
        $model->setUploadModel($this->uploadModel);
        $webUser = ParamsHelper::getUser();
        if ($model->handle($webUser->getId())) {
            return;
        } else if (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;
    }

    public function actionResetPassword()
    {
        /* @var UserPassword $model */
        $model = Yii::createObject($this->passwordModel);
        $model->load(Yii::$app->request->post(), '');
        $webUser = ParamsHelper::getUser();
        if ($model->handle($webUser->getId())) {
            return;
        } else if (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }
        return $model;
    }
}
