<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rootcause_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('public',TRUE);		
        $this->_emp   = $this->load->database('empcenter',TRUE);		
        $this->_table ='r01rootcause';
        $this->_rootcause ='t01rootcause';
        $this->_personal_m ='m01personal';
        $this->_personal_d ='m01personal_d';
        $this->_organization ='m03organization';
	    $this->_pmail ='p01emailroot';
        $this->_param='m04param';
        $this->_user ='t02request_user';
        $this->_createuser='t02request_createuser';
        $this->_install ='t02request_installsoftware';
        $this->_createfolder='t02request_createfolder';
        $this->_accessfolder ='t02request_accessfolder';
        $this->_agreement ='t02request_agreement';
    }
    
    
    function get_location($nip){
		$this->_emp->where('IDEmployee',$nip);
		$result = $this->_emp->get($this->_personal_d);
		if($result->num_rows()>0){
		 $row = $result->row();
		  return $row->IDLocation;
		}else{
		  return 1;	
		}
	}

   function sendtomail($idloc){ 
	    $this->_db->where('DeleteFlag','A');	  
	    $this->_db->where('RootSite',$idloc);
	    $result = $this->_db->get($this->_pmail);
	    if($result->num_rows()>0){
		return $result->result_array();
	    }else{
		return null;
	    }
    
   }  	

   function datarequest_agreement($idh){
        $a = 'isib_public.'.$this->_agreement ;
        $this->datatables->select("$a.ID AS ID,                                 
                                    IF ($a.StatusAgreement = '1', 'Accept',
                                    IF ($a.StatusAgreement = '0', 'No','-'
                                    )) AS StatusAgreement,       
                                  
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.CounterReq", $idh);
        return $this->datatables->generate();
        
    }
    function datarequest_accessfolder($idh){
        $a = 'isib_public.'.$this->_accessfolder ;
        $this->datatables->select("$a.ID AS ID,                                 
                                    $a.FolderAccess AS FolderAccess, 
                                    IF ($a.AccessStatus = '1', 'R/O',
                                    IF ($a.AccessStatus = '2', 'R/W',
                                    IF ($a.AccessStatus = '0', 'N/A','-'
                                    ))) AS AccessStatus,       
                                  
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.CounterReq", $idh);
        return $this->datatables->generate();
        
    }
    function datarequest_software($idh){
        $a = 'isib_public.'.$this->_install ;
        $this->datatables->select("$a.ID AS ID,
                                    $a.SoftwareName AS SoftwareName,
                                    IF ($a.SoftwareStatus = '1', 'Install',
                                    IF ($a.SoftwareStatus = '0', 'Uninstall','-'
                                    )) AS SoftwareStatus,
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.CounterReq", $idh);
        return $this->datatables->generate();
    }
    function datarequest_createfolder($idh){
        $a = 'isib_public.'.$this->_createfolder ;
        $this->datatables->select("$a.ID AS ID,                                 
                                    $a.FolderName AS FolderName, 
                                    IF ($a.FolderStatus = '1', 'Create',
                                    IF ($a.FolderStatus = '0', 'Delete','-'
                                    )) AS FolderStatus,       
                                  
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.CounterReq", $idh);
        return $this->datatables->generate();
    }
    function datarequest_createuser($idh){
        $a = 'isib_public.'.$this->_createuser ;
        $this->datatables->select("$a.ID AS ID,                                 
                                    $a.UserID AS UserID, 
                                    IF ($a.StatusUser = '1', 'Create',
                                    IF ($a.StatusUser = '0', 'Banned','-'
                                    )) AS StatusUser,       
                                    IF ($a.InternetStatus = '1', 'With Access Internet',
                                    IF ($a.InternetStatus = '0', 'Without Internet','-'
                                    )) AS InternetStatus,       
                                    $a.InternalEmail AS InternalEmail, 
                                    $a.ExternalEmail AS ExternalEmail, 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.CounterReq", $idh);
        return $this->datatables->generate();
        
    }
    
    function datarequest_user($idh){
        $a = 'isib_public.'.$this->_user  ;
        $this->datatables->select("$a.ID AS ID,                                 
                                    $a.ComputerName AS ComputerName, 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.NoCounter", $idh);
        return $this->datatables->generate();
        
    }    
	
    function getuser($idh){
        $sql ="SELECT a.*,b.ComputerName
               FROM $this->_rootcause a
               LEFT JOIN $this->_user b ON a.ID = b.NoCounter
               WHERE 
                a.ID='$idh'";
        $result = $this->_db->query($sql);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return 'empty';
        }
    }	    
	
    function getuserx($idh){
        $this->_db->where('NoCounter',$idh);
        $result = $this->_db->get($this->_user);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return 'empty';
        }
    }
    function getcreateuser($idh){
        $this->_db->where('CounterReq',$idh);
        $result = $this->_db->get($this->_createuser);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return 'empty';
        }
    }
    
    function getinstall($idh){
        $this->_db->where('CounterReq',$idh);
        $result = $this->_db->get($this->_install);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return 'empty';
        }
    }
    
    function getcreatefolder($idh){
        $this->_db->where('CounterReq',$idh);
        $result = $this->_db->get($this->_createfolder);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return 'empty';
        }
    }
    
    function getaccessfolder($idh){
        $this->_db->where('CounterReq',$idh);
        $result = $this->_db->get($this->_accessfolder);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return 'empty';
        }
    }
    function getagreement($idh){
        $this->_db->where('CounterReq',$idh);
        $result = $this->_db->get($this->_agreement);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return 'empty';
        }
    }
    
    function getpersonal($nip){
        $sql = "SELECT a.*,b.DescStructure as Departement,b.IDStructure
                FROM $this->_personal_d a
                LEFT JOIN $this->_organization b ON a.IDDepartement = b.IDStructure
                WHERE 
                a.IDEmployee= '$nip' AND
                a.DeleteFlag='A'
            
                ";
        $result = $this->_emp->query($sql);
         if($result->num_rows()>0){
                return $result->row();          
            }else{
                return 'empty';
            }
        
    }

    function getall_data(){
        $sql = "
                SELECT
                a.*,
                b.FullName AS picoleh,
                c.FullName AS diadd,
                d.FullName AS diedit,
                e.FullName AS accoleh,
                f.FullName AS dihapusoleh,  
                g.RootName
                
                FROM $this->_rootcause a
                    
                LEFT JOIN isib_employee.$this->_personal_d b ON  a.PIC = b.IDEmployee
                LEFT JOIN isib_employee.$this->_personal_d c ON  a.AddedBy = c.IDEmployee
                LEFT JOIN isib_employee.$this->_personal_d d ON  a.EditedBy = d.IDEmployee
                LEFT JOIN isib_employee.$this->_personal_d e ON  a.DeleteBy = e.IDEmployee
                LEFT JOIN isib_employee.$this->_personal_d f ON  a.HodConfBy = e.IDEmployee
                LEFT JOIN $this->_table g ON a.IDRoot = g.IDRoot
                
                WHERE
                a.DeleteFlag='A' 
                
                ORDER BY a.AddedDate DESC    
                
                ";
        
     $result = $this->_db->query($sql);
      if($result->num_rows()>0){
          return $result->result_array();          
      }else{
          return 'empty';
      }
        
        
    }
    
        
    function getall_datauser($from,$until,$user){
     $this->_db->select('a.*,b.FullName,c.RootName');
     $this->_db->from($this->_rootcause.' a');
     $this->_db->join($this->_personal_m.' b','b.IDEmployee = a.PIC','LEFT'); 
     $this->_db->join($this->_table.' c','c.IDRoot = a.IDRoot','LEFT'); 
     $this->_db->where('a.AddedBy',$user);
     $this->_db->where('a.ComplainDate >=',$from);
     $this->_db->where('a.ComplainDate <=',$until);
     $this->_db->where('a.DeleteFlag','A');
      $result = $this->_db->get();
      if($result->num_rows()>0){
          return $result->result_array();          
      }else{
          return 'empty';
      }
      
  } 
  
  function get_rootcause($where){
        $this->_db->where($where);
        return $this->_db->get($this->_rootcause);
    }
    
    
   function get_child($iduser){
        $query      = "SELECT H.*, D.* 
                       FROM  $this->_personal_m H 
                       LEFT JOIN $this->_personal_d D ON H.IDEmployee = D.IDEmployee 
                       WHERE H.IDEmployeeParent = '$iduser'"
                ;
        return $this->_emp->query($query);
    }
    
   function get_personal($iduser){
        $query      = "SELECT H.*, D.* 
                       FROM  $this->_personal_m H 
                       LEFT JOIN $this->_personal_d D ON H.IDEmployee = D.IDEmployee 
                       WHERE H.IDEmployee = '$iduser'"
                ;
        return $this->_emp->query($query);
    }
    
     function get_prs_public($iduser){
        $this->_db->where("IDEmployee",$iduser);
        return $this->_db->get($this->_personal_m);
    }
        
   function getdata() {
        $a = 'isib_public.'.$this->_table ;
        $this->datatables->select("$a.IDRoot AS IDRoot,                                 
                                    $a.RootName AS RootName 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }

   function getalldata($from,$until){
        $a = 'isib_public.'.$this->_rootcause ;
        $b = 'isib_public.'.$this->_table ;
	$c = 'isib_public.'.$this->_personal_m;
        $this->datatables->select("$a.ID AS ID,                                 
                                    $a.IDRoot AS IDRoot, 
				    $a.AddedBy AS AddedBy, 
                                    $c.FullName AS FullName, 	
                                    $b.RootName AS RootName, 
                                    $a.ComplainNote AS ComplainNote, 
                                    $a.ComplainDate AS ComplainDate, 
                                    $a.RootCause AS RootCause, 
                                    $a.ProblemNote AS ProblemNote, 
                                    $a.SolutionNote AS SolutionNote, 
                                    $a.SolutionDate AS SolutionDate, 
                                    $a.StatusProblem AS StatusProblem, 
                                    $a.TypeProblem AS TypeProblem, 
                                    $a.PIC AS PIC,
                                    $a.HoDConf AS HODC,
                                    $a.RejectNote AS RNote,
                                    IF ($a.RootCause = '1', 'Human',
                                    IF ($a.RootCause = '2', 'System',
                                    IF ($a.RootCause = '3', 'Eksternal',''
                                    ))) AS Cause,   
				    IF ($a.IDLocation = '1', 'Kapuk',
                                    IF ($a.IDLocation = '2', 'Bitung','-')) AS Location,	  
                                    IF ($a.StatusProblem = '0', 'Waiting',
                                    IF ($a.StatusProblem = '1', 'Solved',
                                    IF ($a.StatusProblem = '2', 'Suspended',
                                    IF ($a.StatusProblem = '3', 'Unsolved',
                                    IF ($a.StatusProblem = '4', 'In Progress',
				    IF ($a.StatusProblem = '5', 'Reject',''	
                                    )))))) AS Status,
                                    IF ($a.TypeProblem = '1', 'Request',
                                    IF ($a.TypeProblem = '2', 'Complain',''
                                    )) AS Type, 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDRoot = $b.IDRoot", 'left');  
	$this->datatables->join($c, "$a.AddedBy = $c.IDEmployee", 'left');     
        $this->datatables->where("$a.ComplainDate >=",$from);
        $this->datatables->where("$a.ComplainDate <=",$until);       
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }

    
    function getdata_user($from,$until,$user){
        $a = 'isib_public.'.$this->_rootcause ;
        $b = 'isib_public.'.$this->_table ;
	$c = 'isib_public.'.$this->_personal_m;
        $this->datatables->select("$a.ID AS ID,                                 
                                    $a.IDRoot AS IDRoot,
				    $a.AddedBy AS AddedBy, 
                                    $c.FullName AS FullName, 
                                    $b.RootName AS RootName, 
                                    $a.ComplainNote AS ComplainNote, 
                                    $a.ComplainDate AS ComplainDate, 
                                    $a.RootCause AS RootCause, 
                                    $a.ProblemNote AS ProblemNote, 
                                    $a.SolutionNote AS SolutionNote, 
                                    $a.SolutionDate AS SolutionDate, 
                                    $a.StatusProblem AS StatusProblem, 
                                    $a.TypeProblem AS TypeProblem, 
                                    $a.PIC AS PIC,
                                    $a.HoDConf AS HODC,
                                    $a.RejectNote AS RNote,
                                    IF ($a.RootCause = '1', 'Human',
                                    IF ($a.RootCause = '2', 'System',
                                    IF ($a.RootCause = '3', 'Eksternal',''
                                    ))) AS Cause,  
				    IF ($a.IDLocation = '1', 'Kapuk',
                                    IF ($a.IDLocation = '2', 'Bitung','-')) AS Location,   
                                    IF ($a.StatusProblem = '0', 'Waiting',
                                    IF ($a.StatusProblem = '1', 'Solved',
                                    IF ($a.StatusProblem = '2', 'Suspended',
                                    IF ($a.StatusProblem = '3', 'Unsolved',
                                    IF ($a.StatusProblem = '4', 'In Progress',''
                                    ))))) AS Status,
                                    IF ($a.TypeProblem = '1', 'Request',
                                    IF ($a.TypeProblem = '2', 'Complain',''
                                    )) AS Type, 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDRoot = $b.IDRoot", 'left');
      	$this->datatables->join($c, "$a.AddedBy = $c.IDEmployee", 'left');   
        $jumlaharray = count($user);        
        if($jumlaharray =='1'){
            $data =  $user; 
        }else{
             //$filter = array_unique($user);
             $filter = array_keys(array_flip($user));
             $data =   implode(",", $filter);
           
        }
        
        $where = "$a.AddedBy IN($data)"; 
        
       //print_r($where);
        
        $this->datatables->where("$a.ComplainDate >=",$from);
        $this->datatables->where("$a.ComplainDate <=",$until);
        $this->datatables->where($where);
        $this->datatables->where("$a.DeleteFlag", "A");
         return $this->datatables->generate();
    }
    
    function getrootcause(){
        return $this->_db->get($this->_table)->result_array();
    }
    
    function getby_id($id){
        $this->_db->where('IDRoot',$id);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return NULL; 
        }
                
    }
    function getby_id_root($id){
        $this->_db->where('ID',$id);
        $result = $this->_db->get($this->_rootcause);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return NULL; 
        }
    }
    
    function getlast_id($user){
       $this->_db->where('AddedBy',$user);
       $this->_db->order_by('ID','DESC');
       $result = $this->_db->get($this->_rootcause);
       if($result->num_rows()>0){
          $row = $result->row();
          return $row->ID;
       }else{
           return 'empty';
       }
    }
    
    function getby_name($code){
        $this->_db->where('RootName',$code);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
           return 'exist';
        } else {
           return 'empty';
        }
                
    }  
    
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    function insert_root($record) {
        $this->_db->insert($this->_rootcause, $record);
    }
    function insert_user($record) {
        $this->_db->insert($this->_user, $record);
    }
    function insert_createuser($record) {
        $this->_db->insert($this->_createuser, $record);
    }
    function insert_install($record) {
        $this->_db->insert($this->_install, $record);
    }
    function insert_createfolder($record) {
        $this->_db->insert($this->_createfolder, $record);
    }
    function insert_accessfolder($record) {
        $this->_db->insert($this->_accessfolder, $record);
    }
    function insert_agreement($record) {
        $this->_db->insert($this->_agreement, $record);
    }
    
    function update($id,$record){
       $this->_db->where('IDRoot',$id); 
       $this->_db->update($this->_table,$record);         
    }
    function update_root($id,$record){
       $this->_db->where('ID',$id); 
       $this->_db->update($this->_rootcause,$record);         
    }
    function get_param($where){
		$this->_emp->where($where);
		return $this->_emp->get($this->_param);
	}
  
}

