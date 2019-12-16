<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rootcouse_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('public',TRUE);		
        $this->_table ='r01rootcause';
    }
        
   function getdata() {
        $a = 'triasnet_public.'.$this->_table ;
        $this->datatables->select("$a.IDRoot AS IDRoot,                                 
                                    $a.RootName AS RootName 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }
    
    
    function getby_id($id){
        $this->_db->where('IDRoot',$id);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return NULL; 
        }
                
    }
    
    
    function getby_name($code){
        $this->_db->where('RootName',$code);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
           return 'exist';
        } else {
           return 'empty';
        }
                
    }  
    
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    
    function update($id,$record){
       $this->_db->where('IDRoot',$id); 
       $this->_db->update($this->_table,$record);         
    }
    
  
}

