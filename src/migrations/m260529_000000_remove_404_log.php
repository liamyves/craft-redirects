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
        $this->createTable('{{%redirects_404s}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string(500)->notNull(),
            'hitCount' => $this->integer()->notNull()->defaultValue(1),
            'lastHitAt' => $this->dateTime()->notNull(),
            'dateCreated' => $this->dateTime()->notNull(),
            'dateUpdated' => $this->dateTime()->notNull(),
            'uid' => $this->uid(),
        ]);

        $this->createIndex(null, '{{%redirects_404s}}', ['url'], true);

        return true;
    }
}
