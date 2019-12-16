<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("chart_model","crt");
    }    
       
    function index (){
        $jmll           = $this->crt->get_jml_gender("L")->row()->jml;
        $jmlp           = $this->crt->get_jml_gender("P")->row()->jml;
        $jmlu           = $this->crt->get_jml_gender()->row()->jml;
        $data['data']   = "{label : \"Laki-Laki\", data : $jmll },{label : \"Perempuan\", data : $jmlp },{label : \"Undefined\", data : $jmlu }";
        $this->load->view('emp_stat/home',$data); 
    }
    
}

/* End of file main.php */
/* Location: ./application/controllers/welcome.php */
