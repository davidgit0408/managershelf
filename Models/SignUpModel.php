<?php

namespace App\Models;

use CodeIgniter\Model;

class SignUpModel extends Model
{
    protected $db;
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id_company',
        'created_by',
        'created_at',
        'company',
        'name',
        'birthday',
        'tel',
        'cellphone',
        'cpf_cnpj',
        'role',
        'genre',
        'email',
        'email_confirm',
        'pass',
        'img_url',
        'rg',
        'client',
        'curse',
        'marital_status',
        'address',
        'district',
        'city',
        'state',
        'country',
        'zipcode',
        'addtional_info',
        'view_popup',
        'print_eye_tracking'
    ];

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function get_user_by_email($email)
    {
        $builder_users = $this->db->table('users');
        $builder_users->where('email', $email);

        $query = $builder_users->orderBy('id', 'ASC')->get();

        if ($query->getNumRows() > 0) 
            return $query->getResultArray();
        else
            throw new \Exception("Não há nenhum usário cadastrado com o email informado.");
    }

    public function get_user_by_cpf_cnpj($cpf_cnpj)
    {
        $builder_users = $this->db->table('users');
        $builder_users->where('cpf_cnpj', $cpf_cnpj);
        $query = $builder_users->orderBy('id', 'ASC')->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }
        throw new \Exception("Não há nenhum usário cadastrado com o email informado.");
    }

    public function confirm_email($email)
    {
        $builder_users = $this->db->table('users');
        $builder_users->set(["email_confirm" => 1]);
        $builder_users->where('email', $email);
        $builder_users->update();
    }
}
