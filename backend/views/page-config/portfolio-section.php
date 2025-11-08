<?php

use common\models\page\PagePortfolioSectionConfig;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\page\PagePortfolioSectionConfig */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Portfolio Section';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Portfolio Section</h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'portfolio-section-form',
                'options' => ['class' => 'config-form'],
            ]); ?>

            <?= $form->field($model, 'badge')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save Portfolio Section', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <!-- Portfolio Images -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Portfolio Images</h3>
            <div class="box-tools pull-right">
                <?= Html::a('<i class="fa fa-plus"></i> Add Image', ['create-portfolio-image'], [
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
                        'attribute' => 'url',
                        'label' => 'Image',
                        'format' => 'raw',
                        'value' => function ($model) {
                            if (!empty($model->url)) {
                                return Html::img($model->url, [
                                    'style' => 'max-width: 100px; max-height: 100px; border: 1px solid #ddd; border-radius: 4px;',
                                    'alt' => $model->alt ?? 'Portfolio Image'
                                ]);
                            }
                            return '<span class="text-muted">No image</span>';
                        },
                    ],
                    [
                        'attribute' => 'alt',
                        'label' => 'Alt Text',
                    ],
                    [
                        'attribute' => 'title',
                        'label' => 'Title',
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
                                    ['update-portfolio-image', 'id' => $model->id],
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
                                    ['delete-portfolio-image', 'id' => $model->id],
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
