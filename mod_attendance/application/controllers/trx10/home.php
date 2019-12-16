<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Leavework_model', 'leavework');
        $this->load->model('Employee_model', 'employee');
        $this->load->model('logs_model', 'logs');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('param_model', 'param');
        $this->load->model('uac_model', 'uac');
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

        $idmenu = "148";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);

        $this->load->view('trx10/home', $data);
    }

    function dataleavework() {

        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');

        $idmodule = '83';
        $rowlogin = $this->login->get_by_user($this->User);
        $rowmenu = $this->access->get_by_idmenu($idmodule);
        $rowparam = $this->param->get_param($this->User);

        $check1 = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $check2 = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';

        $parameter = $rowparam->ParamValue;
        if ($parameter == $this->User) {
            $param = 'Y';
        } else {
            $param = 'N';
        }

        if ($check1 == 'exist' and $check2 == 'exist') {
            $role = $rowlogin->Role;
            if ($role == '1' or $role == '2') {
                echo $this->leavework->getdata_leavework($f, $u);
            } else if ($role == '0' and $param == 'Y') {
                echo $this->leavework->getdata_leavework($f, $u);
            }
        }
    }

    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '148';
        $row = $this->uac->getdata_button($this->User, $idmenu, $button);
        $check = ($row == null or $row == '') ? 'empty' : 'exist';

        if ($check !== 'empty') {
            $access = $row->kdbutton;
            $mesg = "Result Button";
            $valid = 'true';
        } else {
            $access = '';
            $mesg = "Result Is Null";
            $valid = 'false';
        }

        $json = '{ "mesg":"' . $mesg . '",
                   "btnaccess":"' . $access . '",
                   "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

   
    function set_pattern_date() {
        $valid = "true";

        $fromdate = date('Y-m-d', strtotime($this->input->post('fromdate')));
        $untildate = date('Y-m-d', strtotime($this->input->post('untildate')));
        $this->session->set_userdata('fromdate', $fromdate);
        $this->session->set_userdata('untildate', $untildate);

        echo '{ "valid":"' . $valid . '"}';
    }

    function autocomplete_employee() {
        $result = $this->employee->find_employee_active();
        $arr = array();
        foreach ($result->result() as $row) {
            $nip = $row->IDEmployee;
            $user = $this->User;
                if ($nip !== $user) { 
                    $arr[] = array('idemployee' => $row->IDEmployee,
                               'fullname' => strtoupper($row->FullName)
                ); 
                }
            
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
 
    function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->search_employee_active($q);
        $data['response'] = 'true';
        $data['message'] = array();
        foreach ($result->result() as $row) {
            $nip = $row->IDEmployee;
            $user = $this->User;
            if ($nip !== $user) {
                $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                    'value' => $row->FullName,
                    'idemployee' => $row->IDEmployee
                );
            }
        }
        echo json_encode($data);
    }

    function addnew() {
        $data['default']['f01'] = ''; //IDemployee
        $data['default']['f02'] = ''; //FullName
        $data['default']['f03'] = ''; //Fromdate      
        $data['default']['f04'] = ''; //Untildate      

        $data['default']['f05a']['disabled'] = '';
        $data['default']['f05a']['value'] = '0';
        $data['default']['f05a']['display'] = 'Sun';

        $data['default']['f05b']['disabled'] = '';
        $data['default']['f05b']['value'] = '1';
        $data['default']['f05b']['display'] = 'Mon';

        $data['default']['f05c']['disabled'] = '';
        $data['default']['f05c']['value'] = '2';
        $data['default']['f05c']['display'] = 'Tue';

        $data['default']['f05d']['disabled'] = '';
        $data['default']['f05d']['value'] = '3';
        $data['default']['f05d']['display'] = 'Wed';

        $data['default']['f05e']['disabled'] = '';
        $data['default']['f05e']['value'] = '4';
        $data['default']['f05e']['display'] = 'Thu';

        $data['default']['f05f']['disabled'] = '';
        $data['default']['f05f']['value'] = '5';
        $data['default']['f05f']['display'] = 'Fri';

        $data['default']['f05g']['disabled'] = '';
        $data['default']['f05g']['value'] = '6';
        $data['default']['f05g']['display'] = 'Sat';


        $data['default']['f06'] = '';
        $data['default']['f07'] = 'KULIAH / KURSUS / TRAINING'; //Note      

        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('trx10/home/addpost');
        $this->load->view('trx10/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f03', 'From Date', 'required');
        $this->form_validation->set_rules('f04', 'Until Date', 'required');

        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f03 = date('Y-m-d', strtotime($this->input->post('f03')));
            $f04 = date('Y-m-d', strtotime($this->input->post('f04')));
            $f05a = $this->anti_xss($this->input->post('f05a'));
            $f05b = $this->anti_xss($this->input->post('f05b'));
            $f05c = $this->anti_xss($this->input->post('f05c'));
            $f05d = $this->anti_xss($this->input->post('f05d'));
            $f05e = $this->anti_xss($this->input->post('f05e'));
            $f05f = $this->anti_xss($this->input->post('f05f'));
            $f05g = $this->anti_xss($this->input->post('f05g'));
            $f06 = $this->anti_xss($this->input->post('f06'));
            $f07 = $this->anti_xss($this->input->post('f07'));

            $record = array(
                'IDEmployee' => $f01,
                'StartDate' => $f03,
                'FinishDate' => $f04,
                'Day0' => $f05a,
                'Day1' => $f05b,
                'Day2' => $f05c,
                'Day3' => $f05d,
                'Day4' => $f05e,
                'Day5' => $f05f,
                'Day6' => $f05g,
                'LeaveHour' => $f06,
                'Note' => trim($f07),
                'AddedBy' => $this->User,
                'AddedDate' => $this->Datetime,
                'AddedIP' => $this->Ip
            );

            $recordlog = array(
                'ID' => NULL,
                'username' => $this->User,
                'log_date' => $this->Datetime,
                'log_ip' => $this->Ip,
                'log_agent' => $this->Browser,
                'controller' => site_url('trx10/home/addnew'),
                'activities' => 'add new ' . $f01
            );


            $this->leavework->insert($record);
            $this->logs->insert($recordlog);

            $mesg = 'insert data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f03 = '';
            $err_f04 = '';
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",                      
                       "err_f03":"' . $err_f03 . '",       
                       "err_f04":"' . $err_f04 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->leavework->get_by_id($id);

        $data['default']['f01'] = $row->IDEmployee;
        $data['default']['f02'] = $row->FullName;
        $data['default']['f03'] = date('d-m-Y', strtotime($row->StartDate));
        $data['default']['f04'] = date('d-m-Y', strtotime($row->FinishDate));


        $data['default']['f05a']['disabled'] = '';
        $data['default']['f05a']['value'] = '0';
        $data['default']['f05a']['display'] = 'Sun';
        $data['default']['f05a']['checked'] = ($row->Day0 == null or $row->Day0 == '') ? '' : 'CHECKED';

        $data['default']['f05b']['disabled'] = '';
        $data['default']['f05b']['value'] = '1';
        $data['default']['f05b']['display'] = 'Mon';
        $data['default']['f05b']['checked'] = ($row->Day1 == null or $row->Day1 == '') ? '' : 'CHECKED';

        $data['default']['f05c']['disabled'] = '';
        $data['default']['f05c']['value'] = '2';
        $data['default']['f05c']['display'] = 'Tue';
        $data['default']['f05c']['checked'] = ($row->Day2 == null or $row->Day2 == '') ? '' : 'CHECKED';

        $data['default']['f05d']['disabled'] = '';
        $data['default']['f05d']['value'] = '3';
        $data['default']['f05d']['display'] = 'Wed';
        $data['default']['f05d']['checked'] = ($row->Day3 == null or $row->Day3 == '') ? '' : 'CHECKED';

        $data['default']['f05e']['disabled'] = '';
        $data['default']['f05e']['value'] = '4';
        $data['default']['f05e']['display'] = 'Thu';
        $data['default']['f05e']['checked'] = ($row->Day4 == null or $row->Day4 == '') ? '' : 'CHECKED';

        $data['default']['f05f']['disabled'] = '';
        $data['default']['f05f']['value'] = '5';
        $data['default']['f05f']['display'] = 'Fri';
        $data['default']['f05f']['checked'] = ($row->Day5 == null or $row->Day5 == '') ? '' : 'CHECKED';

        $data['default']['f05g']['disabled'] = '';
        $data['default']['f05g']['value'] = '6';
        $data['default']['f05g']['display'] = 'Sat';
        $data['default']['f05g']['checked'] = ($row->Day6 == null or $row->Day6 == '') ? '' : 'CHECKED';

        $data['default']['f06'] = $row->LeaveHour;
        $data['default']['f07'] = $row->Note;

        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';

        $data['url_post'] = site_url('trx10/home/editpost');
        $this->load->view('trx10/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f03', 'Learning Date', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f03 = date('Y-m-d', strtotime($this->input->post('f03')));
            $f04 = date('Y-m-d', strtotime($this->input->post('f04')));
            $f05a = $this->anti_xss($this->input->post('f05a'));
            $f05b = $this->anti_xss($this->input->post('f05b'));
            $f05c = $this->anti_xss($this->input->post('f05c'));
            $f05d = $this->anti_xss($this->input->post('f05d'));
            $f05e = $this->anti_xss($this->input->post('f05e'));
            $f05f = $this->anti_xss($this->input->post('f05f'));
            $f05g = $this->anti_xss($this->input->post('f05g'));
            $f06 = $this->anti_xss($this->input->post('f06'));
            $f07 = $this->anti_xss($this->input->post('f07'));

            $record = array(
                'IDEmployee' => $f01,
                'StartDate' => $f03,
                'FinishDate' => $f04,
                'Day0' => $f05a,
                'Day1' => $f05b,
                'Day2' => $f05c,
                'Day3' => $f05d,
                'Day4' => $f05e,
                'Day5' => $f05f,
                'Day6' => $f05g,
                'LeaveHour' => $f06,
                'Note' => trim($f07),
                'EditedBy' => $this->User,
                'EditedDate' => $this->Datetime,
                'EditedIP' => $this->Ip
            );

            $recordlog = array(
                'ID' => NULL,
                'username' => $this->User,
                'log_date' => $this->Datetime,
                'log_ip' => $this->Ip,
                'log_agent' => $this->Browser,
                'controller' => site_url('trx10/home/editdata'),
                'activities' => 'edit data ' . $f01
            );

            $this->leavework->update($id, $record);
            $this->logs->insert($recordlog);

            $mesg = 'update data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f03 = '';
            $err_f04 = '';
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",                      
                       "err_f03":"' . $err_f03 . '",     
                       "err_f04":"' . $err_f04 . '"' .
                '}';
        echo $json;
    }

    function delete($id) {
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );

        $this->leavework->update($id, $record);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function exportdata($g) {
        $ext = '.xlsx';
        $path_file = '/tmp/';

        $fromdate = date('Y-m-d', strtotime($fromdate));
        $untildate = date('Y-m-d', strtotime($untildate));

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
        $objSheet->setTitle('Permission to Leave Work Report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(12);

        // write header        
        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');
        $objSheet->getCell('C1')->setValue('Group');
        $objSheet->getCell('D1')->setValue('Permission Date');
        $objSheet->getCell('E1')->setValue('Until Date');
        $objSheet->getCell('F1')->setValue('On Days');
        $objSheet->getCell('G1')->setValue('Work Hours');
        $objSheet->getCell('H1')->setValue('Note');


        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->leavework->getall_data($f,$u,$g);


        if ($result !== 'empty') {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $group = $this->libfun->get_name_group($row['IDJobGroup']);

                $objSheet->getCell('A' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('B' . $i)->setValue($row['Name']);
                $objSheet->getCell('C' . $i)->setValue($group);
                $objSheet->getCell('D' . $i)->setValue($row['StartDate']);
                $objSheet->getCell('E' . $i)->setValue($row['FinishDate']);
                $objSheet->getCell('F' . $i)->setValue($row['OnDays']);
                $objSheet->getCell('G' . $i)->setValue($row['LeaveHour']);
                $objSheet->getCell('H' . $i)->setValue($row['Note']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:H' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:H' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:H1')->getBorders()->
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

            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "Permission_to_Leave_Work" . $ext);
            $data = file_get_contents($path_file . "Permission_to_Leave_Work" . $ext);
            force_download("Permission_to_Leave_Work" . $ext, $data);
        }
    }

}
