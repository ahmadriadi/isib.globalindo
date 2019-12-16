<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Deduction_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db         =  $this->load->database('fieldpayroll', TRUE);       
        $this->_table      = 't04deductionmanual';
        $this->_employee   = 'isib_employee.m01personal';
    }
  
  function getpersonal($nip){
    $this->_db->where('IDEmployee',$nip);
   return $this->_db->get($this->_employee)->row();
  }  
 
    
  function check_deduction($posting, $nip, $parameter,$flag,$record) {
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('Parameter', $parameter);
        $this->_db->where('FlagLoan', $flag);
        $query = $this->_db->get($this->_table);
        if ($query->num_rows() > 0) {
            $this->update($posting, $nip, $parameter, $flag, $record);
        } else {
            $this->insert($record);
        }
    }
  
  
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    
    
    function update($posting, $nip, $parameter,$flag,$record){
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('Parameter', $parameter);
        $this->_db->where('FlagLoan', $flag);
        $this->_db->update($this->_table,$record);
    }
    
   


}

?>


