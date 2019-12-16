<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Public_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db      = $this->load->database('public',TRUE);
        $this->_db2     = $this->load->database('empcenter',TRUE);
        $this->_tbl1    = 'm01personal';
        $this->_tbl2    = 'm01personal_d';
        $this->_tblr1   = 'r01educationlevel';
        $this->_tblr2   = 'r02familymember';
        $this->_tblr3   = 'r03maritalstatus';
        $this->_tblr4   = 'r04religion';
        $this->_tblr5   = 'r05groupjob';
        $this->_tblr6   = 'm03organization';
        $this->_tblr7   = 'r07unitjob';
        $this->_tblr8   = 'r08position';
        $this->_tblr9   = 'r09location';
        $this->_tblr10  = 'r10education';
        $this->_tblr11  = 'r11majors';
        $this->_tblr12  = 'r12relationship';
    }
    function get_employee($userid=NULL){
    if ($userid != NULL){    
        $this->_db->where('IDEmployee',$userid);
        $query = $this->_db->get($this->_tbl1);
    }
    else{
        $query = $this->_db->query("
            SELECT IDEmployee, FullName as label 
            FROM $this->_tbl1
            WHERE Status = 'A' and IDEmployee != '$userid'
            ORDER BY FullName ASC
         ");
    }
    return $query;
    }
    function get_unitjob($unitcode=NULL){
        if ($unitcode == NULL){
            $query = $this->_db->query("
                SELECT UnitDesc as label, UnitCode
                FROM $this->_tblr7");
        }
        else {
            $this->_db->where("UnitCode",$unitcode);
            $query = $this->_db->get($this->_tblr7);
        }
        return $query;
    }
    function get_departement(){
        return $this->_db2->get($this->_tblr6);
    }
    function get_position(){
        $this->_db->order_by("ID","DESC");
        return $this->_db->get($this->_tblr8);
    }
    function get_relation(){
        $this->_db->order_by("IDStatus","DESC");
        return $this->_db->get($this->_tblr3);
    }
    function get_edulevel(){
        $this->_db->order_by("ID","DESC");
        return $this->_db->get($this->_tblr10);
    }
    function get_location(){
        return $this->_db->get($this->_tblr9);
    }
    function get_jobgroup(){
        return $this->_db->get($this->_tblr5);
    }
    function get_religion(){
        $this->_db->order_by("ID", "DESC");
        return $this->_db->get($this->_tblr4);
    }
    function get_data($uid) {
        $query  = $this->_db->query("
                SELECT D.*,H.EmailInternal,H.EmailExternal,H.Extension, H.BankAccount
                FROM $this->_tbl2 D
                LEFT JOIN $this->_tbl1 H
                ON H.IDEmployee = D.IDEmployee
                WHERE D.IDEmployee = '$uid'
                ");
        if ($query->num_rows()==1) {
            return $query->row();
        } else {
            return NULL; 
        }
    }
    function get_majors($majcode=NULL){
        if ($majcode == NULL){
            $query = $this->_db->query("
                SELECT MajCode, MajDesc as label
                FROM $this->_tblr11
                ORDER BY MajDesc ASC
                    ");
        }
        else {
            $this->_db->where("MajCode",$majcode);
            $query = $this->_db->get($this->_tblr11);
        }
        return $query;
    }
    function updateh($where,$record){
        $this->_db->where($where);
        $this->_db->update($this->_tbl1,$record);
    }
    function updated($where,$record){
        $this->_db->where($where);
        $this->_db->update($this->_tbl2,$record);
    }
    function insert($record) {
        $this->_db->insert($this->_tbl2, $record);
    }
   
}
