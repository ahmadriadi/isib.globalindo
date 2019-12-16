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

        $data['default']['f02'][1]['value'] = "ALL";
        $data['default']['f02'][1]['display'] = "ALL";
        $data['default']['f02'][1]['checked'] = "CHECKED";
        $data['default']['f02'][2]['value'] = "A";
        $data['default']['f02'][2]['display'] = "ACTIVE";
        $data['default']['f02'][3]['value'] = "P";
        $data['default']['f02'][3]['display'] = "PASSIVE";

        $idmenu = "106";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('rpt22/home', $data);
    }

    function presencedata($date, $group) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $date = date('Y-m-d', strtotime($date));
        $result = $this->report->report_countemployee($date, $group);
        if ($result !== 'empty') {
            $valid = "true";
            $json = '{ "mesg":"' . 'Data Already Exist' . '",                                   
                       "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        } else {
            $valid = "false";
            $json = '{ "mesg":"' . 'Sorry no result data job : ' . $group . 'on periode date' . $date . '",                                   
                      "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        }
    }

    function iframedata($date, $group) {
        $date = date('Y-m-d', strtotime($date));
        $data['url'] = site_url('rpt22/home/reportdata/' . $date . '/' . $group);
        $this->load->view('rpt22/iframe', $data);
    }

    function reportdata($date, $group) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $date = date('Y-m-d', strtotime($date));
        $resulactive = $this->report->report_countemployee_active($date);
        $resulpassive = $this->report->report_countemployee_passive($date);
        if ($resuldata !== 'empty') {
            $data['date'] = $date;

            if ($group == 'ALL') {
                $filter = 'ALL';
                $rpt = 'rpt22/report_all';
                $excel = 'rpt22/home/excel_all';
                $data['resultactive'] = $resulactive;
                $data['resultpassive'] = $resulpassive;
            } else if ($group == 'A') {
                $filter = 'ACTIVE';
                $rpt = 'rpt22/report_active';
                $excel = 'rpt22/home/excel_active';
                $data['resultactive'] = $resulactive;
            } else if ($group == 'P') {
                $filter = 'PASSIVE';
                $rpt = 'rpt22/report_passive';
                $excel = 'rpt22/home/excel_passive';
                $data['resultpassive'] = $resulpassive;
            }


            $data['jobgroup'] = $filter;
            $data['url_excel'] = site_url($excel) . "/" . $date . '/' . $group;
            $this->load->view($rpt, $data);
        }
    }

    function excel_all($date, $group) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel

        if ($group == 'ALL') {
            $filter = 'ALL';
        } else if ($group == 'A') {
            $filter = 'ACTIVE';
        } else if ($group == 'P') {
            $filter = 'PASSIVE';
        }

        $date = date('Y-m-d', strtotime($date));
        $resulactive = $this->report->report_countemployee_active($date);
        $resulpassive = $this->report->report_countemployee_passive($date);

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
        $objSheet->setTitle('count data employee');

        $objSheet->getStyle('A6:L8')->getFont()->setBold(true)->setSize(10);


        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('LIST OF EMPLOYEE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($date)));
        $objSheet->getCell('A5')->setValue('FILTER');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($filter);

        $objSheet->getCell('A6')->setValue('LIST OF EMPLOYEE');

        $objSheet->getCell('A7')->setValue('Employee Active');
        $objSheet->getCell('G7')->setValue('Empoyee Passive');

        $objSheet->getCell('A8')->setValue('No');
        $objSheet->getCell('B8')->setValue('IDEmployee');
        $objSheet->getCell('C8')->setValue('Fullname');
        $objSheet->getCell('D8')->setValue('Group');
        $objSheet->getCell('E8')->setValue('Departement');
        $objSheet->getCell('F8')->setValue('Hiredate');

        $objSheet->getCell('G8')->setValue('No');
        $objSheet->getCell('H8')->setValue('IDEmployee');
        $objSheet->getCell('I8')->setValue('Fullname');
        $objSheet->getCell('J8')->setValue('Group');
        $objSheet->getCell('K8')->setValue('Departement');
        $objSheet->getCell('L8')->setValue('Resigndate');


       

          // add mergecell
          $sheet = $objPHPExcel->getActiveSheet();
          $sheet->mergeCells('A6:L6');
          $sheet->mergeCells('A7:F7');
          $sheet->mergeCells('G7:L7');

          //add center
          $sheet->getStyle('A6:L6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('A7:F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('G7:L7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('G8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('H8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('I8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('J8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('K8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('L8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



          //add border
          $objSheet->getStyle('A6:L6')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('A7:F7')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('G7:L7')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('A8:A8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('B8:B8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('C8:C8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('D8:D8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('E8:E8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('F8:F8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('G8:G8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('H8:H8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('I8:I8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('J8:J8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('K8:K8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('L8:L8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

       
        $i = 8;
        $nh = $nr = 0;
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

        foreach ($resulpassive as $row) {
            $i++;

            if ($resulactive != 'empty') {
                $no = 8;
                $nohire = 0;
                foreach ($resulactive as $row) {
                    $nip = $row['IDEmployee'];
                    $name = $row['FullName'];
                    $group = $this->libfun->get_name_group($row['IDJobGroup']);
                    $unit = $row['DescStructure'];
                    $hiredate = $row['HireDate'];
                    $nohire++;
                    $no++;

                    $objSheet->getCell('A' . $no)->setValue($nohire);
                    $objSheet->getCell('B' . $no)->setValue("'" . $nip);
                    $objSheet->getCell('C' . $no)->setValue($name);
                    $objSheet->getCell('D' . $no)->setValue($group);
                    $objSheet->getCell('E' . $no)->setValue($unit);
                    $objSheet->getCell('F' . $no)->setValue($hiredate);
                }
            }

            if ($resulpassive != 'empty') {
                 $no = 8;
                $noresign = 0;
                foreach ($resulpassive as $row) {
                    $no++;
                    $nip = $row['IDEmployee'];
                    $name = $row['FullName'];
                    $group = $this->libfun->get_name_group($row['IDJobGroup']);
                    $unit = $row['DescStructure'];
                    $resigndate = $row['ResignDate'];
                    $noresign++;

                    $objSheet->getCell('G' . $no)->setValue($noresign);
                    $objSheet->getCell('H' . $no)->setValue("'" . $nip);
                    $objSheet->getCell('I' . $no)->setValue($name);
                    $objSheet->getCell('J' . $no)->setValue($group);
                    $objSheet->getCell('K' . $no)->setValue($unit);
                    $objSheet->getCell('L' . $no)->setValue($resigndate);
                }
            }
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
        $objWriter->save($path_file . "count_employee_all" . $ext);
        $data = file_get_contents($path_file . "count_employee_all" . $ext);
        force_download("count_employee_all" . $ext, $data);
    }
    
    function excel_active($date, $group) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel

        if ($group == 'ALL') {
            $filter = 'ALL';
        } else if ($group == 'A') {
            $filter = 'ACTIVE';
        } else if ($group == 'P') {
            $filter = 'PASSIVE';
        }

        $date = date('Y-m-d', strtotime($date));
        $resulactive = $this->report->report_countemployee_active($date);

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
        $objSheet->setTitle('count data employee');

        $objSheet->getStyle('A6:F8')->getFont()->setBold(true)->setSize(10);


        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('LIST OF EMPLOYEE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($date)));
        $objSheet->getCell('A5')->setValue('FILTER');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($filter);

        $objSheet->getCell('A6')->setValue('LIST OF EMPLOYEE');

        $objSheet->getCell('A7')->setValue('Employee Active');

        $objSheet->getCell('A8')->setValue('No');
        $objSheet->getCell('B8')->setValue('IDEmployee');
        $objSheet->getCell('C8')->setValue('Fullname');
        $objSheet->getCell('D8')->setValue('Group');
        $objSheet->getCell('E8')->setValue('Departement');
        $objSheet->getCell('F8')->setValue('Hiredate');

          // add mergecell
          $sheet = $objPHPExcel->getActiveSheet();
          $sheet->mergeCells('A6:F6');
          $sheet->mergeCells('A7:F7');

          //add center
          $sheet->getStyle('A6:F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('A7:F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        


          //add border 
          $objSheet->getStyle('A6:F6')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('A8:F8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('A7:F7')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('A8:A8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('B8:B8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('C8:C8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('D8:D8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('E8:E8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('F8:F8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
         
       
        $nh = $nr = 0;
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

            if ($resulactive != 'empty') {
                $i = 8;
                $nohire = 0;
                foreach ($resulactive as $row) {
                    $nip = $row['IDEmployee'];
                    $name = $row['FullName'];
                    $group = $this->libfun->get_name_group($row['IDJobGroup']);
                    $unit = $row['DescStructure'];
                    $hiredate = $row['HireDate'];
                    $nohire++;
                    $i++;

                    $objSheet->getCell('A' . $i)->setValue($nohire);
                    $objSheet->getCell('B' . $i)->setValue("'" . $nip);
                    $objSheet->getCell('C' . $i)->setValue($name);
                    $objSheet->getCell('D' . $i)->setValue($group);
                    $objSheet->getCell('E' . $i)->setValue($unit);
                    $objSheet->getCell('F' . $i)->setValue($hiredate);
                }
            }

         
        $objSheet->getStyle('A8:F' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:F' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:F' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
        ob_end_clean();
        $objWriter->save($path_file . "count_employee_all" . $ext);
        $data = file_get_contents($path_file . "count_employee_all" . $ext);
        force_download("count_employee_all" . $ext, $data);
    }
    
    
     function excel_passive($date, $group) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel

        if ($group == 'ALL') {
            $filter = 'ALL';
        } else if ($group == 'A') {
            $filter = 'ACTIVE';
        } else if ($group == 'P') {
            $filter = 'PASSIVE';
        }

        $date = date('Y-m-d', strtotime($date));
        $resulpassive = $this->report->report_countemployee_passive($date);

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
        $objSheet->setTitle('count data employee');

        $objSheet->getStyle('A6:F8')->getFont()->setBold(true)->setSize(10);


        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('LIST OF EMPLOYEE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($date)));
        $objSheet->getCell('A5')->setValue('FILTER');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($filter);

        $objSheet->getCell('A6')->setValue('LIST OF EMPLOYEE');

        $objSheet->getCell('A7')->setValue('Employee Passive');

        $objSheet->getCell('A8')->setValue('No');
        $objSheet->getCell('B8')->setValue('IDEmployee');
        $objSheet->getCell('C8')->setValue('Fullname');
        $objSheet->getCell('D8')->setValue('Group');
        $objSheet->getCell('E8')->setValue('Departement');
        $objSheet->getCell('F8')->setValue('Hiredate');

          // add mergecell
          $sheet = $objPHPExcel->getActiveSheet();
          $sheet->mergeCells('A6:F6');
          $sheet->mergeCells('A7:F7');

          //add center
          $sheet->getStyle('A6:F6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('A7:F7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('A8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('B8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('C8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('D8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('E8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          $sheet->getStyle('F8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        


          //add border 
          $objSheet->getStyle('A6:F6')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('A8:F8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('A7:F7')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('A8:A8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('B8:B8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('C8:C8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('D8:D8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('E8:E8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
          $objSheet->getStyle('F8:F8')->getBorders()->
          getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
         
       
        $nh = $nr = 0;
        $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
        $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

            if ($resulpassive != 'empty') {
                $i = 8;
                $noresign = 0;
                foreach ($resulpassive as $row) {
                    $nip = $row['IDEmployee'];
                    $name = $row['FullName'];
                    $group = $this->libfun->get_name_group($row['IDJobGroup']);
                    $unit = $row['DescStructure'];
                    $resigndate = $row['ResignDate'];
                    $noresign++;
                    $i++;

                    $objSheet->getCell('A' . $i)->setValue($noresign);
                    $objSheet->getCell('B' . $i)->setValue("'" . $nip);
                    $objSheet->getCell('C' . $i)->setValue($name);
                    $objSheet->getCell('D' . $i)->setValue($group);
                    $objSheet->getCell('E' . $i)->setValue($unit);
                    $objSheet->getCell('F' . $i)->setValue($resigndate);
                }
            }

         
        $objSheet->getStyle('A8:F' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:F' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:F' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
        ob_end_clean();
        $objWriter->save($path_file . "count_employee_all" . $ext);
        $data = file_get_contents($path_file . "count_employee_all" . $ext);
        force_download("count_employee_all" . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */





