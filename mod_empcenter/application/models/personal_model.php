<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personal_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('empcenter',TRUE);				
        $this->_tbl1 = 'm01personal';
    }
    
    function get_data($uid) {
        $this->_db->where('IDEmployee', $uid);
        $query  = $this->_db->get($this->_tbl1);
        if ($query->num_rows()==1) {
            return $query->row();
        } else {
            return NULL; 
        }
    }
    function update($where,$record){
        $this->_db->where($where);
        $this->_db->update($this->_tbl1,$record);
    }
    function insert($record) {
        $this->_db->insert($this->_tbl1, $record);
    }
   
}

