<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model', 'login');
        $this->load->model('personal_model', 'personal');
        $this->load->model('hits_model', 'hits');
        $this->hits_info();
    }

    public function index() {
        $this->session->sess_destroy();
        $this->load->view('login');
    }

    public function verification() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $rs_login = $this->login->validate_credential($username, $password);
        if ($rs_login != NULL) {
            $rs_personal = $this->personal->get_data($username);
            if ($rs_personal) {
                $fullname = $rs_personal->FullName;
                $email    = $rs_personal->EmailInternal;
            } else {
                $fullname = "not registered";
                $email    = "not registered";
            }
            $session = array(
                'sess_logged_in' => TRUE,
                'sess_userid' => $username,
                'sess_fullname' => $fullname,
                'sess_email' => $email
            );
            $this->session->set_userdata($session);
            $redirect = site_url();
            $output   = array("success" => "yes", 
                              "mesg"    => "access granted",
                              "redir"   => $redirect);
        } else {
            $redirect = site_url('login');
            $output   = array("success" => "no", 
                              "mesg"    => "access denied",
                              "redir"   => $redirect);
        }
        header('Content-Type: application/json', true);
        echo json_encode($output);
    }

    function logout() {
        //$this->logs->sys_log('logout', '');
        $this->session->sess_destroy();
        redirect('login', 'refresh');
    }

    public function hits_info() {
        $info = array(
            'remote_addr' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'uri_string' => $this->uri->uri_string(),
            'referrer' => $this->agent->referrer()
        );
        $this->hits->insert($info);
    }
    
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
