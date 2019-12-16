<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Paramlate_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_table = 'paramlate';
    }

    function getdata() {
        $a = $this->_table;
        $this->datatables->select("$a.ID AS ID,
                                 $a.ParamDate AS ParamDate,                              
                                 $a.ParamSite AS ParamSite,  
                                 IF($a.ParamSite ='1','KAPUK','BITUNG') AS Lokasi,       
                                 $a.StartTimeLate AS StartTimeLate,                                   
                                 $a.Note AS Note,                                   
                            ", FALSE);
        $this->datatables->from("$a");
        return $this->datatables->generate();
    }

    function getby_id($id) {
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function getall_data() {
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function checkdata($date, $site) {
        $this->_db->where('ParamDate', $date);
        $this->_db->where('ParamSite', $site);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
          return  'exist';
        } else {
          return  'empty';
        }
    }

    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table, $record);
    }

    function delete($id) {
        $sql = "DELETE FROM $this->_table WHERE ID='$id'";
        $this->_db->query($sql);
    }

}
?>

