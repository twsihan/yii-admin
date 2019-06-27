<?php

/* @var $this yii\web\View */

$this->title = '更新权限';
$this->params['breadcrumbs'] = [
    [
        'label' => '权限列表',
        'url' => ['index'],
    ],
    $this->title,
];

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <?= $this->render('_form', ['model' => $model, 'isNewRecord' => false]) ?>
            </div>
        </div>
    </div>
</div>
