<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Proot_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_pbl = $this->load->database('public', TRUE);
        $this->_emp = $this->load->database('empcenter', TRUE);
        $this->_personal = 'm01personal_d';
        $this->_table = 'p01emailroot';
    }

    function getdata() {
        $a = 'isib_public.' . $this->_table;
        $b = 'isib_employee.' . $this->_personal;
        $this->datatables->select("$a.ID AS ID,                                 
                                   $a.IDEmployee AS IDEmployee,       
                                   $b.FullName AS FullName,       
                                    IF ($a.RootSite = '1', 'Kapuk',
                                    IF ($a.RootSite = '2', 'Bitung','-'
                                    )) AS Location,    
                                   $a.Note AS Note,
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$a.DeleteFlag", 'A');
        return $this->datatables->generate();
    }

    function getall_data() {
        $sql = "  SELECT a.*,b.FullName
                FROM $this->_table a
                LEFT JOIN isib_employee.$this->_personal b ON a.IDEmployee=b.IDEmployee
                WHERE 
                b.Status='A' AND
                a.DeleteFlag='A'
            ";
        $result = $this->_pbl->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function getby_id($id) {
        $sql = "SELECT a.*,b.FullName
                FROM $this->_table a
                LEFT JOIN isib_employee.$this->_personal b ON a.IDEmployee=b.IDEmployee
                WHERE 
                b.Status ='A' AND
                a.ID ='$id' AND
                a.DeleteFlag='A'
            ";
        $result = $this->_pbl->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function checkdata($nip, $location) {
        $this->_pbl->where('IDEmployee', $nip);
        $this->_pbl->where('RootSite', $location);
        $this->_pbl->where('DeleteFlag', 'A');
        $result = $this->_pbl->get($this->_table);
        if ($result->num_rows() > 0) {
            return 'exist';
        } else {
            return null;
        }
    }

    function insert($record) {
        $this->_pbl->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_pbl->where('ID', $id);
        $this->_pbl->update($this->_table, $record);
    }

}

