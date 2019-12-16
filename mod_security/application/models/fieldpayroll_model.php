<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fieldpayroll_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db         =  $this->load->database('fieldpayroll', TRUE);       
        $this->_slipthr      = 't05slip';
        $this->_employee   = 'isib_employee.m01personal';
    }
  
  function getpersonal($nip){
    $this->_db->where('IDEmployee',$nip);
     return $this->_db->get($this->_employee)->row();
  }  
 
    
  function checkdataslip($nip, $posting,$record) {
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $nip);
        $query = $this->_db->get($this->_slipthr);
        if ($query->num_rows() > 0) {
            $this->updateslip($nip, $posting, $record);
        } else {
            $this->insertslip($record);
        }
    }
  
  
    function insertslip($record) {
        $this->_db->insert($this->_slipthr, $record);
    }
    
    
    function updateslip($nip, $posting,$record){
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $nip);
        $this->_db->update($this->_slipthr,$record);
    }
    
   


}

?>


