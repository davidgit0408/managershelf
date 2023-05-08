<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class InterviewdBigdata extends Migration
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
            'interviewd_id' => [
                'type' => 'iNT',
                'constraint' => 11
            ],
            'telefone' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],   
            'cpf' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],    
            'idade' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'profissao' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'genero' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'cep' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'logradouro' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'bairro' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'estado' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'cidade' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'filhos' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'qtd_filhos' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'age_filhos' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'renda' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'compras' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'valor_compra' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'reposicao' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'categorias' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'cupom' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'entrevista' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'fluxo_online' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'notebook' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'webcam' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'outro_dispositivo' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'conheceu' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
            'data' => [
                'type' => 'VARCHAR',
                'constraint' => 255
            ],
             
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('interviewed_bigdata');
    }

    public function down()
    {
        //
    }
}
