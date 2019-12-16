<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fakes_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('empcenter',TRUE);		
        $this->_table ='isib_employee.r05fakes';
    }
    
      
   function getdata() {
        $a = $this->_table;       
        $this->datatables->select("$a.ID AS ID,
                                 $a.DivreCode AS DivreCode,                              
                                 $a.RegionalName AS RegionalName,                                   
                                 $a.KCName AS KCName,                                   
                                 $a.DatiName2 AS DatiName2,                                   
                                 $a.FaskesCode AS FaskesCode,                                   
                                 $a.FakesName AS FakesName,                                   
                                 $a.FakesType AS FakesType,                                   
                                 $a.FakesAddress AS FakesAddress,                                   
                            ", FALSE);
        $this->datatables->from("$a");     
        return $this->datatables->generate();
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
    
     function getall_data(){
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return NULL; 
        }
                
    }
    
    
    function getby_code($code,$record){
        $this->_db->where('FaskesCode',$code);
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

