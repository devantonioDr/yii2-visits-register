<?php

use common\models\page\PageFooterContentConfig;
use common\widgets\ImageUploadWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\page\PageFooterContentConfig */

$this->title = 'Footer Content';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Footer Content</h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'footer-content-form',
                'options' => ['class' => 'config-form'],
            ]); ?>

            <?= $form->field($model, 'brand_name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'brand_type')->dropDownList(PageFooterContentConfig::getBrandTypes()) ?>
            
            <?= ImageUploadWidget::widget([
                'model' => $model,
                'attribute' => 'brand_logo',
                'label' => 'Brand Logo',
                'maxWidth' => 400,
                'maxHeight' => 300,
            ]) ?>
            
            <?= $form->field($model, 'brand_logo_alt')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'brand_logo_max_width')->textInput(['maxlength' => 50]) ?>
            <?= $form->field($model, 'brand_description')->textarea(['rows' => 3]) ?>
            <?= $form->field($model, 'address')->textInput(['maxlength' => 500]) ?>
            <?= $form->field($model, 'phone')->textInput(['maxlength' => 50]) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => 255, 'type' => 'email']) ?>
            <?= $form->field($model, 'copyright')->textInput(['maxlength' => 500]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save Footer Content', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

