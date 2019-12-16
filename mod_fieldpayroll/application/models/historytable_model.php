<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Historytable_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('fieldpayroll', TRUE);
        $this->_dbhis = $this->load->database('hisfieldpayroll', TRUE);
        $this->_personalpayroll = 'm01fieldpayroll';
        $this->_addition = 't03addition';
        $this->_mandeduction = 't04deductionmanual';
        $this->_loan_h = 'm02personalloan_h';
        $this->_insentif = 't06insentif';
        $this->_parampayroll = 'prm02payroll';
    }
    function getby_id_personal($id) {
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_personalpayroll);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function insert_hispersonal($record){
        $this->_dbhis->insert($this->_personalpayroll,$record);
    }
    
    
    function getby_id_addition($id){
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_addition);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
    
     function insert_hisaddition($record){
        $this->_dbhis->insert($this->_addition,$record);
    }
    
    
    function getby_id_mandeduction($id){
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_mandeduction);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
    
     function insert_hismandeduction($record){
        $this->_dbhis->insert($this->_mandeduction,$record);
    }
    
     function getby_id_insentif($id){
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_insentif);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
    
     function insert_hisinsentif($record){
        $this->_dbhis->insert($this->_insentif,$record);
    }
    
    
    function getby_id_loanh($id){
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_loan_h);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
    
     function insert_hisloanh($record){
        $this->_dbhis->insert($this->_loan_h,$record);
    }
    
    
    function insert_history_parampayroll($record){
        $this->_dbhis->insert($this->_parampayroll,$record);
    }
   
}
?>



