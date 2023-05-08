<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserCompany extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'company' => [
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
