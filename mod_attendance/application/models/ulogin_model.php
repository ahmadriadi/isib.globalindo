<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ulogin_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db_scr = $this->load->database('security', TRUE);
        $this->_db_emp = $this->load->database('empcenter', TRUE);
        $this->_table = 't01userlogin';
        $this->_personal = 'isib_employee.m01personal';
    }

    function datalogin() {
        $a = 'isib_security.t01userlogin';
        $b = $this->_personal;
        $this->datatables->select("$a.ID AS ID,                                 
                                 $a.Username AS Username,            
                                 IF($a.Role='0','User',
                                 IF($a.Role='1','Module Admin',
                                 IF($a.Role='2','Super Admin','-')))AS RoleUser,            
                                 $b.FullName AS FullName,                                     
                                 IF($b.IDJobGroup ='ST','STAFF',	
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN','-'))))) AS JobGroup   
                                 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.Username = $b.IDEmployee", 'left');
        $this->datatables->where("$a.Status", "A");
        $this->datatables->where("$b.Status", "A");
        return $this->datatables->generate();
    }

    function check_username($uid) {
        $this->_db_scr->where('Username', $uid);
        $this->_db_scr->where('Status', 'A');
        $this->_db_scr->limit(1);
        $query = $this->_db_scr->get($this->_table);
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return NULL;
        }
    }

    function insert($record) {
        $this->_db_scr->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db_scr->where('Username', $id);
        $this->_db_scr->update($this->_table, $record);
    }

     
    function update_modpublic(){
       $sql1 = "UPDATE user_menuaccess SET Access = 1 where IDMenu ='140'"; 
       $sql2 = "UPDATE user_menuaccess SET Access = 1 where IDMenu ='141'"; 
       $sql3 = "UPDATE user_menuaccess SET Access = 1 where IDMenu ='142'"; 
       $sql4 = "UPDATE user_menuaccess SET Access = 1 where IDMenu ='143'"; 
       $sql5 = "UPDATE user_menuaccess SET Access = 1 where IDMenu ='262'"; 
       
       $sql6 = "UPDATE user_buttonaccess SET Access = 1 where IDMenu ='140'"; 
       $sql7 = "UPDATE user_buttonaccess SET Access = 1 where IDMenu ='141'"; 
       $sql8 = "UPDATE user_buttonaccess SET Access = 1 where IDMenu ='142'"; 
       $sql9 = "UPDATE user_buttonaccess SET Access = 1 where IDMenu ='143'"; 
       $sql10 = "UPDATE user_buttonaccess SET Access = 1 where IDMenu ='262'"; 
       
       $this->_db_scr->query($sql1);
       $this->_db_scr->query($sql2);
       $this->_db_scr->query($sql3);
       $this->_db_scr->query($sql4);
       $this->_db_scr->query($sql5);
       
       $this->_db_scr->query($sql6);
       $this->_db_scr->query($sql7);
       $this->_db_scr->query($sql8);
       $this->_db_scr->query($sql9);
       $this->_db_scr->query($sql10);
        
    }

    function delete($id) {
        $this->_db_scr->delete($this->_table, array('ID' => $id));
    }

    function get_by_id($id) {
        $this->_db_scr->select('a.*,b.FullName,b.IDJobGroup');
        $this->_db_scr->from($this->_table . ' a');
        $this->_db_scr->join($this->_personal . ' b', 'a.Username=b.IDEmployee', 'left');
        $this->_db_scr->where('a.Status', 'A');
        $this->_db_scr->where('a.ID',$id);
        $result = $this->_db_scr->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

}

