<?php

/* @var $model twsihan\admin\models\logic\ItemLogic */
/* @var $isNewRecord bool */

use twsihan\admin\models\logic\RoleLogic;
use yii\bootstrap\ActiveForm;
use twsihan\yii\helpers\Html;
use kartik\select2\Select2;

?>
<?php $form = ActiveForm::begin([
    'id' => 'admin-form',
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
    <?= $form->field($model, 'username')->textInput() ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'avatar')->fileInput() ?>
    <?= $form->field($model, 'realName')->textInput() ?>
    <?= $form->field($model, 'email')->textInput() ?>
    <?= $form->field($model, 'address')->textInput() ?>
    <?= $form->field($model, 'roleName')->widget(Select2::class, [
        'theme' => Select2::THEME_BOOTSTRAP,
        'data' => RoleLogic::select(),
        'options' => ['placeholder' => '请选择'],
    ]) ?>
    <?= $form->field($model, 'status')->widget(Select2::class, [
        'theme' => Select2::THEME_BOOTSTRAP,
        'data' => ['启用', '禁止'],
    ]) ?>
    <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton($isNewRecord ? '创建' : '更新', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
