<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dailysalary_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db         =  $this->load->database('fieldpayroll', TRUE);       
        $this->_table      = 't01dailysalary';
        $this->_employee   = 'triasnet_employee.m01personal';
       
    }
    
 function datadailysalary($from,$until){            
      $a = $this->_table;   
      $b = 'triasnet_employee.m01personal';
      $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,
				 $b.IDJobGroup AS IDJobGroup,
                                 $a.PresenceDate AS PresenceDate,
                                 $a.PresenceStatus AS PresenceStatus,
                                 $a.DailySalaryPayment AS DailySalaryPayment,
                                 $a.MontlyPayment AS MontlyPayment,                              
                                 $a.DailyIncentiveShift AS DailyIncentiveShift,                              
                                 $a.InsurancePayment AS InsurancePayment,                              
                                 $b.FullName AS FullName,
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
				 IF($b.IDJobGroup ='MAG','MAGANG',
				 IF($b.IDJobGroup ='OS','MITRA KERJA',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN','-')))))) AS JobGroup       
                            ",FALSE);
      $this->datatables->from("$a"); 
      $this->datatables->join($b,"$a.IDEmployee = $b.IDEmployee",'left'); 
      $this->datatables->where("$a.DeleteFlag","A"); 
      $this->datatables->where("$b.DeleteFlag","A"); 
      $this->datatables->where("$a.PresenceDate >=",$from); 
      $this->datatables->where("$a.PresenceDate <=",$until); 

      $this->datatables->edit_column('MontlyPayment', '$1', "number_format(MontlyPayment,'2',',','.')");	
      $this->datatables->edit_column('DailyIncentiveShift', '$1', "number_format(DailyIncentiveShift,'2',',','.')");
      $this->datatables->edit_column('InsurancePayment', '$1', "number_format(InsurancePayment,'2',',','.')");			

      return $this->datatables->generate();       
  }  
  
  
  function get_by_id($id){
     $this->_db->select('a.*,b.FullName');
     $this->_db->from($this->_table.' a');
     $this->_db->join($this->_employee.' b','b.IDEmployee = a.IDEmployee','LEFT'); 
     $this->_db->where('a.ID',$id);
     $this->_db->where('a.DeleteFlag','A');
     $this->_db->where('b.DeleteFlag','A');
      $result = $this->_db->get();
      if($result->num_rows()>0){
          return $result->row();          
      }else{
          return 'empty';
      }
      
  } 
  function getall_data($from,$until,$g){   
     $this->_db->from($this->_table.' a');
     $this->_db->join($this->_employee.' b','b.IDEmployee = a.IDEmployee','LEFT');     
     $this->_db->where('a.PresenceDate >=',$from);
     $this->_db->where('a.PresenceDate  <=',$until);
     $this->_db->where('a.DeleteFlag','A');
     $this->_db->where('b.DeleteFlag','A');
     ($g=='AL')?$this->_db->where_in('b.IDJobGroup',array('LT','LK','MAG','OS')):$this->_db->where('b.IDJobGroup',$g);
     $this->_db->order_by('b.IDJobGroup','DESC');
     $this->_db->order_by('b.FullName','ASC');	
      $result = $this->_db->get();
      if($result->num_rows()>0){
          return $result->result_array();          
      }else{
          return 'empty';
      }
      
  }
  
  function get_all_reference(){
      return $this->_db->get('r02addition');
  }
  
  
  
  
  function checkdata($nip,$posting){
      $this->_db->where('DeleteFlag','A');
      $this->_db->where('IDEmployee',$nip);
      $this->_db->where('PresenceDate',$posting);
      $result = $this->_db->get($this->_table);
      if($result->num_rows()>0){
          return 'exist';    
      }else{
          return 'empty';    
      }
      
      /*
      if($result->num_rows()>0){
          $this->insert($record);          
      }else{
          $this->update_employee($nip,$posting,$record);
      }
      * 
      */
      
  }
    
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    
    function update($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table, $record);
    }
    
    function update_employee($id,$date, $record) {
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('PresenceDate', $date);
        $this->_db->update($this->_table, $record);
    }
    
    
    function count_by_id($id) {
        $this->_db->where('ID', $id);
        return $this->_db->count_all_results($this->_table);
    }


}

?>


