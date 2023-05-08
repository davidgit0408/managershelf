<?php

namespace App\Controllers;

class SignUpController extends BaseController
{

    private $signupModel;
    private $email;
    private $session;

    public $emailConfig = array(
        'protocol' => 'smtp',
        'SMTPHost' => 'smtp.gmail.com',
        'SMTPPort' => 587,
        'SMTPUser' => 'info@dkmamanagershelf.com',
        'SMTPPass' => 'wlyqpcvrjpadpcic',
        'SMTPTimeout'  => '60',
        'SMTPCrypto' => 'tls',
        'mailType'  => 'html',
        'charset'  => 'utf-8',
        'newline'  => "\r\n",
        'validate'  => TRUE
    );

    public function __construct()
    {
        $this->email = \Config\Services::email();
        $this->signupModel = new \App\Models\SignUpModel();
        $this->session = \Config\Services::session();
    }

    public function index()
    {
        echo view("commom/template/html-header");
        echo view('Pages/signup');
        echo view("commom/template/html-footer");
    }

    public function store()
    {
        $date = new \DateTime();

        try {
            $user = $this->signupModel->get_user_by_email($this->request->getPost('email'));
            $this->session->setFlashdata('error_msg', "Já existe um usuário cadastrado com esse email.");
            return redirect()->to("index.php/sign_up");
        } catch (\Throwable $th) {
        }

        try {
            $user = $this->signupModel->get_user_by_cpf_cnpj($this->request->getPost('cpf_cnpj'));
            $this->session->setFlashdata('error_msg', "Já existe um usuário cadastrado com esse CNPJ/CPF.");
            return redirect()->to("index.php/sign_up");
        } catch (\Throwable $th) {
        }

        $insert["name"]            = $this->request->getPost('name');
        $insert["email"]            = $this->request->getPost('email');
        $insert["pass"]             = md5($this->request->getPost('password'));
        $insert["cpf_cnpj"]         = $this->request->getPost('cpf_cnpj');
        $insert["company"]          = $this->request->getPost('company');
        $insert["created_at"]       = $date->format('Y-m-d H:i:s');
        
        $insert["email_confirm"]    = 0;
        $this->signupModel->insert($insert);

        $link = base_url("index.php/confirm_email/" . $insert["email"] . "/" . $date->getTimestamp());
        $send = $this->send_email($insert["email"], $insert["name"], $link);

        $this->session->setFlashdata('success_msg', "Você receberá um email com um link para confirmação de seu cadastro.");
        return redirect()->to("/");
    }

    public function confirm_email($email, $key)
    {
        $date = new \DateTime();
        $date->setTimestamp($key);

        try {
            $user = $this->signupModel->get_user_by_email($email);
            if ($user[0]["created_at"] == $date->format('Y-m-d H:i:s')) {
                $this->signupModel->confirm_email($email);
                $this->session->setFlashdata('success_msg', "Email confirmado com sucesso.");
               
            } else $this->session->setFlashdata('error_msg', "Chave de confirmação inválida.");
        } catch (\Throwable $th) {
            $this->session->setFlashdata('error_msg', $th->getMessage());
        }
        return redirect()->to("/");
    }

    public function send_email($recipient, $nome, $link)
    {
        $this->email->initialize($this->emailConfig);
        $this->email->setFrom($this->emailConfig["SMTPUser"], "DKMA Manager Shelf");
        $this->email->setTo($recipient);
        $this->email->setSubject('Confirme seu email - ManagerShelf');
        $this->email->attach(base_url('assets/img/brand/logo.png'), 'inline');
        $cid = $this->email->setAttachmentCID(base_url('assets/img/brand/logo.png'));

        $message_formated = view("email/message", [
            "title" => "Confirme seu email - ManagerShelf",
            "logo" => $cid,
            "nome" => $nome,
            "pre_button_text" => "OBA!! Que bom te ter por aqui. Bem-vindo ao ManagerShelf. Agora o próximo passo é confirmar seu email.",
            "button_link" => $link,
            "button_text" => "Confirme seu email",
            "after_button_text" => "Caso você não tenha feito nenhuma solicitação em nosso sistema, desconsidere esta mensagem.",
        ]);

        $this->email->setMessage($message_formated);
        $this->email->send();
        // echo $this->email->printDebugger();die();
    }
}
