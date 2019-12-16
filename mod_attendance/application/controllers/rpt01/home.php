<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report_model', 'report');
        $this->load->model('employee_model', 'employee');
	$this->load->model('libraryfunction_model', 'libfun'); 	
    }

    function index() {
        $date = $this->libfun->periode_work();
        $data['default']['f01'] = substr($date, 0, 10);
        $data['default']['f02'] = substr($date, 11, 10);
        
        $qdept = $this->employee->get_dept()->result();
	$i = 0;
	foreach ($qdept as $r) {
	    $i++;
	    $data['default']['dept'][-1]['value'] ='ALL';
	    $data['default']['dept'][-1]['display'] = '-ALL DEPARTEMENT-';   
	    $data['default']['dept'][$i]['value'] = $r->ID;
	    $data['default']['dept'][$i]['display'] = $r->DescStructure;   
	}
        

        $query = $this->employee->get_rjob_standar()->result();
	$j = 0;
	foreach ($query as $r) {
	    $j++;
	    $data['default']['f03'][$j]['value'] = $r->IDJobGroup;
	    $data['default']['f03'][$j]['display'] = $r->GroupName;   
	}
        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));

        $this->load->view('rpt01/home', $data);
    }

   

    function presencedata($group, $f, $u, $dept) {
        $dfrom = date('Y-m-d', strtotime($f));
        $duntil = date('Y-m-d', strtotime($u));

        $result = $this->report->check_allpresence($group, $dfrom, $duntil,$dept);
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

    function iframedata($group, $from, $until, $dept) {
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $data['url'] = site_url('rpt01/home/reportdata/' . $group . '/' . $dfrom . '/' . $duntil.'/'.$dept);
        $this->load->view('rpt01/iframe', $data);
    }

    function reportdata($group, $from, $until, $dept) {
        $jdFrom = GregorianToJD(date('m', strtotime($from)), date('d', strtotime($from)), date('Y', strtotime($from)));
        $jdUntil = GregorianToJD(date('m', strtotime($until)), date('d', strtotime($until)), date('Y', strtotime($until)));


        $resuldata = $this->report->report_summary($from, $until,$dept,$group);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['jobgroup'] = $group;            
            $data['dept'] = $dept;            
            $data['departement'] = $this->employee->dept($dept);
            $data['selisih'] = $jdUntil - $jdFrom + 1;

            $data['resultdata'] = $resuldata;
            $this->load->view('rpt01/report', $data);
        }
    }

    function excel($group, $fromdate, $untildate,$dept) {
        error_reporting(0);
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
        $objSheet->setTitle('summary');


        $result = $this->report->report_summary($fromdate, $untildate,$dept,$group);
        $departement = $this->employee->dept($dept);
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('DETAIL PRESENCE REPORT');
        $objSheet->getCell('A3')->setValue('JOB GROUP');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('A5')->setValue('DEPARTEMENT');
        
        $objSheet->getCell('B3')->setValue(':');
        $objSheet->getCell('C3')->setValue($group);
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($departement);

        $objSheet->getCell('C6')->setValue('DATE');
        $objSheet->getCell('A7')->setValue('ID EMPLOYEE');
        $objSheet->getCell('B7')->setValue('FULLNAME');

        $huruf = 'B';
        $perioddate = $fromdate;
        while ($perioddate <= $untildate) {
            $huruf++;
            $objSheet->getCell($huruf . '7')->setValue(date('d', strtotime($perioddate)));
            $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
        }
        $huruf++;
        $objSheet->getCell($huruf . '6')->setValue('TOTAL');
        $objSheet->getCell($huruf . '7')->setValue('P');
        $huruf++;
        $objSheet->getCell($huruf . '7')->setValue('PLW');
        $huruf++;
        $objSheet->getCell($huruf . '7')->setValue('A');
        $huruf++;
	$objSheet->getCell($huruf . '7')->setValue('SP');
        $huruf++;
        $objSheet->getCell($huruf . '7')->setValue('SN');
        $huruf++;
        $objSheet->getCell($huruf . '7')->setValue('L');
        $huruf++;
        $objSheet->getCell($huruf . '7')->setValue('LP');
        $huruf++;
        $objSheet->getCell($huruf . '7')->setValue('OT');
        $huruf++;
        $objSheet->getCell($huruf . '7')->setValue('NC');
        $huruf++;
        $objSheet->getCell($huruf . '7')->setValue('ALD');

        $objSheet->getStyle('A1:' . $huruf . '7')->getFont()->setBold(true)->setSize(10);

        $i = 7;
        $lastnip = $array[0]['IDEmployee'];
        $P_Count = $PLW_Count = $A_Count = $SP_Count = $SN_Count = $L_Count = $LP_Count = $OT_Count = $NC_Count = $ALD_Count = 0;
        $data = array();
        if ($result != NULL) {
            foreach ($result as $row) {
                
                if ($lastnip != $row['IDEmployee']) {
                    // Cetak
                    $i++;
                    $objSheet->getCell('A' . $i)->setValue("'".$IDEmployee);
                    $objSheet->getCell('B' . $i)->setValue($FullName);

                    $huruf2 = 'B';
                    $perioddate = $fromdate;
                    while ($perioddate <= $untildate) {
                        $huruf2++;
			//echo $data['25'];echo "<br/>";echo "<br/>";                        
			$objSheet->getCell($huruf2 . $i)->setValue($data[date('d', strtotime($perioddate))]);
                        $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
                        //unset($data);
                        //$data = array();
                    }
                    $huruf2++;
                    $objSheet->getCell($huruf2 . $i)->setValue($P_Count);
                    $huruf2++;
                    $objSheet->getCell($huruf2 . $i)->setValue($PLW_Count);
                    $huruf2++;
                    $objSheet->getCell($huruf2 . $i)->setValue($A_Count);
                    $huruf2++;
		    $objSheet->getCell($huruf2 . $i)->setValue($SP_Count);
                    $huruf2++;	
                    $objSheet->getCell($huruf2 . $i)->setValue($SN_Count);
                    $huruf2++;
                    $objSheet->getCell($huruf2 . $i)->setValue($L_Count);
                    $huruf2++;
                    $objSheet->getCell($huruf2 . $i)->setValue($LP_Count);
                    $huruf2++;
                    $objSheet->getCell($huruf2 . $i)->setValue($OT_Count);
                    $huruf2++;
                    $objSheet->getCell($huruf2 . $i)->setValue($NC_Count);
                    $huruf2++;
                    $objSheet->getCell($huruf2 . $i)->setValue($ALD_Count);
                    
                    $P_Count = $PLW_Count =$A_Count = $SP_Count= $SN_Count = $L_Count = $LP_Count = $OT_Count = $NC_Count = $ALD_Count = 0;
                }
                // Set
                $IDEmployee = $row['IDEmployee'];
                $FullName = $row['FullName'];
                
                $presence           = $row['PresenceDate'];
                $hire               = $row['HireDate'];
                $resign             = $row['ResignDate'];
                if($hire>$presence) {
                               $data[date('d', strtotime($row['PresenceDate']))] = "New";
                } elseif( (!is_null($resign) AND ($presence >= $resign)) ) {
                               $data[date('d', strtotime($row['PresenceDate']))] = "Resign";
                 }else{
                     
                     $description        = $row['Description'] ;
                     
                    if($description =='P'){
                        $P_Count+= 1;
                        $data[date('d', strtotime($row['PresenceDate']))] = '';
                    }else if($description =='PLW'){
                        $PLW_Count+= 1;
                        $data[date('d', strtotime($row['PresenceDate']))] = 'PLW';
                    }else if($description =='A'){
                        $A_Count+= 1;
                        $data[date('d', strtotime($row['PresenceDate']))] = 'A';
                    }else if($description =='SP'){
                        $SP_Count+= 1;
                        $data[date('d', strtotime($row['PresenceDate']))] = 'SP';
                    }else if($description =='SN'){
                       $SN_Count+= 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'SN'; 
                    }else if($description =='LP'){
                       //$LP_Count+= 1;
                      // $data[date('d', strtotime($row['PresenceDate']))] = 'LP'; 
                    }else if($description =='AL'){
                       $L_Count+= 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'L'; 
                    }else if($description =='MTL'){
                       $L_Count+= 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'L'; 
                    }else if($description =='MRL'){
                       $L_Count+= 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'L'; 
                    }else if($description =='CL'){
                       $L_Count+= 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'L'; 
                    }else if($description =='SL'){
                       $L_Count+= 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'L'; 
                    }else if($description =='OL'){
                       $L_Count+= 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'L'; 
                    }else if($description =='FML'){
                       $L_Count+= 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'L'; 
                    }else if($description =='CIR'){
                       $L_Count+= 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'L'; 
                    }else if($description =='OT'){
                       $OT_Count+= 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'OT'; 
                    }else if($description =='NC'){
                       $NC_Count += 1;
                       $data[date('d', strtotime($row['PresenceDate']))] = 'NC';
                       $P_Count += 1;
                    }else if($description =='ALD'){                                   
                        if($row['IDJobGroup'] =='ST' and ($row['ActualIn'] !==null and $row['ActualOut']!==null )){
                           $P_Count+= 1;
                           $ALD_Count+= '';
                           $data[date('d', strtotime($row['PresenceDate']))] = ''; 
                        }else if($row['IDJobGroup'] !=='ST' and ($row['ActualIn'] !==null and $row['ActualOut']!==null )){
                           $P_Count+= 1;
                           $ALD_Count+= '';
                           $data[date('d', strtotime($row['PresenceDate']))] = ''; 
                        }else if($row['IDJobGroup'] =='ST'){
                           $A_Count += '';
                           $ALD_Count+= 1;
                           $data[date('d', strtotime($row['PresenceDate']))] = 'ALD';  
                        }else{
                           $A_Count+= '';
                           $ALD_Count+= 1;
                           $data[date('d', strtotime($row['PresenceDate']))] = 'ALD';  
                        } 
                   }else{
                            $data[date('d', strtotime($row['PresenceDate']))] = '-';
                    }
                           
              }
                
                $lastnip = $row['IDEmployee'];
            }
            // Cetak
            $i++;
            $objSheet->getCell('A' . $i)->setValue("'".$IDEmployee);
            $objSheet->getCell('B' . $i)->setValue($FullName);

            $huruf2 = 'B';
            $perioddate = $fromdate;
            while ($perioddate <= $untildate) {
                $huruf2++;
                $objSheet->getCell($huruf2 . $i)->setValue($data[date('d', strtotime($perioddate))]);
                $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
            }
            $huruf2++;
            $objSheet->getCell($huruf2 . $i)->setValue($P_Count);
            $huruf2++;
            $objSheet->getCell($huruf2 . $i)->setValue($PLW_Count);
            $huruf2++;
            $objSheet->getCell($huruf2 . $i)->setValue($A_Count);
            $huruf2++;
	    $objSheet->getCell($huruf2 . $i)->setValue($SP_Count);
            $huruf2++;  	
            $objSheet->getCell($huruf2 . $i)->setValue($SN_Count);
            $huruf2++;
            $objSheet->getCell($huruf2 . $i)->setValue($L_Count);
            $huruf2++;
            $objSheet->getCell($huruf2 . $i)->setValue($LP_Count);
            $huruf2++;
            $objSheet->getCell($huruf2 . $i)->setValue($OT_Count);
            $huruf2++;
            $objSheet->getCell($huruf2 . $i)->setValue($NC_Count);
            $huruf2++;
            $objSheet->getCell($huruf2 . $i)->setValue($ALD_Count);
        }


        $objSheet->getStyle('A6:' . $huruf2 . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A6:' . $huruf2 . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A6:' . $huruf2 . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }

	ob_end_clean();
        $objWriter->save($path_file . "summarypresenceemployee" . $ext);
        $data = file_get_contents($path_file . "summarypresenceemployee" . $ext);
        force_download("summarypresenceemployee" . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


