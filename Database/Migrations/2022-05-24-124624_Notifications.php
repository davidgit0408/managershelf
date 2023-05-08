<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Notifications extends Migration
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
            'content' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
            ],
            'show_to' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'data timestamp default current_timestamp',
            'view' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('notifications');
    }

    public function down()
    {
        //
    }
}
