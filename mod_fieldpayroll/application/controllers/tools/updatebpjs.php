

<?php

/*
  Request aryana 01-07-2015, perubahan point bpjs menjadi 1%
 */

class Updatebpjs extends CI_Controller {

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
        $this->updatedata();
        echo "Update point BPJS finish <br/>";
    }

    function updatedata() {
        $resultbpjs = $this->synchrondata->getpersonal_bpjs();
        $pointbpjs = $this->synchrondata->getpoint('bpjs');

        if ($resultbpjs !== 'empty') {
            foreach ($resultbpjs as $row) {
                $nip = $row['IDEmployee'];
               // $monthlysalary = $row['MonthlySalary'];
                
                /* gapok di konfirmasi aryana diratakan 
                 * seluruh karyawan 2.700.000 * 1%
                 * 15 Oktober 2015 
                 */                
                
                $gapok = 2700000;
                $bpjs = ($pointbpjs/100) * ($gapok);
                
                $record = array(                    
                    'BPJS' => $bpjs                    
                );
                     
              $this->synchrondata->update_monthly($nip, $record);
                
            }
        }
    }

}
