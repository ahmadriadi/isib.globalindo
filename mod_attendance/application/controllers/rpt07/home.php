<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report_model', 'report');
        $this->load->model('employee_model', 'employee');
        $this->load->model('libraryfunction_model', 'libfun');
        $this->load->model('uac_model', 'uac');
        $this->User = $this->session->userdata('sess_userid');
    }

    function index() {
        $date = $this->libfun->periode_work();
        $data['default']['f01'] = substr($date, 0, 10);
        $data['default']['f02'] = substr($date, 11, 10);

        $query = $this->employee->get_rjob()->result();
	$i = 0;
	foreach ($query as $r) {
	    $i++;
	    $data['default']['f04'][$i]['value'] = $r->IDJobGroup;
	    $data['default']['f04'][$i]['display'] = $r->GroupName;   
	}

        $data['default']['f05'][0]['value'] = "All";
        $data['default']['f05'][0]['display'] = "ALL EMPLOYEE";
        $data['default']['f05'][0]['checked'] = "CHECKED";
        $data['default']['f05'][1]['value'] = "Personal";
        $data['default']['f05'][1]['display'] = "PERSONAL";
        $data['default']['f05'][2]['value'] = "Office";
        $data['default']['f05'][2]['display'] = "OFFICE";


        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));

        $idmenu                    = "102";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt07/home', $data);
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

    function presencedata($f, $u, $group, $necessity,$nip='') {
        $dfrom = date('Y-m-d', strtotime($f));
        $duntil = date('Y-m-d', strtotime($u));

        $result = $this->report->report_leavepermit($nip, $dfrom, $duntil, $group, $necessity);
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

    function iframedata($f, $u, $group, $necessity,$nip='') {
        $dfrom = date('Y-m-d', strtotime($f));
        $duntil = date('Y-m-d', strtotime($u));
        $data['url'] = site_url('rpt07/home/reportdata/' . $dfrom . '/' . $duntil . '/' . $group . '/' . $necessity . '/' . $nip);
        $this->load->view('rpt07/iframe', $data);
    }

    function reportdata($from, $until, $group, $necessity, $nip='') {
        $resuldata = $this->report->report_leavepermit($nip, $from, $until, $group, $necessity);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {

            $gname = $this->libfun->get_name_group($group);

            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['jobgroup'] = $gname;
            $data['fulname'] = $nip;
            $data['resultdata'] = $resuldata;
            $data['url_excel'] = site_url('rpt07/home/excel' . '/' . $from . '/' . $until . '/' . $group . '/' . $necessity . '/' . $nip);

            $this->load->view('rpt07/report', $data);
        }
    }

    function excel($fromdate, $untildate, $group, $necessity, $nip='') {
        $result = $this->report->report_leavepermit($nip, $fromdate, $untildate, $group, $necessity);
        $gname = $this->libfun->get_name_group($group);
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

        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('LEAVEPERMIT REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
        $objSheet->getCell('A5')->setValue('JOB GROUP');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($gname);

        $objSheet->getCell('F8')->setValue('IMK');
        $objSheet->getCell('A9')->setValue('ID');
        $objSheet->getCell('B9')->setValue('ID EMPLOYEE');
        $objSheet->getCell('C9')->setValue('FULLNAME');
        $objSheet->getCell('D9')->setValue('DATE');
        $objSheet->getCell('E9')->setValue('DAY OF WEEK');
        $objSheet->getCell('F9')->setValue('Personal');
        $objSheet->getCell('G9')->setValue('Office');
        $objSheet->getCell('H9')->setValue('Out');
        $objSheet->getCell('I9')->setValue('In');
        $objSheet->getCell('J9')->setValue('Sum Hour');
        $objSheet->getCell('K9')->setValue('Note');

        $i = 9;
        $n = $m = 0;
        $lastemp_IDEmployee = '';
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

        $pesonal = $office = 0;

        if ($result != NULL) {
            foreach ($result as $row) {
                if ($row['Necessity'] != '1' && $row['Necessity'] != '2')
                    continue;
                if ($lastemp_IDEmployee != $row['IDEmployee'] && $n > 0) {
                    $i++;
                    $objSheet->getCell('E' . $i)->setValue('TOTAL');
                    $objSheet->getCell('F' . $i)->setValue($pesonal);
                    $objSheet->getCell('G' . $i)->setValue($office);
                    $pesonal = $office = 0;
                }

                $n++;
                $i++;

                $data['personal'] = $data['office'] = $data['out'] = $data['in'] = $data['sumhour'] = '';
                $data['date'] = $row['LPDate'];
                $data['day'] = $day[date('w', strtotime($row['LeavePermitDate']))];
                $data['note'] = $row['Note'];
                $data['name'] = $row['FullName'];
                $data['nip'] = $row['IDEmployee'];

                $out = (date('H:i', strtotime($row['OutDate'])) == '00:00') ? '-' : date('H:i', strtotime($row['OutDate']));
                $in = (date('H:i', strtotime($row['InDate'])) == '00:00') ? '-' : date('H:i', strtotime($row['InDate']));

                $data['out'] = $out;
                $data['in'] = $in;
                $hour = $this->decimaltominutes($row['IMKHour']);
                $imkhour = ($hour == '-10000:00') ? '-' : $hour;
                $data['sumhour'] = $imkhour;



                if ($row['Necessity'] == '1') {
                    $pesonal+= 1;
                    $data['personal'] = 1;
                } elseif ($row['Necessity'] == '2') {
                    $office+= 1;
                    $data['office'] = 1;
                }

                $lastemp_IDEmployee = $data['nip'];

                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue("'" . $data['nip']);
                $objSheet->getCell('C' . $i)->setValue($data['name']);
                $objSheet->getCell('D' . $i)->setValue($data['date']);
                $objSheet->getCell('E' . $i)->setValue($data['day']);
                $objSheet->getCell('F' . $i)->setValue($data['personal']);
                $objSheet->getCell('G' . $i)->setValue($data['office']);
                $objSheet->getCell('H' . $i)->setValue($data['out']);
                $objSheet->getCell('I' . $i)->setValue($data['in']);
                $objSheet->getCell('J' . $i)->setValue($data['sumhour']);
                $objSheet->getCell('K' . $i)->setValue($data['note']);
            }
            $i++;
            $objSheet->getStyle('A' . $i . ':I' . $i)->getFont()->setBold(true)->setSize(10);
            $objSheet->getCell('A' . $i)->setValue();
            $objSheet->getCell('B' . $i)->setValue();
            $objSheet->getCell('C' . $i)->setValue();
            $objSheet->getCell('D' . $i)->setValue();
            $objSheet->getCell('E' . $i)->setValue('TOTAL');
            $objSheet->getCell('F' . $i)->setValue($pesonal);
            $objSheet->getCell('G' . $i)->setValue($office);
            $objSheet->getCell('H' . $i)->setValue();
            $objSheet->getCell('I' . $i)->setValue();
            $objSheet->getCell('K' . $i)->setValue();
        }
        $objSheet->getStyle('A8:K' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:K' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:K' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
        ob_end_clean();
        $objWriter->save($path_file . "leavepermit" . $ext);
        $data = file_get_contents($path_file . "leavepermit" . $ext);
        force_download("leavepermit" . "-" . date('d-m-Y h:i:s') . $ext, $data);
    }

    function decimaltominutes($dec) {
        // start by converting to seconds
        $seconds = $dec * 3600;
        // we're given hours, so let's get those the easy way
        $hours = floor($dec);
        // since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= $hours * 3600;
        // calculate minutes left
        $minutes = floor($seconds / 60);
        // remove those from seconds as well
        $seconds -= $minutes * 60;
        // return the time formatted HH:MM:SS
        return $this->lz($hours) . ":" . $this->lz($minutes);
    }

    function lz($num) {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */




