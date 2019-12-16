<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Menu_model extends CI_Model {

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
    function get_button($idmenu=NULL,$idbutton=NULL,$where=NULL){
        if ($idmenu != NULL and $idbutton == NULL and $where==NULL){
            $this->_db->where("IDMenu",$idmenu);      
        }
        else if ($idmenu != NULL and $idbutton != NULL and $where==NULL){
            $this->_db->where("IDMenu",$idmenu);
            $this->_db->where("IDButton",$idbutton);
        }
        else if ($idmenu == NULL and $idbutton == NULL and $where!=NULL){
            $this->_db->where($where);
        }
        return $this->_db->get($this->_tbl6);    
    }
    function add_btn($rec){
        $this->_db->insert($this->_tbl6,$rec);
    }
    function update_button($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_tbl6,$rec);
    }
    function del_button($where){
        $this->_db->where($where);
        $this->_db->delete($this->_tbl6);
        $this->del_button_user($where);
    }
    function del_button_user($where){
        $this->_db->where($where);
        $this->_db->delete($this->_tbl5);       
    }
    function get_user(){
        $query  = $this->_db2->query("SELECT * FROM $this->_tbl3 WHERE IDJobGroup = 'ST' AND ResignDate IS NULL");
        return $query;
    }
    function get_access($idmenu=NULL,$iduser=NULL){
        if ($idmenu == NULL and $iduser == NULL){
            $query = $this->_db->get($this->_tbl4);
        }else if ($idmenu != NULL and $iduser == NULL){
            $this->_db->where("IDMenu",$idmenu);
            $query = $this->_db->get($this->_tbl4);
        }else if ($idmenu != NULL and $iduser != NULL){
            $this->_db->where("IDMenu",$idmenu);
            $this->_db->where("IDUser",$iduser);
            $query = $this->_db->get($this->_tbl4);
        }
        return $query;
    }
    function get_btnaccess($idmenu,$idbutton,$iduser=NULL){
        if ($iduser == NULL){
            $this->_db->where("IDMenu",$idmenu);
            $this->_db->where("IDButton",$idbutton);
        }
        else if ($iduser != NULL and $idbutton == NULL){
            $this->_db->where("IDMenu",$idmenu);
            $this->_db->where("IDUser",$iduser);
        }
        return $this->_db->get($this->_tbl5);
    }
    function add_access($record){
        $this->_db->insert($this->_tbl4,$record);
    }
    function add_btnaccess($record){
        $this->_db->insert($this->_tbl5,$record);
    }
    function add_menu($record){
        $this->_db->insert($this->_tbl1,$record);
    }
    function update_menu($where,$record){
        $this->_db->where($where);
        $this->_db->update($this->_tbl1,$record);
    }
    function delete_menu($where){
        $idparent    = $where['IDMenu'];
        $child = $this->get_child($idparent)->result();
        if ($child != NULL){ // jika punya child, hapus childnya terlebih dahulu
            foreach ($child as $c){
                $subchild   = $this->get_child($c->IDMenu)->result();
                if ($subchild != NULL){// jika child punya child lagi, hapus childnya child terlebih dahulu
                    foreach ($subchild as $sc){
                        $this->_db->where("IDMenu",$sc->IDMenu);
                        $this->_db->delete($this->_tbl1);
                        $where1  = array("IDMenu" => $sc->IDMenu);
                        $this->delete_menu_user($where1);
                        $this->del_button($where1);
                    }
                }
                $this->_db->where("IDMenu",$c->IDMenu);
                $this->_db->delete($this->_tbl1);   
                $where2  = array("IDMenu" => $c->IDMenu);
                $this->delete_menu_user($where2);
                $this->del_button($where2);
            }
        }
        $this->_db->where($where);
        $this->_db->delete($this->_tbl1);
        $this->delete_menu_user($where);
        $this->del_button($where);
    }
    function delete_menu_user($where){
        $this->_db->where($where);
        $this->_db->delete($this->_tbl4);        
    }
    function get_menu($id=NULL,$p=NULL,$loc=NULL){
        if ($p == NULL){
            if ($id == NULL){
                $this->_db->order_by("ID","ASC");
                $query = $this->_db->get($this->_tbl1);
            }
            else{
                $this->_db->where("IDMenu",$id);
                $query = $this->_db->get($this->_tbl1);
            }
        }
        else{
            $query = $this->_db->query("SELECT * FROM $this->_tbl1 WHERE HasSubMenu = 1");
//            $query = $this->_db->query("SELECT MenuDesc AS label, IDMenu AS idmenu FROM $this->_tbl1 WHERE HasSubMenu = 1");
        }
        return $query;
    }
    function get_head(){
        $this->_db->where("IDParent","0");
        return $this->_db->get($this->_tbl1);
    }
    function get_child($idparent,$hassub=NULL){
        $this->_db->where("IDParent",$idparent);
        if($hassub == '0'){
            $this->_db->where("HasSubMenu", "0");
        }
        elseif($hassub == '1'){
            $this->_db->where("HasSubMenu", "1");
        }
        $this->_db->order_by("HasSubMenu", "asc");
        $this->_db->order_by("MenuDesc", "asc"); 
        return $this->_db->get($this->_tbl1);
    }
    function get_jml_child($idparent){
        $this->_db->where("IDParent",$idparent);
        $child = $this->_db->get($this->_tbl1);
        $jml   = $child->num_rows();
        $i = 0;
        $jmlchild[$i] = 0;
        foreach ($child->result() as $c){
            $i++;
            if ($c->HasSubMenu == 1){
                $this->_db->where("IDParent",$c->IDMenu);
                $cjml = $this->_db->get($this->_tbl1)->num_rows();
                $jmlchild[$i] = $cjml;
            }
        }
        $jmlc = array_sum($jmlchild);
        return $jml+$jmlc;
    }
    function getlastid(){
        return $this->_db->query("SELECT MAX(IDMenu)AS maxid FROM $this->_tbl1");
    }
    
}
