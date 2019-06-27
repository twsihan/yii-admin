<?php

/* @var $this yii\web\View */
/* @var $searchModel \twsihan\admin\models\logic\ItemLogic */
/* @var $dataProvider \yii\data\ActiveDataProvider */

use twsihan\yii\data\GridView;
use twsihan\yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use twsihan\admin\models\logic\ItemLogic;

$this->title = '权限列表';
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
                <?= $form->field($searchModel, 'type')->dropDownList(ItemLogic::typeMap(), ['prompt' => '请选择']) ?>
                <?= Html::submitButton('<i class="fa fa-search"></i>', ['class' => 'btn bg-light-blue color-palette']) ?>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="box-body">
                <?= GridView::widget([
                    'filterModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'label' => '权限名称',
                            'attribute' => 'name',
                        ],
                        [
                            'label' => '权限简介',
                            'attribute' => 'description',
                        ],
                        [
                            'label' => '更新时间',
                            'attribute' => 'updated_at',
                            'format' => ['date', 'Y-MM-dd HH:ss:mm'],
                        ],
                        [
                            'label' => '创建日期',
                            'attribute' => 'created_at',
                            'format' => ['date', 'Y-MM-dd HH:ss:mm'],
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header' => '操作',
                            'template' => '{update} {delete}',
                            'buttons' => [
                                'update' => function ($url, $model, $key) {
                                    unset($url, $key);
                                    return Html::a(Html::tag('i', '', ['class' => 'fa fa-pencil-square-o bigger-120']),
                                        ['update', 'name' => $model['name']],
                                        [
                                            'class' => 'btn btn-info me-table-update btn-xs',
                                            'title' => '编辑',
                                        ]
                                    );
                                },
                                'delete' => function ($url, $model, $key) {
                                    unset($url, $key);
                                    return Html::a(Html::tag('i', '', ['class' => 'fa fa-trash-o bigger-120']),
                                        ['delete', 'name' => $model['name']],
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
