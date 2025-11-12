<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_google_tag_manager}}".
 *
 * @property int $id
 * @property string|null $gtm_id Google Tag Manager ID (e.g., GTM-53MMVHXG)
 * @property string|null $gtag_id Google Tag ID (e.g., AW-16791212959 or G-XXXXXXXXXX)
 * @property int $enabled Whether GTM is enabled (1) or disabled (0)
 * @property int $gtag_enabled Whether Google Tag is enabled (1) or disabled (0)
 * @property string $created_at
 * @property string $updated_at
 */
class PageGoogleTagManagerConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_google_tag_manager}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['enabled', 'gtag_enabled'], 'integer'],
            [['enabled', 'gtag_enabled'], 'default', 'value' => 0],
            [['gtm_id', 'gtag_id'], 'string', 'max' => 50],
            [['gtm_id'], 'match', 'pattern' => '/^GTM-[A-Z0-9]+$/', 'message' => 'El ID de GTM debe tener el formato GTM-XXXXXXX'],
            [['gtag_id'], 'match', 'pattern' => '/^(AW|G)-[A-Z0-9]+$/', 'message' => 'El ID de Google Tag debe tener el formato AW-XXXXXXX o G-XXXXXXXXXX'],
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
            'gtm_id' => 'Google Tag Manager ID',
            'gtag_id' => 'Google Tag ID',
            'enabled' => 'Habilitar Google Tag Manager',
            'gtag_enabled' => 'Habilitar Google Tag',
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

    /**
     * Check if GTM is enabled and has a valid ID
     * @return bool
     */
    public function isActive()
    {
        return $this->enabled == 1 && !empty($this->gtm_id);
    }

    /**
     * Check if Google Tag is enabled and has a valid ID
     * @return bool
     */
    public function isGtagActive()
    {
        return $this->gtag_enabled == 1 && !empty($this->gtag_id);
    }
}

