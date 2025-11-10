<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_call_to_action}}".
 *
 * @property int $id
 * @property string $text
 * @property string $link
 * @property string $target
 * @property string|null $icon
 * @property string $created_at
 * @property string $updated_at
 */
class PageCallToActionConfig extends ActiveRecord
{
    const TARGET_BLANK = '_blank';
    const TARGET_SELF = '_self';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_call_to_action}}';
    }

    // /**
    //  * {@inheritdoc}
    //  */
    // public function behaviors()
    // {
    //     return [
    //         TimestampBehavior::class,
    //     ];
    // }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'link'], 'required'],
            [['text'], 'string', 'max' => 255],
            [['link'], 'string', 'max' => 500],
            [['link'], 'url'],
            [['target'], 'in', 'range' => [self::TARGET_BLANK, self::TARGET_SELF]],
            [['target'], 'default', 'value' => self::TARGET_SELF],
            [['icon'], 'string', 'max' => 100],
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
            'text' => 'Text',
            'link' => 'Link',
            'target' => 'Target',
            'icon' => 'Icon',
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
     * Get available target options
     * @return array
     */
    public static function getTargetOptions()
    {
        return [
            self::TARGET_SELF => 'Same Window (_self)',
            self::TARGET_BLANK => 'New Window (_blank)',
        ];
    }
}

