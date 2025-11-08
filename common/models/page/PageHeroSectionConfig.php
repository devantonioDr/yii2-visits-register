<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_hero_section}}".
 *
 * @property int $id
 * @property string $heading
 * @property string $heading_type
 * @property string|null $heading_image
 * @property string|null $heading_image_alt
 * @property string|null $heading_image_max_width
 * @property string|null $subheading
 * @property string $media_type
 * @property string $media_url
 * @property string|null $video_format
 * @property string|null $fallback_image_url
 * @property string|null $alt_text
 * @property string $created_at
 * @property string $updated_at
 */
class PageHeroSectionConfig extends ActiveRecord
{
    const HEADING_TYPE_TEXT = 'text';
    const HEADING_TYPE_IMAGE = 'image';

    const MEDIA_TYPE_IMAGE = 'image';
    const MEDIA_TYPE_VIDEO = 'video';

    const VIDEO_FORMAT_MP4 = 'mp4';
    const VIDEO_FORMAT_WEBM = 'webm';
    const VIDEO_FORMAT_OGG = 'ogg';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_hero_section}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['heading', 'subheading', 'media_type', 'media_url'], 'string'],
            [['heading'], 'string', 'max' => 255],
            [['heading_type'], 'in', 'range' => [self::HEADING_TYPE_TEXT, self::HEADING_TYPE_IMAGE]],
            [['heading_image', 'media_url'], 'string', 'max' => 500],
            [['heading_image_max_width'], 'string', 'max' => 50],
            [['media_type'], 'in', 'range' => [self::MEDIA_TYPE_IMAGE, self::MEDIA_TYPE_VIDEO]],
            [['video_format'], 'in', 'range' => [self::VIDEO_FORMAT_MP4, self::VIDEO_FORMAT_WEBM, self::VIDEO_FORMAT_OGG], 'skipOnEmpty' => true],
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
            'heading' => 'Heading',
            'heading_type' => 'Heading Type',
            'heading_image' => 'Heading Image',
            'heading_image_max_width' => 'Heading Image Max Width',
            'subheading' => 'Subheading',
            'media_type' => 'Media Type',
            'media_url' => 'Media URL',
            'video_format' => 'Video Format',
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
     * Get available heading types
     * @return array
     */
    public static function getHeadingTypes()
    {
        return [
            self::HEADING_TYPE_TEXT => 'Text',
            self::HEADING_TYPE_IMAGE => 'Image',
        ];
    }

    /**
     * Get available media types
     * @return array
     */
    public static function getMediaTypes()
    {
        return [
            self::MEDIA_TYPE_IMAGE => 'Image',
            self::MEDIA_TYPE_VIDEO => 'Video',
        ];
    }

    /**
     * Get available video formats
     * @return array
     */
    public static function getVideoFormats()
    {
        return [
            self::VIDEO_FORMAT_MP4 => 'MP4',
            self::VIDEO_FORMAT_WEBM => 'WebM',
            self::VIDEO_FORMAT_OGG => 'OGG',
        ];
    }
}

