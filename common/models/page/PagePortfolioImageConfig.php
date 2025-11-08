<?php

namespace common\models\page;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%page_portfolio_images}}".
 *
 * @property int $id
 * @property int|null $portfolio_section_id
 * @property string $url
 * @property string|null $alt
 * @property string|null $title
 * @property int $sort_order
 * @property string $created_at
 * @property string $updated_at
 *
 * @property PagePortfolioSectionConfig|null $portfolioSection
 */
class PagePortfolioImageConfig extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%page_portfolio_images}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['url'], 'required'],
            [['portfolio_section_id', 'sort_order'], 'integer'],
            [['sort_order'], 'default', 'value' => 0],
            [['url'], 'string', 'max' => 500],
            [['alt', 'title'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
            [['portfolio_section_id'], 'exist', 'skipOnError' => true, 'targetClass' => PagePortfolioSectionConfig::class, 'targetAttribute' => ['portfolio_section_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'portfolio_section_id' => 'Portfolio Section ID',
            'url' => 'URL',
            'alt' => 'Alt',
            'title' => 'Title',
            'sort_order' => 'Sort Order',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[PortfolioSection]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPortfolioSection()
    {
        return $this->hasOne(PagePortfolioSectionConfig::class, ['id' => 'portfolio_section_id']);
    }
}

