<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Positions extends Migration
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
            'id_scenario' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_company' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => '0',
            ],
            'shelf' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'column' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => '1',
            ],
            'position' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'id_product' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'views' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'width' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'height' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('positions');
    }

    public function down()
    {
        //
    }
}
