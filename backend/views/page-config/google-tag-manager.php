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
                <strong>Instrucciones:</strong> Configure Google Tag Manager y/o Google Tag (gtag.js). 
                Los códigos se insertarán automáticamente en todas las páginas del sitio.
            </div>

            <?php $form = ActiveForm::begin([
                'id' => 'gtm-config-form',
                'options' => ['class' => 'config-form'],
            ]); ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Google Tag Manager (GTM)</h3>
                        </div>
                        <div class="box-body">
                            <?= $form->field($model, 'gtm_id')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'GTM-53MMVHXG',
                                'pattern' => 'GTM-[A-Z0-9]+',
                            ])->hint('Formato: GTM-XXXXXXX (ejemplo: GTM-53MMVHXG)') ?>

                            <?= $form->field($model, 'enabled')->checkbox([
                                'label' => 'Habilitar Google Tag Manager',
                            ])->hint('Marque esta opción para activar Google Tag Manager en el sitio') ?>

                            <?php if ($model->isActive()): ?>
                                <div class="alert alert-success mt-2">
                                    <i class="fa fa-check-circle"></i> 
                                    <strong>Google Tag Manager está activo</strong><br>
                                    ID: <code><?= Html::encode($model->gtm_id) ?></code>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mt-2">
                                    <i class="fa fa-exclamation-triangle"></i> 
                                    <strong>Google Tag Manager está desactivado</strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Google Tag (gtag.js)</h3>
                        </div>
                        <div class="box-body">
                            <?= $form->field($model, 'gtag_id')->textInput([
                                'maxlength' => true,
                                'placeholder' => 'AW-16791212959 o G-XXXXXXXXXX',
                                'pattern' => '(AW|G)-[A-Z0-9]+',
                            ])->hint('Formato: AW-XXXXXXX (Google Ads) o G-XXXXXXXXXX (Google Analytics 4)') ?>

                            <?= $form->field($model, 'gtag_enabled')->checkbox([
                                'label' => 'Habilitar Google Tag',
                            ])->hint('Marque esta opción para activar Google Tag (gtag.js) en el sitio') ?>

                            <?php if ($model->isGtagActive()): ?>
                                <div class="alert alert-success mt-2">
                                    <i class="fa fa-check-circle"></i> 
                                    <strong>Google Tag está activo</strong><br>
                                    ID: <code><?= Html::encode($model->gtag_id) ?></code>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning mt-2">
                                    <i class="fa fa-exclamation-triangle"></i> 
                                    <strong>Google Tag está desactivado</strong>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <?= Html::submitButton('Guardar Configuración', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

