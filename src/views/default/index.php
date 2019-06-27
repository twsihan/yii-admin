<?php

/* @var $this yii\web\View */

use twsihan\yii\helpers\ArrayHelper;
use twsihan\admin\components\helpers\ParamsHelper;

$this->title = '首页';

$admin = ParamsHelper::getIdentity();

?>
<div class="row">
    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="glyphicon glyphicon-user"></i>
                <h3 class="box-title">账号信息</h3>
            </div>
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>账号</dt>
                    <dd><?= ArrayHelper::getValue($admin, 'username') ?></dd>
                    <dt>角色</dt>
                    <dd><?= ArrayHelper::getValue($admin, 'username') ?></dd>
                    <dt>时区</dt>
                    <dd><?= Yii::$app->getTimeZone() ?></dd>
                    <dt>上次登录时间</dt>
                    <dd><?= date('Y-m-d H:i:s', ArrayHelper::getValue($admin, 'last_time')) ?></dd>
                    <dt>上次登录IP</dt>
                    <dd><?= ArrayHelper::getValue($admin, 'last_ip') ?></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-solid">
            <div class="box-header with-border">
                <i class="fa fa-desktop"></i>
                <h3 class="box-title">其他信息</h3>
            </div>
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>Yii版本</dt>
                    <dd><?= Yii::getVersion() ?></dd>
                    <dt>上传文件</dt>
                    <dd><?= '2M' ?></dd>
                    <dt>GitHub</dt>
                    <dd><a href="https://github.com" target="_blank">https://github.com/twsihan</a></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
