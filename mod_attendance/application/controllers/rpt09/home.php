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

       $query = $this->employee->get_rjob_standar()->result();
	$i = 0;
	foreach ($query as $r) {
	    $i++;
            $data['default']['f04'][-1]['value'] = 'ALL';
	    $data['default']['f04'][-1]['display'] = 'ALL GROUP';
	    $data['default']['f04'][$i]['value'] = $r->IDJobGroup;
	    $data['default']['f04'][$i]['display'] = $r->GroupName;   
	}

        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));
        
        $idmenu                    = "104";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt09/home', $data);
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

    function presencedata($group, $f, $u, $name = '') {
        $dfrom = date('Y-m-d', strtotime($f));
        $duntil = date('Y-m-d', strtotime($u));

        $result = $this->report->report_absence($dfrom, $duntil, $group, $name);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {
            $valid = "true";
            $json = '{ "mesg":"' . 'Data Already Exists' . '",                                   
                       "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        } else {
            $valid = "false";
            $json = '{ "mesg":"' . 'There\'s no data found in this periode (' . $dfrom . " to " . $duntil . ')",                                   
                      "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        }
    }

    function iframedata($group, $from, $until, $name = '') {
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $data['url'] = site_url('rpt09/home/reportdata/' . $group . '/' . $dfrom . '/' . $duntil . '/' . $name);
        $this->load->view('rpt09/iframe', $data);
    }

    function reportdata($group, $from, $until, $name = '') {
        $resuldata = $this->report->report_absence($from, $until, $group, $name);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {
           
            $ng = $this->libfun->get_name_group($group);
            
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['jobgroup'] = $ng;
            $data['fulname'] = $name;
            $data['resultdata'] = $resuldata;
            $data['url_excel'] = site_url('rpt09/home/excel' . '/' . $group . '/' . $from . '/' . $until . '/' . $name);

            $this->load->view('rpt09/report', $data);
        }
    }

    function excel($group, $fromdate, $untildate, $nip) {
        $result = $this->report->report_absence($fromdate, $untildate, $group, $nip);

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
        $objSheet->setTitle('absence');

        $objSheet->getStyle('A1:Q9')->getFont()->setBold(true)->setSize(10);
        
        $ng = $this->libfun->get_name_group($group);
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('ABSENCE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
        $objSheet->getCell('A5')->setValue('JOB GROUP');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($ng);
        $objSheet->getCell('F8')->setValue('TYPE LEAVE');
        $objSheet->getCell('A9')->setValue('ID');
        $objSheet->getCell('B9')->setValue('ID EMPLOYEE');
        $objSheet->getCell('C9')->setValue('FULLNAME');
        $objSheet->getCell('D9')->setValue('DATE');
        $objSheet->getCell('E9')->setValue('DAY OF WEEK');
        $objSheet->getCell('F9')->setValue('A');
        $objSheet->getCell('G9')->setValue('OL');
        $objSheet->getCell('H9')->setValue('SN');
        $objSheet->getCell('I9')->setValue('NOTE');

        $i = 9;
        $n = $m = 0;
        $lastemp_IDEmployee='';
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

        $A_Count = $OL_Count = $SN_Count = 0;

        if ($result != NULL) {
            foreach ($result as $row) {
                if ($row['Description'] != 'A' && $row['Description'] != 'OL' && $row['Description'] != 'SN') continue; 
                if($lastemp_IDEmployee != $row['IDEmployee'] && $n>0){
                    $i++;
                    $objSheet->getCell('E' . $i)->setValue('TOTAL');
                    $objSheet->getCell('F' . $i)->setValue($A_Count);
                    $objSheet->getCell('G' . $i)->setValue($OL_Count);
                    $objSheet->getCell('H' . $i)->setValue($SN_Count);
                    $A_Count = $OL_Count = $SN_Count = 0;
                }
                
                $n++;
                $i++;
                $data['A'] = $data['OL'] = $data['SN'] = '';
                $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                $data['DayOfWeek'] = $day[date('w', strtotime($row['PresenceDate']))];
                $data['Note'] = $row['rDescription'];
                $data['IDEmployee'] = $row['IDEmployee'];
                $data['FullName'] = $row['FullName'];

                if ($row['Description'] == 'A') {
                    $A_Count+= 1;
                    $data['A'] = 1;
                } elseif ($row['Description'] == 'OL') {
                    $OL_Count+= 1;
                    $data['OL'] = 1;
                } elseif ($row['Description'] == 'SN') {
                    $SN_Count+= 1;
                    $data['SN'] = 1;
                }
                
                $lastemp_IDEmployee= $row['IDEmployee'];
                
                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue("'".$data['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($data['FullName']);
                $objSheet->getCell('D' . $i)->setValue($data['PresenceDate']);
                $objSheet->getCell('E' . $i)->setValue($data['DayOfWeek']);
                $objSheet->getCell('F' . $i)->setValue($data['A']);
                $objSheet->getCell('G' . $i)->setValue($data['OL']);
                $objSheet->getCell('H' . $i)->setValue($data['SN']);
                $objSheet->getCell('I' . $i)->setValue($data['Note']);
                
                
            }
            $i++;
            $objSheet->getStyle('A'.$i.':I'.$i)->getFont()->setBold(true)->setSize(10);
            $objSheet->getCell('A' . $i)->setValue();
            $objSheet->getCell('B' . $i)->setValue();
            $objSheet->getCell('C' . $i)->setValue();
            $objSheet->getCell('D' . $i)->setValue();
            $objSheet->getCell('E' . $i)->setValue('TOTAL');
            $objSheet->getCell('F' . $i)->setValue($A_Count);
            $objSheet->getCell('G' . $i)->setValue($OL_Count);
            $objSheet->getCell('H' . $i)->setValue($SN_Count);
            $objSheet->getCell('I' . $i)->setValue();
        }
        $objSheet->getStyle('A8:I' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:I' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:I' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
	ob_end_clean();
        $objWriter->save($path_file . "absence" . $ext);
        $data = file_get_contents($path_file . "absence" . $ext);
        force_download("absence"."-".date('d-m-Y h:i:s') . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */





