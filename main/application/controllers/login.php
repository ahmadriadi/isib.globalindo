<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('login_model', 'login');
        $this->load->model('userlogin_model', 'userlogin');
        $this->load->model('useractivation_model', 'activation');
        $this->load->model('personal_model', 'personal');
        $this->load->model('hits_model', 'hits');
        $this->load->model("menu_model", "mnu");
        //$this->maintenance();
        $this->hits_info();
    }

    function anti_xss($source) {
        $f = stripslashes(strip_tags(htmlspecialchars($source, ENT_QUOTES)));
        return $f;
    }

    public function index() {
        if ($this->session->userdata('sess_logged_in') == TRUE) {
            redirect("main");
        } else {
            $this->load->view('login');
        }
        //echo "Please wait! . . .";
        //$this->load->view('maintenance');
    }

    public function verification() {
        $username = $this->anti_xss($this->input->post('username'));
        $password = $this->anti_xss($this->input->post('password'));
        $rs_login = $this->login->validate_credential($username, $password);
        if ($rs_login != NULL) {
            $rs_personal = $this->personal->get_data($username);
            $rs_pp = $this->login->get_pictureprofile($username);
            $rs_role = $this->userlogin->check_username($username);

            if ($rs_role) {
                $role = $rs_role->Role;
            } else {
                $role = '';
            }

            if ($rs_pp) {
                $img = explode("..", $rs_pp->SourceImage);
                $pp = base_url() . $img[0] . $img[1];
            } else {
                $pp = '0';
            }

            if ($rs_personal) {
                $fullname = $rs_personal->FullName;
                $email = $rs_personal->InternalEmail;
                $emaileks = $rs_personal->ExternalEmail;
                $location = $rs_personal->Location;
                $position = $rs_personal->Position;
                if ($location == 'KAPUK') {
                    $loca = '1';
                } else if ($location == 'BITUNG') {
                    $loca = '2';
                } else {
                    $loca = $location;
                }

                $site = $loca;
            } else {
                $fullname = "not registered";
                $email = "not registered";
                $site = "1";
                $position = '';
            }
            //$enroll     = $this->login->get_enroll($username)->row()->IDCard;
            $rowenroll = $this->login->get_enroll($username);
            if ($rowenroll !== 'empty') {
                $enroll = $rowenroll->IDCard;
            } else {
                $enroll = '0';
            }

//            $numlog         = $this->login->get_log($username)->row()->Log;
//            $updlog         = array("Log" => $numlog+1);
//            $this->login->update_log($username,$updlog);

            $checkmother = $this->personal->get_mother($username);
            if ($checkmother == 'empty') {
                $recordfamily = array(
                    "F3" => '0'
                );
                $this->personal->update_flag($username, $recordfamily);
            }

            $rowpersonal = $this->personal->get_datapersonal($username);
            $dept = trim($rowpersonal->Department);
            $position = trim($rowpersonal->Position);
            $flagleader = $rowpersonal->F2f2;

            if ($dept == '7' and $position == 'STAFF' and $flagleader == '0') {
                $record = array(
                    "F2" => 0,
                    "F2f1" => 0,
                    "F2f2" => 0
                );
                $this->personal->update_employee($rowpersonal->ID, $record);
            }


            $session = array(
                'sess_logged_in' => TRUE,
                'sess_userid' => $username,
                'sess_enroll' => $enroll,
                'sess_fullname' => $fullname,
                'sess_email' => $email,
                'sess_emaileks' => $emaileks,
                'sess_foto' => $pp,
                'sess_site' => $site,
                'sess_role' => $role,
                'sess_position' => $position,
                'sess_mother' => $checkmother,
                'sess_base_url' => base_url()
            );

            $this->session->set_userdata($session);
            $redirect = site_url();
            $output = array(
                "success" => "yes",
                "mesg" => "access granted",
                "redir" => $redirect);
//            $this->buildmenu();
        } else {
            $redirect = site_url('login');
            $output = array(
                "success" => "no",
                "mesg" => "access denied",
                "redir" => $redirect);
        }
        header('Content-Type: application/json', true);
        echo json_encode($output);
    }

    function logout() {
        //$this->logs->sys_log('logout', '');
        $this->session->sess_destroy();
        redirect('login', 'refresh');
    }

    public function lostpassword() {
        $this->session->sess_destroy();
        $this->session->set_flashdata('lost-msg', '');
        $this->load->view('lostpassword');
    }

    public function sendpassword() {

        $this->session->set_flashdata('lost-msg', '');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('email2', 'Email Confirmation', 'required|valid_email|callback_check_confirm_email');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('lost-msg', 'Parameter required');
            $this->load->view('lostpassword');
        } else {
            $username = $this->input->post('username');
            $email = $this->input->post('email');
            $email2 = $this->input->post('email2');
            $rs_userlogin = $this->userlogin->check_username($username);
            if ($rs_userlogin) {
                $rs_personal = $this->personal->get_data_public($username);
                if ($rs_personal) {
                    $EmailIn = $rs_personal->EmailInternal;
                    $EmailEx = $rs_personal->EmailExternal;
                    if (($email == $EmailIn) or ( $email == $EmailEx)) {
                        $this->load->helper('string');
                        $password = random_string('alnum', 32);
                        $messages = "Click <a href='" . site_url('login/activation') . "/" . urlencode($username) . "/" . urlencode($email) . "/" . urlencode($password) . "'>Here...</a> to reset your password";
                        if ($this->sendlmail($email, 'activation key for user ' . $username, $messages)) {
                            $record = array('Username' => $username, 'Email' => $email, 'Token' => $password);
                            $this->activation->insert($record);
                            $data['default']['msg-content'] = "The activation key has been sent to your email";
                        } else {
                            $data['default']['msg-content'] = "Error : Can't sent activation key to your email";
                        }
                    } else {
                        $data['default']['msg-content'] = "Error : Email <strong>" . $email . "</strong> is not listed in our employee database";
                    }
                } else {
                    $data['default']['msg-content'] = "Error : Username <strong>" . $username . "</strong> is not listed in our employee database";
                }
            } else {
                $data['default']['msg-content'] = "Error : Username <strong>" . $username . "</strong> is not listed in our security database";
            }
            // 
            $data['default']['msg-header'] = "Activation Key";
            $data['default']['msg-continue'] = "Need more info";
            $this->load->view('page/message', $data);
        }
    }

    public function check_confirm_email() {
        $email1 = $this->input->post('email');
        $email2 = $this->input->post('email2');
        if ($email1 != $email2) {
            $this->form_validation->set_message('check_confirm_email', "Email Confirmation not match!.");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function activation($username = '', $email = '', $token = '') {
        $email = urldecode($email);
        $rs_activation = $this->activation->verify($username, $email, $token);
        if ($rs_activation) {
            $this->load->helper('string');
            $password = random_string('alnum', 5);
            $messages = "<p>Username : " . $username . "<br>Password : " . $password . "</p>";
            if ($this->sendlmail($email, 'Password for user ' . $username, $messages)) {
                $password = md5($username . $password);
                $record = array('Username' => $username, 'Password' => $password);
                $this->userlogin->update($username, $record);
                $data['default']['msg-content'] = "Your password has been sent to your email";
            } else {
                $data['default']['msg-content'] = "Error : Can't sent password to your email";
            }
        } else {
            $data['default']['msg-content'] = "Error : Activation key is invalid!";
        }
        $data['default']['msg-header'] = "Reset Password";
        $data['default']['msg-continue'] = "Need more info";
        $this->load->view('page/message', $data);
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

    public function sendgmail($sendto = '', $subject = '', $message = '') {
        $email_config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => '465',
            'smtp_user' => 'info.testerdata@gmail.com',
            'smtp_pass' => 'b6879ggl',
            'mailtype' => 'html',
            'starttls' => true,
            'newline' => "\r\n",
            'crlf' => "\r\n"
        );

        $this->load->library('email', $email_config);
        $this->email->from('info.testerdata@gmail.com', 'SYSTEM');
        $this->email->to($sendto);
        $this->email->subject($subject);
        $this->email->message($message);
        if (!$this->email->send()) {
            return FALSE;
        }
        return TRUE;
    }

    public function sendlmail($sendto = '', $subject = '', $message = '') {
        $email_config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => '465',
            'smtp_user' => 'info.testerdata@gmail.com',
            'smtp_pass' => 'b6879ggl',
            'mailtype' => 'html',
            'starttls' => true,
            'newline' => "\r\n",
            'crlf' => "\r\n"
        );

        $this->load->library('email', $email_config);
        $this->email->from('info.testerdata@gmail.com', 'SYSTEM');
        $this->email->to($sendto);
        $this->email->subject($subject);
        $this->email->message($message);
        if (!$this->email->send()) {
            return FALSE;
        }
        return TRUE;
    }

    function ceksesi() {
        echo $this->session->userdata("sess_user_menu");
    }

//    build menu
    function buildmenu($iduser) {
//        $iduser = $this->session->userdata('sess_userid');
        $ghead = $this->mnu->get_head()->result();
        $i = rand(0, 100);
        $menu = "q";
        foreach ($ghead as $h) {
            $i++;
            $access = $this->mnu->get_access($h->IDMenu, $iduser)->row()->Access;
//            $access = "";
            if ($access != "0") {
                $menu .= "<li class='hasSubmenu glyphicons $h->MenuIcon'>";
                $menu .= "<a data-toggle='collapse' href='#mnu_systemtea$i'><i></i><span> $h->MenuDesc </span></a>";
                $menu .= $this->buildchild($i, $h->IDMenu, $iduser);
                $jmlchild = $this->mnu->get_jml_child($h->IDMenu);
                $menu .= "<span class='count'>$jmlchild</span>";
                $menu .= "</li>";
            }
        }
        return $menu;
//        echo "<br><xmp width='100'> $menu </xmp>";
    }

    function buildchild($i = NULL, $idparent, $iduser) {
        $tchild = $this->mnu->get_child($idparent)->result();
        $int = rand(5000, 6000);
//        $iduser = $this->session->userdata('sess_userid');
//        print_r($tchild);

        if ($tchild != NULL) {

            $child = "<ul class='collapse' id='mnu_systemtea$i' >";
            foreach ($tchild as $c) {
                $access = $this->mnu->get_access($c->IDMenu, $iduser)->row()->Access;
                $int++;
                if ($access != '0') {
                    if ($c->HasSubMenu == 1) {
                        $child .= "<li class='hasSubmenu'>";
                        $child .= "<a data-toggle='collapse' href='#mnu_systemtea$int'><span>$c->MenuDesc </span></a>";
                        $child .= $this->buildchild($int, $c->IDMenu, $iduser);
                        $jmlchild = $this->mnu->get_jml_child($c->IDMenu);
                        $child .= "<span class='count'>$jmlchild</span>";
                        $child .= "</li>";
                    }
                    if ($c->HasSubMenu == 0) {
                        $cek_this_parent = $this->mnu->get_menu($idparent)->row()->IDParent;
                        if ($cek_this_parent == 0) {
                            $child .= "<li>";
                            $child .= "<ul>";
                            $child .= "<li>";
                            $child .= "<a url-mod='$c->URLMod' url-det='$c->URLDet' ><span> $c->MenuDesc </span></a>";
                            $child .= "</li>";
                            $child .= "</ul>";
                            $child .= "</li>";
                        } else {
                            $child .= "<li>";
                            $child .= "<a url-mod='$c->URLMod' url-det='$c->URLDet' ><span> $c->MenuDesc </span></a>";
                            $child .= "</li>";
                        }
                    }
                }
            }
            $child .= "</ul>";
            return $child;
        }
    }

    function maintenance() {
        $this->load->view("maintenance");
    }

   

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

