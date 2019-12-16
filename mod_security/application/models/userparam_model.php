<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Userparam_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('empcenter',TRUE);		
        $this->_table ='m04param';
        $this->_personal = 'm01personal';
    }
    
    
     function dataparam() {
        $a = $this->_table;
        $b = $this->_personal;
        $this->datatables->select("$a.ID AS ID,                                 
                                 $a.IDParam AS IDParam,
                                 $a.ParamValue AS ParamValue,
                                 $a.Note AS Note,                                                
                                 $b.FullName AS FullName,
                                 IF($b.IDJobGroup ='ST','STAFF',	
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN','-'))))) AS JobGroup   
                                 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.ParamValue = $b.IDEmployee", 'left');   
        return $this->datatables->generate();
    }
    
    function get_by_id($id){
        $this->_db->where('ID',$id);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return NULL; 
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

