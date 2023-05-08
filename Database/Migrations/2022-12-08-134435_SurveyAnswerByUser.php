<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SurveyAnswerByUser extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'id_survey' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'title' => [
                'type' => 'LONGTEXT',
            ],
            'answers' => [
                'type' => 'LONGTEXT'
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('survey_answer_by_user');
    }

    public function down()
    {
        //
    }
}
