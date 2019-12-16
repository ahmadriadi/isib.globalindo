<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Leavepermit_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_table = 't08leavepermit';
	$this->_presence = 't03presence';	
        $this->_personal = 'isib_employee.m01personal';
    }

    function allleavepermit($from, $until) {
        $a = $this->_table;
        $b = 'isib_employee.m01personal';
        $this->datatables->select("$a.ID AS ID,                                
                                 $a.IDEmployee AS IDEmployee,
                                 DATE_FORMAT($a.LeavePermitDate,'%d-%m-%Y') AS LeavePermitDate,
                                 $a.OutDate AS OutDate,                                
                                 $a.InDate AS InDate, 
				 $b.IDJobGroup AS IDJobGroup,                               
                                 $a.IMKHour AS IMKHour, 
                                 IF($a.Necessity='1','PERSONAL',
                                 IF($a.Necessity='2','OFFICE','-')) AS Status,
				 $a.VehicleNo AS VehicleNo, 	  
                                 $a.Note AS Note,                                
                                 $b.FullName AS FullName,    
                                 IF($b.IDJobGroup ='ST','STAFF',    
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
				 IF($b.IDJobGroup ='OS','MITRA KERJA',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN',
                                 IF($b.IDJobGroup ='MAG','MAGANG','-'))))))) AS JobGroup   
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$a.LeavePermitDate >=", date('Y-m-d', strtotime($from)));
        $this->datatables->where("$a.LeavePermitDate <=", date('Y-m-d', strtotime($until)));
        $this->datatables->where("$a.DeleteFlag", 'A');
        $this->datatables->where("$b.DeleteFlag", 'A');
	$this->datatables->where("$a.ConfirmFlag", '1');

        return $this->datatables->generate();
    }
    
    
       function leavepermit_hrd($from, $until,$g) {
        $f = date('Y-m-d', strtotime($from));
        $u = date('Y-m-d', strtotime($until));
        $group = ($g=='AL')?'':" AND b.IDJobGroup='$g'";
        $sql = "
                SELECT a.*,b.FullName,b.IDJobGroup
                FROM $this->_table a 
                LEFT JOIN $this->_personal b ON a.IDEmployee = b.IDEmployee
                WHERE a.LeavePermitDate BETWEEN '$f' AND '$u' AND  a.ConfirmFlag='1' AND b.DeleteFlag='A' AND a.DeleteFlag='A' $group";

        $result = $this->_db->query($sql);
        if ($result->num_rows()> 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    
        function leavepermit($from, $until, $userid,$g) {
        $f = date('Y-m-d', strtotime($from));
        $u = date('Y-m-d', strtotime($until));
        $group = ($g=='AL')?'':" AND b.IDJobGroup='$g'";
        $sql = "

                SELECT a.*,b.IDEmployeeParent,b.FullName,b.IDJobGroup
                FROM $this->_table a
                LEFT JOIN $this->_personal b ON a.IDEmployee = b.IDEmployee
                WHERE a.LeavePermitDate BETWEEN '$f' AND '$u' AND a.AddedBy ='$userid' AND a.ConfirmFlag='1' AND a.DeleteFlag='A' AND b.DeleteFlag='A' $group
            ";

        $result = $this->_db->query($sql);
        if ($result->num_rows()> 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    
    /*
    
    function leavepermit($from, $until, $userid,$g) {
        $f = date('Y-m-d', strtotime($from));
        $u = date('Y-m-d', strtotime($until));
        $group = ($g=='AL')?'':" AND b.IDJobGroup='$g'";
        $sql = "
                SELECT a.*,b.IDEmployeeParent,b.FullName,b.IDJobGroup
                FROM $this->_table a 
                LEFT JOIN $this->_personal b ON a.IDEmployee = b.IDEmployee
                WHERE a.LeavePermitDate BETWEEN '$f' AND '$u' AND a.IDEmployee ='$userid' AND a.ConfirmFlag='1' AND a.DeleteFlag='A' AND b.DeleteFlag='A'  $group
                UNION 
                SELECT a.*,b.IDEmployeeParent,b.FullName,b.IDJobGroup
                FROM $this->_table a 
                LEFT JOIN $this->_personal b ON a.IDEmployee = b.IDEmployee
                WHERE a.LeavePermitDate BETWEEN '$f' AND '$u' AND b.IDEmployeeParent ='$userid' AND a.ConfirmFlag='1' AND a.DeleteFlag='A' AND b.DeleteFlag='A' $group
            ";

        $result = $this->_db->query($sql);
        if ($result->num_rows()> 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
   */

    function get_by_id($id) {
        $sql = "SELECT a.*,
                       b.FullName,b.IDJobGroup
                 FROM $this->_table a
                 LEFT JOIN $this->_personal b
                 ON a.IDEmployee = b.IDEmployee
                 WHERE a.ID='$id'                
                
                ";

        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function getall_data($from, $until) {
        $sql = "SELECT a.*,
                       b.FullName,b.IDJobGroup
                 FROM $this->_table a
                 LEFT JOIN $this->_personal b
                 ON a.IDEmployee = b.IDEmployee
                 WHERE
                 a.LeavePermitDate BETWEEN '$from' AND '$until' AND
                 a.DeleteFlag ='A' AND
                 b.DeleteFlag ='A' 
                 
                 ORDER BY
                  b.IDJobGroup  DESC,
                  b.FullName ASC,
                  a.LeavePermitDate ASC
                ";

        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
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
        return $this->_db->count_all_results($this->_table);
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

     function presenceby_necessity($id, $presence, $record) {
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('PresenceDate', $presence);
        $this->_db->update($this->_presence, $record);
    }
    function lastpresenceby_necessity($id, $presence, $record) {
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('PresenceDate', $presence);
        $this->_db->update($this->_presence, $record);
    }

}

?>

