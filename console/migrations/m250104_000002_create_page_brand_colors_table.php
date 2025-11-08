<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_brand_colors}}`.
 */
class m250104_000002_create_page_brand_colors_table extends Migration
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

        $this->createTable('{{%page_brand_colors}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'primary' => $this->char(7)->notNull()->defaultValue('#000000'),
            'on_primary' => $this->char(7)->notNull()->defaultValue('#ffffff'),
            'primary_container' => $this->char(7)->notNull()->defaultValue('#ffffff'),
            'on_primary_container' => $this->char(7)->notNull()->defaultValue('#000000'),
            'secondary' => $this->char(7)->notNull()->defaultValue('#000000'),
            'on_secondary' => $this->char(7)->notNull()->defaultValue('#ffffff'),
            'secondary_container' => $this->char(7)->notNull()->defaultValue('#ffffff'),
            'on_secondary_container' => $this->char(7)->notNull()->defaultValue('#000000'),
            'tertiary' => $this->char(7)->notNull()->defaultValue('#000000'),
            'on_tertiary' => $this->char(7)->notNull()->defaultValue('#ffffff'),
            'tertiary_container' => $this->char(7)->notNull()->defaultValue('#ffffff'),
            'on_tertiary_container' => $this->char(7)->notNull()->defaultValue('#000000'),
            'error' => $this->char(7)->notNull()->defaultValue('#ba1a1a'),
            'on_error' => $this->char(7)->notNull()->defaultValue('#ffffff'),
            'error_container' => $this->char(7)->notNull()->defaultValue('#ffdad6'),
            'on_error_container' => $this->char(7)->notNull()->defaultValue('#410002'),
            'surface' => $this->char(7)->notNull()->defaultValue('#fefefe'),
            'on_surface' => $this->char(7)->notNull()->defaultValue('#1c1c1c'),
            'surface_variant' => $this->char(7)->notNull()->defaultValue('#e7e7e7'),
            'on_surface_variant' => $this->char(7)->notNull()->defaultValue('#5f5f5f'),
            'outline' => $this->char(7)->notNull()->defaultValue('#c7c7c7'),
            'outline_variant' => $this->char(7)->notNull()->defaultValue('#e0e0e0'),
            'shadow' => $this->char(7)->notNull()->defaultValue('#000000'),
            'scrim' => $this->char(7)->notNull()->defaultValue('#000000'),
            'inverse_surface' => $this->char(7)->notNull()->defaultValue('#1c1c1c'),
            'inverse_on_surface' => $this->char(7)->notNull()->defaultValue('#f4f4f4'),
            'inverse_primary' => $this->char(7)->notNull()->defaultValue('#9bdc8f'),
            'background' => $this->char(7)->notNull()->defaultValue('#f8fdf7'),
            'on_background' => $this->char(7)->notNull()->defaultValue('#1a1c1a'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_brand_colors}}');
    }
}

