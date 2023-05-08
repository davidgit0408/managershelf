<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Perguntas extends Migration
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
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'pergunta' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'resposta' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('perguntas');
    }

    public function down()
    {
        //
    }
}
