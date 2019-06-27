<?php

/* @var $this yii\web\View */

$this->title = '创建角色';
$this->params['breadcrumbs'] = [
    [
        'label' => '角色列表',
        'url' => ['index'],
    ],
    $this->title,
];

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <?= $this->render('_form', ['model' => $model, 'isNewRecord' => true]) ?>
            </div>
        </div>
    </div>
</div>
