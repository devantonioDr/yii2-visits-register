<?php

use common\models\page\PagePortfolioImageConfig;
use common\widgets\ImageUploadWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\page\PagePortfolioImageConfig */

$this->title = $model->isNewRecord ? 'Create Portfolio Image' : 'Update Portfolio Image';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = ['label' => 'Portfolio Section', 'url' => ['portfolio-section']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'portfolio-image-form',
                'options' => ['class' => 'config-form'],
            ]); ?>

            <?= ImageUploadWidget::widget([
                'model' => $model,
                'attribute' => 'url',
                'label' => 'Image URL',
                'maxWidth' => 400,
                'maxHeight' => 300,
            ]) ?>

            <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'sort_order')->textInput(['type' => 'number', 'value' => $model->sort_order ?: 0]) ?>
            
            <?php if (!$model->isNewRecord): ?>
                <?= Html::activeHiddenInput($model, 'id') ?>
            <?php endif; ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= Html::a('Cancel', ['portfolio-section'], [
                    'class' => 'btn btn-default'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

