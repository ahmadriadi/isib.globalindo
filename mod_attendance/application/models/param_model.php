<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Param_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('empcenter', TRUE);
        $this->_table   = 'm04param';  
	$this->_personal = 'm01personal';    
    }
    

 function get_param($userid){
     $this->_db->where('ParamValue',$userid);
     $result = $this->_db->get($this->_table);
     if($result->num_rows()>0){
         return $result->row();
         
     }else{
         return null;
     }
     
     
     
 }

function get_hrd() {
        $sql = "SELECT a.*,b.FullName,b.InternalEmail,b.ExternalEmail,b.NoHP
            FROM $this->_table a
            LEFT JOIN isib_public.$this->_personal b
            ON a.ParamValue = b.IDEmployee
            WHERE a.IDParam='IDHRD' AND b.DeleteFlag='A'
            ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }



function get_hrdmgr() {
        $sql = "SELECT a.*,b.FullName,b.InternalEmail,b.ExternalEmail,b.NoHP
            FROM $this->_table a
            LEFT JOIN isib_public.$this->_personal b
            ON a.ParamValue = b.IDEmployee
            WHERE a.IDParam='IDHRDMGR' AND b.DeleteFlag='A'        
            ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    
 
}

?>

