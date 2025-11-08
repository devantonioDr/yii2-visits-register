<?php

use common\models\page\PageCallToActionConfig;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\page\PageCallToActionConfig */

$this->title = 'Call to Action';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Call to Action</h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'call-to-action-form',
                'options' => ['class' => 'config-form'],
            ]); ?>

            <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'link')->textInput(['maxlength' => 500]) ?>
            <?= $form->field($model, 'target')->dropDownList(PageCallToActionConfig::getTargetOptions()) ?>
            <?= $form->field($model, 'icon')->textInput(['maxlength' => 100]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save Call to Action', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

