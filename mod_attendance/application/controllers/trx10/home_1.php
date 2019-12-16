<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('learnpermit_model', 'learnpermit');
        $this->load->model('Employee_model', 'employee');
        $this->load->model('logs_model', 'logs');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('param_model', 'param');
        $this->load->model('uac_model', 'uac');   

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $fromd = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');
        $check1 = ($fromd == '' or $fromd == null) ? 'empty' : 'exist';
        $check2 = ($untild == '' or $untild == null) ? 'empty' : 'exist';

       

        if ($check1 == 'empty' and $check2 == 'empty') {
            $date = $this->periodpayroll();
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
        
        $idmenu                    = "148";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        
        $this->load->view('trx10/home', $data);
    }

    function datalearnpermit() {
      
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
                 echo $this->learnpermit->learnpermitforhrd($f, $u);
            } else if ($role == '0' and $param == 'Y') {
                 echo $this->learnpermit->learnpermitforhrd($f, $u);
            }
        }   
    }
    
    function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '148';
        $row        = $this->uac->getdata_button($this->User,$idmenu,$button);
        $check      = ($row ==null or $row =='')?'empty':'exist';
        
        if($check !=='empty'){
                $access = $row->kdbutton;  
                $mesg = "Result Button";
                $valid = 'true';
        }else{           
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

    function periodpayroll() {
        $today = array('Year' => date('Y'), 'Month' => date('m'));
        $untildate = date('d-m-Y', strtotime($today['Year'] . "-" . $today['Month'] . "-24"));
        $from = date('d-m-Y', strtotime("-1 month +1 day", strtotime($untildate)));
        return $from . " " . $untildate;
    }

    function set_pattern_date() {
        $valid = "true";

        $fromdate = date('Y-m-d', strtotime($this->input->post('fromdate')));
        $untildate = date('Y-m-d', strtotime($this->input->post('untildate')));
        $this->session->set_userdata('fromdate', $fromdate);
        $this->session->set_userdata('untildate', $untildate);

        echo '{ "valid":"' . $valid . '"}';
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
        
        $data['default']['f05'][0]['disabled'] = '';
        $data['default']['f05'][0]['value'] = '0';        
        $data['default']['f05'][0]['display'] = 'Sun';        
        $data['default']['f05'][1]['disabled'] = '';
        $data['default']['f05'][1]['value'] = '1';        
        $data['default']['f05'][1]['display'] = 'Mon';
        $data['default']['f05'][2]['disabled'] = '';
        $data['default']['f05'][2]['value'] = '2';        
        $data['default']['f05'][2]['display'] = 'Tue';
        $data['default']['f05'][3]['disabled'] = '';
        $data['default']['f05'][3]['value'] = '3';        
        $data['default']['f05'][3]['display'] = 'Wed';
        $data['default']['f05'][4]['disabled'] = '';
        $data['default']['f05'][4]['value'] = '4';        
        $data['default']['f05'][4]['display'] = 'Thu';
        $data['default']['f05'][5]['disabled'] = '';
        $data['default']['f05'][5]['value'] = '5';        
        $data['default']['f05'][5]['display'] = 'Fri';
        $data['default']['f05'][5]['disabled'] = '';
        $data['default']['f05'][5]['value'] = '6';        
        $data['default']['f05'][5]['display'] = 'Sat';
        
        
        $data['default']['f06'] = ''; //Untildate 
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
            $f01 = $this->input->post('f01');
            $f03 = date('Y-m-d', strtotime($this->input->post('f03')));
            $f04 = date('Y-m-d', strtotime($this->input->post('f04')));
            $f05 = $this->input->post('f05');
            $f06 = $this->input->post('f06');
            $f07 = $this->input->post('f07');

            $record = array(
                'IDEmployee' => $f01,
                'PermissionDate' => $f03,
                'UntilDate' => $f04,
                'OnDay' => $f05,
                'WorkHourDay' => $f06,
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


            $this->learnpermit->insert($record);
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
        $row = $this->learnpermit->get_by_id($id);

        $data['default']['f01'] = $row->IDEmployee;
        $data['default']['f02'] = $row->FullName;
        $data['default']['f03'] = date('d-m-Y', strtotime($row->PermissionDate));
        $data['default']['f04'] = date('d-m-Y', strtotime($row->UntilDate));
        
        $days = $row->OnDay;
        
        if($days=='0'){
            $data['default']['f05'][0]['value'] = '0';        
            $data['default']['f05'][0]['display'] = 'Sun'; 
            $data['default']['f05'][0]['checked']='CHECKED';
            $data['default']['f05'][1]['disabled'] = '';
            $data['default']['f05'][1]['value'] = '1';        
            $data['default']['f05'][1]['display'] = 'Mon';
            $data['default']['f05'][2]['disabled'] = '';
            $data['default']['f05'][2]['value'] = '2';        
            $data['default']['f05'][2]['display'] = 'Tue';
            $data['default']['f05'][3]['disabled'] = '';
            $data['default']['f05'][3]['value'] = '3';        
            $data['default']['f05'][3]['display'] = 'Wed';
            $data['default']['f05'][4]['disabled'] = '';
            $data['default']['f05'][4]['value'] = '4';        
            $data['default']['f05'][4]['display'] = 'Thu';
            $data['default']['f05'][5]['disabled'] = '';
            $data['default']['f05'][5]['value'] = '5';        
            $data['default']['f05'][5]['display'] = 'Fri';
            $data['default']['f05'][5]['disabled'] = '';
            $data['default']['f05'][5]['value'] = '6';        
            $data['default']['f05'][5]['display'] = 'Sat';          
        }else if($days=='01'){
            $data['default']['f05'][0]['value'] = '0';        
            $data['default']['f05'][0]['display'] = 'Sun'; 
            $data['default']['f05'][0]['checked']='CHECKED';
            $data['default']['f05'][1]['disabled'] = '';
            $data['default']['f05'][1]['value'] = '1';
            $data['default']['f05'][1]['checked']='CHECKED';
            $data['default']['f05'][1]['display'] = 'Mon';
            $data['default']['f05'][2]['disabled'] = '';
            $data['default']['f05'][2]['value'] = '2';        
            $data['default']['f05'][2]['display'] = 'Tue';
            $data['default']['f05'][3]['disabled'] = '';
            $data['default']['f05'][3]['value'] = '3';        
            $data['default']['f05'][3]['display'] = 'Wed';
            $data['default']['f05'][4]['disabled'] = '';
            $data['default']['f05'][4]['value'] = '4';        
            $data['default']['f05'][4]['display'] = 'Thu';
            $data['default']['f05'][5]['disabled'] = '';
            $data['default']['f05'][5]['value'] = '5';        
            $data['default']['f05'][5]['display'] = 'Fri';
            $data['default']['f05'][5]['disabled'] = '';
            $data['default']['f05'][5]['value'] = '6';        
            $data['default']['f05'][5]['display'] = 'Sat';              
        }else if($days=='012'){
            $data['default']['f05'][0]['value'] = '0';        
            $data['default']['f05'][0]['display'] = 'Sun'; 
            $data['default']['f05'][0]['checked']='CHECKED';
            $data['default']['f05'][1]['disabled'] = '';
            $data['default']['f05'][1]['value'] = '1';
            $data['default']['f05'][1]['checked']='CHECKED';
            $data['default']['f05'][1]['display'] = 'Mon';
            $data['default']['f05'][2]['disabled'] = '';
            $data['default']['f05'][2]['value'] = '2'; 
            $data['default']['f05'][2]['checked']='CHECKED';
            $data['default']['f05'][2]['display'] = 'Tue';
            $data['default']['f05'][3]['disabled'] = '';
            $data['default']['f05'][3]['value'] = '3';        
            $data['default']['f05'][3]['display'] = 'Wed';
            $data['default']['f05'][4]['disabled'] = '';
            $data['default']['f05'][4]['value'] = '4';        
            $data['default']['f05'][4]['display'] = 'Thu';
            $data['default']['f05'][5]['disabled'] = '';
            $data['default']['f05'][5]['value'] = '5';        
            $data['default']['f05'][5]['display'] = 'Fri';
            $data['default']['f05'][5]['disabled'] = '';
            $data['default']['f05'][5]['value'] = '6';        
            $data['default']['f05'][5]['display'] = 'Sat';  
            
        }else if($days=='0123'){
            $data['default']['f05'][0]['value'] = '0';        
            $data['default']['f05'][0]['display'] = 'Sun'; 
            $data['default']['f05'][0]['checked']='CHECKED';
            $data['default']['f05'][1]['disabled'] = '';
            $data['default']['f05'][1]['value'] = '1';
            $data['default']['f05'][1]['checked']='CHECKED';
            $data['default']['f05'][1]['display'] = 'Mon';
            $data['default']['f05'][2]['disabled'] = '';
            $data['default']['f05'][2]['value'] = '2'; 
            $data['default']['f05'][2]['checked']='CHECKED';
            $data['default']['f05'][2]['display'] = 'Tue';
            $data['default']['f05'][3]['disabled'] = '';
            $data['default']['f05'][3]['value'] = '3'; 
            $data['default']['f05'][3]['checked']='CHECKED';
            $data['default']['f05'][3]['display'] = 'Wed';
            $data['default']['f05'][4]['disabled'] = '';
            $data['default']['f05'][4]['value'] = '4';        
            $data['default']['f05'][4]['display'] = 'Thu';
            $data['default']['f05'][5]['disabled'] = '';
            $data['default']['f05'][5]['value'] = '5';        
            $data['default']['f05'][5]['display'] = 'Fri';
            $data['default']['f05'][5]['disabled'] = '';
            $data['default']['f05'][5]['value'] = '6';        
            $data['default']['f05'][5]['display'] = 'Sat';  
            
        }else if($days=='01234'){
            $data['default']['f05'][0]['value'] = '0';        
            $data['default']['f05'][0]['display'] = 'Sun'; 
            $data['default']['f05'][0]['checked']='CHECKED';
            $data['default']['f05'][1]['disabled'] = '';
            $data['default']['f05'][1]['value'] = '1';
            $data['default']['f05'][1]['checked']='CHECKED';
            $data['default']['f05'][1]['display'] = 'Mon';
            $data['default']['f05'][2]['disabled'] = '';
            $data['default']['f05'][2]['value'] = '2'; 
            $data['default']['f05'][2]['checked']='CHECKED';
            $data['default']['f05'][2]['display'] = 'Tue';
            $data['default']['f05'][3]['disabled'] = '';
            $data['default']['f05'][3]['value'] = '3'; 
            $data['default']['f05'][3]['checked']='CHECKED';
            $data['default']['f05'][3]['display'] = 'Wed';
            $data['default']['f05'][4]['disabled'] = '';
            $data['default']['f05'][4]['value'] = '4';        
            $data['default']['f05'][4]['display'] = 'Thu';
            $data['default']['f05'][5]['disabled'] = '';
            $data['default']['f05'][5]['value'] = '5';        
            $data['default']['f05'][5]['display'] = 'Fri';
            $data['default']['f05'][5]['disabled'] = '';
            $data['default']['f05'][5]['value'] = '6';        
            $data['default']['f05'][5]['display'] = 'Sat';  
            
        }
        
        $data['default']['f06'] = $row->WorkHourDay;
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
            $f01 = $this->input->post('f01');
            $f03 = date('Y-m-d', strtotime($this->input->post('f03')));
            $f04 = $this->input->post('f04');

            $record = array(
                'IDEmployee' => $f01,
                'LearningDate' => $f03,
                'Note' => trim($f04),
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

            $this->learnpermit->update($id, $record);
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

        $this->learnpermit->update($id, $record);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function exportdata() {
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
        $objSheet->setTitle('learnpermit report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(12);

        // write header        
        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');
        $objSheet->getCell('C1')->setValue('Group');
        $objSheet->getCell('D1')->setValue('Learning Date');
        $objSheet->getCell('E1')->setValue('Note');


        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->learnpermit->getall_data($f, $u);


        if ($result !== 'empty') {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $group = $row['IDJobGroup'];
                if ($group == 'ST') {
                    $ng = 'STAFF';
                } else if ($group == 'LT') {
                    $ng = 'LAPANGAN TETAP';
                } else if ($group == 'LK') {
                    $ng = 'LAPANGAN KONTRAK';
                } else if ($group == 'HL') {
                    $ng = 'HARIAN LEPAS';
                } else {
                    $ng = 'LAIN-LAIN';
                }

                $objSheet->getCell('A' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('B' . $i)->setValue($row['Name']);
                $objSheet->getCell('C' . $i)->setValue($ng);
                $objSheet->getCell('D' . $i)->setValue($row['LearningDate']);
                $objSheet->getCell('E' . $i)->setValue($row['Note']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:E' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:E' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:E1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);


            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "learnpermit_attendance" . $ext);
            $data = file_get_contents($path_file . "learnpermit_attendance" . $ext);
            force_download("learnpermit_attendance" . $ext, $data);
        }
    }

}
