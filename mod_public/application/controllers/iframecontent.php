<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Iframecontent extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        
    }

    function index() {
       
        
        $data ['url'] = 'http://triasindrasaputra.com/webmail/';
        $this->load->view('iframeview',$data);
    }




}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */



