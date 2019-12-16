<?php

//OVERTIME
class Synchrondata extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('synchrondata_model', 'synchrondata');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $data['change_monthly_salary'] = site_url('tools/synchrondata/monthlysalary');       
        $this->load->view('tools/home', $data);
    }
    
    
    function monthlysalary(){
        $this->updating_monthlysalary();
        echo "Update Monthly Salary Finish";
        
    }
    
    function updating_monthlysalary() {
        ini_set('memory_limit', '-1'); // for unlimited size  
        $result = $this->synchrondata->getall_employee_bitung();
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            $no =0;
            foreach ($result as $row) {
               $no++;                
                //echo 'NO '.$no." NIP ".$row['IDEmployee'].' Name '.$row['FullName'].' Location '.$row['IDLocation'].' Group '.$row['IDJobGroup'].' Monthly Salary '.$row['MonthlySalary'].'<br/>';
               
                $nip = $row['IDEmployee'];
                $group = $row['IDJobGroup'];
                $monthlysalary = '2442000';                
                $insurance = ($group=='LT')?(2/100)*$monthlysalary:(0.25/100) *$monthlysalary;
                $dailysalary = $monthlysalary/30;     
                $dailyovertimeperhour = $monthlysalary/173;  
                           
                
                $record = array(                    
                    'MonthlySalary' => $monthlysalary,
                    'Insurance' => $insurance,
                    'DailySalary' => $dailysalary,               
                    'OvertimePerHour' => $dailyovertimeperhour,  
                    'AddedBy' => 'SYSTEM',
                    'AddedDate' => $this->Datetime,
                    'AddedIP' => $this->Ip
                );
                     
               $this->synchrondata->update_monthly($nip, $record);
            }
        }
    }

    

}
