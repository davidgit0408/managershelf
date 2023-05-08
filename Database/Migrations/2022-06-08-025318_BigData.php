<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class BigData extends Migration
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
                'constraint' => '255',
            ],
            'image' => [
                'type' => 'LONGTEXT',
            ],
            'url' => [
                'type' => 'LONGTEXT',
            ],
            'feature' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'ean' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'brand' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'price' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
            ],
            'grammage' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'height' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'width' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'producer' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('bigdata_products');
    }

    public function down()
    {
        //
    }
}
