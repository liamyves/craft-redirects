<?php

namespace recranet\redirects\migrations;

use craft\db\Migration;

class m260529_000002_remove_created_by extends Migration
{
    public function safeUp(): bool
    {
         $this->dropForeignKeyIfExists("{{%redirects}}", 'createdById');
        $this->dropColumn('{{%redirects}}', 'createdById');

        return true;
    }

    public function safeDown(): bool
    {
        $this->addColumn('{{%redirects}}', 'createdById', $this->integer()->null()->after('enabled'));
        $this->addForeignKey(null, '{{%redirects}}', 'createdById', '{{%users}}', 'id', 'SET NULL');

        return true;
    }
}
