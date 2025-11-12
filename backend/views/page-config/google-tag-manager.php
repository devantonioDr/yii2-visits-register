<?php

use common\models\page\PageGoogleTagManagerConfig;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\page\PageGoogleTagManagerConfig */

$this->title = 'Google Tag Manager';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">Google Tag Manager Configuration</h3>
        </div>
        <div class="box-body">
            <div class="alert alert-info">
                <i class="fa fa-info-circle"></i> 
                <strong>Instrucciones:</strong> Ingrese su ID de Google Tag Manager (formato: GTM-XXXXXXX). 
                El código se insertará automáticamente en todas las páginas del sitio.
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'gtm-config-form',
                'options' => ['class' => 'config-form'],
            ]); ?>

            <?= $form->field($model, 'gtm_id')->textInput([
                'maxlength' => true,
                'placeholder' => 'GTM-53MMVHXG',
                'pattern' => 'GTM-[A-Z0-9]+',
            ])->hint('Formato: GTM-XXXXXXX (ejemplo: GTM-53MMVHXG)') ?>

            <?= $form->field($model, 'enabled')->checkbox([
                'label' => 'Habilitar Google Tag Manager',
            ])->hint('Marque esta opción para activar Google Tag Manager en el sitio') ?>

            <div class="form-group">
                <?= Html::submitButton('Guardar Configuración', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>

            <?php if ($model->isActive()): ?>
                <div class="alert alert-success mt-3">
                    <i class="fa fa-check-circle"></i> 
                    <strong>Google Tag Manager está activo</strong><br>
                    ID: <code><?= Html::encode($model->gtm_id) ?></code>
                </div>
            <?php else: ?>
                <div class="alert alert-warning mt-3">
                    <i class="fa fa-exclamation-triangle"></i> 
                    <strong>Google Tag Manager está desactivado</strong><br>
                    Configure el ID y habilite la opción para activarlo.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

