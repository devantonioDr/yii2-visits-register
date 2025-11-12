<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_social_links}}".
 *
 * @property int $id
 * @property string $platform
 * @property string $url
 * @property string|null $icon
 * @property int $sort_order
 * @property string $created_at
 * @property string $updated_at
 */
class PageSocialLinkConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_social_links}}';
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
            [['platform', 'url'], 'required'],
            [['sort_order'], 'integer'],
            [['sort_order'], 'default', 'value' => 0],
            [['platform'], 'string', 'max' => 100],
            [['url'], 'string', 'max' => 500],
            [['url'], 'url'],
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
            'platform' => 'Platform',
            'url' => 'URL',
            'icon' => 'Icon',
            'sort_order' => 'Sort Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Get all social links ordered by sort_order
     * @return static[]
     */
    public static function getAllOrdered()
    {
        return static::find()->orderBy(['sort_order' => SORT_ASC])->all();
    }
}

