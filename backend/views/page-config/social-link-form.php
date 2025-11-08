<?php

use common\models\page\PageSocialLinkConfig;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\page\PageSocialLinkConfig */

$this->title = $model->isNewRecord ? 'Create Social Link' : 'Update Social Link';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = ['label' => 'Social Links', 'url' => ['social-links']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'social-link-form',
                'options' => ['class' => 'config-form'],
            ]); ?>

            <?= $form->field($model, 'platform')->textInput(['maxlength' => 100])->hint('e.g., Facebook, Twitter, Instagram, etc.') ?>

            <?= $form->field($model, 'url')->textInput(['maxlength' => 500, 'type' => 'url'])->hint('Full URL to the social media profile') ?>

            <?= $form->field($model, 'icon')->textInput(['maxlength' => 100])->hint('e.g., fa fa-facebook, ion-social-facebook, etc.') ?>

            <?= $form->field($model, 'sort_order')->textInput(['type' => 'number', 'value' => $model->sort_order ?: 0]) ?>
            
            <?php if (!$model->isNewRecord): ?>
                <?= Html::activeHiddenInput($model, 'id') ?>
            <?php endif; ?>

            <div class="form-group">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
                    'class' => 'btn btn-primary'
                ]) ?>
                <?= Html::a('Cancel', ['social-links'], [
                    'class' => 'btn btn-default'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

