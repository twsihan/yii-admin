<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\filters\Access;
use twsihan\admin\components\helpers\ParamsHelper;
use twsihan\admin\components\web\Controller;
use twsihan\admin\models\mysql\Admin;
use twsihan\admin\models\logic\AdminLogic;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Response;

/**
 * Class AdminController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class AdminController extends Controller
{
    /**
     * @var $model
     */
    public $uploadModel = 'twsihan\admin\components\web\UploadedFile';


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => Access::class,
                'rules' => [
                    [
                        'actions' => ['profile'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        /* @var AdminLogic $model */
        $model = new AdminLogic(['scenario' => 'create']);
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if (Yii::$app->request->isPost) {
            $model->setUploadModel($this->uploadModel);
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
        if (empty($id) || !($arrIds = explode(',', $id))) {
            return $this->redirect('index');
        }

        /* @var Admin $model */
        $admins = Admin::findAll(['id' => $arrIds]);
        if (empty($admins)) {
            return $this->redirect('index');
        }

        foreach ($admins as $admin) {
            $admin->delete();
        }
        Yii::$app->getSession()->setFlash('success', '删除成功');

        return $this->redirect('index');
    }

    public function actionUpdate($id)
    {
        if ($id) {
            /* @var AdminLogic $model */
            $model = new AdminLogic(['scenario' => 'update']);
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }

            if (Yii::$app->request->isPost) {
                $model->setUploadModel($this->uploadModel);
                if ($model->load(Yii::$app->request->post()) && $model->update($id)) {
                    Yii::$app->getSession()->setFlash('success', '修改成功');

                    return $this->redirect('index');
                } else {
                    Yii::$app->getSession()->setFlash('error', '修改失败');
                }
            }

            $model->findByUserId($id);
            return $this->render('update', [
                'model' => $model,
            ]);
        }
        return $this->redirect('index');
    }

    public function actionIndex()
    {
        $model = new AdminLogic(['scenario' => 'search']);
        $model->load(Yii::$app->request->get());

        return $this->render('index', [
            'searchModel' => $model,
            'dataProvider' => $model->search(),
        ]);
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
                if ($passwordModel->load(Yii::$app->request->post()) && $passwordModel->password($userId)) {
                    Yii::$app->getSession()->setFlash('success', '修改成功');
                    return $this->redirect('profile');
                } else {
                    Yii::$app->getSession()->setFlash('error', '修改失败');
                }
            } else {
                $profileModel->setUploadModel($this->uploadModel);
                if ($profileModel->load(Yii::$app->request->post()) && $profileModel->update($userId)) {
                    Yii::$app->getSession()->setFlash('success', '修改成功');
                    return $this->redirect('profile');
                } else {
                    Yii::$app->getSession()->setFlash('error', '修改失败');
                }
            }
        }

        $profileModel->findByUserId($userId);
        $profileModel->setUploadModel($this->uploadModel);
        return $this->render('profile', [
            'profileModel' => $profileModel,
            'passwordModel' => $passwordModel,
        ]);
    }
}
