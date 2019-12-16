<?php

//
class Updatedailysalary extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('synchrondata_model', 'synchrondata');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }
   

  function index(){
	$this->updating_dailysalary();
	echo 'update daily salary, finish';
   }
    
    
    function updating_dailysalary() {
        ini_set('memory_limit', '-1'); // for unlimited size  
        $result = $this->synchrondata->getpersonal_payroll();
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            $no =0;
            foreach ($result as $row) {
               $no++;                
     
                $nip = $row['IDEmployee'];
                $monthlysalary =  $row['MonthlySalary'];    
                $dailysalary = $monthlysalary/31; /* intruksi manajemen pertanggal 25-02-2015 untuk daily salary menjadi 31 hari */	     

                $record = array(   
                    'DailySalary' => $dailysalary,              

                );
                     
               $this->synchrondata->update_monthly($nip, $record);
            }
        }
    }

    

}
