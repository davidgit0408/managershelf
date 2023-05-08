<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Scenarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'id_company' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'planogram'=> [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => '1',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '0',
            ],
            'shelves' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'default' => '0',
            ],
            'customization' => [
                'type' => 'LONGTEXT',
                'null' => true,
                'default' => null,
            ],
            'urlprintPlanogram' => [
                'type' => 'TEXT',
            ],
            'created_at timestamp default current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('scenarios');
    }

    public function down()
    {
        //
    }
}