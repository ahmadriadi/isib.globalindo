<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance_info_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('attendance', TRUE);
        $this->_table   = 't01machine';      
        $this->_table2  = 'm02cardmap';      
    }
    function get_data($iduser,$tgl){
        $this->_db->select('*');
        $this->_db->from($this->_table);
        $this->_db->join("$this->_table2","$this->_table2.IDCard = $this->_table.EnrollNumber","left");
        $this->_db->where("$this->_table2.LastStatus = 'T' AND $this->_table2.IDEmployee  = '$iduser' AND $this->_table.EnrollDate = '$tgl'");
        return $this->_db->get();
        
    }
}


