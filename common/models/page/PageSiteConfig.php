<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_site_config}}".
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string|null $description
 * @property string $created_at
 * @property string $updated_at
 */
class PageSiteConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_site_config}}';
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'title'], 'required'],
            [['description'], 'string'],
            [['name', 'title'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'title' => 'Title',
            'description' => 'Description',
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

