<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Files extends Migration
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
            'file_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'src' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'uploaded_on' => [
                'type' => 'DATETIME',
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'height' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'width' => [
                'type' => 'INT',
                'constraint' => 11,
            ],        
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('files');
    }

    public function down()
    {
        //
    }
}
