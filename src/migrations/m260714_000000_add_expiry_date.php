<?php

namespace recranet\redirects\migrations;

use craft\db\Migration;

class m260714_000000_add_expiry_date extends Migration
{
    public function safeUp(): bool
    {
        if (!$this->db->columnExists('{{%redirects}}', 'expiryDate')) {
            $this->addColumn('{{%redirects}}', 'expiryDate', $this->dateTime()->null()->after('enabled'));
        }

        return true;
    }

    public function safeDown(): bool
    {
        if ($this->db->columnExists('{{%redirects}}', 'expiryDate')) {
            $this->dropColumn('{{%redirects}}', 'expiryDate');
        }

        return true;
    }
}
