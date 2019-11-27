<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\form\UserLogin;
use twsihan\admin\components\helpers\ParamsHelper;
use Yii;
use yii\web\ServerErrorHttpException;

/**
 * Class DefaultController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class DefaultController extends ActiveController
{


    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionLogin()
    {
        $model = new UserLogin();
        $model->load(Yii::$app->request->post(), '');
        if (($result = $model->login()) !== false) {
            return $result;
        } else if (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to create the object for unknown reason.');
        }
        return $model;
    }

    public function actionLogout()
    {
        $user = ParamsHelper::getUser();

        $user->logout();
    }
}
