<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db2  = $this->load->database('attendance',TRUE);
        $this->_db   = $this->load->database('security',TRUE);		
        $this->_tbl1 = 't01userlogin';
        $this->_tbl2 = 'm02cardmap';
        $this->_tbl_foto = 'r06userprofile';
	$this->_personal = 'isib_employee.m01personal_d';
    }


    function validate_credential($uid, $pass) {
        $sql = "SELECT a.Username,a.Password,a.Role  
               FROM $this->_tbl1 a
               INNER JOIN  $this->_personal b ON a.Username = b.IDEmployee
               WHERE 
                b.Status='A' AND 
                a.Username ='$uid' AND
                a.Password = '" . md5($uid . $pass) . "'    
            ";

        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }    

   /*	
    function validate_credential($uid, $pass) {
        $this->_db->where('Username', $uid);
        $this->_db->where('Password', md5($uid.$pass));
        $this->_db->where('Status', 'A');
        $this->_db->limit(1);
        $query  = $this->_db->get($this->_tbl1);
        if ($query->num_rows()==1) {
            return $query->row();
        } else {
            return NULL; 
        }
    }
*/


    function get_log($uid){
        $this->_db->where("Username",$uid);
        return $this->_db->get($this->_tbl1);
    }
    function update_log($uid,$rec){
        $this->_db->where("Username",$uid);
        return $this->_db->update($this->_tbl1,$rec);
    }
    function update_flag(){
        $this->_db->where("Username",$uid);
        return $this->_db->update($this->_tbl1,$rec);     
    }
	
    function insertfoto($record){
        $this->_db->insert($this->_tbl_foto,$record);
        
    }
    
    function get_pictureprofile($user){
       $this->_db->where('IDUser',$user); 
       $this->_db->order_by('ID','DESC'); 
       $result = $this->_db->get($this->_tbl_foto);
       if($result->num_rows() > 0){           
           return $result->row();
       }else{
           return null;
       }
        
    }
    function get_enroll($uid){
        $this->_db2->where("IDEmployee",$uid);
	$this->_db2->where("LastStatus","T");
        $result =  $this->_db2->get($this->_tbl2);
	if($result->num_rows() > 0){           
           return $result->row();
       }else{
           return 'empty';
       }
	
    }
	
}
