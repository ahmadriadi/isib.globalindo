<?php

class Historydata_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('empcenter', TRUE);	
        $this->_emp = $this->load->database('empcenterhis', TRUE);	
        $this->_at = $this->load->database('attendancehis', TRUE);	
        $this->_hist1 = 'tmp02tisemployee_his';
		$this->_hist2 = 'tmp03osemployee_his';
		$this->_histcange = 'tmp01changenip_his';
		$this->_picket= 't12employeepicket';
		$this->_holiday= 'r02holiday';
    }
    
   
    
    function insert_his_employeepicket($record){
        $this->_at->insert($this->_picket, $record);
        
    }
    function holiday($record){
        $this->_emp->insert($this->_holiday, $record);
        
    }
    
    function history1() {
        $result = $this->_db->get($this->_hist1);        
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }       
      
    }

    function history2() {
        $result = $this->_db->get($this->_hist2);        
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }       
      
    }
     	
    
    function insert_his_change($record) {
        $this->_db->insert($this->_histcange, $record);
    }

   

}

?>
