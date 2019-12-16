<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Org_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('empcenter',TRUE);
        $this->_tblm = "m03organization";
        $this->_tblmhis = "hism03organization";
    }
    function getall(){
        $query = "
        SELECT S.IDStructure,S.DescStructure, P.IDStructure as IDParent, P.DescStructure as DescParent, S.RelType, S.Level as Level
        FROM $this->_tblm S
        LEFT JOIN $this->_tblm P
        ON P.IDStructure = S.IDStructureParent
        WHERE S.DeleteFlag = 'A'
        ";
        return $this->_db->query($query);
    }
    function maxid(){
        return $this->_db->query(" SELECT MAX(IDStructure) AS max FROM $this->_tblm where DeleteFlag = 'A'");
    }
    function getdata($ids=NULL){
        if ($ids == NULL){
            $query =  "            
            SELECT IDStructure AS idunit, DescStructure as label , Level as level
            FROM $this->_tblm
            WHERE DeleteFlag = 'A'
            ORDER BY DescStructure ASC 
            ";
        }
        else {
            $query = " 
            SELECT S.IDStructure AS idunit, S.IDStructureParent AS idparent, P.DescStructure AS nmparent, S.DescStructure AS descunit, S.RelType AS type, S.Level AS level
            FROM $this->_tblm S
                LEFT JOIN $this->_tblm P
                    ON S.IDStructureParent = P.IDStructure
            WHERE S.IDStructure = '$ids'
            ";
        }
        return $this->_db->query($query);
    }
    function insert($data){
        $this->_db->insert($this->_tblm,$data);
    }
    function inshist($ids){
        $query = "
        INSERT INTO $this->_tblmhis 
        SELECT *
        FROM $this->_tblm
        WHERE IDStructure = '$ids'
        ";
        return $this->_db->query($query);
    }
    function cek_ids($ids){
        $query = "
        SELECT count( * ) as jml
        FROM $this->_tblm
        WHERE idstructureparent = '$ids' and DeleteFlag = 'A'
        ";
        return $this->_db->query($query);
    }
    function cek_parent($idparent){
        $this->_db->where("IDStructure",$idparent);
        return $this->_db->get($this->_tblm);
    }
    function get_children($idparent){
        $this->_db->where(array("IDStructureParent" => $idparent, "DeleteFlag" => "A"));
        return $this->_db->get($this->_tblm);
    }
    function update($where,$record){
        $this->_db->where($where);
        $this->_db->update($this->_tblm,$record);
    }
}