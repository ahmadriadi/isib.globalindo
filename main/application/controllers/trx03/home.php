<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('happybday_model', 'hbday');    
    }

    function datahbday() {
        $month = date('m');
        echo $this->hbday->get_hbd($month);
    }

   
}
