<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMarginColumn extends Migration
{
    public function up()
    {
        $this->forge->addColumn('positions', [
            'cpf' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],   
        ]); 
    }

    public function down()
    {
        //
    }
}
