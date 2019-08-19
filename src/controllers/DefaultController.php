<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\filters\AccessControl;
use twsihan\admin\components\web\Controller;
use twsihan\admin\models\logic\AdminLogic;
use twsihan\admin\components\helpers\ParamsHelper;
use Yii;

/**
 * Class DefaultController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class DefaultController extends Controller
{


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'allowAction' => ['login'],
                'rules' => [
                    [
                        'actions' => ['index', 'logout', 'error'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

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
