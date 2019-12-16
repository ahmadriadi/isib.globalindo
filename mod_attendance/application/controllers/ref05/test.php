<?php

//OVERTIME
class Test extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('parampresence_model', 'parampresence');
        $this->load->model('logs_model', 'logs');
        $this->load->model('employee_model', 'employee');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('uac_model', 'uac');       
        }
        
      function index(){
        $data  = array();
        $item  = array();
        $result = $this->employee->findall_employee()->result_array();
        $i=0;
        foreach ($result as $row){
            $i++;
            $subdata = array($row['IDEmployee'],$row['FullName']);
            $data['data'][]=$subdata;
            if ($i == 5){
                break;
            }
        }
        
        
        
         echo  json_encode($data);       
        
      }  
        
        
}
