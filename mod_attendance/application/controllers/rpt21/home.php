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

        $data['default']['f04'][1]['value'] = "ST";
        $data['default']['f04'][1]['display'] = "STAFF";
        $data['default']['f04'][1]['checked'] = "CHECKED";
        $data['default']['f04'][2]['value'] = "LAP";
        $data['default']['f04'][2]['display'] = "LAPANGAN";
        
        
        $data['default']['f05'][1]['value'] = "P";
        $data['default']['f05'][1]['display'] = "PRESENCE";
        $data['default']['f05'][1]['checked'] = "CHECKED";
        $data['default']['f05'][2]['value'] = "A";
        $data['default']['f05'][2]['display'] = "ABSENCE";

        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));
        
        $idmenu                    = "177";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt21/home', $data);
    }

   
    function presencedata($group,$type, $f, $u) {
        $dfrom = date('Y-m-d', strtotime($f));
        $duntil = date('Y-m-d', strtotime($u));

        $result = $this->report->report_actual($dfrom, $duntil, $group,$type);
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

    function iframedata($group,$type, $from, $until) {
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $data['url'] = site_url('rpt21/home/reportdata/' . $group . '/' .$type.'/'. $dfrom . '/' . $duntil);
        $this->load->view('rpt21/iframe', $data);
    }

    function reportdata($group,$type, $from, $until) {
        $resuldata = $this->report->report_actual($from, $until, $group,$type);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {            
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['view'] = ($type == 'P') ? 'PRESENCE' : 'ABSENCE';
            $data['jobgroup'] = ($group == 'ST') ? 'STAFF' : 'LAPANGAN';
            $data['resultdata'] = $resuldata;
            $data['type'] = $type;
            $data['url_excel'] = site_url('rpt21/home/excel' . '/' . $group .'/'.$type. '/' . $from . '/' . $until);

            $this->load->view('rpt21/report', $data);
        }
    }

    function excel($group,$type,$fromdate, $untildate) {
        $result = $this->report->report_actual($fromdate, $untildate, $group,$type);
        $ng = ($group == 'ST') ? 'STAFF' : 'LAPANGAN';
        $view = ($type == 'P') ? 'PRESENCE' : 'ABSENCE';
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

        $objSheet->getStyle('A1:J9')->getFont()->setBold(true)->setSize(10);
        
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('ACTUAL PRESENCE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
        $objSheet->getCell('A5')->setValue('JOB GROUP');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($ng);
        $objSheet->getCell('A6')->setValue('DATA');
        $objSheet->getCell('B6')->setValue(':');
        $objSheet->getCell('C6')->setValue($view);
        
        $objSheet->getCell('F8')->setValue('TYPE LEAVE');
        $objSheet->getCell('A9')->setValue('ID');
        $objSheet->getCell('B9')->setValue('ID EMPLOYEE');
        $objSheet->getCell('C9')->setValue('FULLNAME');
        $objSheet->getCell('D9')->setValue('DATE');
        $objSheet->getCell('E9')->setValue('DAY OF WEEK');
        $objSheet->getCell('F9')->setValue('ACTUAL IN');
        $objSheet->getCell('G9')->setValue('ACTUAL OUT');
        $objSheet->getCell('H9')->setValue('P');
        $objSheet->getCell('I9')->setValue('A');
        $objSheet->getCell('J9')->setValue('NOTE');

        $i = 9;
        $n = $m = 0;
        $lastemp_IDEmployee='';
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

        $P_Count = $A_Count= 0;

        if ($result != NULL) {
            foreach ($result as $row) {
             
                if($lastemp_IDEmployee != $row['IDEmployee'] && $n>0){
                    $i++;
                    $objSheet->getCell('G' . $i)->setValue('TOTAL');
                    $objSheet->getCell('H' . $i)->setValue($P_Count);
                    $objSheet->getCell('I' . $i)->setValue($A_Count);
                    $A_Count = $P_Count = 0;
                }
                
                $n++;
                $i++;
                $data['A'] = $data['P']= '';
                $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                $data['DayOfWeek'] = $day[date('w', strtotime($row['PresenceDate']))];
                $data['Note'] = $row['rDescription'];
                $data['IDEmployee'] = $row['IDEmployee'];
                $data['FullName'] = $row['FullName'];

                if ($type == 'A') {
                    $A_Count+= 1;
                    $data['A'] = 1;
                } elseif ($type == 'P') {
                    $P_Count+= 1;
                    $data['P'] = 1;
                } 
                
                $lastemp_IDEmployee= $row['IDEmployee'];
                
                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue("'".$data['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($data['FullName']);
                $objSheet->getCell('D' . $i)->setValue($data['PresenceDate']);
                $objSheet->getCell('E' . $i)->setValue($data['DayOfWeek']);
                $objSheet->getCell('F' . $i)->setValue($row['ActualIn']);
                $objSheet->getCell('G' . $i)->setValue($row['ActualOut']);
                $objSheet->getCell('H' . $i)->setValue($data['P']);
                $objSheet->getCell('I' . $i)->setValue($data['A']);
                $objSheet->getCell('J' . $i)->setValue($data['Note']);
                
                
            }
            $i++;
            $objSheet->getStyle('A'.$i.':J'.$i)->getFont()->setBold(true)->setSize(10);
            $objSheet->getCell('A' . $i)->setValue();
            $objSheet->getCell('B' . $i)->setValue();
            $objSheet->getCell('C' . $i)->setValue();
            $objSheet->getCell('D' . $i)->setValue();
            $objSheet->getCell('E' . $i)->setValue();
            $objSheet->getCell('F' . $i)->setValue();
            $objSheet->getCell('G' . $i)->setValue('TOTAL');
            $objSheet->getCell('H' . $i)->setValue($P_Count);
            $objSheet->getCell('I' . $i)->setValue($A_Count);
            $objSheet->getCell('J' . $i)->setValue();
        }
        $objSheet->getStyle('A8:J' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:J' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:J' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
	ob_end_clean();
        $objWriter->save($path_file . "actualpresence" . $ext);
        $data = file_get_contents($path_file . "actualpresence" . $ext);
        force_download("actualpresence"."-".date('d-m-Y h:i:s') . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */





