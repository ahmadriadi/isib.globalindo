<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Operator_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db_emp = $this->load->database('empcenter', TRUE);
        $this->_db = $this->load->database('production', TRUE);
        $this->_table = 'ref04operator';
        $this->_personal = 'm01personal';
     
    }
    
    
    function find_employee(){
        $this->_db_emp->where('Status','A');
        $this->_db_emp->where('DeleteFlag','A');
        return $this->_db_emp->get($this->_personal);
        
    }

    function getdata() {
        $a = $this->_table;
        $b = 'triasnet_employee.'.$this->_personal;
        $this->datatables->select("$a.IDRecord AS IDRecord,                                 
                                   $a.IDOperator AS IDOperator,
                                   $b.FullName AS FullName,
                                    IF($b.IDJobGroup ='ST','STAFF',    
                                    IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                    IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                    IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                    IF($b.IDJobGroup ='LL','LAIN-LAIN',
                                    IF($b.IDJobGroup ='OS','MITRA KERJA',
                                    IF($b.IDJobGroup ='MAG','MAGANG','-'))))))) AS JobGroup,
                                    IF($b.IDLocation='1','KAPUK',
                                    IF($b.IDLocation='2','BITUNG','-')) AS Location,    
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDOperator = $b.IDEmployee", 'left');
        $this->datatables->where("$a.DeleteFlag", "A");
        $this->datatables->where("$b.Status", "A");
        $this->datatables->where("$b.DeleteFlag", "A");
        return $this->datatables->generate();
    }

  
    
    function getall_data() {
        $this->_db->select('a.*,b.FullName,b.IDJobGroup,b.IDLocation');
        $this->_db->from($this->_table.' a');
        $this->_db->join('triasnet_employee.'.$this->_personal.' b','b.IDEmployee = a.IDOperator','LEFT'); 
        $this->_db->where('a.DeleteFlag', 'A');
        $this->_db->where('b.Status', 'A');
        $this->_db->where('b.DeleteFlag', 'A');
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    function getby_id($id) {
        $this->_db->select('a.*,b.FullName,b.IDJobGroup,b.IDLocation');
        $this->_db->from($this->_table.' a');
        $this->_db->join('triasnet_employee.'.$this->_personal.' b','b.IDEmployee = a.IDOperator','LEFT'); 
        $this->_db->where('a.IDRecord', $id);
        $this->_db->where('a.DeleteFlag', 'A');
        $this->_db->where('b.Status', 'A');
        $this->_db->where('b.DeleteFlag', 'A');
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }


    function checkdata($operator) {
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('IDOperator', $operator);
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
        $this->_db->where('IDRecord', $id);
        $this->_db->update($this->_table, $record);
    }

  
}
?>

