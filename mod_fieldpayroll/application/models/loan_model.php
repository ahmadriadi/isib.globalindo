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
        $this->_interestloan = 't06loaninterest';
       
    }


      function getdata_interest($id){
      $this->_db->where('FlagProcess','1');
      $this->_db->where('ID',$id);
      $result = $this->_db->get($this->_interestloan);
      if($result->num_rows()>0){
            return  'exist';
        }else{
             return 'empty';
        }
     
     }
    
    function getdataloanfor_interest($posting){
         $sql = " SELECT a.ID,a.IDEmployee,b.FullName,c.InterestInstalment,a.Installment,a.InstallmentDate
                  FROM  $this->_table_d a
                  INNER JOIN  $this->_employee b ON a.IDEmployee = b.IDEmployee     
                  LEFT JOIN  $this->_table_h c ON a.IDHeader = c.ID     
                  WHERE
                        b.Status='A' AND
                        a.InstallmentDate >='$posting' AND
                        a.DeleteFlag ='A' 
                ";
        $query = $this->_db->query($sql);
        return $query->result_array();        
    }  	

/*

      function getdataloanfor_interest($posting){
         $sql = " SELECT a.ID,a.IDEmployee,b.FullName,c.InterestInstalment,a.Installment,a.InstallmentDate
                  FROM  $this->_table_d a
                  INNER JOIN  $this->_employee b ON a.IDEmployee = b.IDEmployee     
                  LEFT JOIN  $this->_table_h c ON a.IDHeader = c.ID     
                  WHERE
                        b.Status='A' AND
                        a.InstallmentDate >='$posting' AND
                        a.DeleteFlag ='A' AND
                        a.Flag ='0'
                ";
        $query = $this->_db->query($sql);
        return $query->result_array();        
    }  	
*/
    
    
     function getinterest_all($from,$until){       
          $sql = " SELECT a.*,b.FullName,b.IDJobGroup,c.Installment,d.InterestInstalment
                  FROM  $this->_interestloan a
                  INNER JOIN  $this->_employee b ON a.IDEmployee = b.IDEmployee     
                  LEFT JOIN  $this->_table_d c ON a.IDRecord = c.ID     
                  LEFT JOIN  $this->_table_h d ON c.IDHeader = d.ID                         
                  WHERE 
                        a.PostingDate BETWEEN '$from' AND '$until' AND
                        a.DeleteFlag ='A'
                ";
        $result = $this->_db->query($sql);   
        if($result->num_rows()>0){
            return $result->result();
        }else{
             return 'empty';
        }
    }
    
      function getinterest_byid($id){
        $sql = " SELECT a.*,b.FullName,c.Installment,d.InterestInstalment
                  FROM  $this->_interestloan a
                  INNER JOIN  $this->_employee b ON a.IDEmployee = b.IDEmployee     
                  LEFT JOIN  $this->_table_d c ON a.IDRecord = c.ID     
                  LEFT JOIN  $this->_table_h d ON c.IDHeader = d.ID     
                      
                  WHERE
                        a.ID='$id' AND
                        a.DeleteFlag ='A'
                ";
        $result = $this->_db->query($sql);    
        if($result->num_rows()>0){
            return $result->row();
        }else{
             return 'empty';
        }
    }

   function checkinterest2($nip,$posting){
        $this->_db->where('IDEmployee',$nip);
        $this->_db->where('PostingDate',$posting);
        $this->_db->where('DeleteFlag','A');
        $result = $this->_db->get($this->_interestloan);
        if($result->num_rows()>0){
            return 'exist';
        }else{
             return 'empty';
        }
    }
    
    function checkinterest($idrecord,$nip,$posting){
	$this->_db->where('IDRecord',$idrecord);
        $this->_db->where('IDEmployee',$nip);
        $this->_db->where('PostingDate',$posting);
        $this->_db->where('DeleteFlag','A');
        $result = $this->_db->get($this->_interestloan);
        if($result->num_rows()>0){
            return 'exist';
        }else{
             return 'empty';
        }
    }
    
    function update_interest($id,$record){
        $this->_db->where('ID',$id);
        $this->_db->update($this->_interestloan,$record);
    }
            
    
    function insert_interest($record){
        $this->_db->insert($this->_interestloan,$record);
    }
    
  function search_loan($term,$posting){
         $sql = " SELECT a.ID,a.IDEmployee,b.FullName,c.InterestInstalment,a.Installment,a.InstallmentDate
                  FROM  $this->_table_d a
                  INNER JOIN  $this->_employee b ON a.IDEmployee = b.IDEmployee     
                  LEFT JOIN  $this->_table_h c ON a.IDHeader = c.ID     
                  WHERE b.FullName  LIKE '%$term%' AND 
                        b.Status='A' AND
                        a.InstallmentDate ='$posting' AND
                        a.DeleteFlag ='A' AND
                        a.Flag ='0'
                ";
        $query = $this->_db->query($sql);
        return $query;        
    }  
    
    
  function loanemployee_interest($from,$until){
      $a = $this->_interestloan;   
      $b = 'triasnet_employee.m01personal';
      $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,
                                 $a.PostingDate AS PostingDate,
                                 $a.Amount AS Amount,
                                 $a.FlagProcess AS FlagProcess,
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
      $this->datatables->where("$a.PostingDate >=","$from"); 
      $this->datatables->where("$a.PostingDate <=","$until"); 
      $this->datatables->edit_column('Amount', '$1', "number_format(Amount,'2',',','.')");
      return $this->datatables->generate();
      
  }    
  
    
  function loanemployee_h_paidoff(){
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
      $this->datatables->where("$a.FlagPaid","1");
      $this->datatables->edit_column('Amount', '$1', "number_format(Amount,'2',',','.')");
      $this->datatables->edit_column('Instalment', '$1', "number_format(Instalment,'2',',','.')");
      return $this->datatables->generate();
      
  }  
  
  function loanemployee_h_paidon(){
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
      $this->datatables->where("$a.FlagPaid","0");
      $this->datatables->edit_column('Amount', '$1', "number_format(Amount,'2',',','.')");
      $this->datatables->edit_column('Instalment', '$1', "number_format(Instalment,'2',',','.')");
      return $this->datatables->generate();
      
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
  
    function getby_id_d($id){
      $this->_db->select('a.*,b.InterestInstalment');
      $this->_db->from($this->_table_d.' a');
      $this->_db->join($this->_table_h.' b','b.ID = a.IDHeader'); 
      $this->_db->where('a.ID',$id);
      $this->_db->where('a.DeleteFlag','A');
      $result = $this->_db->get();
      if($result->num_rows()>0){
          return $result->row();          
      } else {
          return null;  
      }
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
    
     function get_alldatax(){
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


   function get_alldata($param){

	//echo $param;

        if($param=='paidoff'){
            $where = " 
                      WHERE   
		      a.DeleteFlag ='A' AND			      	
                      a.FlagPaid = '1' AND
		      b.Status ='A' 
                      
                    ";
            
        }else{            
              $where = " 
                      WHERE  
		      a.DeleteFlag ='A' AND	
                      a.FlagPaid = '0'  AND	
		      b.Status ='A'		
                      
                    ";
        } 
         
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
               
          $where
               
          ORDER BY
                b.IDJobGroup DESC,
                b.FullName  ASC,
	        a.FlagPaid DESC
              
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

