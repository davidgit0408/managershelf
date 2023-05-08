<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{

    ////////////////////////////////////////////
    //Funções relacionadas aos usuários//
    ////////////////////////////////////////////

    public function login($email, $pass)
    {
        $builder =  $this->db->table('users');
        $builder->where('email', $email);
        $builder->where('pass', $pass);
        $query = $builder->get();

        if ($query) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_all_users()
    {
        $builder = $this->db->table('users');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_user_by_id($id)
    {
        $builder = $this->db->table('users');
        $builder->orderBy('id', 'ASC');
        $builder->where('id', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_users_by_role($role)
    {
        $builder =  $this->db->table('users');
        $builder->where('role', $role);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_user_by_email($email)
    {
        $builder =  $this->db->table('users');
        $builder->orderBy('id', 'ASC');
        $builder->where('email', $email);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function check($tabela, $coluna, $valor)
    {
        $builder =  $this->db->table($tabela);
        $builder->where($coluna, $valor);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    ////////////////////////////////////////////
    //Funções relacionadas aos empreendimentos//
    ////////////////////////////////////////////

    public function get_all_products()
    {
        $builder = $this->db->table('products');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_product_by_id($id)
    {
        $builder = $this->db->table('products');
        $builder->where('id', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_product_by_ean($ean)
    {
        $builder = $this->db->table('products');
        $builder->where('ean', $ean);
        $query =  $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    ////////////////////////////////////////////
    //Funções relacionadas aos empreendimentos//
    ////////////////////////////////////////////

    public function get_all_scenarios()
    {
        $builder =  $this->db->table('scenarios');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_scenario_by_id($id)
    {

        $builder = $this->db->table('scenarios');
        $builder->where('id', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return 0;
        }
    }

    public function get_scenarios_by_company($id)
    {
        $builder =  $this->db->table('scenarios');
        $builder->orderBy('name', 'ASC');
        $builder->where('id_company', $id);
        $query =  $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return 0;
        }
    }

    public function update_scenario_status($data)
    {
        $builder = $this->db->table('scenarios');
        $builder->set('status', $data['status']);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    /////////////////////////////////////
    //Funções relacionadas as unidades//
    ////////////////////////////////////

    public function get_position_by($data)
    {
        var_dump($data);
        die();
        $builder = $this->db->table('positions');
        $builder->orderBy('position', 'ASC');
        $where_clause = array('id_scenario ' => $data['id_scenario'], $data['column'], $data['value']);
        $builder->where($where_clause);
        //$builder->where('id_scenario', $data['id_scenario'], $data['column'], $data['value']);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_all_positions()
    {
        $builder = $this->db->table('positions');
        $query =  $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_position_by_id($id)
    {
        $builder = $this->db->table('positions');
        $builder->orderBy('id_position', 'ASC');
        $builder->where('id_position', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_position_by_scenario($id)
    {
        $builder = $this->db->table('positions');
        $builder->select('positions.*, products.name as product_name, products.brand as product_brand, products.price as product_price, products.ean as product_ean, products.image as product_image, products.url as url');
        $builder->join('products', 'positions.id_product = products.id');
        $builder->orderBy('column', 'ASC');
        $builder->orderBy('position', 'ASC');
        $builder->where('id_scenario', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return 0;
        }
    }

    /////////////////////////////////////
    //Funções relacionadas os contratos//
    ////////////////////////////////////


    public function insert_order($data)
    {
        $builder = $this->db->table('orders');
        $builder->set($data);
        $builder->insert();
    }

    public function get_all_orders()
    {
      
        $builder =  $this->db->table('orders');
        $builder->select('*, orders.status as order_status');
        $builder->join('carts', 'carts.id_cart = orders.id_cart');
        $builder->join('clients', 'carts.id_client = clients.id_client');
        $builder->join('scenarios', 'carts.id_scenario = scenarios.id_scenario');
        $builder->join('positions', 'carts.id_position = positions.id_position');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_orderBy_id($id)
    {
        $builder = $this->db->table('orders');
        $builder->orderBy('id', 'ASC');
        $builder->where('id', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_orderBy_user($id)
    {
        $builder = $this->db->table('orders');
        $builder->orderBy('id', 'ASC');
        $builder->where('id_user', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_orderBy_cart($id)
    {
        $builder = $this->db->table('orders');
        $builder->orderBy('id', 'DESC');
        $builder->where('id_cart', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_orderBy_company($id)
    {
        $builder = $this->db->table('orders');
        $builder->orderBy('id', 'DESC');
        $builder->where('id_company', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return 0;
        }
    }

    public function insert_signed_order($data)
    {
        $builder = $this->db->table('orders');
        $builder->set('signed_order',  $data['signed_order']);
        $builder->set('signed_order2',  $data['signed_order2']);
        $builder->where('id',  $data['id']);
        $builder->update();
    }

    public function update_order_status($data)
    {
        $builder = $this->db->table('orders');
        $builder->set('status', $data['status']);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    
    public function upload_image_user($id, $directory)
    {
        $builder = $this->db->table('users');
        $builder->set('print_eye_tracking', $directory);
        $builder->where('id',$id);
        $builder->update();
    }
    
    /////////////////////////////////////
    //Funções relacionadas as proposta//
    ////////////////////////////////////

    public function get_all_carts()
    {
        $builder = $this->db->table('carts');
        $builder->select('*');
        $builder->from('carts');
        $builder->join('positions', 'positions.id_position = carts.id_position');
        $builder->join('scenarios', 'scenarios.id_scenario = carts.id_scenario');
        $builder->join('clients', 'clients.id_client = carts.id_client');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_cart_by_id($id)
    {
        $builder = $this->db->table('carts');
        $builder->select('*');
        $builder->join('products', 'products.ean = carts.product_ean');
        $builder->where('carts.id_cart', $id);
        $builder->orderBy("sequence", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_bought_cart_by_id($id)
    {
        $builder =  $this->db->table('carts');
        $builder->select('*');
        $builder->join('products', 'products.ean = carts.product_ean');
        $builder->where('carts.id_cart', $id);
        $builder->where('carts.viewed', 0);
        $builder->orderBy("sequence", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_viewed_cart_by_id($id)
    {
        $builder =  $this->db->table('carts');
        $builder->select('*');
        $builder->join('products', 'products.ean = carts.product_ean');
        $builder->where('carts.id_cart', $id);
        $builder->where('carts.viewed', 1);
        $builder->orderBy("sequence", "asc");
        $query =  $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_removed_cart_cart_by_id($id)
    {
        $builder = $this->db->table('carts');
        $builder->select('*');
        $builder->join('products', 'products.ean = carts.product_ean');
        $builder->where('carts.id_cart', $id);
        $builder->where('carts.removed_cart >=', 1);
        $builder->orderBy("removed_cart", "desc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_removed_checkout_cart_by_id($id)
    {
        $builder = $this->db->table('carts');
        $builder->select('*');
        $builder->join('products', 'products.ean = carts.product_ean');
        $builder->where('carts.id_cart', $id);
        $builder->where('carts.removed_checkout >=', 1);
        $builder->orderBy("removed_checkout", "desc");
        $query =  $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_cart_by_all($data)
    {
        $builder = $this->db->table('carts');
        $builder->orderBy('id', 'DESC');
        //$builder->where('id_client', $data['id_client'], 'id_scenario', $data['id_scenario'], 'id_company', $data['id_company']);
        $where_clause = array('id_client ' => $data['id_client'], 'id_scenario'=> $data['id_scenario'], 'id_company' => $data['id_company']);
        $builder->where($where_clause);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function insert_cart($data)
    {
        $builder = $this->db->table('carts');
        $builder->set($data);
        $builder->insert();
    }

    public function update_cart_status($data)
    {
        $builder =  $this->db->table('carts');
        $builder->set('cart_status', $data['status']);
        $builder->where('id_cart', $data['id_cart']);
        $builder->update();
    }

    public function update_cart($data)
    {
        $builder =  $this->db->table('carts');
        $builder->set($data);
        $builder->where('id_cart', $data['id_cart']);
        $builder->where('product_ean', $data['product_ean']);
        $builder->update();
    }

    /////////////////////////////////////
    //Funções relacionadas ao cliente//
    ////////////////////////////////////

    public function insert_client($data)
    {
        $this->db->table('users')->insert($data);
    }

    public function update_client_by_email($data)
    {
        $builder = $this->db->table('users');
        $builder->set($data);
        $builder->where('email', $data['email']);
        $builder->update();
    }

    public function insert_client_id($data)
    {
        $builder = $this->db->table('users');
        $builder->set($data);
        $builder->insert();
        $id = $builder->insertId();
        return $id;
    }

    public function insert_client_meta($data)
    {
        $builder = $this->db->table('user_meta');
        $builder->set($data);
        $builder->insert();
    }

    public function get_costuma_comprar()
    {
        $builder = $this->db->table('user_meta');
        $builder->where("meta_key", "costuma_comprar");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array('0' => array("meta_value" => json_encode(array("Dados inexistentes"))));
        }
    }

    public function get_rejeita_comprar()
    {
        $builder = $this->db->table('user_meta');
        $builder->where("meta_key", "rejeita_comprar");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array('0' => array("meta_value" => json_encode(array("Dados inexistentes"))));
        }
    }

    public function get_onde_compra()
    {
        $builder = $this->db->table("user_meta");
        $builder->select('*');
        $builder->where("meta_key", "onde_compra_gelatina")->orWhere('meta_key', 'onde_compra_bola');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array('0' => array("meta_value" => json_encode(array("Dados inexistentes"))));
        }
    }

    public function get_user_meta($id)
    {
        $builder = $this->db->table("user_meta");
        $builder->select('*');
        $builder->where("id_user", $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array('0' => array("meta_key" => json_encode(array("Dados inexistentes")), "meta_value" => json_encode(array("Dados inexistentes"))));
        }
    }

    public function get_user_meta_key($id, $meta_key)
    {
        $builder = $this->db->table("user_meta");
        $builder->select('*');
        $builder->where("id_user", $id, 'meta_key', $meta_key);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array('0' => array("meta_key" => json_encode(array("Dados inexistentes")), "meta_value" => json_encode(array("Dados inexistentes"))));
        }
    }

    public function get_client_by_email($email)
    {
        $builder = $this->db->table('clients');
        $builder->orderBy('id_client', 'ASC');
        $builder->where('email', $email);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_client_by_id($id)
    {
        $builder = $this->db->table('clients');
        $builder->orderBy('id_client', 'ASC');
        $builder->where('id_client', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_client_by_cpf($cpf)
    {
        $builder = $this->db->table('clients');
        $builder->orderBy('id_client', 'ASC');
        $builder->where('cpf_cnpj', $cpf);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query;
        } else {
            return $query;
        }
    }

    public function get_all_clients()
    {
        $builder = $this->db->table('users');
        $builder->where('role', 'client');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function updateProdutosVistos($data)
    {
        $builder = $this->db->table('eye_tracking_results');
        $builder->where('scenario_id',$data["scenario_id"]);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $builder = $this->db->table('eye_tracking_results');
            $builder->set($data);
            $builder->where('scenario_id', $data['scenario_id']);
            $builder->update();
        }else{
            $builder = $this->db->table('eye_tracking_results');
            $builder->set($data);
            $builder->insert();
        }
    }
    public function insert_perguntas($data)
    {
        $builder = $this->db->table('perguntas');
        $builder->set($data);
        $builder->insert();
    }


    public function insert_eye_tracking($data)
    {
        $builder = $this->db->table('eye_tracking_results');
        $builder->set($data);
        $builder->insert();
    }

    //////////////////////////////////////////////////
    //////Funções relacionadas aos estudos///////////
    /////////////////////////////////////////////////

    public function get_all_company()
    {
        $builder = $this->db->table('company');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return "Dados inexistentes";
        }
    }

    public function get_company_by_id($id)
    {
        $builder = $this->db->table('company');
        $builder->orderBy('id', 'ASC');
        $builder->where('id', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return 0;
        }
    }

    public function update_company($data)
    {
        $builder = $this->db->table('company');
        $builder->set($data);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    public function get_scenario_by_company($id)
    {
        $builder = $this->db->table('scenarios');
        $builder->where('id_company', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return 0;
        }
    }

    public function get_order_by_company($id){
        $query = $this->db->table('orders')->where(array('id_company' => $id))->get();
        if($query->getNumRows()>0){
            return $query->getResultArray();
        }else{
            return 0;
        }
    }  
    
    public function get_order_by_cart($id){
        $query = $this->db->table('orders')->where(array('id_cart' => $id))->get();
        if($query->getNumRows()>0){
            return $query->getResultArray();
        }else{
            return 0;
        }
    }

    public function qtdPesquisa(){

        $scenariosId = [
            '140',
            '141',
            '142'
        ];
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

        $builder =  $this->db->table('orders');
        $builder->select('scenarios.id as scenario_id, users.birthday as idade, users.tel as classe_social, users.cellphone as usa_em_casa, users.state as estado');
        $builder->join('carts', 'carts.id_cart = orders.id_cart', "inner");
        $builder->join('users', 'carts.id_client = users.id', "inner");
        $builder->join('scenarios', 'carts.id_scenario = scenarios.id', "inner");
        $builder->join('survey_form_answers', 'users.email = survey_form_answers.id_user', "inner");
        // $builder->join('eye_tracking_results', 'users.email = eye_tracking_results.uuid', 'left');
        $builder->groupBy('users.email');
        $builder->like('survey_form_answers.answers', "42-outros");
        $builder->whereIn('scenarios.id', $scenariosId);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function qtdEyeTracking(){

        $scenariosId = [
            '140',
            '141',
            '142'
        ];

        $builder =  $this->db->table('orders');
        $builder->select('users.email as email');
        $builder->join('carts', 'carts.id_cart = orders.id_cart');
        $builder->join('users', 'carts.id_client = users.id');
        $builder->join('scenarios', 'carts.id_scenario = scenarios.id');
        $builder->join('eye_tracking_results', 'users.email = eye_tracking_results.uuid');
        $builder->whereIn('scenarios.id', "(".implode(",", $scenariosId).")");
        $builder->groupBy('eye_tracking_results.uuid');
        $query = $builder->get();
        return $query->getNumRows();
    }
}
