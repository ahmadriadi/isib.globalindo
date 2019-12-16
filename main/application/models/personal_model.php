<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personal_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('empcenter',TRUE);		
        $this->_db2  = $this->load->database('public',TRUE);		
        $this->_tbl1 = 'm01personal';
        $this->_tbl2 = 'm01personal_d';
        $this->_personal = 'isib_public.m01personal';
        $this->_job = 'isib_public.m01personal_job';
        $this->_par = "m04param";
	$this->_family = "m01personal_family";
	$this->_loginrole = "isib_security.t01userlogin";
    }
    
    
      function  get_datapersonal($nip){
        $sql = "SELECT a.*,
                       b.Location,b.JobGroup,b.Department,b.Position,b.Unit,
                       b.DateFirstJoin,b.DateStartProbation,b.DateEndProbation,b.DatePassProbation,
                       b.DateNewContract,b.DateEndContract,b.DateInField,b.Status,b.HireDate,
                       b.FlagHire,b.ResignDate,b.FlagResign,b.EmployeeStatus,b.ResignReason,b.Note
                  FROM $this->_tbl1 a
                  INNER JOIN $this->_job b ON a.IDEmployee = b.IDEmployee
                  WHERE  
                  a.DeleteFlag ='A' AND 
                  a.IDEmployee='$nip'
                ";
        
        $result= $this->_db2->query($sql);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return 'empty';
            
        }
    }
    
    function update_employee($id,$record){
        $this->_db2->where('ID',$id);
        $this->_db2->update($this->_tbl1,$record);
    }

    function get_mother($user){
        $this->_db2->where('FamilyMember','mother');
        $this->_db2->where('DeleteFlag','A');
        $this->_db2->where('IDEmployee',$user);
        $result = $this->_db2->get($this->_family);
        if($result->num_rows()>0){
            return 'exists';
        }else{
            return 'empty';
        }
    }
    
    function update_flag($nip,$record){
        $this->_db2->where('IDEmployee',$nip);
        $this->_db2->update($this->_tbl1,$record);
        
    }
    
    
    function getrole($nip){     
     $sql ="SELECT * FROM $this->_loginrole 
            WHERE Username ='$nip'
            ";
     $result = $this->_db->query($sql);
     if($result->num_rows()>0){
         return $result->row();
     }else{
         return 'empty';
     }
    }  
    
    function getparam_empcenter($nip,$parameter){     
     $sql ="SELECT * FROM $this->_par 
            WHERE ParamValue ='$nip' AND Note LIKE  '%$parameter%'
            ";
     $result = $this->_db->query($sql);
     if($result->num_rows()>0){
         return 'exist';
     }else{
         return 'empty';
     }
    }  	


    function  get_data($uid){
        $sql = "SELECT a.*,b.Location,b.Position FROM $this->_personal a
                LEFT JOIN $this->_job b ON a.IDEmployee=b.IDEmployee
                WHERE a.IDEmployee ='$uid'";
        $result = $this->_db->query($sql);
        if($result->num_rows()==1){
            return $result->row();
        }else{
            return null;
        }
    }
    function get_data_emp($iduser){
        $this->_db->where("IDEmployee", $iduser);
        return $this->_db->get($this->_tbl1);
    }
    function get_data_public($uid) {
        $this->_db2->where('IDEmployee', $uid);
        $query  = $this->_db2->get($this->_tbl1);
        if ($query->num_rows()==1) {
            return $query->row();
        } else {
            return NULL; 
        }
    }
    function insert($record) {
        $this->_db->insert($this->_tbl1, $record);
    }
    function get_userparam($wh){
        $this->_db->where($wh);
        return $this->_db->get($this->_par);
    }
   
}

