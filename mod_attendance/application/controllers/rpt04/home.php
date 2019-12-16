<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report_model', 'report');
        $this->load->model('libraryfunction_model', 'libfun');
        $this->load->model('employee_model', 'employee');
        $this->load->model('uac_model', 'uac');
        $this->User = $this->session->userdata('sess_userid');
    }

    function index() {
        $date = $this->libfun->periode_work();
        $data['default']['f01'] = substr($date, 0, 10);
        $data['default']['f02'] = substr($date, 11, 10);

        $query = $this->employee->get_rjob_standar()->result();
	$i = 0;
	foreach ($query as $r) {
	    $i++;
            $data['default']['f04'][-1]['value'] = 'ALL';
	    $data['default']['f04'][-1]['display'] = 'ALL GROUP';
	    $data['default']['f04'][$i]['value'] = $r->IDJobGroup;
	    $data['default']['f04'][$i]['display'] = $r->GroupName;   
	}

        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));
        
        
        $idmenu                    = "99";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt04/home', $data);
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

    function overtimedata($group, $f, $u, $name = '') {
        $dfrom = date('Y-m-d', strtotime($f));
        $duntil = date('Y-m-d', strtotime($u));

        $result = $this->report->report_overtime($dfrom, $duntil, $group, $name);
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

    function iframedata($group, $from, $until, $name='') {
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $data['url'] = site_url('rpt04/home/reportdata/' . $group . '/' . $dfrom . '/' . $duntil . '/' . $name);
        $this->load->view('rpt04/iframe', $data);
    }

    function reportdata($group, $from, $until, $name='') {
        $resuldata = $this->report->report_overtime($from, $until, $group, $name);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['jobgroup'] = $group;
            $data['fulname'] = $name;
            $data['resultdata'] = $resuldata;
            $data['url_excel'] = site_url('rpt04/home/excel'. '/' . $group . '/' . $from . '/' . $until . '/' . $name);


            $day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
            $i = 0;
            foreach ($resuldata as $row) {
                $i++;
                $data['overtime'][$i]['ID'] = $row['ID'];
                $data['overtime'][$i]['IDSPKL'] = $row['IDSPKL'];
                $data['overtime'][$i]['IDEmployee'] = $row['IDEmployee'];
                $data['overtime'][$i]['FullName'] = $row['FullName'];
                if ($row['IDJobGroup'] == 'ST') {
                    $data['overtime'][$i]['GROUP'] = 'Staff';
                } else if ($row['IDJobGroup'] == 'LT') {
                    $data['overtime'][$i]['GROUP'] = 'Lap Tetap';
                } else if ($row['IDJobGroup'] == 'LK') {
                    $data['overtime'][$i]['GROUP'] = 'Lap Kontrak';
                } else if ($row['IDJobGroup'] == 'HL') {
                    $data['overtime'][$i]['GROUP'] = 'Harian Lepas';
                } else if ($row['IDJobGroup'] == 'LL') {
                    $data['overtime'][$i]['GROUP'] = 'Lain-lain';
                }
                $data['overtime'][$i]['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                $data['overtime'][$i]['DayOfWeek'] = $day[date('w', strtotime($row['PresenceDate']))];

                $timein = $row['OvertimeIn'];
                $timeout = $row['OvertimeOut'];
                $DescDay = $row['Description'];
                //$WorkDay = $row['WorkDay'];
                $IDJobGroup = $row['IDJobGroup'];


		//Catatan Perubahan 30 September 2014 check by riadi dan pak denny
                $periodedate = date('Y-m-d',strtotime($row['OvertimeIn']));                  
                $dayofweek = date('w', strtotime($periodedate));
                if ($this->report->check_holiday($periodedate)) {
                        $WorkDay = 'OFF';
                } else {
                    if ($dayofweek == 0)
                        $WorkDay = 'SUN';
                    elseif ($dayofweek > 0 && $dayofweek < 5)
                        $WorkDay = 'N1';
                    elseif ($dayofweek == 5)
                        $WorkDay = 'N2';
                    elseif ($dayofweek == 6)
                        $WorkDay = 'N3';
                }   		


                if (is_null($timein) or $timein == '0000-00-00 00:00:00') {
                    $OvertimeIn = "0000-00-00 00:00:00";
                    $OvertimeHour = 0;
                } else if (is_null($timeout) or $timeout == '0000-00-00 00:00:00') {
                    $OvertimeOut = "0000-00-00 00:00:00";
                    $OvertimeHour = 0;
                } else {
                    $OvertimeHour = $this->libfun->subs_time($timein, $timeout, 30);
                    $OvertimeIn = date('d-m-Y H:i', strtotime($row['OvertimeIn']));
                    $OvertimeOut = date('d-m-Y H:i', strtotime($row['OvertimeOut']));
                }

		 /* di hidupkan di hari libur atau minggu berdasarkan catatan bu doris per tanggal 01-04-2014 */
		 /* Revisi catatan 30 September 2014 di taruh di luar agar terbawa semua */
		
                if ($OvertimeHour >= 8)
                      $OvertimeHour = $OvertimeHour - 1;
		

                if ($WorkDay == 'SUN' OR $WorkDay == 'OFF' AND $DescDay != 'ALD') {
                   
                    if ($IDJobGroup == 'ST') {
                        $OvertimeTotalHour = $this->libfun->overtime_on_offday_staff($OvertimeHour);
                    } else if ($IDJobGroup == 'LT') {
                        $OvertimeTotalHour = $this->libfun->overtime_on_offday($OvertimeHour, 1);
                    } else {
                        if ($WorkDay == 'SUN') {
                            $OvertimeTotalHour = $this->libfun->overtime_on_workday($OvertimeHour, 2);
                        } else {
                            $OvertimeTotalHour = $this->libfun->overtime_on_offday($OvertimeHour, 2);
                        }
                    }
                } else {
                    // For WorkDay is normal day
                    if ($IDJobGroup == 'LT') {
                        $OvertimeTotalHour = $this->libfun->overtime_on_workday($OvertimeHour, 1);
                    } else {
                        $OvertimeTotalHour = $this->libfun->overtime_on_workday($OvertimeHour, 2);
                    }
                }

                $data['overtime'][$i]['OvertimeIn'] = $OvertimeIn;
                $data['overtime'][$i]['OvertimeOut'] = $OvertimeOut;
                $data['overtime'][$i]['OvertimeTotalHour'] = $OvertimeTotalHour;
            }

            $this->load->view('rpt04/report', $data);
        }
    }

    function excel($group, $fromdate, $untildate, $nip) {
        error_reporting(0);
        $ext = '.xlsx';
        $path_file = '/tmp/';

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->helper('download');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("title")
                ->setDescription("description");

        // currency format, &euro; with < 0 being in red color
        $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        // number format, with thousands seperator and two decimal points.
        $numberFormat = '#,#0.##;[Red]-#,#0.##';

        // writer will create the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();
        // rename the sheet
        $objSheet->setTitle('detail overtime report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4

        $objSheet->getStyle('A1:J6')->getFont()->setBold(true)->setSize(10);
        // write header
         $resuldata = $this->report->report_overtime($fromdate, $untildate, $group, $nip);
        for ($i = 0; $i < count($resuldata); $i++) {
            //IDJobGroup
            if ($result[$i]['IDJobGroup'] == 'ST') {
                $NMJobGroup = "STAFF";
            } else {
                if ($result[$i]['IDJobGroup'] == 'LT') {
                    $NMJobGroup = "LAPANGAN TETAP";
                } else {
                    if ($result[$i]['IDJobGroup'] == 'LK') {
                        $NMJobGroup = "LAPANGAN KONTRAK";
                    } else {
                        if ($result[$i]['IDJobGroup'] == 'HL') {
                            $NMJobGroup = "HARIAN LEPAS";
                        } else {
                            $NMJobGroup = "LAIN-LAIN";
                        }
                    }
                }
            }
        }
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('OVERTIME REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
        $objSheet->getCell('A5')->setValue('JOB GROUP');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($NMJobGroup);

        $objSheet->getCell('A6')->setValue('NO');
        $objSheet->getCell('B6')->setValue('ID SPKL');
        $objSheet->getCell('C6')->setValue('ID EMPLOYEE');
        $objSheet->getCell('D6')->setValue('NAME');
        $objSheet->getCell('E6')->setValue('JOB GROUP');
        $objSheet->getCell('F6')->setValue('PRESENCE DATE');
        $objSheet->getCell('G6')->setValue('WORK DAY');
        $objSheet->getCell('H6')->setValue('OVERTIME IN');
        $objSheet->getCell('I6')->setValue('OVERTIME OUT');
        $objSheet->getCell('J6')->setValue('OVERTIME TOTAL HOUR');
        $i = 6;
        $n = $m = 0;
        $day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        if ($resuldata != NULL) {
            foreach ($resuldata as $row) {
                $n++;
                $i++;

                $IDJobGroup = $row['IDJobGroup'];
                $DescDay = $row['Description'];
                //$WorkDay = $row['WorkDay'];

                $OvertimeOut = $row['OvertimeOut'];
                $OvertimeIn = $row['OvertimeIn'];
		
		
		//Catatan Perubahan 30 September 2014 check by riadi dan pak denny
                $periodedate = date('Y-m-d',strtotime($OvertimeIn));                  
                $dayofweek = date('w', strtotime($periodedate));
                if ($this->report->check_holiday($periodedate)) {
                        $WorkDay = 'OFF';
                } else {
                    if ($dayofweek == 0)
                        $WorkDay = 'SUN';
                    elseif ($dayofweek > 0 && $dayofweek < 5)
                        $WorkDay = 'N1';
                    elseif ($dayofweek == 5)
                        $WorkDay = 'N2';
                    elseif ($dayofweek == 6)
                        $WorkDay = 'N3';
                }   	



                if (is_null($OvertimeIn)) {
                    $OvertimeTotalHour = 0;
                } else {
                    if (is_null($OvertimeOut)) {
                        $OvertimeTotalHour = 0;
                    } else {
                        $OvertimeHour = $this->libfun->subs_time($OvertimeIn, $OvertimeOut, 30);
                        $OvertimeIn = date('d-m-Y H:i', strtotime($row['OvertimeIn']));
                        $OvertimeOut = date('d-m-Y H:i', strtotime($row['OvertimeOut']));
                    }
                }

                /* di matikan berdasarkan catatan bu doris per tanggal 01-04-2014 */
                //if ($OvertimeHour >=8 ) $OvertimeHour = $OvertimeHour - 1; /* di remark tanggal 11 november 2013 */
                //request sally 11 november 2013
                // if ($OvertimeHour >=5 ) $OvertimeHour = $OvertimeHour - 1;
                /* di matikan berdasarkan catatan bu doris per tanggal 01-04-2014 */

		
		
		/* di hidupkan di hari libur atau minggu berdasarkan catatan bu doris per tanggal 01-04-2014 */
		// Revisi 30 September 2014 di taruh di atas agar kena semua
                    if ($OvertimeHour >= 8)
                        $OvertimeHour = $OvertimeHour - 1;


                if ($WorkDay == 'SUN' OR $WorkDay == 'OFF' AND $DescDay != 'ALD') {
                    // For WorkDay is sunday or offday dan bukan cuti bersama                    

                    if ($IDJobGroup == 'ST') {

                        $OvertimeTotalHour = $this->libfun->overtime_on_offday_staff($OvertimeHour);
                    } else if ($IDJobGroup == 'LT') {

                        $OvertimeTotalHour = $this->libfun->overtime_on_offday($OvertimeHour, 1);
                    } else {
                        if ($WorkDay == 'SUN') {

                            $OvertimeTotalHour = $this->libfun->overtime_on_workday($OvertimeHour, 2);
                        } else {

                            $OvertimeTotalHour = $this->libfun->overtime_on_offday($OvertimeHour, 2);
                        }
                    }
                } else {
                    // For WorkDay is normal day
                    if ($IDJobGroup == 'LT') {
                        $OvertimeTotalHour = $this->libfun->overtime_on_workday($OvertimeHour, 1);
                    } else {
                        $OvertimeTotalHour = $this->libfun->overtime_on_workday($OvertimeHour, 2);
                    }
                }
                $PresenceDate = date('d-m-Y', strtotime($row['PresenceDate']));
                $DayOfWeek = $day[date('w', strtotime($row['PresenceDate']))];


                $data['ID'] = $row['ID'];
                $data['IDSPKL'] = $row['IDSPKL'];
                $data['IDEmployee'] = $row['IDEmployee'];
                $data['FullName'] = $row['FullName'];
                if ($row['IDJobGroup'] == 'ST') {
                    $data['GROUP'] = 'Staff';
                } else if ($row['IDJobGroup'] == 'LT') {
                    $data['GROUP'] = 'Lap.Tetap';
                } else if ($row['IDJobGroup'] == 'LK') {
                    $data['GROUP'] = 'Lap.kontrak';
                } else if ($row['IDJobGroup'] == 'HL') {
                    $data['GROUP'] = 'Har.lepas';
                } else if ($row['IDJobGroup'] == 'LL') {
                    $data['GROUP'] = 'Lain-lain';
                }

                $data['PresenceDate'] = $PresenceDate;
                $data['DayOfWeek'] = $DayOfWeek;
                $data['OvertimeIn'] = $OvertimeIn;
                $data['OvertimeOut'] = $OvertimeOut;
                $data['OvertimeTotalHour'] = $OvertimeTotalHour;


                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue($data['ID'] . "/" . $data['IDSPKL']);
                $objSheet->getCell('C' . $i)->setValue("'" . $data['IDEmployee']);
                $objSheet->getCell('D' . $i)->setValue($data['FullName']);
                $objSheet->getCell('E' . $i)->setValue($data['GROUP']);
                $objSheet->getCell('F' . $i)->setValue($data['PresenceDate']);
                $objSheet->getCell('G' . $i)->setValue($data['DayOfWeek']);
                $objSheet->getCell('H' . $i)->setValue($data['OvertimeIn']);
                $objSheet->getCell('I' . $i)->setValue($data['OvertimeOut']);
                $objSheet->getCell('J' . $i)->setValue($data['OvertimeTotalHour']);
            }
            $i++;
            $objSheet->getStyle('A' . $i . ':J' . $i)->getFont()->setBold(true)->setSize(10);
        }

        // create some borders
        // first, create the whole grid around the table
        $objSheet->getStyle('A6:J' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A6:J' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A6:J6')->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        if ($ext == ".xlsx") {
            // Save it as an excel 2007 file
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            // Save it as an PDF file
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }

        ob_end_clean();
        $objWriter->save($path_file . "detailovertimereport" . $ext);
        $data = file_get_contents($path_file . "detailovertimereport" . $ext);
        force_download("detailovertimereport" . "-" . date('d-m-Y h:i') . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */



