<?php

class Historytable_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('itsysdev', TRUE);
        $this->_dbhis = $this->load->database('itsysdevhis', TRUE);
        $this->_mst01 = 'mst01inventaris';
        $this->_mst02 = 'm02ipaddress';
        $this->_trx01 = 'trx01inventaris';
        $this->_r01 = 'r01inventaris';
        $this->_r02 = 'r02location';
        $this->_r03 = 'r03printbarcode';
        
    }

    function insert_mst01_history($record) {
        $this->_dbhis->insert($this->_mst01, $record);
    }
    
      function insert_mst02_history($record) {
        $this->_dbhis->insert($this->_mst02, $record);
    }

    function insert_trx01_history($record) {
        $this->_dbhis->insert($this->_trx01, $record);
    }

    function insert_r01_history($record) {
        $this->_dbhis->insert($this->_r01, $record);
    }

    function insert_r02_history($record) {
        $this->_dbhis->insert($this->_r02, $record);
    }
    function insert_r03_history($record) {
        $this->_dbhis->insert($this->_r03, $record);
    }

    
      
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
