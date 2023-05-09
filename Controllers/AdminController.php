<?php

namespace App\Controllers;

require_once "vendor/autoload.php";

use CodeIgniter\I18n\Time;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use CodeIgniter\Cookie\Cookie;
use DateTime;
use DateTimeZone;

class AdminController extends BaseController
{

    private $adminModel;
    private $email;
    private $session;
    private $permissionModel;
    private $db;


    public $emailConfig = array(
        'protocol' => 'smtp',
        'SMTPHost' => 'smtp.gmail.com',
        'SMTPPort' => 587,
        'SMTPUser' => 'info@dkmamanagershelf.com',
        'SMTPPass' => 'mxnaqdgchlclziqu',
        'SMTPTimeout'  => '60',
        // 'SMTPCrypto' => 'tls',
        'mailType'  => 'html',
        'charset'  => 'utf-8',
        'newline'  => "\r\n",
        'validate'  => TRUE
    );

    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->adminModel = new \App\Models\AdminModel();
        $this->session = \Config\Services::session();
        $this->permissionModel =  new \App\Models\PermissionModel();
        $this->db = \Config\Database::connect();
        $this->request = service('request');
        $data = array();
        $data['route'] = '';
    }
    public function index()
    {
        $data = [
            'id' =>  $this->session->get('id'),
            'created_by' => $this->session->get('created_by')
        ];

        echo view("commom/template/html-header");
        echo view('Pages/login');
        echo view("commom/template/html-footer", $data);
    }

    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = md5($this->request->getPost('password'));
        try {
            $data = $this->adminModel->login($email, $password);
        } catch (\Throwable $th) {
            $this->session->setFlashdata('error_msg', $th->getMessage());
            $logs = array(
                'action' => 'Login (' . $email . ')',
                'type' => 'Erro',
                'message' => $th->getMessage(),
                //'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
            return  redirect()->to("/");
        }
        $permissions = $this->permissionModel->check_permission($data['id']);
        try {
            $permissions = $this->permissionModel->check_permission($data['id']);
        } catch (\Throwable $th) {
            $permissions = array();
        }

        $logs = array(
            'action' => 'Login',
            'type' => 'Sucesso',
            'id_user' => $data['id'],
            'ip' => $this->request->getPost('ip_address')
        );
        $this->adminModel->insert_logs($logs);

        $sessionData = [
            'id' => $data['id'],
            'role' => $data['role'],
            'email' => $data['email'],
            'name' => $data['name'],
            'created_by' => $data['created_by'],
            'permissions' =>  $permissions,
            'controller' => 'admin'
        ];

        $this->session->set($sessionData);
        return redirect()->to("dashboard");
    }

    public function logout()
    {
        $logs = array(
            'action' => 'Logout',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        $this->session->destroy();
        return redirect()->to('');
    }

    public function dashboard_admin()
    {


        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        if ($id_user == NULL) {
            return redirect()->to('');
        }
        if ($id_user == 1) {
            $data['companies'] = $this->adminModel->get_all_available_company();
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['companies'] = $this->adminModel->get_available_company_by($id_user);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        //Verificação se usuário visualizou o popup
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'dashboard';

        $data['view'] = $data['usuario']['view_popup'];
        $data['version'] = $this->adminModel->get_all_versions();
        // $notices = $this->adminModel->get_all_notices();

        $notices = [];

        $data['notices'] = [];

        foreach ($notices as $notice) {
            $notice['notices_img'] = $this->adminModel->get_all_img_notices($notice['ID']);
            array_push($data['notices'], $notice);
        }


        echo view("commom/template/html-header.php");
        // echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $notices);
        echo view('admin/dashboard-admin.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function all_company()
    {
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'all_company';

        if ($id_user == 1) {
            $data['companies'] = $this->adminModel->get_all_available_company();
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['companies'] = $this->adminModel->get_available_company_by($id_user);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        // print_r($data);die;

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/company/companies', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function new_company()
    {

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'new_company';
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        $permissions = $this->permissionModel->check_permission($data['usuario']["id"]);
        $data['permissions'] = $permissions;

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/company/new_company.php');
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function edit_company()
    {
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);

        $id_company = $_GET['id_company'];
        $data['companies'] = $this->adminModel->get_company_by_id($id_company);

        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/company/edit_company.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function payment($transition_id)
    {
        $data['id_transacao'] = $transition_id ? $transition_id : $this->adminModel->get_last_inserted_sale()['id'];
        $data["transacao"] = $this->adminModel->get_sale_by_id($data['id_transacao']);
        $data['total'] = $data["transacao"][0]["total_value"];
        $sale = $this->adminModel->get_sale_by_id($data['id_transacao']);
        if ($sale[0]["status"] == "Aguardando pagamento") $this->session->getFlashdata('error_msg', 'Pagamento já efetuado.');

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/company/payment.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }


    public function  confirm_pix()
    {
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view('admin/formulario/confirm_pix.php');
    }


    public function  save_form_answers_confirm_pix()
    {
        $dataJson = $this->request->getJSON();
        $data['id_user'] = $dataJson->id_user;
        $data['id_company'] = "9999";
        $data['answers'] = json_encode($dataJson->answers);
        $data['data_send'] = date('Y-m-d H:i:s');

        $this->send_email_notification($data['id_user']);

        $this->adminModel->save_form_answers($data);
        return true;
    }


    public function  obrigado()
    {
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view('admin/formulario/obrigado.php');
    }


    public function obrigado_por_concluir()
    {
        $session = $this->session->get("email");
        if (empty($session)) return redirect()->to('/form/init?id_company=1804');

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view('admin/formulario/obrigado_concluido.php');
    }


    public function  obrigado_ja_realizou()
    {
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view('admin/formulario/obrigado_duplicado.php');
    }


    public function  obrigado_cota_encerrada()
    {
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view('admin/formulario/obrigado_duplicado2.php');
    }


    public function init_search_form()
    {
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view('admin/formulario/formulario_pesquisa.php');
    }


    public function  save_form_answers()
    {

        $dataJson = $this->request->getJSON();

        /* salvando respostas clientes */
        $data['id_user'] = $dataJson->id_user;
        $data['id_company'] = $dataJson->id_company;
        $data['data_send'] = date('Y-m-d H:i:s');

        $checkClient = $this->adminModel->check('survey_form_answers', 'id_user', $dataJson->id_user);
        if ($checkClient) {
            $id = $this->adminModel->get_id_survey($dataJson->id_user);
            $id_survey = intval($id[0]['id']);
        } else {
            $id_survey = $this->adminModel->save_form_answers($data);
        }

        // salvando cada resposta por linha
        $i = 0;
        foreach ($dataJson->answers as $key => $value) {
            $data_answers[$i][0] = $key;
            $data_answers[$i][1] = $value;
            $i++;
        };
        $this->adminModel->survey_answer_by_user($id_survey, $data_answers);


        /* dados clientes */
        $client['name'] = $dataJson->answers->nome . " " . $dataJson->answers->sobrenome;
        $client['email'] = $dataJson->id_user;
        $client['id_company'] = $dataJson->id_company;
        $client['birthday'] = $dataJson->answers->age;
        $client['tel'] = $dataJson->answers->classe_social;
        $client['cellphone'] = $dataJson->answers->usa_em_casa;
        $client['cpf_cnpj'] = json_decode(json_encode($dataJson->answers), true)['chave-pix'] . " (" . json_decode(json_encode($dataJson->answers), true)['tipo-pix'] . ")";
        $client['state'] = $dataJson->answers->estado;
        $client['pass'] = md5('1234'); //senha padrão       

        $check = $this->adminModel->check('users', 'email', $dataJson->id_user);
        //caso usuário não exista, cadastra

        if (!$check) {
            $this->adminModel->insert_client($client);
        }

        return true;
    }


    public function  save_end_form_answers()
    {
        $dataJson = $this->request->getJSON();
        $data['id_user'] = $dataJson->id_user;
        $data['id_company'] = $dataJson->id_company;

        $client['email'] = $data['id_user'];
        $client['img_url'] = bin2hex(random_bytes(20));
        $this->adminModel->update_user_by_email($client);

        $this->session->set('email', $client['email']);
        $this->session->set('token', $client['img_url']);

        $oldAnswers = $this->adminModel->get_answers_form_by_user($data['id_user']);
        $oldAnswersId = $oldAnswers[0]['id'];
        $oldAnswers = json_decode($oldAnswers[0]["answers"], true);
        $newAnswers = json_decode(json_encode($dataJson->answers), true);

        $data['answers'] = json_encode($oldAnswers + $newAnswers);
        $data['data_send'] = date('Y-m-d H:i:s');
        $this->adminModel->update_form_answers($data, $oldAnswersId);

        $this->send_email_notification($data['id_user']);

        return true;
    }

    public function send_email_notification($id_user)
    {
        $this->email->initialize($this->emailConfig);
        $this->email->setFrom($this->emailConfig["SMTPUser"], "ManagerShelf");
        $this->email->setTo("rg.almeida2012@gmail.com");
        $this->email->setSubject('Confirmação de PIX!');
        $this->email->attach(base_url('assets/img/brand/logo.png'), 'inline');
        $cid = $this->email->setAttachmentCID(base_url('assets/img/brand/logo.png'));


        $message_formated = view("email/message.php", [
            "title" => $id_user . " confirmou seu pix em nosso sistema.",
            "logo" => $cid,
            "nome" => "ADM",
            "pre_button_text" => "Consulte o banco de dados.",
            "button_link" => "#!",
            "button_text" => "Desconsidere esse botão",
            "after_button_text" => "Caso você não tenha feito nenhuma solicitação em nosso sistema, desconsidere esta mensagem.",
        ]);

        $this->email->setMessage($message_formated);
        $this->email->send();
    }

    public function end_shopping_search_form()
    {
        $uuid = $_GET['uuid'];
        $id = $_GET['id'];
        $builder = $this->db->table('users');
        $builder->orderBy('id', 'ASC');
        $builder->where(array('email' => $uuid));
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            $user = $query->getResultArray();
            $builder = $this->db->table('carts');
            $builder->orderBy('id', 'ASC');
            $builder->select('carts.*, orders.id_cart as orders_id_cart');
            $builder->join('orders as order', 'carts.id_cart = order.id_cart');
            $builder->join('orders', 'orders.id_cart = carts.id_cart AND orders.id_user = ' . $id);
            $builder->where(array('id_client' => $user[0]["id"], 'bought >' => 0));
            $queryInner = $builder->get();
            if ($query->getNumRows() > 0) {
                $products = $queryInner->getResultArray();
                $data['products'] = array();
                $data['bought'] = 0;
                $data['id_scenario'] = 140;
                for ($i = 0; $i < count($products); $i++) {
                    $productsDetails = $this->db->table('products')
                        ->select('name,price,image,url,feature,brand,category,grammage')
                        ->where(array('ean' => $products[$i]['product_ean']))
                        ->get()->getResultArray();

                    $category = $this->db->table('categories')
                        ->select('name')
                        ->where(array('id' => $productsDetails[0]['category']))
                        ->get()->getResultArray();

                    $product['id'] = $products[$i]['id'];
                    $product['id_cart'] = $products[$i]['id_cart'];
                    $product['id_client'] = $products[$i]['id_client'];
                    $product['id_scenario'] = $products[$i]['id_scenario'];
                    $data['id_scenario'] = $products[$i]['id_scenario'];
                    $product['id_company'] = $products[$i]['id_company'];
                    $product['product_ean'] = $products[$i]['product_ean'];
                    $product['product_name'] = $productsDetails[0]['name'];
                    $product['product_price']  = $productsDetails[0]['price'];
                    $product['product_image']  = $productsDetails[0]['image'];
                    $product['product_url']  = $productsDetails[0]['url'];
                    $product['product_feature']  = $productsDetails[0]['feature'];
                    $product['product_brand']  = $productsDetails[0]['brand'];
                    $product['product_category'] = $productsDetails[0]['category'];
                    $product['product_grammage']  = $productsDetails[0]['grammage'];
                    $product['category'] = $category[0]['name'];
                    $product['removed_cart'] = $products[$i]['removed_cart'];
                    $product['removed_checkout'] = $products[$i]['removed_checkout'];
                    $product['sequence'] = $products[$i]['sequence'];
                    $product['viewed'] = $products[$i]['viewed'];
                    $product['bought'] = $products[$i]['bought'];
                    $data['bought'] += $products[$i]['bought'];
                    $product['time'] = $products[$i]['time'];
                    $product['ip_public'] = $products[$i]['ip_public'];
                    $product['ip_private'] = $products[$i]['ip_private'];
                    if (isset($products[$i]['data'])) $product['data'] = $products[$i]['data'];
                    $product['orders_id_cart'] = $products[$i]['orders_id_cart'];
                    $data['products'][$i] = $product;
                }
                echo view("commom/template/html-header.php");
                echo view("admin/template/splash.php");
                echo view('admin/formulario/formulario_finalizacao.php', $data);
            } else {
                $result['main'] = 'Nenhum produto foi comprado por este usuário.';
                $this->response->setStatusCode(500)->setJSON($result);
            }
        } else {
            $result['main'] = 'Este usuário ainda não foi cadastrado.';
            $this->response->setStatusCode(404)->setJSON($result);
        }
    }


    public function view_result_answers()
    {
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'form/results';
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        $permissions = $this->permissionModel->check_permission($data['usuario']["id"]);
        $data['permissions'] = $permissions;

        $user = $this->adminModel->get_answers_form();
        $data['result_answers'] = array();

        for ($i = 0; $i < count($user); $i++) {
            $userInfo = $this->adminModel->get_user($user[$i]['id_user']);
            $data['result_answers'][$i] = [
                'id' => $user[$i]['id_user'],
                'name' => $userInfo[$i]['name'],
                'email' => $userInfo[$i]['email'],
                'data' =>   $user[$i]['data_send']
            ];
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/formulario/resultado_pesquisas.php');
        //echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function get_products_form_by_ean()
    {
        $dataJson = $this->request->getJSON();
        $id_user = $dataJson->userId;
        $allEan = $this->adminModel->get_products_ean_by_user($id_user);
        $data['products_user_answers'] = array();
        foreach ($allEan as $key => $jsons) {
            foreach ($jsons as $key => $value) {
                $array_products = json_decode($value, true);
                for ($i = 0; $i < count($array_products); $i++) {
                    $allProductInfo = $this->adminModel->get_product_by_ean($array_products['page-' . $i + 1]);
                    $data['products_user_answers'][$i] = [
                        'name' => $allProductInfo[0]['name'],
                        'price' => $allProductInfo[0]['price'],
                        'image' =>   $allProductInfo[0]['image'],
                        'url' =>   $allProductInfo[0]['url'],
                        'feature' =>   $allProductInfo[0]['feature'],
                        'brand' =>   $allProductInfo[0]['brand']
                    ];
                }
            }
        }

        return json_encode($data['products_user_answers']);
    }


    public function add_company()
    {

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $dt_begin = strtotime($this->request->getPost('dt_begin'));
        $dt_end = strtotime($this->request->getPost('dt_end'));
        $company = array(
            'name' => $this->request->getPost('name'),
            'qtd_scenarios' => $this->request->getPost('qtd_scenarios'),
            'qtd_pesquisa' => $this->request->getPost('qtd_pesquisa'),
            'qtd_eyetracking' => $this->request->getPost('qtd_eyetracking'),
            'link' => $this->request->getPost('link'),
            'status' => 'Pendente',
            'id_user' => $this->session->get('id'),
            'dt_begin' => $this->request->getPost('dt_begin'),
            'dt_end' => $this->request->getPost('dt_end')
        );
        $client = $this->adminModel->get_user_by_id($company['id_user']);
        if ($dt_begin < $dt_end) {
            $id = $this->adminModel->insert_company($company);
            $company['id'] = $id;
            $this->session->getFlashdata('success_msg', 'Estudo inserido com sucesso!');

            $logs = array(
                'action' => 'Criou um novo estudo.',
                'type' => 'Sucesso',
                'message' => 'Estudo inserido com sucesso!',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
            $notification = array(
                'content' => $this->session->get('name') . ' adicionou ' . $this->request->getPost('name'),
                'id_user' => $this->session->get('id'),
                'show_to' => $show_to
            );
            $this->adminModel->insert_notification($notification);


            //$this->set_study_data_for_pagseguro(1, $company);
            // esta dando erro no redirect da set_study_data_for_pagseguro, entao movi ela para dentro do add_company

            /* NA INVOCAÇÃO DESSE TRECHO SÃO PASSADOS O TOTAL A PAGAR E O ARRAY PRODUTO */
            $product = $company;
            if ($this->request->getPost('test') === 'yes') {
                $total = 1;
            } else {
                $total = 5000 + ($company['qtd_scenarios'] * 250) + ($company['qtd_eyetracking'] * 25);
            }
            $string = "0123456789abcdefghijklmnopqrstuvwxyz" . strtoupper("abcdefghijklmnopqrstuvwxyz");
            $string = str_shuffle($string);
            $key = date("Y") . substr($string, 0, 20);

            $data = array(
                'client_id' => $product['id_user'],
                'company_id' => $company['id'],
                'key' => $key,
                'active' => 1,
                'total_value' => $total,
                'status' => 'Aguardando pagamento',
                'created_in' => date("Y-m-d H:i:s")
            );
            $transaction_id = $this->adminModel->save_sale($data);
            return redirect()->to('/payment/transition_id=' . $transaction_id . '/');
        } else {
            $this->session->getFlashdata('company_values', $company);
            $this->session->getFlashdata('error_msg', 'A data inicial precisa ser inferior a data final!');
            return  redirect()->to('/new_company');
        }
    }

    public function update_company()
    {
        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $dt_begin = new Time($this->request->getPost('dt_begin'));
        $dt_end = new Time($this->request->getPost('dt_end'));

        if ($dt_begin < $dt_end) {
            $company = array(
                'id' => $this->request->getPost('id'),
                'name' => $this->request->getPost('name'),
                'link' => $this->request->getPost('link'),
                'dt_begin' => $this->request->getPost('dt_begin'),
                'dt_end' => $this->request->getPost('dt_end')
            );
            $this->adminModel->update_company($company);
            $this->session->getFlashdata('success_msg', 'Estudo atualizado com sucesso!');
            $logs = array(
                'action' => 'Editou um estudo',
                'type' => 'Sucesso',
                'message' => 'Estudo atualizado com sucesso!',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
            $notification = array(
                'content' => $this->session->get('name') . ' editou ' . $this->request->getPost('name'),
                'id_user' => $this->session->get('id'),
                'show_to' => $show_to
            );
            $this->adminModel->insert_notification($notification);
            return redirect()->to('/all_company');
        } else {
            $this->session->getFlashdata('error_msg', 'A data inicial precisa ser inferior a data final!');
            return  redirect()->to('/edit_company?id_company=' . $this->request->getPost('id'));
        }
    }

    public function loading_img()
    {
        $imagens  = $this->adminModel->get_all_files();
        if (is_array($imagens)) {
            foreach ($imagens as $img) {
                if (file_exists($img['src'])) {
                    $ibagens[] =  $img;
                }
            }
            echo json_encode($ibagens);
        }
    }

    public function edit_product($id_product)
    {
        function mtimecmp($a, $b)
        {
            $mt_a = filemtime($a);
            $mt_b = filemtime($b);

            if ($mt_a == $mt_b)
                return 0;
            else if ($mt_a < $mt_b)
                return -1;
            else
                return 1;
        }

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'all_products';
        $data['categories'] = $this->adminModel->get_all_categories();

        if ($id_user == 1) {
            $data['imagens'] = $this->adminModel->get_all_files();
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['imagens'] = $this->adminModel->get_files_by($created_by);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['imagens'] = $this->adminModel->get_files_by($id_user);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }
        $data['product'] = $this->adminModel->get_product_by_id($id_product);
        $data['product_images'] = "";
        $url = $data['product'][0]['url'];
        if ($url != "") {
            if (json_decode($url, true)) {
                $data['product_images'] = json_decode($url, true);
            } else {
                $product_images = json_encode($url, true);
                $data['product_images'] = json_decode($product_images, true);
            }
        }

        // print_r($data);die;
        echo view("commom/template/html-header.php");
        //echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/product/edit_product.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    function get_company_info()
    {
        $id = json_decode(json_encode($this->request->getPost('id')), true);
        $data = date("Y-m-d");
        $get_company = $this->adminModel->get_company_by_id($id);
        $max_pesquisa = intval($get_company[0]['qtd_pesquisa']);
        if ($this->adminModel->get_order_by_company($id) != 0) {
            $total_pesquisa = $this->db->query("SELECT COUNT(id) as total FROM `survey_form_answers` WHERE `answers` LIKE '%42-outros%'")->getResultArray()[0]["total"];
            // $total_pesquisa = count($this->adminModel->get_order_by_company($id));
        } else {
            $total_pesquisa = 0;
        }
        if ($this->adminModel->get_order_by_date_company($id, $data) != 0) {
            $hoje = count($this->adminModel->get_order_by_date_company($id, $data));
        } else {
            $hoje = 0;
        }

        $amostra = ceil(($total_pesquisa / $max_pesquisa) * 100);
        $faltantes = $max_pesquisa - $total_pesquisa;
        $company = array(
            'pesquisas' => $total_pesquisa,
            'hoje' => $hoje,
            'amostra' => $amostra,
            'entrevista' => $faltantes
        );
        echo json_encode($company);
    }

    function get_orders()
    {
        $date = $this->request->getPost('date');
        $id_company = json_decode(json_encode($this->request->getPost('id_company')), true);

        $get_orders = $this->adminModel->get_order_by_date_company($id_company, $date);
        if ($get_orders != 0) {
            $qtd_orders = count($get_orders);
        } else {
            $qtd_orders = 0;
        }

        echo $qtd_orders;
    }

    public function all_products()
    {

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'all_products';

        if ($id_user == 1) {
            $data['companies'] = $this->adminModel->get_all_available_company();
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) { //se foi criado por um usuário tem acesso a todos os cenários criados por ele
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['companies'] = $this->adminModel->get_available_company_by($id_user);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        if (isset($_GET['pagina'])) $pc = $_GET['pagina'];
        else $pc = 1;
        if (isset($_GET['pesquisa'])) $pesquisa = $_GET['pesquisa'];
        else $pesquisa = 0;
        if (isset($_GET['orderBy'])) $orderBy = $_GET['orderBy'];
        else $orderBy = 0;
        if (isset($_GET['order'])) $order = $_GET['order'];
        else $order = 0;

        $total_reg = 10;
        $offset = $pc - 1;
        $offset *= $total_reg;

        $data['total_reg'] = $total_reg;

        if (!$created_by) {
            $products = $this->adminModel->get_products_by_limit($id_user, $pesquisa, $total_reg, $offset, $order, $orderBy);
            $data['total'] = $this->adminModel->get_products_by_limit($id_user, $pesquisa, 0, 0, 0, 0);
        } else { // se tiver sido criado por um user verá os mesmos produtos que o criador
            $products = $this->adminModel->get_products_by_limit($created_by, $pesquisa, $total_reg, $offset, $order, $orderBy);
            $data['total'] = $this->adminModel->get_products_by_limit($created_by, $pesquisa, 0, 0, 0, 0);
        }
        $data['products'] = $products;

        echo view("commom/template/html-header.php");
        if (!isset($_GET['pagina'])) {
            echo view("admin/template/splash.php");
        }
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/product/all.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function all_alert_products()
    {


        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'all_planograms';

        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_alert_products(0);
            $data['available_company'] = $this->adminModel->get_all_available_company();
            $data['companies'] = $this->adminModel->get_all_company();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_alert_products($created_by);
            $data['available_company'] = $this->adminModel->get_available_company_by($created_by);
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_alert_products($id_user);
            $data['available_company'] = $this->adminModel->get_available_company_by($id_user);
            $data['companies'] = $this->adminModel->get_company_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/planogram/alert_products.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function alert_product_view()
    {


        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'all_planograms';

        $planogram_id = $_GET['planogram_id'];
//        $product_id = $_GET['product_id'];

        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_alert_product_by_id(0, $planogram_id);
            $data['available_company'] = $this->adminModel->get_all_available_company();
            $data['companies'] = $this->adminModel->get_all_company();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_alert_product_by_id(0, $planogram_id);
            $data['available_company'] = $this->adminModel->get_available_company_by($created_by);
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_alert_product_by_id(0, $planogram_id);
            $data['available_company'] = $this->adminModel->get_available_company_by($id_user);
            $data['companies'] = $this->adminModel->get_company_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/planogram/alert_product_info.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function bigdata_products()
    {

        $this->bigdataModel = new \App\Models\BigDataModel();
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'bigdata_products';

        if ($id_user == 1) {
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) { //se foi criado por um usuário tem acesso a todos os cenários criados por ele
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        if (isset($_GET['pagina'])) $pc = $_GET['pagina'];
        else $pc = 1;
        if (isset($_GET['pesquisa'])) $pesquisa = $_GET['pesquisa'];
        else $pesquisa = 0;
        if (isset($_GET['orderBy'])) $orderBy = $_GET['orderBy'];
        else $orderBy = 0;
        if (isset($_GET['order'])) $order = $_GET['order'];
        else $order = 0;

        $total_reg = 10;
        $offset = $pc - 1;
        $offset *= $total_reg;

        $data['total_reg'] = $total_reg;

        if (!$created_by) {
            $products = $this->bigdataModel->get_products_by_limit($id_user, $pesquisa, $total_reg, $offset, $order, $orderBy);
            $data['total'] = $this->bigdataModel->get_products_by_limit($id_user, $pesquisa, 0, 0, 0, 0);
        } else { // se tiver sido criado por um user verá os mesmos produtos que o criador
            $products = $this->bigdataModel->get_products_by_limit($created_by, $pesquisa, $total_reg, $offset, $order, $orderBy);
            $data['total'] = $this->bigdataModel->get_products_by_limit($created_by, $pesquisa, 0, 0, 0, 0);
        }

        // buscar ean para bloquear produtos que ja foram importados

        $this->db = \Config\Database::connect();
        $builder = $this->db->table('products');
        $builder->select('ean');
        $builder->where('id_user', $id_user);
        $query = $builder->get();
        $data['ean_array'] = array_column($query->getResultArray(), 'ean');

        // ---------------------------------------------------------

        $data['products'] = $products;


        echo view("commom/template/html-header.php");
        if (!isset($_GET['pagina'])) {
            echo view("admin/template/splash.php");
        }
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/product/all_bigdata.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function bigdata_import($array_products)
    {
        $products = json_decode($array_products);
        $this->bigdataModel = new \App\Models\BigDataModel();
        $id_user = $this->session->get('id');
        for ($i = 0; $i < count($products); $i++) {
            $getProduct =  $this->bigdataModel->get_product_by_id($products[$i]);
            $product = array(
                'name' => $getProduct[0]['name'],
                'id_user' =>  $id_user,
                'url' => $getProduct[0]['url'],
                'image' => $getProduct[0]['image'],
                'price' => $getProduct[0]['price'],
                'brand' => $getProduct[0]['brand'],
                'producer' => $getProduct[0]['producer'],
                'category' => $getProduct[0]['category'],
                'grammage' => $getProduct[0]['grammage'],
                'feature' => $getProduct[0]['feature'],
                'ean' => $getProduct[0]['ean'],
                'width' => $getProduct[0]['width'],
                'height' => $getProduct[0]['height']
            );
            $this->adminModel->insert_product($product);
        }
    }

    public function new_product()
    {
        function mtimecmp($a, $b)
        {
            $mt_a = filemtime($a);
            $mt_b = filemtime($b);

            if ($mt_a == $mt_b)
                return 0;
            else if ($mt_a < $mt_b)
                return -1;
            else
                return 1;
        }

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['categories'] = $this->adminModel->get_all_categories();
        $data['route'] = 'new_product';

        //Filtrando as imagens com altura e largura iguais por usuário
        if ($id_user == 1) {
            $data['imagens'] = $this->adminModel->get_all_files();
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['imagens'] = $this->adminModel->get_files_by($created_by);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['imagens'] = $this->adminModel->get_files_by($id_user);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/product/new_product.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function add_product()
    {
        $ean = $this->request->getPost('ean');
        if ($ean == '' || $ean == ' ') {
            $this->session->setFlashdata('error_msg', 'Por favor, preencha o campo "Ean do Produto" com o EAN correspondente a este produto.');
            return redirect()->to('/new_product');
        } else {
            $check = $this->adminModel->check('products', 'ean', $this->request->getPost('ean'));
            if (!$check) {
                $image_gon = $this->request->getPost('images_url2');
                $image_url = $this->request->getPost('images_url');

                if ($this->isJson($image_gon))
                    $image_gon = json_decode($image_gon);

                if ($this->isJson($image_url))
                    $image_url = json_decode($image_url);

                $product = array(
                    'id' => $this->request->getPost('id'),
                    'name' => $this->request->getPost('name'),
                    'id_user' => $this->session->get('id'),
                    'url' => $image_url,
                    'image' => $image_gon,
                    'price' => $this->request->getPost('price'),
                    'brand' => $this->request->getPost('brand'),
                    'producer' => $this->request->getPost('producer'),
                    'category' => $this->request->getPost('category'),
                    'grammage' => $this->request->getPost('grammage'),
                    'feature' => $this->request->getPost('feature'),
                    'ean' => $this->request->getPost('ean'),
                    'width' => $this->request->getPost('width'),
                    'height' => $this->request->getPost('height')
                );
                $this->adminModel->insert_product($product);

                $logs = array(
                    'action' => 'Adicionou um produto.',
                    'type' => 'Sucesso',
                    'id_user' => $this->session->get('id'),
                    'ip' => $this->request->getIPAddress()
                );
                $this->adminModel->insert_logs($logs);

                if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
                else $show_to = $this->session->get('id');
                $notification = array(
                    'content' => $this->session->get('name') . ' adicionou o produto ' . $this->request->getPost('name'),
                    'id_user' => $this->session->get('id'),
                    'show_to' => $show_to
                );
                $this->adminModel->insert_notification($notification);

                $this->session->getFlashdata('success_msg', 'Produto inserido com sucesso!');
                return redirect()->to('/all_products');
            } else {
                $logs = array(
                    'action' => 'Tentou cadastrar produto com EAN já existente.',
                    'message' => 'O EAN informado já está cadastrado. Por favor informe um EAN válido.',
                    'type' => 'Erro',
                    'id_user' => $this->session->get('id'),
                    'ip' => $this->request->getIPAddress()
                );
                $this->adminModel->insert_logs($logs);
                $this->session->getFlashdata('error_msg', 'O EAN informado já está cadastrado. Por favor informe um EAN válido. ');
                return redirect()->to('/new_product');
            }
        }
    }

    public function verificar_alteracao_360($id, $url)
    {
        $product = $this->adminModel->get_product_by_id($id);
        $product = $product[0];

        if ($product['image'] != $url) {
            $data = array(
                'width' => null,
                'height' => null
            );
            $this->adminModel->update_position_by_product($data, $id);
        }
    }

    public function add_image()
    {

        $targetFile = "";
        $caminho = "";
        $target_files_array = [];
        $target_files_string = "";
        $dest_folder1 = 'writable/uploads/produtos/destaque/'; // definindo pasta destino dos arquivos
        if (!empty($_FILES)) {
            if (!file_exists($dest_folder1) && !is_dir($dest_folder1)) mkdir($dest_folder1); // criando pasta caso ela for inexistente
            foreach ($_FILES['file']['tmp_name'] as $key => $value) {

                $name = $_FILES['file']['name'][$key];
                $name_exploded = explode('.', $name);
                $name_slug = $this->slugify($_FILES['file']['name'][$key]);

                $tempFile = $_FILES['file']['tmp_name'][$key]; // pegando imagem inserida no form
                $targetFile = $dest_folder1 . $name_slug . '.' . end($name_exploded); // definido url final
                $target_files_string = $dest_folder1 . $name_slug . '.' . end($name_exploded) . "," . $target_files_string;
                array_push($target_files_array, $targetFile);
                move_uploaded_file($tempFile, $targetFile); // salvando arquivo
            }
            return json_encode($target_files_array);
        }
    }
    function upload_ajax()
    {
        $id_user = $this->session->get('id');
        helper(['form', 'url']);
        $builder = \Config\Database::connect();
        $db = $builder->table('products');

        $input = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/png]',
            ]
        ]);
        if (!$input) {
            print_r('Choose a valid file');
        } else {
            if (!empty($_FILES)) {
                // File upload configuration 
                $img = $this->request->getFile('file');
                $nome = $img->getName();
                $nome_pasta = rand(10, 999999999);
                //$dest_folder1 = 'writable/uploads/produtos/' . $id_user .'/'.  $nome . '/';
                $dest_folder = 'assets/uploads/produtos/' . $id_user . '/' .  $nome_pasta . '/destaque/';
                if (!is_dir($dest_folder)) {
                    mkdir($dest_folder, 0777, true);
                }
                $image = \Config\Services::image();
                $image->withFile($img);
                $image->resize(100, 100, true, 'height');
                $img->move(ROOTPATH . 'assets/uploads/produtos/' . $id_user . '/' . $nome_pasta . '/destaque');
                $c = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿŔŕ?';
                $d = 'AAACAAAcEEEEIIIIDNOOOOOOUUUUYBSaaacaaaceeeeiiiidnoooooouuuuyybyRr-';
                $nome = strtr($nome, utf8_decode($c), $d);
                $nome = str_replace("cI", "c", $nome);
                $nome = str_replace("cc", "c", $nome);
                $nome = str_replace(" ", "_", $nome);
                $nome = preg_replace('/[^-,;^!<>@&\/\sA-Za-z0-9_.-]/', '', $nome);
                list($width, $height) = getimagesize('assets/uploads/produtos/' . $id_user . '/' . $nome_pasta . '/destaque' . '/' . $nome);
                $uploadData['file_name'] = $nome;
                $uploadData['uploaded_on'] = date("Y-m-d H:i:s");
                $data = [
                    'uploaded_on' => $uploadData['uploaded_on'],
                    'file_name' => $uploadData['file_name'],
                    'src' => $dest_folder . $uploadData['file_name'],
                    'width' => $width,
                    'height' => $height,
                    'id_user' => $id_user
                ];
                $this->adminModel->insertProduct($data);
                $logs = array(
                    'action' => 'Fez um upload pela galeria.',
                    'type' => 'Sucesso',
                    'id_user' => $this->session->get('id'),
                    'ip' => $this->request->getIPAddress()
                );
            }
        }
    }

    public function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function update_product()
    {
        $id = $this->request->getPost('id');
        $ean = $this->request->getPost('ean');

        $id_user = $this->session->get('id');

        $image_gon = $this->request->getPost('images_url2');
        $image_url = $this->request->getPost('images_url');

        if ($this->isJson($image_gon))
            $image_gon = json_decode($image_gon);

        if ($this->isJson($image_url))
            $image_url = json_decode($image_url);

        $product = array(
            'id' => $this->request->getPost('id'),
            'name' => $this->request->getPost('name'),
            'price' => $this->request->getPost('price'),
            'url' => $image_url,
            'image' => $image_gon,
            'feature' => $this->request->getPost('feature'),
            'producer' => $this->request->getPost('producer'),
            'ean' => $this->request->getPost('ean'),
            'brand' => $this->request->getPost('brand'),
            'category' => $this->request->getPost('category'),
            'grammage' => $this->request->getPost('grammage'),
            'width' => $this->request->getPost('width'),
            'height' => $this->request->getPost('height')
        );

        if (isset($targetFile)) $product['image'] = $targetFile;
        if (isset($targetFile)) $this->verificar_alteracao_360($id, $targetFile);
        $this->adminModel->update_product($product);
        $logs = array(
            'action' => 'Editou um produto.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');
        $notification = array(
            'content' => $this->session->get('name') . ' editou o produto ' . $this->request->getPost('name'),
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
        $this->session->getFlashdata('success_msg', 'Produto editado com sucesso!');
        return redirect()->to('/all_products');
    }

    function update_product_dimension()
    {
        $id = json_decode(json_encode($this->request->getPost('id')), true);
        $width = json_decode(json_encode($this->request->getPost('width')), true);
        $height = json_decode(json_encode($this->request->getPost('height')), true);

        if (mb_strpos($width, ',') !== false) {
            $width = str_replace(',', '.', $width);
        }
        if (mb_strpos($height, ',') !== false) {
            $height = str_replace(',', '.', $height);
        }
        $product = array(
            'id' => $id,
            'width' => $width,
            'height' => $height
        );
        $this->adminModel->update_product($product);
    }

    public function delete_product()
    {
        $id_product = $_GET['id'];
        $product = $this->adminModel->get_product_by_id($id_product);

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');
        $notification = array(
            'content' => $this->session->get('name') . ' excluiu o produto ' . $product[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);

        $this->adminModel->delete_product($id_product);
        $logs = array(
            'action' => 'Deletou um produto.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        return redirect()->to('/all_products');
    }
    public function reload($id_product)
    {

        $data['imagens'] = $this->adminModel->get_all_files();

        $data['product_images'] = "";
        $url = $data['product'][0]['url'];
        if ($url != "") {
            if (json_decode($url, true)) {
                $data['product_images'] = json_decode($url, true);
            } else {
                $product_images = json_encode($url, true);
                $data['product_images'] = json_decode($product_images, true);
            }
        }
    }

    public function import_export()
    {
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'import_export';

        if ($id_user == 1) {
            $data['companies'] = $this->adminModel->get_all_available_company();
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['companies'] = $this->adminModel->get_available_company_by($id_user);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        $data['categories'] = array();
        $data['brands'] = array();
        $data['producers'] = array();
        $data['all_categories'] = $this->adminModel->get_all_categories();
        $products = $this->adminModel->get_all_products();
        if ($products) foreach ($products as $product) {
            array_push($data['categories'], $product['category']);
            array_push($data['brands'], $product['brand']);
            array_push($data['producers'], $product['producer']);
        }
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/product/import_export.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function interviewed_import()
    {
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'interviewed_import';


        if ($id_user == 1) {
            $data['companies'] = $this->adminModel->get_all_available_company();
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['companies'] = $this->adminModel->get_available_company_by($id_user);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }
        $all_permissions = $this->permissionModel->get_all_permissions();
        $data['all_permissions'] = $all_permissions;

        $permissions = $this->permissionModel->check_permission($data['usuario']["id"]);
        $data['permissions'] = $permissions;

        if (!in_array("IMPORTAR_ENTREVISTADOS", $data['permissions'])) {
            return redirect()->to('/dashboard');
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/interviewed_import/import.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function import_interviewed_csv()
    {
        if (isset($_FILES['file'])) {
            $file_tmp = $_FILES['file']['tmp_name'];
            $handle = fopen($file_tmp, "r");
            $i = 0;
            $interviewdInsert = 0;

            while (($data = fgetcsv($handle)) !== FALSE) {
                if (count($data) !== 28) {
                    $this->session->setFlashdata('error_msg_imp', 'Utilize o modelo padrão para envio de dados.');
                    return redirect()->to('/interviewed_import');
                }
                $interviewd[$i] = $data;

                $interviewd_data = array(
                    'name' => $interviewd[$i][0],
                    'email' => $interviewd[$i][1],
                    'cpf' => $interviewd[$i][3],
                );
                $verifyCpfExists = $this->adminModel->interviewed_cpf_exist($interviewd_data['cpf']);
                if (!$verifyCpfExists) {
                    if ($i > 0) {
                        $insertData = $this->adminModel->insert_interviewed_data($interviewd_data);
                        $id[$i] = $insertData;

                        $interviewd_bigdata = array(
                            'interviewd_id' => $id[$i],
                            'telefone' => $interviewd[$i][2],
                            'idade' => $interviewd[$i][4],
                            'profissao' => $interviewd[$i][5],
                            'genero' => $interviewd[$i][6],
                            'cep' => $interviewd[$i][7],
                            'logradouro' => $interviewd[$i][8],
                            'bairro' => $interviewd[$i][9],
                            'estado' => $interviewd[$i][10],
                            'cidade' => $interviewd[$i][11],
                            'filhos' => $interviewd[$i][12],
                            'qtd_filhos' => $interviewd[$i][13],
                            'age_filhos' => $interviewd[$i][14],
                            'renda' => $interviewd[$i][15],
                            'compras' => $interviewd[$i][16],
                            'valor_compra' => $interviewd[$i][17],
                            'reposicao' => $interviewd[$i][18],
                            'categorias' => $interviewd[$i][19],
                            'cupom' => $interviewd[$i][20],
                            'entrevista' => $interviewd[$i][21],
                            'fluxo_online' => $interviewd[$i][22],
                            'notebook' => $interviewd[$i][23],
                            'webcam' => $interviewd[$i][24],
                            'outro_dispositivo' => $interviewd[$i][25],
                            'conheceu' => $interviewd[$i][26],
                            'data' => $interviewd[$i][27],
                        );
                        $this->adminModel->insert_interviewed_bigdata($interviewd_bigdata);
                        $interviewdInsert += 1;
                    }
                }
                $i++;
            }
            $interviewdTotalCount = count($interviewd);
            $this->session->setFlashdata('error_msg_imp', 'Foram importados: ' . $interviewdInsert . ' de ' . $interviewdTotalCount . ' entrevistados');
            return redirect()->to('/interviewed_import');
        }
    }

    public function import_csv()
    {
        $ean_repetido = 0;
        if (isset($_FILES['file'])) {
            $errors = array();
            $allowed_ext = array('csv');

            $file_name = $_FILES['file']['name'];
            // $file_ext = strtolower(end(explode('.', $file_name)));
            $file_size = $_FILES['file']['size'];
            $file_tmp = $_FILES['file']['tmp_name'];

            $row = 0;
            if (($handle = fopen($file_tmp, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
                    $row++;
                    if (count($data) != 8) {
                        $this->session->getFlashdata('error_msg_imp', 'Utilize o modelo padrão para envio de dados.');
                        return redirect()->to('/import_export');
                    }
                    if ($row == 1) continue;

                    $data = array_pad($data, 8, '');

                    $ean = $data[0];
                    $nome = $data[1];
                    $preco = $data[2];
                    $marca = $data[3];
                    $fabricante = $data[4];
                    $gramatura = $data[5];
                    $caracteristica = $data[6];
                    $categoria = $data[7];

                    $check = $this->adminModel->check('products', 'ean', $ean);
                    if (!$check) {
                        //consulta se existe a categoria para pegar o id
                        $get_category = $this->adminModel->get_category_by_name($categoria);
                        if ($get_category) {
                            $category = $get_category[0]['id'];
                        } else {
                            //se ainda não existir a categoria cria
                            $category_name = array('name' => $categoria);
                            $this->adminModel->insert_category($category_name);
                            $get_category = $this->adminModel->get_category_by_name($categoria);
                            $category = $get_category[0]['id'];
                        }

                        $product = array(
                            'ean' => $ean,
                            'name' => $nome,
                            'price' => $preco,
                            'brand' => $marca,
                            'producer' => $fabricante,
                            'grammage' => $gramatura,
                            'feature' => $caracteristica,
                            'category' => $category,
                            'id_user' => $this->session->get('id')
                        );
                        $this->adminModel->insert_product($product);
                        $logs = array(
                            'action' => 'Importou produtos em csv.',
                            'type' => 'Sucesso',
                            'id_user' => $this->session->get('id'),
                            'ip' => $this->request->getIPAddress()
                        );
                        $this->adminModel->insert_logs($logs);
                        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
                        else $show_to = $this->session->get('id');

                        $notification = array(
                            'content' => $this->session->get('name') . ' importou produtos por CSV.',
                            'id_user' => $this->session->get('id'),
                            'show_to' => $show_to
                        );
                        $this->adminModel->insert_notification($notification);
                    } else {
                        $ean_repetido++;
                    }
                }
                fclose($handle);
            }
        }
        if ($product) {
            $this->session->getFlashdata('success_msg_imp', 'Produtos inseridos com sucesso. Na sua base existia um total de ' . $ean_repetido . ' ean(s) que já estavam cadastrados em nosso banco de dados e/ou repetidos.');
        } else {
            $this->session->getFlashdata('error_msg_imp', 'Todos os eans inseridos já existem em nosso banco de dados. Nenhum produto cadastrado.');
            $logs = array(
                'action' => 'Inseriu eans repetidos na importação.',
                'type' => 'Erro',
                'message' => 'Todos os eans inseridos já existem em nosso banco de dados. Nenhum produto cadastrado.',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
        }

        return redirect()->to('/import_export');
    }

    public function export_csv()
    {
        $spreadsheet = new Spreadsheet();
        $Excel_writer = new Xlsx($spreadsheet);
        
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'EAN');
        $sheet->setCellValue('B1', 'Nome');
        $sheet->setCellValue('C1', 'Preço');
        $sheet->setCellValue('D1', 'Marca');
        $sheet->setCellValue('E1', 'Fabricante');
        $sheet->setCellValue('F1', 'Gramatura');
        $sheet->setCellValue('G1', 'Característica');
        $sheet->setCellValue('H1', 'Categoria');

        $this->db = \Config\Database::connect();
        $query = $this->request->getPost();
        $builder = $this->db->table('products');
        if ($query['category'] != 'all') $builder->where('category', $query['category']);
        if ($query['brand'] != 'all') $builder->where('brand', $query['brand']);
        if ($query['producer'] != 'all') $builder->where('producer', $query['producer']);
        $query = $builder->get();

        if ($query) {
            $i = 2;
            foreach ($query->getResult('array') as $row) {
                $categoria = '';
                $categories = $this->adminModel->get_category_by_id($row['category']);
                foreach ($categories as $category) {
                    if (empty($category)) {
                        $categoria = 'Dados Inexistentes';
                    } else {
                        $categoria = $category['name'];
                    }
                }
                $sheet->setCellValue('A' . $i, $row['ean']);
                $sheet->setCellValue('B' . $i, $row['name']);
                $sheet->setCellValue('C' . $i, $row['price']);
                $sheet->setCellValue('D' . $i, $row['brand']);
                $sheet->setCellValue('E' . $i, $row['producer']);
                $sheet->setCellValue('G' . $i, $row['feature']);
                $sheet->setCellValue('F' . $i, $row['grammage']);
                $sheet->setCellValue('H' . $i, $categoria);
                $i++;
            }

            $filename = 'Produtos.xlsx';

            header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment;filename=' . $filename);
            header('Cache-Control: max-age=0');

            $Excel_writer->save('php://output');

            $logs = array(
                'action' => 'Exportou produtos em .XLSX',
                'type' => 'Sucesso',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
            if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
            else $show_to = $this->session->get('id');

            $notification = array(
                'content' => $this->session->get('name') . ' exportou planilha de produtos ',
                'id_user' => $this->session->get('id'),
                'show_to' => $show_to
            );
            $this->adminModel->insert_notification($notification);
        } else {
            $logs = array(
                'action' => 'Erro na exportação.',
                'type' => 'Erro',
                'message' => 'Nenhum produto encontrado',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
            $this->session->getFlashdata('error_msg', 'Nenhum produto encontrado.');
            return redirect()->to('/import_export');
        }
    }

    public function export_csv_alert_priducts()
    {
        $spreadsheet = new Spreadsheet();
        $Excel_writer = new Xlsx($spreadsheet);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'EAN');
        $sheet->setCellValue('B1', 'Nome');
        $sheet->setCellValue('C1', 'alert_count');
        $sheet->setCellValue('D1', 'total_count');

        $alert_products_count = $this->adminModel->get_alert_product_by_id(0, $_GET['planogram_id']);
        $alert_products = $this->adminModel->get_alert_product_by_id(0, $_GET['planogram_id']);

        $i = 2;
            foreach ($alert_products as $row) {

                $sheet->setCellValue('A' . $i, $row['id']);
                $sheet->setCellValue('B' . $i, $row['product_name']);
                $sheet->setCellValue('C' . $i, $row['alert_count']);
                $sheet->setCellValue('D' . $i, $row['qty']);
                $i++;
            }

            $filename = 'Alert_Products.xlsx';

            header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment;filename=' . $filename);
            header('Cache-Control: max-age=0');

            $Excel_writer->save('php://output');

            $logs = array(
                'action' => 'Exportou produtos em .XLSX',
                'type' => 'Sucesso',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
            if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
            else $show_to = $this->session->get('id');

            $notification = array(
                'content' => $this->session->get('name') . ' exportou planilha de produtos ',
                'id_user' => $this->session->get('id'),
                'show_to' => $show_to
            );
            $this->adminModel->insert_notification($notification);
    }

    public function all_planograms()
    {


        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'all_planograms';

        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['available_company'] = $this->adminModel->get_all_available_company();
            $data['companies'] = $this->adminModel->get_all_company();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['available_company'] = $this->adminModel->get_available_company_by($created_by);
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['available_company'] = $this->adminModel->get_available_company_by($id_user);
            $data['companies'] = $this->adminModel->get_company_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/planogram/all.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function new_planogram($release_flag)
    {
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['release_flag'] = $release_flag;
        $data['route'] = 'new_planogram';

        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/planogram/new_planogram.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function edit_planogram($id_planogram)
    {
        $id_scenario = $id_planogram;

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'all_planograms';
        $scenario = $this->adminModel->get_planogram_by_id($id_scenario);
        $data['release_flag'] = $scenario[0]['release_flag'];
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['available_company'] = $this->adminModel->get_all_available_company();
            $data['companies'] = $this->adminModel->get_all_company();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['available_company'] = $this->adminModel->get_available_company_by($created_by);
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['available_company'] = $this->adminModel->get_available_company_by($id_user);
            $data['companies'] = $this->adminModel->get_company_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        $products = $this->adminModel->get_all_products();

        $data['products'] = $products;
        $data['categories'] = $this->adminModel->get_all_categories();
        $data['data'] = $this->adminModel->get_planogram_by_id($id_scenario);
        $data['columns_qtd'] = $this->adminModel->get_planogram_columns_quantity($id_scenario);
        $data['shelves_qtd'] = $this->adminModel->get_planogram_shelves_quantity($id_scenario);

        $data['positions_qtd'] = $this->adminModel->get_planogram_positions_quantity($id_scenario);
        $data["shelf_height"] = array();
        $all_positions = $this->adminModel->get_position_by_scenario($id_scenario);
        foreach ($all_positions as $position) {
            $data['columns'][$position["column"]][$position["shelf"]][] = $position;

            if (!array_key_exists($position["shelf"], $data["shelf_height"]))
                $data["shelf_height"][$position["shelf"]] = $position['height'];
            else if ($data["shelf_height"][$position["shelf"]] < $position['height'])
                $data["shelf_height"][$position["shelf"]] = $position['height'];
        }
        //Formatação data
        date_default_timezone_set('America/Sao_Paulo');
        $data['hoje'] = new Time('now', 'America/Sao_Paulo', 'pt_BR');
        $data['formatter'] = $data['hoje'];

        echo view("commom/template/html-header.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/planogram/edit_planogram.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function duplica_planogram($id_planogram)
    {

        $id_scenario = $id_planogram;

        //informações do cenário original
        $get_scenario = $this->adminModel->get_planogram_by_id($id_scenario);
        $name = $get_scenario[0]['name'] . ' - Copy'; //nome do cenário original + Copy para indicar que é um cenário copiado
        $shelves = $get_scenario[0]['shelves'];

        if ($this->session->get('created_by')) $creator = $this->session->get('created_by');
        else $creator = $this->session->get('id');

        $scenario = array(
            'id_user' => $creator,
            'name' => $name,
            'shelves' => $get_scenario[0]['shelves'],
            'status' => 'Cópia'
        );
        $this->adminModel->insert_planogram($scenario);
        $this->session->getFlashdata('success_msg', 'Cenário duplicado com sucesso!');

        //passando as informações do cenário novo para pegar o id
        $get_new_scenario = $this->adminModel->get_planogram_by($scenario);
        $id_new_scenario = $get_new_scenario[0]['id'];

        //pega as posições do cenário original 
        $get_positions = $this->adminModel->get_position_by_scenario($id_scenario);
        //insere as mesmas posições para o cenário duplicado
        foreach ($get_positions as $position) {
            $position_array = array(
                'id_scenario' => $id_new_scenario,
                'shelf' => $position['shelf'],
                'column' => $position['column'],
                'position' => $position['position'],
                'id_product' => $position['id_product'],
                'views' => $position['views'],
                'qty' => $position['qty'],
                'width' => $position['position_width'],
                'height' => $position['position_height']
            );
            $this->adminModel->insert_position($position_array);
        }
        $logs = array(
            'action' => 'Duplicou um cenário.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $notification = array(
            'content' => $this->session->get('name') . ' duplicou o cenário ' . $get_scenario[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
        return redirect()->to('/all_planograms');
    }

    public function search_product()
    {
        $string = $this->request->getPost('string');
        $category = $this->request->getPost('category');
        $products = $this->adminModel->get_products_by_string($string, $category);

        return $this->response->setJSON($products);

        $logs = array(
            'action' => 'Pesquisou por um produto na tela de editar cenário.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
    }

    public function add_planogram()
    {

        if ($this->session->get('created_by')) $creator = $this->session->get('created_by');
        else $creator = $this->session->get('id');

        $scenario = array(
            'shelves' => $this->request->getPost('shelves'),
            'name' => $this->request->getPost('name'),
            'category' => $this->request->getPost('category'),
            'location' => $this->request->getPost('location'),
            'release_flag' => $this->request->getPost('release_flag'),
            'status' => 'Inativo',
            'id_user' => $creator
        );
        $this->adminModel->insert_planogram($scenario);
        //$this->session->getFlashdata('success_msg', 'Cenário inserido com sucesso!');
        $logs = array(
            'action' => 'Criou um cenário.',
            'type' => 'Sucesso',
            'message' => 'Cenário inserido com sucesso!',
            'id_user' => $this->session->get('id'),
            //'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $notification = array(
            'content' => $this->session->get('name') . ' adicionou o cenário ' . $this->request->getPost('name'),
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
        return redirect()->to("all_planograms");
    }

    public function update_planogram()
    {

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');
        $img = $this->request->getPost('url_print');
        //apagando todas posições da prateleira que foi apagada
        $shelves_old = $this->request->getPost('shelves_old');
        $shelves_new = $this->request->getPost('shelves');


        $urlPrintPlanogram = "";
        if ($img) {
            $filteredData = substr($img, strpos($img, ",") + 1);
            $unencodedData = base64_decode($filteredData);
            $file_name = 'scenario' . $this->request->getPost('id') . '.png';
            $dest_folder = 'writable/uploads/scenarios/scenario' . $this->request->getPost('id') . '.png';
            $folder = 'writable/uploads/scenarios/';
            if (!is_dir($folder)) mkdir($folder);
            file_put_contents($dest_folder, $unencodedData);
            $urlPrintPlanogram = $dest_folder;
        }
        $scenario = array(
            'id' => $this->request->getPost('id'),
            'shelves' => $this->request->getPost('shelves'),
            'name' => $this->request->getPost('name'),
            'urlprintPlanogram' => $urlPrintPlanogram
        );
        $this->adminModel->update_planogram($scenario);
        $this->session->getFlashdata('success_msg', 'Planograma atualizado com sucesso!');
        $logs = array(
            'action' => 'Atualizou um planograma.',
            'type' => 'Sucesso',
            'message' => 'Planograma atualizado com sucesso!',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        $notification = array(
            'content' => $this->session->get('name') . ' editou o cenário ' . $this->request->getPost('name'),
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
        return redirect()->to('/edit_planogram' . '/' . $scenario['id']);
    }

    //liberar planograma para campo
    public function libera_planograma()
    {
        $id_planogram = json_decode(json_encode($this->request->getPost('id_planogram')), true);
        $id_company = json_decode(json_encode($this->request->getPost('id_company')), true);
        $get_plan = $this->adminModel->get_planogram_by_id($id_planogram);
        $get_positions = $this->adminModel->get_position_by_scenario($id_planogram);
        // echo "<pre>"; print_r($get_positions); echo "</pre>";die();

        if (is_array($get_positions)) {
            if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
            else $show_to = $this->session->get('id');

            //Duplicando planograma e atualizando status
            $scenario = array(
                'id_company' => $id_company,
                'id_user' => $show_to,
                'name' => $get_plan[0]['name'],
                'shelves' => $get_plan[0]['shelves'],
                'status' => 'Em campo',
                'planogram' => $id_planogram
            );
            $id_new_scenario = $this->adminModel->insert_planogram($scenario);

            //pegando as posições do planograma e insertindo para o cenario em campo
            foreach ($get_positions as $position) {
                $position_array = array(
                    'id_scenario' => $id_new_scenario,
                    'shelf' => $position['shelf'],
                    'column' => $position['column'],
                    'position' => $position['position'],
                    'id_product' => $position['id_product'],
                    'views' => $position['views'],
                    'qty' => $position['qty'],
                    'height' => $position['height'],
                    'width' => $position['width'],
                    'margin' => $position['margin']


                );
                $this->adminModel->insert_position($position_array);
            }
            echo $id_new_scenario;
            $logs = array(
                'action' => 'Liberou planograma para campo.',
                'type' => 'Sucesso',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);

            $notification = array(
                'content' => $this->session->get('name') . ' liberou  ' . $get_plan[0]['name'] . ' para campo.',
                'id_user' => $this->session->get('id'),
                'show_to' => $show_to
            );
            $this->adminModel->insert_notification($notification);
        } else {
            echo 'empty';
            $logs = array(
                'action' => 'Tentativa de liberar planograma vazio para campo.',
                'message' => 'Não é possível liberar para campo um planograma vazio!',
                'type' => 'Erro',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
        }
    }

    //Deleta o planograma e todas as positions relacionada a ele
    public function delete_planogram($id_planograma)
    {
        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $id_scenario = $id_planograma;
        $scenario = $this->adminModel->get_planogram_by_id($id_scenario);

        $notification = array(
            'content' => $this->session->get('name') . ' deletou ' . $scenario[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
        $this->adminModel->delete_planogram($id_scenario);
        $this->adminModel->remove_position_by_scenario($id_scenario);
        $logs = array(
            'action' => 'Deletou um cenário.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        return redirect()->to('/all_planograms');
    }

    // Share de gondola
    public function share()
    {

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = '';
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }
        $total_width = 0;
        $products = $this->adminModel->get_all_products();
        $data['products'] = $products;
        $id_planogram = $_GET['planogram'];
        $data['planograma'] = $this->adminModel->get_planogram_by_id($id_planogram);
        $data['columns_qtd'] = $this->adminModel->get_planogram_columns_quantity($id_planogram);
        $data['shelves_qtd'] = $this->adminModel->get_planogram_shelves_quantity($id_planogram);
        $all_positions = $this->adminModel->get_position_by_scenario($id_planogram);
        foreach ($all_positions as $position) {
            $data['columns'][$position["column"]][$position["shelf"]][] = $position;

            if (isset($widths[$position["id_product"]]))
                $widths[$position["id_product"]] = $widths[$position["id_product"]] + floatval($position["width"]);
            else
                $widths[$position["id_product"]] = floatval($position["width"]);

            $total_width += floatval($position["width"]);
        }
        $data['widths'] = $widths;
        $data['total_width'] = $total_width;
        // echo json_encode($widths);
        //Formatação data
        $data['hoje'] = new Time('now', 'America/Sao_Paulo', 'pt_BR');
        $data['formatter'] = $data['hoje'];

        // echo "<pre>";var_dump($data);die();
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/planogram/share.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function export_share()
    {
        $products = $this->input->get('data');
        $products = json_decode($products, true);

        $rest = $products['total_width'] % 100000;
        $m  = floor($rest / 100);
        $cm = $rest % 100;
        $largura_total = $m . 'm ' . $cm . 'cm';

        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);

        $table_columns = array(
            'Nome',
            'Marca',
            'Total de Frentes',
            'Share Total',
            'Gondola',
            'Posição',
            'Share por Gôndola'
        );

        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 1, 'Planograma:');
        $object->getActiveSheet()->setCellValueByColumnAndRow(6, 1, $products['planograma']);


        $object->getActiveSheet()->setCellValueByColumnAndRow(0, 2, 'Largura Total:');
        $object->getActiveSheet()->setCellValueByColumnAndRow(6, 2, $largura_total);

        $column = 0;
        $excel_row = 5;
        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 4, $field);
            $column++;
        }

        if ($products) {
            foreach ($products['facing'] as $product) {
                $porcentagem = number_format(($product['percent'] / $products['total_width']), 2, ",", ".") . '%';

                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $product['nome']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $product['marca']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $product['total_frentes']);
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $porcentagem);

                foreach ($product['position'] as $position) {
                    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $position['shelf']);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $position['number']);
                    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $position['percent'] . '%');
                    $excel_row++;
                }
            }
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ShareDeGondola.xlsx"');
        return $object_writer->save('php://output');
    }

    public function export_fixation_time()
    {
        $products = $this->request->getPost('data');

        $products = json_decode($products, true);
        $object = new PHPExcel();
        $object->setActiveSheetIndex(0);


        $table_columns = array(
            'Nome Do Produto',
            'Prateleira',
            'Posição',
            'Tempo De Fixação',
        );

        $object->getActiveSheet()->setCellValueByColumnAndRow(2, 1, 'Tempo De Fixação');


        $column = 0;
        $excel_row = 4;

        foreach ($table_columns as $field) {
            $object->getActiveSheet()->setCellValueByColumnAndRow($column, 3, $field);
            $column++;
        }


        if ($products) {
            foreach ($products as $product) {
                $name =  $product[0];
                $position = $product[1];
                $shelf =  $product[2];
                $time = $product[3];
                $object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, mb_strtoupper($name, "UTF-8"));
                $object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, mb_strtoupper($position, "UTF-8"));
                $object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, mb_strtoupper($shelf, "UTF-8"));
                $object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, mb_strtoupper($time, "UTF-8"));
                $excel_row++;
            }
        }

        $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="fixation_time.xlsx"');
        ob_start();
        $object_writer->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();
        $response =  array(
            'op' => 'ok',
            'file' => "data:application/vnd.ms-excel;base64," . base64_encode($xlsData)
        );

        echo $xlsData;
        exit();
    }

    public function all_scenarios()
    {
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'all_scenarios';

        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['scenarios'] = $this->adminModel->get_all_scenarios();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
            $data['companies'] = $this->adminModel->get_all_company();
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['scenarios'] = $this->adminModel->get_scenario_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
            $data['companies'] = $this->adminModel->get_company_by_user($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['scenarios'] = $this->adminModel->get_scenario_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
            $data['companies'] = $this->adminModel->get_company_by_user($id_user);
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/scenario/all.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function view_scenario($id)
    {

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['available_company'] = $this->adminModel->get_all_available_company();
            $data['companies'] = $this->adminModel->get_all_company();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['available_company'] = $this->adminModel->get_available_company_by($created_by);
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['available_company'] = $this->adminModel->get_available_company_by($id_user);
            $data['companies'] = $this->adminModel->get_company_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        $id_scenario = $id;
        $data['cenario'] = $this->adminModel->get_scenario_by_id($id_scenario);
        $data['columns_qtd'] = $this->adminModel->get_planogram_columns_quantity($id_scenario);
        $data['shelves_qtd'] = $this->adminModel->get_planogram_shelves_quantity($id_scenario);
        $all_positions = $this->adminModel->get_position_by_scenario($id_scenario);
        foreach ($all_positions as $position) {
            $data['columns'][$position["column"]][$position["shelf"]][] = $position;
        }
        $data["all_positions"] = $all_positions;
        // echo "<pre>"; print_r($data['columns']); echo "</pre>";die();
        $data['company'] = $this->adminModel->get_company_by_id($data['cenario'][0]['id_company']);
        $data['planogram'] = $this->adminModel->get_planogram_by_id($data['cenario'][0]['planogram']);
        $data['scenario_file'] = $this->adminModel->get_scenario_file('scenario' . $id_scenario . '.png');

        $get_eye_tracking = $this->adminModel->get_eye_tracking_by($id_scenario);

        //Formatação data

        $data['hoje'] = new Time('now', 'America/Sao_Paulo', 'pt_BR');
        $data['formatter'] = $data['hoje'];
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/scenario/view_scenario.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function export_scenario()
    {
        $logs = array(
            'action' => 'Exportou um cenário.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
    }

    public function adicionarPosicao()
    {
        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $products = $this->adminModel->get_all_products();
        $scenario = $this->adminModel->get_planogram_by_id($this->request->getPost('id_scenario'));
        $position = array(
            'id_scenario' => $this->request->getPost('id_scenario'),
            'column' => $this->request->getPost('column'),
            'shelf' => $this->request->getPost('shelf'),
            'position' => $this->request->getPost('position'),
            'id_product' => $this->request->getPost('id_product'),
            'views' => $this->request->getPost('views') != null ? $this->request->getPost('views') : 1,
            'qty' => $this->request->getPost('qty') != null ? $this->request->getPost('qty') : 1,
            'height' => $this->request->getPost('height'),
            'width' => $this->request->getPost('width'),
        );
        $this->adminModel->insert_position($position);

        $logs = array(
            'action' => 'Adicionou um produto na prateleira.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        $product1 = $this->adminModel->get_product_by_id($position['id_product']);

        $notification = array(
            'content' => $this->session->get('name') . ' adicionou o produto ' . $product1[0]['name'] . ' em ' . $scenario[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);

        $image = "  <li class='produto_sortido' data-id='' data-id_product='" . $position['id_product'] . "' data-id_scenario='" . $position['id_scenario'] . "' data-shelf='" . $position['shelf'] . "' data-id_position='" . $position['position'] . "' data-views='" . $position['views'] . "'>";
        $image .= "     <div class='images'>";
        for ($i = 1; $i <= $this->request->getPost('views'); $i++) {
            $image .= "<img style='margin-left: -3px;height:150px' src='" . base_url($product1[0]['image']) . "' />";
        }
        $image .= "     </div>";
        $image .= "                <i data-toggle='modal' data-target='#edit-modal-" . $position['shelf'] . "-" . $position['position'] . "' class='fas fa-pencil-alt edit text-white'></i>";
        $image .= "                <i id='remove-" . $position['shelf'] . "-" . $position['position'] . "' class='fas fa-times close text-white'></i>";
        $image .= "                 <div id='edit-modal-" . $position['shelf'] . "-" . $position['position'] . "' class='modal fade' role='dialog'>";
        $image .= "                  <div class='modal-dialog'>";
        $image .= "                        <!-- Modal content-->";
        $image .= "                        <div class='modal-content'>";
        $image .= "                          <div class='modal-header'>";
        $image .= "                            <h4 class='modal-title'>Editar Produto</h4>";
        $image .= "                            <button type='button' class='close' data-dismiss='modal'>&times;</button>";
        $image .= "                          </div>";
        $image .= "                          <div class='modal-body'>";
        $image .= "                                <div class='row'>";
        $image .= "                                    <div class='col-lg-12'>";
        $image .= "                                      <div class='form-group'>";
        $image .= "                                        <label class='form-control-label' for='shelves'>Produto</label>";
        $image .= "                                        <select class='form-control form-control-alternative' name='id_product'>";
        foreach ($products as $product) {
            $image .= "                                                <option value='" . $product['id'] . "'>" . $product['name'] . "</option>";
        }
        $image .= "                                        </select>";
        $image .= "                                      </div>";
        $image .= "                                    </div>";
        $image .= "                                    <div class='col-lg-12'>";
        $image .= "                                      <div class='form-group'>";
        $image .= "                                        <label class='form-control-label' for='name'>Quantas frentes deste produto serão exibidas?</label>";
        $image .= "                                        <input type='text' name='views' class='form-control form-control-alternative' placeholder='Quantas frentes deste produto serão exibidas?' value='' required>";
        $image .= "                                      </div>";
        $image .= "                                    </div>";
        $image .= "                                </div>";
        $image .= "                          </div>";
        $image .= "                          <input type='hidden' name='id_scenario' value='" . $position['id_scenario'] . "'>";
        $image .= "                          <input type='hidden' name='shelf' value='" . $position['shelf'] . "'>";
        $image .= "                          <input type='hidden' name='position' value='" . $position['position'] . "'>";
        $image .= "                          <div class='modal-footer text-center'>";
        $image .= "                            <button class='btn btn-success' data-dismiss='modal'>Salvar</button>";
        $image .= "                          </div>";
        $image .= "                        </div>";
        $image .= "                  </div>";
        $image .= "                </div>";
        $image .= " </li>";
        return $image;
    }

    public function edit_position()
    {

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $products = $this->adminModel->get_all_products();
        $produto = $this->adminModel->get_product_by_id($this->request->getPost('id_product'));
        $scenario = $this->adminModel->get_planogram_by_id($this->request->getPost('id_scenario'));

        $id = $this->request->getPost('id');
        $position = array(
            'id_scenario' => $this->request->getPost('id_scenario'),
            'shelf' => $this->request->getPost('shelf'),
            'id_product' => $this->request->getPost('id_product'),
            'views' => $this->request->getPost('views') != null ? $this->request->getPost('views') : 1,
            'qty' => $this->request->getPost('qty') != null ? $this->request->getPost('qty') : 1,
            'alert_count' => $this->request->getPost('alert_count') != null ? $this->request->getPost('alert_count') : 1
        );
        $this->adminModel->update_position($position, $id);

        $logs = array(
            'action' => 'Editou a posição de um produto ou prateleira.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        $notification = array(
            'content' => $this->session->get('name') . ' editou o produto ' . $produto[0]['name'] . ' na prateleira de ' . $scenario[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
    }

    public function remove_position()
    {

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');
        $scenario = $this->adminModel->get_planogram_by_id($this->request->getPost('id_scenario'));

        $id = $this->request->getPost('id');
        $position = array(
            'id_scenario' => $this->request->getPost('id_scenario'),
            'shelf' => $this->request->getPost('shelf')
        );
        $this->adminModel->remove_position($position, $id);

        $logs = array(
            'action' => 'Removeu um produto da prateleira',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        $notification = array(
            'content' => $this->session->get('name') . ' removeu um produto da prateleira de ' . $scenario[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
    }

    public function remove_shelf()
    {

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');
        $scenario = $this->adminModel->get_planogram_by_id($this->request->getPost('id_scenario'));

        $position = array(
            'id_scenario' => $this->request->getPost('id_scenario'),
            'shelf' => $this->request->getPost('shelf')
        );
        $this->adminModel->remove_shelf($position);

        $logs = array(
            'action' => 'Removeu uma prateleira',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        $notification = array(
            'content' => $this->session->get('name') . ' removeu uma prateleira de ' . $scenario[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
    }

    public function remove_column()
    {

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');
        $scenario = $this->adminModel->get_planogram_by_id($this->request->getPost('id_scenario'));

        $position = array(
            'id_scenario' => $this->request->getPost('id_scenario'),
            'column' => $this->request->getPost('column')
        );
        $this->adminModel->remove_column($position);

        $logs = array(
            'action' => 'Removeu uma coluna',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        $notification = array(
            'content' => $this->session->get('name') . ' removeu uma coluna de ' . $scenario[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
    }

    function copy_shelf()
    {
        $copy_shelves = json_decode(json_encode($this->request->getPost('copy_shelf')), true);

        foreach ($copy_shelves as $copy_shelf) {
            $id_scenario = $copy_shelf['id_scenario'];
            $shelf = intval($copy_shelf['shelf']);
            $column = intval($copy_shelf['column']);

            $get_scenario = $this->adminModel->get_planogram_by_id($id_scenario);
            $qtd_shelves = $this->adminModel->get_planogram_shelves_quantity($id_scenario, $column);
            $new_shelf = $qtd_shelves + 1;

            //Pegando as informações da prateleira original
            $query = array(
                'id_scenario' => $id_scenario,
                'column' => 'shelf',
                'value' => $shelf
            );
            $positions = $this->adminModel->get_position_by($query);

            //Só duplica a prateleira se ela não estiver vazia
            if ($positions != 0) {
                //Criando mais uma prateleira ao cenário (última prateleira)
                $scenario = array(
                    'id' => $id_scenario,
                    'shelves' => $new_shelf
                );
                $this->adminModel->update_planogram($scenario);

                //Duplicando as posições da prateleira original na nova prateleira
                foreach ($positions as $new_position) {
                    $position_array = array(
                        'id_scenario' => $new_position['id_scenario'],
                        'shelf' => $new_shelf,
                        'column' => $new_position['column'],
                        'position' => $new_position['position'],
                        'id_product' => $new_position['id_product'],
                        'views' => $new_position['views'],
                        'qty' => $new_position['qty'],
                        'height' => $new_position['height'],
                        'width' => $new_position['width']
                    );
                    $this->adminModel->insert_position($position_array);
                }
                $logs = array(
                    'action' => 'Copiou uma prateleira.',
                    'type' => 'Sucesso',
                    'id_user' => $this->session->get('id'),
                    'ip' => $this->request->getIPAddress()
                );
                $this->adminModel->insert_logs($logs);

                if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
                else $show_to = $this->session->get('id');

                $notification = array(
                    'content' => $this->session->get('name') . ' copiou a prateleira ' . $shelf . ' de ' . $get_scenario[0]['name'],
                    'id_user' => $this->session->get('id'),
                    'show_to' => $show_to
                );
                $this->adminModel->insert_notification($notification);
            } else {
                echo 'Não é possível duplicar uma prateleira vazia!';
                $logs = array(
                    'action' => 'Erro ao copiar uma prateleira vazia.',
                    'message' => 'Não é possível duplicar uma prateleira vazia!',
                    'type' => 'Erro',
                    'id_user' => $this->session->get('id'),
                    'ip' => $this->request->getIPAddress()
                );
                $this->adminModel->insert_logs($logs);
            }
        }
    }

    function copy_column()
    {
        $copy_shelves = json_decode(json_encode($this->request->getPost('copy_column')), true);

        foreach ($copy_shelves as $copy_column) {
            $id_scenario = $copy_column['id_scenario'];
            $column = intval($copy_column['column']);

            $get_scenario = $this->adminModel->get_planogram_by_id($id_scenario);
            $qtd_columns = $this->adminModel->get_planogram_columns_quantity($id_scenario);

            //Pegando as informações da prateleira original
            $query = array(
                'id_scenario' => $id_scenario,
                'column' => 'column',
                'value' => $column
            );
            $positions = $this->adminModel->get_position_by($query);

            //Só duplica a prateleira se ela não estiver vazia
            if ($positions != 0) {
                //Criando mais uma prateleira ao cenário (última prateleira)

                //Duplicando as posições da prateleira original na nova prateleira
                foreach ($positions as $new_position) {
                    $position_array = array(
                        'id_scenario' => $new_position['id_scenario'],
                        'shelf' => $new_position['shelf'],
                        'column' => $qtd_columns + 1,
                        'position' => $new_position['position'],
                        'id_product' => $new_position['id_product'],
                        'views' => $new_position['views'],
                        'qty' => $new_position['qty'],
                        'height' => $new_position['height'],
                        'width' => $new_position['width']
                    );
                    $this->adminModel->insert_position($position_array);
                }
                $logs = array(
                    'action' => 'Copiou uma coluna da prateleira.',
                    'type' => 'Sucesso',
                    'id_user' => $this->session->get('id'),
                    'ip' => $this->request->getIPAddress()
                );
                $this->adminModel->insert_logs($logs);

                if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
                else $show_to = $this->session->get('id');

                $notification = array(
                    'content' => $this->session->get('name') . ' copiou a prateleira ' . $column . ' de ' . $get_scenario[0]['name'],
                    'id_user' => $this->session->get('id'),
                    'show_to' => $show_to
                );
                $this->adminModel->insert_notification($notification);
            } else {
                echo 'Não é possível duplicar uma prateleira vazia!';
                $logs = array(
                    'action' => 'Erro ao copiar uma prateleira vazia.',
                    'message' => 'Não é possível duplicar uma prateleira vazia!',
                    'type' => 'Erro',
                    'id_user' => $this->session->get('id'),
                    'ip' => $this->request->getIPAddress()
                );
                $this->adminModel->insert_logs($logs);
            }
        }
    }

    public function painel_financeiro()
    {

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'painel_financeiro';

        if ($id_user == 1) {
            $data['companies'] = $this->adminModel->get_all_available_company();
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['companies'] = $this->adminModel->get_available_company_by($id_user);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        $body = array(
            'client_id' => $id_user
        );
        $data['paymentset'] = $this->adminModel->payment_painel($body);


        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/financeiro/painel_financeiro.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }


    public function panel_interviewees()
    {


        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);

        if ($id_user == 1) {
            $data['companies'] = $this->adminModel->get_all_available_company();
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) { //se foi criado por um usuário tem acesso a todos os cenários criados por ele
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['companies'] = $this->adminModel->get_available_company_by($id_user);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        if (isset($_GET['pagina'])) $pc = $_GET['pagina'];
        else $pc = 1;
        if (isset($_GET['pesquisa'])) $pesquisa = $_GET['pesquisa'];
        else $pesquisa = 0;
        if (isset($_GET['orderBy'])) $orderBy = $_GET['orderBy'];
        else $orderBy = 0;
        if (isset($_GET['order'])) $order = $_GET['order'];
        else $order = 0;

        $total_reg = 10;
        $offset = $pc - 1;
        $offset *= $total_reg;

        $data['total_reg'] = $total_reg;

        function replaceName($name)
        {
            $partes = explode(' ', $name);
            $primeiroNome = array_shift($partes);
            $ultimoNome = array_pop($partes);
            if (!$ultimoNome) {
                $ultimoNome = '******';
            }
            $nomeCompleto = $primeiroNome . ' ' . preg_replace('/([a-z])/', '*', $ultimoNome);
            return $nomeCompleto;
        }

        function replaceCpf($cpf)
        {
            $somenteNumeros = preg_replace('/[^0-9]/', '', $cpf);
            $ultimos = substr($somenteNumeros, 5);
            $somenteNumeros = str_replace($ultimos, "******", $somenteNumeros);
            return $somenteNumeros;
        }

        function replaceNumber($Numero)
        {
            $somenteNumeros = preg_replace('/[^0-9]/', '', $Numero);
            $ultimos = substr($somenteNumeros, 4);
            $somenteNumeros = str_replace($ultimos, "******", $somenteNumeros);
            return $somenteNumeros;
        }

        function replaceCep($Numero)
        {
            $somenteNumeros = preg_replace('/[^0-9]/', '', $Numero);
            $ultimos = substr($somenteNumeros, 4);
            $somenteNumeros = str_replace($ultimos, "*-***", $somenteNumeros);
            return $somenteNumeros;
        }

        if (!$created_by) {
            $products = $this->adminModel->get_interviewees_by_limit($id_user, $pesquisa, $total_reg, $offset, $order, $orderBy);
            $data['total'] = $this->adminModel->get_interviewees_by_limit($id_user, $pesquisa, 0, 0, 0, 0);
        } else { // se tiver sido criado por um user verá os mesmos produtos que o criador
            $products = $this->adminModel->get_interviewees_by_limit($created_by, $pesquisa, $total_reg, $offset, $order, $orderBy);
            $data['total'] = $this->adminModel->get_interviewees_by_limit($created_by, $pesquisa, 0, 0, 0, 0);
        }

        $entrevistados = array();
        for ($i = 0; $i < count($products); $i++) {
            $products[$i]['name'] = replaceName($products[$i]['name']);
            $products[$i]['cpf'] = replaceCpf($products[$i]['cpf']);
            $products[$i]['telefone'] = replaceNumber($products[$i]['telefone']);
            $products[$i]['cep'] = replaceCep($products[$i]['cep']);
            $products[$i]['logradouro'] = replaceName($products[$i]['logradouro']);
            $products[$i]['bairro'] = replaceName($products[$i]['bairro']);
            $products[$i]['categorias'] =  str_pad('', strlen($products[$i]['categorias']), "*");
            $products[$i]['filhos'] = str_pad('', strlen($products[$i]['filhos']), "*");
            $products[$i]['qtd_filhos'] = str_pad('', strlen($products[$i]['qtd_filhos']), "*");
            $products[$i]['age_filhos'] = str_pad('', strlen($products[$i]['age_filhos']), "*");
            $products[$i]['filhos'] = str_pad('', strlen($products[$i]['filhos']), "*");
            $products[$i]['compras'] = str_pad('', strlen($products[$i]['compras']), "*");
            $products[$i]['valor_compra'] = str_pad('', strlen($products[$i]['valor_compra']), "*");
            $products[$i]['cupom'] = str_pad('', strlen($products[$i]['cupom']), "*");
            $products[$i]['idade'] = $products[$i]['idade'];
            $products[$i]['profissao'] = str_pad('', strlen($products[$i]['profissao']), "*");
            $products[$i]['reposicao'] = str_pad('', strlen($products[$i]['reposicao']), "*");
            $products[$i]['fluxo_online'] = str_pad('', strlen($products[$i]['fluxo_online']), "*");
            $products[$i]['entrevista'] = str_pad('', strlen($products[$i]['entrevista']), "*");
            $products[$i]['notebook'] = str_pad('', strlen($products[$i]['notebook']), "*");
            $products[$i]['webcam'] = str_pad('', strlen($products[$i]['webcam']), "*");
            $products[$i]['outro_dispositivo'] = str_pad('', strlen($products[$i]['outro_dispositivo']), "*");
            $products[$i]['interviewd_id'] = str_pad('', strlen($products[$i]['interviewd_id']), "*");
            $products[$i]['bairro'] = replaceName($products[$i]['bairro']);
            $products[$i]['bairro'] = replaceName($products[$i]['bairro']);
            $products[$i]['renda'] =   str_replace($products[$i]['renda'], "*****,00", $products[$i]['renda']);
            $products[$i]['email'] = substr_replace($products[$i]['email'], '*****', 1, strpos($products[$i]['email'], '@') - 2);
            $entrevistados[$i] = $products[$i];
        }


        $data['persons'] = $entrevistados;
        echo view("commom/template/html-header.php");
        if (!isset($_GET['pagina'])) {
            echo view("admin/template/splash.php");
        }
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/panel_interviewees/panel_interviewees.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }


    public function get_interview_person()
    {

        $id = $_GET['id'];

        function replaceNamePerson($name)
        {
            $partes = explode(' ', $name);
            $primeiroNome = array_shift($partes);
            $ultimoNome = array_pop($partes);
            if (!$ultimoNome) {
                $ultimoNome = '******';
            }
            $nomeCompleto = $primeiroNome . ' ' . preg_replace('/([a-z])/', '*', $ultimoNome);
            return $nomeCompleto;
        }

        function replaceCpfPerson($cpf)
        {
            $somenteNumeros = preg_replace('/[^0-9]/', '', $cpf);
            $ultimos = substr($somenteNumeros, 5);
            $somenteNumeros = str_replace($ultimos, "******", $somenteNumeros);
            return $somenteNumeros;
        }

        function replaceNumberPerson($Numero)
        {
            $somenteNumeros = preg_replace('/[^0-9]/', '', $Numero);
            $ultimos = substr($somenteNumeros, 4);
            $somenteNumeros = str_replace($ultimos, "******", $somenteNumeros);
            return $somenteNumeros;
        }

        function replaceCepPerson($Numero)
        {
            $somenteNumeros = preg_replace('/[^0-9]/', '', $Numero);
            $ultimos = substr($somenteNumeros, 4);
            $somenteNumeros = str_replace($ultimos, "*-***", $somenteNumeros);
            return $somenteNumeros;
        }

        $products = $this->adminModel->get_interview_person($id);
        $person['name'] = replaceNamePerson($products[0]['name']);
        $person['cpf'] = replaceCpfPerson($products[0]['cpf']);
        $person['telefone'] = replaceNumberPerson($products[0]['telefone']);
        $person['cep'] = replaceCepPerson($products[0]['cep']);
        $person['logradouro'] = replaceNamePerson($products[0]['logradouro']);
        $person['bairro'] = replaceNamePerson($products[0]['bairro']);
        $person['categorias'] =  str_pad('', strlen($products[0]['categorias']), "*");
        $person['filhos'] = str_pad('', strlen($products[0]['filhos']), "*");
        $person['qtd_filhos'] = str_pad('', strlen($products[0]['qtd_filhos']), "*");
        $person['age_filhos'] = str_pad('', strlen($products[0]['age_filhos']), "*");
        $person['filhos'] = str_pad('', strlen($products[0]['filhos']), "*");
        $person['compras'] = str_pad('', strlen($products[0]['compras']), "*");
        $person['idade'] = $products[0]['idade'];
        $person['valor_compra'] = str_pad('', strlen($products[0]['valor_compra']), "*");
        $person['cupom'] = str_pad('', strlen($products[0]['cupom']), "*");
        $person['profissao'] = str_pad('', strlen($products[0]['profissao']), "*");
        $person['reposicao'] = str_pad('', strlen($products[0]['reposicao']), "*");
        $person['fluxo_online'] = str_pad('', strlen($products[0]['fluxo_online']), "*");
        $person['entrevista'] = str_pad('', strlen($products[0]['entrevista']), "*");
        $person['notebook'] = str_pad('', strlen($products[0]['notebook']), "*");
        $person['webcam'] = str_pad('', strlen($products[0]['webcam']), "*");
        $person['outro_dispositivo'] = str_pad('', strlen($products[0]['outro_dispositivo']), "*");
        $person['interviewd_id'] = str_pad('', strlen($products[0]['interviewd_id']), "*");
        $person['bairro'] = replaceNamePerson($products[0]['bairro']);
        $person['bairro'] = replaceNamePerson($products[0]['bairro']);
        $person['renda'] =   str_replace($products[0]['renda'], "*****,00", $products[0]['renda']);
        $person['email'] = substr_replace($products[0]['email'], '*****', 1, strpos($products[0]['email'], '@') - 2);
        $entrevistados = $person;
        $data = $entrevistados;
        return json_encode($data);
    }

    public function add_blank_position()
    {
        $position = array(
            'id_scenario' => $this->request->getPost('id_scenario'),
            'column' => $this->request->getPost('column'),
            'shelf' => $this->request->getPost('shelf'),
            'position' => $this->request->getPost('position'),
            'views' => 1,
            'qty' => 1
        );
        $this->adminModel->insert_position($position);
    }

    function edit_dimension()
    {
        $values = json_decode(json_encode($this->request->getPost('values')), true);
        $id_position = $values['id_position'];

        $data = array(
            'width' => $values['width'],
            'height' => $values['height'],
            'margin' => $values['margin']
        );
        $this->adminModel->update_position($data, $id_position);

        $logs = array(
            'action' => 'Redimensionou a imagem de um produto.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $notification = array(
            'content' => $this->session->get('name') . ' redimensionou a imagem de um produto no planograma ' . $values['cenario'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
    }

    public function select_orders()
    {


        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);

        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        $data['all_products'] = $this->adminModel->get_all_products();
        $data['order_data'] = $this->adminModel->get_all_orders();
        $data['orders_data'] = $this->adminModel->get_all_orders();

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/select_order.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function all_orders()
    {

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'all_orders';
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $companies = $this->adminModel->get_all_company();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['companies'] = $this->adminModel->get_available_company_by($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
            $companies = $this->adminModel->get_company_by_user($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
            $companies = $this->adminModel->get_company_by_user($id_user);
        }
        $data['estudos'] = array();
        if ($companies) {
            foreach ($companies as $company) {
                $company['planograms'] = array();
                $company['amostra'] = array();
                $plans = $this->adminModel->count_scenario_by_company($company['id']);
                array_push($company['planograms'], $plans);
                if ($this->adminModel->get_order_by_company($company['id']) != 0) {
                    $total_pesquisa = count($this->adminModel->get_order_by_company($company['id']));
                } else {
                    $total_pesquisa = 0;
                }

                $max_pesquisa = intval($company['qtd_pesquisa']);
                $amostra = ceil(($total_pesquisa / $max_pesquisa) * 100);

                array_push($company['amostra'], $amostra);
                array_push($data['estudos'], $company);
            }
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/report/all.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function order($idcompany)
    {
        $id_company = $idcompany;
        $data["id_company"] = $idcompany;
        $company = $this->adminModel->get_company_by_id($id_company);
        $data['name_company'] = $company[0]['name'];

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);

        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
            $data['scenarios'] = $this->adminModel->get_scenarios_by_company($idcompany);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
            $data['scenarios'] = $this->adminModel->get_scenarios_by_company($idcompany, $created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
            $data['scenarios'] = $this->adminModel->get_scenarios_by_company($idcompany, $id_user);
        }

        $pc = !empty($this->request->getGet('pagina')) ? $this->request->getGet('pagina') : 1;
        $limit = 10;
        $offset = $pc - 1;
        $offset *= $limit;
        $data['limit'] = $limit;

        $data['order_data'] = $this->adminModel->get_order_by_company_limit($id_company, $limit, $offset);
        $data['total'] = $this->adminModel->get_order_by_company_limit($id_company);

        if (!empty($data['order_data'])) {
            foreach ($data['order_data'] as $order_data) {
                $order_detail = '';
                $removed_cart = '';
                $removed_checkout = '';
                $sequence = '';
                $cart_time = '';
                $cart_datas[$order_data['id']] = $this->adminModel->get_cart_by_id($order_data['id_cart']);
                if (is_array($cart_datas[$order_data['id']])) {
                    foreach ($cart_datas[$order_data['id']] as $cart_data) {
                        $product_data[$cart_data['id']] = $this->adminModel->get_product_by_ean($cart_data['product_ean']);
                        if (is_array($product_data[$cart_data['id']])) {
                            foreach ($product_data[$cart_data['id']] as $product_data) {
                                if ($cart_data['bought'] > 0) {
                                    $order_detail .= $product_data['name'] . ' x ' . $cart_data['bought'] . ', ';
                                }
                                if ($cart_data['removed_cart'] > 0) {
                                    $removed_cart .= $product_data['name'] . ' x ' . $cart_data['removed_cart'] . ', ';
                                }
                                if ($cart_data['removed_checkout'] > 0) {
                                    $removed_checkout .= $product_data['name'] . ' x ' . $cart_data['removed_checkout'] . ', ';
                                }
                                $sequence .= $product_data['name'] . ', ';
                            }
                        }
                    }
                }

                $user_data = $this->adminModel->get_user_by_id($order_data['id_user']);
                if (isset($user_data['name'])) {
                    $clients = $user_data['name'];
                } else {
                    $clients = '';
                }

                $scenario_data[$order_data['id']] = $this->adminModel->get_planogram_by_id($order_data['id_scenario']);
                if (isset($scenario_data[$order_data['id']][0]))    $scenario = $scenario_data[$order_data['id']][0]['name'];
                else $scenario = '';

                $data['orders_list']['client'][$order_data['id']] = $clients;
                $data['orders_list']['scenario'][$order_data['id']] = $scenario;
                $data['orders_list']['order_detail'][$order_data['id']] = $order_detail;
                $data['orders_list']['removed_cart'][$order_data['id']] = $removed_cart;
                $data['orders_list']['removed_checkout'][$order_data['id']] = $removed_checkout;
                $data['orders_list']['sequence'][$order_data['id']] = $sequence;
                $data['orders_list']['total'][$order_data['id']] = $order_data['total'];
                $data['orders_list']['payment_method'][$order_data['id']] = $order_data['payment_method'];
                $data['orders_list']['time'][$order_data['id']] = !empty($cart_datas[$order_data['id']]) ? $cart_datas[$order_data['id']][0]['time'] : "00:00:00";
            }
        }
        //HeatMap
        foreach ($data['scenarios'] as $scenarios) {
            $all_positions = $this->adminModel->get_position_by_scenario($scenarios['id']);
            foreach ($all_positions as $position) {
                $data['positions'][$scenarios['id']][$position["column"]][$position["shelf"]][] = $position;
            }
        }
        //HeatMap
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/report/company_report.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }



    public function get_purchase($id_purchase)
    {
        $request = $this->adminModel->get_orderBy_id($id_purchase);
        $order_data = $request[0];
        if ($order_data) {
            $order_detail = '';
            $removed_cart = '';
            $removed_checkout = '';
            $sequence = '';
            $cart_time = '';
            $cart_datas[$order_data['id']] = $this->adminModel->get_cart_by_id($order_data['id_cart']);
            if (is_array($cart_datas[$order_data['id']])) {
                foreach ($cart_datas[$order_data['id']] as $cart_data) {
                    $product_data[$cart_data['id']] = $this->adminModel->get_product_by_ean($cart_data['product_ean']);
                    if (is_array($product_data[$cart_data['id']])) {
                        foreach ($product_data[$cart_data['id']] as $product_data) {
                            if ($cart_data['bought'] > 0) {
                                $order_detail .= $product_data['name'] . ' (' . $product_data['ean'] . ')' . ' x ' . $cart_data['bought'] . ', ';
                            }
                            if ($cart_data['removed_cart'] > 0) {
                                $removed_cart .= $product_data['name'] . '(' . $product_data['ean'] . ')' . ' x ' . $cart_data['removed_cart'] . ', ';
                            }
                            if ($cart_data['removed_checkout'] > 0) {
                                $removed_checkout .= $product_data['name'] . '(' . $product_data['ean'] . ')' . ' x ' . $cart_data['removed_checkout'] . ', ';
                            }
                            $sequence .= $product_data['name'] . '(' . $product_data['ean'] . ')' . ', ';
                        }
                    }
                }
            }
            $user_data[$order_data['id']] = $this->adminModel->get_user_by_id($order_data['id_user']);
            if (isset($user_data[$order_data['id']])) $client = $user_data[$order_data['id']]['name'];
            else $client = '';
            $scenarios = $this->adminModel->get_scenario_by_id($order_data['id_scenario']);
            if (isset($scenarios[0]['name'])) $name_scenario = $scenarios[0]['name'];
            else $name_scenario = '';
            if (isset($cart_datas[$order_data['id']][0]['time'])) $time = $cart_datas[$order_data['id']][0]['time'];
            else $time = '';
            if (isset($order_data['data'])) $data = date('d/m/Y', strtotime($order_data['data']));
            else $data = '';
            $time = $cart_datas[$order_data['id']][0]['time'];
            $purchase['client'] = $client;
            $purchase['name_scenario'] = $name_scenario;
            $purchase['order_detail'] = $order_detail;
            $purchase['removed_cart'] = $removed_cart;
            $purchase['removed_checkout'] = $removed_checkout;
            $purchase['sequence'] = $sequence;
            $purchase['time'] = $time;
            $purchase['order_total'] = $order_data['total'];
            $purchase['order_data'] = $order_data['payment_method'];
            $purchase['data'] = $data;

            return json_encode($purchase);
        }
    }



    public function export_carts()
    {
        $spreadsheet = new Spreadsheet();
        $Excel_writer = new Xlsx($spreadsheet);
        $sheet = $spreadsheet->getActiveSheet();

        $table_columns = array(
            "Comprador",
            "Cenário",
            "Comprados",
            "Removidos no Carrinho",
            "Removidos no Checkout",
            "Ordem de Interação",
            "Tempo de compra",
            "Preço total",
            "Forma de Pagamento",
            "Data"
        );
        $sheet->setCellValue('A1', $table_columns[0]);
        $sheet->setCellValue('B1', $table_columns[1]);
        $sheet->setCellValue('C1', $table_columns[2]);
        $sheet->setCellValue('D1', $table_columns[3]);
        $sheet->setCellValue('E1', $table_columns[4]);
        $sheet->setCellValue('F1', $table_columns[5]);
        $sheet->setCellValue('G1', $table_columns[6]);
        $sheet->setCellValue('H1', $table_columns[7]);
        $sheet->setCellValue('I1', $table_columns[8]);
        $sheet->setCellValue('J1', $table_columns[9]);

        $id_company = $_GET['id_company'];
        $company = $this->adminModel->get_company_by_id($id_company);
        $order_data = $this->adminModel->get_order_by_company($id_company);
        $excel_row = 2;
        if ($order_data) {
            foreach ($order_data as $order_data) {
                $order_detail = '';
                $removed_cart = '';
                $removed_checkout = '';
                $sequence = '';
                $cart_time = '';
                $cart_datas[$order_data['id']] = $this->adminModel->get_cart_by_id($order_data['id_cart']);
                if (is_array($cart_datas[$order_data['id']])) {
                    foreach ($cart_datas[$order_data['id']] as $cart_data) {
                        $product_data[$cart_data['id']] = $this->adminModel->get_product_by_ean($cart_data['product_ean']);
                        if (is_array($product_data[$cart_data['id']])) {
                            foreach ($product_data[$cart_data['id']] as $product_data) {
                                if ($cart_data['bought'] > 0) {
                                    $order_detail .= $product_data['name'] . ' (' . $product_data['ean'] . ')' . ' x ' . $cart_data['bought'] . ', ';
                                }
                                if ($cart_data['removed_cart'] > 0) {
                                    $removed_cart .= $product_data['name'] . '(' . $product_data['ean'] . ')' . ' x ' . $cart_data['removed_cart'] . ', ';
                                }
                                if ($cart_data['removed_checkout'] > 0) {
                                    $removed_checkout .= $product_data['name'] . '(' . $product_data['ean'] . ')' . ' x ' . $cart_data['removed_checkout'] . ', ';
                                }
                                $sequence .= $product_data['name'] . '(' . $product_data['ean'] . ')' . ', ';
                            }
                        }
                    }
                }

                $user_data[$order_data['id']] = $this->adminModel->get_user_by_id($order_data['id_user']);
                if (isset($user_data[$order_data['id']])) $client = $user_data[$order_data['id']]['name'];
                else $client = '';
                // $scenarios = $this->adminModel->get_planogram_by_id($order_data['id_scenario']);
                $scenarios = $this->adminModel->get_scenario_by_id($order_data['id_scenario']);
                if (isset($scenarios[0]['name'])) $name_scenario = $scenarios[0]['name'];
                else $name_scenario = '';

                if (isset($cart_datas[$order_data['id']][0]['time'])) $time = $cart_datas[$order_data['id']][0]['time'];
                else $time = '';

                if (isset($order_data['data'])) $data = date('d/m/Y', strtotime($order_data['data']));
                else $data = '';

                if (isset($cart_datas[$order_data['id']][0]['time'])) {
                    $time = $cart_datas[$order_data['id']][0]['time'];

                    $sheet->setCellValue("A" . $excel_row, $client);
                    $sheet->setCellValue("B" . $excel_row, $name_scenario);
                    $sheet->setCellValue("C" . $excel_row, $order_detail);
                    $sheet->setCellValue("D" . $excel_row, $removed_cart);
                    $sheet->setCellValue("E" . $excel_row, $removed_checkout);
                    $sheet->setCellValue("F" . $excel_row, $sequence);
                    $sheet->setCellValue("G" . $excel_row, $time);
                    $sheet->setCellValue("H" . $excel_row, $order_data['total']);
                    $sheet->setCellValue("I" . $excel_row, $order_data['payment_method']);
                    $sheet->setCellValue("J" . $excel_row, $data);
                    $excel_row++;
                } else $time = '';
            }
        }
        $logs = array(
            'action' => 'Exportou planilha de compras por estudo.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $notification = array(
            'content' => $this->session->get('name') . ' exportou uma planilha de compras de ' . $company[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="RelatorioDeCompras.xlsx"');
        $Excel_writer->save('php://output');
    }

    public function client_scenario()
    {
        $email = $this->session->get('email');
        $scenario = $this->request->getPost('id_cenario');
        $company = $this->request->getPost('id_company');
        $qtd_max = $this->request->getPost('qtd_max');
        $eye_tracking = $this->request->getPost('eye_tracking');

        //$this->session->set('id_company', $company);
        $logs = array(
            'action' => 'Logou em um cenário como cliente.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        return redirect()->to('/scenario?id_company=' . $company . '&user_uuid=' . $email . '&id_scenario=' . $scenario . '&qtd_max=' . $qtd_max . '&eye_tracking=' . $eye_tracking);
    }

    function orderUpdate()
    {
        $positions = json_decode(json_encode($this->request->getPost('positions')), true);
        foreach ($positions as $position) {
            $new = array(
                'position' => $position['posicao'],
                'column' => $position['column'],
                'shelf' => $position['shelf'],
            );
            $this->adminModel->update_draggable($new, $position['id']);
            $pos = $this->adminModel->get_position_by_id($position['id']);
        }
        $scenario = $this->adminModel->get_planogram_by_id($pos[0]['id_scenario']);
        $logs = array(
            'action' => 'Moveu a posição de um produto ou prateleira.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $notification = array(
            'content' => $this->session->get('name') . ' alterou posições em ' . $scenario[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);
    }

    function gallery()
    {
        $data = array();

        function mtimecmp($a, $b)
        {
            $mt_a = filemtime($a);
            $mt_b = filemtime($b);

            if ($mt_a == $mt_b)
                return 0;
            else if ($mt_a < $mt_b)
                return -1;
            else
                return 1;
        }
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['products'] = $this->adminModel->get_all_products();
        $data['route'] = 'gallery';

        //Filtrando as imagens por usuário
        //Apenas o id 1 (DKMA) pode ver todas as imagens

        if ($id_user == 1) {
            $data['imagens'] = $this->adminModel->get_all_files();
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['imagens'] = $this->adminModel->get_files_by($created_by);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['imagens'] = $this->adminModel->get_files_by($id_user);
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        $rowperpage = 40;


        $data['allCount'] = $this->adminModel->get_gallery_total();
        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/gallery/gallery.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function loading_gallery()
    {

        $row = $_POST['row'];
        $cont = $_POST['cont'];
        $max = $_POST['max'];
        $rowperpage = 60;
        $query = $this->adminModel->get_gallery_page($row, $rowperpage);


        $html = '';

        foreach ($query as $row) {
            $cont++;
            $max++;
            $html .= '<div class="col-xl-2 col-lg-2 imgori"><div class="child"><img class="imgProd img-fluid" data_contador="' . $cont . '" data-src="' . $row['src'] . '" src="' .  base_url($row['src']) . '" onclick="image(this)" data-bs-toggle="modal" data-bs-target="#modal_image"></div></div>';
        }
        $jsonAnswer = array('html' => $html, 'max' => $max);
        echo json_encode($jsonAnswer);
    }




    // selecting posts


    function dragDropUpload()
    {
        $id_user = $this->session->get('id');
        helper(['form', 'url']);
        $builder = \Config\Database::connect();
        $db = $builder->table('products');

        $input = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/png]',
            ]
        ]);

        if (!$input) {
            print_r('Choose a valid file');
        } else {
            if (!empty($_FILES)) {
                // File upload configuration 
                $img = $this->request->getFile('file');
                $nome = $img->getName();
                $nome_pasta = rand(10, 999999999);
                //$dest_folder1 = 'writable/uploads/produtos/' . $id_user .'/'.  $nome . '/';
                $dest_folder = 'writable/uploads/produtos/' . $id_user . '/' .  $nome_pasta . "/destaque/";
                if (!is_dir($dest_folder)) {
                    mkdir($dest_folder, 0777, true);
                }
                $image = \Config\Services::image();
                $image->withFile($img);
                $image->resize(100, 100, true, 'height');
                $img->move(ROOTPATH . 'writable/uploads/produtos/' . $id_user . '/' . $nome_pasta . '/destaque');
                $c = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿŔŕ?';
                $d = 'AAACAAAcEEEEIIIIDNOOOOOOUUUUYBSaaacaaaceeeeiiiidnoooooouuuuyybyRr-';
                $nome = strtr($nome, mb_convert_encoding($c, 'ISO-8859-1', 'UTF-8'), $d);
                $nome = str_replace("cI", "c", $nome);
                $nome = str_replace("cc", "c", $nome);
                $nome = str_replace(" ", "_", $nome);
                $nome = preg_replace("/[^-,;^!<>@&\/\sA-Za-z0-9_.-]/", '', $nome);
                list($width, $height) = getimagesize('writable/uploads/produtos/' . $id_user . '/' . $nome_pasta . '/destaque' . '/' . $nome);
                $uploadData['file_name'] = $nome;
                $uploadData['uploaded_on'] = date("Y-m-d H:i:s");
                $data = [
                    'uploaded_on' => $uploadData['uploaded_on'],
                    'file_name' => $uploadData['file_name'],
                    'src' => $dest_folder . $uploadData['file_name'],
                    'width' => $width,
                    'height' => $height,
                    'id_user' => $id_user
                ];

                $this->adminModel->insertProduct($data);
                $logs = array(
                    'action' => 'Fez um upload pela galeria.',
                    'type' => 'Sucesso',
                    'id_user' => $this->session->get('id'),
                    'ip' => $this->request->getIPAddress()
                );
                $this->adminModel->insert_logs($logs);
                if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
                else $show_to = $this->session->get('id');

                $notification = array(
                    'content' => $this->session->get('name') . ' fez upload de imagens na galeria!',
                    'id_user' => $this->session->get('id'),
                    'show_to' => $show_to
                );
                $this->adminModel->insert_notification($notification);
            }
        }
    }

    function deleteImage()
    {
        $src = $this->request->getPost('srcImg');
        $this->adminModel->remove_files_by($src);
        try {
            //code...
            unlink($src);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return $this->response->setJSON([
            "status" => 200,
            "message" => "Imagem excluída com sucesso."
        ]);
    }

    function all_categories()
    {

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'all_categories';
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }
        $data['companies'] = $this->adminModel->get_users_by_role('company');
        $data['categories'] = array();
        $categories = $this->adminModel->get_all_categories();
        $products = $this->adminModel->get_all_products();

        foreach ($categories as $category) {
            $category['qty_products'] = 0;
            foreach ($products as $product) {
                if ($product['category'] == $category['id']) {
                    $category['qty_products'] = $category['qty_products'] + 1;
                }
            }
            array_push($data['categories'], $category);
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/product/categories.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    function add_category()
    {
        $category_name = array(
            'name' => $this->request->getPost('category_name')
        );
        $this->adminModel->insert_category($category_name);
        $this->session->getFlashdata('success_msg', 'Categoria inserida com sucesso!');
        $logs = array(
            'action' => 'Adicionou uma nova categoria.',
            'message' => 'Categoria inserida com sucesso!',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $notification = array(
            'content' => $this->session->get('name') . ' adicionou a categoria ' . $this->request->getPost('category_name'),
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);

        return redirect()->to('/all_categories');
    }

    function update_category()
    {
        $update_categories = json_decode(json_encode($this->request->getPost('update_category')), true);
        foreach ($update_categories as $update_category) {
            $new = array(
                'name' => $update_category['name'],
                'id_company' => $update_category['id_company'],
            );
            $this->adminModel->update_category($new, $update_category['id']);
            $logs = array(
                'action' => 'Editou categoria.',
                'type' => 'Sucesso',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
            if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
            else $show_to = $this->session->get('id');

            $notification = array(
                'content' => $this->session->get('name') . ' editou a categoria ' . $update_category['name'],
                'id_user' => $this->session->get('id'),
                'show_to' => $show_to
            );
            $this->adminModel->insert_notification($notification);
        }
    }

    function delete_category()
    {
        $id_category = $_GET['id'];
        $category = $this->adminModel->get_category_by_id($id_category);

        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $notification = array(
            'content' => $this->session->get('name') . ' deletou a categoria ' . $category[0]['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);

        $this->adminModel->delete_category($id_category);
        $logs = array(
            'action' => 'Deletou uma categoria.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);


        return redirect()->to('/all_categories');
    }

    function add_category_product()
    {
        $category_name = array(
            'name' => json_decode(json_encode($this->request->getPost('name_category')), true)
        );
        $this->adminModel->insert_category($category_name);
        $logs = array(
            'action' => 'Adicionou categoria pelo form de produtos.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
        else $show_to = $this->session->get('id');

        $notification = array(
            'content' => $this->session->get('name') . ' adicionou a categoria ' . $category_name['name'],
            'id_user' => $this->session->get('id'),
            'show_to' => $show_to
        );
        $this->adminModel->insert_notification($notification);

        $categories = $this->adminModel->get_all_categories();
        echo json_encode($categories);
    }

    public function myaccount()
    {

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'myaccount';
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }
        $data['user'] = $this->adminModel->get_user_by_id($id_user);

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/account/myaccount.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function update_user()
    {

        $user = array(
            'id' => $this->request->getPost('id'),
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
        );

        $this->adminModel->update_user($user);
        $this->session->set('name', $this->request->getPost('name'));
        $this->session->set('email', $this->request->getPost('email'));

        $this->session->getFlashdata('success_msg', 'Suas informações foram atualizadas com sucesso!');
        $logs = array(
            'action' => 'Atualizou e-mail ou senha.',
            'type' => 'Sucesso',
            'message' => 'Suas informações foram atualizadas com sucesso!',
            'id_user' => $this->request->getPost('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        return redirect()->to('/myaccount');
    }

    public function img_user()
    {
        $id_user = $this->session->get('id');
        helper(['form', 'url']);

        $builder = \Config\Database::connect();
        $db = $builder->table('users');

        $input = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/png]',
                'max_size[file,1024]',
            ]
        ]);

        if (!$input) {
            die('Choose a valid file');
        } else {
            $img = $this->request->getFile('file');
            $img->move(ROOTPATH . 'writable/uploads/perfil/' . $id_user);
            $nome = $img->getName();
            $c = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýýþÿŔŕ?';
            $d = 'AAACAAAcEEEEIIIIDNOOOOOOUUUUYBSaaacaaaceeeeiiiidnoooooouuuuyybyRr-';
            $nome = strtr($nome, mb_convert_encoding($c, 'ISO-8859-1', 'UTF-8'), $d);
            $nome = str_replace("cI", "c", $nome);
            $nome = str_replace("cc", "c", $nome);
            $nome = str_replace(" ", "_", $nome);
            $nome = preg_replace("/[^-,;^!<>@&\/\sA-Za-z0-9_.-]/", '', $nome);

            $data = [
                'img_url' =>  'writable/uploads/perfil/' . $id_user . '/' . $nome,
            ];

            $db->where('id', $id_user);
            $db->set($data);
            $db->update();

            $logs = array(
                'action' => 'Alterou a foto de perfil.',
                'type' => 'Sucesso',
                'id_user' => $id_user,
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
        }
    }

    function user_pass()
    {
        $passwords = json_decode(json_encode($this->request->getPost('password')), true);
        foreach ($passwords as $password) {
            $user = $this->adminModel->get_user_by_id($password['id_user']);

            //Confirmando se a senha antiga digitada está correta
            if (md5($password['old_pass']) == $user["pass"]) {
                //Confirmando se os dois campos da senha nova são iguais
                if ($password['new_pass'] ==  $password['confirm_pass']) {
                    $users = array(
                        'id' => $password['id_user'],
                        'pass' => md5($password['new_pass'])
                    );
                    $this->adminModel->update_user($users);
                    $logs = array(
                        'action' => 'Alterou a senha.',
                        'type' => 'Sucesso',
                        'message' => 'Senha atualizada com sucesso!',
                        'id_user' => $password['id_user'],
                        'ip' => $this->request->getIPAddress()
                    );
                    $this->adminModel->insert_logs($logs);
                } else {
                    echo 'new_pass';
                }
            } else {
                echo 'old_pass';
            }
        }
    }

    public function versions()
    {
        /*if ($this->session->get('id') != "1") {
            $this->logout();
        }*/
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'versions';
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }
        $data['versions'] = $this->adminModel->get_all_versions();

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/version/all.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function add_version()
    {
        $version = array(
            'number' => $this->request->getPost('number'),
            'note' => $this->request->getPost('note')
        );
        $this->adminModel->insert_version($version);
        $logs = array(
            'action' => 'Inseriu uma nova versão.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);

        //A cada nova versão zera as visualizações de todos os usuários
        $new_version = array(
            'view_popup' => 0
        );
        $this->adminModel->update_view_user($new_version);
        $logs = array(
            'action' => 'Zerou as visualizações para todos os usuários.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        return redirect()->to('/versions');
    }

    public function edit_version()
    {
        /*if ($this->session->get('id') != "1") {
            $this->logout();
        }*/

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        if ($id_user == 1) $data['planograms'] = $this->adminModel->get_all_planograms();
        else if ($created_by) $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
        else $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);

        $id_version = $_GET['id'];
        $data['version'] = $this->adminModel->get_version_by_($id_version);

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/version/edit_version.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function update_version()
    {

        $version = array(
            'id' => $this->request->getPost('id'),
            'number' => $this->request->getPost('number'),
            'note' => $this->request->getPost('note')
        );
        $this->adminModel->update_version($version);

        $logs = array(
            'action' => 'Editou uma versão.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        return redirect()->to('/versions');
    }

    function update_view()
    {
        $id = json_decode(json_encode($this->request->getPost('id')), true);
        $views = array(
            'id' => $id,
            'view_popup' => 1
        );
        $this->adminModel->update_view_by_user($views);
        $logs = array(
            'action' => 'Usuário visualizou a última versão.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
    }

    public function logs()
    {
        /*if ($this->session->get('id') != "1") {
            $this->logout();
        }*/
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'logs';
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        $data['logs'] = $this->adminModel->get_all_logs();

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/log/all.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function users()
    {

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $data['route'] = 'users';
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }
        $all_permissions = $this->permissionModel->get_all_permissions();
        $data['all_permissions'] = $all_permissions;
        $data['users'] = $this->adminModel->get_users_by_($id_user);
        $permissions = $this->permissionModel->check_permission($data['usuario']["id"]);
        $data['permissions'] = $permissions;

        echo view("commom/template/html-header.php");
        //echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/user/all.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function add_user()
    {


        $user = array(
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'pass' => md5($this->request->getPost('pass')),
            'cpf_cnpj' => $this->request->getPost('cpf_cnpj'),
            'role' => 'admin',
            'created_by' => $this->session->get('id')
        );
        $check = $this->adminModel->check('users', 'email', $user['email']);
        if (!$check) {
            $this->adminModel->insert_user($user);
            $logs = array(
                'action' => 'Usuário cadastrado.',
                'type' => 'Sucesso',
                'message' => 'Usuário cadastrado com sucesso!',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
            $this->session->getFlashdata('success_msg', 'Usuário cadastrado com sucesso!');

            if ($this->session->get('created_by')) $show_to = $this->session->get('created_by');
            else $show_to = $this->session->get('id');

            $notification = array(
                'content' => $this->session->get('name') . ' adicionou ' . $user['name'],
                'id_user' => $this->session->get('id'),
                'show_to' => $show_to
            );
            $this->adminModel->insert_notification($notification);
        } else {
            $logs = array(
                'action' => 'Tentativa de cadastrar usuário já existente.',
                'type' => 'Erro',
                'message' => 'Esse e-mail já está cadastrado!',
                'id_user' => $this->session->get('id'),
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
            $this->session->getFlashdata('error_msg', 'Esse e-mail já está cadastrado!');
        }
        return redirect()->to('/users');
    }

    public function delete_user($id_user)
    {
        $id = $id_user;
        $this->adminModel->delete_user($id);
        $logs = array(
            'action' => 'Deletou um usuário.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        return redirect()->to('/users');
    }

    function update_view_notification()
    {
        $id = json_decode(json_encode($this->request->getPost('id')), true);
        $created_by = json_decode(json_encode($this->request->getPost('created_by')), true);
        if (!$created_by) $notifications = $this->adminModel->check_view_by($id);
        else $notifications = $this->adminModel->check_view_by($created_by);
        if ($notifications)
            foreach ($notifications as $notification) {
                if (!(mb_strpos($notification['view'], $id) !== false)) { // Se não tiver visto a notificação ainda
                    $new_view = trim($notification['view'] . ' ' . $id);
                    $new_view = preg_replace('/( )+/', ' ', $new_view);

                    $view = array(
                        'id' => $notification['id'],
                        'view' => $new_view
                    );
                    $this->adminModel->update_view_by($view);
                }
            }
    }

    public function delete_notify()
    {
        $id = $this->session->get('id');
        $this->adminModel->delete_notify($id);
        $logs = array(
            'action' => 'Deletou as notificacoes.',
            'type' => 'Sucesso',
            'id_user' => $id,
            //'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
    }

    public function view_notify()
    {

        $id = $this->session->get('id');
        $this->adminModel->view_notify($id);
        $logs = array(
            'action' => 'Marcou as Notificacoes como visualizadas.',
            'type' => 'Sucesso',
            'id_user' => $id,
            'ip' => $this->request->getIPAddress()
        );
        var_dump($logs);
        die();
        $this->adminModel->insert_logs($logs);
    }

    public function heatmap()
    {
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        if ($id_user == 1) {
            $data['planograms'] = $this->adminModel->get_all_planograms();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['planograms'] = $this->adminModel->get_planogram_by_user($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        $get_id = $this->request->getGet("id");
        $id_scenario = $get_id;
        $data['cenario'] = $this->adminModel->get_scenario_by_id($id_scenario);
        $data['img'] = $this->adminModel->get_scenario_file('scenario' . $data['cenario'][0]['id'] . '.png');

        $data['results'] = $this->adminModel->get_eye_tracking_by($id_scenario);
        $data['results'] = json_encode($data['results']);

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/nav-heatmap.php", $data);
        echo view("admin/template/header-heatmap.php", $data);
        echo view('admin/report/heatmap.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    function export_user_image()
    {
        $img = $_REQUEST['data'];
        // $user_id = 
        echo $img;
        $id_user = $this->session->get('id');

        if ($img) {
            $filteredData = substr($img, strpos($img, ",") + 1);
            $unencodedData = base64_decode($filteredData);
            $file_name = 'user' . $id_user . '.png';
            $dest_folder = 'writable/uploads/users/' . $id_user . '.png';
            $folder = 'writable/uploads/users/';
            if (!is_dir($folder)) mkdir($folder);
            file_put_contents($dest_folder, $unencodedData);

            list($width, $height) = getimagesize($dest_folder);

            $files = array(
                'id' => $id_user,
                'print_eye_tracking' => $dest_folder,
            );
            $this->adminModel->update_user_image($files);

            $logs = array(
                'action' => 'Imagem do usuário carregada.',
                'type' => 'Sucesso',
                'id_user' => $id_user,
                'ip' => $this->request->getIPAddress()
            );
            $this->adminModel->insert_logs($logs);
        }
    }

    public function gera_heatmap($get_id_scenario)
    {
        $id_user = $this->session->get('id');
        $img = $this->request->getPost('img_val');
        $id_scenario = $get_id_scenario;

        if ($img) {
            $filteredData = substr($img, strpos($img, ",") + 1);
            $unencodedData = base64_decode($filteredData);
            $file_name = 'scenario' . $id_scenario . '.png';
            $dest_folder = 'writable/uploads/scenarios/scenario' . $id_scenario . '.png';
            $folder = 'writable/uploads/scenarios/';
            if (!is_dir($folder)) mkdir($folder);
            file_put_contents($dest_folder, $unencodedData);

            list($width, $height) = getimagesize($dest_folder);

            $files = array(
                'file_name' => $file_name,
                'src' => $dest_folder,
                'width' => $width,
                'height' => $height,
                'id_user' => $id_user
            );
            $this->adminModel->insert_file($files);
        }

        return redirect()->to('/heatmap?id=' . $id_scenario);

        if ($this->request->getPost('all')) {
            return redirect()->to('/heatmap?id=' . $id_scenario);
        } else {
            $users = $this->request->getPost('usuario');
            $users = implode(" ", $users);
            return redirect()->to('/heatmap?id=' . $id_scenario . '/users=' . $users);
        }
    }
}
