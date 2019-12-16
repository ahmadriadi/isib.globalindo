<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Repaireamil_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_emp   = $this->load->database('empcenter',TRUE);				
        $this->_pbl   = $this->load->database('public',TRUE);				
        $this->_personal_h = 'm01personal';
        $this->_personal_d = 'm01personal_d';
    }
   
    function get_email_pbl($email){
        $this->_pbl->like('InternalEmail',$email);
        $result = $this->_pbl->get($this->_personal_h);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
        
    }
    
    function update_email_pbl($id,$record){
        $this->_pbl->where('ID',$id);
        $this->_pbl->update($this->_personal_h,$record);
        
    }
    function get_email_emp_h($email){
        $this->_emp->like('EmailInternal',$email);
        $result = $this->_emp->get($this->_personal_h);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
        
    }
    function get_email_emp_d($email){
        $this->_emp->like('EmailInternal',$email);
        $result = $this->_emp->get($this->_personal_d);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
        
    }
    
    function update_email_emp_h($id,$record){
        $this->_emp->where('ID',$id);
        $this->_emp->update($this->_personal_h,$record);
        
    }
    function update_email_emp_d($id,$record){
        $this->_emp->where('ID',$id);
        $this->_emp->update($this->_personal_d,$record);
        
    }
}
