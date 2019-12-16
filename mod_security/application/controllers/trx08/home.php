<?php

//USER FOR LOGISTIC: CANCELLATION
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('authorization_model', 'authorization');
        $this->load->model('Employee_model', 'employee');
        

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->load->view('trx08/home');
    }

    function dataauthorization_cancel() {
        echo $this->authorization->datacancel();
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
               
        $data['url_post'] = site_url('trx08/home/addpost');
        $this->load->view('trx08/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->input->post('f01');
           
            $record = array('IDEmployee ' => $f01);           

            $rowlogin = $this->authorization->check_access_cancel($f01);
            $check = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
            if ($check == 'empty') {              
                $this->authorization->insert_cancel($record);
                $mesg = 'insert data success';
                $valid = 'true';
                $err_f01 = '';
            } else {
                $mesg = 'inserting data failed. userid :' . $f01 . ' already exist';
                $valid = 'false';
                $err_f01 = 'ERROR';
            }
        } else {
            $mesg = 'insert data failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '"' .
                '}';
        echo $json;
    }

 

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->authorization->get_by_id_cancel($id);

        $data['default']['f01'] = $row->IDEmployee;
        $data['default']['f02'] = $row->FullName;
        
        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('trx08/home/editpost');
        $this->load->view('trx08/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->input->post('f01');
            
            $record = array('IDEmployee ' => $f01); 

            $this->authorization->update_cancel($id, $record);
            $mesg = 'update data success';
            $valid = 'true';
            $err_f01 = '';
        } else {
            $mesg = 'update data failed';
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
        $this->authorization->delete_cancel($id);
        $mesg = "Delete Data Success";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

}


