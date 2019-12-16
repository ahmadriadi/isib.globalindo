<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Incomplete_trx_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_db2 = $this->load->database('empcenter', TRUE);
        $this->_pbl = $this->load->database('public', TRUE);
        $this->_table = 't05incomplete';
        $this->_param = 'm04param';
        $this->_m_table = 'm01personal';
        $this->_holiday = 'r02holiday';
    }
    
     function get_holiday($from, $until) {
        $sql = "SELECT COUNT(*) AS jumlahlibur FROM $this->_holiday WHERE Date BETWEEN '$from' AND '$until'";
        $result = $this->_db2->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
    
    function get_accepted($iduser){
        $att    = "isib_attendance";
        $emp    = "isib_employee";
        $this->datatables->select("E.FullName AS Name, I.IncompleteDate AS IncompleteDate, I.TimeIn AS TimeIn, I.TimeOut AS TimeOut, I.Note AS Note, I.ConfirmDate AS ApprovalDate");
        $this->datatables->from("$att.$this->_table AS I");
        $this->datatables->join("$emp.$this->_m_table AS E","I.IDEmployee = E.IDEmployee");
        $this->datatables->where("I.ConfirmBy = '$iduser' AND I.DeleteFlag = 'A'");
        return $this->datatables->generate();
    }	
   function incompleteall($from,$until) {
        $a = $this->_table;
        $b = 'isib_employee.m01personal';
        $this->datatables->select("$a.ID AS ID,                                 
                                 $a.IDEmployee AS IDEmployee,
				 $b.IDJobGroup AS IDJobGroup,
                                 $a.IncompleteDate AS IncompleteDate,
                                 $a.TimeIn AS TimeIn,
                                 $a.TimeOut AS TimeOut,
                                 $a.Note AS Note,                              
                                 $b.FullName AS FullName,
                                 IF($b.IDJobGroup ='ST','STAFF',    
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN',
				 IF($b.IDJobGroup ='OS','MITRA KERJA',
                                 IF($b.IDJobGroup ='MAG','MAGANG','-'))))))) AS JobGroup,    
                                 IF ($a.ConfirmFlag = '0', '<b class=\"waiting\">Waiting</b>',
                                 IF ($a.ConfirmFlag = '1', '<b class=\"accept\">Accepted</b>',
                                 IF ($a.ConfirmFlag = '2', concat('<span class=\"reject\" data-toggle=\"popover\" data-title=\"Reason of rejection\" data-content=\"',$a.RejectReason,'\" data-placement=\"right\"><b data-toggle=\"tooltip\" data-original-title=\"click to view the reason\" data-placement=\"top\" >Rejected</b></span>'),''
                                 ))) AS Status
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$a.IncompleteDate >=", date('Y-m-d',  strtotime($from)));
        $this->datatables->where("$a.IncompleteDate <=", date('Y-m-d',  strtotime($until)));
        $this->datatables->where("$a.DeleteFlag", "A");
        $this->datatables->where("$b.DeleteFlag", "A");
        $this->datatables->where("$a.ConfirmFlag","1");       
        return $this->datatables->generate();
    }	

    function incompleteemployee($from,$until,$user) {
        $a = $this->_table;
        $b = 'isib_employee.m01personal';
        $this->datatables->select("$a.ID AS ID,                                 
                                 $a.IDEmployee AS IDEmployee,
                                 $a.IncompleteDate AS IncompleteDate,
                                 $a.TimeIn AS TimeIn,
                                 $a.TimeOut AS TimeOut,
                                 $a.Note AS Note,                              
                                 $b.FullName AS FullName,
                                 IF ($a.ConfirmFlag = '0', '<b class=\"waiting\">Waiting</b>',
                                 IF ($a.ConfirmFlag = '1', '<b class=\"accept\">Accepted</b>',
                                 IF ($a.ConfirmFlag = '2', concat('<span class=\"reject\" data-toggle=\"popover\" data-title=\"Reason of rejection\" data-content=\"',$a.RejectReason,'\" data-placement=\"right\"><b data-toggle=\"tooltip\" data-original-title=\"click to view the reason\" data-placement=\"top\" >Rejected</b></span>'),''
                                 ))) AS Status
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$a.IncompleteDate >=", date('Y-m-d',  strtotime($from)));
        $this->datatables->where("$a.IncompleteDate <=", date('Y-m-d',  strtotime($until)));
        $this->datatables->where("$a.DeleteFlag", "A");
        $this->datatables->where("$b.DeleteFlag", "A");
        $this->datatables->where("$a.IDEmployee", $user);

        return $this->datatables->generate();
    }

    function getall_data($f, $u,$g) {
        $fromdate = date('Y-m-d', strtotime($f));
        $untildate = date('Y-m-d', strtotime($u));
        $group = ($g=='AL')?'':"AND b.IDJobGroup='$g'";
        

        $sql = "
               SELECT
                    a.*,
                    b.FullName,
                    b.IDJobGroup
                FROM
                    $this->_table a
                LEFT JOIN
                    isib_employee. $this->_m_table b
                ON
                    a.IDEmployee = b.IDEmployee
                WHERE
                    a.IncompleteDate BETWEEN '$fromdate' AND '$untildate' AND
                    b.DeleteFlag='A' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' $group
                ORDER BY
                    b.IDJobGroup DESC,
                    b.FullName ASC,
                    a.IncompleteDate ASC
                ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return null;
    }



function get_all_by_period($f, $u, $user) {
        $fromdate = date('Y-m-d', strtotime($f));
        $untildate = date('Y-m-d', strtotime($u));

        $sql = "
               SELECT
                   a.*,
                   IF (a.ConfirmFlag = '0', 'Waiting',
                   IF(a.ConfirmFlag ='1','Accept',
                   IF(a.ConfirmFlag ='2','Reject','1'))) AS ConfirmData,
                   b.FullName
                FROM
                    $this->_table a
                LEFT JOIN
                    isib_employee. $this->_m_table b
                ON
                    a.IDEmployee = b.IDEmployee
                WHERE
                    a.IncompleteDate BETWEEN '$fromdate' AND '$untildate' AND
                    a.DeleteFlag='A' AND b.DeleteFlag='A' AND a.IDEmployee='$user'  
                ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return null;
    }


    function get_by_id($id) {
        $sql = "
               SELECT
                    a.*,
                    b.FullName
                FROM
                    $this->_table a
                LEFT JOIN
                    isib_employee. $this->_m_table b
                ON
                    a.IDEmployee = b.IDEmployee
                WHERE
                    a.ID = '$id'                
                ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        }
        return null;
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
   /*	
    function countatl($from, $until, $user) {
        $sql = " SELECT * 
                FROM $this->_table 
                WHERE 
                     IncompleteDate BETWEEN '$from' AND '$until' AND
                     IDEmployee='$user' AND
                     ConfirmFlag='1' AND
                     CHRDFlag   ='1' AND
		     DeleteFlag='A'		
                    ";
        $query = $this->_db->query($sql);
        return $query->num_rows();
    }
   */

    function countatl($from, $until, $user) {
        $sql = " SELECT * 
                FROM $this->_table 
                WHERE 
                     IncompleteDate BETWEEN '$from' AND '$until' AND
                     IDEmployee='$user' AND
                     ConfirmFlag='1' AND                     
		     DeleteFlag='A'		
                    ";
        $query = $this->_db->query($sql);
        return $query->num_rows();
    }		

    function count_by_idspkl($id) {
        $this->_db->where('IDSPKL', $id);
        return $this->_db->count_all_results($this->_table);
    }

    function checkdata($idemployee, $date) {
        $atldate = date('Y-m-d', strtotime($date));
        $this->_db->where('IDEmployee', $idemployee);
        $this->_db->where('IncompleteDate', $atldate);
	$this->_db->where("ConfirmFlag","1");  
        //$this->_db->where('TimeIn', $timein);
        //$this->_db->where('TimeOut', $timeout);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return 'exist';
        }
            return 'empty';
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

  function get_by_id_request($id){
        $sql = "SELECT
                    a.IDEmployee AS NIP,
                    b.FullName AS Name,
                    b.IDJobGroup AS JGroup,
                    c.IDJobPosition AS Position,
                    c.IDDepartement AS Dept,
                    b.IDEmployeeParent AS Parent,
                    a.IncompleteDate AS IDate,
                    a.TimeIn AS TIN,
                    a.TimeOut AS TOT,
                    a.Note,
		    a.ConfirmDate,
                    a.AddedDate,	
		    d.FullName AS ParentName  
                  FROM
                   t05incomplete a
                  LEFT JOIN
                    isib_employee.m01personal b
                  ON  
                    a.IDEmployee = b.IDEmployee
                  LEFT JOIN
                     isib_employee.m01personal_d c
                  ON  
                    a.IDEmployee = c.IDEmployee
		  LEFT JOIN
                     isib_employee.m01personal d
                  ON  
                    b.IDEmployeeParent = d.IDEmployee
                  WHERE
                    a.ID ='$id'
                ";
        
        $result = $this->_db->query($sql);
        if($result->num_rows()>0){
            return $result->row();            
        }else{
             return null;          
        }        
        
    }


    function hari($hari){
        switch ($hari){
            case 0 : $hari="Minggu";
                Break;
            case 1 : $hari="Senin";
                Break;
            case 2 : $hari="Selasa";
                Break;
            case 3 : $hari="Rabu";
                Break;
            case 4 : $hari="Kamis";
                Break;
            case 5 : $hari="Jum'at";
                Break;
            case 6 : $hari="Sabtu";
                Break;
        }
        return $hari;
    }
    function get_incomplete($where){
        $this->_db->where($where);
        return $this->_db->get($this->_table);
    }
    function get_personal($iduser){
        $query      = "SELECT H.*, D.* FROM m01personal H LEFT JOIN m01personal_d D ON H.IDEmployee = D.IDEmployee WHERE H.IDEmployee = '$iduser'";
        return $this->_db2->query($query);
    }
    function get_prs_public($iduser){
        $this->_pbl->where("IDEmployee",$iduser);
        return $this->_pbl->get($this->_m_table);
    }
    function update_incomplete($where,$record){
        $this->_db->where($where);
        $this->_db->update($this->_table,$record);
    }
    function get_param($where){
        $this->_db2->where($where);
        return $this->_db2->get($this->_param);;
    }
}
?>




