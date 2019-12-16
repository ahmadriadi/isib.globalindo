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
        
        $idmenu                    = "105";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt10/home', $data);
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

         $result = $this->report->report_incomplete($dfrom, $duntil, $group, $name);
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
            $json = '{ "mesg":"' . 'Sorry no result data on periode ' . $dfrom . " to " . $duntil . '",                                   
                      "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        }
    }

    function iframedata($group, $from, $until, $name = '') {
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $data['url'] = site_url('rpt10/home/reportdata/' . $group . '/' . $dfrom . '/' . $duntil . '/' . $name);
        $this->load->view('rpt10/iframe', $data);
    }

    function reportdata($group, $from, $until, $name = '') {
        $result = $this->report->report_incomplete($from, $until, $group, $name);
        $cekdata = ($result !== null) ? $result : 'empty';
        if ($cekdata !== 'empty') {
            $ng = $this->libfun->get_name_group($group);
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['jobgroup'] = $ng;
            $data['fulname'] = $name;
            $data['resultdata'] = $result;
            $data['url_excel'] = site_url('rpt10/home/excel' . '/' . $group . '/' . $from . '/' . $until . '/' . $name);

            $this->load->view('rpt10/report', $data);
        }
    }

    function excel($group, $fromdate, $untildate, $nip) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $result = $this->report->report_incomplete($fromdate, $untildate, $group, $nip);
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
        $objSheet->setTitle('incomplete');

               // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('INCOMPLETE PRESENCE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));

        $objSheet->getCell('A6')->setValue('ID');
        $objSheet->getCell('B6')->setValue('ID EMPLOYEE');
        $objSheet->getCell('C6')->setValue('FULLNAME');
        $objSheet->getCell('D6')->setValue('GROUP');
        $objSheet->getCell('E6')->setValue('DATE');
        $objSheet->getCell('F6')->setValue('DAY OF WEEK');
        $objSheet->getCell('G6')->setValue('ACTUAL IN');
        $objSheet->getCell('H6')->setValue('ACTUAL OUT');
        $objSheet->getCell('I6')->setValue('STATUS');
        $objSheet->getCell('I7')->setValue('Data Incomplete');
        $objSheet->getCell('J7')->setValue('Form Incompete');
        $objSheet->getCell('K6')->setValue('NOTE');


        // add mergecell
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->mergeCells('I6:K6');

        //add center
        $sheet->getStyle('H6:D14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $i = 7;
        $n = $m = 0;
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

        if ($result != NULL) {

            $count_incomplete = $count_presence =0;
            foreach ($result as $row) {
                if ($row['Description'] != 'NC')
                    continue;
                $n++;
                $i++;
                $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                $data['DayOfWeek'] = $day[$row['DayOfWeek']];
                $data['Note'] = $row['rDescription'];
                $data['IDEmployee'] = $row['IDEmployee'];
                $data['FullName'] = $row['FullName'];
                 $data['GroupName'] = $row['GroupName'];
                if ($row['ActualIn'] == NULL) {
                    $data['ActualIn'] = '';
                } else {
                    $data['ActualIn'] = date('H:i', strtotime($row['ActualIn']));
                }
                if ($row['ActualOut'] == NULL) {
                    $data['ActualOut'] = '';
                } else {
                    $data['ActualOut'] = date('H:i', strtotime($row['ActualOut']));
                }


                $checkincomplete= $this->report->check_dataincomplete($row['IDEmployee'],$row['PresenceDate']);
                if($checkincomplete=='exist'){
                    $data['Incomplete']='';
                    $data['Formdata']=1;
                    $data['Note'] = 'PRESENCE COMPLETE';
                    $count_presence+= 1;
                }else{
                    $data['Note'] = $row['rDescription'];
                    $data['Incomplete']=1;
                    $data['Formdata']='';
                    $count_incomplete+= 1;
                }

                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue("'" . $data['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($data['FullName']);
                $objSheet->getCell('D' . $i)->setValue($data['GroupName']);
                $objSheet->getCell('E' . $i)->setValue($data['PresenceDate']);
                $objSheet->getCell('F' . $i)->setValue($data['DayOfWeek']);
                $objSheet->getCell('G' . $i)->setValue($data['ActualIn']);
                $objSheet->getCell('H' . $i)->setValue($data['ActualOut']);
                $objSheet->getCell('I' . $i)->setValue($data['Incomplete']);
                $objSheet->getCell('J' . $i)->setValue($data['Formdata']);
                $objSheet->getCell('K' . $i)->setValue($data['Note']);
            }
            $i++;
            $objSheet->getStyle('A' . $i . ':K' . $i)->getFont()->setBold(true)->setSize(10);
            $objSheet->getCell('A' . $i)->setValue();
            $objSheet->getCell('B' . $i)->setValue();
            $objSheet->getCell('C' . $i)->setValue();
            $objSheet->getCell('D' . $i)->setValue();
            $objSheet->getCell('E' . $i)->setValue();
            $objSheet->getCell('F' . $i)->setValue();
            $objSheet->getCell('G' . $i)->setValue();
            $objSheet->getCell('H' . $i)->setValue('TOTAL');
            $objSheet->getCell('I' . $i)->setValue($count_incomplete);
            $objSheet->getCell('J' . $i)->setValue($count_presence);
            $objSheet->getCell('K' . $i)->setValue();
        }
        $objSheet->getStyle('A6:K' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A6:K' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A6:K' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A1:K' . $i)->getFont()->setSize(10);

        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
         ob_end_clean();
        $objWriter->save($path_file . "incomplete" . $ext);
        $data = file_get_contents($path_file . "incomplete" . $ext);
        force_download("incomplete" . $ext, $data);
        
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */





