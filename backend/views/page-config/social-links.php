<?php

use common\models\page\PageSocialLinkConfig;
use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Social Links';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Social Links</h3>
            <div class="box-tools pull-right">
                <?= Html::a('<i class="fa fa-plus"></i> Add Social Link', ['create-social-link'], [
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
                        'attribute' => 'platform',
                        'label' => 'Platform',
                    ],
                    [
                        'attribute' => 'url',
                        'label' => 'URL',
                        'format' => 'raw',
                        'value' => function ($model) {
                            return Html::a(
                                Html::encode($model->url),
                                $model->url,
                                ['target' => '_blank', 'title' => $model->url]
                            );
                        },
                    ],
                    [
                        'attribute' => 'icon',
                        'label' => 'Icon',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (!empty($model->icon)) {
                                return '<i class="' . Html::encode($model->icon) . '"></i> ' . Html::encode($model->icon);
                            }
                            return '<span class="text-muted">No icon</span>';
                        },
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
                                    ['update-social-link', 'id' => $model->id],
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
                                    ['delete-social-link', 'id' => $model->id],
                                    [
                                        'title' => 'Delete',
                                        'class' => 'btn btn-sm btn-danger',
                                        'data-confirm' => 'Are you sure you want to delete this item?',
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
