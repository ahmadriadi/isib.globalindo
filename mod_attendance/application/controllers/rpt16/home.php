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

        $query = $this->employee->get_rjob_field()->result();
	$i = 0;
	foreach ($query as $r) {
	    $i++;
	    $data['default']['f04'][$i]['value'] = $r->IDJobGroup;
	    $data['default']['f04'][$i]['display'] = $r->GroupName;   
	}
        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));
        
        $idmenu                    = "111";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt16/home', $data);
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

    function iframedata($group, $from, $until, $name = '') {
        $dfrom = date('Y-m-d', strtotime($from));
        $duntil = date('Y-m-d', strtotime($until));
        $data['url'] = site_url('rpt16/home/reportdata/' . $group . '/' . $dfrom . '/' . $duntil . '/' . $name);
        $this->load->view('rpt16/iframe', $data);
    }

    function reportdata($group, $from, $until, $name = '') {
        $resuldata = $this->report->report_overtime($from, $until, $group, $name);
        $cekdata = ($resuldata !== null) ? $resuldata : 'empty';
        if ($cekdata !== 'empty') {
            $ng = $this->libfun->get_name_group($group); 
            $data['fromdate'] = $from;
            $data['untildate'] = $until;
            $data['jobgroup'] = $ng;
            $data['fulname'] = $name;
            $data['resultdata'] = $resuldata;
            $data['url_excel'] = site_url('rpt16/home/excel' . '/' . $group . '/' . $from . '/' . $until . '/' . $name);

            $this->load->view('rpt16/report', $data);
        }
    }

    function excel($group, $fromdate, $untildate, $nip) {
        //error_reporting(0);
        $resuldata = $this->report->report_overtime($fromdate, $untildate, $group, $nip);
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
        $objSheet->setTitle('summary overtime field report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4

        $objSheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(10);
        // write header
        // write header
        $objSheet->getCell('A1')->setValue('No');
        $objSheet->getCell('B1')->setValue('NIP');
        $objSheet->getCell('C1')->setValue('Nama');
        $objSheet->getCell('D1')->setValue('Jumlah Lembur');


        $i = 0;
        $n = 0;
        $counter = 1;

        $Summary_OverTime = 0;
        $Lastemp_IDEmployee = '';
        $lastemp_FullName = '';

        $day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        if ($resuldata != NULL) {
            foreach ($resuldata as $row) {

                if ($Lastemp_IDEmployee <> $row['IDEmployee'] AND $lastemp_FullName != $row['FullName'] AND $n > 0) {
                    $counter++;
                    $i++;
                    $objSheet->getCell('A' . $counter)->setValue($i);
                    $objSheet->getCell('B' . $counter)->setValue("'" . $Lastemp_IDEmployee);
                    $objSheet->getCell('C' . $counter)->setValue($lastemp_FullName);
                    $objSheet->getCell('D' . $counter)->setValue($Summary_OverTime);
                    $Summary_OverTime = 0;
                }

                $n++;

                $IDJobGroup = $row['IDJobGroup'];
                //$WorkDay = $row['WorkDay'];
                $DescDay = $row['Description'];

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
                

                if (is_null($OvertimeIn) OR $OvertimeIn == '0000-00-00 00:00:00') {
                    $OvertimeIn = "00-00-0000 00:00";
                    $OvertimeHour = 0;
                } else {
                    if (is_null($OvertimeOut) OR $OvertimeOut == '0000-00-00 00:00:00') {
                        $OvertimeOut = "00-00-0000 00:00";
                        $OvertimeHour = 0;
                    } else {

                        $OvertimeIn = date('d-m-Y H:i', strtotime($row['OvertimeIn']));
                        $OvertimeOut = date('d-m-Y H:i', strtotime($row['OvertimeOut']));
                        $OvertimeHour = $this->libfun->subs_time($OvertimeIn, $OvertimeOut, 30);
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
                    
                    if ($IDJobGroup == 'LT') {
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


                $Summary_OverTime+= $OvertimeTotalHour;
                $Lastemp_IDEmployee = $row['IDEmployee'];
                $lastemp_FullName = $row['FullName'];
            }
            $counter++;
            $objSheet->getStyle('A' . $counter . ':D' . $counter);
            $objSheet->getCell('A' . $counter)->setValue($i + 1);
            $objSheet->getCell('B' . $counter)->setValue("'" . $Lastemp_IDEmployee);
            $objSheet->getCell('C' . $counter)->setValue($lastemp_FullName);
            $objSheet->getCell('D' . $counter)->setValue($Summary_OverTime);
        }

        // create some borders
        // first, create the whole grid around the table
        $objSheet->getStyle('A1:D' . $counter)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A1:D' . $counter)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A1:D1' . $counter)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        if ($ext == ".xlsx") {
            // Save it as an excel 2007 file
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            // Save it as an PDF file
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
        ob_end_clean();
        $objWriter->save($path_file . "summaryovertimefield -" . $group . " -report" . $ext);
        $data = file_get_contents($path_file . "summaryovertimefield -" . $group . " -report" . $ext);
        force_download("summaryovertimefield -" . $group . " -report" . "-" . date('d-m-Y h:i') . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */




