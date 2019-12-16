<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Leavepermit_trx_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->_dbatt = $this->load->database('attendance', TRUE);
        $this->_dbemp = $this->load->database('empcenter', TRUE);
        $this->_pbl = $this->load->database('public', TRUE);
        $this->_table = 't08leavepermit';
        $this->_table2 = 't08leavepermit_d';
        $this->_table_prs1 = 'm01personal';
        $this->_table_prs2 = 'm01personal_d';
	$this->_table3= 'm03organization';
    }
    function get_tap($iduser,$tgl){
        $this->_dbatt->select('*');
        $this->_dbatt->from('t01machine');
        $this->_dbatt->join("m02cardmap","m02cardmap.IDCard = t01machine.EnrollNumber","left");
        $this->_dbatt->where("m02cardmap.IDEmployee  = '$iduser' AND t01machine.EnrollDate = '$tgl'");
        return $this->_dbatt->get();        
    }
    function get_leavepermit($where=NULL){
        if ($where != NULL){
            $this->_dbatt->where($where);
        }
        return $this->_dbatt->get($this->_table);
    }
    function get_accepted($iduser){
        $att    = "isib_attendance";
        $emp    = "isib_employee";
        $this->datatables->select("E.FullName AS Name, A.OutDate, A.InDate, A.Necessity, A.Note, A.ConfirmDate AS ApprovalDate");
        $this->datatables->from("$att.$this->_table AS A");
        $this->datatables->join("$emp.$this->_table_prs1 AS E","A.IDEmployee = E.IDEmployee");
        $this->datatables->where("A.ConfirmBy = '$iduser' AND A.DeleteFlag = 'A'");
        return $this->datatables->generate();
    }    
    //noaktif sementara
    function get_participant($where=NULL){
        $query      = "SELECT T.IDEmployee, E.FullName, E.IDJobPosition, E.IDDepartement FROM isib_attendance.$this->_table2 T JOIN isib_employee.$this->_table_prs2 E ON T.IDEmployee = E.IDEmployee WHERE T.IDLeavePermit = '".$where['IDLeavePermit']."'";
        return $this->db->query($query);
    }
    function get_personal($iduser){
        $query      = "
            SELECT H.*, D.* , O.DescStructure as DescDepart
            FROM $this->_table_prs1 H 
                LEFT JOIN $this->_table_prs2 D 
                    ON H.IDEmployee = D.IDEmployee 
                    LEFT JOIN $this->_table3 O
                        ON D.IDDepartement = O.IDStructure
                    WHERE H.IDEmployee = '$iduser'
                ";
        return $this->_dbemp->query($query);
    }
    // nonaktif sementara
    function get_last_id(){
        $this->_dbatt->select_max("IDLeavePermit");
        return $this->_dbatt->get($this->_table);        
    }
    function get_other($where){
        $this->_dbatt->select('IDEmployee as iduser, FullName as label');
        $this->_dbatt->where_not_in("IDEmployee",$where);
        $this->_dbatt->where("ResignDate IS NULL");
        return $this->_dbatt->get($this->_table_prs1);
    }
    function insert_lpermit($rec){
        $this->_dbatt->insert($this->_table,$rec);
    }
    function insert_lpermit_d($rec){
        $this->_dbatt->insert($this->_table2,$rec);
    }
    function update_lpermit($where,$rec){
        $this->_dbatt->where($where);
        $this->_dbatt->update($this->_table,$rec);
    }
    function update_lpermit_d($where,$rec){
        $this->_dbatt->where($where);
        $this->_dbatt->update($this->_table2,$rec);
    }
    function delete_lpermit($where){
        $this->_dbatt->where($where);
        $this->_dbatt->delete($this->_table);
    }
    function delete_lpermit_d($where){
        $this->_dbatt->where($where);
        $this->_dbatt->delete($this->_table2);
    }
    function get_prs_public($iduser){
        $this->_pbl->where("IDEmployee",$iduser);
        return $this->_pbl->get($this->_table_prs1);
    }
    
}
