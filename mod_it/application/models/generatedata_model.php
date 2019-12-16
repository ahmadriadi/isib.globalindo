<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Generatedata_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('itsysdev', TRUE);
        $this->_mst01 = 'mst01inventaris';
        $this->_trx01 = 'trx01inventaris';
        $this->_r01 = 'r01inventaris';
        $this->_r02 = 'r02location';  
    }
    

 function checkmst01($item,$counter,$record) {
        $this->_db->where('ItemCode', $item);
        $this->_db->where('CounterCode', $counter);
        $result = $this->_db->get($this->_mst01);
        if ($result->num_rows() > 0) {
             $this->update_mst01($counter, $record);
        } else {
             $this->insert_mst01($record);
        }
    }
    
  function update_mst01($id, $record) {
        $this->_db->where('CounterCode', $id);
        $this->_db->update($this->_mst01, $record);
  }
  
    
    
  function insert_mst01($record) {
        $this->_db->insert($this->_mst01, $record);
    }   

}

?>

