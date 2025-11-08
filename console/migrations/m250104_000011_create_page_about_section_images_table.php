<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_about_section_images}}`.
 */
class m250104_000011_create_page_about_section_images_table extends Migration
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

        $this->createTable('{{%page_about_section_images}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'about_section_id' => $this->bigInteger()->unsigned()->notNull(),
            'icon' => $this->string(100)->null(),
            'text' => $this->string(255)->notNull(),
            'sort_order' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Create indexes
        $this->createIndex('idx_page_about_section_images_section_id', '{{%page_about_section_images}}', 'about_section_id');
        $this->createIndex('idx_page_about_section_images_sort_order', '{{%page_about_section_images}}', 'sort_order');

        // Add foreign key constraint
        $this->addForeignKey(
            'fk_page_about_section_images_section',
            '{{%page_about_section_images}}',
            'about_section_id',
            '{{%page_about_section}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_page_about_section_images_section', '{{%page_about_section_images}}');
        $this->dropIndex('idx_page_about_section_images_sort_order', '{{%page_about_section_images}}');
        $this->dropIndex('idx_page_about_section_images_section_id', '{{%page_about_section_images}}');
        $this->dropTable('{{%page_about_section_images}}');
    }
}

