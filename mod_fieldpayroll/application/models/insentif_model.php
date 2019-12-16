<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Insentif_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db         =  $this->load->database('fieldpayroll', TRUE);       
        $this->_table      = 't06insentif';
        $this->_employee   = 'triasnet_employee.m01personal';
       
    }
    
 function datainsentif(){            
      $a = $this->_table;   
      $b = 'triasnet_employee.m01personal';
      $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,                                
                                 $a.Amount AS Amount,
                                 $a.Status AS Status,
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
  function getall_data($from,$until){   
     $this->_db->from($this->_table.' a');
     $this->_db->join($this->_employee.' b','b.IDEmployee = a.IDEmployee','LEFT');   
     $this->_db->where('a.DeleteFlag','A');
     $this->_db->where('b.DeleteFlag','A');
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
  
    function checkdata($nip,$date,$param,$flag,$record){
       $this->_db->where('IDEmployee', $nip);
       $this->_db->where('PostingDate', $date);
       $this->_db->where('Parameter', $param);
       $this->_db->where('FlagEntry', $flag);       
       $result = $this->_db->get('t03addition');
       if($result->num_rows()>0){
           $this->update_system($nip, $date, $param, $flag, $record);
       }else{
           $this->insert_system($record);
       }        
    }
     
     function check_additonal($nip,$posting,$param,$flag){
       $this->_db->where('IDEmployee', $nip);      
       $this->_db->where('PostingDate', $posting);      
       $this->_db->where('Parameter', $param);
       $this->_db->where('FlagEntry', $flag);      
       $result = $this->_db->get('t03addition');
       if($result->num_rows()>0){
           return $result->row();
           
       }else{
           return null;
       }
               
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
    
     function insert_system($record){      
      $this->_db->insert('t03addition', $record);
      
  }  
    
   function update_system($nip,$date,$param,$flag,$record){
       $this->_db->where('IDEmployee', $nip);
       $this->_db->where('PostingDate', $date);
       $this->_db->where('Parameter', $param);
       $this->_db->where('FlagEntry', $flag);
       $this->_db->update('t03addition', $record);
       
   }
   
   function delete_additional($nip,$posting,$param,$flag){
        $sql ="DELETE FROM t03addition
               WHERE
                IDEmployee ='$nip' AND
                PostingDate ='$posting' AND
                Parameter ='$param' AND
                FlagEntry ='$flag'
                ";    
        $this->_db->query($sql);
        
    }
    
    
    function count_by_id($id) {
        $this->_db->where('ID', $id);
        return $this->_db->count_all_results($this->_table);
    }


}

?>


