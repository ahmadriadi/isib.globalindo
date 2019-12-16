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
        
        $idmenu                    = "109";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt14/home', $data);
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

        $result = $this->report->report_sum_absen($dfrom, $duntil, $group, $name);
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
        $data['url'] = site_url('rpt14/home/reportdata/' . $group . '/' . $dfrom . '/' . $duntil . '/' . $name);
        $this->load->view('rpt14/iframe', $data);
    }

    function reportdata($group, $from, $until, $name = '') {
        $resuldata = $this->report->report_sum_absen($from, $until, $group, $name);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['jobgroup'] = 'STAFF';
            $data['fulname'] = $name;
            $data['resultdata'] = $resuldata;
            $data['url_excel'] = site_url('rpt14/home/excel' . '/' . $group . '/' . $from . '/' . $until . '/' . $name);
            $this->load->view('rpt14/report', $data);
        }
    }

    function excel($group, $fromdate, $untildate, $nip) {
        //error_reporting(0);      
        $result = $this->report->report_sum_absen($fromdate, $untildate, $group, $nip);
        $cekdata = ($result !== null) ? $result : 'empty';
        
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
        $objSheet->setTitle('absence staff');

        $objSheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(10);
        $objSheet->getCell('A1')->setValue('No');
        $objSheet->getCell('B1')->setValue('NIP');
        $objSheet->getCell('C1')->setValue('Name');
        $objSheet->getCell('D1')->setValue('A');
        $objSheet->getCell('E1')->setValue('SP');
        $objSheet->getCell('F1')->setValue('OL');
        $objSheet->getCell('G1')->setValue('SN');
        $objSheet->getCell('H1')->setValue('ALD');

	 $style = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '#FF4500'),
                'size' => 10,
                'name' => 'Calibri'
        ));

        $i = 0;
        $n = 0;
        $counter = 1;
        $A_Count = $SP_Count = $OL_Count = $SN_Count = $ALD_Count = 0;
        $Lastemp_IDEmployee = '';
        $lastemp_FullName = '';
	$lastemp_unit = '';	
        if ($cekdata !== 'empty') {
            foreach ($result as $row) {

		$ain = $row['ActualIn'];
                $aout = $row['ActualOut'];   
		$min = $row['ManualIn'];
                $mout = $row['ManualOut']; 

                $actualhour = (strtotime($aout) - strtotime($ain)) / 3600;  

                if ($row['Description'] != 'A' && $row['Description'] != 'P' && $row['Description'] != 'SP' && $row['Description'] != 'OL' && $row['Description'] != 'SN' && $row['Description'] != 'ALD')
                    continue;
                if ($Lastemp_IDEmployee != $row['IDEmployee'] AND $lastemp_FullName != $row['FullName'] && $n > 0) {
                    $counter++;
                    $i++;

                    if($lastemp_unit =='SECURITY'){  
                        $objSheet->getStyle('A' . $counter . ':H' . $counter)->applyFromArray($style);
                        $objSheet->getCell('A' . $counter)->setValue($i);
                        $objSheet->getCell('B' . $counter)->setValue("'" . $Lastemp_IDEmployee);
                        $objSheet->getCell('C' . $counter)->setValue($lastemp_FullName);
                        $objSheet->getCell('D' . $counter)->setValue($A_Count.' (Confirm to HRD)');
                        $objSheet->getCell('E' . $counter)->setValue($SP_Count);
                        $objSheet->getCell('F' . $counter)->setValue($OL_Count);
                        $objSheet->getCell('G' . $counter)->setValue($SN_Count);
                        $objSheet->getCell('H' . $counter)->setValue($ALD_Count);
                        $A_Count = $SP_Count = $OL_Count = $SN_Count = $ALD_Count = 0;                         
                        
                    }else{        
                        
                        $objSheet->getCell('A' . $counter)->setValue($i);
                        $objSheet->getCell('B' . $counter)->setValue("'" . $Lastemp_IDEmployee);
                        $objSheet->getCell('C' . $counter)->setValue($lastemp_FullName);
                        $objSheet->getCell('D' . $counter)->setValue($A_Count);
                        $objSheet->getCell('E' . $counter)->setValue($SP_Count);
                        $objSheet->getCell('F' . $counter)->setValue($OL_Count);
                        $objSheet->getCell('G' . $counter)->setValue($SN_Count);
                        $objSheet->getCell('H' . $counter)->setValue($ALD_Count);
                        $A_Count = $SP_Count = $OL_Count = $SN_Count = $ALD_Count = 0; 
                    }
                }

                $n++;
                if ($row['Description'] == 'A') {
                    $A_Count+= 1;
                } else if ($row['Description'] == 'SP') {
                    $SP_Count+= 1;
                } else if ($row['Description'] == 'OL') {
                    $OL_Count+= 1;
                } else if ($row['Description'] == 'SN') {
                    $SN_Count+= 1;
                } elseif ($row['Description'] == 'ALD') {
                    if ($row['IDJobGroup'] == 'ST' and ($row['ActualIn'] !== null and $row['ActualOut'] !== null )) {
                        $ALD_Count+= '';
                    } else {
                        $ALD_Count+= 1;
                    }
                } else if($row['Description'] == 'P'){
                    if ($actualhour <= 4 and is_null($min) and is_null($mout)) {                           
                              $A_Count+= 1;
                              
                   }                   
                }

                $Lastemp_IDEmployee = $row['IDEmployee'];
                $lastemp_FullName = $row['FullName'];
		$lastemp_unit = $row['IDUnitGroup'];
            }
            $counter++;
            $objSheet->getStyle('A1' . $counter . ':D1' . $counter)->getFont()->setBold(true)->setSize(10);
            $objSheet->getCell('A' . $counter)->setValue($i + 1);
            $objSheet->getCell('B' . $counter)->setValue("'" . $Lastemp_IDEmployee);
            $objSheet->getCell('C' . $counter)->setValue($lastemp_FullName);
            $objSheet->getCell('D' . $counter)->setValue($A_Count);
            $objSheet->getCell('E' . $counter)->setValue($SP_Count);
            $objSheet->getCell('F' . $counter)->setValue($OL_Count);
            $objSheet->getCell('G' . $counter)->setValue($SN_Count);
            $objSheet->getCell('H' . $counter)->setValue($ALD_Count);
        }
        $objSheet->getStyle('A1:H' . $counter)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A1:H' . $counter)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A1:H' . $counter)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }

        ob_end_clean();
        $objWriter->save($path_file . "summary_absence_staff" . $ext);
        $data = file_get_contents($path_file . "summary_absence_staff" . $ext);
        force_download("summary_absence_staff" . "-" . date('d-m-Y h:i') . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */




