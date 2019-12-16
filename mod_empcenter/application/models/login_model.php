<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('security',TRUE);		
        $this->_tbl1 = 't01userlogin';
    }
    
    function validate_credential($uid, $pass) {
        $this->_db->where('Username', $uid);
        $this->_db->where('Password', md5($uid.$pass));
        $this->_db->where('Status', 'A');
        $this->_db->limit(1);
        $query  = $this->_db->get($this->_tbl1);
        if ($query->num_rows()==1) {
            return $query->row();
        } else {
            return NULL; 
        }
    }
    
}
