<?php

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{

    ////////////////////////////////////////////
    //Funções relacionadas aos usuários//
    ////////////////////////////////////////////
    protected $db;
    protected $db_auxiliar;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->db_auxiliar = \Config\Database::connect('auxiliar');
    }
    public function login($email, $pass)
    {
        try {
            $this->get_user_by_email($email);
        } catch (\Throwable $th) {
            throw $th;
        }

        try {
            $this->check_email($email);
        } catch (\Throwable $th) {
            throw $th;
        }


        $builder = $this->db->table('users');
        $builder->select('*');
        $where_clause = array('email ' => $email, 'pass ' => $pass);
        $builder->where($where_clause);
        $query = $builder->get();
        if ($query->getNumRows() > 0)
            return $query->getRowArray();
        else
            throw new \Exception("Senha incorreta.");
        /*if ($query->getResultArray() > 0) 
            return $query->getRowArray();
            //getResultArray
         else 
         throw new \Exception("Senha incorreta.");*/
    }

    public function insert_user($data)
    {
        $this->db->table('users')->insert($data);
    }

    public function update_user($data)
    {
        $builder = $this->db->table('users');
        $builder->set($data);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    public function update_user_by_email($data)
    {
        $builder = $this->db->table('users');
        $builder->set($data);
        $builder->where('email', $data['email']);
        $builder->update();
    }

    public function update_user_image($data)
    {
        $builder = $this->db->table('users');
        $builder->set($data);
        $builder->where('id', $data['id']);
        $builder->update();
    }


    public function get_all_users()
    {
        $query = $this->db->table('users')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_user_by_id($id)
    {

        $builder = $this->db->table('users');
        $builder->orderBy('id', 'ASC');
        $builder->where('id', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getRowArray();
        } else {
            return array();
        }
    }

    public function delete_notify($id)
    {
        $builder = $this->db->table('notifications');
        $builder->where('id_user', $id);
        $builder->delete();
    }

    public function view_notify($id)
    {
        $builder = $this->db->table('notifications');
        $builder->set('view', $id);
        $builder->where('id_user', $id);
        $builder->update();
    }

    public function get_user_by_email($email)
    {

        $builder = $this->db->table('users')->orderBy('id', 'ASC')->where('email', $email);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            throw new \Exception("Não há nenhum usuário cadastrado com o email informado.");
        }
    }

    public function uploadFile($data)
    {
        $arquivo = $data['upload_path'] . '/' . $data['file_name'];
        $builder = $this->db->table('users');
        $builder->where('id', $data['id']);
        $builder->set('img_url', $arquivo);
        $query = $builder->update();
    }

    public function check_email($email)
    {

        $builder = $this->db->table('users')->orderBy('id', 'ASC')->where('email', $email, 'email_confirm', 1);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            throw new \Exception("Por favor, confirme seu email.");
        }
    }

    public function get_manager_by_id($id)
    {
        $query = $this->db->table('users')->orderBy('id', 'ASC')->where('id', $id)->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_client_by_id($id)
    {
        $query = $this->db->table('users')->orderBy('id', 'ASC')->where('id', $id)->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_users_by_role($role)
    {
        $query = $this->db->table('users')->orderBy('id', 'ASC')->where('role', $role)->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function check($tabela, $coluna, $valor)
    {
        $builder = $this->db->table($tabela);
        $builder->select('*');
        $builder->where($coluna, $valor);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_users_by_($id_user)
    {
        $builder = $this->db->table('users');
        $builder->select('*');
        $builder->where('created_by', $id_user);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function delete_user($id)
    {
        $builder = $this->db->table('users');
        $builder->where('id', $id);
        $builder->delete();
    }

    public function update_view_user($data)
    {
        $builder = $this->db->table('users');
        $builder->set('view_popup', $data['view_popup']);
        $builder->update();
    }

    public function update_view_by_user($data)
    {
        $builder = $this->db->table('users');
        $builder->set('view_popup', $data['view_popup']);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    public function insert_product($data)
    {
        $builder = $this->db->table('products');
        $builder->set($data);
        $builder->insert();
    }

    public function insert_interviewed_data($data)
    {
        $builder = $this->db->table('interviewed_data');
        $builder->set($data);
        $builder->insert();
        return $this->db->insertID();
    }

    function interviewed_cpf_exist($key)
    {
        $builder = $this->db->table('interviewed_data');
        $builder->where('cpf', $key);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function insert_interviewed_bigdata($data)
    {
        $builder = $this->db->table('interviewed_bigdata');
        $builder->set($data);
        $builder->insert();
    }

    public function update_product($data)
    {
        $builder = $this->db->table('products');
        $builder->set($data);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    public function update_product_status($data)
    {
        $builder = $this->db->table('products');
        $builder->set('status', $data['status']);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    public function get_all_products()
    {
        $query = $this->db->table('products')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_products_by_limit($id, $search, $limit, $offset, $order, $by)
    {
        $builder = $this->db->table('products');
        $builder->select("products.*, categories.name as categoria");
        $builder->join('categories', 'products.category = categories.id', 'left');
        if ($id != 1) $builder->where('products.id_user', $id);
        if ($order) $builder->orderBy($by, $order);
        if ($search) {
            $builder->like('products.name', $search);
            $builder->orLike('products.brand', $search);
            $builder->orLike('products.ean', $search);
        }

        if ($limit) {
            $query = $builder->get($limit, $offset);
            if ($query->getNumRows() > 0) return $query->getResultArray();
            else return array();
        } else {
            return $builder->countAllResults();
        }
    }

    public function get_interviewees_by_limit($id, $search, $limit, $offset, $order, $by)
    {

        $builder = $this->db->table('interviewed_bigdata');
        $builder->join('interviewed_data', 'interviewed_data.id = interviewed_bigdata.interviewd_id', 'left');
        if ($order) $builder->orderBy($by, $order);
        if ($search) {
            $builder->like('interviewed_bigdata.idade', $search);
            $builder->orLike('interviewed_bigdata.genero', $search);
            $builder->orLike('interviewed_bigdata.estado', $search);
            $builder->orLike('interviewed_bigdata.cidade', $search);
        }

        if ($limit) {
            $query = $builder->get($limit, $offset);
            if ($query->getNumRows() > 0) return $query->getResultArray();
            else return array();
        } else {
            return $builder->countAllResults();
        }
    }

    public function get_interview_person($id)
    {

        $builder = $this->db->table('interviewed_bigdata');
        $builder->where('interviewed_bigdata.id', $id);
        $builder->join('interviewed_data', 'interviewed_data.id = interviewed_bigdata.interviewd_id', 'left');
        $query = $builder->get();
        
        if ($query->getNumRows() > 0) return $query->getResultArray();
        else return array();
    }



    public function get_order_by_date_company($id, $data)
    {
        $builder = $this->db->table('orders');
        $builder->orderBy('id', 'DESC');
        $builder->where('id_company', $id);
        $builder->like('data', $data);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_product_by_id($id)
    {
        $builder = $this->db->table('products');
        $builder->orderBy('id', 'ASC');
        $builder->where('id', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_product_by_ean($ean)
    {
        $query = $this->db->table('products')->orderBy('id', 'ASC')->where('ean', $ean)->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_products_by_ean($ean)
    {
        $query = $this->db->table('carts')->orderBy('id', 'ASC')->where('product_ean', $ean)->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_products_by_string($string, $category)
    {
        $string = "'%".$string."%'";
        if(!empty($string) && !empty($category)){
            $builder = $this->db->query("SELECT image, name, price, id FROM products WHERE `category` = $category AND ( `name` LIKE $string OR `feature` LIKE $string OR `ean` LIKE $string OR `brand` LIKE $string );");
        }else if(!empty($string)){
            $builder = $this->db->query("SELECT image, name, price, id FROM products WHERE ( `name` LIKE $string OR `feature` LIKE $string OR `ean` LIKE $string OR `brand` LIKE $string );");
        }else if(!empty($category)){
            $builder = $this->db->query("SELECT image, name, price, id FROM products WHERE `category` = $category;");
        }else{
            $builder = $this->db->query("SELECT image, name, price, id FROM products;");
        }
        if ($builder->getNumRows() > 0) return $builder->getResultArray();
        else return array();
    }

    public function get_product_by($query)
    {



        $builder = $this->db->table('products');
        $builder->select('*');
        if ($query['category'] != 'all') $builder->where('category', $query['category']);
        if ($query['brand'] != 'all') $builder->where('brand', $query['brand']);
        if ($query['producer'] != 'all') $builder->where('producer', $query['producer']);

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function delete_product($data)
    {
        $builder = $this->db->table('products');
        $builder->where('id', $data);
        $builder->delete();
    }

    public function count_products()
    {
        $result = $this->db->table('products')->count_all_results();
        return $result;
    }

    public function insert_planogram($data)
    {
        $this->db->table('scenarios')->insert($data);
        return $this->db->insertID();
    }

    public function update_planogram($data)
    {
        $builder = $this->db->table('scenarios');
        $builder->set($data);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    public function get_all_planograms()
    {
        $builder = $this->db->table('scenarios');
        $builder->select('*');
        $builder->orderBy('name', 'ASC');
        $builder->whereNotIn('status', ['Em campo']);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_planogram_by_user($id)
    {

        $builder = $this->db->table('scenarios');
        $builder->orderBy('name', 'ASC');
        $builder->where('id_user', $id);
        $builder->whereNotIn('status', ['Em campo']);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_planogram_by_id($id)
    {
        $builder = $this->db->table('scenarios');
        $builder->select('*');
        $builder->where('id', $id);
        // $builder->whereNotIn('status', ['Em campo']);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_planogram_by($data)
    {
        $query = $this->db->table('scenarios')->where(array('name' => $data['name'], 'status' => $data['status']))->orderBy('id', 'DESC')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_planogram_shelves_quantity($id_scenario)
    {
        $builder = $this->db->table('positions');
        $builder->select('shelf');
        $builder->where('id_scenario', $id_scenario);
        $builder->orderBy('shelf', 'DESC');
        $builder->limit(1, 0);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray()[0]["shelf"];
        } else {
            return 0;
        }
    }

    public function get_planogram_positions_quantity($id_scenario)
    {
        $builder = $this->db->table('positions');
        $builder->select('position');
        $builder->where('id_scenario', $id_scenario);
        $builder->orderBy('position', 'DESC');
        $builder->limit(1, 0);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray()[0]["position"];
        } else {
            return 0;
        }
    }

    public function get_planogram_columns_quantity($id_scenario)
    {
        $builder = $this->db->table('positions');
        $builder->select('positions.column');
        $builder->where('id_scenario', $id_scenario);
        $builder->orderBy('positions.column', 'DESC');
        $builder->limit(1, 0);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray()[0]["column"];
        } else {
            return 0;
        }
    }

    public function delete_planogram($data)
    {
        $builder = $this->db->table('scenarios');
        $builder->where('id', $data);
        $builder->delete();
    }

    public function get_all_scenarios()
    {
        $builder = $this->db->table('scenarios');
        $builder->select("scenarios.*, company.name as company_name");
        $builder->join('company', 'scenarios.id_company = company.id', 'left');
        $builder->where('scenarios.status', 'Em campo');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_scenario_by_id($id)
    {
        $builder = $this->db->table('scenarios');
        $builder->where('id', $id);
        // $builder->where('status', 'Em campo');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_scenario_by_user($id)
    {
        $builder = $this->db->table('scenarios');
        $builder->select("scenarios.*, company.name as company_name");
        $builder->join('company', 'scenarios.id_company = company.id', 'left');
        $builder->where('scenarios.id_user', $id);
        $builder->where('scenarios.status', 'Em campo');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function update_scenario_status($data)
    {
        $builder = $this->db->table('scenarios');
        $builder->set('status', $data['status']);
        $builder->where('id_scenario', $data['id']);
        $builder->update();
    }

    public function get_scenarios_by_company($id, $id_user = null)
    {
        $query = $this->db->table('scenarios');
        $query->select("scenarios.*, company.name as company_name");
        $query->join('company', 'scenarios.id_company = company.id', 'left');
        $query->orderBy('scenarios.id', 'ASC')->where('scenarios.id_company', $id)->where('scenarios.status', 'Em campo');
        if (!empty($id_user))
            $query->where('scenarios.id_user', $id_user);

        $query = $query->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function count_scenario_by_company($id)
    {
        $builder = $this->db->table('scenarios');
        $builder->select('*');
        $builder->like('id_company', $id);
        $query = $builder->get();
        return $query->getNumRows();
    }

    public function insert_position($data)
    {
        $this->db->table('positions')->insert($data);
    }

    public function delete_position($data)
    {
        $builder = $this->db->table('positions');
        $builder->where('id_position', $data['id_position']);
        $builder->delete();
    }

    public function delete_position_by($data)
    {
        $builder = $this->db->table('positions');
        $builder->where('id_scenario', $data['id_scenario']);
        $builder->where('shelf', $data['shelf']);
        $builder->delete();
    }

    public function update_position_status($data)
    {
        $builder = $this->db->table('positions');
        $builder->set('status', $data['status']);
        $builder->where('id_position', $data['id_position']);
        $builder->update();
    }

    public function get_position($data)
    {
        $builder = $this->db->table('positions');
        $builder->select('*');
        $builder->where('column', $data['column']);
        $builder->where('shelf', $data['shelf']);
        $builder->where('id_scenario', $data['id_scenario']);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_position_by($data)
    {
        $builder = $this->db->table('positions');
        $where = array('id_scenario' => $data['id_scenario'], $data['column'] => $data['value']);
        $builder->select('positions.*, products.name as product_name, products.price as product_price, products.ean as product_ean, products.image as product_image, products.url as url');
        $builder->join('products', 'positions.id_product = products.id', 'left');
        $builder->orderBy('position', 'ASC');
        $builder->where($where);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_all_positions()
    {
        $query = $this->db->table('positions')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_position_by_id($id)
    {
        $builder = $this->db->table('positions');
        $builder->select('positions.*, products.name as product_name, products.price as product_price, products.ean as product_ean, products.image as product_image, products.url as url');
        $builder->join('products', 'positions.id_product = products.id', 'left');
        $builder->orderBy('position', 'ASC');
        $builder->where('positions.id', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_position_by_scenario($id)
    {

        $builder = $this->db->table('positions');
        $builder->select('positions.*, products.name as product_name, products.price as product_price, products.ean as product_ean, products.image as product_image, products.url as url, positions.width as position_width, positions.height as position_height, products.width as product_width, products.height as product_height');
        $builder->join('products', 'positions.id_product = products.id', 'left');
        $builder->where('id_scenario', $id);
        $builder->orderBy('column', 'ASC')->orderBy('shelf', 'ASC')->orderBy('position', 'ASC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function remove_position_by_scenario($id)
    {
        $builder = $this->db->table('positions');
        $builder->where('id_scenario', $id);
        $builder->delete();
    }

    public function insert_order($data)
    {
        $this->db->table('orders')->insert($data);
    }

    public function get_all_orders()
    {
        $builder = $this->db->table('orders');
        $builder->select('*');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_orderBy_id($id)
    {
        $query = $this->db->table('orders')->where('id', $id)->orderBy('id', 'ASC')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
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

    public function get_orderBy_company($id)
    {
        $query = $this->db->table('orders')->where('id_company', $id)->orderBy('id', 'ASC')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_order_by_company($id)
    {
        $builder = $this->db->table('orders');
        $builder->orderBy('id', 'DESC');
        $builder->where('id_company', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_order_by_company_limit($id, $limit = null, $offset = null)
    {
        $builder = $this->db->table('orders');
        $builder->orderBy('id', 'DESC');
        $builder->where('id_company', $id);
        if ($limit) {
            $query = $builder->get($limit, $offset);
            if ($query->getNumRows() > 0) return $query->getResultArray();
            else return array();
        } else {
            return $builder->countAllResults();
        }
    }

    public function get_orderBy_date_company($id, $data)
    {
        $builder = $this->db->table('orders');
        $builder->select('*');
        $builder->orderBy('id', 'DESC');
        $builder->where('id_company', $id);
        $builder->like('data', $data);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_last_inserted_sale()
    {
        $builder = $this->db->table('sales');
        $builder->select('*');
        $builder->orderBy('id', 'DESC');

        $query = $builder->get();
        if ($query->getNumRows() > 0)
            return $query->getResultArray()[0];
        else
            return array();
    }

    public function get_all_carts()
    {
        $builder = $this->db->table('carts');
        $builder->select('*');
        $builder->join('positions', 'positions.id_position = carts.id_position');
        $builder->join('scenarios', 'scenarios.id_scenario = carts.id_scenario');
        $builder->join('clients', 'clients.id_client = carts.id_client');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_cart_by_id($id)
    {
        $query = $this->db->table('carts')->where('id_cart', $id)->orderBy('id', 'ASC')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }
    public function get_products_by_id($id)
    {
        $query = $this->db->table('products')->where('id', $id)->orderBy('id', 'ASC')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_bought_cart_by_id($id)
    {
        $builder = $this->db->table('carts');
        $builder->select('*');
        $builder->join('products', 'products.ean = carts.product_ean');
        $builder->where('carts.id_cart', $id);
        $builder->where('carts.viewed', 0);
        $builder->orderBy("sequence", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_viewed_cart_by_id($id)
    {
        $builder = $this->db->table('carts');
        $builder->select('*');
        $builder->join('products', 'products.ean = carts.product_ean');
        $builder->where('carts.id_cart', $id);
        $builder->where('carts.viewed', 1);
        $builder->orderBy("sequence", "asc");
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
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
            return array();
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
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_carts_by_scenario($id)
    {
        $query = $this->db->table('carts')->where('id_scenario', $id)->orderBy('id_cart', 'ASC')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_carts_by($ean, $id)
    {
        $query = $this->db->table('carts')->where(array('id_scenario' => $id, 'product_ean' => $ean))->orderBy('id_cart', 'ASC')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function insert_cart($data)
    {
        $this->db->table('carts')->insert($data);
    }

    public function insert_signed_cart($data)
    {
        $builder = $this->db->table('carts');
        $builder->set('signed_cart',  $data['file']);
        $builder->set('addtional_info',  $data['addtional_info']);
        $builder->where('id_cart',  $data['id']);
        $builder->update();
    }

    public function update_cart_status($data)
    {
        $builder = $this->db->table('carts');
        $builder->set('cart_status', $data['status']);
        $builder->where('id_cart', $data['id_cart']);
        $builder->update();
    }

    public function update_cart($data)
    {
        $builder = $this->db->table('carts');
        $builder->set($data);
        $builder->where('id_cart', $data['id_cart']);
        $builder->update();
    }

    public function insert_client($data)
    {
        $this->db->table('users')->insert($data);
    }

    public function get_client_by_email($email)
    {
        $query = $this->db->table('users')->where('email', $email)->orderBy('id', 'ASC')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_user_meta_key($id, $meta_key)
    {
        $this->db->select('*');
        $this->db->from("user_meta");
        $this->db->where(array("id_user" => $id, 'meta_key' => $meta_key));
        $query = $this->db->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array('0' => array("meta_key" => json_encode(array("Dados inexistentes")), "meta_value" => json_encode(array("Dados inexistentes"))));
        }
    }

    public function get_all_clients()
    {
        $query = $this->db->table('users')->where('role', 'client')->orderBy('id', 'ASC')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function update_client($data)
    {
        $builder = $this->db->table('users');
        $builder->set($data);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    public function update_client_by_email($data)
    {
        $builder = $this->db->table('users');
        $builder->set($data);
        $builder->where('email', $data['email']);
        $builder->update();
    }

    public function getRows($id = '')
    {
        $builder = $this->db->table('files');
        $builder->select('id,file_name,uploaded_on');
        if ($id) {
            $builder->where('id', $id);
            $query = $builder->get();
            $result = $query->getResultArray();
        } else {
            $builder->orderBy('uploaded_on', 'desc');
            $query = $builder->get();
            $result = $query->getResultArray();
        }

        return !empty($result) ? $result : false;
    }

    public function insertProduct($data)
    {
        $builder = $this->db->table('files');
        $builder->set($data);
        $query = $builder->insert();
        return $query ? true : false;
    }

    public function get_gallery_total()
    {
        $builder = $this->db->table('files');
        $teste = $builder->select('*')
                 ->notLike('src', 'writable/uploads/scenarios/')
                 ->countAllResults();
        return $teste;
    }

    public function get_gallery_pageone($rowperpage)
    {
        $builder = $this->db->table('files');
        $teste = $builder->select('*')
        ->notLike('src', 'writable/uploads/scenarios/')
        ->orderBy("id desc")
        ->limit($rowperpage);
        
        $query = $teste->get();
        return $query->getResultArray();
    }

    public function get_gallery_page($row, $rowperpage)
    {
        $builder = $this->db->table('files');
        $teste = $builder->select('*')
        ->notLike('src', 'writable/uploads/scenarios/')
        ->orderBy("id desc")
        ->limit($rowperpage, $row);

        $query = $teste->get();
        return $query->getResultArray();
    }


    public function get_all_files()
    {
        $builder = $this->db->table('files');
        $builder->select('*');
        $builder->orderBy("id desc");
        $builder->notLike('src', 'writable/uploads/scenarios/');

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_files_by($id)
    {
        $builder = $this->db->table('files');
        $builder->select('*');
        $builder->where('id_user', $id);
        $builder->orderBy("id desc");
        $builder->notLike('src', 'writable/uploads/scenarios/');

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function remove_files_by($src)
    {
        $builder = $this->db->table('files');
        $builder->where('src', $src);
        $builder->delete();
    }

    public function get_files_by_wh($id)
    {
        $builder = $this->db->table('files');
        $builder->select('*');
        $builder->where('id_user', $id);
        //imagens cujo altura seja igual largura
        $where = "height = width";
        $builder->where($where);

        $builder->orderBy("id desc");

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_all_files_by_wh()
    {
        $builder = $this->db->table('files');
        $builder->select('*');
        $builder->orderBy("id desc");
        //imagens cujo altura seja igual largura
        $where = "height = width";
        $builder->where($where);

        $builder->orderBy("id desc");

        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_scenario_file($scenario)
    {
        $builder = $this->db->table('files');
        $builder->where('file_name', $scenario);
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function insert_file($data = array())
    {
        $insert = $this->db->table('files')->insert($data);
        return $insert ? true : false;
    }

    public function update_file($data)
    {
        $builder = $this->db->table('files');
        $builder->set($data);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    public function update_draggable($data, $id)
    {
        $builder = $this->db->table('positions');
        $builder->set($data);
        $builder->where('id', $id);
        $builder->update();
    }

    public function remove_position($data, $id)
    {
        $builder = $this->db->table('positions');
        $builder->where('id', $id);
        $builder->delete();
    }

    public function remove_shelf($data)
    {
        $builder = $this->db->table('positions');
        $builder->where('id_scenario', $data['id_scenario']);
        $builder->where('shelf', $data['shelf']);
        $builder->delete();

        $builder->resetQuery();

        $builder->set('shelf', 'shelf - 1', FALSE);
        $builder->where('id_scenario', $data['id_scenario']);
        $builder->where('shelf >', $data['shelf']);
        $builder->update();
    }

    public function remove_column($data)
    {
        $builder = $this->db->table('positions');
        $builder->where('id_scenario', $data['id_scenario']);
        $builder->where('column', $data['column']);
        $builder->delete();

        $builder->resetQuery();

        $builder->set('`column`', '(`column` - 1)', FALSE);
        $builder->where('id_scenario', $data['id_scenario']);
        $builder->where('column >', $data['column']);
        $builder->update();
    }

    public function update_position($data, $id)
    {
        $builder = $this->db->table('positions');
        $builder->set($data);
        $builder->where('id', $id);
        $builder->update();
    }

    public function update_position_by_product($data, $id)
    {
        $builder = $this->db->table('positions');
        $builder->set($data);
        $builder->where('id_product', $id);
        $builder->update();
    }

    public function get_all_categories()
    {
        $query = $this->db->table('categories')->orderBy('id', 'ASC')->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_category_by_id($id)
    {
        $builder = $this->db->table('categories');
        $builder->where('id', $id);
        $builder->orderBy('id', 'ASC');
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResult('array');
        } else {
            return array(["name" => 'Dados Inexistentes']);
        }
    }

    public function delete_category($data)
    {
        $builder = $this->db->table('categories');
        $builder->where('id', $data);
        $builder->delete();
    }

    public function insert_category($data)
    {
        $this->db->table('categories')->insert($data);
    }

    public function update_category($data, $id)
    {
        $builder = $this->db->table('categories');
        $builder->set($data);
        $builder->where('id', $id);
        $builder->update();
    }

    public function get_category_by_name($name)
    {
        $builder = $this->db->table('categories');
        $builder->select('*');
        $builder->where('name', $name);
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_all_notices()
    {
        $db2 = $this->db_auxiliar;
        $builder = $db2->table('wp_posts');
        $builder->select('*');
        $builder->where('post_type', 'post');
        //$builder->orderBy('post_date', 'DESC');
        if ($query = $builder->get()) {
            //echo count($query);
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function get_all_img_notices($id)
    {
        $db2 = $this->db_auxiliar;
        $builder = $db2->table('wp_postmeta');

        $builder->select('guid');
        $builder->join('wp_posts', 'wp_postmeta.meta_value = wp_posts.id');
        $builder->where('meta_key', '_thumbnail_id');
        $builder->where('wp_postmeta.post_id', $id);

        if ($query = $builder->get()) {
            return $query->getResultArray();
        } else {
            return false;
        }
    }

    public function insert_company($data)
    {
        $builder = $this->db->table('company');
        $builder->set($data);
        $builder->insert();
        return $this->db->insertId();
    }

    public function get_all_company()
    {
        $builder = $this->db->table('company');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
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
            return array();
        }
    }

    public function update_company($data)
    {
        $builder = $this->db->table('company');
        $builder->set($data);
        $builder->where('id', $data['id']);
        $builder->update();
    }

    public function get_all_available_company()
    {
        $builder = $this->db->table('company');
        $builder->select('*');
        $builder->where('status', "Ativo");
        $query = $builder->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_available_company_by($id)
    {
        $builder = $this->db->table('company');
        $builder->where('id_user', $id, 'status' != 'Finalizado');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_company_by_user($id)
    {
        $builder_company = $this->db->table('company');
        $builder_company->where('id_user', $id);
        $query = $builder_company->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function save_sale($data)
    {
        $db = $this->db;
        $builder = $this->db->table('sales');
        $builder->insert($data);
        return $db->insertId();
    }

    public function payment_painel($id)
    {
        $builder = $this->db->table('sales');
        $builder->where('client_id', $id);
        $builder->orderBy('id', 'DESC');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_sale_by_id($id)
    {

        $builder_sales = $this->db->table('sales');
        $builder_sales->select('*');
        $builder_sales->where('id', $id);
        $query = $builder_sales->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function change_sales_status($status, $id)
    {

        $builder_sales = $this->db->table('sales');
        $builder_company = $this->db->table('company');

        $builder_sales->set('status', $status);
        $builder_sales->where('id', $id);
        $builder_sales->update();


        $query = $builder_sales->get();

        if ($query->getNumRows() > 0) {
            $return = $query->getResultArray();
            $builder_company->set('company.status', $status);
            $builder_company->where('id', $return[0]["company_id"]);
            $builder_company->update();
        } else {
            return;
        }
    }

    public function insert_pscode($pscode, $id)
    {
        $builder_sales = $this->db->table('sales');
        $builder_sales->set('pscode', $pscode);
        $builder_sales->where('id', $id);
        $builder_sales->update();
    }

    public function insert_version($data)
    {
        $this->db->table('versions')->insert($data);
    }

    public function update_version($data)
    {
        $builder_versions = $this->db->table('versions');
        $builder_versions->set($data);
        $builder_versions->where('id', $data['id']);
        $builder_versions->update();
    }

    public function get_all_versions()
    {
        $builder_versions = $this->db->table('versions');
        $builder_versions->select('*');
        $builder_versions->orderBy('id', 'DESC');
        $query = $builder_versions->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_version_by_($id)
    {
        $builder_versions = $this->db->table('versions');
        $builder_versions->select('*');
        $builder_versions->where('id', $id);
        $query = $builder_versions->get();

        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function insert_logs($data)
    {
        //$this->db->table('logs')->insert($data);
    }

    public function get_all_logs()
    {
        $builder_logs = $this->db->table('logs');
        $builder_logs->select('*');
        $builder_logs->orderBy('id', 'DESC');
        $query = $builder_logs->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function insert_notification($data)
    {
        $builder = $this->db->table('notifications');
        $builder->set($data);
        $builder->insert();
    }

    public function get_notifications_by($id)
    {

        $builder_notifications = $this->db->table('notifications');
        $builder_notifications->orderBy('id', 'DESC');
        $builder_notifications->where('show_to', $id);
        $query = $builder_notifications->get();
        if ($query->getResultArray() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function update_view_by($data)
    {
        $builder_notifications = $this->db->table('notifications');
        $builder_notifications->set($data);
        $builder_notifications->where('id', $data['id']);
        $builder_notifications->update();
    }

    public function check_view_by($created_by)
    {
        $builder_notifications = $this->db->table('notifications');
        $builder_notifications->select('*');
        $builder_notifications->orderBy('id', 'DESC');
        $builder_notifications->where('show_to', $created_by);
        $query = $builder_notifications->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_eye_tracking_by_user($id_scenario, $users)
    {
        $builder_eye_tracking_results = $this->db->table('eye_tracking_results');

        $builder_eye_tracking_results->select('GazeX, GazeY');
        $builder_eye_tracking_results->orderBy('GazeX', 'INC');
        $builder_eye_tracking_results->where('scenario_id', $id_scenario);
        $builder_eye_tracking_results->where($users);

        $query = $builder_eye_tracking_results->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_eye_tracking_by($id_scenario)
    {
        $builder_eye_tracking_results = $this->db->table('eye_tracking_results');
        $builder_eye_tracking_results->select('eye_tracking_results.docX, eye_tracking_results.docY, eye_tracking_results.time, positions.shelf, positions.column, positions.position, positions.width as position_width, positions.height as position_height, positions.views, products.name as product_name, products.price as product_price, products.image as product_image, products.url as product_url, products.ean as product_ean, products.width as product_width, products.height as product_height');
        $builder_eye_tracking_results->join('positions', "eye_tracking_results.produtosVistos = positions.id");
        $builder_eye_tracking_results->join('products', "positions.id_product = products.id");
        $builder_eye_tracking_results->orderBy('GazeX', 'INC');
        $builder_eye_tracking_results->where('scenario_id', $id_scenario);
        $query = $builder_eye_tracking_results->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_product_size($id_scenario)
    {
        $this->db->query('SET SESSION sql_mode = ""');
        $this->db->query('SET SESSION sql_mode = REPLACE(REPLACE(REPLACE( @@sql_mode, "ONLY_FULL_GROUP_BY,", ""), ",ONLY_FULL_GROUP_BY", ""), "ONLY_FULL_GROUP_BY", "")');

        $builder_positions = $this->db->table('positions a');
        $builder_positions->select('a.id,b.name,a.shelf,a.position,a.views,b.width,b.height');
        $builder_positions->join('products b', 'a.id_product=b.id', 'left');
        $builder_positions->join('files c', 'b.image=c.src', 'left');
        $builder_positions->orderBy('a.position', 'ASC');
        $builder_positions->groupBy('a.position');
        $builder_positions->where('id_scenario', $id_scenario);
        $query = $builder_positions->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_results_by_product($posX1, $posX2, $posY1, $posY2, $id_scenario)
    {
        $builder_eye_tracking_results = $this->db->table('eye_tracking_results');
        $builder_eye_tracking_results->select('time');
        $builder_eye_tracking_results->where('scenario_id', $id_scenario);
        $builder_eye_tracking_results->where('docX >', $posX1);
        $builder_eye_tracking_results->where('docX <', $posX2);
        $builder_eye_tracking_results->where('docY >', $posY1);
        $builder_eye_tracking_results->where('docY <', $posY2);
        $query = $builder_eye_tracking_results->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function insert_eye_tracking($data)
    {
        $builder = $this->db->table('eye_tracking_results');
        $builder->set($data);
        $builder->insert();
    }

    public function insert_orders_clients($data)
    {
        $builder = $this->db->table('user_orders');
        $builder->set($data);
        $builder->insert();
    }
    public function save_form_answers($data)
    {
        $builder = $this->db->table('survey_form_answers');
        $builder->set($data);
        $builder->insert();
        return $this->db->insertId();
    }

    public function get_id_survey($email)
    {
        $builder = $this->db->table('survey_form_answers');
        $builder->where(['id_user' => $email]);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function survey_answer_by_user($id_survey, $data)
    {
        $builder = $this->db->table('survey_answer_by_user');

        for ($i = 0; $i < count($data); $i++) {
            $array = [$id_survey, $data[$i][0], $data[$i][1]];
            $builder->select('*');
            $builder->where('id_survey', $id_survey);
            $builder->where('title', $data[$i][0]);
            $query = $builder->get();
            if ($query->getNumRows() > 0) {
                $update = $this->db->table('survey_answer_by_user');
                $update->where('id_survey', $id_survey);
                $update->where('title', $data[$i][0]);
                $update->update(['id_survey' => $id_survey,'title' => $data[$i][0],'answers' => $data[$i][1]]);
            } else {
                $insert = $this->db->table('survey_answer_by_user');
                $insert->set(['id_survey' => $id_survey,'title' => $data[$i][0],'answers' => $data[$i][1]]);
                $insert->insert();
            }
        }
    }

    public function update_form_answers($data, $id)
    {
        $builder = $this->db->table('survey_form_answers');
        $builder->set($data);
        $builder->where('id', $id);
        $builder->update();
    }

    public function get_answers_form()
    {
        $builder = $this->db->table('survey_form_answers');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_answers_form_by_user($id)
    {
        $builder = $this->db->table('survey_form_answers');
        $builder->where(['id_user' => $id]);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_products_form()
    {
        $builder = $this->db->table('survey_form_answers');
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function get_user($id)
    {
        $builder = $this->db->table('users');
        $builder->where(['id' => $id]);
        $builder->select('name, email');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function get_products_ean_by_user($id)
    {
        $builder = $this->db->table('survey_form_answers');
        $builder->where(['id_user' => $id]);
        $builder->select('answers');
        $query = $builder->get();
        return $query->getResultArray();
    }
}
