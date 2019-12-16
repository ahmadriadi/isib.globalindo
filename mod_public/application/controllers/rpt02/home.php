<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report_model', 'report');
        $this->load->model('employee_model', 'employee');

    }

    function index() {
        $date = $this->periodpayroll();
        $data['default']['f01'] = substr($date, 0, 10);
        $data['default']['f02'] = substr($date, 11, 10);
       
        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));

        $this->load->view('rpt02/home', $data);
    }
    
     function periodpayroll() {
        $today = array('Year' => date('Y'), 'Month' => date('m'));
        $untildate = date('d-m-Y', strtotime($today['Year'] . "-" . $today['Month'] . "-24"));
        $from = date('d-m-Y', strtotime("-1 month +1 day", strtotime($untildate)));
        return $from . " " . $untildate;
    }


    function presencedata($f, $u, $name = '') {
        $dfrom = date('Y-m-d', strtotime($f));
        $duntil = date('Y-m-d', strtotime($u));

        $result = $this->report->report_for_late($dfrom, $duntil, $name);
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

    function iframedata($from, $until, $name = '') {
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $data['url'] = site_url('rpt02/home/reportdata/'  . $dfrom . '/' . $duntil . '/' . $name);
        $this->load->view('rpt02/iframe', $data);
    }

    function reportdata($from, $until, $name = '') {
        $resuldata = $this->report->report_for_late($from, $until, $name);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {

            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['fulname'] = $name;
            $data['resultdata'] = $resuldata;
            $data['url_excel'] = site_url('rpt02/home/excel' . '/' . $group . '/' . $from . '/' . $until . '/' . $name);

            $this->load->view('rpt02/report', $data);
        }
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

    function excel($fromdate, $untildate, $nip) {
        $result = $this->report->report_for_late($fromdate, $untildate, $nip);

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

        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('LATE ARRIVE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
      

        $objSheet->getCell('A6')->setValue('ID');
        $objSheet->getCell('B6')->setValue('ID EMPLOYEE');
        $objSheet->getCell('C6')->setValue('FULLNAME');
        $objSheet->getCell('D6')->setValue('DATE');
        $objSheet->getCell('E6')->setValue('DAY OF WEEK');
        $objSheet->getCell('F6')->setValue('ACTUAL IN');
        $objSheet->getCell('G6')->setValue('LATE (HOUR)');
        $objSheet->getCell('H6')->setValue('DEDUC (HOUR)');
        
        $styleforlate = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => 'FF0000'),
                'size' => 12,
                'name' => 'Calibri'
        ));

        
        
        $i = 6;
        $counter = 0;
        $lastid = "";
        $lastnip = "";
        $lastdate = "";
        $lastname = "";
        $no =  0;
        $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $TotLH = $DeducSum = 0;

        if ($result != NULL) {
         
            $TotLH = $deducsum = 0;
            foreach ($result as $row) {
                
               
                $nip = $row['IDEmployee'];
                $act = ($row['ActualIn'] == '' or $row['ActualIn'] == NULL) ? 'empty' : 'exist';
                $man = ($row['ManualIn'] == '' or $row['ManualIn'] == NULL) ? 'empty' : 'exist';

                if ($act == 'exist' and $man == 'empty') {
                    $actualin = date('Hi', strtotime($row['ActualIn']));
                    $prindata = 'yes';
                } else if ($act == 'exist' and $man == 'exist') {
                    $actualin = date('Hi', strtotime($row['ManualIn']));
                    $prindata = 'no';
                } else if ($act == 'empty' and $man == 'exist') {
                    $actualin = date('Hi', strtotime($row['ManualIn']));
                    $prindata = 'no';
                } else if ($act == 'empty' and $man == 'empty') {
                    $actualin = date('Hi', strtotime('00:00'));
                    $prindata = 'no';
                }


                $rowplate = $this->report->get_paramlate($row['PresenceDate'],$row['IDLocation']);
                $flag = ($rowplate=='' or $rowplate==null)? 'empty' : 'exist';
                $workday = ($rowplate=='' or $rowplate==null)? $row['WorkDay'] : $rowplate->StartTimeLate;

                $workhour = $row['WorkHour'];
                $lhour = $row['LateHour'];
                $dailysalary = $row['DailySalary'];
                $timelp = date('Hi', strtotime($row['IMKOut']));
                $lp = ($row['IMKOut'] == '' or $row['IMKOut'] == NULL) ? 'empty' : 'exist';


                $t_in = date('H:i:s', strtotime($row['ActualIn']));
		$workinshift1 = date('H:i:s', strtotime("08:00:00"));
                $workinshift2 = date('H:i:s', strtotime("16:00:00"));
                $workinshift2_2 = date('H:i:s', strtotime("16:30:00"));
                $workinshift2_3 = date('H:i:s', strtotime("20:00:00"));
                $workinshift2_4 = date('H:i:s', strtotime("13:00:00"));
                $workinshift3 = date('H:i:s', strtotime("00:00:00"));

		if($workday =='N3'){
                    $range1 ='1200';
                }else{
                    $range1 ='1300';
                }

                if ($actualin >= '0400' AND $actualin <= $range1) {
                    if ((strtotime($t_in) - strtotime($workinshift1)) / 3600 > 0) {
                        $latehour = (strtotime($t_in) - strtotime($workinshift1)) / 3600;
                    } else {
                        $latehour = 0.00;
                    }

                    $shift = '1';
                    $lateh = $latehour;
                    //$lateh = $lhour;
                } else if ($actualin >= '1300' AND $actualin <= '1800') {

                    if ($workday == 'N2') {
                        $workin = $workinshift2_2;
                    } else if ($workday == 'N3') {
                        $workin = $workinshift2_4;
                    } else {
                        $workin = $workinshift2;
                    }

                    if ((strtotime($t_in) - strtotime($workin)) / 3600 > 0) {
                        $latehour = (strtotime($t_in) - strtotime($workin)) / 3600;
                    } else {
                        $latehour = 0.00;
                    }

                    $shift = '2';
                    $lateh = $latehour;
                } else if ($actualin >= '1800' AND $actualin <= '2100') {
                    if ((strtotime($t_in) - strtotime($workinshift2_3)) / 3600 > 0) {
                        $latehour = (strtotime($t_in) - strtotime($workinshift2_3)) / 3600;
                    } else {
                        $latehour = 0.00;
                    }

                    $shift = '2-1';
                    $lateh = $latehour;
                }/* else if ($actualin >= '0000' AND $actualin <= '0200') {
                    if ((strtotime($t_in) - strtotime($workinshift3)) / 3600 > 0) {
                        $latehour = (strtotime($t_in) - strtotime($workinshift3)) / 3600;
                    } else {
                        $latehour = 0.00;
                    }

                    $shift = '3';
                    $lateh = $latehour;
                }*/
		
		$tmplt = $lateh+0.01;	
                $late = $this->decimaltominutes($tmplt);

                $tmplate = explode(':', $late);
                $timelate = $tmplate[0] . $tmplate[1];

                if (floor($workhour) == '5') {
                    $timehour = '7';
                } else {
                    $timehour = '7';
                }

                $hoursalary = (($dailysalary) / ($timehour));
                
                if ($prindata == 'yes') {


                            if ($late !== '00:00') {
                                $counter++;

                                if ($row['IDEmployee'] != $lastid and $counter > 1 and $sumdeduchour !== '-') {
                                    $i++;
                                    $objSheet->getStyle('A' . $i . ':H' . $i)->applyFromArray($styleforlate);
                                    $objSheet->getCell('F' . $i)->setValue('SUM PRESENCE LATE : ' . $lastname);
                                    $objSheet->getCell('G' . $i)->setValue($this->decimaltominutes($sumhour + 0.01));
                                    $objSheet->getCell('H' . $i)->setValue($sumdeduchour);
                                    $sumdeduchour = $sumhour = '-';
                                }


                                if ($workhour == 7.00) {
                                    if ($timelate >= '0001' and $timelate <= '0010') {
                                        $deduclate = $hoursalary;
                                        $deducstatus = '1';
                                    } else if ($timelate >= '0011' and $timelate <= '0020') {
                                        $deduclate = $hoursalary * 2;
                                        $deducstatus = '2';
                                    } else if ($timelate >= '0021' and $timelate <= '0030') {
                                        $deduclate = $hoursalary * 3;
                                        $deducstatus = '3';
                                    } else if ($timelate >= '0031' and $timelate <= '0040') {
                                        $deduclate = $hoursalary * 4;
                                        $deducstatus = '4';
                                    } else if ($timelate >= '0041' and $timelate <= '0050') {
                                        $deduclate = $hoursalary * 5;
                                        $deducstatus = '5';
                                    } else if ($timelate >= '0051' and $timelate <= '0059') {
                                        $deduclate = $hoursalary * 6;
                                        $deducstatus = '6';
                                    } else if ($timelate >= '0100' and $timelate <= '0110') {
                                        $deduclate = $hoursalary * 7;
                                        $deducstatus = '7';
                                    } else if ($timelate >= '0111') {
                                        $deduclate = $hoursalary * 7;
                                        $deducstatus = '7';
                                    }

                                    $status = '7';
                                } else if ($workhour == 5.00) {
                                    if ($timelate >= '0001' and $timelate <= '0010') {
                                        $deduclate = $hoursalary;
                                        $deducstatus = '1';
                                    } else if ($timelate >= '0011' and $timelate <= '0020') {
                                        $deduclate = $hoursalary * 2;
                                        $deducstatus = '2';
                                    } else if ($timelate >= '0021' and $timelate <= '0030') {
                                        $deduclate = $hoursalary * 3;
                                        $deducstatus = '3';
                                    } else if ($timelate >= '0031' and $timelate <= '0040') {
                                        $deduclate = $hoursalary * 4;
                                        $deducstatus = '4';
                                    } else if ($timelate >= '0041' and $timelate <= '0050') {
                                        $deduclate = $hoursalary * 5;
                                        $deducstatus = '5';
                                    } else if ($timelate >= '0051' and $timelate <= '0059') {
                                        $deduclate = $hoursalary * 6;
                                        $deducstatus = '6';
                                    } else if ($timelate >= '0100' and $timelate <= '0110') {
                                        $deduclate = $hoursalary * 7;
                                        $deducstatus = '7';
                                    } else if ($timelate >= '0111') {
                                        $deduclate = $hoursalary * 7;
                                        $deducstatus = '7';
                                    }

                                    $status = ' 5 ';
                                }


							   if ($lp == 'empty' and $shift !== '2-1' and $flag == 'empty') {
                                    $statusdata = 'cetak';
                                    
                                    
                                } else {
                                    error_reporting(0);
                                    $rowlp = $this->report->get_leavepermit($row['IDEmployee'], $row['PresenceDate']);
				    $imkkeluar = date('Hi', strtotime($rowlp->OutDate));	                                    


                                    if ($shift !== '2-1' and $imkkeluar == '0800') {
                                        $statusdata = 'jangan';
                                        
                                        //echo $flag.'-'.$row['FullName'].'-'.$presencedate.'<br/>';
                                        
                                    } else if ($shift !== '2-1' and $flag == 'empty') {
                                        $statusdata = 'cetak';
                                        
                                    }
                                }

                                if ($statusdata == 'cetak') {
                                    $i++;
                                    $no++;
                                    
                                    $objSheet->getCell('A' . $i)->setValue($no);
                                    $objSheet->getCell('B' . $i)->setValue("'" . $nip);
                                    $objSheet->getCell('C' . $i)->setValue($row['FullName']);
                                    $objSheet->getCell('D' . $i)->setValue($row['PresenceDate']);
                                    $objSheet->getCell('E' . $i)->setValue($day[date('w', strtotime($row['PresenceDate']))]);
                                    $objSheet->getCell('F' . $i)->setValue(date('H:i', strtotime($actualin)));
                                    $objSheet->getCell('G' . $i)->setValue(date('H:i', strtotime($timelate)));
                                    $objSheet->getCell('H' . $i)->setValue($deducstatus);
                                    
                                    $sumhour +=$lateh;
                                    $sumdeduchour +=$deducstatus;
                                    $lastid = $row['IDEmployee'];
                                    $lastname = $row['FullName'];
                                }		
							
                            }
                        }


            }
            
            $i++;
            $objSheet->getStyle('A' . $i . ':H' . $i)->applyFromArray($styleforlate);
            $objSheet->getCell('F' . $i)->setValue('SUM PRESENCE LATE : ' . $lastname);
            $objSheet->getCell('G' . $i)->setValue($this->decimaltominutes($sumhour + 0.01));
            $objSheet->getCell('H' . $i)->setValue($sumdeduchour);
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





