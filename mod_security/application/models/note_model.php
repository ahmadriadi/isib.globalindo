<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Note_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('empcenter',TRUE);		
        $this->_tbl1 = 'histuseractivity';
    }
    
    function get_by_id($id){
        $this->_db->where('ID',$id);
        $result = $this->_db->get($this->tbl1);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return NULL; 
        }
        
        
    }
    
   function get_idparent($iduser){       
       $this->_db->where('IDEmployee',$iduser);
       $result = $this->_db->get('m01personal');
       if($result->num_rows() >0){
           return $result->row();           
           
       }
           return null;       
     }
    
   function get_idposition($parent){
       $this->_db->where('IDEmployee',$parent);
       $result = $this->_db->get('m01personal_d');
       if($result->num_rows() >0){
           return $result->row();           
           
       }
           return null;       
     }
  	    


    function get_alldata_parent($from,$until,$iduser){        
        $where = "WHERE a.DateCurrent BETWEEN '$from' AND '$until' AND UserID='$iduser' AND ParentID='$iduser'";  
        $query = "SELECT
                    a.*,
                    b.FullName as User
                FROM
                  histuseractivity a
                LEFT JOIN
                   m01personal b
                 ON
                  a.UserID  = b.IDEmployee
                 $where
                 ORDER BY
                  a.DateCurrent DESC                  
                ";
        
        $result = $this->_db->query($query);        
         if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return NULL; 
        }
        
    }
    
    function get_alldata_user($from,$until,$user){        
        $where = "WHEREa a.DateCurrent BETWEEN '$from' AND '$until' AND ParentID ='$user'";  
        $query = "SELECT
                    a.*,
                    b.FullName as User
                FROM
                  histuseractivity a
                LEFT JOIN
                   m01personal b
                 ON
                  a.UserID  = b.IDEmployee
                 $where
                 ORDER BY
                  a.DateCurrent DESC                  
                ";        
        $result = $this->_db->query($query);        
         if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return NULL; 
        }
        
    }
    
    
    function get_alldata($from,$until,$user,$position){        
        $a = "WHERE a.DateCurrent BETWEEN '$from' AND '$until' AND ParentID ='$user'";               
        $b = "WHERE a.DateCurrent BETWEEN '$from' AND '$until' AND UserID ='$user'";      
        
        if($position =='MANAGER'){            
            $where = $a;
        }else{
            $where = $b;
        }
               
        $query = "SELECT
                    a.*,
                    b.FullName as User
                FROM
                  histuseractivity a
                LEFT JOIN
                   m01personal b
                 ON
                  a.UserID  = b.IDEmployee
                 $where
                 ORDER BY
                  a.DateCurrent DESC                  
                ";        
        $result = $this->_db->query($query);        
         if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return NULL; 
        }
        
    }
    
    
    function get_data($from,$until) {
        $this->_db->where('DateCurrent >=',$from);
        $this->_db->where('DateCurrent <=',$until);
        $query  = $this->_db->get($this->_tbl1);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return NULL; 
        }
    }

    function insert($record) {
        $this->_db->insert($this->_tbl1, $record);
    }
    
    function update($id,$record){
       $this->_db->where('ID',$id); 
       $this->_db->update($this->_tbl1,$record); 
        
    }
    
    function delete($id) {
        $this->_db->delete($this->_tbl1, array('ID' => $id));
    }
   
}

