<?php

use common\models\page\PageCustomScriptConfig;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\page\PageCustomScriptConfig */

$this->title = $model->isNewRecord ? 'Create Custom Script' : 'Update Custom Script';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = ['label' => 'Custom Scripts', 'url' => ['custom-scripts']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
        </div>
        
        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'custom-script-form',
                'options' => ['class' => 'config-form'],
            ]); ?>
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> 
                <strong>Note:</strong> Enter the script code that will be inserted in the <code>&lt;head&gt;</code> section of all pages. 
                Only enabled scripts will be included.
            </div>

            <?= $form->field($model, 'label')->textInput(['maxlength' => 255])->hint('A descriptive name for this script (e.g., "Facebook Pixel", "Custom Analytics", etc.)') ?>


            <?= $form->field($model, 'script')->textarea([
                'rows' => 5,
                'placeholder' => '// Your script code here',
                'style' => 'font-family: monospace; font-size: 12px;'
            ]) ?>

           
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'enabled')->checkbox([
                        'label' => 'Enable this script',
                    ])->hint('Only enabled scripts will be included in the pages') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'sort_order')->textInput([
                        'type' => 'number',
                        'value' => $model->sort_order ?: 0
                    ])->hint('Scripts are loaded in this order (lower numbers first)') ?>
                </div>
            </div>
            
            <?php if (!$model->isNewRecord): ?>
                <?= Html::activeHiddenInput($model, 'id') ?>
            <?php endif; ?>

            <div class="form-group" style="margin-top: 20px;">
                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', [
                    'class' => 'btn btn-primary btn-lg',
                    'style' => 'margin-right: 10px;'
                ]) ?>
                <?= Html::a('Cancel', ['custom-scripts'], [
                    'class' => 'btn btn-default btn-lg'
                ]) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
