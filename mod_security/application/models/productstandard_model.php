<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Productstandard_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('estimasi',TRUE);		
        $this->_table ='productstandard';
    }
        
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    
    function update($group,$partid,$record){
       $this->_db->where('CategoryGroup',$group); 
       $this->_db->where('PartID',$partid); 
       $this->_db->update($this->_table,$record);         
    }
    
    
  
    function insert_or_update($group,$partid,$record){
       $this->_db->where('CategoryGroup',$group); 
       $this->_db->where('PartID',$partid); 
       $result = $this->_db->get($this->_table);
       if($result->num_rows()>0){
           $this->update($group,$partid, $record);
       }else{
           $this->insert($record);
       }
       
    }
   
}

