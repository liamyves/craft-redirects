<?php

namespace recranet\redirects\migrations;

use craft\db\Migration;

class m260529_000000_remove_404_log extends Migration
{
    public function safeUp(): bool
    {
        $this->dropTableIfExists('{{%redirects_404s}}');

        return true;
    }

    public function safeDown(): bool
    {
        return true;
    }
}
