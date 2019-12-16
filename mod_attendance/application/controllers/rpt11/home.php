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


        $data['default']['f03'][1]['value'] = "ST";
        $data['default']['f03'][1]['display'] = "STAFF";
        $data['default']['f03'][1]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value'] = "LAP";
        $data['default']['f03'][2]['display'] = "LAPANGAN";

        $idmenu = "106";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('rpt11/home', $data);
    }

    function presencedata($from, $until, $group) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $result = $this->report->report_turnover($dfrom, $duntil, $group);
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
            $json = '{ "mesg":"' . 'Sorry no result data job : ' . $group . 'on periode ' . $dfrom . " to " . $duntil . '",                                   
                      "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        }
    }

    function iframedata($from, $until, $group) {
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $data['url'] = site_url('rpt11/home/reportdata/' . $dfrom . '/' . $duntil . '/' . $group);
        $this->load->view('rpt11/iframe', $data);
    }

    function reportdata($from, $until, $group) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $resuldata = $this->report->report_turnover($from, $until, $group);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['jobgroup'] = ($group == 'ST') ? 'STAFF' : 'LAPANGAN';
            $data['url_excel'] = site_url('rpt11/home/excel') . "/" . $from . '/' . $until . '/' . $group;
            $data['resultdata'] = $resuldata;
            $this->load->view('rpt11/report', $data);
        }
    }

    function excel($fromdate, $untildate, $group) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel

        $gjob = ($group == 'ST') ? 'STAFF' : 'LAPANGAN';
        $result = $this->report->report_turnover($fromdate, $untildate, $group);
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
        $objSheet->setTitle('turnover employee');

        $objSheet->getStyle('A1:H6')->getFont()->setBold(true)->setSize(10);


        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('TURNOVER EMPLOYEE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
        $objSheet->getCell('A5')->setValue('GROUP JOB');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($gjob);

        $objSheet->getCell('A6')->setValue('NO');
        $objSheet->getCell('B6')->setValue('ID EMPLOYEE');
        $objSheet->getCell('C6')->setValue('FULLNAME');
        $objSheet->getCell('D6')->setValue('ID JOB GROUP');
        $objSheet->getCell('E6')->setValue('UNIT GROUP');
        $objSheet->getCell('F6')->setValue('HIRE DATE');
        $objSheet->getCell('G6')->setValue('RESIGN DATE');
        $objSheet->getCell('H6')->setValue('INTERVAL DAYS');


        $i = 6;
        $n = $m = 0;
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

        if ($result != NULL) {
            foreach ($result as $row) {

                $n++;
                $i++;
                $data['IDEmployee'] = $row['IDEmployee'];
                $data['FullName'] = $row['FullName'];
                if ($row['HireDate'] == NULL) {
                    $data['HireDate'] = '';
                } else {
                    $data['HireDate'] = date('d-m-Y', strtotime($row['HireDate']));
                }
                if ($row['ResignDate'] == NULL) {
                    $data['ResignDate'] = '';
                } else {
                    $data['ResignDate'] = date('d-m-Y', strtotime($row['ResignDate']));
                }

                $data['Priv'] = $row['Priv']+1;
                $data['IDJobGroup'] = $row['IDJobGroup'];
                $data['IDUnitGroup'] = $row['IDUnitGroup'];

                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue("'" . $data['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($data['FullName']);
                $objSheet->getCell('D' . $i)->setValue($this->libfun->get_name_group($row['IDJobGroup']));
                $objSheet->getCell('E' . $i)->setValue($data['IDUnitGroup']);
                $objSheet->getCell('F' . $i)->setValue($data['HireDate']);
                $objSheet->getCell('G' . $i)->setValue($data['ResignDate']);
                $objSheet->getCell('H' . $i)->setValue($data['Priv']);


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
        $objWriter->save($path_file . "turnover_employee" . $ext);
        $data = file_get_contents($path_file . "turnover_employee" . $ext);
        force_download("turnover_employee" . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */





