<?php

use common\models\page\PageHeroSectionConfig;
use common\widgets\ImageUploadWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\page\PageHeroSectionConfig */

$this->title = 'Hero Section';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <?php $form = ActiveForm::begin([
        'id' => 'hero-section-form',
        'options' => ['class' => 'config-form', 'enctype' => 'multipart/form-data'],
    ]); ?>

    <!-- Sección Heading -->
    <div class="box box-primary" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-heading"></i> Heading
            </h3>
        </div>
        <div class="box-body">
            <?= $form->field($model, 'heading')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'heading_type')->dropDownList(PageHeroSectionConfig::getHeadingTypes()) ?>
            
            <?= ImageUploadWidget::widget([
                'model' => $model,
                'attribute' => 'heading_image',
                'label' => 'Heading Image',
                'maxWidth' => 300,
                'maxHeight' => 300,
            ]) ?>
            
            <?= $form->field($model, 'heading_image_max_width')->textInput(['maxlength' => 50]) ?>
            
            <div class="form-group">
                <?= Html::submitButton('Save Heading', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <!-- Sección SubHeading -->
    <div class="box box-success" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-text-height"></i> SubHeading
            </h3>
        </div>
        <div class="box-body">
            <?= $form->field($model, 'subheading')->textarea(['rows' => 3]) ?>
            
            <div class="form-group">
                <?= Html::submitButton('Save SubHeading', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <!-- Sección Background -->
    <div class="box box-warning" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-image"></i> Background
            </h3>
        </div>
        <div class="box-body">
            <?= $form->field($model, 'media_type')->dropDownList(PageHeroSectionConfig::getMediaTypes()) ?>
            
            <?= ImageUploadWidget::widget([
                'model' => $model,
                'attribute' => 'media_url',
                'label' => 'Media URL (Image/Video)',
                'maxWidth' => 400,
                'maxHeight' => 300,
            ]) ?>
            
            <?= $form->field($model, 'video_format')->dropDownList(PageHeroSectionConfig::getVideoFormats(), ['prompt' => 'Select format']) ?>
            
            <div class="form-group">
                <?= Html::submitButton('Save Background', ['class' => 'btn btn-warning']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>