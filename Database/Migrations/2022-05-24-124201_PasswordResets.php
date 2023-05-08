<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class PasswordResets extends Migration
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
            'user_email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'reset_key' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at timestamp default current_timestamp on update current_timestamp',
            
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('password_resets');
    }

    public function down()
    {
        //
    }
}
