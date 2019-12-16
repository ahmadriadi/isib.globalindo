<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personalpayroll_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db         =  $this->load->database('fieldpayroll', TRUE);       
        $this->_table      = 'm01fieldpayroll';
        $this->_employee   = 'isib_employee.m01personal';
    }
  
    
    function updatedata($nip,$record){
        $this->_db->where('IDEmployee', $nip);
        $this->_db->update($this->_table,$record);
    }
    
   


}

?>


