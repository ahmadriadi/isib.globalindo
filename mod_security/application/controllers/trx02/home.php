<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model', 'login');
    }


    function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    } 	
	

    function index() {
        $data['default']['userid'] = $this->session->userdata('sess_userid');
        $data['default']['fullname'] = $this->session->userdata('sess_fullname');
	$data['url_post'] = site_url('trx02/home/chgpasswd_process');
        $this->load->view('trx02/home', $data);
    }

    function chgpasswd_process() {
        $valid = 'false';
        $error_oldpwd = '** error old password **';
        $error_newpwd = '** error new password **';
        $error_confirmpwd = '** error confirm password **';

       
        $this->form_validation->set_rules('oldpassword', 'Old Password', 'required|callback_check_oldpwd');
        $this->form_validation->set_rules('newpassword', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'required|callback_check_confirmpwd');

        if ($this->form_validation->run() == TRUE) {
            $newpwd = $this->anti_xss($this->input->post('newpassword'));
            $user   = $this->anti_xss($this->input->post('userid'));
            $md5pwd = md5($user . $newpwd);
            $record = array(
                'Password' => $md5pwd
            );
            $this->login->update($user, $record);
            $valid = 'true';
            $error_oldpwd = "";
            $error_newpwd = "";
            $error_confirmpwd = "";
        } else {
            $valid = 'false';
            $error_oldpwd = form_error('oldpassword');
            $error_newpwd = form_error('newpassword');
            $error_confirmpwd = form_error('confirmpassword');
        }
        $json = '{' .
                '"valid":"' . $valid . '",' .
                '"error_oldpwd":"' . $error_oldpwd . '",' .
                '"error_newpwd":"' . $error_newpwd . '",' .
                '"error_confirmpwd":"' . $error_confirmpwd . '"' .
                '}';

        echo $json;
    }

    function check_oldpwd() {
        $user       = $this->anti_xss($this->input->post('userid'));
        $pass       = $this->anti_xss($this->input->post('oldpassword'));
        $useraccess = $this->login->validate_credential($user, $pass);
        if (!$useraccess) {
            $this->form_validation->set_message('check_oldpwd', "User :".$user." Old Password not match.");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_confirmpwd() {
        $newpwd = $this->anti_xss($this->input->post('newpwd'));
        $confirmpwd = $this->anti_xss($this->input->post('confirmpwd'));
        if ($newpwd != $confirmpwd) {
            $this->form_validation->set_message('check_confirmpwd', "New Password and Confirm Password not match!.");
            return FALSE;
        } else {
            return TRUE;
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
