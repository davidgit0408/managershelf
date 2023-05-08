<?php

namespace App\Models;

use CodeIgniter\Model;
class PasswordResetModel extends Model
{

    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function insert_reset_key($data)
    {
        $this->db->table('password_resets')->insert($data);
    }

    public function check_reset_key($key = null)
    {
        try {
            return $this->return_reset_key($key);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function return_reset_key($key = null)
    {
        if (!$key)
            throw new \Exception("Key inválida ou expirada.");

        $builder = $this->db->table('password_resets');
        $builder->where('password_resets.reset_key', $key);
        $query = $builder->get();

        if ($query->getNumRows() > 0)
            return $query->getRowArray();
        else
            throw new \Exception("Key inválida ou expirada.");
    }

    public function update_password($data, $old_pass = null)
    {
        try {
            $this->get_user_by_email($data['email']);
        } catch (\Throwable $th) {
            throw $th;
        }

        try {
            $this->get_user_by_email($data['email'], $old_pass);
        } catch (\Throwable $th) {
            throw new \Exception("A senha antiga informada está incorreta.");
        }

        $builder = $this->db->table('users');
        $builder->set($data);
        $builder->where('email', $data['email']);
        $builder->update();

        $builder = $this->db->table('password_resets');
        $builder->where('password_resets.user_email', $data['email']);
        $builder->delete();
    }

    public function get_user_by_email($email, $old_pass = null)
    {
        $where['email'] = $email;
        if ($old_pass) $where['pass'] = md5($old_pass);
        $query = $this->db->table('users')->orderBy('id', 'ASC')->where($where)->get();

        if ($query->getNumRows() > 0)
            return $query->getResultArray();
        else
            throw new \Exception("Não há nenhum usário cadastrado com o email informado.");
    }
}
