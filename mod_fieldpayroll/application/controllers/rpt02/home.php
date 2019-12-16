<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Specialslip_model', 'specialslip');       
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
        $this->load->view('rpt02/home', $data);
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

    function periodemonthly() {
        $today = array('Year' => date('Y'), 'Month' => date('m'));
        $untildate = date('d-m-Y', strtotime($today['Year'] . "-" . $today['Month'] . "-24"));
        $from = date('d-m-Y', strtotime("-1 month +1 day", strtotime($untildate)));
        return $from . " " . $untildate;
    }
    
    
    

    function specialslip() {
        $dfrom = date('Y-m-d', strtotime($this->input->post('f01')));
        $duntil = date('Y-m-d', strtotime($this->input->post('f02')));        
        $nip = $this->input->post('f03');        
        
        $result = $this->specialslip->getspecial_slip($duntil,$nip);         
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
        $data['url'] = site_url('rpt02/home/printslip/' . $posting . '/' . $dfrom.'/'.$duntil.'/'.$nip);
        $this->load->view('rpt02/iframe', $data);
    }

   function printslip($posting,$start,$until,$id = '') {
        $DatePosting = date('Y-m-d', strtotime($posting));
        $i = 0;
        $result_specialslip = $this->specialslip->getspecial_slip($DatePosting, $id);
        if ($result_specialslip) {
            set_time_limit(0);
            $this->load->library('mpdf54/mpdf');
            $this->mpdf = new mPDF('c', array(210, 135), '9', 'dejavusans', 10, 10, 10, 10, 0, 0);

            foreach ($result_specialslip as $row) {
                $i++;
                
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
                $data['slip']['TotalIncome'] = $row['SumDailySalaryPayment'];
                $data['slip']['TotalDeduction'] = $row['TotalDeduction'];
                $data['slip']['TakeHomePay'] = $row['SumDailySalaryPayment'];
                $data['slip']['Footer'] = $row['ID'] . '/' . date('Ymd', strtotime($Until));

                //echo "$i "; 
                $html = $this->load->view('rpt02/report', $data, TRUE);
                $this->output->set_output($html);
                $this->mpdf->WriteHTML($html);
                $this->mpdf->WriteHTML('<pagebreak sheet-size=210 135;/>');
            }
            set_time_limit(60);
            $this->mpdf->Output('specialslip.' . date('YMd', strtotime($posting)) . '.' . $this->input->ip_address() . '.pdf', 'I');
        }
    }
    
    
    
    function exportdata($posting,$start,$until,$id = '') {
        $ext = '.xlsx';
        $path_file = '/tmp/';
        
        $fromdate = date('Y-m-d', strtotime($fromdate));
        $untildate = date('Y-m-d', strtotime($untildate));

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->helper('download');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("title")
                ->setDescription("description");

        // currency format, &euro; with < 0 being in red color
        $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        // number format, with thousands seperator and two decimal points.
        $numberFormat = '#,#0.##;[Red]-#,#0.##';

        // writer will create the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();
        // rename the sheet
        $objSheet->setTitle('payrollslip report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:Q1')->getFont()->setBold(true)->setSize(12);

        // write header       
        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');
        $objSheet->getCell('C1')->setValue('No.Rekening');
	$objSheet->getCell('D1')->setValue('Group'); 	
        $objSheet->getCell('E1')->setValue('PostingDate');
        $objSheet->getCell('F1')->setValue('SumDailySalaryPayment');
        $objSheet->getCell('G1')->setValue('SumDailyIncentiveShift');
        $objSheet->getCell('H1')->setValue('SumDailyOvertimePayment');
        $objSheet->getCell('I1')->setValue('OtherIncome');
        $objSheet->getCell('J1')->setValue('InsurancePayment');
        $objSheet->getCell('K1')->setValue('AbsencePayment');
        $objSheet->getCell('L1')->setValue('LoanPayment');
        $objSheet->getCell('M1')->setValue('OutstandingPayment');
        $objSheet->getCell('N1')->setValue('OtherPayment');
        $objSheet->getCell('O1')->setValue('TotalIncome');
        $objSheet->getCell('P1')->setValue('TotalDeduction');
        $objSheet->getCell('Q1')->setValue('TakeHomePay');
      
        $posting = date('Y-m-d',strtotime($until));
      
        $result = $this->specialslip->getspecial_slip($posting, $id);
        if ($result != NULL) {   
            $i = 1;
            foreach ($result as $row) {
                $i++;
                  
		$group = $this->libfun->get_name_group($row['IDJobGroup']);
                
                $objSheet->getCell('A' . $i)->setValue("'".$row['IDEmployee']);
                $objSheet->getCell('B' . $i)->setValue($row['FullName']);
                $objSheet->getCell('C' . $i)->setValue($row['BankAccountNo']);
		$objSheet->getCell('D' . $i)->setValue($group);
                $objSheet->getCell('E' . $i)->setValue(date('d-m-Y',strtotime($row['PostingDate'])));
                $objSheet->getCell('F' . $i)->setValue($row['SumDailySalaryPayment']);
                $objSheet->getCell('G' . $i)->setValue('0');
                $objSheet->getCell('H' . $i)->setValue('0');
                $objSheet->getCell('I' . $i)->setValue('0');
                $objSheet->getCell('J' . $i)->setValue('0');
                $objSheet->getCell('K' . $i)->setValue('0');
                $objSheet->getCell('L' . $i)->setValue('0');
                $objSheet->getCell('M' . $i)->setValue('0');
                $objSheet->getCell('N' . $i)->setValue('0');
                $objSheet->getCell('O' . $i)->setValue('0');
                $objSheet->getCell('P' . $i)->setValue('0');
                $objSheet->getCell('Q' . $i)->setValue($row['SumDailySalaryPayment']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:Q' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:Q' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:Q1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);
            $objSheet->getColumnDimension('F')->setAutoSize(true);       
            $objSheet->getColumnDimension('G')->setAutoSize(true);       
            $objSheet->getColumnDimension('H')->setAutoSize(true); 
	    $objSheet->getColumnDimension('I')->setAutoSize(true); 	      
	    $objSheet->getColumnDimension('J')->setAutoSize(true); 	      
	    $objSheet->getColumnDimension('K')->setAutoSize(true); 	      
	    $objSheet->getColumnDimension('L')->setAutoSize(true); 	      
	    $objSheet->getColumnDimension('M')->setAutoSize(true); 	      
	    $objSheet->getColumnDimension('N')->setAutoSize(true); 	      
	    $objSheet->getColumnDimension('O')->setAutoSize(true); 	      
	    $objSheet->getColumnDimension('P')->setAutoSize(true); 	      
	    $objSheet->getColumnDimension('Q')->setAutoSize(true); 	      

            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "specialslip" . $ext);
            $data = file_get_contents($path_file . "specialslip" . $ext);
            force_download("specialslip" . $ext, $data);
        }
    }
    
    
    
   

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */



