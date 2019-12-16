<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paraminsentive_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('fieldpayroll', TRUE);
        $this->_table = 'prm01insentive';
        $this->_employee = 'triasnet_employee.m01personal';
    }

    function getdata() {
        $a = $this->_table;
        $b = 'triasnet_employee.m01personal';
        $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,                                
                                 $a.Note AS Note,                              
                                 $b.FullName AS FullName,
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
				 IF($b.IDJobGroup ='MAG','MAGANG',
				 IF($b.IDJobGroup ='OS','MITRA KERJA',	
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN','-')))))) AS JobGroup    
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$a.DeleteFlag", "A");
        $this->datatables->where("$b.DeleteFlag", "A");


        return $this->datatables->generate();
    }

    function getby_id($id) {
        $this->_db->select('a.*,b.FullName');
        $this->_db->from($this->_table . ' a');
        $this->_db->join($this->_employee . ' b', 'b.IDEmployee = a.IDEmployee', 'LEFT');
        $this->_db->where('a.ID', $id);
        $this->_db->where('a.DeleteFlag', 'A');
        $this->_db->where('b.DeleteFlag', 'A');
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
    }

    function getall_data() {
        $this->_db->from($this->_table . ' a');
        $this->_db->join($this->_employee . ' b', 'b.IDEmployee = a.IDEmployee', 'LEFT');
        $this->_db->where('a.DeleteFlag', 'A');
        $this->_db->where('b.DeleteFlag', 'A');
        $this->_db->order_by('b.IDJobGroup', 'DESC');
        $this->_db->order_by('b.FullName', 'ASC');
        $result = $this->_db->get();
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


