<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \twsihan\admin\models\logic\AdminLogic */

use twsihan\admin\widgets\Alert;
use twsihan\yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '登录';

$fieldOptions = [
    'options' => ['class' => 'form-group has-feedback'],
    'inputTemplate' => "{input}",
];

?>
<div class="login-box">
    <div class="login-logo">
        <b>Admin</b>Yii2
    </div>
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <?= Alert::widget() ?>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'enableClientValidation' => false,
        ]); ?>
        <?= $form->field($model, 'username', $fieldOptions)
            ->label(false)->error(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>
        <?= $form->field($model, 'password', $fieldOptions)
            ->label(false)->error(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>
        <div class="row">
            <div class="col-xs-8">
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div>
            <div class="col-xs-4">
                <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
