<?php

namespace App\Models;

use CodeIgniter\Model;
class PermissionModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function check_permission($id = null)
    {
        try {
            return $this->return_permission($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function return_permission($id = null)
    {
        if (!$id)
            throw new \Exception("A função espera pelo menos um parâmetro.");
        else if (!is_numeric($id))
            throw new \Exception("A função espera um parâmetro numérico.");
       
        try {
            $this->get_user_by_id($id);
        } catch (\Throwable $th) {
            throw $th;
        }
        
        $builder = $this->db->table('permission');
        $builder->join('permission_user', 'permission.id = permission_user.id_permission');
        $builder->where('permission_user.id_user', $id);
        $query = $builder->get();
        $result = [];
        if ($query->getNumRows() > 0) {
           
            $query_result = $query->getResultArray();
           
            foreach ($query_result as $a) {
                    
                    $result[] = $a["permission_name"];
            }
            return $result;
        } else
            return array();
            throw new \Exception("Não há nenhuma permissão definida para este usário.");
    }

    public function get_user_by_id($id)
    {
        $builder = $this->db->table('users');
        $query = $builder->orderBy('id', 'ASC')->where('id' , $id)->get();
        if ($query->getNumRows() > 0)
            return $query->getResultArray();
        else
            throw new \Exception("Não há nenhum usário cadastrado com o id informado.");
    }
    public function get_all_permissions(){
        $builder = $this->db->table('permission');
        $query = $builder->get();

        if ($query->getNumRows() > 0)
            return $query->getResultArray();
        else
            throw new \Exception("Não há permissoes cadastradas.");
    }

    public function InsertPermission($data)
    {   
        var_dump($data["ids_permissions"]);
        foreach($data["ids_permissions"] as $id_permission){
            $builder = $this->db->table('permission_user');
            $builder->where("id_user", $data['id_user'])->where("id_permission", $id_permission);
            $query = $builder->get();
            if ($query->getNumRows() == 0) {
                $data = array("id_user"=> $data['id_user'], "id_permission"=> $id_permission);
                $builder = $this->db->table('permission_user');
                $builder->insert($data);
            } 
        }
        
    
    }
}
