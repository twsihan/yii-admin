<?php

namespace twsihan\admin\components\rest;

use twsihan\admin\components\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\HttpHeaderAuth;
use yii\filters\auth\QueryParamAuth;

/**
 * Class ActiveController
 *
 * @package twsihan\admin\components\rest
 * @author twsihan <twsihan@gmail.com>
 */
class ActiveController extends \yii\rest\ActiveController
{
    public $serializer = Serializer::class;
    public $authMethods = [
        HttpBasicAuth::class,
        HttpBearerAuth::class,
        HttpHeaderAuth::class,
        QueryParamAuth::class,
    ];
    public $access = AccessControl::class;


    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods']= $this->authMethods;
        $behaviors['access'] = $this->access;
        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
    }

    public function allowAction()
    {
        return [];
    }
}
