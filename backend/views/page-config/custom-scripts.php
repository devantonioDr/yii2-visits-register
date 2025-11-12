<?php

use common\models\page\PageCustomScriptConfig;
use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Custom Scripts';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Custom Scripts</h3>
            <div class="box-tools pull-right">
                <?= Html::a('<i class="fa fa-plus"></i> Add Script', ['create-custom-script'], [
                    'class' => 'btn btn-success btn-sm'
                ]) ?>
            </div>
        </div>
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => 'ID',
                        'headerOptions' => ['style' => 'width: 60px;'],
                    ],
                    [
                        'attribute' => 'label',
                        'label' => 'Label',
                    ],
                    [
                        'attribute' => 'script',
                        'label' => 'Script',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (!empty($model->script)) {
                                $script = Html::encode($model->script);
                                if (strlen($script) > 100) {
                                    return '<code>' . substr($script, 0, 100) . '...</code>';
                                }
                                return '<code>' . $script . '</code>';
                            }
                            return '<span class="text-muted">No script</span>';
                        },
                    ],
                    [
                        'attribute' => 'enabled',
                        'label' => 'Enabled',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return $model->enabled == 1 
                                ? '<span class="label label-success">Yes</span>' 
                                : '<span class="label label-default">No</span>';
                        },
                        'headerOptions' => ['style' => 'width: 80px;'],
                    ],
                    [
                        'attribute' => 'sort_order',
                        'label' => 'Sort Order',
                        'headerOptions' => ['style' => 'width: 100px;'],
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Created',
                        'format' => 'datetime',
                        'headerOptions' => ['style' => 'width: 150px;'],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => 'Actions',
                        'headerOptions' => ['style' => 'width: 150px;'],
                        'contentOptions' => ['style' => 'text-align: center;'],
                        'template' => '{update} {delete}',
                        'buttons' => [
                            'update' => function ($url, $model, $key) {
                                return Html::a(
                                    '<i class="fa fa-edit"></i> Edit',
                                    ['update-custom-script', 'id' => $model->id],
                                    [
                                        'title' => 'Edit',
                                        'class' => 'btn btn-sm btn-primary',
                                        'data-pjax' => '0',
                                    ]
                                );
                            },
                            'delete' => function ($url, $model, $key) {
                                return Html::a(
                                    '<i class="fa fa-trash"></i> Delete',
                                    ['delete-custom-script', 'id' => $model->id],
                                    [
                                        'title' => 'Delete',
                                        'class' => 'btn btn-sm btn-danger',
                                        'data-confirm' => 'Are you sure you want to delete this script?',
                                        'data-method' => 'post',
                                        'data-pjax' => '0',
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

