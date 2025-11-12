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
 * @property int $enabled Whether GTM is enabled (1) or disabled (0)
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
            [['enabled'], 'integer'],
            [['enabled'], 'default', 'value' => 0],
            [['gtm_id'], 'string', 'max' => 50],
            [['gtm_id'], 'match', 'pattern' => '/^GTM-[A-Z0-9]+$/', 'message' => 'El ID de GTM debe tener el formato GTM-XXXXXXX'],
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
            'enabled' => 'Habilitado',
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
}

