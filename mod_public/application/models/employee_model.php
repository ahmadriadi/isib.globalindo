<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('empcenter', TRUE);
        $this->_db_at      =  $this->load->database('attendance', TRUE);
        $this->_table   = 'm01personal';      
        $this->_param   = 'm04param';      
        $this->_table_d   = 'm01personal_d';      
        $this->_position = 'triasnet_public.m01personal_job';      
        $this->_group = 'r05jobgroup';     
    }
    
  function getparam_rptsummary($nip){     
     $sql ="SELECT * FROM $this->_param 
            WHERE ParamValue ='$nip' AND Note LIKE  '%SUMMARY PRESENCE%'
            ";
     $result = $this->_db->query($sql);
     if($result->num_rows()>0){
         return 'exist';
     }else{
         return 'empty';
     }
  }       


   function getparam_rptdetail($nip){     
     $sql ="SELECT * FROM $this->_param 
            WHERE ParamValue ='$nip' AND Note LIKE  '%REPORT DETAIL%'
            ";
     $result = $this->_db->query($sql);
     if($result->num_rows()>0){
         return 'exist';
     }else{
         return 'empty';
     }
 }     
    
    
    
    
 function get_userparam($nip){
     $this->_db->where('ParamValue',$nip);
     $this->_db->like('Note','SUMMARY PRESENCE');
     $result = $this->_db->get($this->_param);
     if($result->num_rows()>0){
         return 'exist';
     }else{
         return 'empty';
     }
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
                     FullName  LIKE '%$term%' AND 
                    (

                    (Status='A') OR 
                            (
                                    (Status='P') AND (CURDATE() <= ANY(SELECT  
                                                                          DATE_ADD(ResignDate, INTERVAL 1 MONTH) as keluar  
                                                                    FROM 
                                                                        m01personal 
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
                    m01personal
                WHERE 
                     FullName  LIKE '%$term%' AND 
                    (

                    (Status='A') OR 
                            (
                                    (Status='P') AND (CURDATE() <= ANY(SELECT  
                                                                          DATE_ADD(ResignDate, INTERVAL 1 MONTH) as keluar  
                                                                    FROM 
                                                                        m01personal 
                                                                    WHERE 
                                                                        FullName like '%$term%') )
                            )

                    ) AND IDJobGroup IN('LT','LK','HL') 
                ";

        $query = $this->_db->query($sql);
        return $query;
    }


    
    function get_all($term){
        $sql = "SELECT IDEmployee,FullName 
                FROM $this->_table_d 
                WHERE FullName like '%$term%'";
        $query = $this->_db->query($sql);
        return $query;
    }
    
    
    function get_for_man($term,$dept){
        $sql = "SELECT IDEmployee,FullName 
                FROM $this->_table_d 
                WHERE FullName like '%$term%' AND IDDepartement='$dept'                            
                ";
        $query = $this->_db->query($sql);
        return $query;
        
    }
    function get_for_spv($term,$dept){
        $sql = "SELECT IDEmployee,FullName 
                FROM $this->_table_d 
                WHERE FullName like '%$term%' AND 
                IDDepartement='$dept' AND
                IDJobPosition NOT IN('DIRECTOR','COMISSIONER','MANAGER','ASSISTANT MANAGER')
                ";
        $query = $this->_db->query($sql);
        return $query;
        
    }
    function get_for_user($term,$userid){
        $sql = "SELECT IDEmployee,FullName 
                FROM $this->_table 
                WHERE FullName like '%$term%' AND IDEmployee='$userid'";
        $query = $this->_db->query($sql);
        return $query;
    }
    
    
    
   
    
    function search_employee_entry($term,$userid){
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
                     FullName  LIKE '%$term%' AND 
                    (

                    (Status='A') OR 
                            (
                                    (Status='P') AND (CURDATE() <= ANY(SELECT  
                                                                          DATE_ADD(ResignDate, INTERVAL 1 MONTH) as keluar  
                                                                    FROM 
                                                                        m01personal 
                                                                    WHERE 
                                                                        FullName like '%$term%') )
                            )

                    ) AND IDEmployeeParent ='$userid'                    
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
    
     function get_dept($id) {
         $sql ="SELECT * FROM m03organization
                WHERE DeleteFlag='A' AND IDStructure='$id'";         
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



    
 
}

?>

