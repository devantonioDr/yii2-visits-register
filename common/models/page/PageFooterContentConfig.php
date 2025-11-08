<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_footer_content}}".
 *
 * @property int $id
 * @property string $brand_name
 * @property string $brand_type
 * @property string|null $brand_logo
 * @property string|null $brand_logo_alt
 * @property string|null $brand_logo_max_width
 * @property string|null $brand_description
 * @property string|null $address
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $copyright
 * @property string $created_at
 * @property string $updated_at
 */
class PageFooterContentConfig extends ActiveRecord
{
    const BRAND_TYPE_TEXT = 'text';
    const BRAND_TYPE_IMAGE = 'image';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_footer_content}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['brand_name', 'brand_type'], 'required'],
            [['brand_description', 'copyright'], 'string'],
            [['brand_name', 'brand_logo_alt', 'phone'], 'string', 'max' => 255],
            [['brand_type'], 'in', 'range' => [self::BRAND_TYPE_TEXT, self::BRAND_TYPE_IMAGE]],
            [['brand_logo'], 'string', 'max' => 500],
            [['brand_logo_max_width'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 500],
            [['email'], 'string', 'max' => 255],
            [['email'], 'email', 'skipOnEmpty' => true],
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
            'brand_name' => 'Brand Name',
            'brand_type' => 'Brand Type',
            'brand_logo' => 'Brand Logo',
            'brand_logo_alt' => 'Brand Logo Alt',
            'brand_logo_max_width' => 'Brand Logo Max Width',
            'brand_description' => 'Brand Description',
            'address' => 'Address',
            'phone' => 'Phone',
            'email' => 'Email',
            'copyright' => 'Copyright',
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
     * Get available brand types
     * @return array
     */
    public static function getBrandTypes()
    {
        return [
            self::BRAND_TYPE_TEXT => 'Text',
            self::BRAND_TYPE_IMAGE => 'Image',
        ];
    }
}

