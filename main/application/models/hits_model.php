<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Hits_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('security',TRUE);		
        $this->_tbl1 = 'h01hits';
    }

    function insert($record) {
        $this->_db->insert($this->_tbl1, $record);
    }

    function get_data($limit=NULL) {
        $query = $this->_db->get($this->_tbl1,$limit);
        if ($query->num_rows()>0) {
            return $query->result();
        } else {
            return NULL; 
        }
    }
	function okiwrite($rec){
		return $this->_ex->insert("okitable",$rec);
	}

}

?>
