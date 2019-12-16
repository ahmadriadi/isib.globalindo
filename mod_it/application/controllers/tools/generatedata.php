<?php

class Generatedata extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('generatedata_model', 'generate');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->gendata();
    }

    function gendata() {

        $itemcode = 'KYB';
        $masterdata = 'IT-KYB-';

        for ($i = 0; $i <= 110; $i++) {

            $record = array(
                "CounterCode" => $masterdata . $i,
                "ItemCode" => $itemcode,
            );
            
            $this->generate->checkmst01($itemcode,$masterdata.$i,$record);
        }
    }

}
