<?php

//OVERTIME
class Rtrw extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Synchrondata_model', 'syncron');
        date_default_timezone_set("Asia/Jakarta");
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->updatert();
    }

    function updatert() {
        $edited = '0091010601';
        $editdate = date('Y-m-d H:i:s', strtotime('2014-08-22 00:00:00'));
        $result = $this->syncron->getpersonal_byedit($edited, $editdate);
        $checkresult = ($result == '' or $result == NULL) ? 'empty' : 'exist';
        if ($checkresult !== 'empty') {
            foreach ($result as $row) {
                $nip = $row['IDEmployee'];
                $nama = $row['FullName'];

                $record = array(
                    "KTPRT" => $row['KTPRW'],
                    "KTPRW" => $row['KTPRT']
                );
                
                echo $nama. 'RT ='.$row['KTPRT'].' RW ='.$row['KTPRW'].'<BR/>';
               // if($nip !=='0267190710'){
		 // $this->syncron->updatertrw_public($nip, $record);
                  //$this->syncron->updatertrw_employee($nip, $record);
		//}
               
            }
        }
    }

}
