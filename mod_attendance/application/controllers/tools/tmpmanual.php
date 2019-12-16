<?php

class Tmpmanual extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Synchrondata_model', 'syncron');
        date_default_timezone_set("Asia/Jakarta");
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->createmanual();
        
    }
    
    function createmanual(){
       $result = $this->syncron->get_tmpmanual();
       if($result !=='empty'){
           foreach ($result as $row) {
               $nip = $row['IDEmployee'];                  
               $presencedate = date('Y-m-d',  strtotime($row['PresenceDate']));   
               $catatan = $row['Note'];
               
               echo $nip.' - '.$presencedate.' - '.$catatan.'</br>';
               
             
               $rtime = $this->syncron->getworktime($presencedate);
                
                $record = array(
                    'IDEmployee' => $nip,
                    'ManualIn' => $presencedate.' '.$rtime->WorkIn,             
                    'ManualOut' => $presencedate.' '.$rtime->WorkOut,             
                    'Description' =>'MP',             
                    'Note' =>'GENERATE BY SYSTEM',             
                    'CatatanProses' =>$catatan,             
                  );    
                                
                 $this->syncron->updatefor_manual($nip,$presencedate,$record);
               
               
           }
           
       }
       
        
    }

    
}

