<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_services}}`.
 */
class m250104_000008_create_page_services_table extends Migration
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

        $this->createTable('{{%page_services}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'icon' => $this->string(100)->null(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text()->null(),
            'delay' => $this->string(50)->defaultValue('0'),
            'sort_order' => $this->integer()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Create index for sort_order
        $this->createIndex('idx_page_services_sort_order', '{{%page_services}}', 'sort_order');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_page_services_sort_order', '{{%page_services}}');
        $this->dropTable('{{%page_services}}');
    }
}

