<?php

class Repairemail extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('repaireamil_model', 'repairemail');
        date_default_timezone_set("Asia/Jakarta");
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->dataemail1();
        $this->dataemail2();
        $this->dataemail3();
        
    }
    
    function dataemail1(){
       $result = $this->repairemail->get_email_pbl('trias.loc');
       $checkresult = ($result =='' or $result ==NULL)?'empty':'exist';
       if($checkresult !=='empty'){
           foreach ($result as $row) {
            $id= $row['ID'];   
            $nip= $row['IDEmployee'];   
            $emaillama = $row['InternalEmail'];   
            $cluster = explode('@', $emaillama);
            $emailbaru = $cluster[0].'@tis.loc';
            
            //echo 'ID :'.$id.' - '.$nip.' Email Lama :'.$emaillama.' Email Baru :'.$emailbaru.'<br/>';
           
            $record = array(
                "InternalEmail"=>$emailbaru
                
            );
            
            $this->repairemail->update_email_pbl($id,$record);
            
           }
           
       }
       
        
    }
    function dataemail2(){
       $result = $this->repairemail->get_email_emp_h('trias.loc');
       $checkresult = ($result =='' or $result ==NULL)?'empty':'exist';
       if($checkresult !=='empty'){
          foreach ($result as $row) {
            $id= $row['ID'];   
            $nip= $row['IDEmployee'];   
            $emaillama = $row['EmailInternal'];   
            $cluster = explode('@', $emaillama);
            $emailbaru = $cluster[0].'@tis.loc';
            
            //echo 'ID :'.$id.' - '.$nip.' Email Lama :'.$emaillama.' Email Baru :'.$emailbaru.'<br/>';
           
            $record = array(
                "EmailInternal"=>$emailbaru
                
            );
            
            $this->repairemail->update_email_emp_h($id,$record);
            
           }
           
       }
       
        
    }
    function dataemail3(){
       $result = $this->repairemail->get_email_emp_d('trias.loc');
       $checkresult = ($result =='' or $result ==NULL)?'empty':'exist';
       if($checkresult !=='empty'){
           foreach ($result as $row) {
            $id= $row['ID'];   
            $nip= $row['IDEmployee'];   
            $emaillama = $row['EmailInternal'];   
            $cluster = explode('@', $emaillama);
            $emailbaru = $cluster[0].'@tis.loc';
            
            //echo 'ID :'.$id.' - '.$nip.' Email Lama :'.$emaillama.' Email Baru :'.$emailbaru.'<br/>';
           
            $record = array(
                "EmailInternal"=>$emailbaru
                
            );
            
            $this->repairemail->update_email_emp_d($id,$record);
           }
           
       }
       
        
    }

    
}

