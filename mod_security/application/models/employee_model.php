<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('empcenter', TRUE);
        $this->_pbl      =  $this->load->database('public', TRUE);
        $this->_table   = 'm01personal';      
        $this->_table_d   = 'm01personal_d';      
        $this->_job   = 'm01personal_job';      
    }
    
function find_employee_active(){     
	$this->_db->where('DeleteFlag', 'A');
	$this->_db->where('Status', 'A');
        $query = $this->_db->get($this->_table);
        return $query;    
     
 }   

function updatedata_d($nip,$record){
    $this->_db->where('IDEmployee',$nip);
    $this->_db->update($this->_table_d,$record);
}
function updatedata_job($nip,$record){
    $this->_pbl->where('IDEmployee',$nip);
    $this->_pbl->update($this->_job,$record);
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
        $sql ="SELECT * FROM isib_public.m01personal_job WHERE IDEmployee='$nip'";
        $result = $this->_db->query($sql);
        if($result->num_rows()>0){
            return $result->row();
            
        }else{
            return null;
        }
        
    }



    
 
}

?>
