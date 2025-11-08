<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_site_config}}`.
 */
class m250104_000001_create_page_site_config_table extends Migration
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

        $this->createTable('{{%page_site_config}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_site_config}}');
    }
}

