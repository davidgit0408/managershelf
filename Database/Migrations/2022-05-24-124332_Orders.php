<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Orders extends Migration
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
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_cart' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'id_scenario' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_company' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'total' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'payment_method' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'data timestamp default current_timestamp on update current_timestamp',
            
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('orders');
    }

    public function down()
    {
        //
    }
}
