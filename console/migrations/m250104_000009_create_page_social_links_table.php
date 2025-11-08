<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_social_links}}`.
 */
class m250104_000009_create_page_social_links_table extends Migration
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

        $this->createTable('{{%page_social_links}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'platform' => $this->string(100)->notNull(),
            'url' => $this->string(500)->notNull(),
            'icon' => $this->string(100)->null(),
            'sort_order' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Create indexes
        $this->createIndex('idx_page_social_links_platform', '{{%page_social_links}}', 'platform');
        $this->createIndex('idx_page_social_links_sort_order', '{{%page_social_links}}', 'sort_order');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_page_social_links_sort_order', '{{%page_social_links}}');
        $this->dropIndex('idx_page_social_links_platform', '{{%page_social_links}}');
        $this->dropTable('{{%page_social_links}}');
    }
}

