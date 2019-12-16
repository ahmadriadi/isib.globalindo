<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manualdeduction_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db         =  $this->load->database('fieldpayroll', TRUE);       
        $this->_table      = 't04deductionmanual';
        $this->_employee   = 'triasnet_employee.m01personal';
       
    }
    
 function datadeduction($from,$until){            
      $a = $this->_table;   
      $b = 'triasnet_employee.m01personal';
      $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,
				 $b.IDJobGroup AS IDJobGroup,
                                 $a.PostingDate AS PostingDate,
                                 $a.Amount AS Amount,
                                 $a.Parameter AS Parameter,
                                 $a.Note AS Note,                              
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
      $this->datatables->where("$a.PostingDate >=",$from); 
      $this->datatables->where("$a.PostingDate <=",$until); 

      $this->datatables->edit_column('Amount', '$1', "number_format(Amount,'2',',','.')");
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
     $this->_db->where('a.PostingDate >=',$from);
     $this->_db->where('a.PostingDate  <=',$until);
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
      return $this->_db->get('r01deduction');
  }
  
  
  
  
  function checkdata($nip,$posting){
      $this->_db->where('DeleteFlag','A');
      $this->_db->where('IDEmployee',$nip);
      $this->_db->where('PostingDate',$posting);
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
        $this->_db->where('PostingDate', $date);
        $this->_db->update($this->_table, $record);
    }
    
    
    function count_by_id($id) {
        $this->_db->where('ID', $id);
        return $this->_db->count_all_results($this->_table);
    }


}

?>


