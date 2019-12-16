<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventory_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('inventory', TRUE);
        $this->_r13 = 'r13countrycode';
    }

  
    
    function checkr13($country,$code,$record) {
        $this->_db->where('Country', $country);
        $this->_db->where('Code', $code);
        $result = $this->_db->get($this->_r13);
        if ($result->num_rows() > 0) {
            $this->update_r13($country,$code,$record);
        } else {
           $this->insert_r13($record);
        }
    }
    
    
    function insert_r13($record){
        $this->_db->insert($this->_r13,$record);
        
    }
    
    function update_r13($country,$code,$record){
        $this->_db->where('Country', $country);
        $this->_db->where('Code', $code);
        $this->_db->update($this->_r13,$record);
    }

 

}
?>
