<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Userparam_model', 'parameterdata');
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
        $this->load->view('trx05/home');
    }

    function dataparameterdata() {
        echo $this->parameterdata->dataparam();
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
                        'value' => $row->IDEmployee,
                        'idemployee' => $row->IDEmployee
                    );
                }
            } else {
                $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                    'value' => $row->IDEmployee,
                    'idemployee' => $row->IDEmployee
                );
            }
        }
        echo json_encode($data);
    }

    function addnew() {
        $data['default']['f01'] = ''; //Code
        $data['default']['f02'] = ''; //ParamValue
        $data['default']['f03'] = ''; //Note

        $data['url_post'] = site_url('trx05/home/addpost');
        $this->load->view('trx05/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'Code', 'required');
        $this->form_validation->set_rules('f02', 'IDEmployee', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));

            $record = array(
                'IDParam' => $f01,
                'ParamValue' => $f02,
                'Note' => $f03
            );


            $this->parameterdata->insert($record);
            $mesg = 'insert data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",                      
                       "err_f02":"' . $err_f02 . '",                      
                       "err_f03":"' . $err_f03 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->parameterdata->get_by_id($id);

        $data['default']['f01'] = $row->IDParam;
        $data['default']['f02'] = $row->ParamValue;
        $data['default']['f03'] = $row->Note;
        $data['url_post'] = site_url('trx05/home/editpost');
        $this->load->view('trx05/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Code', 'required');
        $this->form_validation->set_rules('f02', 'IDEmployee', 'required');
        if ($this->form_validation->run() == TRUE) {
	     $id = $this->session->userdata('id');	
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));

            $record = array(
                'IDParam' => $f01,
                'ParamValue' => $f02,
                'Note' => $f03
            );


            $this->parameterdata->update($id,$record);
            $mesg = 'update data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",                      
                       "err_f02":"' . $err_f02 . '",                      
                       "err_f03":"' . $err_f03 . '"' .
                '}';
        echo $json;
    }

    function delete($id) {

        $this->parameterdata->delete($id);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

}
