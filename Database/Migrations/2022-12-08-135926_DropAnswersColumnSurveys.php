<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DropAnswersColumnSurveys extends Migration
{
    public function up()
    {
        $this->forge->dropColumn('survey_form_answers', 'answers');
    }

    public function down()
    {
        //
    }
}
