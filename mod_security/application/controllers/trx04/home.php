<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Userlogin_model', 'userlogin');
        $this->load->model('Menu_Model', 'menu');
        $this->load->model('Employee_model', 'employee');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }	

    function index() {
        $this->load->view('trx04/home');
    }

    function datauserlogin() {
        echo $this->userlogin->datalogin();
    }

    function autocomplete_employee() {
        $result = $this->employee->find_employee_active();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                           'fullname' => strtoupper($row->FullName)
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }		

    function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->search_employee($q);
        $data['response'] = 'true';
        $data['message'] = array();
        foreach ($result->result() as $row) {
            $status = $row->Status;
            $now = $row->Sekarang;
            $lock = $row->BatasFilter;

            if ($status == 'P') {
                if ($now <= $lock) {
                    $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                        'value' => $row->FullName,
                        'idemployee' => $row->IDEmployee
                    );
                }
            } else {
                $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                    'value' => $row->FullName,
                    'idemployee' => $row->IDEmployee
                );
            }
        }
        echo json_encode($data);
    }

    function addnew() {
        $data['default']['f01'] = ''; //IDEmployee
        $data['default']['f02'] = ''; //FullName
        $data['default']['f03'][1]['value'] = "1";
        $data['default']['f03'][1]['display'] = "No";      
	$data['default']['f03'][1]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value'] = "0";
        $data['default']['f03'][2]['display'] = "Yes";
        
        
        $data['flagposition'] = 'add';
        $data['url_post'] = site_url('trx04/home/addpost');
        $this->load->view('trx04/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $commonuser = $this->anti_xss($this->input->post('f03'));
            $f02 = md5($f01 . '123');

            $record = array(
                'Username' => $f01,
                'Password' => $f02,
                'Status' => 'A',
                'Role' => '0',
                'Log' => '0'
            );

            $rowlogin = $this->userlogin->check_username($f01);
            $check = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
            if ($check == 'empty') {
                if($commonuser =='0'){
                     $this->add_a_user($f01);
                }
               
                $this->userlogin->insert($record);
		$this->userlogin->update_modpublic();
                $mesg = 'insert data, success';
                $valid = 'true';
                $err_f01 = '';
            } else {
                $mesg = 'insert data, failed userid :' . $f01 . ' already exist';
                $valid = 'false';
                $err_f01 = 'ERROR';
            }
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '"' .
                '}';
        echo $json;
    }

    function add_a_user($iduser) {
        $menu = $this->menu->get_menu()->result();
        $button = $this->menu->get_button()->result();
        $i = 0;
        foreach ($menu as $m) {
            $i++;
            $rec = array("IDUser" => $iduser, "IDMenu" => $m->IDMenu, "Access" => "0");
            $this->menu->add_access($rec);
            //echo $i . $m->MenuDesc . " OKE <br>";
        }
        $o = 0;
        foreach ($button as $b) {
            $o++;
            $recbtn = array("IDUser" => $iduser, "IDMenu" => $b->IDMenu, "IDButton" => $b->IDButton, "Access" => "0");
            $this->menu->add_btnaccess($recbtn);
            //echo $o . $b->IDMenu . $b->ButtonDesc . " OKE <br>";
        }
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->userlogin->get_by_id($id);

        $data['default']['f01'] = $row->Username;
        $data['default']['f02'] = $row->FullName;
        $data['default']['f03'][1]['value'] = "1";
        $data['default']['f03'][1]['display'] = "No";
        $data['default']['f03'][1]['checked'] = "";
        $data['default']['f03'][2]['value'] = "0";
        $data['default']['f03'][2]['display'] = "Yes";

        if ($row->Log == '1') {
            $data['default']['f08'][1]['checked'] = "CHECKED";
        } else if ($row->Log == '0') {
            $data['default']['f08'][2]['checked'] = "CHECKED";
        }
        
        
        $data['flagposition'] = 'edit';
        $data['url_post'] = site_url('trx04/home/editpost');
        $this->load->view('trx04/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = md5($f01 . '123');

            $record = array(
                'Username' => $f01,
                'Password' => $f02
            );

            $this->userlogin->update($f01, $record);
	    $this->userlogin->update_modpublic();
	
            $mesg = 'update data, success';
            $valid = 'true';
            $err_f01 = '';
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '"' .
                '}';
        echo $json;
    }

    function delete($id) {
        $this->userlogin->delete($id);
        $mesg = "Delete Data, Success";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

}

