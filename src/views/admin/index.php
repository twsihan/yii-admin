<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $searchModel \twsihan\admin\models\logic\AdminLogic */
/* @var $dataProvider \yii\data\ActiveDataProvider */

use yii\bootstrap\ActiveForm;
use twsihan\yii\data\GridView;
use twsihan\yii\helpers\Html;
use twsihan\admin\models\logic\RoleLogic;

$this->title = '管理员列表';
$this->params['breadcrumbs'] = [$this->title];

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <?php $form = ActiveForm::begin([
                    'id' => 'search-form',
                    'options' => [
                        'class' => 'form-inline',
                        'enctype' => 'multipart/form-data',
                    ],
                    'fieldConfig' => [
                        'template' => '{label}：{input}',
                    ],
                    'method' => 'get',
                ]); ?>
                <?= $form->field($searchModel, 'username')->textInput() ?>
                <?= $form->field($searchModel, 'email')->textInput() ?>
                <?= $form->field($searchModel, 'roleName')->dropDownList(RoleLogic::select(), ['prompt' => '请选择']) ?>
                <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn bg-light-blue color-palette']) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="box-body">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'label' => '账户',
                            'attribute' => 'username',
                        ],
                        [
                            'label' => '姓名',
                            'attribute' => 'username',
                        ],
                        [
                            'label' => '邮箱',
                            'attribute' => 'email',
                        ],
                        [
                            'label' => '角色标识',
                            'attribute' => 'assign.item.description',
                        ],
                        [
                            'label' => '状态',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->status == 1) {
                                    return Html::tag('small', '禁用', ['class' => 'label label-danger']);
                                } else {
                                    return Html::tag('small', '启用', ['class' => 'label label-primary']);
                                }
                            },
                        ],
                        [
                            'label' => '最后登录时间',
                            'attribute' => 'last_time',
                            'format' => ['date', 'Y-MM-dd HH:ss:mm'],
                        ],
                        [
                            'label' => '更新时间',
                            'attribute' => 'updated_at',
                            'format' => ['date', 'Y-MM-dd HH:ss:mm'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'template' => '{update} {delete}',
                            'buttons' => [
                                'update' => function ($url) {
                                    return Html::a(Html::tag('i', '', ['class' => 'fa fa-pencil-square-o bigger-120']),
                                        $url,
                                        [
                                            'class' => 'btn btn-info me-table-update btn-xs',
                                            'title' => '编辑',
                                        ]
                                    );
                                },
                                'delete' => function ($url) {
                                    return Html::a(Html::tag('i', '', ['class' => 'fa fa-trash-o bigger-120']),
                                        $url,
                                        [
                                            'class' => 'btn btn-danger me-table-delete btn-xs',
                                            'title' => '删除',
                                        ]
                                    );
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
