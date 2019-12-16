<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Officialtravel_trx_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->_dbatt = $this->load->database('attendance', TRUE);
        $this->_dbemp = $this->load->database('empcenter', TRUE);
        $this->_pbl = $this->load->database('public', TRUE);
        $this->_table = 't07officialtravel';
//        $this->_table2 = 't08leavepermit_d';
        $this->_table_prs1 = 'm01personal';
        $this->_table_prs2 = 'm01personal_d';
    }
    function get_travel($where=NULL){
        if ($where != NULL){
            $this->_dbatt->where($where);            
        }

        return $this->_dbatt->get($this->_table);
    }
    function get_personal($iduser){
        $query      = "SELECT H.*, D.* FROM $this->_table_prs1 H LEFT JOIN $this->_table_prs2 D ON H.IDEmployee = D.IDEmployee WHERE H.IDEmployee = '$iduser'";
        return $this->_dbemp->query($query);
    }
//    function  get_lastid(){
//        $this->_dbatt->select_max("IDTravel");
//        return $this->_dbatt->get($this->_table); 
//    }
    function insert_travel($rec){
        $this->_dbatt->insert($this->_table,$rec);
    }
    function update_travel($where,$rec){
        $this->_dbatt->where($where);
        $this->_dbatt->update($this->_table,$rec);
    }
    function delete_travel($where){
        $this->_dbatt->where($where);
        $this->_dbatt->delete($this->_table);
    }
    function get_prs_public($iduser){
        $this->_pbl->where("IDEmployee",$iduser);
        return $this->_pbl->get($this->_table_prs1);
    }    
}
