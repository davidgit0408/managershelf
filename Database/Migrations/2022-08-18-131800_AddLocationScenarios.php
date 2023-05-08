<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationScenarios extends Migration
{
    public function up()
    {
        $this->forge->addColumn('scenarios', [
            'location' => [
                'type'           => 'VARCHAR',
                'constraint'     => '255',
                'null' => true,
            ],
        ]);

    }

    public function down()
    {
        //
    }
}
