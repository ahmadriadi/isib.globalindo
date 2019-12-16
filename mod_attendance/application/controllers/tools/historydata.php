<?php

//OVERTIME
class Historydata extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('historydata_model', 'history');
        date_default_timezone_set("Asia/Jakarta");
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->get_his1();
	$this->get_his2();
    }
   function get_his1(){
        $result = $this->history->history1();
        $checkresult = ($result == '' or $result == NULL) ? 'empty' : 'exist';
        if($checkresult=='exist'){
	 	foreach ($result as $row){
		    
			$record = array(
                         "NIPLama"=>$row['NIPLama'],	
			 "NIPBaru"=>$row['NIPBaru'],	
			 "Note"=>$row['Note'], 		
						
			);

			$this->history->insert_his_change($record);
		
		}
        }	
		
   }

    function get_his2(){
        $result = $this->history->history2();
        $checkresult = ($result == '' or $result == NULL) ? 'empty' : 'exist';
        if($checkresult=='exist'){
	 	foreach ($result as $row){
		    
			$record = array(
                         "NIPLama"=>$row['NIPLama'],	
			 "NIPBaru"=>$row['NIPBaru'],	
			 "Note"=>$row['Note'], 		
						
			);

			$this->history->insert_his_change($record);
		
		}
        }	
		
   }	
  	

   

}
