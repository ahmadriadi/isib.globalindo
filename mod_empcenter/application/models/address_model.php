<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Address_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('empcenter',TRUE);				
        $this->_addr = 'r04address';
    }
    function get_location($where){
        $this->_db->where($where);
        return $this->_db->get($this->_addr);
    }
}