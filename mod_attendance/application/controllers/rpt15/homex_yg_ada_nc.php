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

        $query = $this->employee->get_rjob_field()->result();
	$i = 0;
	foreach ($query as $r) {
	    $i++;
	    $data['default']['f04'][$i]['value'] = $r->IDJobGroup;
	    $data['default']['f04'][$i]['display'] = $r->GroupName;   
	}
        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));
        
        
        $idmenu                    = "103";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt15/home', $data);
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
        $data['url'] = site_url('rpt15/home/reportdata/' . $group . '/' . $dfrom . '/' . $duntil . '/' . $name);
        $this->load->view('rpt15/iframe', $data);
    }

    function reportdata($group, $from, $until, $name = '') {
        $resuldata = $this->report->report_sum_absen($from, $until, $group, $name);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {            
            $ng = $this->libfun->get_name_group($group); 
            
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['jobgroup'] = $ng;
            $data['fulname'] = $name;
            $data['resultdata'] = $resuldata;
            $data['url_excel'] = site_url('rpt15/home/dataexcel' . '/' . $group . '/' . $from . '/' . $until . '/' . $name);
            $this->load->view('rpt15/report', $data);
        }
    }


   function dataexcel($group, $fromdate, $untildate, $nip) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel

        $result = $this->report->report_sum_absen($fromdate, $untildate, $group, $nip);
        $cekdata = ($result !== null) ? $result : 'empty';
        
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        // add class excel
        $excel = new PHPExcel();

        //add property
        $excel->getProperties()->setCreator("PHP Excel")
                ->setLastModifiedBy("PHP Excel")
                ->setTitle("DATA SUMMARY ABSENCE FIELD")
                ->setSubject("DATA SUMMARY ABSENCE FIELD")
                ->setDescription("DATA SUMMARY ABSENCE FIELD")
                ->setKeywords("DATA SUMMARY ABSENCE FIELD")
                ->setCategory("DATA SUMMARY ABSENCE FIELD");

        //add style
        $worksheet = $excel->getActiveSheet();
        $worksheet->getStyle('A1:I2')->getFont()->setBold(true)->setSize(12);


//add header      
        $worksheet->getCell('A9')->setValue('PT. TRIAS INDRA SAPUTRA');
        $worksheet->getCell('A10')->setValue('REPORT SUMMARY ABSENCE FIELD');
        $worksheet->getCell('A11')->setValue('PERIODE :'.date('d-m-Y',strtotime($fromdate)).' s/d '.date('d-m-Y',strtotime($untildate)));


//add sub header
        $worksheet->getCell('A14')->setValue('No.');
        $worksheet->getCell('B14')->setValue('IDEmployee');
        $worksheet->getCell('C14')->setValue('FullName');
        $worksheet->getCell('D14')->setValue('Type Absence');
        $worksheet->getCell('D15')->setValue('A');
        $worksheet->getCell('D16')->setValue('A');
        $worksheet->getCell('E16')->setValue('NC');
        $worksheet->getCell('F15')->setValue('SP');
        $worksheet->getCell('G15')->setValue('OL');
        $worksheet->getCell('H15')->setValue('SN');
        $worksheet->getCell('I15')->setValue('ALD');

// add mergecell 
        $sheet = $excel->getActiveSheet();
        $sheet->mergeCells('A14:A16');
        $sheet->mergeCells('B14:B16');
        $sheet->mergeCells('C14:C16');
        $sheet->mergeCells('D14:I14');
        $sheet->mergeCells('D15:E15');
        $sheet->mergeCells('F15:F16');
        $sheet->mergeCells('G15:G16');
        $sheet->mergeCells('H15:H16');
        $sheet->mergeCells('I15:I16');


//add center   
        $sheet->getStyle('D14:I14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A14:A16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B14:B16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C14:C16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D14:D16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E14:E16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F14:F16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G14:G16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H14:H16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I14:I16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



//add border
        $worksheet->getStyle('A14:H16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('D15:I15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('B14:B16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('C14:C16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('D14:D16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('E14:E16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('F15:F16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('G15:G16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('H15:H16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('I14:I16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


//add style		
        $style = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '#FF4500'),
                'size' => 10,
                'name' => 'Calibri'
        ));

         if ($cekdata !== 'empty') {
            $i = 0;
            $n = 0;
            $counter = 16;
            $A_Count =$NC_Count =  $SP_Count = $OL_Count = $SN_Count = $ALD_Count = 0;
            $Lastemp_IDEmployee = '';
            $lastemp_FullName = '';
            $lastemp_unit = '';

            foreach ($result as $row) {

                $ain = $row['ActualIn'];
                $aout = $row['ActualOut'];
                $actualhour = (strtotime($aout) - strtotime($ain)) / 3600;
                $location = $row['IDLocation'];
                $tempat = ($location == '1') ? 'kapuk' : 'bitung';
                $description = $row['Description'];

                $rowparam = $this->report->get_parampresence($row['PresenceDate']);
                $chekcparam = ($rowparam == '' or $rowparam == NULL) ? 'empty' : 'exist';

                if ($chekcparam == 'exist') {
                    if ($row['PresenceDate'] == $rowparam->ParamDate and $description == 'P' and (!is_null($row['ActualIn']) and !is_null($row['ActualOut']))) {
                        $actualh = (strtotime($row['ActualOut']) - strtotime($row['ActualIn'])) / 3600;
                        $acthour = $this->libfun->decimaltominutes($actualh);
                        $hour = $this->libfun->decimaltominutes($rowparam->ParamWorkHour);

                        if ($location == $rowparam->ParamSite and $acthour < $hour) {
                            $nomor++;

                            if ($row['IDEmployee'] == '0610010414') {
                                $description = 'P';
                                $flagparam = 'normal';
                            } else if ($row['IDEmployee'] == '0612010414') {
                                $description = 'P';
                                $flagparam = 'normal';
                            } else if ($row['IDEmployee'] == '0615040514') {
                                $description = 'P';
                                $flagparam = 'normal';
                            } else {
                                $description = 'A';
                                $flagparam = 'notnormal';
                            }
                        } else {
                            $description = 'P';
                            $flagparam = 'normal';
                        }
                    }

                    $flag = $flagparam;
                } else {

                    $flag = 'normal';
                }
                
                 if ($row['Description'] !== 'A' && $row['Description'] !== 'P' && $row['Description'] !== 'SP' && $row['Description'] !== 'OL' && $row['Description'] !== 'SN' && $row['Description'] !== 'ALD')
                    continue;
                if ($Lastemp_IDEmployee !== $row['IDEmployee'] AND $lastemp_FullName !== $row['FullName'] && $n > 0) {
                    $counter++;
                    $i++;
                     if ($lastemp_unit == 'SECURITY') {
                            $worksheet->getStyle('A' . $counter . ':I' . $counter)->applyFromArray($style);
                            $worksheet->getCell('A' . $counter)->setValue($i);
                            $worksheet->getCell('B' . $counter)->setValue("'" . $Lastemp_IDEmployee);
                            $worksheet->getCell('C' . $counter)->setValue($lastemp_FullName);
                            $worksheet->getCell('D' . $counter)->setValue($A_Count . ' (Confirm to HRD)');
                            $worksheet->getCell('E' . $counter)->setValue($NC_Count);
                            $worksheet->getCell('F' . $counter)->setValue($SP_Count);
                            $worksheet->getCell('G' . $counter)->setValue($OL_Count);
                            $worksheet->getCell('H' . $counter)->setValue($SN_Count);
                            $worksheet->getCell('I' . $counter)->setValue($ALD_Count);
                            $A_Count =$NC_Count= $SP_Count = $OL_Count = $SN_Count = $ALD_Count = 0;
                     }else{
                            $worksheet->getCell('A' . $counter)->setValue($i);
                            $worksheet->getCell('B' . $counter)->setValue("'" . $Lastemp_IDEmployee);
                            $worksheet->getCell('C' . $counter)->setValue($lastemp_FullName);
                            $worksheet->getCell('D' . $counter)->setValue($A_Count);
                            $worksheet->getCell('E' . $counter)->setValue($NC_Count);
                            $worksheet->getCell('F' . $counter)->setValue($SP_Count);
                            $worksheet->getCell('G' . $counter)->setValue($OL_Count);
                            $worksheet->getCell('H' . $counter)->setValue($SN_Count);
                            $worksheet->getCell('I' . $counter)->setValue($ALD_Count);
                            $A_Count =$NC_Count= $SP_Count = $OL_Count = $SN_Count = $ALD_Count = 0;
                         
                         
                     }
                    
                }
                 $actin = ($row['ActualIn'] == '' or $row['ActualIn'] == NULL) ? 'empty' : 'exist';
                 $actout = ($row['ActualOut'] == '' or $row['ActualOut'] == NULL) ? 'empty' : 'exist';
                        
                        
                if($actin =='empty' and  $actout =='exist'){
                   $NC_Count+= 1;
                 }else if($actin =='exist' and  $actout =='empty'){
                   $NC_Count+= 1;
                 }
                

                $n++;
                if ($description == 'A') {
                    $A_Count+= 1;
                } else if ($description == 'SP') {
                    $SP_Count+= 1;
                } else if ($description == 'OL') {
                    $OL_Count+= 1;
                } else if ($description == 'SN') {
                    $SN_Count+= 1;
                } elseif ($description == 'ALD') {
                    if ($row['IDJobGroup'] == 'ST' and ($row['ActualIn'] !== null and $row['ActualOut'] !== null )) {
                        $ALD_Count+= '';
                    } else {
                        $ALD_Count+= 1;
                    }
                } else if ($description == 'P') {
                    if ($actualhour <= 4) {
                        $A_Count+= 1;
                    }
                }

                $Lastemp_IDEmployee = $row['IDEmployee'];
                $lastemp_FullName = $row['FullName'];
                $lastemp_unit = $row['IDUnitGroup'];
                
               
            }
            
            $counter++;
            $worksheet->getStyle('A1' . $counter . ':I1' . $counter)->getFont()->setBold(true)->setSize(10);
            $worksheet->getCell('A' . $counter)->setValue($i + 1);
            $worksheet->getCell('B' . $counter)->setValue("'" . $Lastemp_IDEmployee);
            $worksheet->getCell('C' . $counter)->setValue($lastemp_FullName);
            $worksheet->getCell('D' . $counter)->setValue($A_Count);
            $worksheet->getCell('E' . $counter)->setValue($NC_Count);
            $worksheet->getCell('F' . $counter)->setValue($SP_Count);
            $worksheet->getCell('G' . $counter)->setValue($OL_Count);
            $worksheet->getCell('H' . $counter)->setValue($SN_Count);
            $worksheet->getCell('I' . $counter)->setValue($ALD_Count); 
            
            
        }

        /// body border 
        $worksheet->getStyle('A16:I' . $counter)->getBorders()->getAllBorders()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A16:I' . $counter)->getBorders()->getOutline()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A16:I' . $counter)->getBorders()->getBottom()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        // footer
        $j = $counter + 3;
        $k = $counter + 4;
        $l = $counter + 8;
        $worksheet->getCell('G' . $j)->setValue('Jakarta, ' . date('d-F-Y'));
        $worksheet->getCell('G' . $k)->setValue('PT. Trias Indra Saputra');
        $worksheet->getCell('G' . $l)->setValue('System Development');

        ob_end_clean();

        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="summaryabsencefield.xlsx"');


        $objWriter = IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        $excel->disconnectWorksheets();
        unset($excel);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */





