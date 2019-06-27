<?php

/* @var $model twsihan\admin\models\logic\RoleLogic */
/* @var $isNewRecord bool */

use yii\bootstrap\ActiveForm;
use twsihan\yii\helpers\Html;
use twsihan\admin\models\logic\ItemLogic;
use twsihan\yii\widgets\CheckboxGroup;

?>
<?php $form = ActiveForm::begin([
    'id' => 'role-form',
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
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'description')->textInput() ?>
    <?= $form->field($model, 'rules', ['template' => '{label}<div class="col-sm-9">{input}</div>'])->widget(CheckboxGroup::class, [
        'items' => ItemLogic::itemGroup(),
    ]) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton($isNewRecord ? '创建' : '更新', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
