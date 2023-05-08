<?php

namespace App\Controllers;

use App\Controllers\BaseController;

require_once 'vendor/autoload.php';

class PagamentoController extends BaseController
{
    protected $session;
    protected $adminModel;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->adminModel = new \App\Models\AdminModel();
        $this->db = \Config\Database::connect();
        $this->db_auxiliar = \Config\Database::connect('auxiliar');
    }
    public function do_payment()
    {
        $builder = $this->db->table('sales');
        $builder->where('id', $_POST['id']);
        if (isset($_POST['url_boleto'])) {
            $builder->set('boleto', $_POST['url_boleto']);
        }
        $builder->set('payment_method', $_POST['method']);
        $builder->set('transaction', $_POST['id_transacao']);
        $builder->update();
    }
    public function do_paymentCredit()
    {
        $builder = $this->db->table('sales');
        $builder->where('id', $_POST['id']);
        $builder->set('payment_method', $_POST['payment_method']);
        $builder->set('transaction', $_POST['id_transacao']);
        $builder->update();
    }
    public function update_payment($status, $id)
    {


        // echo $id . "<br>";
        $get_id = $this->db->table('sales');
        $get_id->select('company_id');
        $get_id->where('transaction', $id);
        $query = $get_id->get();
        if ($query->getNumRows() > 0) {
            $aoba = $query->getRowArray();
            $builder = $this->db->table('sales');
            $builder->where('transaction', $id);
            switch ($status) {
                case 'pending':
                    $builder->set('status', 'Aguardando pagamento');
                    break;
                case 'approved':
                    $company = $this->db->table('company');
                    $company->where('id', $aoba['company_id']);
                    $company->set('status', 'Ativo');
                    $company->update();
                    $builder->set('status', 'Aprovado');
                    break;
                case 'in_process':
                    $builder->set('status', 'Em processo');
                    break;
                case 'rejected':
                    $builder->set('status', 'Rejeitado');
                    break;
                case 'cancelled':
                    $builder->set('status', 'Cancelado');
                    break;
            }
            $builder->update();
        }
    }


    //Esta função é para verificar no banco todos os pagamentos do usuário logado (ID DO PAGAMENTO) e retornar o status atual do pagamento para o banco (UPDATE)
    public function att_payment()
    {
        $builder = $this->db->table('new_table');
        $builder->where('id_user', $_POST['id_user']);
        $query = $builder->get();
        foreach ($query->getResult('array') as $data) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => base_url() . '/MercadoPago/controllers/notificationController.php',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_POSTFIELDS => (['transacao' => $data['transacao']]),
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            //Atualizar no banco o status atual do pagamento
            $builder_notification = $this->db->table('new_table');
            $builder_notification->where('id_user', $_POST['id_user'], 'transacao', $data['transacao']);
            $builder_notification->set('status', $response['status']);
            $builder->set('boleto', $response['url_boleto']);
            $builder_notification->update();
        }
    }

    public function payment_status()
    {
        $data = (['topic' => $_POST['topic'], 'id' => $_POST['id']]);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => base_url() . '/MercadoPago/controllers/att.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        var_dump($response);
    }
}
