<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Picture_model extends CI_Model {
    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('empcenter',TRUE);		
        $this->_table ='t05photo';
    }
        
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    
    function update($nip,$record){
       $this->_db->where('IDEmployee',$nip); 
       $this->_db->update($this->_table,$record);         
    }
    
   function getfoto($nip){
        $this->_db->where('IDEmployee',$nip); 
        $result = $this->_db->get($this->_table);
        if($result->num_rows()>0){
            $this->_db->where('IDEmployee',$nip); 
            $this->_db->order_by('ID','DESC'); 
            $row = $this->_db->get($this->_table)->row();
            $source = $row->UrlImage; 
            $checktitik =  $source[0];      
            
            if($checktitik=='.'){
                 $img = explode("..", $row->UrlImage);	          
                 $pp = $img[0].$img[1];
            }else{
                 $pp = $row->UrlImage;
            }
            
             return $this->session->userdata('sess_base_url').$pp;
        }else{
             return $this->session->userdata('sess_base_url').'public/theme/images/avatar-style-dark.jpg';
        }
       
   }
   
   function checkfoto($nip,$record){
        $this->_db->where('IDEmployee',$nip); 
        $result = $this->_db->get($this->_table);
        if($result->num_rows()>0){
            $this->update($nip, $record);
        }else{
            $this->insert($record);
        }
   }
   
   
   function upload_process($config, $file){
        $status = "";
	     $msg = "";
        //
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload($file))
        {
            $status = 'error';
            $msg = $this->upload->display_errors('', '');
        }
        else
        {
            $status = 'success';
            $msg = $this->upload->data();
        }
        //
        $result = array(
            'status' => $status,
            'msg' => $msg
        );
        return $result;
    }
    
    
     // Generate Random Digit
    function genRndDgt($length = 8, $specialCharacters = true) {
        $digits = '';	
        $chars = "abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";

        if($specialCharacters === true)
                $chars .= "!?=/&+,.";


        for($i = 0; $i < $length; $i++) {
                $x = mt_rand(0, strlen($chars) -1);
                $digits .= $chars{$x};
        }

        return $digits;
    }
   
   
   function crop_process($config){
        $status = "";
		  $msg = "";
        //
        $this->load->library('image_lib', $config);
        $this->image_lib->initialize($config); //PENTING:: digunakan untuk resizing kedua
        //
        if ( ! $this->image_lib->crop())
        {
            $status = 'error';
            $msg = $this->image_lib->display_errors('','');
        }
        else{
            $status = 'success';
            $msg = '';
        }
        //
        $result = array(
            'status' => $status,
            'msg' => $msg
        );
        return $result;
    }
    
    
    function resize_process($config){
        $status = "";
	$msg = "";
        //
        $this->load->library('image_lib', $config);
        $this->image_lib->initialize($config); //PENTING:: digunakan untuk resizing kedua
        //
        if ( ! $this->image_lib->resize())
        {
            $status = 'error';
            $msg = $this->image_lib->display_errors('','');
        }
        else{
            $status = 'success';
            $msg = '';
        }
        //
        $result = array(
            'status' => $status,
            'msg' => $msg
        );
        return $result;
    }
  
   
}

