<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Company extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'qtd_scenarios' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'qtd_pesquisa' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'qtd_eyetracking' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'dt_begin' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],     
            'dt_end' => [
                'type' => 'LONGTEXT',
            ],   
            'created_at timestamp default current_timestamp',  
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('company');
    }

    public function down()
    {
        //
    }
}
