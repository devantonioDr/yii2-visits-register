<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_brand_colors}}".
 *
 * @property int $id
 * @property string $primary
 * @property string $on_primary
 * @property string $primary_container
 * @property string $on_primary_container
 * @property string $secondary
 * @property string $on_secondary
 * @property string $secondary_container
 * @property string $on_secondary_container
 * @property string $tertiary
 * @property string $on_tertiary
 * @property string $tertiary_container
 * @property string $on_tertiary_container
 * @property string $error
 * @property string $on_error
 * @property string $error_container
 * @property string $on_error_container
 * @property string $surface
 * @property string $on_surface
 * @property string $surface_variant
 * @property string $on_surface_variant
 * @property string $outline
 * @property string $outline_variant
 * @property string $shadow
 * @property string $scrim
 * @property string $inverse_surface
 * @property string $inverse_on_surface
 * @property string $inverse_primary
 * @property string $background
 * @property string $on_background
 * @property string $created_at
 * @property string $updated_at
 */
class PageBrandColorsConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_brand_colors}}';
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['primary', 'on_primary', 'primary_container', 'on_primary_container', 
              'secondary', 'on_secondary', 'secondary_container', 'on_secondary_container',
              'tertiary', 'on_tertiary', 'tertiary_container', 'on_tertiary_container',
              'error', 'on_error', 'error_container', 'on_error_container',
              'surface', 'on_surface', 'surface_variant', 'on_surface_variant',
              'outline', 'outline_variant', 'shadow', 'scrim',
              'inverse_surface', 'inverse_on_surface', 'inverse_primary',
              'background', 'on_background'], 'required'],
            [['primary', 'on_primary', 'primary_container', 'on_primary_container',
              'secondary', 'on_secondary', 'secondary_container', 'on_secondary_container',
              'tertiary', 'on_tertiary', 'tertiary_container', 'on_tertiary_container',
              'error', 'on_error', 'error_container', 'on_error_container',
              'surface', 'on_surface', 'surface_variant', 'on_surface_variant',
              'outline', 'outline_variant', 'shadow', 'scrim',
              'inverse_surface', 'inverse_on_surface', 'inverse_primary',
              'background', 'on_background'], 'string', 'max' => 7],
            [['primary', 'on_primary', 'primary_container', 'on_primary_container',
              'secondary', 'on_secondary', 'secondary_container', 'on_secondary_container',
              'tertiary', 'on_tertiary', 'tertiary_container', 'on_tertiary_container',
              'error', 'on_error', 'error_container', 'on_error_container',
              'surface', 'on_surface', 'surface_variant', 'on_surface_variant',
              'outline', 'outline_variant', 'shadow', 'scrim',
              'inverse_surface', 'inverse_on_surface', 'inverse_primary',
              'background', 'on_background'], 'match', 'pattern' => '/^#[0-9A-Fa-f]{6}$/', 'message' => 'Color must be a valid hexadecimal color (e.g., #RRGGBB)'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'primary' => 'Primary',
            'on_primary' => 'On Primary',
            'primary_container' => 'Primary Container',
            'on_primary_container' => 'On Primary Container',
            'secondary' => 'Secondary',
            'on_secondary' => 'On Secondary',
            'secondary_container' => 'Secondary Container',
            'on_secondary_container' => 'On Secondary Container',
            'tertiary' => 'Tertiary',
            'on_tertiary' => 'On Tertiary',
            'tertiary_container' => 'Tertiary Container',
            'on_tertiary_container' => 'On Tertiary Container',
            'error' => 'Error',
            'on_error' => 'On Error',
            'error_container' => 'Error Container',
            'on_error_container' => 'On Error Container',
            'surface' => 'Surface',
            'on_surface' => 'On Surface',
            'surface_variant' => 'Surface Variant',
            'on_surface_variant' => 'On Surface Variant',
            'outline' => 'Outline',
            'outline_variant' => 'Outline Variant',
            'shadow' => 'Shadow',
            'scrim' => 'Scrim',
            'inverse_surface' => 'Inverse Surface',
            'inverse_on_surface' => 'Inverse On Surface',
            'inverse_primary' => 'Inverse Primary',
            'background' => 'Background',
            'on_background' => 'On Background',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Get configuration singleton
     * @return static|null
     */
    public static function getConfig()
    {
        return static::find()->one();
    }
}

