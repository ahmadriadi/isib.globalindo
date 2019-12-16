<?php

class Restorepresence extends CI_Controller {

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
       $from = '2014-12-22';  
       $until = '2014-12-24';  
       $result = $this->synchron->getdata_presence($from,$until);
       $checkresult = ($result =='' or $result ==NULL)?'empty':'exist';
       if($checkresult !=='empty'){
           $no =0;
           foreach ($result as $row) {
               $no++;
               $id = $row['IDPresence'];   
               $presencedate = $row['PresenceDate']; 
               $status = $row['Description']; 
              
               
                $array1 = array(
                    "ManualIn"=>NULL,
                    "ManualOut"=>NULL,                      
                    "Note"=>NULL,                      
                    "CatatanProses"=>NULL,                      
                    "AddedBy"=>NULL,                      
                    "AddedDate"=>NULL,                      
                    "AddedIP"=>NULL                     
                );
                
                $array2 = array(
                    "ManualIn"=>NULL,
                    "Note"=>NULL,                      
                    "CatatanProses"=>NULL,                      
                    "AddedBy"=>NULL,                      
                    "AddedDate"=>NULL,                      
                    "AddedIP"=>NULL                     
                );
                
                $array3 = array(
                    "Note"=>NULL,                      
                    "CatatanProses"=>NULL,                      
                    "AddedBy"=>NULL,                      
                    "AddedDate"=>NULL,                      
                    "AddedIP"=>NULL                     
                );
                

               if($status =='P'){
                 $record = $array1;
               }else if($status =='A'){
                 $record = $array1;                   
               }else if($status =='LP'){
                 $record = $array2;    
               }else if($status =='OT'){
                 $record = $array1;    
               }else if($status =='OT'){
                 $record = $array1;    
               }else if($status =='NC' and $row['AddedBy']=='SYSTEM'){
                 $record = $array3;    
               }else if($status =='OL'){
                 $record = $array1;    
               }else if($status =='CL'){
                 $record = $array1;    
               }
             
                      
              $this->synchron->update_presence_by_id($id,$record);
               
           }
           
       }
       
        
    }

    
}
