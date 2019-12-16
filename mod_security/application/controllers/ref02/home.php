<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('proot_model', 'proot');
        $this->load->model('historytable_model', 'historytable');
        $this->load->model('employee_model', 'employee');
        

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function anti_xss($source) {
        $f = stripslashes(strip_tags(htmlspecialchars($source, ENT_QUOTES)));
        return $f;
    }

    function index() {
        $this->load->view('ref02/home');
    }

    function getdatatable() {
        echo $this->proot->getdata();
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
        $data['default']['f01'] = ''; //IDEmployee
        $data['default']['f02'] = ''; //FullName
        $data['default']['f03'][1]['value'] = "1";
        $data['default']['f03'][1]['display'] = "Kapuk";
        $data['default']['f03'][1]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value'] = "2";
        $data['default']['f03'][2]['display'] = "Bitung";
        $data['default']['f04'] = ''; //Note

        $data['flagposition'] = 'add';
        $data['url_post'] = site_url('ref02/home/addpost');
        $this->load->view('ref02/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->input->post('f04');

            $record = array(
                'IDEmployee' => $f01,
                'RootSite' => $f03,
                'Note' => $f04,
                'AddedBy' => $this->User,
                'AddedDate' => $this->Datetime,
                'AddedIP' => $this->Ip
            );

            $rowcheck = $this->proot->checkdata($f01, $f03);
            $check = ($rowcheck == '' or $rowcheck == null) ? 'empty' : 'exist';
            if ($check == 'empty') {
                $this->proot->insert($record);
                $mesg = 'insert data, success';
                $valid = 'true';
                $err_f01 = '';
                $err_f03 = '';
                $err_f04 = '';
            } else {
                $mesg = 'insert data, failed userid :' . $f01 . ' already exist';
                $valid = 'false';
                $err_f01 = 'ERROR';
                $err_f03 = 'ERROR';
                $err_f04 = 'ERROR';
            }
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",      
                       "err_f03":"' . $err_f03 . '",      
                       "err_f04":"' . $err_f04 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->proot->getby_id($id);

        $data['default']['f01'] = $row->IDEmployee;
        $data['default']['f02'] = $row->FullName;
        $data['default']['f03'][1]['value'] = "1";
        $data['default']['f03'][1]['display'] = "Kapuk";
        $data['default']['f03'][1]['checked'] = "";
        $data['default']['f03'][2]['value'] = "2";
        $data['default']['f03'][2]['display'] = "Bitung";

        if ($row->RootSite == '1') {
            $data['default']['f03'][1]['checked'] = "CHECKED";
        } else if ($row->RootSite == '2') {
            $data['default']['f03'][2]['checked'] = "CHECKED";
        }

        $data['default']['f04'] = $row->Note;

        $data['flagposition'] = 'edit';
        $data['url_post'] = site_url('ref02/home/editpost');
        $this->load->view('ref02/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->input->post('f04');

            $record = array(
                'IDEmployee' => $f01,
                'RootSite' => $f03,
                'Note' => $f04,
                'EditedBy' => $this->User,
                'EditedDate' => $this->Datetime,
                'EditedIP' => $this->Ip
            );

            $this->historydata($id);
            $this->proot->update($id, $record);
            $mesg = 'update data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f03 = '';
            $err_f04 = '';
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",      
                       "err_f03":"' . $err_f03 . '",      
                       "err_f04":"' . $err_f04 . '"' .
                '}';
        echo $json;
    }

    function delete($id) {
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );

        $this->proot->update($id, $record);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }
    
    
    function historydata($id){
        $row = $this->proot->getby_id($id);
        $record = array(
            "IDEmployee"=>$row->IDEmployee,
            "RootSite"=>$row->RootSite,
            "Note"=>$row->Note,
            "AddedBy"=>$row->AddedBy,
            "AddedDate"=>$row->AddedDate,
            "AddedIP"=>$row->AddedIP,
            "EditedBy"=>$row->EditedBy,
            "EditedDate"=>$row->EditedDate,
            "EditedIP"=>$row->EditedIP,
            "DeleteBy"=>$row->DeleteBy,
            "DeleteDate"=>$row->DeleteDate,
            "DeleteIP"=>$row->DeleteIP,
            "DeleteFlag"=>$row->DeleteFlag,
            "HistBy"=>$this->User,
            "HistDate"=>$this->Datetime,
            "HistIP"=>$this->Ip,
            "IDTable"=>$id,
        );
        
        $this->historytable->insert_pmail($record);
        
        
    }

}

