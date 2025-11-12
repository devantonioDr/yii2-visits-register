<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_google_tag_manager}}`.
 */
class m250104_000013_create_page_google_tag_manager_table extends Migration
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

        $this->createTable('{{%page_google_tag_manager}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'gtm_id' => $this->string(50)->null(),
            'enabled' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_google_tag_manager}}');
    }
}

