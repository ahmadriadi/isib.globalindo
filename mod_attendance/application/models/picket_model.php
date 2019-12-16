<?php

class Picket_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_table = 't12employeepicket';
        $this->_holiday = 'r02holiday';
        $this->_personal = 'isib_employee.m01personal_d';
    }

    function getdata($from, $until) {
        $a = $this->_table;
        $b= $this->_personal;
        $this->datatables->select("$a.ID AS ID,
                                   $a.IDEmployee AS IDEmployee, 
                                   $b.FullName AS FullName, 
                                   $b.IDJobGroup AS IDJobGroup, 
                                   $a.RangePicket AS RangePicket, 
                                   IF($a.RangePicket='1','One Day',
                                   IF($a.RangePicket='2','More one days','-')) AS RangePicket,
                                   $a.FromDate AS FromDate, 
                                   $a.UntilDate AS UntilDate, 
                                   $a.StatusPicket AS StatusPicket, 
                                   IF($a.StatusPicket='A','Active',
                                   IF($a.StatusPicket='P','Passive','-')) AS Status,    
                                   $a.AddedBy AS AddedBy, 
                                   $a.AddedDate AS AddedDate, 
                                   $a.AddedIP AS AddedIP, 
                                   $a.EditedBy AS EditedBy, 
                                   $a.EditedDate AS EditedDate, 
                                   $a.EditedIP AS EditedIP, 
                                   $a.DeleteBy AS DeleteBy, 
                                   $a.DeleteDate AS DeleteDate, 
                                   $a.DeleteIP AS DeleteIP, 
                                   $a.DeleteFlag AS DeleteFlag, 
                                   $a.Note AS Note, 
                                 ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b,"$a.IDEmployee = $b.IDEmployee",'left'); 
        $this->datatables->where("$a.DeleteFlag","A");
        $this->datatables->where("$a.FromDate >=", date('Y-m-d', strtotime($from)));
        $this->datatables->where("$a.FromDate <=", date('Y-m-d', strtotime($until)));
        return $this->datatables->generate();
    }
    
    function getby_id($id) {
        $sql = "SELECT a.*,b.FullName,b.IDJobGroup FROM $this->_table a 
                INNER JOIN $this->_personal b  ON a.IDEmployee = b.IDEmployee
                WHERE 
                a.ID= '$id'
                
                ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }


    function getall_data($from,$until) {
        $sql = "SELECT a.*,b.FullName,b.IDJobGroup FROM $this->_table a 
                INNER JOIN $this->_personal b  ON a.IDEmployee = b.IDEmployee
                WHERE 
                a.FromDate >='$from' AND a.UntilDate <='$until'
                
                ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function checkdata($nip, $from, $until) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('FromDate', $from);
        $this->_db->where('UntilDate', $until);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return 'exist';
        } else {
            return 'empty';
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

    function countby_id($id) {
        $this->_db->where('ID', $id);
        return $this->_db->count_all_results($this->_v_table);
    }

}

?>
