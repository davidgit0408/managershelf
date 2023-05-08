<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Versions extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'number'       => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'note' => [
                'type' => 'LONGTEXT',
            ],
            'created_at timestamp default current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('versions');
    }

    public function down()
    {
        //
    }
}
