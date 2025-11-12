<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_custom_scripts}}".
 *
 * @property int $id
 * @property string $label Label/Name for the script
 * @property string $script Script content (long text)
 * @property int $enabled Whether script is enabled (1) or disabled (0)
 * @property int $sort_order Sort order for display
 * @property string $created_at
 * @property string $updated_at
 */
class PageCustomScriptConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_custom_scripts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['label', 'script'], 'required'],
            [['script'], 'string'],
            [['enabled', 'sort_order'], 'integer'],
            [['enabled'], 'default', 'value' => 0],
            [['sort_order'], 'default', 'value' => 0],
            [['label'], 'string', 'max' => 255],
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
            'label' => 'Label',
            'script' => 'Script',
            'enabled' => 'Enabled',
            'sort_order' => 'Sort Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Get all enabled scripts ordered by sort_order
     * @return static[]
     */
    public static function getEnabledScripts()
    {
        return static::find()
            ->where(['enabled' => 1])
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();
    }

    /**
     * Get all scripts ordered by sort_order
     * @return static[]
     */
    public static function getAllOrdered()
    {
        return static::find()
            ->orderBy(['sort_order' => SORT_ASC])
            ->all();
    }
}

