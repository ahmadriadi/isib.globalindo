<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UserLogin_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('security',TRUE);		
        $this->_tbl1 = 't01userlogin';
    }
    
    function check_username($uid) {
        $this->_db->where('Username', $uid);
        $this->_db->where('Status', 'A');
        $this->_db->limit(1);
        $query  = $this->_db->get($this->_tbl1);
        if ($query->num_rows()==1) {
            return $query->row();
        } else {
            return NULL; 
        }
    }
    
    function update($id, $record) {
        $this->_db->where('Username', $id);
        $this->_db->update($this->_tbl1, $record);
    }

}
