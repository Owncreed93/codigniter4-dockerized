<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'auto_increment' => true, 'unsigned' => true],
            'user_id' => ['type' => 'INT', 'unsigned' => true, 'null' => true],
            'model' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'record_id' => ['type' => 'INT', 'unsigned' => true],
            'action' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'old_data' => ['type' => 'TEXT', 'null' => false],
            'new_data' => ['type' => 'TEXT', 'null' => false],
            'logged_at' => ['type' => 'DATETIME', 'null' => false]
        ]);

        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('audit_logs');
    }

    public function down()
    {
        $this->forge->dropTable('audit_logs');
    }
}
