<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Uac_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('security',TRUE);
        $this->_db2  = $this->load->database('empcenter',TRUE);
        $this->_tbl1 = 'menu_module';
        $this->_tbl2 = 't01userlogin';
        $this->_tbl3 = 'm01personal';
        $this->_tbl4 = 'user_menuaccess';
        $this->_tbl5 = 'user_buttonaccess';
        $this->_tbl6 = 'menu_button';
    }
    function cek_pwd($idemployee,$pwd){
        $this->_db->where("Username",$idemployee);
        $this->_db->where("Password",md5($idemployee.$pwd));
        return $this->_db->get($this->_tbl2);
    }
    function get_user($value=NULL,$find=NULL){
        if ($value == NULL and $find == NULL){
//            $query  = $this->_db2->query("SELECT * FROM $this->_tbl3 WHERE IDJobGroup = 'ST' AND ResignDate IS NULL");    
            $query  = $this->_db2->query("SELECT * FROM $this->_tbl3 WHERE ResignDate IS NULL");    
        }
        else if ($value != NULL and $find == NULL){
            $query  = $this->_db2->query("SELECT * FROM $this->_tbl3 WHERE IDEmployee = '$value'");
        }
        else if ($value != NULL and $find == "any"){
            $query  = $this->_db2->query("SELECT * FROM $this->_tbl3 WHERE IDEmployee like '%$value%' OR FullName like '%$value%'");
        }
        return $query;
    }
    function get_role($iduser){
        $this->_db->where("Username",$iduser);
        return $this->_db->get($this->_tbl2);
    }
    function get_button($idmenu,$idbutton=NULL){
        if ($idmenu != NULL and $idbutton != NULL){
            $this->_db->where("IDMenu",$idmenu);
            $this->_db->where("IDButton",$idbutton);            
        }
        else if ($idmenu != NULL and $idbutton == NULL){
            $this->_db->where("IDMenu",$idmenu);
        }
        else if ($idmenu == NULL and $idbutton == NULL){
            
        }
        return $this->_db->get($this->_tbl6);
    }
    function get_access($iduser=NULL,$idmenu=NULL){
        if ($iduser == NULL and $idmenu == NULL){
            $query  = $this->_db->get($this->_tbl4);
        }
        elseif($iduser != NULL and $idmenu == NULL){
            $q      = "Select m.MenuDesc as menu, a.IDMenu as idmenu, a.Access as access from $this->_tbl4 a LEFT JOIN $this->_tbl1 m on a.IDMenu = m.IDMenu Where a.IDuser = '$iduser'";
            $query  = $this->_db->query($q);
        }
        elseif($iduser != NULL and $idmenu != NULL){
            $q      = "Select m.MenuDesc as menu,m.Level as level, m.IDMenu as idmenu, a.Access as access from $this->_tbl4 a LEFT JOIN $this->_tbl1 m on a.IDMenu = m.IDMenu Where a.IDuser = '$iduser' and a.IDMenu = '$idmenu'";
            $query  = $this->_db->query($q);            
        }
        return $query;
    }
    function get_btnaccess($iduser,$idmenu){
        $q  = "SELECT b.ButtonDesc as btndesc,b.KdButton as kdbutton, a.IDButton as idbutton, a. IDMenu as idmenu, a.Access as access FROM $this->_tbl5 a LEFT JOIN $this->_tbl6 b on a.IDMenu = b.IDMenu and a.IDButton = b.IDButton WHERE a.IDUser = '$iduser' and a.IDMenu = '$idmenu' AND b.IDButton IS NOT NULL ";
        return $this->_db->query($q);
    }
    function update_access($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_tbl4,$rec);
    }
    function update_btnaccess($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_tbl5,$rec);        
    }
    function update_role($iduser,$role){
        $rec = array ("Role" => $role);
        $this->_db->where("Username",$iduser);
        $this->_db->update($this->_tbl2,$rec);
    }
    function add_access($record){
        $this->_db->insert($this->_tbl4,$record);
    }    
}
