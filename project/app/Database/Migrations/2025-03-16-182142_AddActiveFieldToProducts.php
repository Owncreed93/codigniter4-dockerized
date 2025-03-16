<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddActiveFieldToProducts extends Migration
{
    public function up()
    {
        $this->forge->addColumn('products', [
            'active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'null' => false
            ]
        ]);

        $this->forge->dropColumn('products', ['created_at', 'updated_at']);
    }

    public function down()
    {
        $this->forge->dropColumn('products', ['active']);

        $this->forge->addColumn('products', [
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
    }
}
