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
        
        $idmenu                    = "97";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt02/home', $data);
    }

   /*

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

   */


    function autocomplete_employee() {
        $result = $this->employee->findall_employee();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                           'fullname' => strtoupper($row->FullName)
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    } 	


    function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->find_employeee($q);
        $data['response'] = 'true';
        $data['message'] = array();
        foreach ($result->result() as $row) {          
                    $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                        'value' => $row->IDEmployee,
                        'idemployee' => $row->IDEmployee
                    );              
            }
            
           echo json_encode($data);
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

    function presencedata() {
        $dfrom = date('Y-m-d', strtotime($this->input->post('f01')));
        $duntil = date('Y-m-d', strtotime($this->input->post('f02')));
        $nip = $this->input->post('f03');
        $result = $this->report->check_personalpresence($nip, $dfrom, $duntil);
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

    function iframedata($nip, $from, $until) {
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $data['url'] = site_url('rpt02/home/reportdata/' . $nip . '/' . $dfrom . '/' . $duntil);
        $this->load->view('rpt02/iframe', $data);
    }

    function reportdata($nip, $from, $until) {
        $resuldata = $this->report->report_detail($nip, $from, $until)->result_array();
        $rowdata = $this->report->report_detail($nip, $from, $until)->row();
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {
            $data['from'] = $from;
            $data['until'] = $until;
            $data['nip'] = $nip;
            $data['name'] = $rowdata->FullName;           
	    $group = $this->libfun->get_name_group($rowdata->IDJobGroup);

            $data['group'] = $group;
            $data['resultdata'] = $resuldata;
            $this->load->view('rpt02/report', $data);
        }
    }

    function excel($idemployee, $fromdate, $untildate) {
        $resuldata = $this->report->report_detail($idemployee, $fromdate, $untildate)->result_array();
        $rowdata = $this->report->report_detail($idemployee, $fromdate, $untildate)->row();
        $checkdata = ($resuldata == null or $resuldata == '') ? 'empty' : 'exist';
        $group = $this->libfun->get_name_group($rowdata->IDJobGroup);

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

        $objSheet->getStyle('A1:U9')->getFont()->setBold(true)->setSize(10);
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('DETAIL PRESENCE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
        $objSheet->getCell('A5')->setValue('ID EMPLOYEE');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue("'" . $idemployee);
        $objSheet->getCell('A6')->setValue('FULLNAME');
        $objSheet->getCell('B6')->setValue(':');
        $objSheet->getCell('C6')->setValue($rowdata->FullName);
        $objSheet->getCell('A7')->setValue('JOB GROUP');
        $objSheet->getCell('B7')->setValue(':');
        $objSheet->getCell('C7')->setValue($group);
        $objSheet->getCell('D8')->setValue('ACTUAL');
        $objSheet->getCell('F8')->setValue('MANUAL');
        $objSheet->getCell('L8')->setValue('TYPE PRESENCE');
        $objSheet->getCell('A9')->setValue('ID');
        $objSheet->getCell('B9')->setValue('DATE');
        $objSheet->getCell('C9')->setValue('DAY OF WEEK');
        $objSheet->getCell('D9')->setValue('TIME IN');
        $objSheet->getCell('E9')->setValue('TIME OUT');
        $objSheet->getCell('F9')->setValue('TIME IN');
        $objSheet->getCell('G9')->setValue('TIME OUT');
        $objSheet->getCell('H9')->setValue('MAN HOUR');
        $objSheet->getCell('I9')->setValue('LATE HOUR');
        $objSheet->getCell('J9')->setValue('P');
        $objSheet->getCell('K9')->setValue('PLW');
        $objSheet->getCell('L9')->setValue('A');
        $objSheet->getCell('M9')->setValue('SP');
        $objSheet->getCell('N9')->setValue('SN');
        $objSheet->getCell('O9')->setValue('L');
        $objSheet->getCell('P9')->setValue('LP');
        $objSheet->getCell('Q9')->setValue('OT');
        $objSheet->getCell('R9')->setValue('NC');
        $objSheet->getCell('S9')->setValue('ALD');
        $objSheet->getCell('T9')->setValue('- 4');
        $objSheet->getCell('U9')->setValue('NOTE');
        
        
         // For Style Excel
        $bold = array(
                'font' => array(
                    'bold' => true,
                    'underline' => PHPExcel_Style_Font::UNDERLINE_SINGLE
                )
            );
       
        
        $style = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '#0D181A'),
                'size' => 10,
                'name' => 'Calibri'
        ));
        
        $pembatas = array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                           'startcolor' => array('rgb' => 'EEE8AA')
                          );
        
        $summary = array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                           'startcolor' => array('rgb' => 'FFFFE0')
                          );
              
        // End Style
        $nomor =0;
        $i = 9;
        $n = $m = 0;
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

        $ActualHour_Sum = $LateHour_Sum = 0;
        $P_Count = $Minus_Count= $PLW_Count = $A_Count = $SP_Count = $SN_Count = $L_Count = $leavepermit_Count = $OT_Count = $NC_Count = $ALD_Count = 0;

        if ($checkdata == 'exist') {
            foreach ($resuldata as $row) {
                $n++;
                $i++;
                //$data['P'] = $data['A'] = $data['SN'] = $data['L'] = $data['LP'] = $data['OT'] = $data['NC'] = '';
                $minus = $p = $plw = $a = $sp = $sn = $l = $leavepermit = $ot = $nc = $ald = '';
                $PresenceDate = date('d-m-Y', strtotime($row['PresenceDate']));
                $DayOfWeek = $day[$row['DayOfWeek']];
                $ActualIn = substr($row['ActualIn'], 11, 5);
                $ActualOut = substr($row['ActualOut'], 11, 5);
                $ManualIn = substr($row['ManualIn'], 11, 5);
                $ManualOut = substr($row['ManualOut'], 11, 5);
                $ActualHour = $row['ActualHour'];
                $ActualHour_Sum+= $ActualHour;
                $rowlp = $this->report->get_leavepermit($row['IDEmployee'], $row['PresenceDate']);
				$imkkeluar = date('Hi', strtotime($rowlp->OutDate));	
				
				 if($imkkeluar=='0800'){
					 //$late = $this->libfun->decimaltominutes(0.00);
					 $Late = $this->decimaltominutes(0.00);
					 $Latedata = 0.00;
				 }else{
					 $Late =$this->decimaltominutes($row['LateHour']+0.01);
					 $Latedata = $row['LateHour'];
				 }
                
               // $Late = $row['LateHour'];
                $LateHour_Sum+= $Latedata;

                $in = substr($row['ActualIn'], 11, 5);
                $out = substr($row['ActualOut'], 11, 5);
                $min = substr($row['ManualIn'], 11, 5);
                $mout = substr($row['ManualOut'], 11, 5);
                
                $ain = $row['ActualIn'];
                $aout = $row['ActualOut'];    
		       $manin = $row['ManualIn'];
                $manout = $row['ManualOut'];
                       
                
                $checkmin = ($min=='' or $min==null)?'empty':'exist';
                $checkmout = ($mout=='' or $mout==null)?'empty':'exist';
                
                $actualhour = (strtotime($aout) - strtotime($ain)) / 3600; 
				$manhour = (strtotime($manout) - strtotime($manin)) / 3600; 
                
                    $location = $row['IDLocation'];
                    $tempat = ($location=='1')?'kapuk':'bitung';
                    $description = $row['Description'];
                    
                    $rowparam = $this->report->get_parampresence($row['PresenceDate']);
                    $chekcparam = ($rowparam == '' or $rowparam == NULL) ? 'empty' : 'exist';

                    if ($chekcparam == 'exist') {
                    if ($row['PresenceDate'] == $rowparam->ParamDate and $description=='P' and (!is_null($row['ActualIn']) and !is_null($row['ActualOut']))) {
                        $actualh = (strtotime($row['ActualOut']) - strtotime($row['ActualIn'])) / 3600;
                        $acthour = $this->libfun->decimaltominutes($actualh);
                        $hour = $this->libfun->decimaltominutes($rowparam->ParamWorkHour);

                        if ($location == $rowparam->ParamSite and $acthour < $hour) {
                            $nomor++;   

                            if ($row['IDEmployee'] == '0610010414') {
                                $description = 'P';
                                $flagparam='normal';
                            } else if ($row['IDEmployee'] == '0612010414') {
                                $description = 'P';
                                $flagparam='normal';
                            } else if ($row['IDEmployee'] == '0615040514') {
                                $description = 'P';
                                $flagparam='normal';
                            } else {
                                $description = 'A';
                                $flagparam='notnormal';
                            }
                            
                            //echo '<br/>';
                           // echo $description.'No'.$nomor.' - '.$row['IDEmployee'].'-'.$name.' Lokasi :'.$tempat.' In :'.$row['ActualIn'].' Out :'.$row['ActualOut'].' Hour'.$hour.' Actual Hour :'.$acthour.'<br/>';
                        } else {
                           $description = 'P';
                           $flagparam='normal';
                        }
                    }
                    
                      $flag = $flagparam;
                }else{
                    
                      $flag='normal';
                }
                
                $note = $row['rDescription'].' '.$row['Note'];
                if ($description == 'P') {
                   
                    
                   if ($actualhour <= 4) { 
                        if($checkmin !=='exist' and $checkmout !=='exist'){
                             $Minus_Count+= 1;
                             $minus = 1;
                             $note = 'LESS WORK HOUR';  
                        } 
						 if ($manhour >= 4) { 
                              $P_Count+= 1;
                              $p = 1;
                              $note= '';
                       }
                   }else{
                        $P_Count+= 1;
                        $p = 1;
                       
                   } 


		      
               
                }elseif ($description == 'A') {
                    $A_Count+= 1;
                    $a = 1;
                    
                     if($flag=='notnormal'){
                         $Minus_Count+= 1;
                         $minus = 1;
                         $note = 'LESS WORK HOUR :'.$rowparam->ParamWorkHour;
                           
                        }else{
                         $note = 'ABSENCE';
                        }
                }elseif ($description == 'ALD') {
                    if ($row['IDJobGroup'] == 'ST' and ($row['ActualIn'] !== null and $row['ActualOut'] !== null )) {
                        $P_Count+= 1;
                        $ALD_Count+= '';
                        $p = 1;
                        $ald = '';
                        $note = '';
                    } else if ($row['IDJobGroup'] !== 'ST' and ($row['ActualIn'] !== null and $row['ActualOut'] !== null )) {
                        $P_Count+= 1;
                        $ALD_Count+= '';
                        $data['Presence'] = 1;
                        $ald = '';
                        $note = '';
                    } else if ($row['IDJobGroup'] == 'ST') {
                        $A_Count += '';
                        $ALD_Count+= 1;
                        $p = '';
                        $ald = 1;
                        $note = 'ANNUAL LEAVE DEDUCTION';
                    } else {
                        $A_Count+= '';
                        $ALD_Count+= 1;
                        $a = '';
                        $ald = 1;
                        $note = 'ANNUAL LEAVE DEDUCTION';
                    }
                }elseif ($description == 'SP') {
                    $SP_Count+= 1;
                    $sp = 1;
                    $note = 'SKORSING';
                } elseif ($description == 'PLW') {
                    $PLW_Count+= 1;
                    $plw = 1;
                }elseif ($description == 'SN' or $description == 'LSN') {
                    $SN_Count+= 1;
                    $sn = 1;
                }  elseif ($description == 'L') {
                    $L_Count+= 1;
                    $l = 1;
                } elseif ($description == 'LP') {
                    $leavepermit_Count+=1;
                    $leavepermit = 1;
                    
                     $status = $row['Necessity'];
                     
                     if ($status == '1') {
                            $data['Note'] = 'LEAVE PERMIT (personal)';
                            if ($actualhour <= 4) {                           
                                  $Minus_Count+= 1;
                                  $minus = 1;
                                  $note = 'LESS WORK HOUR'; 
                            }
                        } else if ($status == '2') {
                            $data['Note'] = 'LEAVE PERMIT (office)';
                            
                        }
                     
                    //penambahan filter untuk mengecek kondisi absensi
                    if (($in != null and $out != null) or ($min != null and $mout != null)) {

                        $P_Count+= 1;
                        $p = 1;
                    }
                } elseif ($description = NULL AND ($in != null AND $out != null and $flag=='normal')) {
                    $P_Count+= 1;
                    $p = 1;
                    $note = '';
                } elseif ($description == 'OT') {
                    $OT_Count+= 1;
                    $ot = 1;
                } elseif ($description == 'NC') {
                    $NC_Count+= 1;
                    $nc = 1;
                    $P_Count+= 1;
                    $p = 1;
                }  elseif ($description == 'CIR') {
                    $L_Count+= 1;
                    $l = 1;
                    $note = 'CIRCUMCISION LEAVE';
                } 
                
                if($row['IDUnitGroup']=='SECURITY' AND $a=='1' OR $minus =='1'){
                    $objSheet->getStyle('A' . $i . ':T' . $i)->applyFromArray($style);
                    
                    $objSheet->getCell('A' . $i)->setValue($n);
                    $objSheet->getCell('B' . $i)->setValue($PresenceDate);
                    $objSheet->getCell('C' . $i)->setValue($DayOfWeek);
                    $objSheet->getCell('D' . $i)->setValue($ActualIn);
                    $objSheet->getCell('E' . $i)->setValue($ActualOut);
                    $objSheet->getCell('F' . $i)->setValue($ManualIn);
                    $objSheet->getCell('G' . $i)->setValue($ManualOut);
                    $objSheet->getCell('H' . $i)->setValue($ActualHour);
                    $objSheet->getCell('I' . $i)->setValue($Late);
                    $objSheet->getCell('J' . $i)->setValue($p);
                    $objSheet->getCell('K' . $i)->setValue($plw);
                    $objSheet->getCell('L' . $i)->setValue($a);
                    $objSheet->getCell('M' . $i)->setValue($sp);
                    $objSheet->getCell('N' . $i)->setValue($sn);
                    $objSheet->getCell('O' . $i)->setValue($l);
                    $objSheet->getCell('P' . $i)->setValue($leavepermit);
                    $objSheet->getCell('Q' . $i)->setValue($ot);
                    $objSheet->getCell('R' . $i)->setValue($nc);
                    $objSheet->getCell('S' . $i)->setValue($ald);
                    $objSheet->getCell('T' . $i)->setValue($minus);
                    $objSheet->getCell('U' . $i)->setValue($note);                   
                    
                }else{
                    
                    $objSheet->getCell('A' . $i)->setValue($n);
                    $objSheet->getCell('B' . $i)->setValue($PresenceDate);
                    $objSheet->getCell('C' . $i)->setValue($DayOfWeek);
                    $objSheet->getCell('D' . $i)->setValue($ActualIn);
                    $objSheet->getCell('E' . $i)->setValue($ActualOut);
                    $objSheet->getCell('F' . $i)->setValue($ManualIn);
                    $objSheet->getCell('G' . $i)->setValue($ManualOut);
                    $objSheet->getCell('H' . $i)->setValue($ActualHour);
                    $objSheet->getCell('I' . $i)->setValue($Late);
                    $objSheet->getCell('J' . $i)->setValue($p);
                    $objSheet->getCell('K' . $i)->setValue($plw);
                    $objSheet->getCell('L' . $i)->setValue($a);
                    $objSheet->getCell('M' . $i)->setValue($sp);
                    $objSheet->getCell('N' . $i)->setValue($sn);
                    $objSheet->getCell('O' . $i)->setValue($l);
                    $objSheet->getCell('P' . $i)->setValue($leavepermit);
                    $objSheet->getCell('Q' . $i)->setValue($ot);
                    $objSheet->getCell('R' . $i)->setValue($nc);
                    $objSheet->getCell('S' . $i)->setValue($ald);
                    $objSheet->getCell('T' . $i)->setValue($minus);
                    $objSheet->getCell('U' . $i)->setValue($note);
                    
                    
                }
                
            }
            $i++;
            $objSheet->getStyle('A' . $i . ':U' . $i)->getFont()->setBold(true)->setSize(10);
            $objSheet->getCell('A' . $i)->setValue();
            $objSheet->getCell('B' . $i)->setValue();
            $objSheet->getCell('C' . $i)->setValue();
            $objSheet->getCell('D' . $i)->setValue();
            $objSheet->getCell('E' . $i)->setValue();
            $objSheet->getCell('F' . $i)->setValue();
            $objSheet->getCell('G' . $i)->setValue('TOTAL');
            $objSheet->getCell('H' . $i)->setValue($ActualHour_Sum);
            $objSheet->getCell('I' . $i)->setValue($this->decimaltominutes($LateHour_Sum+0.01));
            $objSheet->getCell('J' . $i)->setValue($P_Count);
            $objSheet->getCell('K' . $i)->setValue($PLW_Count);
            $objSheet->getCell('L' . $i)->setValue($A_Count);
            $objSheet->getCell('M' . $i)->setValue($SP_Count);
            $objSheet->getCell('N' . $i)->setValue($SN_Count);
            $objSheet->getCell('O' . $i)->setValue($L_Count);
            $objSheet->getCell('P' . $i)->setValue($leavepermit_Count);
            $objSheet->getCell('Q' . $i)->setValue($OT_Count);
            $objSheet->getCell('R' . $i)->setValue($NC_Count);
            $objSheet->getCell('S' . $i)->setValue($ALD_Count);
            $objSheet->getCell('T' . $i)->setValue($Minus_Count);
            $objSheet->getCell('U' . $i)->setValue();
        }
        $objSheet->getStyle('A8:U' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:U' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:U' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }

        ob_end_clean();
        $objWriter->save($path_file . "detailreportpresence" . $ext);
        $data = file_get_contents($path_file . "detailreportpresence" . $ext);
        force_download("detailreportpresence" . "-" . date('d-m-Y h:i') . $ext, $data);
    }
    
    

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */





