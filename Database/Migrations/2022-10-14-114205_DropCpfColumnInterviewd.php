<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropCpfColumnInterviewd extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('interviewed_bigdata', 'cpf');
    }

    public function down()
    {
        //
    }
}
