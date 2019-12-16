<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('empcenter', TRUE);
	$this->_db_at = $this->load->database('attendance', TRUE);
        $this->_table   = 'm01personal'; 
	$this->_group = 'r05jobgroup';     
    }
    
 function findall_employee(){     
	$this->_db->where('DeleteFlag', 'A');
        $query = $this->_db->get($this->_table);
        return $query;    
     
 }


function find_employee_active(){     
	$this->_db->where('DeleteFlag', 'A');
	$this->_db->where('Status', 'A');
        $query = $this->_db->get($this->_table);
        return $query;    
     
 } 


function find_employee_afterresign(){
        $sql = "
                SELECT 
                    IDEmployee,
                    FullName,
                    CURDATE() AS Sekarang,
                    ResignDate,
                    Status,
                    DATE_ADD(ResignDate, INTERVAL 1 MONTH) as BatasFilter
                FROM 
                    m01personal
                WHERE 
                     DeleteFlag='A' AND
                    (

                    (Status='A') OR 
                            (
                                    (Status='P') AND (CURDATE() <= ANY(SELECT  
                                                                          DATE_ADD(ResignDate, INTERVAL 1 MONTH) as keluar  
                                                                    FROM 
                                                                        m01personal 
                                                                    WHERE 
                                                                     DeleteFlag='A') )
                            )

                    ) 
                ";

        $query = $this->_db->query($sql);
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
                    m01personal
                WHERE 
                     FullName  LIKE '%$term%' AND  DeleteFlag='A' AND
                    (

                    (Status='A') OR 
                            (
                                    (Status='P') AND (CURDATE() <= ANY(SELECT  
                                                                          DATE_ADD(ResignDate, INTERVAL 1 MONTH) as keluar  
                                                                    FROM 
                                                                        m01personal 
                                                                    WHERE 
                                                                        FullName like '%$term%' AND DeleteFlag='A') )
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
                    m01personal
                WHERE 
                     FullName  LIKE '%$term%' AND  DeleteFlag='A' AND
                    (

                    (Status='A') OR 
                            (
                                    (Status='P') AND (CURDATE() <= ANY(SELECT  
                                                                          DATE_ADD(ResignDate, INTERVAL 1 MONTH) as keluar  
                                                                    FROM 
                                                                        m01personal 
                                                                    WHERE 
                                                                        FullName like '%$term%' AND DeleteFlag='A') )
                            )

                    ) AND IDJobGroup IN('LT','LK','HL','MAG','OS') 
                ";

        $query = $this->_db->query($sql);
        return $query;
    }

 function search_employee_enrty($term,$userid){
         $sql = "
                SELECT 
                    IDEmployee,
                    FullName,
                    CURDATE() AS Sekarang,
                    ResignDate,
                    Status,
                    DATE_ADD(ResignDate, INTERVAL 1 MONTH) as BatasFilter
                FROM 
                    m01personal
                WHERE 
                     FullName  LIKE '%$term%' AND DeleteFlag='A' AND
                    (

                    (Status='A') OR 
                            (
                                    (Status='P') AND (CURDATE() <= ANY(SELECT  
                                                                          DATE_ADD(ResignDate, INTERVAL 1 MONTH) as keluar  
                                                                    FROM 
                                                                        m01personal 
                                                                    WHERE 
                                                                        FullName like '%$term%' AND DeleteFlag='A') )
                            )

                    ) AND IDEmployeeParent ='$userid'
                    
                ";

        $query = $this->_db->query($sql);
        return $query;
        
        
    }
	
    function search_employee_active($term){
         $sql = " SELECT IDEmployee,FullName
                  FROM  $this->_table
                  WHERE FullName  LIKE '%$term%' AND 
                        Status='A' AND DeleteFlag='A'
                ";
        $query = $this->_db->query($sql);
        return $query;        
    }

   function search_employee_active_parent($term,$user){
         $sql = " SELECT IDEmployee,FullName
                  FROM  $this->_table
                  WHERE FullName  LIKE '%$term%' AND 
                        Status='A' AND IDEmployeeParent='$user'  AND DeleteFlag='A'
                ";
        $query = $this->_db->query($sql);
        return $query;        
    }

   
    
    function get_by_nip($nip){
        $this->_db->where('IDEmployee',$nip);
        $result = $this->_db->get($this->_table);
        if($result->num_rows()>0){
            return $result->row();
            
        }else{
            return NULL;
        }
        
    }
    
    
   function getall_data(){
        $this->_db->where('Status','A');
        $this->_db->where('DeleteFlag','A');
        $result = $this->_db->get($this->_table);
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
                WHERE IDJobGroup NOT IN('AL','ST')";         
         $result= $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }


 function get_dept() {
         $sql ="SELECT * FROM m03organization
                WHERE DeleteFlag='A'";         
         $result= $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }
 function dept($dept){     
     $sql ="SELECT * FROM m03organization
                WHERE DeleteFlag='A' AND ID='$dept'";         
         $result= $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            $row =  $result->row();
            return $row->DescStructure;
            
        } else {
            return null;
        }
 }   


 function find_employeee($term){
         $sql = " SELECT IDEmployee,FullName
                  FROM  $this->_table
                  WHERE FullName  LIKE '%$term%' AND 
                  DeleteFlag='A'
                ";
        $query = $this->_db->query($sql);
        return $query;        
    }


    
 
}

?>

