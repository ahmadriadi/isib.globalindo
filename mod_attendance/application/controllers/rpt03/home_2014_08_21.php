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
	    $data['default']['f04'][$i]['value'] = $r->IDJobGroup;
	    $data['default']['f04'][$i]['display'] = $r->GroupName;   
	}

        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));

        
        $idmenu                    = "98";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt03/home', $data);
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

        $result = $this->report->report_late($dfrom, $duntil, $group, $name);
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
        $data['url'] = site_url('rpt03/home/reportdata/' . $group . '/' . $dfrom . '/' . $duntil . '/' . $name);
        $this->load->view('rpt03/iframe', $data);
    }

    function reportdata($group, $from, $until, $name = '') {
        $resuldata = $this->report->report_late($from, $until, $group, $name);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {
	    			
 	    $ng = $this->libfun->get_name_group($group);

            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['jobgroup'] = $ng;
            $data['fulname'] = $name;
            $data['resultdata'] = $resuldata;
            $data['url_excel'] = site_url('rpt03/home/excel' . '/' . $group . '/' . $from . '/' . $until . '/' . $name);

            $this->load->view('rpt03/report', $data);
        }
    }

    function excel($group, $fromdate, $untildate, $nip) {
        $result = $this->report->report_late($fromdate, $untildate, $group, $nip);

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
        $objSheet->setTitle('late arrival');

        $objSheet->getStyle('A1:H6')->getFont()->setBold(true)->setSize(10);

      	$NMJobGroup = $this->libfun->get_name_group($group);
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('LATE ARRIVE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
        $objSheet->getCell('A5')->setValue('JOB GROUP');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($NMJobGroup);

        $objSheet->getCell('A6')->setValue('ID');
        $objSheet->getCell('B6')->setValue('ID EMPLOYEE');
        $objSheet->getCell('C6')->setValue('FULLNAME');
        $objSheet->getCell('D6')->setValue('DATE');
        $objSheet->getCell('E6')->setValue('DAY OF WEEK');
        $objSheet->getCell('F6')->setValue('ACTUAL IN');
        $objSheet->getCell('G6')->setValue('LATE HOUR');
        $objSheet->getCell('H6')->setValue('LATE HOUR SUM');

        $i = 6;
        $n = $m = 0;
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $last_idemployee = $result[0]['IDEmployee'];

        if ($result != NULL) {
            $TotLH = 0;
            foreach ($result as $row) {
                if ($row['LateHour'] == 0)
                    continue;
                if ($row['IDEmployee'] != $last_idemployee) {
                    $data['LateHour_Sum'] = 0;
                    $TotLH = 0;
                }
                $keterangan_ijin = $row['Description'];
                if ($keterangan_ijin == 'LP') {
                    
                } else {
                    $n++;
                    $i++;
                    $TotLH = $TotLH + $row['LateHour'];
                    $data['IDEmployee'] = $row['IDEmployee'];
                    $data['FullName'] = $row['FullName'];
                    $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                    $data['DayOfWeek'] = $day[date('w', strtotime($row['PresenceDate']))];
                    $data['ActualIn'] = date('H:i', strtotime($row['ActualIn']));
                    $data['LateHour'] = $row['LateHour'];
                    $data['LateHour_Sum'] = $TotLH;
                }
                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue("'" . $data['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($data['FullName']);
                $objSheet->getCell('D' . $i)->setValue($data['PresenceDate']);
                $objSheet->getCell('E' . $i)->setValue($day[date('w', strtotime($data['PresenceDate']))]);
                $objSheet->getCell('F' . $i)->setValue($data['ActualIn']);
                $objSheet->getCell('G' . $i)->setValue($data['LateHour']);
                $objSheet->getCell('H' . $i)->setValue($data['LateHour_Sum']);
                $last_idemployee = $data['IDEmployee'];
            }
        }
        $objSheet->getStyle('A6:H' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A6:H' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A6:H' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
        ob_end_clean();
        $objWriter->save($path_file . "late arrival" . $ext);
        $data = file_get_contents($path_file . "late arrival" . $ext);
        force_download("late arrival" . "-" . date('d-m-Y h:i:s') . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */



