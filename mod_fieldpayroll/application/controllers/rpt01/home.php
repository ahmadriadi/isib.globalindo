<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Payrollslip_model', 'payslip');       
        $this->load->model('Employee_model', 'employee');   
	$this->load->model('uac_model', 'uac');  
        $this->load->model('libraryfunction_model', 'libfun');
        
        $this->User =$this->session->userdata('sess_userid') ;    
    }

    function index() {
        $date = $this->libfun->periode_work();
        $data['default']['f01'] = substr($date, 0, 10);
        $data['default']['f02'] = substr($date, 11, 10);
        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));
	
        $idmenu                    = "74";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt01/home', $data);
    }
    
    function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->search_employee_field($q);
        $data['response'] = 'true';
        $data['message'] = array();
        foreach ($result->result() as $row) {
            $status = $row->Status;
            $now = $row->Sekarang;
            $lock = $row->BatasFilter;

            if ($status == 'P') {
                if ($now <= $lock) {
                    $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                        'value' => $row->IDEmployee,
                        'idemployee' => $row->IDEmployee
                    );
                }
            } else {
                $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                    'value' => $row->IDEmployee,
                    'idemployee' => $row->IDEmployee
                );
            }
        }
        echo json_encode($data);
    }

    function autocomplete_employee() {
        $result = $this->employee->find_employee_active();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                           'fullname' => strtoupper($row->FullName)
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }


    function periodemonthly() {
        $today = array('Year' => date('Y'), 'Month' => date('m'));
        $untildate = date('d-m-Y', strtotime($today['Year'] . "-" . $today['Month'] . "-24"));
        $from = date('d-m-Y', strtotime("-1 month +1 day", strtotime($untildate)));
        return $from . " " . $untildate;
    }
    
    
    

    function payslip() {
        $dfrom = date('Y-m-d', strtotime($this->input->post('f01')));
        $duntil = date('Y-m-d', strtotime($this->input->post('f02')));        
        $nip = $this->input->post('f03');        
        
        $result = $this->payslip->get_payslip($duntil,$nip);         
        $checkdata = ($result=='' or $result==null)?'empty':'exist';      
        if ($checkdata == 'exist') {
            $valid = "true";
            $json = '{ "mesg":"' . 'Data Already Exist' . '",                                   
                       "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        } else {
            $valid = "false";
            $json = '{ "mesg":"' . 'Sorry no result data on periode posting ' . $duntil.'",                                   
                      "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        }
       
    }    
    
   function iframedata($pos, $from, $until,$id ='') {
        $posting = date('Y-m-d', strtotime($pos));
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $nip = $id;
        $data['url'] = site_url('rpt01/home/printslip/' . $posting . '/' . $dfrom.'/'.$duntil.'/'.$nip);
        $this->load->view('rpt01/iframe', $data);
    }

   function printslip($posting,$start,$until,$id = '') {
        $DatePosting = date('Y-m-d', strtotime($posting));
        $i = 0;
        $result_payslip = $this->payslip->get_payslip($DatePosting, $id);
        if ($result_payslip) {
            set_time_limit(0);
            $this->load->library('mpdf54/mpdf');
            $this->mpdf = new mPDF('c', array(210, 135), '9', 'dejavusans', 10, 10, 10, 10, 0, 0);

            foreach ($result_payslip as $row) {
                $i++;
                
		$location = ($row['IDLocation']=='1')?'KPK':'BTG';

                $absen = $row['AbsencePayment'];
                $absenfix = substr($absen,1);
                $data['slip']['ID'] = $row['ID'];
                $data['slip']['Posting']    = $posting;
                $data['slip']['StartDate']  = $start;
                $data['slip']['UntilDate']  = $until;
                $data['slip']['IDEmployee'] = $row['IDEmployee'];
                $data['slip']['FullName']   = $row['FullName'];
                $data['slip']['IDUnitGroup']= $row['IDUnitGroup'];
                $data['slip']['IDJobGroup'] = $row['IDJobGroup'];
		$data['slip']['Location'] = $location;
                $data['slip']['SumDailySalaryPayment']   = $row['SumDailySalaryPayment'];
                $data['slip']['SumDailyIncentiveShift']  = $row['SumDailyIncentiveShift'];
                $data['slip']['SumDailyOvertimePayment'] = $row['SumDailyOvertimePayment'];
                $data['slip']['SumDailyOvertimeMeal'] = $row['SumDailyOvertimeMeal'];
                $data['slip']['OvertimeIncentive'] = $row['OvertimeIncentive'];
                $data['slip']['OtherIncome'] = $row['OtherIncome'];
                $data['slip']['InsurancePayment'] = $row['InsurancePayment'];
                $data['slip']['AbsencePayment'] = $absenfix;
                $data['slip']['LoanPayment'] = $row['LoanPayment'];
                $data['slip']['OutstandingPayment'] = $row['OutstandingPayment'];
                $data['slip']['OtherPayment'] = $row['OtherPayment'];
                $data['slip']['TotalIncome'] = $row['TotalIncome'];
                $data['slip']['TotalDeduction'] = $row['TotalDeduction'];
                $data['slip']['TakeHomePay'] = $row['TakeHomePay'];
                $data['slip']['Footer'] = $row['ID'] . '/' . date('Ymd', strtotime($Until));

                //echo "$i "; 
                $html = $this->load->view('rpt01/report', $data, TRUE);
                $this->output->set_output($html);
                $this->mpdf->WriteHTML($html);
                $this->mpdf->WriteHTML('<pagebreak sheet-size=210 135;/>');
            }
            set_time_limit(60);
            $this->mpdf->Output('payslip.' . date('YMd', strtotime($posting)) . '.' . $this->input->ip_address() . '.pdf', 'I');
        }
    }
    
   

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */



