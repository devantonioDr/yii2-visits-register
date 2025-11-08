<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap4\Html;
use yii\helpers\Url;

/**
 * ImageUploadWidget - Widget para subir y mostrar imágenes
 * 
 * @property string $model El modelo ActiveRecord
 * @property string $attribute El atributo del modelo que contiene la URL de la imagen
 * @property string $label La etiqueta del campo
 * @property array $options Opciones adicionales para el widget
 * @property string $uploadUrl La URL para subir la imagen (default: '/page-config/upload-image')
 * @property int $maxWidth Ancho máximo para mostrar la imagen (default: 300)
 * @property int $maxHeight Alto máximo para mostrar la imagen (default: 300)
 */
class ImageUploadWidget extends Widget
{
    /**
     * @var \yii\db\ActiveRecord El modelo
     */
    public $model;

    /**
     * @var string El atributo del modelo
     */
    public $attribute;

    /**
     * @var string La etiqueta del campo
     */
    public $label;

    /**
     * @var array Opciones adicionales
     */
    public $options = [];

    /**
     * @var string URL para subir la imagen
     */
    public $uploadUrl = '/page-config/upload-image';

    /**
     * @var int Ancho máximo para mostrar la imagen
     */
    public $maxWidth = 300;

    /**
     * @var int Alto máximo para mostrar la imagen
     */
    public $maxHeight = 300;

    /**
     * @var string Nombre personalizado del campo (útil para arrays como Model[index][attribute])
     */
    public $inputName;

    /**
     * @var string ID único del widget
     */
    private $widgetId;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->widgetId = 'image-upload-' . $this->getId();
        
        if (empty($this->label)) {
            $this->label = $this->model->getAttributeLabel($this->attribute);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $value = $this->model->{$this->attribute};
        // Generar un ID único basado en el widgetId para evitar conflictos
        $baseInputId = Html::getInputId($this->model, $this->attribute);
        $inputId = $this->widgetId . '-' . $baseInputId;
        $inputName = $this->inputName ? $this->inputName : Html::getInputName($this->model, $this->attribute);
        
        $imageUrl = !empty($value) ? $value : null;
        $hasImage = !empty($imageUrl);

        $html = '<div class="image-upload-widget" id="' . $this->widgetId . '">';
        
        // Label
        $html .= '<label class="control-label">' . Html::encode($this->label) . '</label>';
        
        // Contenedor de la imagen
        $html .= '<div class="image-preview-container" style="margin-bottom: 15px;">';
        if ($hasImage) {
            $html .= '<div class="image-preview-wrapper">';
            $html .= '<img src="' . Html::encode($imageUrl) . '" alt="Preview" class="image-preview" style="max-width: ' . $this->maxWidth . 'px; max-height: ' . $this->maxHeight . 'px; border: 1px solid #ddd; border-radius: 4px; padding: 5px; background: #f9f9f9;">';
            $html .= '<button type="button" class="btn btn-sm btn-danger remove-image-btn" style="margin-top: 10px; display: block;">Eliminar Imagen</button>';
            $html .= '</div>';
        } else {
            $html .= '<div class="no-image-placeholder" style="border: 2px dashed #ddd; padding: 20px; text-align: center; color: #999; border-radius: 4px;">';
            $html .= '<p>No hay imagen seleccionada</p>';
            $html .= '</div>';
        }
        $html .= '</div>';
        
        // Input file
        $html .= '<div class="form-group">';
        $html .= '<input type="file" id="' . $inputId . '_file" name="' . $inputName . '_file" accept="image/*" class="form-control-file image-file-input" style="margin-bottom: 10px;">';
        $html .= '<button type="button" class="btn btn-primary upload-image-btn">Subir Imagen</button>';
        $html .= '</div>';
        
        // Input hidden para la URL
        if ($this->inputName) {
            // Si se especificó un nombre personalizado, crear el input manualmente
            $html .= '<input type="hidden" id="' . $inputId . '" name="' . Html::encode($inputName) . '" value="' . Html::encode($value) . '">';
        } else {
            $html .= Html::activeHiddenInput($this->model, $this->attribute, [
                'id' => $inputId,
            ]);
        }
        
        // Mensaje de estado
        $html .= '<div class="upload-status" style="margin-top: 10px; display: none;"></div>';
        
        $html .= '</div>';

        // Registrar JavaScript
        $this->registerJs($inputId, $this->widgetId);

        return $html;
    }

    /**
     * Registra el JavaScript necesario
     */
    protected function registerJs($inputId, $widgetId)
    {
        $uploadUrl = Url::to([$this->uploadUrl]);
        $csrfToken = Yii::$app->request->csrfToken;
        $csrfParam = Yii::$app->request->csrfParam;

        $js = <<<JS
(function() {
    var widgetId = '{$widgetId}';
    var inputId = '{$inputId}';
    var fileInputId = inputId + '_file';
    var uploadUrl = '{$uploadUrl}';
    var csrfToken = '{$csrfToken}';
    var csrfParam = '{$csrfParam}';
    
    var \$widget = $('#' + widgetId);
    var \$fileInput = $('#' + fileInputId);
    var \$hiddenInput = $('#' + inputId);
    var \$uploadBtn = \$widget.find('.upload-image-btn');
    var \$removeBtn = \$widget.find('.remove-image-btn');
    var \$statusDiv = \$widget.find('.upload-status');
    var \$previewContainer = \$widget.find('.image-preview-container');
    
    // Función para mostrar mensaje de estado
    function showStatus(message, type) {
        type = type || 'info';
        var alertClass = type === 'error' ? 'alert-danger' : (type === 'success' ? 'alert-success' : 'alert-info');
        \$statusDiv.html('<div class="alert ' + alertClass + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button></div>').show();
    }
    
    // Función para actualizar la vista previa
    function updatePreview(imageUrl) {
        if (imageUrl) {
            \$previewContainer.html(
                '<div class="image-preview-wrapper">' +
                '<img src="' + imageUrl + '" alt="Preview" class="image-preview" style="max-width: {$this->maxWidth}px; max-height: {$this->maxHeight}px; border: 1px solid #ddd; border-radius: 4px; padding: 5px; background: #f9f9f9;">' +
                '<button type="button" class="btn btn-sm btn-danger remove-image-btn" style="margin-top: 10px; display: block;">Eliminar Imagen</button>' +
                '</div>'
            );
            \$hiddenInput.val(imageUrl);
        } else {
            \$previewContainer.html(
                '<div class="no-image-placeholder" style="border: 2px dashed #ddd; padding: 20px; text-align: center; color: #999; border-radius: 4px;">' +
                '<p>No hay imagen seleccionada</p>' +
                '</div>'
            );
            \$hiddenInput.val('');
        }
    }
    
    // Manejar clic en botón de subir
    \$uploadBtn.on('click', function() {
        var file = \$fileInput[0].files[0];
        if (!file) {
            showStatus('Por favor selecciona un archivo', 'error');
            return;
        }
        
        // Validar tipo de archivo
        if (!file.type.match('image.*')) {
            showStatus('Por favor selecciona un archivo de imagen válido', 'error');
            return;
        }
        
        // Validar tamaño (max 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showStatus('El archivo es demasiado grande. Máximo 5MB', 'error');
            return;
        }
        
        // Crear FormData
        var formData = new FormData();
        formData.append('file', file);
        formData.append(csrfParam, csrfToken);
        formData.append('attribute', '{$this->attribute}');
        
        // Deshabilitar botón durante la subida
        \$uploadBtn.prop('disabled', true).text('Subiendo...');
        \$statusDiv.hide();
        
        // Subir archivo
        $.ajax({
            url: uploadUrl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success && response.url) {
                    updatePreview(response.url);
                    showStatus('Imagen subida exitosamente', 'success');
                    \$fileInput.val(''); // Limpiar input
                } else {
                    showStatus(response.message || 'Error al subir la imagen', 'error');
                }
            },
            error: function(xhr) {
                var message = 'Error al subir la imagen';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                showStatus(message, 'error');
            },
            complete: function() {
                \$uploadBtn.prop('disabled', false).text('Subir Imagen');
            }
        });
    });
    
    // Manejar clic en botón de eliminar
    \$(document).on('click', '#' + widgetId + ' .remove-image-btn', function() {
        if (confirm('¿Estás seguro de que deseas eliminar esta imagen?')) {
            updatePreview(null);
            \$fileInput.val('');
            showStatus('Imagen eliminada', 'success');
        }
    });
    
    // Permitir arrastrar y soltar
    var \$dropZone = \$previewContainer;
    \$dropZone.on('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        \$(this).addClass('drag-over');
    });
    
    \$dropZone.on('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        \$(this).removeClass('drag-over');
    });
    
    \$dropZone.on('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        \$(this).removeClass('drag-over');
        
        var files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            \$fileInput[0].files = files;
            \$uploadBtn.trigger('click');
        }
    });
})();
JS;

        $this->getView()->registerJs($js);
    }
}

