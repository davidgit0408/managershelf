<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EyeTrackingResults extends Migration
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
            'uuid'       => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'company_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ],
            'scenario_id' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'GazeX' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'GazeY' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'HeadX' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'HeadY' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'HeadZ' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'HeadYaw' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'HeadPitch' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'HeadRoll' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'rx' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'ry' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'rw' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'rh' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'state' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'time' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'FrameNr' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'Xview' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'Yview' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'docX' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'docY' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
            ],
            'produtosVistos' => [
                'type' => 'LONGTEXT',
                'null' => true,
            ],
            'created_at timestamp default current_timestamp',
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('eye_tracking_results');
    
    }

    public function down()
    {
        //
    }
}
