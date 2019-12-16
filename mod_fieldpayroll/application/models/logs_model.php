<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Logs_model extends CI_Model {
    public function __construct() {
        parent::__construct(); 
        $this->_db = $this->load->database('security', TRUE);
        $this->_table = 'h01logs';
    }
    
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table, $record);
    }

    function delete($id) {
        $this->_db->delete($this->_table, array('ID' => $id));
    }

}

?>
