<?php

class Updatepaidloan extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('synchrondata_model', 'synchrondata');


        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        //echo 'test';
        $this->updatepaid();
        $this->calculateloan();
    }

    function updatepaid() {
        $result = $this->synchrondata->gettmpt();

        $no = 0;
        foreach ($result as $row) {
            $no++;

            $tmp = explode("'", $row['IDEmployee']);
            $postingdate = date('Y-m-d', strtotime($row['PostingDate']));
            $nip = $tmp[1];
            $flag = '1';

            $record = array(
                'Flag' => $flag
            );
            
           
            $this->synchrondata->update_loan_d($nip, $postingdate, $record);
             echo $no . ' -  ' . $nip . ' - ' . $postingdate . '<br/>';

            
        }
    }

    function calculateloan() {
        $result = $this->synchrondata->getloan_h();
        foreach ($result as $row) {
            $id = $row['ID'];
            $term = $row['Term'];
            $countterm = $this->synchrondata->getterm_detail($id);
            if ($term == $countterm) {

                $record = array(
                    "FlagPaid" => '1'
                );

                $this->synchrondata->update_loan_h($id, $record);
            }
        }
    }

}
