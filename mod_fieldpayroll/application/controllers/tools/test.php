<?php

class test extends CI_Controller {

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
	echo 'test';
    }
    
    
    
    
    function updating_bpjs() {
        ini_set('memory_limit', '-1'); // for unlimited size  
        
        $gapok = 2441000;
        $pointbpjs = 0.5/100;
        
        $result = $this->synchrondata->get_employee();
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            $no =0;
            foreach ($result as $row) {
               $no++;                
             
               $ktp = $row['NoKTP'];
               $nip = $row['IDEmployee'];
               $bpjs = $pointbpjs*$gapok;
                
               
               echo $ktp.'-'.$nip.' - '.$row['FullName'].' - '.$bpjs.'<br/>';
               
               
                $record = array(                    
                    'BPSJ' => $bpjs                    
                );
                     
              $this->synchrondata->update_monthly($nip, $record);
            }
        }
    }

    

}
