<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_about_section}}`.
 */
class m250104_000006_create_page_about_section_table extends Migration
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

        $this->createTable('{{%page_about_section}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'badge' => $this->string(255)->null(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text()->null(),
            'image_url' => $this->string(500)->null(),
            'image_alt' => $this->string(255)->null(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_about_section}}');
    }
}

