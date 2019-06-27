<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $searchModel \twsihan\admin\models\logic\MenuLogic */
/* @var $dataProvider \yii\data\ActiveDataProvider */

use yii\bootstrap\ActiveForm;
use twsihan\yii\helpers\ArrayHelper;
use twsihan\yii\helpers\Html;
use twsihan\yii\data\GridView;
use twsihan\admin\models\mysql\Menu;

$this->title = '菜单列表';
$this->params['breadcrumbs'][] = $this->title;

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
                <?= $form->field($searchModel, 'parent')->dropDownList(
                    ArrayHelper::map(Menu::findParent(true), 'id', 'name'), [
                        'prompt' => '请选择',
                    ]
                ) ?>
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
                            'label' => '菜单名称',
                            'attribute' => 'name',
                        ],
                        [
                            'label' => '父级',
                            'attribute' => 'menuParent.name',
                        ],
                        [
                            'label' => '路由',
                            'attribute' => 'route',
                        ],
                        [
                            'label' => '排序',
                            'attribute' => 'sort',
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
