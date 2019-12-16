<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tools_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('empcenter', TRUE);
        $this->_pbl      =  $this->load->database('public', TRUE);
        $this->_dbhis      =  $this->load->database('empcenterhis', TRUE);
	$this->_personal_h = 'm01personal';    
	$this->_personal_d = 'm01personal_d';    
	$this->_tmp = 'tmp04noakdhk';    
    }
    
    function get_tmp(){
      $result = $this->_db->get($this->_tmp);
      if($result->num_rows()>0){
          return $result->result_array();
      }else{
          return 'empty';
          
      }
    }
    
    
  function getdatahis_personal_h($nip){
      $this->_dbhis->where('IDEmployee',$nip);
      $this->_dbhis->order_by('ID','DESC');
      $result = $this->_dbhis->get($this->_personal_h);
      if($result->num_rows()>0){
          return $result->row();
      }else{
          return 'empty';
          
      }
  }  
  function getdatahis_personal_d($nip){
      $this->_dbhis->where('IDEmployee',$nip);
      $this->_dbhis->order_by('ID','DESC');
      $result = $this->_dbhis->get($this->_personal_d);
      if($result->num_rows()>0){
          return $result->row();
      }else{
          return 'empty';
          
      }
  }
  
  
  function update_personal_h($nip,$record){
      $this->_db->where('IDEmployee',$nip);
      $this->_db->update($this->_personal_h, $record);
  }
    
  function update_personal_d($nip,$record){
      $this->_db->where('IDEmployee',$nip);
      $this->_db->update($this->_personal_d, $record);
  }
    
 
  function update_personal_pbl($nip,$record){
      $this->_pbl->where('IDEmployee',$nip);
      $this->_pbl->update($this->_personal_h, $record);
  }
    
 
}

?>
