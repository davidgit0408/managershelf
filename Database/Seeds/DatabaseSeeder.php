<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Criando usuário administrador
        $admin = [
            'id'            => '1',
            'id_company'    => '1764',
            'created_by'    => '0',
            'name'          => 'Admin',
            'role'          => 'admin',
            'email'         => 'admin@admin.com',
            'email_confirm' => '1',
            'pass'          => '81dc9bdb52d04dc20036dbd8313ed055', //1234
            'img_url'       => 'assets/uploads/perfil/1/219983.png',
            'view_popup'    => '1',
        ];
        $this->db->table('users')->insert($admin);

        // Criando permissões
        $permissions = [
            [
                'permission_name' => 'MENU_RELATORIOS',
                'id' => 1,
            ],
            [
                'permission_name' => 'MENU_ESTUDOS',
                'id' => 2,
            ],
            [
                'permission_name' => 'MENU_USUARIOS',
                'id' => 3,
            ],
            [
                'permission_name' => 'MENU_VERSOES',
                'id' => 4,
            ],
            [
                'permission_name' => 'MENU_LOGS',
                'id' => 5,
            ],
            [
                'permission_name' => 'MENU_CENARIOS',
                'id' => 6,
            ],
            [
                'permission_name' => 'MANAGE_PERMISSIONS',
                'id' => 7,
            ],
            [
                'permission_name' => 'TESTE_COMPRA',
                'id' => 8,
            ],
            [
                'permission_name' => 'IMPORTAR_ENTREVISTADOS',
                'id' => 9,
            ],
        ];
        foreach ($permissions as $line) {
            $this->db->table('permission')->insert($line);
        }

        // Definindo permissões do administrador
        $adminPermissions = [
            [
                'id_permission' => 1,
                'id_user' => 1,
            ],
            [
                'id_permission' => 2,
                'id_user' => 1,
            ],
            [
                'id_permission' => 3,
                'id_user' => 1,
            ],
            [
                'id_permission' => 4,
                'id_user' => 1,
            ],
            [
                'id_permission' => 5,
                'id_user' => 1,
            ],
            [
                'id_permission' => 6,
                'id_user' => 1,
            ],
            [
                'id_permission' => 7,
                'id_user' => 1,
            ],
            [
                'id_permission' => 8,
                'id_user' => 1,
            ],
            [
                'id_permission' => 9,
                'id_user' => 1,
            ],
        ];
        foreach ($adminPermissions as $line) {
            $this->db->table('permission_user')->insert($line);
        }

        // Versão do sistema
        $version = [
            'id'         => '1', 
            'number'     => '1.0',
            'note'       => '<p>Essa é a primeira versão do nosso sistema!</p>',
            'created_at' => '2021-07-14 18:23:48',
           
        ];
        $this->db->table('versions')->insert($version);
    }

}

