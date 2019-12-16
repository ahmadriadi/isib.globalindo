<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('production', TRUE);
        $this->_table = 'ref03product';
     
    }

    function getdata() {
        $a = $this->_table;
        $this->datatables->select("$a.IDRecord AS IDRecord,                                 
                                   $a.IDProduct AS IDProduct,
                                   $a.Description AS Description,
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }


    function getall_data() {
        $this->_db->where('DeleteFlag', 'A');
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

  

    function getby_id($id) {
        $this->_db->where('IDRecord', $id);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

  

    function checkdata($product) {
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('IDProduct', $product);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
  

    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    function update($id, $record) {
        $this->_db->where('IDRecord', $id);
        $this->_db->update($this->_table, $record);
    }

  
}
?>

