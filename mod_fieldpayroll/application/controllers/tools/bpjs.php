<?php

class Bpjs extends CI_Controller {

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
	$this->generate_tempbpjs();
	echo "Generate BPJS Personal <br/>";
	$this->checkdata_bpjs();
	echo "Checkdata BPJS Personal <br/>";
    }
    
    
    function generate_tempbpjs(){
	$resultbpjs = $this->synchrondata->gettemp_bpjs();
	if($resultbpjs !=='empty'){		
		foreach($resultbpjs as $row){
	         $rowp = $this->synchrondata->getpersonal_frombpjs($row['FullName']);	
		 if($rowp !=='empty'){
			$record = array (
			"IDEmployee"=>$rowp->IDEmployee,
			"FullName"=>$row['FullName'],
			"MonthlySalary"=>$row['MonthlySalary'],			
			
			);	

		   $this->synchrondata->sendtotemp_bpjs($rowp->IDEmployee,$record);		

		}  

		}

	}	
	
   }

    
    function updating_bpjs() {
        ini_set('memory_limit', '-1'); // for unlimited size  
       // $gapok = 2441000;
       // $gapok = 2700000; // nilai bpjs berubah berdasarkan request hrd 20-03-2015
        $pointbpjs = 1/100;
        
        $result = $this->synchrondata->get_employee();
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            $no =0;
            foreach ($result as $row) {
               $no++;                
             	
               $gapok = $row['MonthlySalary'];
               $ktp = $row['NoKTP'];
               $nip = $row['IDEmployee'];
               $bpjs = $pointbpjs*$gapok;
                
               
               //echo $ktp.'-'.$nip.' - '.$row['FullName'].' - '.$bpjs.'<br/>';
               
               
                $record = array(                    
                    'BPJS' => $bpjs                    
                );
                     
              $this->synchrondata->update_monthly($nip, $record);
            }
        }
    }


  function checkdata_bpjs(){
    $result = $this->synchrondata->get_tmpbpjs();
    if($result !=='empty'){
	$pointbpjs = 0.5/100;	
	foreach ($result as $row){
		$gapok = $row['MonthlySalary'];
		$nip = $row['IDEmployee'];
		$fullname = $row['FullName'];
		$checkuser = $this->synchrondata->check_personalpayroll($nip);

		if($checkuser=='exist'){
		   $bpjs = $pointbpjs*$gapok;
		}else{
		   $bpjs = 0;
		} 
                
              // echo  $nip.' - '.$fullname.' - '.$bpjs.'<br/>';
                
             
	        $record = array(                    
                    'BPJS' => $bpjs                    
                );
	
		 	
	  	 $this->synchrondata->update_monthly($nip, $record);
                

	}

   }

 }

    

}

