<?php

/* @var $model twsihan\admin\models\logic\ItemLogic */
/* @var $isNewRecord bool */

use yii\bootstrap\ActiveForm;
use twsihan\yii\helpers\Html;
use twsihan\admin\models\logic\ItemLogic;

?>
<?php $form = ActiveForm::begin([
    'id' => 'item-form',
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
    <?= $form->field($model, 'parentName')->dropDownList(ItemLogic::getParentSelect(), ['prompt' => '请选择']) ?>
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'description')->textInput() ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton($isNewRecord ? '创建' : '更新', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

