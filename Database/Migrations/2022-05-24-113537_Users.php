<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Users extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_company'          => [
                'type'           => 'INT',
                'constraint'     => 11,
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => '1',
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'birthday' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'tel' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'cellphone' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'cpf_cnpj' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'genre' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'email_confirm' => [
                'type' => 'TINYINT',
                'constraint' => '4',
                'default' => '0',
            ],
            'pass' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'img_url' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'rg' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'client' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'curse' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'marital_status' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'address' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'district' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'country' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'zipcode' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'addtional_info' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'view_popup' => [
                'type' => 'TINYINT',
                'constraint' => '4',
                'null' => true,
            ],
            'print_eye_tracking' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'created_at timestamp default current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('users');
    }

    public function down()
    {
        //
    }
}
