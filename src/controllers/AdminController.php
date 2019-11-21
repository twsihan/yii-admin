<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\mysql\Admin;
use twsihan\admin\models\logic\AdminLogic;
use Yii;
use yii\web\HttpException;

/**
 * Class AdminController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class AdminController extends ActiveController
{
    /**
     * @var $model
     */
    public $uploadModel = 'twsihan\admin\components\web\UploadedFile';


    public function actionIndex()
    {
        $model = new AdminLogic(['scenario' => 'search']);
        $model->load(Yii::$app->request->get(), '');
        return $model->search();
    }

    public function actionCreate()
    {
        /* @var AdminLogic $model */
        $model = new AdminLogic(['scenario' => 'create']);
        if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
            $model->setUploadModel($this->uploadModel);
            if (!$model->create()) {
                throw new HttpException(500, '创建失败');
            }
        }
        return $model;
    }

    public function actionDelete($id)
    {
        if (empty($id) || !($arrIds = explode(',', $id))) {
            throw new HttpException(422, '缺少参数');
        }

        /* @var Admin $model */
        $admins = Admin::findAll(['id' => $arrIds]);
        if (empty($admins)) {
            return $this->redirect('index');
        }

        foreach ($admins as $admin) {
            $admin->delete();
        }
    }

    public function actionUpdate($id)
    {
        $model = [];
        if ($id) {
            /* @var AdminLogic $model */
            $model = new AdminLogic(['scenario' => 'update']);
            if ($model->load(Yii::$app->request->post(), '') && $model->validate()) {
                $model->setUploadModel($this->uploadModel);
                if (!$model->update($id)) {
                    throw new HttpException(500, '更新失败');
                }
            }
            $model->findByUserId($id);
        }
        return $model;
    }

    public function actionProfile()
    {
        /* @var AdminLogic $profileModel */
        $profileModel = new AdminLogic(['scenario' => 'profile']);
        /* @var AdminLogic $passwordModel */
        $passwordModel = new AdminLogic(['scenario' => 'password']);

        $userId = ParamsHelper::getUser()->getId();
        if (Yii::$app->request->isPost) {
            $type = Yii::$app->request->post('type', 'profile');
            if ($type === 'password') {
                if ($passwordModel->load(Yii::$app->request->post(), '') && $passwordModel->password($userId)) {
                    return $passwordModel;
                } else {
                    throw new HttpException(500, '更新失败');
                }
            } else {
                $profileModel->setUploadModel($this->uploadModel);
                if ($profileModel->load(Yii::$app->request->post(), '') && $profileModel->update($userId)) {
                    return $profileModel;
                } else {
                    throw new HttpException(500, '更新失败');
                }
            }
        }

        $profileModel->findByUserId($userId);
        $profileModel->setUploadModel($this->uploadModel);

        return [
            'profileModel' => $profileModel,
            'passwordModel' => $passwordModel,
        ];
    }
}
