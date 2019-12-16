<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personalfield_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db         =  $this->load->database('fieldpayroll', TRUE);       
        $this->_table      = 'm01fieldpayroll';
        $this->_employee   = 'triasnet_employee.m01personal_d';
	$this->_param      = 'prm02payroll';
       
    }


 function getparam_payroll(){
     $this->_db->where('DeleteFlag','A');
     $this->_db->order_by('ID','DESC');
     $result = $this->_db->get($this->_param);
     if($result->num_rows()>0){
         return $result->row();
     }else{
         return 'empty';
     }
     
 }  
    
 function datafieldemployee(){            
      $a = $this->_table;   
      $b = 'triasnet_employee.m01personal_d';
      $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,
                                 $b.BankAccount AS BankAccountNo,
                                 $a.MonthlySalary AS MonthlySalary,
				 $b.IDJobGroup AS IDJobGroup,
                                 $a.Insurance AS Insurance,
				 $a.BPJS AS BPJS,	
                                 $a.DailySalary AS DailySalary  ,
                                 $a.OvertimePerHour AS OvertimePerHour,                                
                                 $a.OvertimeMeal AS OvertimeMeal,
                                 $a.OvertimeIncentivePaid AS OvertimeIncentivePaid,                                 
                                 $b.FullName AS FullName,
				 $b.Status AS Status,
                                 IF($b.Status ='A','Active','Passive') AS StatusKaryawan, 
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
      //$this->datatables->where("$b.Status","A");	
       $this->datatables->edit_column('MonthlySalary', '$1', "number_format(MonthlySalary,'2',',','.')");
       $this->datatables->edit_column('Insurance', '$1', "number_format(Insurance,'2',',','.')");
       $this->datatables->edit_column('BPJS', '$1', "number_format(BPJS,'2',',','.')");		
       $this->datatables->edit_column('DailySalary', '$1', "number_format(DailySalary,'2',',','.')");
	
       $this->datatables->edit_column('OvertimePerHour', '$1', "number_format(OvertimePerHour,'2',',','.')");
       $this->datatables->edit_column('OvertimeMeal', '$1', "number_format(OvertimeMeal,'2',',','.')");
       	
    
		
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
 function getall_data($g){
     $this->_db->select('a.*,b.FullName,b.BankAccount as BankAccountNo,b.IDJobGroup,b.IDLocation,b.HireDate');
     $this->_db->from($this->_table.' a');
     $this->_db->join($this->_employee.' b','b.IDEmployee = a.IDEmployee','LEFT');     
     $this->_db->where('a.DeleteFlag','A');
     $this->_db->where('b.DeleteFlag','A');
     $this->_db->where('b.Status','A');
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
  
  function check($nip){
      $this->_db->where('IDEmployee',$nip);      
      $result = $this->_db->get($this->_table);
       if($result->num_rows()>0){
           return 'exist';    
      }else{
          return null;
      }
  }
  
  function checkdata($nip,$record){
      $this->_db->where('IDEmployee',$nip);     
      $result = $this->_db->get($this->_table);
      if($result->num_rows()>0){
          $this->insert($record);          
      }else{
          $this->update_employee($nip,$record);
      }
      
  }
    
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    
    function update($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table, $record);
    }
    
    function update_employee($id, $record) {
        $this->_db->where('IDEmployee', $id);
        $this->_db->update($this->_table, $record);
    }
    
    
    function count_by_id($id) {
        $this->_db->where('ID', $id);
        return $this->_db->count_all_results($this->_table);
    }


}

?>


