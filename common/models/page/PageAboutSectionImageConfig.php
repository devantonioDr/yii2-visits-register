<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_about_section_images}}".
 *
 * @property int $id
 * @property int $about_section_id
 * @property string|null $icon
 * @property string $text
 * @property int $sort_order
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PageAboutSectionConfig $aboutSection
 */
class PageAboutSectionImageConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_about_section_images}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['about_section_id', 'text'], 'required'],
            [['about_section_id', 'sort_order'], 'integer'],
            [['sort_order'], 'default', 'value' => 0],
            [['icon'], 'string', 'max' => 100],
            [['text'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
            [['about_section_id'], 'exist', 'skipOnError' => true, 'targetClass' => PageAboutSectionConfig::class, 'targetAttribute' => ['about_section_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'about_section_id' => 'About Section ID',
            'icon' => 'Icon',
            'text' => 'Text',
            'sort_order' => 'Sort Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[AboutSection]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAboutSection()
    {
        return $this->hasOne(PageAboutSectionConfig::class, ['id' => 'about_section_id']);
    }
}

