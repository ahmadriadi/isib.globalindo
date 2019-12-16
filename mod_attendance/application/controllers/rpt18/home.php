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
        
        $idmenu                    = "128";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('rpt18/home', $data);
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

         $result = $this->report->report_incomplete_form($dfrom, $duntil, $group, $name);
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
        $data['url'] = site_url('rpt18/home/reportdata/' . $group . '/' . $dfrom . '/' . $duntil . '/' . $name);
        $this->load->view('rpt18/iframe', $data);
    }

    function reportdata($group, $from, $until, $name = '') {
        $result = $this->report->report_incomplete_form($from, $until, $group, $name);
        $dataday = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
         $i =0;
         foreach ($result as $row){
              $i++;
              $nip    = $row['IDEmployee'];
              $fname   = $row['FullName'];
              $group  = $row['IDJobGroup'];
              $date   = $row['IncompleteDate'];
              $day    = $dataday[date('w', strtotime($row['IncompleteDate']))];
              $in     = $row['TimeIn'];
              $out    = $row['TimeOut'];
              $desc   = $row['Note'];                   
              $gname = $this->libfun->get_name_group($group); 
              
                $table = "<tr width=\"100%\">";
                $table.= "    <td align=\"center\">" . $i . "</td>";
                $table.= "    <td align=\"left\">" . $nip . "</td>";
                $table.= "    <td align=\"left\">" . $fname . "</td>";
                $table.= "    <td align=\"left\">" . $gname . "</td>";
                $table.= "    <td align=\"center\">" . $date . "</td>";
                $table.= "    <td align=\"left\">" . $day . "</td>";
                $table.= "    <td align=\"center\">" . $in . "</td>";
                $table.= "    <td align=\"center\">" . $out . "</td>";
                $table.= "    <td align=\"left\">" . $desc . "</td>";
                $table.= "</tr>";
                
                $data['report'][$i]['table'] =$table;
              
             }
            $data['url_excel'] = site_url('rpt18/home/excel' . '/' . $group . '/' . $from . '/' . $until . '/' . $name);
            $this->load->view('rpt18/report', $data);
        }
    

    function excel($group, $fromdate, $untildate, $nip) {
        ini_set('memory_limit', '-1'); // for unlimited size from file excel	
        $result = $this->report->report_incomplete_form($fromdate, $untildate, $group, $nip);
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
        $objSheet->setTitle('incomplete');

        
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('INCOMPLETE EMPLOYEE REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)));
        
        $objSheet->getCell('A9')->setValue('ID');
        $objSheet->getCell('B9')->setValue('IDEmployee');
        $objSheet->getCell('C9')->setValue('FullName');
        $objSheet->getCell('D9')->setValue('Group');
        $objSheet->getCell('E9')->setValue('Date');
        $objSheet->getCell('F9')->setValue('Day');
        $objSheet->getCell('G9')->setValue('Time In');
        $objSheet->getCell('H9')->setValue('Time Out');
        $objSheet->getCell('I9')->setValue('Description');
        
        $i = 9;
        $n = $m = 0;       
        $dataday = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        
            foreach ($result as $row) {
                $n++;
                $i++;
                $nip    = $row['IDEmployee'];
                $name   = $row['FullName'];
                $group  = $row['IDJobGroup'];
                $date   = $row['IncompleteDate'];
                $day    = $dataday[date('w', strtotime($row['IncompleteDate']))];
                $in     = $row['TimeIn'];
                $out    = $row['TimeOut'];
                $desc   = $row['Note'];                
                $gname = $this->libfun->get_name_group($group);     
                
                $objSheet->getCell('A' . $i)->setValue($n);
                $objSheet->getCell('B' . $i)->setValue("'".$nip);
                $objSheet->getCell('C' . $i)->setValue($name);
                $objSheet->getCell('D' . $i)->setValue($gname);
                $objSheet->getCell('E' . $i)->setValue($date);
                $objSheet->getCell('F' . $i)->setValue($day);
                $objSheet->getCell('G' . $i)->setValue($in);
                $objSheet->getCell('H' . $i)->setValue($out);
                $objSheet->getCell('I' . $i)->setValue($desc);
            }
            $i++;
        
        $objSheet->getStyle('A8:I' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:I' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:I' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
        
	ob_end_clean();
        $objWriter->save($path_file . "incomplete form" . $ext);
        $data = file_get_contents($path_file . "incomplete form" . $ext);
        force_download("incomplete form" . $ext, $data);
        
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */




