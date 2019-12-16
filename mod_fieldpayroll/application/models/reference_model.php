<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reference_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('fieldpayroll', TRUE);
        $this->_table1 = 'r01deduction';
        $this->_table2 = 'r02addition';
    }

    function refdeduction() {
        $a = $this->_table1;
        $this->datatables->select("$a.ID AS ID,
                                 $a.CodeType AS CodeType,                                
                                 $a.Description AS Description   
                            ", FALSE);
        $this->datatables->from("$a");        
        return $this->datatables->generate();
    }

    function refadditional() {
        $a = $this->_table2;
        $this->datatables->select("$a.ID AS ID,
                                 $a.CodeType AS CodeType,                                
                                 $a.Description AS Description   
                            ", FALSE);
        $this->datatables->from("$a");       
        return $this->datatables->generate();
    }

    function get_alldata_deduction() {       
        return $this->_db->get($this->_table1);
    }

    function get_alldata_additional() {      
        return $this->_db->get($this->_table2);
    }

    function get_by_id_deduction($id) {
        $this->_db->where('ID', $id);
        return $this->_db->get($this->_table1);
    }

    function get_by_id_additional($id) {
        $this->_db->where('ID', $id);
        return $this->_db->get($this->_table2);
    }

    function insert_deduction($record) {
        $this->_db->insert($this->_table1, $record);
    }

    function insert_additional($record) {
        $this->_db->insert($this->_table2, $record);
    }

    function update_deduction($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table1, $record);
    }

    function update_additional($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table2, $record);
    }
    function delete_deduction($id){
        $sql = "DELETE from $this->_table1 WHERE ID='$id'";
        $this->_db->query($sql);        
    }
    function delete_additional($id){
        $sql = "DELETE from $this->_table2 WHERE ID='$id'";
        $this->_db->query($sql);        
    }

}
?>

