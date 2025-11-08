<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_call_to_action}}`.
 */
class m250104_000005_create_page_call_to_action_table extends Migration
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

        $this->createTable('{{%page_call_to_action}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'text' => $this->string(255)->notNull(),
            'link' => $this->string(500)->notNull(),
            'target' => "ENUM('_blank', '_self') NOT NULL DEFAULT '_self'",
            'icon' => $this->string(100)->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_call_to_action}}');
    }
}

