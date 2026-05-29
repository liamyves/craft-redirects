<?php

namespace recranet\redirects\migrations;

use craft\db\Migration;

class m260529_000001_remove_hits extends Migration
{
    public function safeUp(): bool
    {
        $this->dropColumn('{{%redirects}}', 'hitCount');
        $this->dropColumn('{{%redirects}}', 'lastHitAt');

        return true;
    }

    public function safeDown(): bool
    {
        $this->addColumn('{{%redirects}}', 'hitCount', $this->integer()->notNull()->defaultValue(0)->after('enabled'));
        $this->addColumn('{{%redirects}}', 'lastHitAt', $this->dateTime()->null()->after('hitCount'));

        return true;
    }
}
