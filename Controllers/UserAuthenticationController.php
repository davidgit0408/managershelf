<?php
namespace App\Controllers;

class UserAuthenticationController extends BaseController
{
    function __construct()
    {
        parent::__construct();

        // Load facebook oauth library 
        $this->load->library('facebook');

        // Load user model 
        $this->load->model('user');
    }

    public function index()
    {
        $userData = array();

        // Authenticate user with facebook 
        if ($this->facebook->is_authenticated()) {
            // Get user info from facebook 
            $fbUser = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,gender,picture');

            // Preparing data for database insertion 
            $userData['oauth_provider'] = 'facebook';
            $userData['oauth_uid']    = !empty($fbUser['id']) ? $fbUser['id'] : '';;
            $userData['first_name']    = !empty($fbUser['first_name']) ? $fbUser['first_name'] : '';
            $userData['last_name']    = !empty($fbUser['last_name']) ? $fbUser['last_name'] : '';
            $userData['email']        = !empty($fbUser['email']) ? $fbUser['email'] : '';
            $userData['gender']        = !empty($fbUser['gender']) ? $fbUser['gender'] : '';
            $userData['picture']    = !empty($fbUser['picture']['data']['url']) ? $fbUser['picture']['data']['url'] : '';
            $userData['link']        = !empty($fbUser['link']) ? $fbUser['link'] : 'https://www.facebook.com/';

            // Insert or update user data to the database 
            $userID = $this->user->checkUser($userData);

            // Check user data insert or update status 
            if (!empty($userID)) {
                $data['userData'] = $userData;

                // Store the user profile info into session 
                $this->session->set_userdata('userData', $userData);
            } else {
                $data['userData'] = array();
            }

            // Facebook logout URL 
            $data['logoutURL'] = $this->facebook->logout_url();
        } else {
            // Facebook authentication url 
            $data['authURL'] =  $this->facebook->login_url();
        }

        // Load login/profile view 
        $this->load->view('user_authentication/index', $data);
    }

    public function authenticate_user()
    {
        $auth = $this->facebook->login_url();
        //armazenando o estudo e o cenário na sessão 
        $this->session->set_userdata('id_company', $this->input->get('id_company'));
        $this->session->set_userdata('id_scenario', $this->input->get('id_scenario'));

        if (!empty($auth)) {
            //tela de login fb
            redirect($auth);
        } else {
            // $this->facebook->logout_url();
        }
    }

    public function login()
    {
        $authenticated = $this->facebook->is_authenticated();
        $company = $this->session->userdata('id_company');
        $scenario = $this->session->userdata('id_scenario');

        if ($authenticated) {
            $fbUser = $this->facebook->request('get', '/me?fields=id,first_name,last_name,email,link,gender,picture');
            $userData['oauth_provider'] = 'facebook';
            $userData['oauth_uid']    = !empty($fbUser['id']) ? $fbUser['id'] : '';;
            $userData['first_name']    = !empty($fbUser['first_name']) ? $fbUser['first_name'] : '';
            $userData['last_name']    = !empty($fbUser['last_name']) ? $fbUser['last_name'] : '';
            $userData['email']        = !empty($fbUser['email']) ? $fbUser['email'] : '';
            $userData['gender']        = !empty($fbUser['gender']) ? $fbUser['gender'] : '';
            $userData['picture']    = !empty($fbUser['picture']['data']['url']) ? $fbUser['picture']['data']['url'] : '';
            $userData['link']        = !empty($fbUser['link']) ? $fbUser['link'] : 'https://www.facebook.com/';

            //Verifica na table users_fb se usuario ja existe, caso exista atualiza os dados e se não existir cria novo usuário
            $userID = $this->user->checkUser($userData);

            if (isset($fbUser['id'])) {
                $users = $this->user->get_user_by_fb($fbUser['id']);
                if (!$users) {
                    //Inserindo usuário no database
                    $user = array(
                        'name' => $fbUser['first_name'] . ' ' . $fbUser['last_name'],
                        'email' => $fbUser['email'],
                        'pass' => $fbUser['id'],
                        'role' => 'client',
                        'img_url' => $fbUser['picture']['data']['url'],
                    );
                    $this->user->insert_user($user);
                    $new_user = $this->user->get_user_by_fb($fbUser['id']);
                    if (!$scenario || !$this->session->userdata('address')) {
                        redirect('admin/cadastro?id_company=' . $company . '&id_user=' . $new_user[0]['id']);
                    };
                }

                $this->session->set_userdata('id', $users[0]['id']);
                $this->session->set_userdata('facebook_id', $fbUser['id']);
                $this->session->set_userdata('role', 'client');
                $this->session->set_userdata('email', $fbUser['email']);
                $this->session->set_userdata('name', $users[0]['name']);
                $this->session->set_userdata('address', $users[0]['address']);

                if (!$this->session->userdata('address')) {
                    redirect('admin/cadastro?id_company=' . $company . '&id_user=' . $users[0]['id']);
                }
                if ($scenario) {
                    redirect('client/scenario?id_scenario=' . $scenario . '&id_company=' . $company);
                } else {
                    redirect('client/choose_scenario?id_company=' . $company . '&id_user=' . $users[0]['id']);
                }
            } else {
                //caso o usuario não permita o login com o facebook redireciona novamente para a pagina de login
                redirect('client/login?id_company=' . $company);
            }
        }
    }

    public function logout()
    {
        $company = $this->session->userdata('id_company');
        // Remove local Facebook session 
        $this->facebook->destroy_session();
        // Remove user data from session 
        $this->session->unset_userdata('userData');
        //Destruindo a sessão
        $this->session->sess_destroy();
        // Redirect to login page (facebook_logout_redirect_url = pagina de login client)
        redirect('client?id_company=' . $company);
    }
}
