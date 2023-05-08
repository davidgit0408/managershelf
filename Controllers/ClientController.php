<?php

namespace App\Controllers;

use CodeIgniter\HTTP\IncomingRequest;

class ClientController extends BaseController
{

    private $adminModel;
    private $companyModel;
    private $session;

    public function __construct()
    {
        $this->clientModel = new \App\Models\ClientModel();
        $this->session = session();
        $this->adminModel = new \App\Models\AdminModel();
        $this->request = service('request');
    }

    public function index()
    {
        $this->session->sess_destroy();
        $company = $this->request->getGet('id_company');
        $data['company'] = $this->clientModel->get_company_by_id($company);
        $data['scenarios'] = $this->clientModel->get_scenarios_by_company($company);

        echo view("commom/template/html-header.php");
        echo view('pages/login_client.php', $data);
        echo view("commom/template/html-footer.php");
    }

    public function login()
    {
        $scenario = $this->session->set('scenario');
        $email = $this->session->set('email');
        $password = md5($this->session->set('password'));
        $id_company = $this->session->set('id_company');

        $data = $this->clientModel->login($email, $password);

        if ($data) {
            $this->session->set('id', $data['id']);
            $this->session->set('id_company', $this->session->set('id_company'));
            $this->session->set('email', $data['email']);
            $this->session->set('name', $data['name']);
            $this->session->set('id_scenario', $this->session->set('id_scenario'));
            redirect('client/scenario?id_scenario=' . $this->session->set('id_scenario') . '&id_company=' . $id_company);
        } else {
            $this->session->setFlashdata('error_msg', 'E-mail e/ou senha inválidos');
            redirect('client?id_company=' . $this->session->set('id_company'));
        }
    }

    public function loginClient($user_uuid){
        $client['name'] = $user_uuid;
        $client['email'] = $user_uuid;
        $client['pass'] = md5('1234'); //senha padrão       

        $check = $this->clientModel->check('users', 'email', $user_uuid);
        //caso usuário não exista, cadastra
        if (!$check) {
            $this->clientModel->insert_client($client);
        }
        $data = $this->clientModel->get_user_by_email($client['email']);
        $user = $data[0];
        $this->session->set('id', $user['id']);
        $this->session->set('email', $user['email']);
        $this->session->set('name', $user['name']);
        $this->session->set('idade', $user['birthday']);
        $this->session->set('classe_social', $user['tel']);
        $this->session->set('usa_em_casa', $user['cellphone']);
        $this->session->set('pix', $user['cpf_cnpj']);
        $this->session->set('estado', $user['state']);
    }

    public function direcionarClient(){

        $estadosRegioes = [
            "sudeste" => [
                "ES",
                "MG",
                "RJ",
                "SP"
            ],
            "sul" => [
                "PR",
                "SC",
                "RS"
            ],
            "nordeste" => [
                "MA",
                "PI",
                "CE",
                "RN",
                "PB",
                "PE",
                "AL",
                "SE",
                "BA"
            ]
        ];

        $scenarios = $this->clientModel->qtdPesquisa();
        $scenariosQtd = [
            '140' => [],
            '141' => [],
            '142' => []
        ];

        foreach ($scenarios as $key => $value) {
            if( !isset($scenariosQtd[$value["scenario_id"]]["regiao"]["sudeste"]) ) 
                $scenariosQtd[$value["scenario_id"]]["regiao"]["sudeste"] = 0;
            if( !isset($scenariosQtd[$value["scenario_id"]]["regiao"]["sul"]) ) 
                $scenariosQtd[$value["scenario_id"]]["regiao"]["sul"] = 0;
            if( !isset($scenariosQtd[$value["scenario_id"]]["regiao"]["nordeste"]) ) 
                $scenariosQtd[$value["scenario_id"]]["regiao"]["nordeste"] = 0;

            if(in_array($value["estado"], $estadosRegioes["sudeste"])) $scenariosQtd[$value["scenario_id"]]["regiao"]["sudeste"]++;
            else if(in_array($value["estado"], $estadosRegioes["sul"])) $scenariosQtd[$value["scenario_id"]]["regiao"]["sul"]++;
            else if(in_array($value["estado"], $estadosRegioes["nordeste"])) $scenariosQtd[$value["scenario_id"]]["regiao"]["nordeste"]++;

            
            if( !isset($scenariosQtd[$value["scenario_id"]]["classe_social"]["AB1"]) ) 
                $scenariosQtd[$value["scenario_id"]]["classe_social"]["AB1"] = 0;
            if( !isset($scenariosQtd[$value["scenario_id"]]["classe_social"]["B2C"]) ) 
                $scenariosQtd[$value["scenario_id"]]["classe_social"]["B2C"] = 0;

            if($value["classe_social"] >= 38) $scenariosQtd[$value["scenario_id"]]["classe_social"]["AB1"]++;
            else if($value["classe_social"] <= 37) $scenariosQtd[$value["scenario_id"]]["classe_social"]["B2C"]++;
    
            if( !isset($scenariosQtd[$value["scenario_id"]]["idade"]["faixa1"]) ) 
                $scenariosQtd[$value["scenario_id"]]["idade"]["faixa1"] = 0;
            if( !isset($scenariosQtd[$value["scenario_id"]]["idade"]["faixa2"]) ) 
                $scenariosQtd[$value["scenario_id"]]["idade"]["faixa2"] = 0;

            if($value["idade"] === "1" || $value["idade"] === "2") $scenariosQtd[$value["scenario_id"]]["idade"]["faixa1"]++;
            else if($value["idade"] === "3" || $value["idade"] === "4") $scenariosQtd[$value["scenario_id"]]["idade"]["faixa2"]++;


            if( !isset($scenariosQtd[$value["scenario_id"]]["amonia"]["com"]) ) 
                $scenariosQtd[$value["scenario_id"]]["amonia"]["com"] = 0;
            if( !isset($scenariosQtd[$value["scenario_id"]]["amonia"]["sem"]) ) 
                $scenariosQtd[$value["scenario_id"]]["amonia"]["sem"] = 0;

            if(str_contains(strval($value["usa_em_casa"]), "5.1")) $scenariosQtd[$value["scenario_id"]]["amonia"]["com"]++;
            else if(str_contains(strval($value["usa_em_casa"]), "5.2")) $scenariosQtd[$value["scenario_id"]]["amonia"]["sem"]++;

            // if( !isset($scenariosQtd[$value["scenario_id"]]["eye_tracking"]) ) 
            //     $scenariosQtd[$value["scenario_id"]]["eye_tracking"] = 0;
            // if( !isset($scenariosQtd[$value["scenario_id"]]["sem_eye_tracking"]) ) 
            //     $scenariosQtd[$value["scenario_id"]]["sem_eye_tracking"] = 0;

            // if( !empty($value["uuid"]) ) $scenariosQtd[$value["scenario_id"]]["eye_tracking"]++;
            // else $scenariosQtd[$value["scenario_id"]]["sem_eye_tracking"]++;
        }

        $scenariosAvalaibe = array_filter($scenariosQtd, function($var){
            if( empty($var) ) return true;

            if($var["idade"]["faixa1"] >= (210 / 3) && ($this->session->get('idade') === "1" || $this->session->get('idade') === "2") ) return false;
            else if($var["idade"]["faixa2"] >= (210 / 3) && ($this->session->get('idade') === "2" || $this->session->get('idade') === "3")) return false;

            if($var["regiao"]["sudeste"] >= (210 / 3) && in_array($this->session->get('estado'), $estadosRegioes["sudeste"]) ) return false;
            else if($var["regiao"]["sul"] >= (84 / 3) && in_array($this->session->get('estado'), $estadosRegioes["sul"]) ) return false;
            else if($var["regiao"]["nordeste"] >= (126 / 3) && in_array($this->session->get('estado'), $estadosRegioes["nordeste"]) ) return false;

            if($var["classe_social"]["AB1"] >= (252 / 3) && $this->session->get('classe_social') >= 38 ) return false;
            else if($var["classe_social"]["B2C"] >= (168 / 3) && $this->session->get('classe_social') <= 37 ) return false;

            if($var["amonia"]["com"] >= (252 / 3) && str_contains($this->session->get('usa_em_casa'), "5.1") ) return false;
            else if($var["amonia"]["sem"] >= (168 / 3) && str_contains($this->session->get('usa_em_casa'), "5.2") ) return false;

            // if($var["eye_tracking"] >= (210 / 3) && str_contains($this->session->get('usa_em_casa'), "5.1") ) return false;
            // else if($var["sem_eye_tracking"] >= (210 / 3) && str_contains($this->session->get('usa_em_casa'), "5.2") ) return false;

            return true;
        });

        if(count($scenariosAvalaibe) === 0) return false;

        $eyeTracking = $this->clientModel->qtdEyeTracking();

        return [
            "id_scenario" => array_keys($scenariosAvalaibe)[0],
            "eye_tracking" => false 
        ];
    }

    public function scenario()
    {
        if ($this->request->getGet('user_uuid')) {
            $this->loginClient($this->request->getGet('user_uuid'));
        }

        //Salvando todos os parametros passados na url, na sessão do usuario
        foreach ($this->request->getGet() as $key => $val) {
            $this->session->set($key, $val);
        }
        
        // Pegando o id_scenario direto da url
        $id_scenario = $this->request->getGet('id_scenario');
        $this->session->set('id_scenario', $id_scenario);
        $id_company = $this->request->getGet('id_company');
        $get_scenario = $this->clientModel->get_scenario_by_id($id_scenario);
        $data['status'] = $get_scenario[0]["status"];
        $data['eye_tracking'] = $this->request->getGet('eye_tracking');
        $company = $this->clientModel->get_company_by_id($id_company);

        if (!empty($get_scenario)) {
            $data['scenario'] = $get_scenario;
            $data['products'] = $this->clientModel->get_all_products();
            $all_positions = $this->clientModel->get_position_by_scenario($id_scenario);
            // echo "<pre>";print_r($all_positions);echo "</pre>";die();
            foreach ($all_positions as $position) {
                $data['positions'][$position["column"]][$position["shelf"]][] = $position;
            }
            
            echo view("commom/template/html-header.php");
            echo view('client/scenario.php', $data);
            echo view("commom/template/html-footer.php");
        } else {
            echo view('errors/html/error_404');
        }
    }

    public function add_cart()
    {
        $id_scenario = $this->session->get('id_scenario');
        $id_company = $this->session->get('id_company');
        $viewed = substr($this->request->getPost('viewed'), 1);
        $bought = substr($this->request->getPost('bought'), 1);
        $rand = md5(uniqid(rand(), true));
        if ($bought != "") {
            $bought_array = explode(',', $bought);
            foreach ($bought_array as $bought) {
                $cart = array(
                    'id_cart' => $rand,
                    'id_client' => $this->session->get('id'),
                    'id_scenario' => $id_scenario,
                    'id_company' => $id_company,
                    'product_ean' => $bought,
                    'removed_cart' => $this->request->getPost('removed_' . $bought),
                    'sequence' => $this->request->getPost('sequence_' . $bought),
                    'viewed' => 0,
                    'bought' => $this->request->getPost('qty_' . $bought),
                    'time' => $this->request->getPost('time'),
                    'ip_public' => $this->request->getPost('ip_public'),
                    'ip_private' => $this->request->getPost('ip_private'),
                );
                $this->clientModel->insert_cart($cart);
            }
        }
        if ($viewed != "") {
            $viewed_array = explode(',', $viewed);
            foreach ($viewed_array as $viewed) {
                $cart = array(
                    'id_cart' => $rand,
                    'id_client' => $this->session->get('id'),
                    'id_scenario' => $id_scenario,
                    'id_company' => $id_company,
                    'product_ean' => $viewed,
                    'removed_cart' => $removed = $this->request->getPost('removed_' . $viewed) ? $this->request->getPost('removed_' . $viewed) : 0,
                    'sequence' => $this->request->getPost('sequence_' . $viewed),
                    'viewed' => 1,
                    'bought' => 0,
                    'time' => $this->request->getPost('time'),
                    'ip_public' => $this->request->getPost('ip_public'),
                    'ip_private' => $this->request->getPost('ip_private'),
                );
                $this->clientModel->insert_cart($cart);
            }
        }
        $cart = array(
            'id_client' => $this->session->get('id'),
            'id_scenario' => $id_scenario,
            'id_company' => $id_company
        );
        $id_cart = $this->clientModel->get_cart_by_all($cart);
        if ($id_cart == "Dados inexistentes" || $bought == "") {
            $order = array(
                'id_user' => $this->session->get('id'),
                'id_cart' => ($id_cart == "Dados inexistentes") ? 'Sem carrinho' : $id_cart[0]['id_cart'],
                'id_scenario' => $id_scenario,
                'id_company' => $id_company,
                'total' => 'R$ 00,00',
                'payment_method' => 'nenhum',
            );
            $this->clientModel->insert_order($order);
            return redirect()->to('thankyou');
        }
        return redirect()->to('cart?id_cart=' . $id_cart[0]['id_cart']);
    }

    public function cart()
    {
        $cart_id = $this->request->getGet('id_cart');
        if ($cart_id) {
            $data['cart'] = $this->clientModel->get_bought_cart_by_id($cart_id);
            echo view("commom/template/html-header.php");
            echo view("client/template/header.php");
            echo view('client/cart.php', $data);
            echo view("client/template/footer.php");
            echo view("commom/template/html-footer.php");
        } else {
            echo view('errors/html/error_404');
        }
    }

    public function remove_checkout()
    {
        $position = array(
            'id_cart' => $this->request->getPost('id_cart'),
            'product_ean' => $this->request->getPost('product_ean'),
            'bought' => $this->request->getPost('bought'),
            'removed_checkout' => $this->request->getPost('removed_checkout'),
        );
        $this->clientModel->update_cart($position);
        echo 'removido';
    }

    public function add_order()
    {
        // echo "<pre>";print_r($this->session->get());echo "</pre>";die();
        $id_company = $this->request->getPost('id_company');
        $order = array(
            'id_user' => $this->session->get('id'),
            'id_cart' => $this->request->getPost('id_cart'),
            'id_scenario' => $this->request->getPost('id_scenario'),
            'id_company' => $this->request->getPost('id_company'),
            'total' => $this->request->getPost('total'),
            'payment_method' => $this->request->getPost('payment_method'),
        );
        $this->clientModel->insert_order($order);

        //Verificação se a quantidade de compras feitas é igual a quantidade máxima de pesquisas
        $get_orders = $this->clientModel->get_order_by_company($id_company);  //compras com determinado estudo
        $qtd_orders_by_company = count($get_orders); //quantidade de compras com determinado estudo
        $company = $this->clientModel->get_company_by_id($id_company);
        $qtd_pesquisa = intval($company[0]['qtd_pesquisa']); //quantidade máxima de compras

        //se a quantidade de compras por estudo for igual a quantidade máxima de pesquisas atualiza o status para finalizado
        if ($qtd_orders_by_company == $qtd_pesquisa) {
            $company = array(
                'id' => $id_company,
                'status' => 'Finalizado'
            );
            $this->clientModel->update_company($company);

            //cenários vinculado ao estudo que acabou de ser finalizado terão o status = inativo
            $scenarios = $this->clientModel->get_scenario_by_company($id_company);
            foreach ($scenarios as $scenario) {
                $data = array(
                    'id' => $scenario['id'],
                    'status' => 'Inativo'
                );
                $this->clientModel->update_scenario_status($data);
            }
        }

        $id_order = $this->clientModel->get_order_by_cart($this->request->getPost('id_cart'));
        return redirect()->to('/thankyou?id_order=' . $id_order[0]['id']);
    }

    public function thanks()
    {
        echo view("commom/template/html-header.php");
        echo view("client/template/header.php");
        echo view('client/thanks.php', $data);
        echo view("client/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function summary()
    {
        $id_order = $this->request->getGet('id_order');
        if ($id_order) {
            $data['order'] = $this->clientModel->get_order_by_id($id_order);
            $data['viewed'] = $this->clientModel->get_viewed_cart_by_id($data['order'][0]['id_cart']);
            $data['bought'] = $this->clientModel->get_bought_cart_by_id($data['order'][0]['id_cart']);
            $data['removed_cart'] = $this->clientModel->get_removed_cart_cart_by_id($data['order'][0]['id_cart']);
            $data['removed_checkout'] = $this->clientModel->get_removed_checkout_cart_by_id($data['order'][0]['id_cart']);
            $data['cart'] = $this->clientModel->get_cart_by_id($data['order'][0]['id_cart']);
            echo view("commom/template/html-header.php");
            echo view("client/template/header.php");
            echo view('client/summary.php', $data);
            echo view("client/template/footer.php");
            echo view("commom/template/html-footer.php");
        } else {
            echo view('errors/html/error_404');
        }
    }

    public function thankyou()
    {
        if ($this->session->get('id_company')) {
            $id_company = $this->session->get('id_company');
        } else {
            $id_order = $this->session->get('id_order');
            $get_order = $this->clientModel->get_order_by_id($id_order);
            $id_company = $get_order[0]['id_company'];
        }

        $data['company'] = $this->clientModel->get_company_by_id($id_company);
        //Link de saída do estudo
        $link = $data['company'][0]["link"];

        $parameters_string = substr(strstr($link, '?'), 1);

        //Encontrar os parametros do link de saida
        parse_str($parameters_string, $parameters);

        $indices = [];
        $valores = [];
        foreach ($parameters as $key => $val) {
            //Verificar se esses parametros existem na sessão
            if ($this->session->get($key)) {
                //salvando os indices e os valores em arrays
                array_push($indices, $this->session->get($key));
                array_push($valores, $val);
            }
        }

        //substituindo os valores dos parametros
        $novo_link = str_replace($valores, $indices, $link);
        $data['link'] = $novo_link;
        $data['id_company'] = $id_company;

        echo view("commom/template/html-header.php");
        echo view('client/thankyou.php', $data);
        echo view("commom/template/html-footer.php");
    }

    public function add_user()
    {
        $client = array(
            'name'      => $this->session->set('email'),
            'email'     => $this->session->set('email'),
            "pass"      => md5($this->session->set('email')),
            'id_company' => 22,
            'role'      => 'client'
        );
        $check = $this->clientModel->check('users', 'email', $client['email']);
        //$id = $this->clientModel->insert_client_id($client);
        if (!$check) {
            $id = $this->clientModel->insert_client_id($client);
        } else {
            redirect('https://dkr1.ssisurveys.com/projects/end?rst=3&psid=' . $this->session->set('email'));
        }
        foreach ($this->session->set() as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $client_meta = array(
                'id_user' => $id,
                'meta_key' => $key,
                'meta_value' => $value
            );
            $this->clientModel->insert_client_meta($client_meta);
        }

        $mt1 = 0;
        $tt1 = 0;
        $ids = array("");
        foreach ($this->clientModel->get_onde_compra() as $onde_compra) {
            if ($onde_compra["meta_value"] == "Hipermercado" or $onde_compra["meta_value"] == "Supermercado") {
                if ($this->clientModel->get_order_by_user($onde_compra["id_user"]) != "Dados inexistentes" && !in_array($onde_compra["id_user"], $ids)) {
                    $mt1++;
                    array_push($ids, $onde_compra["id_user"]);
                }
                //var_dump(count($this->clientModel->get_order_by_user($onde_compra["id_user"])));
            }
            if ($onde_compra["meta_value"] == "Bares" or $onde_compra["meta_value"] == "Padarias") {
                if ($this->clientModel->get_order_by_user($onde_compra["id_user"]) != "Dados inexistentes" && !in_array($onde_compra["id_user"], $ids)) {
                    $tt1++;
                    array_push($ids, $onde_compra["id_user"]);
                }
            }
        }
        //var_dump($mt1);

        if ($this->session->set("onde_compra_gelatina")) {
            $onde_compra = $this->session->set("onde_compra_gelatina");
        } else {
            $onde_compra = $this->session->set("onde_compra_bola");
        }

        $mt1 = $mt1 - 55;

        if ($onde_compra == "Hipermercado" or $onde_compra == "Supermercado") {
            if ($mt1 <= 100) {
                $scenario = 61;
            } else if ($mt1 <= 145) {
                $scenario = 57;
            }
        } else if ($onde_compra == "Bares" or $onde_compra == "Padarias") {
            if ($tt1 <= 100) {
                $scenario = 60;
            } else if ($tt1 <= 200) {
                $scenario = 58;
            }
        } else {
            redirect('https://dkr1.ssisurveys.com/projects/end?rst=2&psid=' . $this->session->set('email'));
        }

        $email      = $this->session->set('email');
        $password   = md5($this->session->set('email'));

        $data = $this->clientModel->login($email, $password);

        if ($data) {
            $this->session->set('id', $data['id']);
            $this->session->set('id_company', 22);
            $this->session->set('email', $data['email']);
            $this->session->set('name', $data['name']);
            $this->session->set('id_scenario', $scenario);
            redirect('/scenario/id_scenario=' . $scenario);
        } else {
            $this->session->setFlashdata('error_msg', 'E-mail e/ou senha inválidos');
            echo view("commom/template/html-header.php");
            echo view('pages/login_client.php');
            echo view("admin/template/footer.php");
            echo view("commom/template/html-footer.php");
        }
        //redirect('client/etapa3?id_user='.$id);
    }

    public function save_all()
    {
        $id =   json_decode($_POST['order_data'], true)['id_user'];
        $psid = $this->clientModel->get_user_by_id($id)[0]['email'];
        foreach ($this->request->getPost() as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $client_meta = array(
                'id_user' => $id,
                'meta_key' => $key,
                'meta_value' => $value
            );
            $this->clientModel->insert_client_meta($client_meta);
        }
        redirect('https://dkr1.ssisurveys.com/projects/end?rst=1&psid=' . $psid . '&basic=15369');
    }

       public function save_user_image()
    {
        $photo = $this->request->getPost('media64');
        $user = str_replace(' ', '', $this->request->getPost('user'));
        $photo = str_replace('data:image/png;base64,', '', $photo);
        $photo = str_replace(' ', '+', $photo);
        $photo = str_replace('data:image/jpeg;base64,', '', $photo);
        $photo = str_replace('data:image/gif;base64,', '', $photo);
        $entry = base64_decode($photo);
        $image = imagecreatefromstring($entry);
        $d = mktime(11, 14, 54, 8, 12, 2014);
        $fileName = "image_" . $user['name'] .  "_" . date("Y-m-d h:i:sa", $d) . ".jpeg";
        $directory = "assets/uploads/users/" . $fileName;
        $logs = array(
            'action' => 'Imagem do usuário carregada no Eye Tracking.',
            'type' => 'Sucesso',
            'id_user' => $user['id'],
            'ip' => $this->request->getIPAddress()
        );
        $this->clientModel->upload_image_user($user['id'], "assets/uploads/users/" . $fileName);
        $this->adminModel->insert_logs($logs);
        header('Content-type:image/jpeg');
        if (!empty($path)) {
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $saveImage = imagejpeg($image, $directory);
        imagedestroy($image);
    }
    
    public function save_eye_tracking()
    {
        $produtos = $this->request->getPost('produtosVistos');
        $uuid = $this->request->getPost('email');
        $scenario = $this->request->getPost('url');
        $storeData = json_decode($this->request->getPost('storeData'), true);
    
      
        $array['company_id'] = $this->session->get('id_company');
        $array['scenario_id'] = $scenario;
        $array['uuid'] = $uuid;
        $array['user_id'] = $this->session->get('id');
       
        ////////////////////////////////////////
        //changed to save duration of fixation//
        ////////////////////////////////////////
        $started = $storeData[0]['time'];
        foreach ($storeData as $storeData) {
            $time = $storeData['time'] - $started;
            $started = $storeData['time'];
            $array['GazeX'] = $storeData['GazeX'];
            $array['GazeY'] = $storeData['GazeY'];
            $array['HeadX'] = $storeData['HeadX'];
            $array['HeadY'] = $storeData['HeadY'];
            $array['HeadZ'] = $storeData['HeadZ'];
            $array['HeadYaw'] = $storeData['HeadYaw'];
            $array['HeadPitch'] = $storeData['HeadPitch'];
            $array['HeadRoll'] = $storeData['HeadRoll'];
            $array['rx'] = $storeData['rx'];
            $array['ry'] = $storeData['ry'];
            $array['rw'] = $storeData['rw'];
            $array['rh'] = $storeData['rh'];
            $array['state'] = $storeData['state'];
            $array['time'] = $time;
            $array['FrameNr'] = $storeData['FrameNr'];
            $array['Xview'] = $storeData['Xview'];
            $array['Yview'] = $storeData['Yview'];
            $array['docX'] = $storeData['docX'];
            $array['docY'] = $storeData['docY'];
            $array['produtosVistos'] = $storeData['produtosVistos'];
            $this->clientModel->insert_eye_tracking($array);
        }
        
        echo 'ok';
    }
    public function saveProdutosVistos(){
        return;
        $uuid =  $this->request->getPost('email');
        $scenario_id = $this->request->getPost('id_cenario');
        $produtosVistos = $this->request->getPost('produtosVistos');
        $array['uuid'] = $uuid;
        $array['scenario_id'] =  $scenario_id;
        $array['produtosVistos'] = $produtosVistos;
        $this->clientModel->updateProdutosVistos($array);
    }
    public function choose_scenario()
    {
        $company = $this->session->get('id_company');
        $user = $this->session->get('id_user');
        $data['scenarios'] = $this->clientModel->get_scenarios_by_company($company);
        $data['user'] = $this->clientModel->get_user_by_id($user);
        $data['company'] = $this->clientModel->get_company_by_id($company);

        echo view("commom/template/html-header.php");
        echo view('client/choose_scenario.php', $data);
        echo view("commom/template/html-footer.php");
        echo view("admin/template/footer.php");
    }

    public function login_client()
    {
        $id_scenario = $this->session->set('id_scenario');
        $id_company = $this->session->set('id_company');

        redirect('client/scenario?id_scenario=' . $id_scenario . '&id_company=' . $id_company);
    }
}
