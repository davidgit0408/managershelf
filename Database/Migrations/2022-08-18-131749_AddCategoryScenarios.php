<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCategoryScenarios extends Migration
{
    public function up()
    {
        $this->forge->addColumn('scenarios', [
            'category' => [
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
