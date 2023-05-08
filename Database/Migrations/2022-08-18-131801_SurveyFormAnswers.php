<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SurveyFormAnswers extends Migration
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
            'id_user' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'id_company' => [
                'type' => 'INT',
                'constraint' => 11
            ],
            'answers' => [
                'type' => 'LONGTEXT'
            ],
            'data_send timestamp default current_timestamp',
        
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('survey_form_answers');
    }

    public function down()
    {
        //
    }
}
