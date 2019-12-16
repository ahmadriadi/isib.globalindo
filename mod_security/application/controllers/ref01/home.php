<?php

//USER FOR LOGISTIC: PRINT
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('rootcouse_model', 'rootcouse');

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
        $this->load->view('ref01/home');
    }

    function getdatatable() {
        echo $this->rootcouse->getdata();
    }

    function addnew() {
        $data['default']['f01'] = ''; //RootName
              
        $data['url_post'] = site_url('ref01/home/addpost');
        $this->load->view('ref01/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'RootName', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $record = array('RootName' => $f01,
                             "AddedBy"=>$this->User,
                             "AddedDate"=>$this->Datetime,
                             "AddedIP"=>$this->Ip
                            );           

            $check = $this->rootcouse->getby_name($f01);
            if ($check == 'empty') {              
                $this->rootcouse->insert($record);
                $mesg = 'insert data success';
                $valid = 'true';
                $err_f01 = '';
            } else {
                $mesg = 'insert data failed :' . $f01 . ' already exist';
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
        $row = $this->rootcouse->getby_id($id);

        $data['default']['f01'] = $row->RootName;
        $data['url_post'] = site_url('ref01/home/editpost');
        $this->load->view('ref01/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'RootName', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            
            $record = array('RootName' => $f01,
                            "EditedBy"=>$this->User,
                            "EditedDate"=>$this->Datetime,
                            "EditedIP"=>$this->Ip
                    );
            
            $this->rootcouse->update($id, $record);
            $mesg = 'update data success';
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
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );
        
        $this->rootcouse->update($id,$record);
        $mesg = "Delete Data Success";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

}


