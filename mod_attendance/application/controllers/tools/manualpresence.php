<?php

/*Intruksi Remalya untuk kebutuhan ibu Intan dalam 
 * penggajian staff periode 24 September 2014
 * data absensi actualin di masukan ke manualin, dan
 * data absensi actualout, di masukan berdasarakan jam kerja hari ini (24 septemnenr 2014) 
*/
class Manualpresence extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Synchrondata_model', 'synchron');
        date_default_timezone_set("Asia/Jakarta");
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->updatepresence();
        
    }
    
    function updatepresence(){
       $date = '2014-09-24';  
       $result = $this->synchron->getpresence_oncondition($date);
       $checkresult = ($result =='' or $result ==NULL)?'empty':'exist';
       if($checkresult !=='empty'){
           $no =0;
           foreach ($result as $row) {
               $no++;
               $nip = $row['IDEmployee'];   
               $name = $row['FullName'];   
               $presencedate = $row['PresenceDate']; 
               $actualin = $row['ActualIn']; 
               $manualout  = '2014-09-24 16:00:00';  
               
               //echo $no.' - '.$nip.' - '.$name.' - '.$presencedate.' - '.$actualin.'<br/>';
               
               $record = array(                
                   "ManualIn"=>$actualin,
                   "ManualOut"=>$manualout,
                   "Description"=>'MP',
                   "Note"=>'(GENERATE BY SYSTEM)',
               );  
                      
              $this->synchron->update_presence($nip,$presencedate,$record);
               
           }
           
       }
       
        
    }

    
}
