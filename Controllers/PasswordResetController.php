<?php

namespace App\Controllers;

class PasswordResetController extends BaseController {

    private $signupModel;
    private $email;
    private $session;

    public $emailConfig = array(
        'protocol' => 'smtp',
        'SMTPHost' => 'smtp.gmail.com',
        'SMTPPort' => 587,
        'SMTPUser' => 'info@dkmamanagershelf.com',
        'SMTPPass' => 'MRd2021**a',
        'SMTPTimeout'  => '60',
        // 'SMTPCrypto' => 'tls',
        'mailType'  => 'html',
        'charset'  => 'utf-8',
        'newline'  => "\r\n",
        'validate'  => TRUE
    );

    public function __construct() {
        $this->email = \Config\Services::email();
        $this->session = \Config\Services::session();
        $this->request = service('request');
        $this->passwordResetModel = new \App\Models\PasswordResetModel();
	}

    public function send_email($recipient, $link){
        $this->email->initialize($this->emailConfig);
        $this->email->setFrom($this->emailConfig["SMTPUser"], "DKMA Manager Shelf");
        $this->email->setTo($recipient);
        $this->email->setSubject('Recuperação de senha - ManagerShelf');
        $this->email->attach(base_url('assets/img/brand/logo.png'), 'inline');
        $cid = $this->email->setAttachmentCID(base_url('assets/img/brand/logo.png'));


        $message_formated = view("email/message.php", [
            "title" => "Recuperação de senha - ManagerShelf",
            "logo" => $cid,
            "nome" => $recipient,
            "pre_button_text" => "Você solicitou uma redefinição de senha em nosso sistema.",
            "button_link" => $link,
            "button_text" => "Clique aqui para redefinir sua senha",
            "after_button_text" => "Caso você não tenha feito nenhuma solicitação em nosso sistema, desconsidere esta mensagem.",
        ]);

        $this->email->setMessage($message_formated);
        $this->email->send();
        // echo $this->email->printDebugger();
    }

    public function reset_pass_request_view(){
        echo view("commom/template/html-header");
		echo view('Pages/reset_pass_request');
		echo view("commom/template/html-footer");
    }

    public function insert_reset_key(){
        $date = new \DateTime();

        $insert["reset_key"]    = md5($date->getTimestamp());
        $insert["user_email"]   = $this->request->getPost('email');
        $insert["created_at"]   = $date->format('Y-m-d H:i:s');
        $this->passwordResetModel->insert_reset_key($insert);

        $link = base_url("index.php/reset/?key=".$insert["reset_key"]);
        $send = $this->send_email($insert["user_email"], $link);

        $this->session->setFlashdata('success_msg', "Você receberá um email com um link para redefinição de sua senha.");
        return redirect()->to(base_url("index.php/password_reset/"));
    }

    public function reset_pass_view(){
        $key = $this->request->getGet('key');
        try {
            $email = $this->passwordResetModel->check_reset_key($key)["user_email"];
        } catch (\Throwable $th) {
            if(!$this->session->getFlashdata('error_msg')) $this->session->setFlashdata('error_msg', $th->getMessage());
            $email = null;
        }
        echo view("commom/template/html-header");
		echo view('Pages/reset_pass', array("email"=>$email));
		echo view("commom/template/html-footer");
    }

    public function update_password(){
        $old_pass = $this->request->getPost('password_old');
        $data["pass"]   = md5($this->request->getPost('password'));
        $data["email"]  = $this->request->getPost('email');

        try {
            $this->passwordResetModel->update_password($data, $old_pass);
        } catch (\Throwable $th) {
            $this->session->setFlashdata('error_msg', $th->getMessage());
            return redirect()->to(base_url("index.php/password_reset/reset_pass_view"));
        }

        $this->session->setFlashdata('success_msg', "Senha redefinida com sucesso!");
        return redirect()->to(base_url());
    }
}