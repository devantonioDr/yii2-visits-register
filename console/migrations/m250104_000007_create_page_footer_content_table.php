<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_footer_content}}`.
 */
class m250104_000007_create_page_footer_content_table extends Migration
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

        $this->createTable('{{%page_footer_content}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'brand_name' => $this->string(255)->notNull(),
            'brand_type' => "ENUM('text', 'image') NOT NULL DEFAULT 'text'",
            'brand_logo' => $this->string(500)->null(),
            'brand_logo_alt' => $this->string(255)->null(),
            'brand_logo_max_width' => $this->string(50)->null(),
            'brand_description' => $this->text()->null(),
            'address' => $this->string(500)->null(),
            'phone' => $this->string(50)->null(),
            'email' => $this->string(255)->null(),
            'copyright' => $this->string(500)->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_footer_content}}');
    }
}

