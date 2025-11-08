<?php

use common\models\page\PageSiteConfig;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\page\PageSiteConfig */

$this->title = 'Site Configuration';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Site Configuration</h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'site-config-form',
                'options' => ['class' => 'config-form'],
            ]); ?>

            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save Site Config', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

