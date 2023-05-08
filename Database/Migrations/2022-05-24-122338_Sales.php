<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Sales extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'company_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'key' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'pscode' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'active' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'total_value' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_in' => [
                'type' => 'VARCHAR',
                'constraint' => '60',
                'null' => true,
            ],
            'boleto' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'transacao' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('sales');
    }

    public function down()
    {
        //
    }
}
