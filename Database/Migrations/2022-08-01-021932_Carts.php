<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Carts extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'id_cart' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'id_client' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'id_scenario' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'id_company' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'product_ean' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'removed_cart' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'removed_checkout' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'sequence' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'viewed' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'bought' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'time' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'ip_public' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'ip_private' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'data timestamp default current_timestamp',
        
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('carts');
    }

    public function down()
    {
        //
    }
}
