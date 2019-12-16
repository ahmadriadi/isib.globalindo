<?php

class Mediatutorialupload extends CI_Model {
    function __construct()
    {
        parent::__construct();	
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
}
?>
