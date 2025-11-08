<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_hero_section}}`.
 */
class m250104_000003_create_page_hero_section_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%page_hero_section}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'heading' => $this->string(255)->notNull(),
            'heading_type' => "ENUM('text', 'image') NOT NULL DEFAULT 'text'",
            'heading_image' => $this->string(500)->null(),
            'heading_image_alt' => $this->string(255)->null(),
            'heading_image_max_width' => $this->string(50)->null(),
            'subheading' => $this->text()->null(),
            'media_type' => "ENUM('video', 'image') NOT NULL DEFAULT 'image'",
            'media_url' => $this->string(500)->notNull(),
            'video_format' => "ENUM('mp4', 'webm', 'ogg') NULL",
            'fallback_image_url' => $this->string(500)->null(),
            'alt_text' => $this->string(255)->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_hero_section}}');
    }
}

