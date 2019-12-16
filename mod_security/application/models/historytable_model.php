<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Historytable_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('hispublic', TRUE);
        $this->_rootcause   = 't01rootcause';      
        $this->_pmail   = 'p01emailroot';      
    }
    

    
    function insert_rootcause($record) {
        $this->_db->insert($this->_rootcause, $record);
    }
    function insert_pmail($record) {
        $this->_db->insert($this->_pmail, $record);
    }
        
 
}

?>
