<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class BigDataController extends BaseController
{
    private $adminModel;
    private $session;

    public function __construct()
    {
        $this->adminModel = new \App\Models\AdminModel();
        $this->bigdataModel = new \App\Models\BigDataModel();
        $this->session = \Config\Services::session();
        $this->request = service('request');
    }

    public function index()
    {
        //
    }

    public function viewList()
    {
        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);

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
        $data['products'] = $products;

        echo view("commom/template/html-header.php");
        if (!isset($_GET['pagina'])) {
            echo view("admin/template/splash.php");
        }
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/bigdata/all.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function viewCreate()
    {
        # code...

        $id_user = $this->session->get('id');
        $created_by = $this->session->get('created_by');
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        if ($id_user == 1) {
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }

        echo view("commom/template/html-header.php");
        echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/bigdata/import.php');
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function store()
    {
        $ean_repetido = 0;
        $ean_inserido = 0;
        if (isset($_FILES['file'])) {
            $errors = array();
            $file_tmp = $_FILES['file']['tmp_name'];

            $row = 0;
            if (($handle = fopen($file_tmp, "r")) !== FALSE) {
                while (($data = fgetcsv($handle, 10000, ";")) !== FALSE) {
                    $row++;
                    // echo "<pre>";print_r($data);die;
                    if (count($data) != 10) {
                        $this->session->setFlashdata('error_msg', 'Utilize o modelo padrão para envio de dados.');
                        return redirect()->to('/bigdata/import');
                    }
                    if ($row == 1) continue;

                    $data = array_pad($data, 10, '');

                    $ean = $data[0];
                    $nome = $data[1];
                    $preco = $data[2];
                    $marca = $data[3];
                    $fabricante = $data[4];
                    $gramatura = $data[5];
                    $caracteristica = $data[6];
                    $categoria = $data[7];
                    $altura = $data[8];
                    $largura = $data[9];

                    $check = $this->adminModel->check('bigdata_products', 'ean', $ean);
                    if (!$check) {
                        $product = array(
                            'ean' => $ean,
                            'name' => $nome,
                            'price' => $preco,
                            'brand' => $marca,
                            'producer' => $fabricante,
                            'grammage' => $gramatura,
                            'feature' => $caracteristica,
                            'category' => $categoria,
                            'height' => $altura,
                            'width' => $largura
                        );
                        $this->bigdataModel->insert($product);

                        $ean_inserido++;
                    } else {
                        $ean_repetido++;
                    }
                }
                fclose($handle);
            }else{
                $this->session->setFlashdata('success_msg', 'Erro ao ler o arquivo.');
            }
        }
        if ($ean_inserido === 0) {
            $this->session->setFlashdata('error_msg', 'Todos os eans inseridos já existem em nosso banco de dados. Nenhum produto cadastrado.');
            return redirect()->to('/bigdata/import');
        }

        $this->session->setFlashdata('success_msg', $ean_inserido.' produtos inseridos com sucesso. Na sua base existia um total de ' . $ean_repetido . ' ean(s) que já estavam cadastrados em nosso banco de dados e/ou repetidos.');
        return redirect()->to('/bigdata/import');
    }

    public function viewUpdate($id_product)
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
        $data['usuario'] = $this->adminModel->get_user_by_id($id_user);
        $created_by = $this->session->get('created_by');

        if ($id_user == 1) {
            $data['imagens'] = $this->adminModel->get_all_files();
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        } else if ($created_by) {
            $data['imagens'] = $this->adminModel->get_files_by($created_by);
            $data['notifications'] = $this->adminModel->get_notifications_by($created_by);
        } else {
            $data['imagens'] = $this->adminModel->get_files_by($id_user);
            $data['notifications'] = $this->adminModel->get_notifications_by($id_user);
        }
        $data['product'] = $this->bigdataModel->get_product_by_id($id_product);

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
        echo view("commom/template/html-header.php");
        // echo view("admin/template/splash.php");
        echo view("admin/template/sidebar-html.php", $data);
        echo view("admin/template/header.php", $data);
        echo view('admin/bigdata/edit_product.php', $data);
        echo view("admin/template/footer.php");
        echo view("commom/template/html-footer.php");
    }

    public function update()
    {
        $id = $this->request->getPost('id');
    
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
        $this->bigdataModel->update_product($product);
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

    public function isJson($string) {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    public function delete()
    {
        $id_product = $_GET['id'];
        $product = $this->adminModel->get_product_by_id($id_product);

        $this->bigdataModel->delete_product($id_product);

        $logs = array(
            'action' => 'Deletou um produto do bigdata.',
            'type' => 'Sucesso',
            'id_user' => $this->session->get('id'),
            'ip' => $this->request->getIPAddress()
        );
        $this->adminModel->insert_logs($logs);
        return redirect()->to('bigdata/all');
    }
}
