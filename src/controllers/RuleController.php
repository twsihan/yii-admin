<?php

namespace twsihan\admin\controllers;

use twsihan\admin\components\rest\ActiveController;
use twsihan\admin\models\form\RuleIndex;
use Yii;

/**
 * Class RoleController
 *
 * @package twsihan\admin\controllers
 * @author twsihan <twsihan@gmail.com>
 */
class RuleController extends ActiveController
{
    public $indexModel = RuleIndex::class;


    public function actionIndex()
    {
        /* @var RuleIndex $model */
        $model = Yii::createObject($this->indexModel);
        $model->load(Yii::$app->request->get(), '');
        $this->serializer = [
            'class' => $this->serializer,
            'collectionEnvelope' => 'items',
        ];
        return $model->handle();
    }
}
