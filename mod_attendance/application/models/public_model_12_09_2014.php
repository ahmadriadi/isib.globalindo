<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Public_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
	$this->_db_at 	= $this->load->database('attendance', TRUE);        
	$this->_db_emp 	= $this->load->database('empcenter', TRUE);        
	$this->_db_pbl 	= $this->load->database('public', TRUE);        
        $this->_tbl1    = 'triasnet_employee.m01personal';
        $this->_tbl1b   = 'm01personal_d';
        $this->_personal= 'm01personal';
        $this->_tbl2    = 'm01personal_course';
        $this->_tbl3    = 'm01personal_education';
        $this->_tbl4    = 'm01personal_family';
        $this->_tbl5    = 'm01personal_language';
        $this->_tbl6    = 'm01personal_workexp';
        $this->_tbl7    = 'm01personal_job';
        $this->_tblorg  = 'm03organization'; 
	$this->_cardmap  = 'm02cardmap';
        
    }
    function get_employee($userid=NULL){
	    if ($userid != NULL){    
		$this->_db_emp->where('IDEmployee',$userid);
		$query = $this->_db_emp->get($this->_tbl1b);
	    }
	    else{
		$query = $this->_db_emp->query("
		    SELECT IDEmployee, FullName as label 
		    FROM $this->_tbl1b
		    WHERE Status = 'A' and IDEmployee != '$userid'
		    and ResignDate IS NULL
		    ORDER BY FullName ASC
		 ");
	    }
	    return $query;
    }
    function allemployee(){
        $a = $this->_tbl1;
        $b = 'triasnet_employee.'.$this->_tbl1b;
	$c = 'triasnet_employee.'.$this->_tblorg;
        $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,                              
                                 $a.FullName AS FullName,
                                 $a.IDJobGroup AS IDJobGroup, 
				 $a.IDLocation AS IDLocation,        
                                 $b.IDJobPosition AS IDJobPosition,    
                                 $b.DateEndContract AS DateEndContract,    
                                 $b.IDDepartement AS IDDepartement,    
                                 IF($a.IDLocation='1','KAPUK',
                                 IF($a.IDLocation='2','BITUNG','-')) AS Location,
                                 IF($a.IDJobGroup ='ST','STAFF',    
                                 IF($a.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($a.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($a.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($a.IDJobGroup ='LL','LAIN-LAIN',
                                 IF($a.IDJobGroup ='MAG','MAGANG','-')))))) AS JobGroup,
                                 $a.IDUnitGroup AS IDUnitGroup,
                                 $a.Status AS Status,
                                 $a.HireDate AS HireDate,
                                 $a.ResignDate AS ResignDate,
				 $c.DescStructure AS DescStructure, 
                                 IF( $c.DescStructure IS NULL, $b.IDDepartement,$c.DescStructure) AS Dept                                     
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
	$this->datatables->join($c, "$a.IDDepartement = $c.IDStructure", 'left');
        $this->datatables->where("$a.Status","A");  
        return $this->datatables->generate();
    }
    
     function allemployee_pasif(){
        $a = $this->_tbl1;
        $b = 'triasnet_employee.'.$this->_tbl1b; 
	$c = 'triasnet_employee.'.$this->_tblorg;   
        $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,                              
                                 $a.FullName AS FullName,
                                 $a.IDJobGroup AS IDJobGroup, 
				 $a.IDLocation AS IDLocation,     
                                 $b.IDJobPosition AS IDJobPosition,           
                                 $b.DateEndContract AS DateEndContract, 
                                 $b.IDDepartement AS IDDepartement,         
                                 IF($a.IDLocation='1','KAPUK',
                                 IF($a.IDLocation='2','BITUNG','-')) AS Location,
                                 IF($a.IDJobGroup ='ST','STAFF',    
                                 IF($a.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($a.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($a.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($a.IDJobGroup ='LL','LAIN-LAIN',
                                 IF($a.IDJobGroup ='MAG','MAGANG','-')))))) AS JobGroup,
                                 $a.IDUnitGroup AS IDUnitGroup,
                                 $a.Status AS Status,
                                 $a.HireDate AS HireDate,
                                 $a.ResignDate AS ResignDate,
				 $c.DescStructure AS DescStructure, 
                                 IF( $c.DescStructure IS NULL, $b.IDDepartement,$c.DescStructure) AS Dept,                                   
                            ", FALSE);
        $this->datatables->from("$a");     
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
	$this->datatables->join($c, "$a.IDDepartement = $c.IDStructure", 'left');	
        $this->datatables->where("$a.Status","P");  
        return $this->datatables->generate();
    }

    function delete_personal_h_emp($nip){
         $this->_db_emp->delete($this->_personal, array('IDEmployee' => $nip));
    }
    function delete_personal_d_emp($nip){
         $this->_db_emp->delete($this->_tbl1b, array('IDEmployee' => $nip));
    }
    function delete_personal_pbl($nip){
         $this->_db_pbl->delete($this->_personal, array('IDEmployee' => $nip));
    }   
    function delete_personal_train_pbl($nip){
         $this->_db_pbl->delete($this->_tbl2, array('IDEmployee' => $nip));
    }   
    function delete_personal_edu_pbl($nip){
         $this->_db_pbl->delete($this->_tbl3, array('IDEmployee' => $nip));
    }   
    function delete_personal_fam_pbl($nip){
         $this->_db_pbl->delete($this->_tbl4, array('IDEmployee' => $nip));
    }
    function delete_personal_lang_pbl($nip){
         $this->_db_pbl->delete($this->_tbl5, array('IDEmployee' => $nip));
    }
    function delete_personal_workexp_pbl($nip){
         $this->_db_pbl->delete($this->_tbl6, array('IDEmployee' => $nip));
    }
    function delete_personal_job_pbl($nip){
         $this->_db_pbl->delete($this->_tbl7, array('IDEmployee' => $nip));
    }	
    
    function get_by_id($id) {
        $this->_db_emp->where('ID', $id);
        $result = $this->_db_emp->get($this->_tbl1);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_position($nip){
       $this->_db_pbl->where('IDEmployee',$nip);
       $result = $this->_db_pbl->get($this->_tbl7);
       if ($result->num_rows() > 0) {
            $row = $result->row(); 
            return $row->Position;            
        } else {
            return null;
        }
       
   } 	
       
    
    function update_ftab($iduser,$rec){
        $this->_db_emp->where("IDEmployee",$iduser);
        $this->_db_emp->update($this->_tbl1,$rec);
    }
    
    function get_prs_public($userid) {
        $this->_db_pbl->where("IDEmployee", $userid);
        $result = $this->_db_pbl->get($this->_personal);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }    
    function insert_pbl_personal($record){
        $this->_db_pbl->insert($this->_personal,$record);
        
    }    
    function update_pbl_personal($nip,$record){
        $this->_db_pbl->where("IDEmployee",$nip);
        $this->_db_pbl->update($this->_personal,$record);
    }

    //job
    function get_prs_emp($userid){
        $this->_db_emp->where("IDEmployee",$userid);
        return $this->_db_emp->get($this->_tbl1);
    }
    function get_det_emp($userid){
        $this->_db_emp->where("IDEmployee",$userid);
        return $this->_db_emp->get($this->_tbl1b);
    }

    /*		
    function get_job($userid){
        $this->_db_pbl->where("IDEmployee",$userid);
        return $this->_db_pbl->get($this->_tbl7);
    }
    */	

    function get_job($userid){        
         $sql = "SELECT a.IDEmployee,b.FullName,b.NickName,a.IDEmployeeParent,b.BankAccount,a.Location,b.IDJobGroup,a.Department,a.Position,a.Unit,a.EmployeeStatus,
                        a.HireDate,a.DateFirstJoin,a.DatePassProbation,a.DateNewContract,a.DateEndContract,b.Note
                 FROM  $this->_tbl7 a
                 LEFT JOIN triasnet_employee.$this->_tbl1b b
                 ON a.IDEmployee = b.IDEmployee
                 WHERE a.IDEmployee =$userid";        
        return $this->_db_pbl->query($sql);
    }		
    
    function get_parent_turnonoff($nip){
        $this->_db_emp->where("IDEmployee",$nip);
        return $this->_db_emp->get($this->_tbl1);
    }

    function getall_employee($param,$group,$loc) {
        $status= ($param=='passive')?'P':'A';
	$g = ($group=="AL")?"":"AND IDJobGroup='$group'";
	$l = ($loc=="AL")?"":"AND IDLocation='$loc'";		
        
        $sql = "SELECT * FROM $this->_tbl1b
                WHERE IDJobGroup IN('ST','LT','LK','HL','MAG','OS') AND  
                Status ='$status' $g $l
                ORDER BY Status ASC,IDJobGroup DESC,IDEmployee ASC";       
        
        //$this->_db_emp->order_by('Status', "asc");
        //$this->_db_emp->order_by('IDJobGroup', "desc");
        //$this->_db_emp->order_by('FullName', "asc");
        return $this->_db_emp->query($sql);
       
    }	
    
    
    function get_detail_turnonoff($nip){
        $this->_db_emp->where("IDEmployee",$nip);
        $result =  $this->_db_emp->get($this->_tbl1b);
        if($result->num_rows()>0){
            return $result->row();
            
        }else{
            return null;
        }
    }
    
    function get_departement($id){
        $this->_db_emp->where("IDStructure",$id);
        return $this->_db_emp->get($this->_tblorg);
    }
            
    function update_prs_emp($userid,$rec){
        $this->_db_emp->where("IDEmployee",$userid);
        $this->_db_emp->update($this->_tbl1,$rec);
    }
    function update_det_emp($userid,$rec){
        $this->_db_emp->where("IDEmployee",$userid);
        $this->_db_emp->update($this->_tbl1b,$rec);
    }
    function update_job($userid,$rec){
        $this->_db_pbl->where("IDEmployee",$userid);
        $this->_db_pbl->update($this->_tbl7,$rec);
    }
    
    function update_parent_turnonoff($userid,$rec){
        $this->_db_emp->where("IDEmployee",$userid);
        $this->_db_emp->update($this->_tbl1,$rec);
    }
    
    function update_detail_turnonoff($userid,$rec){
        $this->_db_emp->where("IDEmployee",$userid);
        $this->_db_emp->update($this->_tbl1b,$rec);
    }
    
    
    function insert_prs_emp($rec){
    	$this->_db_emp->insert($this->_tbl1,$rec);
    }
    function insert_det_emp($rec){
    	$this->_db_emp->insert($this->_tbl1b,$rec);
    }
    function insert_job($rec){
    	$this->_db_pbl->insert($this->_tbl7,$rec);
    }

    function get_emp_head($where){
        $this->_db_emp->where($where);
        return $this->_db_emp->get($this->_personal);
    }
    function get_emp_det($where){
        $this->_db_emp->where($where);
        return $this->_db_emp->get($this->_tbl1b);        
    }
    function get_emp_pbl($where){
        $this->_db_pbl->where($where);
        return $this->_db_pbl->get($this->_personal);        
    }
    function update_header_personal_emp($nip,$rec){
        $this->_db_emp->where('IDEmployee',$nip);
        $this->_db_emp->update($this->_tbl1,$rec);
    }
    function update_detail_personal_emp($nip,$rec){
        $this->_db_emp->where('IDEmployee',$nip);
        $this->_db_emp->update($this->_tbl1b,$rec);
    }
//    family ===========================
    function get_family($iduser,$idfam = NULL){
        if ($iduser != NULL and $idfam == NULL){
            $this->_db_pbl->where("IDEmployee",$iduser);            
        }
        else if ($iduser != NULL and $idfam != NULL){
            $this->_db_pbl->where("IDEmployee",$iduser);
            $this->_db_pbl->where("IDFamily",$idfam);
        }
        return $this->_db_pbl->get($this->_tbl4);
    }
    function get_family_member($where){
        $this->_db_pbl->where($where);
        return $this->_db_pbl->get($this->_tbl4);
    }    
    function get_lastidfamily($iduser){
        $query  = "SELECT MAX(IDFamily) as lastid FROM $this->_tbl4 WHERE IDEmployee = '$iduser'";
        return $this->_db_pbl->query($query);
    }
    function insert_family($rec){
        $this->_db_pbl->insert($this->_tbl4,$rec);
    }
    function update_family($where,$rec){
        $this->_db_pbl->where($where);
        $this->_db_pbl->update($this->_tbl4,$rec);
    }
    function delete_family($where){
        $this->_db_pbl->where($where);
        $this->_db_pbl->delete($this->_tbl4);
    }
//    end of family =====================================
//    education ===========================
    function get_education($iduser,$idedu = NULL){
        if ($iduser != NULL and $idedu == NULL){
            $this->_db_pbl->where("IDEmployee",$iduser);            
        }
        else if ($iduser != NULL and $idedu != NULL){
            $this->_db_pbl->where("IDEmployee",$iduser);
            $this->_db_pbl->where("IDEducation",$idedu);
        }
        return $this->_db_pbl->get($this->_tbl3);
    }
    function get_lastideducation($iduser){
        $query  = "SELECT MAX(IDEducation) as lastid FROM $this->_tbl3 WHERE IDEmployee = '$iduser'";
        return $this->_db_pbl->query($query);
    }
    function insert_education($rec){
        $this->_db_pbl->insert($this->_tbl3,$rec);
    }
    function update_education($where,$rec){
        $this->_db_pbl->where($where);
        $this->_db_pbl->update($this->_tbl3,$rec);
    }
    function delete_education($where){
        $this->_db_pbl->where($where);
        $this->_db_pbl->delete($this->_tbl3);
    }
//    end of education =====================================
//    training and course ===========================
    function get_tnc($iduser,$idtnc = NULL){
        if ($iduser != NULL and $idtnc == NULL){
            $this->_db_pbl->where("IDEmployee",$iduser);            
        }
        else if ($iduser != NULL and $idtnc != NULL){
            $this->_db_pbl->where("IDEmployee",$iduser);
            $this->_db_pbl->where("IDCourse",$idtnc);
        }
        return $this->_db_pbl->get($this->_tbl2);
    }
    function get_lastidtnc($iduser){
        $query  = "SELECT MAX(IDCourse) as lastid FROM $this->_tbl2 WHERE IDEmployee = '$iduser'";
        return $this->_db_pbl->query($query);
    }
    function insert_tnc($rec){
        $this->_db_pbl->insert($this->_tbl2,$rec);
    }
    function update_tnc($where,$rec){
        $this->_db_pbl->where($where);
        $this->_db_pbl->update($this->_tbl2,$rec);
    }
    function delete_tnc($where){
        $this->_db_pbl->where($where);
        $this->_db_pbl->delete($this->_tbl2);
    }
//    end of training and course =====================================
//    languages ===========================
    function get_language($iduser,$idtnc = NULL){
        if ($iduser != NULL and $idtnc == NULL){
            $this->_db_pbl->where("IDEmployee",$iduser);            
        }
        else if ($iduser != NULL and $idtnc != NULL){
            $this->_db_pbl->where("IDEmployee",$iduser);
            $this->_db_pbl->where("IDLanguage",$idtnc);
        }
        return $this->_db_pbl->get($this->_tbl5);
    }
    function get_lastidlanguage($iduser){
        $query  = "SELECT MAX(IDLanguage) as lastid FROM $this->_tbl5 WHERE IDEmployee = '$iduser'";
        return $this->_db_pbl->query($query);
    }
    function insert_language($rec){
        $this->_db_pbl->insert($this->_tbl5,$rec);
    }
    function update_language($where,$rec){
        $this->_db_pbl->where($where);
        $this->_db_pbl->update($this->_tbl5,$rec);
    }
    function delete_language($where){
        $this->_db_pbl->where($where);
        $this->_db_pbl->delete($this->_tbl5);
    }
//    end of languages =====================================
//    languages ===========================
    function get_work($iduser,$idtnc = NULL){
        if ($iduser != NULL and $idtnc == NULL){
            $this->_db_pbl->where("IDEmployee",$iduser);            
        }
        else if ($iduser != NULL and $idtnc != NULL){
            $this->_db_pbl->where("IDEmployee",$iduser);
            $this->_db_pbl->where("IDWorkExp",$idtnc);
        }
        return $this->_db_pbl->get($this->_tbl6);
    }
    function get_lastidwork($iduser){
        $query  = "SELECT MAX(IDWorkExp) as lastid FROM $this->_tbl6 WHERE IDEmployee = '$iduser'";
        return $this->_db_pbl->query($query);
    }
    function insert_work($rec){
        $this->_db_pbl->insert($this->_tbl6,$rec);
    }
    function update_work($where,$rec){
        $this->_db_pbl->where($where);
        $this->_db_pbl->update($this->_tbl6,$rec);
    }
    function delete_work($where){
        $this->_db_pbl->where($where);
        $this->_db_pbl->delete($this->_tbl6);
    }
//    end of languages =====================================
    function get_department(){
        $this->_db_emp->where("DeleteFlag","A");
        return $this->_db_emp->get($this->_tblorg);
    }
    function insert($record) {
        $this->_db_pbl->insert($this->_tbl2, $record);
    }

    function lastnip() {
        $sql = "SELECT  SUBSTR(IDEmployee,1,4) AS LastNip 
                FROM $this->_personal
                ORDER BY IDEmployee DESC";
        $result = $this->_db_emp->query($sql);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            return ($row->LastNip)+1;
        } else {
            return null;
        }
    }

   function getall_employee_public($param,$group,$loc) {
        $status= ($param=='passive')?'P':'A';
	$g = ($group=="AL")?"":"AND b.IDJobGroup='$group'";
	$l = ($loc=="AL")?"":"AND b.IDLocation='$loc'";		
        
        $sql = "SELECT b.*,c.MaritalStatus,c.ExternalEmail,c.NumberChildren,c.NoKTP FROM triasnet_employee.$this->_tbl1b b LEFT JOIN triasnet_public.m01personal c ON b.IDEmployee = c.IDEmployee              
                WHERE b.IDJobGroup IN('ST','LT','LK') AND  
                b.Status ='$status' $g $l
                ORDER BY b.IDJobGroup DESC, b.FullName ASC"; 
       
        return $this->_db_emp->query($sql);
       
    }


    function spouse($idemployee){
        $this->_db_pbl->where('IDEmployee',$idemployee);
        $this->_db_pbl->where('FamilyMember','spouse');
        $result = $this->_db_pbl->get($this->_tbl4);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return null;
            
        }
        
    }
    function children($idemployee){
        $this->_db_pbl->where('IDEmployee',$idemployee);
        $this->_db_pbl->where('FamilyMember','child');
        $result = $this->_db_pbl->get($this->_tbl4); 
        
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
            
        }        
    }


   function get_enroll($idemployee){        
        $this->_db_at->where('IDEmployee',$idemployee);
        $this->_db_at->where('LastStatus','T');
        $this->_db_at->where('DeleteFlag','A');
        $result = $this->_db_at->get($this->_cardmap);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
            
        }   
       
    }    
    
    function insert_enroll($record){
        $this->_db_at->insert($this->_cardmap,$record);        
        
    }    
    function update_enroll($idemployee,$enroll,$record){
        $this->_db_at->where('IDEmployee',$idemployee);
        $this->_db_at->where('IDCard',$enroll);
        $this->_db_at->where('LastStatus','T');
        $this->_db_at->where('DeleteFlag','A');
        $this->_db_at->update($this->_cardmap,$record);
    }     
	
    function delete_enroll($idemployee){
        $sql = "DELETE FROM $this->_cardmap WHERE IDEmployee='$idemployee'";
        $this->_db_at->query($sql);        
        
    }   	
     
   
}

