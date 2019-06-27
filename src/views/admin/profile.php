<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $profileModel \twsihan\admin\models\logic\AdminLogic */
/* @var $passwordModel \twsihan\admin\models\logic\AdminLogic */

use twsihan\yii\helpers\Html;
use yii\widgets\ActiveForm;
use twsihan\yii\helpers\ArrayHelper;
use twsihan\admin\components\helpers\ParamsHelper;

$this->title = '个人中心';
$this->params['breadcrumbs'] = [$this->title];

$directoryAsset = $this->getAssetManager()->getBundle('twsihan\admin\assets\AdminlteAsset')->baseUrl;
$avatar = ($avatar = ArrayHelper::getValue($profileModel, 'avatar')) ? Yii::getAlias(ArrayHelper::getValue($profileModel->getUploadModel(), 'url')) . $avatar : $directoryAsset . '/img/user2-160x160.jpg';
$admin = ParamsHelper::getUser()->getIdentity();

?>
<div class="row">
    <div class="col-md-3">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle" src="<?= $avatar ?>" alt="User profile picture">
                <h3 class="profile-username text-center"><?= $admin->getAttribute('real_name') ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab">基本信息</a></li>
                <li><a href="#set-password" data-toggle="tab">修改密码</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="activity">
                    <div class="row">
                        <?php $form = ActiveForm::begin([
                            'id' => 'profile-form',
                            'options' => [
                                'class' => 'form-horizontal text-center',
                                'enctype' => 'multipart/form-data'
                            ],
                            'fieldConfig' => [
                                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                                'template' => '{label}<div class="col-sm-4">{input}</div><div class="col-sm-5">{error}</div>',
                            ],
                        ]); ?>
                        <div class="col-md-12">
                            <?= $form->field($profileModel, 'avatar')->fileInput() ?>
                            <?= $form->field($profileModel, 'realName')->textInput() ?>
                            <?= $form->field($profileModel, 'email')->textInput() ?>
                            <?= $form->field($profileModel, 'address')->textInput() ?>
                            <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
                            <?= Html::submitButton('更新', ['class' => 'btn btn-primary', 'name' => 'type', 'value' => 'profile']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <div class="tab-pane" id="set-password">
                    <div class="row">
                        <?php $form = ActiveForm::begin([
                            'id' => 'password-form',
                            'options' => [
                                'class' => 'form-horizontal text-center',
                                'enctype' => 'multipart/form-data'
                            ],
                            'fieldConfig' => [
                                'labelOptions' => ['class' => 'col-sm-3 control-label'],
                                'template' => '{label}<div class="col-sm-4">{input}</div><div class="col-sm-5">{error}</div>',
                            ],
                        ]); ?>
                        <div class="col-md-12">
                            <?= $form->field($passwordModel, 'password')->passwordInput() ?>
                            <?= $form->field($passwordModel, 'cPassword')->passwordInput() ?>
                            <?= Html::submitButton('更新', ['class' => 'btn btn-primary', 'name' => 'type', 'value' => 'password']) ?>
                            <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
