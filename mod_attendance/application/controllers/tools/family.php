<?php

class Family extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Synchrondata_model', 'syncron');
        date_default_timezone_set("Asia/Jakarta");
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->spouse();
        
    }
    
    function spouse(){
       $result = $this->syncron->get_pblpersonal();
       $checkresult = ($result =='' or $result ==NULL)?'empty':'exist';
       if($checkresult !=='empty'){
           foreach ($result as $row) {
               $nip = $row['IDEmployee'];   
               
               $record = array(
                   "IDEmployee"=>$nip,
                   "Name"=>$row['CoupleName'],                                 
                   "FamilyMember"=>'spouse'                    
                             
               );      
               
               if(($row['CoupleName']=='-') or ($row['CoupleName']=='') or ($row['CoupleName']==NULL)){
               }else{                   
                     $this->syncron->checkspouse($nip,$record);
               
               }
           }
           
       }
       
        
    }

    
}

