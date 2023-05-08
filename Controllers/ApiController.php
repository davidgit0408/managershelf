<?php

namespace App\Controllers;

class ApiController extends BaseController
{

    public function __construct()
    {
        $this->request = service('request');
        $this->response = service('response');
        $this->db = \Config\Database::connect();
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function index_get($uuid = 0)
    {
        $uuid = $_GET['email'];
        $scenario = $_GET['url'];
        $array['scenario_id'] = $scenario;
        $array['uuid'] = $uuid;
        $this->db->table('eye_tracking_results')->insert($array);
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */

    public function index_post()
    {
        $uuid = $this->request->getPost('uuid');
        $query = $this->db->table('users')->order_by('id', 'ASC')->where(array('email' => $uuid))->get();
        if ($query->num_rows() > 0) {
            $user = $query->result_array();
            $query = $this->db->table('carts')->order_by('id', 'ASC')->where(array('id_client' => $user[0]["id"], 'bought >' => 0))->get();
            if ($query->num_rows() > 0) {
                $this->response($query->result_array(), REST_Controller::HTTP_OK);
            } else {
                $result['main'] = 'Nenhum produto foi comprado por este usuário.';
                $this->response($result, 500);
            }
        } else {
            $result['main'] = 'Este usuário ainda não foi cadastrado.';
            $this->response($result, 404);
        }
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function index_put($id)
    {
    }

    /**
     * Get All Data from this method.
     *
     * @return Response
     */
    public function index_delete($id)
    {
    }
}
