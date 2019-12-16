<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Additionleave_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('fieldpayroll',TRUE);		
        $this->_table ='addtionalleave';
    }
        
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    
    function update($nip,$posting,$record){
       $this->_db->where('IDEmployee',$nip); 
       $this->_db->where('PostingDate',$posting); 
       $this->_db->update($this->_table,$record);         
    }
    
    
  
    function insert_or_update($nip,$posting,$record){
       $this->_db->where('IDEmployee',$nip); 
       $this->_db->where('PostingDate',$posting); 
       $result = $this->_db->get($this->_table);
       if($result->num_rows()>0){
           $this->update($nip, $posting, $record);
       }else{
           $this->insert($record);
       }
       
    }
   
}

