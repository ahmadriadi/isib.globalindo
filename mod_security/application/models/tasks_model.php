<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tasks_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        $this->emp    = $this->load->database("empcenter",TRUE);
        $this->att    = $this->load->database("attendance",TRUE);
        $this->pbl    = $this->load->database("public",TRUE);
        $this->temp1a = "m01personal";
        $this->temp1b = "m01personal_d";
        $this->temp2a = "t01leavetrx";
        $this->temp3a = "t03memo";
        $this->temp3b = "t03memo_folup";
        $this->temp4a = "m04param";
        $this->tatt7a = "t07officialtravel";
        $this->tatt8a = "t08leavepermit";
        $this->tatt8b = "t08leavepermit_d";
        $this->tatt4a = "t04overtime";
        $this->tatt5a = "t05incomplete";
        $this->tpbl1a = "t01rootcause";
        $this->tpbl2a = "m01personal_job";
        $this->digitalform_h = "t02digitalform_h";
        $this->personal = "isib_employee.m01personal_d";
        
        
    }
    
   
    
    
    function get_hrd($where){
        $this->emp->where($where);
        return $this->emp->get($this->temp4a);
    }
    function get_upar($where=NULL){
        if ($where != NULL){
            $this->emp->where($where);
        }
        return $this->emp->get($this->temp4a);        
    }
    function get_personal($iduser){
        $query  = "SELECT H.*,D.* FROM $this->temp1a H LEFT JOIN $this->temp1b D ON H.IDEmployee = D.IDEmployee WHERE H.IDEmployee = '$iduser'";
        return $this->emp->query($query);
    }
    function get_memo($where){
        $this->emp->where($where);
        return $this->emp->get($this->temp3a);        
    }
    function get_memo_in($where){
        $this->emp->where($where);
        return $this->emp->get($this->temp3a);
    }
    function get_memo_feed($iduser){
        $query = "
            SELECT M.*,F.*
            FROM $this->temp3a M
            LEFT JOIN $this->temp3b F
            ON M.IDMemo = F.IDMemo
            WHERE 
            M.FromIDUser = '$iduser'
            AND 
            F.FolRead  = '0'
            ";
        return $this->emp->query($query);
    }
    function get_leavereq($where){
        $this->emp->where($where);
        return $this->emp->get($this->temp2a);
    }
    function get_lpermit($where){
        $this->att->where($where);
        return $this->att->get($this->tatt8a);
    }
    function get_officialtravel($where){
        $this->att->where($where);
        return $this->att->get($this->tatt7a);        
    }
    function get_overtime($where){
        $this->att->where($where);
        return $this->att->get($this->tatt4a);          
    }
    function get_incomplete($where){
        $this->att->where($where);
        return $this->att->get($this->tatt5a);
    }
    function get_itofcr($wh){
        $this->emp->select("E.FullName AS EName, E.IDEmployee AS IDEmp");
        $this->emp->from("$this->temp4a AS P");
        $this->emp->join("$this->temp1a AS E","P.ParamValue = E.IDEmployee");
        $this->emp->where($wh);
        return $this->emp->get();
    }
    function get_hodconf($idhod){
        $query  = "SELECT * FROM $this->tpbl1a WHERE DeleteFlag = 'A' AND HoDConf = '0' AND AddedBy IN (
            SELECT IDEmployee FROM $this->tpbl2a WHERE IDEmployeeParent = '$idhod'
            )";
        return $this->pbl->query($query);
    }
}


