<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_services}}".
 *
 * @property int $id
 * @property string|null $icon
 * @property string $title
 * @property string|null $description
 * @property string $delay
 * @property int $sort_order
 * @property string $created_at
 * @property string $updated_at
 */
class PageServiceConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_services}}';
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
            [['title'], 'required'],
            [['description'], 'string'],
            [['sort_order'], 'integer'],
            [['sort_order'], 'default', 'value' => 0],
            [['icon'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 255],
            [['delay'], 'string', 'max' => 50],
            [['delay'], 'default', 'value' => '0'],
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
            'icon' => 'Icon',
            'title' => 'Title',
            'description' => 'Description',
            'delay' => 'Delay',
            'sort_order' => 'Sort Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Get all services ordered by sort_order
     * @return static[]
     */
    public static function getAllOrdered()
    {
        return static::find()->orderBy(['sort_order' => SORT_ASC])->all();
    }
}

