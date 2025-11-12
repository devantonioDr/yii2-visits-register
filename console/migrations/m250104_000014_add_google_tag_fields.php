<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%page_google_tag_manager}}`.
 * Adds Google Tag (gtag.js) support fields.
 */
class m250104_000014_add_google_tag_fields extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%page_google_tag_manager}}', 'gtag_id', $this->string(50)->null()->after('gtm_id'));
        $this->addColumn('{{%page_google_tag_manager}}', 'gtag_enabled', $this->tinyInteger(1)->notNull()->defaultValue(0)->after('enabled'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%page_google_tag_manager}}', 'gtag_id');
        $this->dropColumn('{{%page_google_tag_manager}}', 'gtag_enabled');
    }
}

