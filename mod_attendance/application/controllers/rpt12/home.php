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
        $data['default']['f01'] = date('d-m-Y');   
       
	$query = $this->employee->get_rjob_standar()->result();
	$i = 0;
	foreach ($query as $r) {
	    $i++;
	    $data['default']['f03'][$i]['value'] = $r->IDJobGroup;
	    $data['default']['f03'][$i]['display'] = $r->GroupName;   
	}
        
       
        $data['default']['group4'] = 'Kapuk';
        $data['default']['group5'] = 'Bitung';        
        $idmenu                    = "107";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt12/home', $data);
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

    function presencedata($site,$group, $date, $name = '') {
        $dfrom = date('Y-m-d', strtotime($date));
        
        $result = $this->report->report_dailypresence($dfrom, $name, $group,$site);
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
            $json = '{ "mesg":"' . 'Sorry no result data on date ' . $dfrom . '",                                   
                      "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        }
    }

    function iframedata($site,$group, $date, $name='') {
        $dfrom = date('Y-m-d', strtotime($date));      
        $data['url'] = site_url('rpt12/home/reportdata/'.$site.'/'. $group . '/' . $dfrom . '/' . $name);
        $this->load->view('rpt12/iframe', $data);
    }

    function reportdata($site,$group, $date, $name='') {
        $resuldata = $this->report->report_dailypresence($date,$name, $group,$site);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {            
            $ng = $this->libfun->get_name_group($group);   
            $data['nowdate'] = $date;          
            $data['jobgroup'] = $ng;
            $data['fulname'] = $name;
            $data['resultdata'] = $resuldata;
            $data['url_excel'] = site_url('rpt12/home/excel'. '/' .$site.'/'. $group . '/' . $date .'/' . $name);

            $this->load->view('rpt12/report', $data);
        }
    }

    function excel($site,$group, $date, $nip) {        
        $result = $this->report->report_dailypresence($date,$nip, $group,$site);
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
        $objSheet->setTitle('detail presence');

        $objSheet->getStyle('A1:V9')->getFont()->setBold(true)->setSize(10);
                
        
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('PRESENCE ONE DAY REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($date)));            
        $objSheet->getCell('F8')->setValue('ACTUAL');
        $objSheet->getCell('H8')->setValue('MANUAL');
        $objSheet->getCell('L8')->setValue('TYPE PRESENCE');
        $objSheet->getCell('A9')->setValue('ID');
        $objSheet->getCell('B9')->setValue('NIP');
        $objSheet->getCell('C9')->setValue('NAME');
        $objSheet->getCell('D9')->setValue('DATE');
        $objSheet->getCell('E9')->setValue('DAY OF WEEK');
        $objSheet->getCell('F9')->setValue('TIME IN');
        $objSheet->getCell('G9')->setValue('TIME OUT');
        $objSheet->getCell('H9')->setValue('TIME IN');
        $objSheet->getCell('I9')->setValue('TIME OUT');
        $objSheet->getCell('J9')->setValue('MAN HOUR');        
        $objSheet->getCell('K9')->setValue('LATE HOUR');      
        $objSheet->getCell('L9')->setValue('P');
        $objSheet->getCell('M9')->setValue('PLW');
        $objSheet->getCell('N9')->setValue('A');
	$objSheet->getCell('O9')->setValue('SP');	
        $objSheet->getCell('P9')->setValue('SN');
        $objSheet->getCell('Q9')->setValue('L');
        $objSheet->getCell('R9')->setValue('LP');
        $objSheet->getCell('S9')->setValue('OT');
        $objSheet->getCell('T9')->setValue('NC');
        $objSheet->getCell('U9')->setValue('ALD');
        $objSheet->getCell('V9')->setValue('NOTE');

        $i = 9;
        $n = $m = 0;
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

        $ActualHour_Sum = $LateHour_Sum  =0;
        $P_Count = $A_Count = $SP_Count = $SN_Count = $L_Count = $LP_Count = $OT_Count = $NC_Count = $ALD_Count = $PLW_Count= 0;

        if ($cekdata !=='empty') {
            foreach ($result as $row) {
                $n++;
                $i++;
                $data['P'] = $data['A'] = $data['SP']= $data['SN'] = $data['L'] = $data['LP'] = $data['OT'] = $data['NC'] =  $data['ALD'] = $data['PLW'] = '';
                $data['IDEmployee'] = $row['IDEmployee'];
                $data['FullName'] = $row['FullName'];
                $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));               
                $data['DayOfWeek'] = $day[$row['DayOfWeek']];
                $data['ActualIn'] = substr($row['ActualIn'], 11, 5);
                $data['ActualOut'] = substr($row['ActualOut'], 11, 5);
                $data['ManualIn'] = substr($row['ManualIn'], 11, 5);
                $data['ManualOut'] = substr($row['ManualOut'], 11, 5);
                $data['ActualHour'] = $row['ActualHour'];
                $ActualHour_Sum+= $data['ActualHour'];                
                $data['LateHour'] = $row['LateHour'];
                $LateHour_Sum+= $data['LateHour'];               

                $data['Note'] =  $row['rDescription'].' '.$row['Note'];

                if (substr($row['Description'], -1, 1) == 'P') {
                    $P_Count+= 1;
                    $data['P'] = 1;
                    if($row['Description']=='P')$data['Note'] = '';
                }  elseif ($row['Description'] == 'A') {
                    $A_Count+= 1;
                    $data['A'] = 1;
                } elseif ($row['Description'] == 'PLW') {
                    //$P_Count+= 1;
                    //$data['P'] = 1;
                    $PLW_Count+= 1;
                    $data['PLW'] = 1;                  
                }  elseif ($row['Description'] == 'SP') {
                    $SP_Count+= 1;
                    $data['SP'] = 1;
		    $data['Note'] = 'SKORSING';	
                } elseif (substr($row['Description'], -2, 2) == 'SN') {
                    $SN_Count+= 1;
                    $data['SN'] = 1;
                } elseif (substr($row['Description'], -1, 1) == 'L') {
                    $L_Count+= 1;
                    $data['L'] = 1;
                } elseif ($row['Description'] == 'LP') {
                    $LP_Count+= 1;
                    $data['LP'] = 1;
                } elseif ($row['Description'] == 'OT') {
                    $OT_Count+= 1;
                    $data['OT'] = 1;
                } elseif ($row['Description'] == 'NC') {
                    $NC_Count+= 1;
                    $data['NC'] = 1;
                    $P_Count+= 1;
                    $data['P'] = 1;
                 }else if($row['Description'] =='ALD'){                                   
                                if($row['IDJobGroup'] =='ST' and ($row['ActualIn'] !==null and $row['ActualOut']!==null )){
                                   $P_Count+= 1;
                                   $ALD_Count+= '';
                                   $data['P'] = 1; 
                                   $data['ALD'] ='';     
                                   $data['Note'] = '';              
                                }else if($row['IDJobGroup'] !=='ST' and ($row['ActualIn'] !==null and $row['ActualOut']!==null )){
                                   $P_Count+= 1;
                                   $ALD_Count+= '';
                                  $data['P'] = 1;
                                   $data['ALD'] ='';    
                                   $data['Note'] = '';                        
                                }else if($row['IDJobGroup'] =='ST'){
                                   $A_Count += '';
                                   $ALD_Count+= 1;
                                   $data['A'] = '';
                                   $data['ALD'] =1;    
                                   $data['Note'] = 'ANNUAL LEAVE DEDUCTION';      
                                }else{
                                   $A_Count+= 1;
                                   $ALD_Count+= 1;
                                   $data['A'] = 1; 
                                   $data['ALD'] =1; 
                                   $data['Note'] = 'ANNUAL LEAVE DEDUCTION';      
                                } 
                   }

                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue("'".$data['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($data['FullName']);
                $objSheet->getCell('D' . $i)->setValue($data['PresenceDate']);
                $objSheet->getCell('E' . $i)->setValue($data['DayOfWeek']);
                $objSheet->getCell('F' . $i)->setValue($data['ActualIn']);
                $objSheet->getCell('G' . $i)->setValue($data['ActualOut']);
                $objSheet->getCell('H' . $i)->setValue($data['ManualIn']);
                $objSheet->getCell('I' . $i)->setValue($data['ManualOut']);
                $objSheet->getCell('J' . $i)->setValue($data['ActualHour']);                
                $objSheet->getCell('K' . $i)->setValue($data['LateHour']);              
                $objSheet->getCell('L' . $i)->setValue($data['P']);
                $objSheet->getCell('M' . $i)->setValue($data['PLW']);
                $objSheet->getCell('N' . $i)->setValue($data['A']);
		$objSheet->getCell('O' . $i)->setValue($data['SP']);
                $objSheet->getCell('P' . $i)->setValue($data['SN']);
                $objSheet->getCell('Q' . $i)->setValue($data['L']);
                $objSheet->getCell('R' . $i)->setValue($data['LP']);
                $objSheet->getCell('S' . $i)->setValue($data['OT']);
                $objSheet->getCell('T' . $i)->setValue($data['NC']);
                $objSheet->getCell('U' . $i)->setValue($data['ALD']);
                $objSheet->getCell('V' . $i)->setValue($data['Note']);
            }
            $i++;
            $objSheet->getStyle('A'.$i.':V'.$i)->getFont()->setBold(true)->setSize(10);
            $objSheet->getCell('A' . $i)->setValue();
            $objSheet->getCell('B' . $i)->setValue();
            $objSheet->getCell('C' . $i)->setValue();
            $objSheet->getCell('D' . $i)->setValue();
            $objSheet->getCell('E' . $i)->setValue();
            $objSheet->getCell('F' . $i)->setValue();
            $objSheet->getCell('G' . $i)->setValue();
            $objSheet->getCell('H' . $i)->setValue();            
            $objSheet->getCell('J' . $i)->setValue();           
            $objSheet->getCell('K' . $i)->setValue();
            $objSheet->getCell('L' . $i)->setValue();
            $objSheet->getCell('M' . $i)->setValue();
            $objSheet->getCell('N' . $i)->setValue();
            $objSheet->getCell('O' . $i)->setValue();
            $objSheet->getCell('P' . $i)->setValue();
            $objSheet->getCell('Q' . $i)->setValue();
            $objSheet->getCell('R' . $i)->setValue();
            $objSheet->getCell('S' . $i)->setValue();
            $objSheet->getCell('T' . $i)->setValue();
	    $objSheet->getCell('U' . $i)->setValue();	
	    $objSheet->getCell('V' . $i)->setValue();	
        }
        $objSheet->getStyle('A8:V' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:V' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:V' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
	ob_end_clean();
        $objWriter->save($path_file . "presenceoneday-".$group. $ext);
        $data = file_get_contents($path_file . "presenceoneday-".$group. $ext);
        force_download("presenceoneday-".$group. $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */




