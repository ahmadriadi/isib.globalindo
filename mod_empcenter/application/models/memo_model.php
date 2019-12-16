<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Memo_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->_db = $this->load->database('empcenter', TRUE);
        $this->_pbl = $this->load->database('public', TRUE);
        $this->_table = 't03memo';
        $this->_table2 = 't03memo_folup';
        $this->_table3 = 't03memo_attachment';
        $this->_table_prs1 = 'm01personal';
        $this->_table_prs2 = 'm01personal_d';
        $this->_table4 = 'm03organization';
        $this->_table5 = 'm01personal';
    }
    function get_department($iddep){
        $this->_db->where("IDStructure",$iddep);
        return $this->_db->get($this->_table4);
    }
    function get_emp_suggest($iduser){
        $query  = "SELECT P.IDEmployee AS iduser, P.FullName as label, P.IDJobPosition AS posisi , D.DescStructure as divisi
            FROM $this->_table_prs2 P
                LEFT JOIN $this->_table4 D
                    ON P.IDDepartement = D.IDStructure
                    WHERE P.IDEmployee != '$iduser'
                        AND
                        P.ResignDate IS NULL
            ";
        return $this->_db->query($query);
//        $this->_db->select("IDEmployee as iduser, FullName as label, IDJobPosition as posisi, IDDepartement as divisi",FALSE);
//        $this->_db->where("IDEmployee !=",$iduser);
//        $this->_db->where("ResignDate IS NULL");
//        return $this->_db->get($this->_table_prs2);
    }
    function get_lastid(){
        $this->_db->select_max("IDMemo");
        return $this->_db->get($this->_table);
    }
    function get_lastfeedcount($where){
        $this->_db->where($where);
        $this->_db->select_max("Count");
        return $this->_db->get($this->_table2);
    }
    function get_inbox($iduser){
        $query  = "
SELECT M.*, E.FullName, E.IDDepartement FROM t03memo M LEFT JOIN m01personal_d E ON M.FromIDUser = E.IDEmployee WHERE M.ToIDUser = '$iduser' AND M.DeleteFlag = 'A' AND M.ConfirmFlag = '1'
UNION ALL
SELECT M.*, E.FullName, E.IDDepartement FROM t03memo M LEFT JOIN m01personal_d E ON M.FromIDUser = E.IDEmployee WHERE M.ToIDUser IN(
SELECT IDEmployee From m01personal_d WHERE IDDepartement IN(
	SELECT IDDepartement FROM m01personal_d WHERE IDEmployee = '$iduser'
	) 
        AND IDEmployee != '$iduser'
)
AND M.DeleteFlag = 'A' AND M.CC = '1' AND M.ConfirmFlag = '1'

                ";
        
        return $this->_db->query($query);
    }
    function get_outbox($iduser){
        $query  = "SELECT M.*, E.FullName, E.IDDepartement FROM $this->_table M LEFT JOIN $this->_table_prs2 E ON M.ToIDUser = E.IDEmployee WHERE M.FromIDUser = '$iduser' AND M.DeleteFlag = 'A' ORDER BY M.MemoDate DESC";
        return $this->_db->query($query);
    }
    function get_memo($idmemo){
        $query  = "
            SELECT M.*, 
            F.IDEmployee as FromID,
            F.IDEmployeeParent as FromIDParent,
            F.FullName as FromName, 
            DF.DescStructure FromDiv, 
            F.IDJobPosition FromPos,
            DT.DescStructure ToDiv, 
            T.IDEmployee as ToID,
            T.IDEmployeeParent as ToIDParent,
            T.FullName as ToName, 
            T.IDJobPosition ToPos
            FROM $this->_table M                 
            LEFT JOIN $this->_table_prs2 F
                ON M.FromIDUser = F.IDEmployee
            LEFT JOIN $this->_table_prs2 T
                ON M.ToIDUser = T.IDEmployee
            LEFT JOIN $this->_table4 DF
                ON F.IDDepartement = DF.IDStructure
            LEFT JOIN $this->_table4 DT
                ON T.IDDepartement = DT.IDStructure
            WHERE M.IDMemo = '$idmemo'";
        return $this->_db->query($query);
    }
    function get_memo_con($where){
        $this->_db->where($where);
        return $this->_db->get($this->_table);
    }
    function get_folup($where){
        $this->_db->where($where);
        $this->_db->order_by("Count","desc");
        return $this->_db->get($this->_table2);
    }
    function send_memo($record){
        $this->_db->insert($this->_table,$record);
    }
    function send_feed($record){
        $this->_db->insert($this->_table2,$record);
    }
    function update_memo($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_table,$rec);        
    }
    function update_feed($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_table2,$rec);
    }
    function delete_memo($where){
        $this->_db->where($where);
        $this->_db->delete($this->_table);
    }
    function delete_feed($where){
        $this->_db->where($where);
        $this->_db->delete($this->_table2);
    }
    function get_personal($iduser){
        $query      = "SELECT H.*, D.* FROM $this->_table_prs1 H LEFT JOIN $this->_table_prs2 D ON H.IDEmployee = D.IDEmployee WHERE H.IDEmployee = '$iduser'";
        return $this->_db->query($query);
    }
    function get_prs_public($iduser){
        $this->_pbl->where("IDEmployee",$iduser);
        return $this->_pbl->get($this->_table5);
    }
    function insert_attachment($record){
        $this->_db->insert($this->_table3,$record);
    }
    function get_attachment($idmemo){
        $this->_db->where("IDMemo", $idmemo);
        return $this->_db->get($this->_table3);
    }
    function get_employee($where){
        $this->_db->where($where);
        return $this->_db->get($this->_table5);
    }
}
