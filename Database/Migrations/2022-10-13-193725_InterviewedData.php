<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InterviewedData extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],    
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('interviewed_data');
    }

    public function down()
    {
        //
    }
}
