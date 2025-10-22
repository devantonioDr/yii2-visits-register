<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%event}}`.
 */
class m240101_000000_create_event_table extends Migration
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

        $this->createTable('{{%event}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'type' => "ENUM('page_view','cta_click') NOT NULL",
            'page' => $this->string(255)->notNull()->comment('ruta o URL canónica'),
            'cta_id' => $this->string(100)->defaultValue(null)->comment('id lógico del CTA (si aplica)'),
            'referrer' => $this->string(255)->defaultValue(null)->comment('document.referrer o header Referer'),
            'device' => $this->string(50)->defaultValue(null)->comment('ej: desktop|mobile|tablet'),
            'country_iso2' => $this->char(2)->defaultValue(null)->comment('US, MX, ES, etc.'),
            'region' => $this->string(80)->defaultValue(null)->comment('estado/provincia (opcional)'),
            'city' => $this->string(120)->defaultValue(null)->comment('ciudad normalizada (opcional)'),
            'visit_id' => $this->char(36)->defaultValue(null)->comment('UUID por visita (opcional pero recomendado)'),
            'ip_hash' => $this->char(64)->notNull()->comment('hash con salt rotatorio'),
            'ua_hash' => $this->char(64)->notNull()->comment('hash con salt rotatorio'),
            'meta' => $this->json()->defaultValue(null)->comment('extras controlados (whitelist) p.ej. UTM, ab_variant, screen, lang, etc.'),
            'ts' => $this->dateTime()->notNull()->comment('UTC'),
            'created_at' => $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ], $tableOptions);

        // Create indexes
        $this->createIndex('idx_event_ts', '{{%event}}', 'ts');
        $this->createIndex('idx_event_type_ts', '{{%event}}', ['type', 'ts']);
        $this->createIndex('idx_event_page_ts', '{{%event}}', ['page', 'ts']);
        $this->createIndex('idx_event_cta_ts', '{{%event}}', ['cta_id', 'ts']);
        $this->createIndex('idx_event_ip_hash', '{{%event}}', 'ip_hash');
        $this->createIndex('idx_event_visit_ts', '{{%event}}', ['visit_id', 'ts']);
        $this->createIndex('idx_event_country_ts', '{{%event}}', ['country_iso2', 'ts']);
        $this->createIndex('idx_event_city_ts', '{{%event}}', ['city', 'ts']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%event}}');
    }
}
