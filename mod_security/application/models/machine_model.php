<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Machine_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db_scr = $this->load->database('security', TRUE);
        $this->_db_emp = $this->load->database('empcenter', TRUE);
        $this->_table = 't03logmachine';
        $this->_personal = 'isib_employee.m01personal';
    }

    function getdata() {
        $a = "isib_security.$this->_table";
        $b = $this->_personal;
        $this->datatables->select("$a.ID AS ID,                                 
                                 $a.IDEmployee AS IDEmployee,            
                                 $a.DateTruncate AS DateTruncate,            
                                 $a.Note AS Note,                                     
                                 $b.FullName AS FullName,                                     
                                 IF($b.IDJobGroup ='ST','STAFF',	
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN','-'))))) AS JobGroup,   
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }

    function checkdata($date) {
        $this->_db_scr->where('DateTruncate', $date);
        $this->_db_scr->where('DeleteFlag', 'A');
        $this->_db_scr->limit(1);
        $result = $this->_db_scr->get($this->_table);
        if ($result->num_rows() == 1) {
            return $result->row();
        } else {
            return 'empty';
        }
    }

    function insert($record) {
        $this->_db_scr->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db_scr->where('ID', $id);
        $this->_db_scr->update($this->_table, $record);
    }

    function delete($id) {
        $this->_db_scr->delete($this->_table, array('ID' => $id));
    }

    function getby_id($id) {
        $this->_db_scr->select('a.*,b.FullName,b.IDJobGroup');
        $this->_db_scr->from($this->_table . ' a');
        $this->_db_scr->join($this->_personal . ' b', 'a.IDEmployee=b.IDEmployee', 'left');
        $this->_db_scr->where('a.ID', $id);
        $result = $this->_db_scr->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
    }

    function getalldata() {
        $this->_db_scr->select('a.*,b.FullName,b.IDJobGroup');
        $this->_db_scr->from($this->_table . ' a');
        $this->_db_scr->join($this->_personal . ' b', 'a.IDEmployee=b.IDEmployee', 'left');
        $result = $this->_db_scr->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
    }

}
