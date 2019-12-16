<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('machine_model', 'machine');
        $this->load->model('menu_Model', 'menu');
        $this->load->model('employee_model', 'employee');

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
        $this->load->view('trx11/home');
    }

    function getdatatable() {
        echo $this->machine->getdata();
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



    function addnew() {
        $data['default']['f01'] = ''; //FullName
        $data['default']['f02'] = ''; //IDEmployee
        $data['default']['f03'] = ''; //DateTruncate
        $data['default']['f04'] = ''; //Note
     
        
        $data['flagposition'] = 'add';
        $data['url_post'] = site_url('trx11/home/addpost');
        $this->load->view('trx11/form', $data);
    }
    
    function checkdate(){
        $date = date('Y-m-d',  strtotime($this->anti_xss($this->input->post('f03'))));
        $checkdata = $this->machine->checkdata($date);
        if($checkdata !=='empty'){
            $this->form_validation->set_message('checkdate', 'Date Truncate already exist');
            return false;
            }else{
            return true;
        }
        
    }

    function addpost() {
        $this->form_validation->set_rules('f02', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f03', 'Date Truncate', 'required|callback_checkdate');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = date('Y-m-d',  strtotime($this->anti_xss($this->input->post('f03'))));
            $f04 = $this->anti_xss($this->input->post('f04'));

            $record = array(
                'IDEmployee' => $f02,
                'DateTruncate' => $f03,
                'Note' => $f04,
                'AddedBy' => $this->User,
                'AddedDate' => $this->Datetime,
                'AddedIP' => $this->Ip,
            );

            
          
            $this->machine->insert($record);
                
            $mesg = 'Insert data success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '", 
                       "err_f02":"' . $err_f02 . '", 
                       "err_f03":"' . $err_f03 . '", 
                       "err_f04":"' . $err_f04 . '"' .
                '}';
        echo $json;
    }

   
    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->machine->getby_id($id);
        $data['default']['f01'] = $row->FullName;
        $data['default']['f02'] = $row->IDEmployee;
        $data['default']['f03'] = $row->DateTruncate;
        $data['default']['f04'] = $row->Note;
        
        $data['flagposition'] = 'edit';
        $data['url_post'] = site_url('trx11/home/editpost');
        $this->load->view('trx11/form', $data);
    }

    function editpost() {
         $this->form_validation->set_rules('f02', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f03', 'Date Truncate', 'required|callback_checkdate');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = date('Y-m-d',  strtotime($this->anti_xss($this->input->post('f03'))));
            $f04 = $this->anti_xss($this->input->post('f04'));

            $record = array(
                'IDEmployee' => $f02,
                'DateTruncate' => $f03,
                'Note' => $f04,
                'AddedBy' => $this->User,
                'AddedDate' => $this->Datetime,
                'AddedIP' => $this->Ip,
            );

            
          
            $this->machine->update($id,$record);
                
            $mesg = 'update data success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '", 
                       "err_f02":"' . $err_f02 . '", 
                       "err_f03":"' . $err_f03 . '", 
                       "err_f04":"' . $err_f04 . '"' .
                '}';
        echo $json;
    }

    function delete($id) {
        $record = array(
            "DeleteBy"=>$this->User,
            "DeleteDate"=>$this->Datetime,
            "DeleteIP"=>$this->Ip,
            "DeleteFlag"=>'D',
        );
        
        
        $this->machine->update($id,$record);
        $mesg = "Delete Data, Success";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

}

