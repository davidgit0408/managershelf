<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddInterviewdCpf extends Migration
{
    public function up()
    {
        $this->forge->addColumn('interviewed_data', [
            'cpf' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],   
        ]); 
    }

    public function down()
    {
        //
    }
}
