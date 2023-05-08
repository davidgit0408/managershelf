<?php

namespace App\Models;

use CodeIgniter\Model;
class AjaxSearchModel extends Model
{
 function fetch_data($query)
 {
  $this->db->select("*");
  $this->db->from("clients");
  if($query != '')
  {
   $this->db->like('name', $query);
   $this->db->or_like('rg', $query);
   $this->db->or_like('cpf_cnpj', $query);
   $this->db->or_like('email', $query);
  }
  $this->db->orderBy('name', 'DESC');
  return $this->db->get();
 }
}
