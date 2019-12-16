<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class UserActivation_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('security',TRUE);		
        $this->_tbl1 = 't02useractivation';
    }
    
    function verify($uid,$email,$token) {
        $this->_db->where('Username', $uid);
        $this->_db->where('Email', $email);
        $this->_db->where('Token', $token);
        $this->_db->limit(1);
        $query  = $this->_db->get($this->_tbl1);
        if ($query->num_rows()==1) {
            return $query->row();
        } else {
            return NULL; 
        }
    }

    function insert($record) {
        $this->_db->insert($this->_tbl1, $record);
    }
    
}
