<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report_model', 'report');
        $this->load->model('employee_model', 'employee');
	$this->load->model('uac_model', 'uac');
	$this->load->model('libraryfunction_model', 'libfun');
        $this->User = $this->session->userdata('sess_userid');
    }
	
    function index() {
        $date = $this->libfun->periode_work();
        $data['default']['f01'] = substr($date, 0, 10);
        $data['default']['f02'] = substr($date, 11, 10);
        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));
	
	$idmenu                    = "101";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt06/home', $data);
    }
	
    function autocomplete_employee() {
        $result = $this->employee->find_employee_afterresign();
        $arr = array();
        foreach ($result->result() as $row) {
            $status = $row->Status;
            $now = $row->Sekarang;
            $lock = $row->BatasFilter;
            
             if ($status == 'P') {
                if ($now <= $lock) {                    
                   $arr[] = array('idemployee' => $row->IDEmployee,
                           'fullname' => strtoupper($row->FullName)
                     ); 
                } 
             }else{                 
                  $arr[] = array('idemployee' => $row->IDEmployee,
                           'fullname' => strtoupper($row->FullName)
                     ); 
             }  
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }		
	

    function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->search_employee($q);
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

 
    function presencedata($from,$until,$nip='') {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));       
        $result = $this->report->report_travel($dfrom, $duntil,$nip);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {
            $valid = "true";
            $json = '{ "mesg":"' . 'Data Already Exist' . '",                                   
                       "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        } else {
            $valid = "false";
            $json = '{ "mesg":"' . 'Sorry no result data ' . $nip . ' on periode ' . $dfrom . " to " . $duntil . '",                                   
                      "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        }
    }

    function iframedata($from, $until,$nip='') {
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $data['url'] = site_url('rpt06/home/reportdata/' . $dfrom . '/' . $duntil . '/' . $nip);
        $this->load->view('rpt06/iframe', $data);
    }

    function reportdata($from,$until,$nip='') {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $resuldata = $this->report->report_travel($from,$until,$nip);       
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['url_excel'] =  site_url('rpt06/home/excel')."/".$from.'/'.$until.'/'.$nip;
            $data['resultdata'] = $resuldata;
            $this->load->view('rpt06/report', $data);
        }
    }

    function excel($fromdate,$untildate,$nip) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $result = $this->report->report_travel($fromdate,$untildate,$nip);         
      // Start Excel
        $ext = '.xlsx';
        $path_file = '/tmp/';

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->helper('download');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("title")
                ->setDescription("description");

        $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        $numberFormat = '#,#0.##;[Red]-#,#0.##';
        $objSheet = $objPHPExcel->getActiveSheet();
        $objSheet->setTitle('officialtravel');

        $objSheet->getStyle('A1:Q9')->getFont()->setBold(true)->setSize(10);

       
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('OFFICIAL TRAVEL REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
        
        $objSheet->getCell('A9')->setValue('ID');
        $objSheet->getCell('B9')->setValue('ID EMPLOYEE');
        $objSheet->getCell('C9')->setValue('FULLNAME');
        $objSheet->getCell('D9')->setValue('FROM DATE');
        $objSheet->getCell('E9')->setValue('UNTIL DATE');
        $objSheet->getCell('F9')->setValue('NOTE');

        $i = 9;
        $n = $m = 0;
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

        if ($result != NULL) {
            foreach ($result as $row) {
                $n++;
                $i++;
                $data['OfficialTravelDate'] = date('d-m-Y', strtotime($row['OfficialTravelDate']));
                $data['UntilDate'] = date('d-m-Y', strtotime($row['UntilDate']));
                $data['Note'] = $row['Note'];
                $data['IDEmployee'] = $row['IDEmployee'];
                $data['FullName'] = $row['FullName'];


                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue("'".$data['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($data['FullName']);
                $objSheet->getCell('D' . $i)->setValue($data['OfficialTravelDate']);
                $objSheet->getCell('E' . $i)->setValue($data['UntilDate']);
                $objSheet->getCell('F' . $i)->setValue($data['Note']);
            }
            $i++;
        }
        $objSheet->getStyle('A8:F' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:F' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:F' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
	ob_end_clean();
        $objWriter->save($path_file . "officialtravel" . $ext);
        $data = file_get_contents($path_file . "officialtravel" . $ext);
        force_download("officialtravel" . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


