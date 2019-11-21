<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\logic\AdminLogic;
use twsihan\admin\components\helpers\ParamsHelper;
use Yii;

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

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin()
    {
        $user = ParamsHelper::getUser();

        if (!$user->isGuest) {
            return $this->goHome();
        }

        $model = new AdminLogic(['scenario' => 'login']);
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        $user = ParamsHelper::getUser();

        $user->logout();

        return $this->goHome();
    }
}
