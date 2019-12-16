<?php

class Holiday_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('empcenter', TRUE);	
        $this->_table = 'r02holiday';
    }
    
   function allholiday($from,$until) {
        $a = 'isib_employee.r02holiday';    
        $this->datatables->select("$a.IDHoliday AS IDHoliday,
                                   $a.Date AS HolidayDate, 
                                   $a.Note AS Note 
                                 ", FALSE);
        $this->datatables->from("$a");  
        $this->datatables->where("$a.Date >=", date('Y-m-d',strtotime($from)));
        $this->datatables->where("$a.Date <=", date('Y-m-d',strtotime($until))); 
        return $this->datatables->generate();
    }

    function getall_data() {
        $this->_db->order_by('Date','ASC');
        $result = $this->_db->get($this->_table);        
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }       
      
    }
    
    function checkdata($date){
        $this->_db->where('Date',$date);
        $result = $this->_db->get($this->_table);        
        if($result->num_rows()>0){
            return 'exist';
        }else{
            return 'empty';
        }  
    }

    function get_by_id($id) {
        $this->_db->where('IDHoliday',$id);
        $result = $this->_db->get($this->_table);        
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return null;
        } 
    }
    
    
   function get_holiday($fromdate, $untildate){
       $sql = "SELECT
                a.*
               FROM
                r02holiday a
               WHERE
                 a.Date BETWEEN '$fromdate' AND '$untildate'
                ";
       $result = $this->_db->query($sql);
       if($result->num_rows() > 0 ){
           return $result->result_array();
       }
           return null;
       
   }
    
    function check_holiday($date){
        $this->_db->where('Date', $date);
        $count = $this->_db->count_all_results($this->_table);
        if ($count == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
     function check_annual_leave_deduction($date,$data){
        $this->_db->where('Date', $date);
        $this->_db->where('Flag', $data);        
        $count = $this->_db->count_all_results($this->_table);
        if ($count == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db->where('IDHoliday', $id);
        $this->_db->update($this->_table, $record);
    }

    function delete($id) {
        $this->_db->delete($this->_table, array('IDHoliday' => $id));
    }

    function count_by_id($id) {
        $this->_db->where('IDHoliday', $id);
        return $this->_db->count_all_results($this->_v_table);
    }

    function importdata($id, $record) {
        // cek, jika sdh ada, update
        // kalau blm ada, insert
        $this->_db->where('IDHoliday', $id);
        if ($this->_db->count_all_results($this->_table) > 0) {
            $this->update($id, $record);
        } else {
            $this->insert($record);
        }
    }

}

?>
