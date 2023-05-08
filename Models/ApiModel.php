<?php

namespace App\Models;

use CodeIgniter\Model;
class ApiModel extends Model
{

    public function get_user_by_email($uuid)
    {
        $query = $this->db->orderBy('id', 'ASC')->get_where('users', array('email' => $uuid));
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        }
    }

    public function get_skus($id)
    {
        $query = $this->db->select('product_ean')->orderBy('id', 'ASC')->get_where('carts', array('id_client' => $id));
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array([0] => array('status' => 'Nenhum produto comprado.'));
        }
    }
}
