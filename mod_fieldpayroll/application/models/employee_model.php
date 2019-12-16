<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('empcenter', TRUE);
	$this->_db_at = $this->load->database('attendance', TRUE);
        $this->_table   = 'm01personal'; 
	$this->_table_d   = 'm01personal_d'; 
	$this->_group = 'r05jobgroup';     
    }


 function find_employee_active(){     
	$this->_db->where('DeleteFlag', 'A');
	$this->_db->where('Status', 'A');
        $this->_db->where_in('IDJobGroup', array('LT', 'LK', 'MAG', 'OS'));
        $query = $this->_db->get($this->_table_d);
        return $query;    
     
 }     
    

 function search_employee($term){
        $sql = "
                SELECT 
                    IDEmployee,
                    FullName,
                    CURDATE() AS Sekarang,
                    ResignDate,
                    Status,
                    DATE_ADD(ResignDate, INTERVAL 1 MONTH) as BatasFilter
                FROM 
                    m01personal_d
                WHERE 
                     FullName  LIKE '%$term%' AND 
                    (

                    (Status='A') OR 
                            (
                                    (Status='P') AND (CURDATE() <= ANY(SELECT  
                                                                          DATE_ADD(ResignDate, INTERVAL 1 MONTH) as keluar  
                                                                    FROM 
                                                                        m01personal_d 
                                                                    WHERE 
                                                                        FullName like '%$term%') )
                            )

                    ) 
                ";

        $query = $this->_db->query($sql);
        return $query;
    }


 function search_employee_field($term){
        $sql = "
                SELECT 
                    IDEmployee,
                    FullName,
                    BankAccount,
                    CURDATE() AS Sekarang,
                    ResignDate,
                    Status,
                    DATE_ADD(ResignDate, INTERVAL 1 MONTH) as BatasFilter
                FROM 
                    m01personal_d
                WHERE 
                     FullName  LIKE '%$term%' AND 
                    (

                    (Status='A') OR 
                            (
                                    (Status='P') AND (CURDATE() <= ANY(SELECT  
                                                                          DATE_ADD(ResignDate, INTERVAL 1 MONTH) as keluar  
                                                                    FROM 
                                                                        m01personal_d 
                                                                    WHERE 
                                                                        FullName like '%$term%') )
                            )

                    ) AND IDJobGroup IN('LT','LK','HL','MAG','OS') 
                ";

        $query = $this->_db->query($sql);
        return $query;
    }
	
   function search_employee_active($term){
         $sql = " SELECT IDEmployee,FullName
                  FROM  $this->_table_d
                  WHERE FullName  LIKE '%$term%' AND 
                        Status='A'
                ";
        $query = $this->_db->query($sql);
        return $query;        
    }

   function search_employee_active_parent($term,$user){
         $sql = " SELECT IDEmployee,FullName
                  FROM  $this->_table_d
                  WHERE FullName  LIKE '%$term%' AND 
                        Status='A' AND IDEmployeeParent='$user'
                ";
        $query = $this->_db->query($sql);
        return $query;        
    }

    function search_employee_fieldpayroll($term){
         $sql = " SELECT IDEmployee,FullName,BankAccount
                  FROM  $this->_table_d
                  WHERE FullName  LIKE '%$term%' AND 
                        Status='A' AND IDJobGroup IN('LT','LK','HL','MAG','OS')
                ";
        $query = $this->_db->query($sql);
        return $query;        
    }	
	
    
    function get_by_nip($nip){
        $this->_db->where('IDEmployee',$nip);
        $result = $this->_db->get($this->_table_d);
        if($result->num_rows()>0){
            return $result->row();
            
        }else{
            return NULL;
        }
        
    }
    
    
    function getall_data(){
        $this->_db->where('Status','A');
        $result = $this->_db->get($this->_table_d);
        if($result->num_rows()>0){
            return $result->result_array();
            
        }else{
            return null;
        }      
        
    }

     function getall_data_lapangan(){        
        $sql ="SELECT * FROM $this->_table_d WHERE IDJobGroup IN('LT','LK','HL','MAG','OS') AND Status ='A'";
        $result = $this->_db->query($sql);
        if($result->num_rows()>0){
            return $result->result_array();
            
        }else{
            return null;
        }   
    }


   function get_position($nip){
        $sql ="SELECT * FROM triasnet_public.m01personal_job WHERE IDEmployee='$nip'";
        $result = $this->_db->query($sql);
        if($result->num_rows()>0){
            return $result->row();
            
        }else{
            return null;
        }
        
    }	

     function get_rjob() {
        $this->_db_at->order_by('ID','ASC');
        $result = $this->_db_at->get($this->_group);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }

    function get_rjob_standar() {
         $sql ="SELECT * FROM $this->_group
                WHERE IDJobGroup NOT IN('AL')";         
         $result= $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }
    function get_rjob_field() {
         $sql ="SELECT * FROM $this->_group
               WHERE IDJobGroup NOT IN('ST')
               ORDER BY ID  ";         
         $result= $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }
 
}

?>
