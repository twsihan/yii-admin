<?php

namespace twsihan\admin\components\rest;

/**
 * Class ActiveController
 *
 * @package twsihan\admin\components\rest
 * @author twsihan <twsihan@gmail.com>
 */
class ActiveController extends \yii\rest\ActiveController
{
    public $serializer = Serializer::class;


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
