<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddQtyToPositions extends Migration
{
    public function up()
    {
        $this->forge->addColumn('positions', [
            'qty' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => '1'
            ]
        ]);
    }

    public function down()
    {
        //
    }
}
