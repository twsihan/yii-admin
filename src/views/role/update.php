<?php

/* @var $model twsihan\admin\models\logic\ItemLogic */

$this->title = '更新角色';
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
                <?= $this->render('_form', ['model' => $model, 'isNewRecord' => false]) ?>
            </div>
        </div>
    </div>
</div>
