<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Akdhk extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Tools_model', 'tools');
  
    }
    
    function index(){   
      $this->getalltem();
    }
    
   function getalltem(){
       $result = $this->tools->get_tmp();
       if($result !=='empty'){
           foreach ($result as $row) {
               $nip = $row['IDEmployee'];
               
               $record = array(
                   "NoAKDHK"=>$row['NoAKDHK'],
               );
               
               $this->tools->update_personal_d($nip,$record);
               $this->tools->update_personal_pbl($nip,$record);
               
               
           }
       }
       
   }

}

/* End of file main.php */
/* Location: ./application/controllers/welcome.php */
