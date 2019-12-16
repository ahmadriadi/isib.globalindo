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
        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));
        
        
        $idmenu                    = "100";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt05/home', $data);
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
        $result = $this->report->report_leave($dfrom, $duntil,$nip);
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
        $data['url'] = site_url('rpt05/home/reportdata/' . $dfrom . '/' . $duntil . '/' . $nip);
        $this->load->view('rpt05/iframe', $data);
    }

    function reportdata($from,$until,$nip='') {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $resuldata = $this->report->report_leave($from,$until,$nip);       
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['url_excel'] =  site_url('rpt05/home/excel')."/".$from.'/'.$until.'/'.$nip;
            $data['resultdata'] = $resuldata;
            $this->load->view('rpt05/report', $data);
        }
    }

    function excel($fromdate,$untildate,$nip) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $result = $this->report->report_leave($fromdate,$untildate,$nip);         
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
        $objSheet->setTitle('leave');

        $objSheet->getStyle('A1:Q9')->getFont()->setBold(true)->setSize(10);
       
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('LEAVE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
                
        $objSheet->getCell('F8')->setValue('TYPE LEAVE');
        $objSheet->getCell('A9')->setValue('ID');
        $objSheet->getCell('B9')->setValue('ID EMPLOYEE');
        $objSheet->getCell('C9')->setValue('FULLNAME');
        $objSheet->getCell('D9')->setValue('DATE');
        $objSheet->getCell('E9')->setValue('DAY OF WEEK');
        $objSheet->getCell('F9')->setValue('AL');
        $objSheet->getCell('G9')->setValue('MRL');
        $objSheet->getCell('H9')->setValue('MTL');
        $objSheet->getCell('I9')->setValue('CL');
        $objSheet->getCell('J9')->setValue('SL');
        $objSheet->getCell('K9')->setValue('OL');
        $objSheet->getCell('L9')->setValue('NOTE');

        $i = 9;
        $n = $m = 0;
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

        $AL_Count = $MRL_Count = $MTL_Count = $CL_Count = $SL_Count = $OL_Count = 0;

        if ($result != NULL) {
            foreach ($result as $row) {
                if (substr($row['Description'], -1, 1) != 'L') continue; 
                $n++;
                $i++;
                $data['AL'] = $data['MRL'] = $data['MTL'] = $data['CL'] = $data['SL'] = $data['OL'] = '';
                $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                $data['DayOfWeek'] = $day[$row['DayOfWeek']];
                $data['Note'] = $row['rDescription'];
                $data['IDEmployee'] = $row['IDEmployee'];
                $data['FullName'] = $row['FullName'];

                if ($row['Description'] == 'AL') {
                    $AL_Count+= 1;
                    $data['AL'] = 1;
                } elseif ($row['Description'] == 'MRL') {
                    $MRL_Count+= 1;
                    $data['MRL'] = 1;
                } elseif (substr($row['Description'], -2, 2) == 'MTL') {
                    $MTL_Count+= 1;
                    $data['MTL'] = 1;
                } elseif (substr($row['Description'], -1, 1) == 'CL') {
                    $CL_Count+= 1;
                    $data['CL'] = 1;
                } elseif ($row['Description'] == 'SL') {
                    $SL_Count+= 1;
                    $data['SL'] = 1;
                } elseif ($row['Description'] == 'OL') {
                    $OL_Count+= 1;
                    $data['OL'] = 1;
                } 

                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue("'".$data['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($data['FullName']);
                $objSheet->getCell('D' . $i)->setValue($data['PresenceDate']);
                $objSheet->getCell('E' . $i)->setValue($data['DayOfWeek']);
                $objSheet->getCell('F' . $i)->setValue($data['AL']);
                $objSheet->getCell('G' . $i)->setValue($data['MRL']);
                $objSheet->getCell('H' . $i)->setValue($data['MTL']);
                $objSheet->getCell('I' . $i)->setValue($data['CL']);
                $objSheet->getCell('J' . $i)->setValue($data['SL']);
                $objSheet->getCell('K' . $i)->setValue($data['OL']);
                $objSheet->getCell('L' . $i)->setValue($data['Note']);
            }
            $i++;
            $objSheet->getStyle('A'.$i.':L'.$i)->getFont()->setBold(true)->setSize(10);
            $objSheet->getCell('A' . $i)->setValue();
            $objSheet->getCell('B' . $i)->setValue();
            $objSheet->getCell('C' . $i)->setValue();
            $objSheet->getCell('D' . $i)->setValue();
            $objSheet->getCell('E' . $i)->setValue('TOTAL');
            $objSheet->getCell('F' . $i)->setValue($AL_Count);
            $objSheet->getCell('G' . $i)->setValue($MRL_Count);
            $objSheet->getCell('H' . $i)->setValue($MTL_Count);
            $objSheet->getCell('I' . $i)->setValue($CL_Count);
            $objSheet->getCell('J' . $i)->setValue($SL_Count);
            $objSheet->getCell('K' . $i)->setValue($OL_Count);
            $objSheet->getCell('L' . $i)->setValue();
        }
        $objSheet->getStyle('A8:L' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:L' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:L' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
	ob_end_clean();
        $objWriter->save($path_file . "leave" . $ext);
        $data = file_get_contents($path_file . "leave" . $ext);
        force_download("leave" . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */



