<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Term_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('inventory',TRUE);		
        $this->_table ='r09paymentterm';
    }
    
    
    function getby_id($id,$record){
        $this->_db->where('ID',$id);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            $this->update($id, $record);
        } else {
            $this->insert($record);
        }
                
    }
    
    
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    
    function update($id,$record){
       $this->_db->where('ID',$id); 
       $this->_db->update($this->_table,$record);         
    }
   
    
    function delete($id) {
        $this->_db->delete($this->_table, array('ID' => $id));
    }
   
}

