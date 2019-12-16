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
        
        $this->_user ='t02request_user';
        $this->_createuser='t02request_createuser';
        $this->_install ='t02request_installsoftware';
        $this->_createfolder='t02request_createfolder';
        $this->_accessfolder ='t02request_accessfolder';
        $this->_agreement ='t02request_agreement';
    }

   
    
    function getpersonal($nip){
        $sql = "SELECT a.*,b.DescStructure as Departement
                FROM $this->_personal_m a
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
  
  
  function get_child($iduser){
        $query      = "SELECT H.*, D.* 
                       FROM  $this->_personal_m H 
                       LEFT JOIN $this->_personal_d D ON H.IDEmployee = D.IDEmployee 
                       WHERE H.IDEmployeeParent = '$iduser'"
                ;
        return $this->_emp->query($query);
  }

  function get_rootcause($where){
        $this->_db->where($where);
        return $this->_db->get($this->_rootcause);
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
        $a = 'triasnet_public.'.$this->_table ;
        $this->datatables->select("$a.IDRoot AS IDRoot,                                 
                                    $a.RootName AS RootName 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }
    
   function getdata_user($from,$until,$user){
        $a = 'triasnet_public.'.$this->_rootcause ;
        $b = 'triasnet_public.'.$this->_table ;
        $this->datatables->select("$a.ID AS ID,                                 
                                    $a.IDRoot AS IDRoot, 
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
                                    IF ($a.RootCause = '1', 'Human',
                                    IF ($a.RootCause = '2', 'System',
                                    IF ($a.RootCause = '3', 'Eksternal',''
                                    ))) AS Cause,     
                                    IF ($a.StatusProblem = '1', '<b class=\"accept\">Finish</b>',
                                    IF ($a.StatusProblem = '2', '<b class=\"waiting\">Waiting</b>',
                                    IF ($a.StatusProblem = '3', '<b class=\"reject\">Stack</b>',''
                                    ))) AS Status,
                                    IF ($a.TypeProblem = '1', '<b class=\"accept\">Request</b>',
                                    IF ($a.TypeProblem = '2', '<b class=\"reject\">Complain</b>',''
                                    )) AS Type, 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDRoot = $b.IDRoot", 'left');
      
        $jumlaharray = count($user);        
        if($jumlaharray =='1'){
            $data =  $user; 
        }else{
             //$filter = array_unique($user);
             $filter = array_keys(array_flip($user));
             $data =   implode(",", $filter);
           
        }
        
        $where = "$a.AddedBy IN($data)"; 
        
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
    
  
}

