<?php

class Mediatutorialprofile extends CI_Model {
    function __construct()
    {
        parent::__construct();	
        $this->load->helper(array('html','url'));
    }
    
    /*
     fungsi untuk men-generate profile thumb
    */
    function genProfileThumb(){
       $base_url = $this->session->userdata('sess_base_url');
         
        $tmpl = 'trx01/_thumbnail_with_status';
        $recent_status = '';
        $recent_profilepic = $base_url.'public/theme/images/avatar-style-light.jpg';
        
        
        //
        $data = array(
            'recent_status' => $recent_status,
            'recent_profilepic' => img($recent_profilepic)
        );
        return $this->load->view($tmpl, $data, true);
        
    }
}
