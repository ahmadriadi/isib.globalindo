<?php

//OVERTIME
class Actualpresence extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('report_model', 'report');
        date_default_timezone_set("Asia/Jakarta");
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->updatepresence();
        
    }
    
    function updatepresence(){
       $from = date('2014-07-22');
       $until = date('2014-07-24');
       $result = $this->report->process_actual($from,$until);
       $checkresult = ($result =='' or $result ==NULL)?'empty':'exist';
       if($checkresult !=='empty'){
           foreach ($result as $row) {
               $nip = $row['IDEmployee'];   
               $date = $row['PresenceDate']; 
               
               $record = array(                
                   "ManualIn"=>NULL,
                   "ManualOut"=>NULL,
                   "Description"=>'A',
                   "Note"=>NULL,
               );  
                      
               $this->report->update_presence($nip,$date,$record);
               
           }
           
       }
       
        
    }

    
}
