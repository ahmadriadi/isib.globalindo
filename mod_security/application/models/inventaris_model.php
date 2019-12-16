<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventaris_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('itsysdev', TRUE);
        $this->_mst01 = 'mst01inventaris';
        $this->_trx01 = 'trx01inventaris';
        $this->_r01 = 'r01inventaris';
        $this->_r02 = 'r02location';
    }

    
    function getr02_byname($name){
        $this->_db->where('Location',$name);
        $result = $this->_db->get($this->_r02);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
        
    }
    
    function checktrx01($inventaris,$record) {
        $this->_db->where('IDInventaris', $inventaris);
        $result = $this->_db->get($this->_trx01);
        if ($result->num_rows() > 0) {
            $this->update_trx01($inventaris, $record);
        } else {
           $this->insert_trx01($record);
        }
    }
    
    
    function insert_trx01($record){
        $this->_db->insert($this->_trx01,$record);
        
    }
    
    function update_trx01($code,$record){
        $this->_db->where('IDInventaris',$code);
        $this->_db->update($this->_trx01,$record);
    }

 

}
?>
