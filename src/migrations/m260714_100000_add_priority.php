<?php

namespace recranet\redirects\migrations;

use craft\db\Migration;

class m260714_100000_add_priority extends Migration
{
    public function safeUp(): bool
    {
        if (!$this->db->columnExists('{{%redirects}}', 'priority')) {
            $this->addColumn('{{%redirects}}', 'priority', $this->integer()->notNull()->defaultValue(0)->after('matchType'));
        }

        return true;
    }

    public function safeDown(): bool
    {
        if ($this->db->columnExists('{{%redirects}}', 'priority')) {
            $this->dropColumn('{{%redirects}}', 'priority');
        }

        return true;
    }
}
