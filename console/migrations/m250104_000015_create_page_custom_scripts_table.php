<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_custom_scripts}}`.
 */
class m250104_000015_create_page_custom_scripts_table extends Migration
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

        $this->createTable('{{%page_custom_scripts}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'label' => $this->string(255)->notNull(),
            'script' => $this->text()->notNull(),
            'enabled' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_custom_scripts}}');
    }
}

