<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Parampayroll_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('fieldpayroll', TRUE);
        $this->_table = 'prm02payroll';
        $this->_employee = 'triasnet_employee.m01personal';
    }

    function getdata() {
        $a = $this->_table;
        $this->datatables->select("$a.ID AS ID,
                                 $a.SumDaySalary AS SumDaySalary,                                
                                 $a.OvertimeWorkHour AS OvertimeWorkHour,                              
                                 $a.InsurancePercent AS InsurancePercent,                              
                                 $a.BPJSPercent AS BPJSPercent,                              
                                 $a.Note AS Note,                              
                                     
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }

    function getby_id($id) {
       $this->_db->where('ID',$id);
       $this->_db->where('DeleteFlag','A');
       $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
        
    }

    function getall_data() {
       $this->_db->where('DeleteFlag','A');
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return 'empty';
        }
    }

    function checkdata($nip) {
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('IDEmployee', $nip);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return "exist";
        } else {
            return "empty";
        }
    }

    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table, $record);
    }

}
?>


