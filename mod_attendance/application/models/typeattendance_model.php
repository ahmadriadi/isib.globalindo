<?php

class Typeattendance_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_table = 'r04typeattendance';
    }

    function alltype() {
        $a = $this->_table;
        $this->datatables->select("$a.ID AS ID,                                  
                                   $a.IDType AS IDType, 
                                   $a.Description AS Description, 
                                   $a.Note AS Note 
                                 ", FALSE);
        $this->datatables->from("$a");      
        return $this->datatables->generate();
    }

    function getall_data() {
        $this->_db->order_by('IDType', 'ASC');
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function checkdata($code) {
        $this->_db->where('IDType', $code);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return 'exist';
        } else {
            return 'empty';
        }
    }

    function get_by_id($id) {
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
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
