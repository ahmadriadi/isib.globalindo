<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class loan_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db           =  $this->load->database('fieldpayroll', TRUE);       
        $this->_table_h      = 'm02personalloan_h';
        $this->_table_d      = 'm02personalloan_d';
        $this->_employee     = 'triasnet_employee.m01personal';
       
    }
    
 function loanemployee_h(){            
      $a = $this->_table_h;   
      $b = 'triasnet_employee.m01personal';
      $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,
                                 DATE_FORMAT($a.LoanDate,'%d-%m-%Y') AS LoanDate,       
                                 $a.Amount AS Amount,
                                 $a.Instalment AS Instalment,
                                 $a.Term AS Term,
                                 DATE_FORMAT($a.DateInstalment,'%d-%m-%Y') AS DateInstalment,     
                                 $a.Note AS Note,              
                                 $a.InterestInstalment AS InterestInstalment,              
                                 $a.InterestLaon AS InterestLaon,              
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
      $this->datatables->where("$b.Status","A");
      $this->datatables->edit_column('Amount', '$1', "number_format(Amount,'2',',','.')");
      $this->datatables->edit_column('Instalment', '$1', "number_format(Instalment,'2',',','.')");
     	
      return $this->datatables->generate();       
  }
  
  
  
  function loanemployee_d($id){            
      $a = $this->_table_d; 
      $b = 'triasnet_employee.m01personal';
      $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,
                                 DATE_FORMAT($a.InstallmentDate,'%d-%m-%Y') AS InstallmentDate,   
                                 $a.Installment AS Installment,
                                 $a.Note AS Note,  
                                 $b.FullName AS FullName,
                                 IF($a.Flag ='1','TERPOTONG',                                
                                 IF($a.Flag ='0','BELUM','-')) AS Status    
                            ",FALSE);
      $this->datatables->from("$a"); 
      $this->datatables->join($b,"$a.IDEmployee = $b.IDEmployee",'left'); 
      $this->datatables->where("$a.IDHeader",$id); 
      $this->datatables->where("$a.DeleteFlag","A"); 
      $this->datatables->where("$b.Status","A");
      
      $this->datatables->edit_column('Installment', '$1', "number_format(Installment,'2',',','.')");
     	
      return $this->datatables->generate();       
  } 
  
  function getby_id_detail($id){
      $this->_db->select('a.*,b.FullName,b.IDJobGroup');
      $this->_db->from($this->_table_d.' a');
      $this->_db->join($this->_employee.' b','b.IDEmployee = a.IDEmployee','LEFT'); 
      $this->_db->where('a.IDHeader',$id);
      $this->_db->where('a.DeleteFlag','A');
      $result = $this->_db->get();
      if($result->num_rows()>0){
          return $result->row();
          
      } else {
          return null;  
      }
  }

  
  function getall_detail($id){
      $this->_db->select('a.*,b.FullName,b.IDJobGroup');
      $this->_db->from($this->_table_d.' a');
      $this->_db->join($this->_employee.' b','b.IDEmployee = a.IDEmployee','LEFT'); 
      $this->_db->where('a.IDHeader',$id);
      $this->_db->where('a.DeleteFlag','A');
      $result = $this->_db->get();
      if($result->num_rows()>0){
          return $result->result_array();
          
      } else {
          return null;  
      }
  }
  
  function get_by_id($id){
     $this->_db->select('a.*,b.FullName');
     $this->_db->from($this->_table_h.' a');
     $this->_db->join($this->_employee.' b','b.IDEmployee = a.IDEmployee','LEFT'); 
     $this->_db->where('a.ID',$id);
     $this->_db->where('a.DeleteFlag','A');
      $result = $this->_db->get();
      if($result->num_rows()>0){
          return $result->row();          
      }else{
          return 'empty';
      }
      
  } 
  function getall_data(){
     $this->_db->select('a.*,b.FullName,b.IDJobGroup');
     $this->_db->from($this->_table_h.' a');
     $this->_db->join($this->_employee.' b','b.IDEmployee = a.IDEmployee','LEFT');     
     $this->_db->where('a.DeleteFlag','A');
     $this->_db->where('b.Status','A');
     $this->_db->order_by('b.IDJobGroup','DESC');
     $this->_db->order_by('b.FullName','ASC');	
      $result = $this->_db->get();
      if($result->num_rows()>0){
          return $result->result_array();          
      }else{
          return 'empty';
      }
      
  }
  
   function getdata_by_id($id) {
        $this->_fieldpayroll->where('ID', $id);
        $query = $this->_fieldpayroll->get($this->_table2);
        if ($query->num_rows() > 0)
        {
            return $query->row(); 
        }
        return NULL;
    }
  
  function check($nip){
      $this->_db->where('IDEmployee',$nip);
      $result = $this->_db->get($this->_table_h);
       if($result->num_rows()>0){
           return 'exist';    
      }else{
          return null;
      }
  }
  
  function checkdata($nip,$record){
      $this->_db->where('IDEmployee',$nip);
      $result = $this->_db->get($this->_table_h);
      if($result->num_rows()>0){
          $this->insert($record);          
      }else{
          $this->update_employee($nip,$record);
      }
      
  }
    
    function insert($record) {
        $this->_db->insert($this->_table_h, $record);
    }
    
    function update($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table_h, $record);
    }
    function insert_d($record) {
        $this->_db->insert($this->_table_d, $record);
    }
    
    function update_d($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table_d, $record);
    }
    
    function update_employee($id, $record) {
        $this->_db->where('IDEmployee', $id);
        $this->_db->update($this->_table_h, $record);
    }
    
    function add_update_d($id,$nip,$date,$record) {
        $this->_db->where('IDHeader', $id);
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('InstallmentDate', $date);
        $query = $this->_db->get($this->_table_d);
         
        if ($query->num_rows() > 0)
        {           
            $result=$query->row(); 
            $this->update_d($result->ID, $record); 
        } else {
            //not found, insert
            $this->insert_d($record);  
        }
    }
    
    
    function delete_h($id) {
        $sql = "DELETE FROM m02personalloan_h WHERE ID = $id";
        $this->_db->query($sql);
    }
    
    function delete_d($id) {
        $sql = "DELETE FROM m02personalloan_d WHERE IDHeader = $id";
        $this->_db->query($sql);
    }
    
    
    function get_headerflag0($id,$flag){
        $this->_db->where("IDHeader", $id);
        $this->_db->where("Flag", $flag);
        $count = $this->_db->count_all_results($this->_table_d);
        if($count > 0){             
            return $count;            
        }
        return null;
    }
    function get_headerflag1($id,$flag){
        $this->_db->where("IDHeader", $id);
        $this->_db->where("Flag", $flag);
        $count = $this->_db->count_all_results($this->_table_d);
        if($count > 0){             
            return $count;            
        }
        return null;
    }
    
    
    function count_by_id($id) {
        $this->_db->where('ID', $id);
        return $this->_db->count_all_results($this->_table_h);
    }
    
     function get_alldata(){
        $query = "
          SELECT
               a.*,
               c.InstallmentDate,
               c.Installment,
               c.Flag,
               c.Note AS NoteDetail,
               b.FullName,
               b.IDJobGroup
          FROM
               triasnet_fieldpayroll.m02personalloan_h a
          JOIN
               triasnet_employee.m01personal b
          ON
               a.IDEmployee = b.IDEmployee
          JOIN
               triasnet_fieldpayroll.m02personalloan_d c
          ON
               a.ID = c.IDHeader
          WHERE               
               b.Status ='A'
               
          ORDER BY
                b.IDJobGroup DESC,
                b.FullName  ASC
              
                ";
        $result = $this->_db->query($query);        
        if ($result->num_rows() > 0)
        {
            return $result->result();
        }
        return false;        
    }


}

?>

