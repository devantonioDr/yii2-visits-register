<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_about_section}}".
 *
 * @property int $id
 * @property string|null $badge
 * @property string $title
 * @property string|null $description
 * @property string|null $image_url
 * @property string|null $image_alt
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PageAboutSectionImageConfig[] $aboutSectionImages
 */
class PageAboutSectionConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_about_section}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['badge', 'title', 'image_alt'], 'string', 'max' => 255],
            [['image_url'], 'string', 'max' => 500],
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
            'badge' => 'Badge',
            'title' => 'Title',
            'description' => 'Description',
            'image_url' => 'Image URL',
            'image_alt' => 'Image Alt',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AboutSectionImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAboutSectionImages()
    {
        return $this->hasMany(PageAboutSectionImageConfig::class, ['about_section_id' => 'id'])
            ->orderBy(['sort_order' => SORT_ASC]);
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

