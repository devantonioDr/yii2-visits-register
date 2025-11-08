<?php

use common\models\page\PageBrandColorsConfig;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model common\models\page\PageBrandColorsConfig */

$this->title = 'Brand Colors';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <?php $form = ActiveForm::begin([
        'id' => 'brand-colors-form',
        'options' => ['class' => 'config-form'],
    ]); ?>

    <!-- Primary Colors -->
    <div class="box box-primary" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-palette"></i> Primary Colors
            </h3>
        </div>
        <div class="box-body">
            <p class="text-muted">Primary colors are the main brand colors used throughout the interface.</p>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'primary')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Main brand color used for primary actions and highlights') ?>
                    <?= $form->field($model, 'on_primary')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color that appears on primary color background') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'primary_container')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Container background color for primary-themed content') ?>
                    <?= $form->field($model, 'on_primary_container')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color for primary container') ?>
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <?= Html::submitButton('<i class="fa fa-save"></i> Save Primary Colors', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <!-- Secondary Colors -->
    <div class="box box-success" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-palette"></i> Secondary Colors
            </h3>
        </div>
        <div class="box-body">
            <p class="text-muted">Secondary colors provide additional visual interest and complement the primary colors.</p>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'secondary')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Secondary brand color for accents and secondary actions') ?>
                    <?= $form->field($model, 'on_secondary')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color that appears on secondary color background') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'secondary_container')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Container background color for secondary-themed content') ?>
                    <?= $form->field($model, 'on_secondary_container')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color for secondary container') ?>
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <?= Html::submitButton('<i class="fa fa-save"></i> Save Secondary Colors', ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>

    <!-- Tertiary Colors -->
    <div class="box box-info" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-palette"></i> Tertiary Colors
            </h3>
        </div>
        <div class="box-body">
            <p class="text-muted">Tertiary colors offer additional variety for special cases and unique elements.</p>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'tertiary')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Tertiary brand color for special accents') ?>
                    <?= $form->field($model, 'on_tertiary')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color that appears on tertiary color background') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'tertiary_container')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Container background color for tertiary-themed content') ?>
                    <?= $form->field($model, 'on_tertiary_container')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color for tertiary container') ?>
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <?= Html::submitButton('<i class="fa fa-save"></i> Save Tertiary Colors', ['class' => 'btn btn-info']) ?>
            </div>
        </div>
    </div>

    <!-- Error Colors -->
    <div class="box box-danger" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-exclamation-triangle"></i> Error Colors
            </h3>
        </div>
        <div class="box-body">
            <p class="text-muted">Error colors are used to indicate errors, warnings, and destructive actions.</p>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'error')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Color used for error states and destructive actions') ?>
                    <?= $form->field($model, 'on_error')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color that appears on error color background') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'error_container')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Container background color for error-themed content') ?>
                    <?= $form->field($model, 'on_error_container')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color for error container') ?>
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <?= Html::submitButton('<i class="fa fa-save"></i> Save Error Colors', ['class' => 'btn btn-danger']) ?>
            </div>
        </div>
    </div>

    <!-- Surface Colors -->
    <div class="box box-warning" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-square"></i> Surface Colors
            </h3>
        </div>
        <div class="box-body">
            <p class="text-muted">Surface colors define the background colors for cards, sheets, and other elevated surfaces.</p>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'surface')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Default surface color for cards, sheets, and dialogs') ?>
                    <?= $form->field($model, 'on_surface')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Default text and icon color on surfaces') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'surface_variant')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Alternative surface color for subtle variations') ?>
                    <?= $form->field($model, 'on_surface_variant')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color for surface variant') ?>
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <?= Html::submitButton('<i class="fa fa-save"></i> Save Surface Colors', ['class' => 'btn btn-warning']) ?>
            </div>
        </div>
    </div>

    <!-- Background Colors -->
    <div class="box box-default" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-image"></i> Background Colors
            </h3>
        </div>
        <div class="box-body">
            <p class="text-muted">Background colors define the main background of the application.</p>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'background')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Main background color of the application') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'on_background')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color on the main background') ?>
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <?= Html::submitButton('<i class="fa fa-save"></i> Save Background Colors', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <!-- Outline & Effects -->
    <div class="box box-default" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-border-style"></i> Outline & Effects
            </h3>
        </div>
        <div class="box-body">
            <p class="text-muted">Colors for borders, outlines, shadows, and overlay effects.</p>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'outline')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Color for borders and dividers') ?>
                    <?= $form->field($model, 'outline_variant')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Alternative outline color for subtle borders') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'shadow')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Color used for shadows and elevation effects') ?>
                    <?= $form->field($model, 'scrim')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Overlay color for modals and dialogs') ?>
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <?= Html::submitButton('<i class="fa fa-save"></i> Save Outline & Effects', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <!-- Inverse Colors -->
    <div class="box box-default" style="margin-bottom: 20px;">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-adjust"></i> Inverse Colors
            </h3>
        </div>
        <div class="box-body">
            <p class="text-muted">Inverse colors are used for special cases where content needs to be inverted.</p>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'inverse_surface')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Inverted surface color for special cases') ?>
                    <?= $form->field($model, 'inverse_on_surface')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Text and icon color on inverse surface') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'inverse_primary')->textInput(['type' => 'color', 'class' => 'form-control color-input'])->hint('Inverted primary color for special cases') ?>
                </div>
            </div>
            <div class="form-group" style="margin-top: 15px;">
                <?= Html::submitButton('<i class="fa fa-save"></i> Save Inverse Colors', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> Save Brand Colors', ['class' => 'btn btn-primary btn-lg']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<style>
.color-input {
    height: 50px;
    cursor: pointer;
}
</style>
