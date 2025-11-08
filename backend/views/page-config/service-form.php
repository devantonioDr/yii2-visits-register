<?php

use common\models\page\PageServiceConfig;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\page\PageServiceConfig */

$this->title = $model->isNewRecord ? 'Create Service' : 'Update Service';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = ['label' => 'Services', 'url' => ['services']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'service-form',
                'options' => ['class' => 'config-form'],
            ]); ?>

            <?= $form->field($model, 'icon')->textInput(['maxlength' => 100])->hint('e.g., fa fa-home, ion-home, etc.') ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

            <?= $form->field($model, 'delay')->textInput(['maxlength' => 50])->hint('Animation delay (e.g., 0, 100, 200)') ?>

            <?= $form->field($model, 'sort_order')->textInput(['type' => 'number', 'value' => $model->sort_order ?: 0]) ?>
            
            <?php if (!$model->isNewRecord): ?>
                <?= Html::activeHiddenInput($model, 'id') ?>
            <?php endif; ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= Html::a('Cancel', ['services'], [
                    'class' => 'btn btn-default'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

