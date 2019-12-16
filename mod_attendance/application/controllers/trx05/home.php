<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('employee_model', 'employee');
        $this->load->model('rawdata_model', 'rawdata');
        $this->load->model('logs_model', 'logs');
        $this->load->model('libraryfunction_model','libfun');


        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

   function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }


    function index() {
        $fromd = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');
        $check1 = ($fromd == '' or $fromd == null) ? 'empty' : 'exist';
        $check2 = ($untild == '' or $untild == null) ? 'empty' : 'exist';
        $query = $this->employee->get_rjob()->result();
        $i = 0;
        foreach ($query as $r) {
            $i++;
            $data['default']['group'][$i]['value'] = $r->IDJobGroup;
            $data['default']['group'][$i]['display'] = $r->GroupName;   
        }
        

        $data['test1'] = $fromd;
        $data['test2'] = $untild;

        if ($check1 == 'empty' and $check2 == 'empty') {
            $date = $this->libfun->periode_work();
            $fromdate = substr($date, 0, 10);
            $untildate = substr($date, 11, 10);
            $this->session->set_userdata('datefrom', $fromdate);
            $this->session->set_userdata('dateuntil', $untildate);
        } else {
            $fromdate = $this->session->userdata('fromdate');
            $untildate = $this->session->userdata('untildate');
            $this->session->set_userdata('datefrom', $fromdate);
            $this->session->set_userdata('dateuntil', $untildate);
        }

        $data['default']['from'] = date('d-m-Y', strtotime($fromdate));
        $data['default']['until'] = date('d-m-Y', strtotime($untildate));

        $this->load->view('trx05/home', $data);
    }

  
    function set_pattern_date() {
        $valid = "true";

        $fromdate = date('Y-m-d', strtotime($this->input->post('fromdate')));
        $untildate = date('Y-m-d', strtotime($this->input->post('untildate')));
        $this->session->set_userdata('fromdate', $fromdate);
        $this->session->set_userdata('untildate', $untildate);

        echo '{ "valid":"' . $valid . '"}';
    }

    function datarawdata() {
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        echo $this->rawdata->allrawdata($f, $u);
    }

    function excel($g) {
        ini_set('memory_limit', '-1'); // for unlimited size  
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
        $objSheet->setTitle('rawdata report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('DataText');
        $objSheet->getCell('B1')->setValue('IDCard');
        $objSheet->getCell('C1')->setValue('IDEmployee');
        $objSheet->getCell('D1')->setValue('FullName');
        $objSheet->getCell('E1')->setValue('JobGroup');
        $objSheet->getCell('F1')->setValue('AbsenStatus');
        $objSheet->getCell('G1')->setValue('AbsenLocation');
        $objSheet->getCell('H1')->setValue('Date');
        $objSheet->getCell('I1')->setValue('Time');
        
        
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->rawdata->getall_data($f,$u,$g);
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';

        if ($checkdata == 'exist') {
            $i = 1;
            foreach ($result as $row) {
                $i++;
                
                $location = ($row['Location']=='1')?'KAPUK':'BITUNG';
                $status = ($row['Direction']=='1')?'IN':'OUT';
                
                $group = $this->libfun->get_name_group($row['IDJobGroup']);
                
                
                $objSheet->getCell('A' . $i)->setValue("'" . $row['DataText']);
                $objSheet->getCell('B' . $i)->setValue("'" . $row['IDCard']);
                $objSheet->getCell('C' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('D' . $i)->setValue($row['FullName']);
                $objSheet->getCell('E' . $i)->setValue($group);
                $objSheet->getCell('F' . $i)->setValue($status);
                $objSheet->getCell('G' . $i)->setValue($location);
                $objSheet->getCell('H' . $i)->setValue(date('d-m-Y', strtotime($row['PresenceDate'])));
                $objSheet->getCell('I' . $i)->setValue($row['PresenceTime']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:I' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:I' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:I1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);
            $objSheet->getColumnDimension('F')->setAutoSize(true);
            $objSheet->getColumnDimension('G')->setAutoSize(true);
            $objSheet->getColumnDimension('H')->setAutoSize(true);
            $objSheet->getColumnDimension('I')->setAutoSize(true);


            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "rawdata_attendance" . $ext);
            $data = file_get_contents($path_file . "rawdata_attendance" . $ext);
            force_download("rawdata_attendance" . $ext, $data);
        }
    }

}

