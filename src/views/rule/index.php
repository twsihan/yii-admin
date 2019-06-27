<?php

/* @var $this yii\web\View */
/* @var $searchModel \yii\base\Model */
/* @var $dataProvider \yii\data\ActiveDataProvider */

use twsihan\yii\data\GridView;

$this->title = '规则列表';
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
                            'label' => '规则名称',
                            'attribute' => 'name',
                        ],
                        [
                            'label' => '规则数据',
                            'attribute' => 'data',
                        ],
                        [
                            'label' => '更新时间',
                            'attribute' => 'updated_at',
                            'format' => ['date', 'Y-MM-dd HH:ss:mm'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
