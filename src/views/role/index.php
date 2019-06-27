<?php

/* @var $this yii\web\View */
/* @var $searchModel \twsihan\admin\models\logic\RoleLogic */
/* @var $dataProvider \yii\data\ActiveDataProvider */

use twsihan\yii\data\GridView;
use twsihan\yii\helpers\Html;

$this->title = '角色列表';
$this->params['breadcrumbs'] = [$this->title];

?>
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <?= GridView::widget([
                    'filterModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'label' => '角色名称',
                            'attribute' => 'name',
                        ],
                        [
                            'label' => '角色简介',
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
