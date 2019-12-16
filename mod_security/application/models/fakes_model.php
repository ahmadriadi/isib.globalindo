<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fakes_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('empcenter',TRUE);		
        $this->_table ='r05fakes';
    }
    
    
    function getby_id($id){
        $this->_db->where('ID',$id);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return NULL; 
        }
                
    }
    
    
    function getby_code($code,$record){
        $this->_db->where('FaskesCode',$code);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
           $this->update($code,$record);
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
    
    function update_code($code,$record){
       $this->_db->where('FaskesCode',$code); 
       $this->_db->update($this->_table,$record);         
    }
    
    
    function delete($id) {
        $this->_db->delete($this->_table, array('ID' => $id));
    }
   
}

