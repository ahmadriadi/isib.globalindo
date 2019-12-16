<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authorization_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('security', TRUE);
        $this->_printed = 'r04authorization_print';
        $this->_canceled = 'r05authorization_cancel';
        $this->_personal = 'isib_employee.m01personal';
    }

    function datacancel() {
        $a = 'isib_security.r05authorization_cancel';
        $b = $this->_personal;
        $this->datatables->select("$a.ID AS ID,                                 
                                 $a.IDEmployee AS IDEmployee, 
                                 $b.FullName AS FullName,                                     
                                 IF($b.IDJobGroup ='ST','STAFF',	
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN','-'))))) AS JobGroup   
                                 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$b.Status", "A");
        return $this->datatables->generate();
    }

    function dataprint() {
        $a = 'isib_security.r04authorization_print';
        $b = $this->_personal;
        $this->datatables->select("$a.ID AS ID,                                 
                                 $a.IDEmployee AS IDEmployee,  
                                 $b.FullName AS FullName,                                     
                                 IF($b.IDJobGroup ='ST','STAFF',	
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN','-'))))) AS JobGroup   
                                 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$b.Status", "A");
        return $this->datatables->generate();
    }

    function check_access_print($uid) {
        $this->_db->where('IDEmployee', $uid);
        $query = $this->_db->get($this->_printed);
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return NULL;
        }
    }

    function check_access_cancel($uid) {
        $this->_db->where('IDEmployee', $uid);
        $query = $this->_db->get($this->_canceled);
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return NULL;
        }
    }

    function insert_print($record) {
        $this->_db->insert($this->_printed, $record);
    }

    function insert_cancel($record) {
        $this->_db->insert($this->_canceled, $record);
    }

    function update_print($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_printed, $record);
    }

    function update_cancel($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_canceled, $record);
    }

    function delete_print($id) {
        $this->_db->delete($this->_printed, array('ID' => $id));
    }

    function delete_cancel($id) {
        $this->_db->delete($this->_canceled, array('ID' => $id));
    }

    function get_by_id_print($id) {
        $this->_db->select('a.*,b.FullName,b.IDJobGroup');
        $this->_db->from($this->_printed . ' a');
        $this->_db->join($this->_personal . ' b', 'a.IDEmployee=b.IDEmployee', 'left');
        $this->_db->where('a.ID', $id);
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_by_id_cancel($id) {
        $this->_db->select('a.*,b.FullName,b.IDJobGroup');
        $this->_db->from($this->_table . ' a');
        $this->_db->join($this->_canceled . ' b', 'a.IDEmployee=b.IDEmployee', 'left');
        $this->_db->where('a.ID', $id);
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

}
