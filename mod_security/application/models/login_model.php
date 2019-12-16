<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('security',TRUE);		
        $this->_tbl1 = 't01userlogin';
        $this->_tbl_foto = 'r06userprofile';
    }
    
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
    
    function insertfoto($record){
        $this->_db->insert($this->_tbl_foto,$record);
        
    }

    function update($user, $record) {
        $this->_db->where('Username', $user);
        $this->_db->update($this->_tbl1, $record);
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
    
}
