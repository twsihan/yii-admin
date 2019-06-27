<?php

/* @var $model twsihan\admin\models\logic\MenuLogic */
/* @var $isNewRecord bool */

use twsihan\admin\models\mysql\Menu;
use twsihan\yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use twsihan\yii\helpers\Html;

?>
<?php $form = ActiveForm::begin([
    'id' => 'menu-form',
    'options' => [
        'class' => 'form-horizontal text-center',
        'enctype' => 'multipart/form-data'
    ],
    'fieldConfig' => [
        'labelOptions' => ['class' => 'col-md-3 control-label'],
        'template' => '{label}<div class="col-md-4">{input}</div><div class="col-md-5">{error}</div>',
    ],
]); ?>
<div class="col-md-12">
    <?= $form->field($model, 'parent')->dropDownList(
        ArrayHelper::map(Menu::findParent(true), 'id', 'name'), [
            'prompt' => '请选择',
        ]
    ) ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'route')->textInput() ?>
    <?= $form->field($model, 'icon')->textInput() ?>
    <?= $form->field($model, 'sort')->input('number') ?>
    <?= $form->field($model, 'data')->textarea(['rows' => 4]) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton($isNewRecord ? '创建' : '更新', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
