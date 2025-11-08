<?php

use common\models\page\PageAboutSectionConfig;
use common\models\page\PageAboutSectionImageConfig;
use common\widgets\ImageUploadWidget;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\page\PageAboutSectionConfig */
/* @var $aboutSectionImages common\models\page\PageAboutSectionImageConfig[] */

$this->title = 'About Section';
$this->params['breadcrumbs'][] = ['label' => 'Page Config', 'url' => ['/page-config/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-config-container">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">About Section</h3>
        </div>
        <div class="box-body">
            <?php $form = ActiveForm::begin([
                'id' => 'about-section-form',
                'options' => ['class' => 'config-form'],
            ]); ?>

            <?= $form->field($model, 'badge')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'description')->textarea(['rows' => 3]) ?>
            
            <?= ImageUploadWidget::widget([
                'model' => $model,
                'attribute' => 'image_url',
                'label' => 'Image URL',
                'maxWidth' => 400,
                'maxHeight' => 300,
            ]) ?>
            
            <?= $form->field($model, 'image_alt')->textInput(['maxlength' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Save About Section', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <!-- About Section Images -->
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title">About Features</h3>
        </div>
        <div class="box-body">
            <div id="about-section-images-list">
                <?php foreach ($aboutSectionImages as $index => $image): ?>
                    <div class="about-section-image-item mb-3 p-3 border rounded" data-id="<?= $image->id ?>">
                        <?php $imgForm = ActiveForm::begin([
                            'id' => "about-section-image-form-{$image->id}",
                            'options' => ['class' => 'config-form'],
                            'action' => ['update-about-section-image'],
                        ]); ?>
                        <?= Html::hiddenInput('id', $image->id) ?>
                        <?= $imgForm->field($image, 'icon')->textInput(['maxlength' => 100, 'name' => "PageAboutSectionImageConfig[{$index}][icon]"])->label('Icon') ?>
                        <?= $imgForm->field($image, 'text')->textInput(['maxlength' => true, 'name' => "PageAboutSectionImageConfig[{$index}][text]"])->label('Text') ?>
                        <?= $imgForm->field($image, 'sort_order')->textInput(['type' => 'number', 'name' => "PageAboutSectionImageConfig[{$index}][sort_order]"])->label('Sort Order') ?>
                        <div class="form-group">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            <?= Html::a('Delete', ['delete-about-section-image', 'id' => $image->id], [
                                'class' => 'btn btn-sm btn-danger',
                                'data-confirm' => 'Are you sure you want to delete this item?',
                                'data-method' => 'post',
                            ]) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-sm btn-success" id="add-about-section-image">Add Feature</button>
        </div>
    </div>
</div>

<script>
document.getElementById('add-about-section-image')?.addEventListener('click', function() {
    const index = document.querySelectorAll('.about-section-image-item').length;
    const formHtml = `
        <div class="about-section-image-item mb-3 p-3 border rounded">
            <form action="<?= Url::to(['update-about-section-image']) ?>" method="post">
                <input type="hidden" name="<?= Yii::$app->request->csrfParam ?>" value="<?= Yii::$app->request->csrfToken ?>">
                <div class="form-group">
                    <label>Icon</label>
                    <input type="text" name="PageAboutSectionImageConfig[${index}][icon]" class="form-control" maxlength="100">
                </div>
                <div class="form-group">
                    <label>Text</label>
                    <input type="text" name="PageAboutSectionImageConfig[${index}][text]" class="form-control">
                </div>
                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="PageAboutSectionImageConfig[${index}][sort_order]" class="form-control">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                </div>
            </form>
        </div>
    `;
    document.getElementById('about-section-images-list').insertAdjacentHTML('beforeend', formHtml);
});
</script>

