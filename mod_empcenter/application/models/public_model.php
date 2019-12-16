<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Public_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db      = $this->load->database('public',TRUE);
        $this->_db2     = $this->load->database('empcenter',TRUE);
        $this->_tbl1    = 'm01personal';
        $this->_tbl1b   = 'm01personal_d';
        $this->_tbl2    = 'm01personal_course';
        $this->_tbl3    = 'm01personal_education';
        $this->_tbl4    = 'm01personal_family';
        $this->_tbl5    = 'm01personal_language';
        $this->_tbl6    = 'm01personal_workexp';
        $this->_tbl7    = 'm01personal_job';
        $this->_tblorg  = 'm03organization'; 
        
    }
    
    
    function  get_datapersonal($nip){
        $sql = "SELECT a.*,
                       b.Location,b.JobGroup,b.Department,b.Position,b.Unit,
                       b.DateFirstJoin,b.DateStartProbation,b.DateEndProbation,b.DatePassProbation,
                       b.DateNewContract,b.DateEndContract,b.DateInField,b.Status,b.HireDate,
                       b.FlagHire,b.ResignDate,b.FlagResign,b.EmployeeStatus,b.ResignReason,b.Note
                  FROM $this->_tbl1 a
                  INNER JOIN $this->_tbl7 b ON a.IDEmployee = b.IDEmployee
                  WHERE  
                  a.DeleteFlag ='A' AND 
                  a.IDEmployee='$nip'
                ";
        
        $result= $this->_db->query($sql);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return 'empty';
            
        }
    }
    
    function update_employee($id,$record){
        $this->_db->where('ID',$id);
        $this->_db->update($this->_tbl1,$record);
    }
            
    
    function get_job_wharray($wh=NULL){
		if ($wh != NULL){
			$this->_db->where($wh);
		}
		return $this->_db->get($this->_tbl7);
	}
    function get_employee($userid=NULL){
	    if ($userid != NULL){    
		$this->_db->where('IDEmployee',$userid);
		$query = $this->_db->get($this->_tbl1);
	    }
	    else{
		$query = $this->_db2->query("
		    SELECT IDEmployee, FullName as label 
		    FROM $this->_tbl1
		    WHERE Status = 'A' and IDEmployee != '$userid'
		    and ResignDate IS NULL
		    ORDER BY FullName ASC
		 ");
	    }
	    return $query;
    }    
    function update_ftab($iduser,$rec){
        $this->_db->where("IDEmployee",$iduser);
        $this->_db->update($this->_tbl1,$rec);
    }
    //job
    function get_prs_emp($userid){
        $this->_db2->where("IDEmployee",$userid);
        return $this->_db2->get($this->_tbl1);
    }
    function get_det_emp($userid){
        $this->_db2->where("IDEmployee",$userid);
        return $this->_db2->get($this->_tbl1b);
    }
    function get_job($userid){
        $this->_db->where("IDEmployee",$userid);
        return $this->_db->get($this->_tbl7);
    }
    function update_prs_emp($userid,$rec){
        $this->_db2->where("IDEmployee",$userid);
        $this->_db2->update($this->_tbl1,$rec);
    }
    function update_det_emp($userid,$rec){
        $this->_db2->where("IDEmployee",$userid);
        $this->_db2->update($this->_tbl1b,$rec);
    }
    function update_job($userid,$rec){
        $this->_db->where("IDEmployee",$userid);
        $this->_db->update($this->_tbl7,$rec);
    }
    function insert_prs_emp($rec){
    	$this->_db2->insert($this->_tbl1,$rec);
    }
    function insert_det_emp($rec){
    	$this->_db2->insert($this->_tbl1b,$rec);
    }
    function insert_job($rec){
    	$this->_db->insert($this->_tbl7,$rec);
    }

    function update_personal($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_tbl1,$rec);
    }
//    family ===========================
    function get_mother($user){
        $this->_db->where('FamilyMember','mother');
        $this->_db->where('DeleteFlag','A');
        $this->_db->where('IDEmployee',$user);
        $result = $this->_db->get($this->_tbl4);
        if($result->num_rows()>0){
            return 'exists';
        }else{
            return 'empty';
        }
    }
    function get_family($iduser,$idfam = NULL){
        if ($iduser != NULL and $idfam == NULL){
            $this->_db->where("IDEmployee",$iduser);            
        }
        else if ($iduser != NULL and $idfam != NULL){
            $this->_db->where("IDEmployee",$iduser);
            $this->_db->where("IDFamily",$idfam);
        }
        $this->_db->where("DeleteFlag","A");
        return $this->_db->get($this->_tbl4);
    }
    function get_family_member($where){
        $this->_db->where($where);
        return $this->_db->get($this->_tbl4);
    }
    function get_lastidfamily($iduser){
        $query  = "SELECT MAX(IDFamily) as lastid FROM $this->_tbl4 WHERE IDEmployee = '$iduser'";
        return $this->_db->query($query);
    }
    function insert_family($rec){
        $this->_db->insert($this->_tbl4,$rec);
    }
    function update_family($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_tbl4,$rec);
    }
    function delete_family($where){
        $this->_db->where($where);
        $this->_db->delete($this->_tbl4);
    }
//    end of family =====================================
//    education ===========================
    function get_education($iduser,$idedu = NULL){
        if ($iduser != NULL and $idedu == NULL){
            $this->_db->where("IDEmployee",$iduser);            
        }
        else if ($iduser != NULL and $idedu != NULL){
            $this->_db->where("IDEmployee",$iduser);
            $this->_db->where("IDEducation",$idedu);
        }
        return $this->_db->get($this->_tbl3);
    }
    function get_lastideducation($iduser){
        $query  = "SELECT MAX(IDEducation) as lastid FROM $this->_tbl3 WHERE IDEmployee = '$iduser'";
        return $this->_db->query($query);
    }
    function insert_education($rec){
        $this->_db->insert($this->_tbl3,$rec);
    }
    function update_education($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_tbl3,$rec);
    }
    function delete_education($where){
        $this->_db->where($where);
        $this->_db->delete($this->_tbl3);
    }
//    end of education =====================================
//    training and course ===========================
    function get_tnc($iduser,$idtnc = NULL){
        if ($iduser != NULL and $idtnc == NULL){
            $this->_db->where("IDEmployee",$iduser);            
        }
        else if ($iduser != NULL and $idtnc != NULL){
            $this->_db->where("IDEmployee",$iduser);
            $this->_db->where("IDCourse",$idtnc);
        }
        return $this->_db->get($this->_tbl2);
    }
    function get_lastidtnc($iduser){
        $query  = "SELECT MAX(IDCourse) as lastid FROM $this->_tbl2 WHERE IDEmployee = '$iduser'";
        return $this->_db->query($query);
    }
    function insert_tnc($rec){
        $this->_db->insert($this->_tbl2,$rec);
    }
    function update_tnc($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_tbl2,$rec);
    }
    function delete_tnc($where){
        $this->_db->where($where);
        $this->_db->delete($this->_tbl2);
    }
//    end of training and course =====================================
//    languages ===========================
    function get_language($iduser,$idtnc = NULL){
        if ($iduser != NULL and $idtnc == NULL){
            $this->_db->where("IDEmployee",$iduser);            
        }
        else if ($iduser != NULL and $idtnc != NULL){
            $this->_db->where("IDEmployee",$iduser);
            $this->_db->where("IDLanguage",$idtnc);
        }
        return $this->_db->get($this->_tbl5);
    }
    function get_lastidlanguage($iduser){
        $query  = "SELECT MAX(IDLanguage) as lastid FROM $this->_tbl5 WHERE IDEmployee = '$iduser'";
        return $this->_db->query($query);
    }
    function insert_language($rec){
        $this->_db->insert($this->_tbl5,$rec);
    }
    function update_language($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_tbl5,$rec);
    }
    function delete_language($where){
        $this->_db->where($where);
        $this->_db->delete($this->_tbl5);
    }
//    end of languages =====================================
//    languages ===========================
    function get_work($iduser,$idtnc = NULL){
        if ($iduser != NULL and $idtnc == NULL){
            $this->_db->where("IDEmployee",$iduser);            
        }
        else if ($iduser != NULL and $idtnc != NULL){
            $this->_db->where("IDEmployee",$iduser);
            $this->_db->where("IDWorkExp",$idtnc);
        }
        return $this->_db->get($this->_tbl6);
    }
    function get_lastidwork($iduser){
        $query  = "SELECT MAX(IDWorkExp) as lastid FROM $this->_tbl6 WHERE IDEmployee = '$iduser'";
        return $this->_db->query($query);
    }
    function insert_work($rec){
        $this->_db->insert($this->_tbl6,$rec);
    }
    function update_work($where,$rec){
        $this->_db->where($where);
        $this->_db->update($this->_tbl6,$rec);
    }
    function delete_work($where){
        $this->_db->where($where);
        $this->_db->delete($this->_tbl6);
    }
//    end of languages =====================================
    function get_department(){
        $this->_db2->where("DeleteFlag","A");
        return $this->_db2->get($this->_tblorg);
    }
    
    
    function insert($record) {
        $this->_db->insert($this->_tbl2, $record);
    }
}
