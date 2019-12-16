<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Param_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('empcenter', TRUE);
        $this->_table   = 'm04param';      
    }
    

 function get_param($userid){
     $this->_db->where('ParamValue',$userid);
     $result = $this->_db->get($this->_table);
     if($result->num_rows()>0){
         return $result->row();
         
     }else{
         return null;
     }
     
     
     
 }



    
 
}

?>
