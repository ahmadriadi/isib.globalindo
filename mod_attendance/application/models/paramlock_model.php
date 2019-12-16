<?php

class Paramlock_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_table = 'tparam';
        $this->_personal = 'isib_employee.m01personal';
    }

    function getdata() {
        $a =  $this->_table;
        $b =  $this->_personal;
        $this->datatables->select("$a.ID AS ID,
                                   $a.IDParam AS IDParam, 
                                   $a.Val1 AS Val1, 
                                   $a.Val2 AS Val2, 
                                   $a.Val3 AS Val3, 
                                   $a.Val4 AS Val4, 
                                   $b.FullName AS FullName,    
                                 ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.Val3 = $b.IDEmployee", 'left');
        return $this->datatables->generate();
    }
    
    
    function getparamdata($param){
        $this->_db->where('IDParam',$param);
        $result = $this->_db->get($this->_table);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return null;
        }
        
    }

    function getall_data() {
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function checkdata($param) {
        $this->_db->where('IDParam', $param);
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

    function count_by_id($id) {
        $this->_db->where('ID', $id);
        return $this->_db->count_all_results($this->_v_table);
    }

    function importdata($id, $record) {
        // cek, jika sdh ada, update
        // kalau blm ada, insert
        $this->_db->where('ID', $id);
        if ($this->_db->count_all_results($this->_table) > 0) {
            $this->update($id, $record);
        } else {
            $this->insert($record);
        }
    }

}

?>
