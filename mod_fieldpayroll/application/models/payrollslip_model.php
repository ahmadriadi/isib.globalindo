<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Payrollslip_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db         =  $this->load->database('fieldpayroll', TRUE);       
        $this->_table      = 't05payrollslip';
	$this->_masterpersonal      = 'm01fieldpayroll';
        $this->_employee   = 'triasnet_employee.m01personal';
        $this->_employee_d   = 'triasnet_employee.m01personal_d';
       
    }
    
 function datapayrollslip($from,$until){
      $a = $this->_table;   
      $b = 'triasnet_employee.m01personal';
      $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,
				 $b.IDJobGroup AS IDJobGroup,
                                 $a.PostingDate AS PostingDate,
                                 $a.SumDailySalaryPayment AS SumDailySalaryPayment,                                
                                 $a.SumDailyIncentiveShift AS SumDailyIncentiveShift,                              
                                 $a.SumDailyOvertimePayment AS SumDailyOvertimePayment,                              
                                 $a.OtherIncome AS OtherIncome,                              
                                 $a.InsurancePayment AS InsurancePayment,                              
                                 $a.AbsencePayment AS AbsencePayment,                              
                                 $a.LoanPayment AS LoanPayment,                              
                                 $a.OutstandingPayment AS OutstandingPayment,                              
                                 $a.OtherPayment AS OtherPayment,                              
                                 $a.TotalIncome AS TotalIncome,                              
                                 $a.TotalDeduction AS TotalDeduction,                              
                                 $a.TakeHomePay AS TakeHomePay,  
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

      $this->datatables->edit_column('SumDailySalaryPayment', '$1', "number_format(SumDailySalaryPayment,'2',',','.')");
      $this->datatables->edit_column('SumDailyIncentiveShift', '$1', "number_format(SumDailyIncentiveShift,'2',',','.')");
      $this->datatables->edit_column('SumDailyOvertimePayment', '$1', "number_format(SumDailyOvertimePayment,'2',',','.')");
      $this->datatables->edit_column('OtherIncome', '$1', "number_format(OtherIncome,'2',',','.')");
      $this->datatables->edit_column('InsurancePayment', '$1', "number_format(InsurancePayment,'2',',','.')");
      $this->datatables->edit_column('AbsencePayment', '$1', "number_format(AbsencePayment,'2',',','.')");
      $this->datatables->edit_column('LoanPayment', '$1', "number_format(LoanPayment,'2',',','.')");
      $this->datatables->edit_column('OutstandingPayment', '$1', "number_format(OutstandingPayment,'2',',','.')");
      $this->datatables->edit_column('OtherPayment', '$1', "number_format(OtherPayment,'2',',','.')");
      $this->datatables->edit_column('TotalIncome', '$1', "number_format(TotalIncome,'2',',','.')");
      $this->datatables->edit_column('TotalDeduction', '$1', "number_format(TotalDeduction,'2',',','.')");
      $this->datatables->edit_column('TakeHomePay', '$1', "number_format(TakeHomePay,'2',',','.')");	
	
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
/*
  function getall_data($from,$until){   
     $this->_db->from($this->_table.' a');
     $this->_db->join($this->_employee.' b','b.IDEmployee = a.IDEmployee','LEFT');     
     $this->_db->where('a.PostingDate >=',$from);
     $this->_db->where('a.PostingDate  <=',$until);
     $this->_db->where('a.DeleteFlag','A');
     $this->_db->order_by('b.IDJobGroup','DESC');
     $this->_db->order_by('b.FullName','ASC');	
      $result = $this->_db->get();
      if($result->num_rows()>0){
          return $result->result_array();          
      }else{
          return 'empty';
      }
      
  }

*/

 function getall_data($start,$until,$g) {
	  $group = ($g=='AL')?" AND b.IDJobGroup NOT IN('ST')":"AND b.IDJobGroup='$g'";
               $sql = " SELECT
                            a.*,
                            b.IDJobGroup,
                            b.FullName,
                            c.BankAccount
                            
                        FROM
                             $this->_table a
                        LEFT JOIN
                             $this->_employee b
                        ON
                             b.IDEmployee = a.IDEmployee
                        LEFT JOIN
                             $this->_employee_d c
                        ON
                             c.IDEmployee = a.IDEmployee
                        WHERE
                             a.PostingDate BETWEEN '$start' AND '$until' AND
                            (b.IDJobGroup IN ('LT','LK','HL','MAG','OS')) and (b.Status='A' AND b.DeleteFlag='A' OR (b.Status='P' AND (b.ResignDate BETWEEN '$start' AND '$until'))) $group
                        ORDER BY    
                             b.IDJobGroup DESC, b.FullName ASC
                       ";
              $result = $this->_db->query($sql);
              
              if($result->num_rows() > 0){
                  
                  return $result->result_array();
              }
                  return null;
        }
  
  function get_payslip($posting, $id) {
        $query =
        "SELECT 
            b.FullName, 
            b.IDUnitGroup,
	    b.IDLocation,
            b.IDJobGroup, 
            a.* 
         FROM 
            triasnet_fieldpayroll.t05payrollslip a 
         JOIN 
            triasnet_employee.m01personal b 
         ON 
            b.IDEmployee=a.IDEmployee
         WHERE 
            a.PostingDate = '$posting' AND b.DeleteFlag='A'";
        if (strlen($id)>0) {
            $query.= " AND a.IDEmployee='$id' ";
        }
        $query.=
        "ORDER BY 
            b.IDJobGroup DESC, 
            b.FullName ASC";
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }
  

  function get_id_payslip($id) {
        $query =
        "SELECT 
            b.FullName, 
            b.IDUnitGroup,
	    b.IDLocation,	
            b.IDJobGroup, 
            a.* 
         FROM 
            triasnet_fieldpayroll.t05payrollslip a 
         JOIN 
            triasnet_employee.m01personal b 
         ON 
            b.IDEmployee=a.IDEmployee
         WHERE 
            a.ID ='$id' AND b.DeleteFlag='A'        
        ORDER BY 
            b.IDJobGroup DESC, 
            b.FullName ASC";
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
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


