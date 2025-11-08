<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_portfolio_images}}`.
 */
class m250104_000010_create_page_portfolio_images_table extends Migration
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

        $this->createTable('{{%page_portfolio_images}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'portfolio_section_id' => $this->bigInteger()->unsigned()->null(),
            'url' => $this->string(500)->notNull(),
            'alt' => $this->string(255)->null(),
            'title' => $this->string(255)->null(),
            'sort_order' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Create indexes
        $this->createIndex('idx_page_portfolio_images_section_id', '{{%page_portfolio_images}}', 'portfolio_section_id');
        $this->createIndex('idx_page_portfolio_images_sort_order', '{{%page_portfolio_images}}', 'sort_order');

        // Add foreign key constraint
        $this->addForeignKey(
            'fk_page_portfolio_images_section',
            '{{%page_portfolio_images}}',
            'portfolio_section_id',
            '{{%page_portfolio_section}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_page_portfolio_images_section', '{{%page_portfolio_images}}');
        $this->dropIndex('idx_page_portfolio_images_sort_order', '{{%page_portfolio_images}}');
        $this->dropIndex('idx_page_portfolio_images_section_id', '{{%page_portfolio_images}}');
        $this->dropTable('{{%page_portfolio_images}}');
    }
}

