<?php
//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        
        parent ::__construct();
        $this->load->model('overtime_trx_model', 'overtime');
        $this->load->model('Employee_model', 'employee');
        $this->load->model('logs_model', 'logs');
        $this->load->model('libraryfunction_model', 'libfun');
	$this->load->model('Paramlock_model', 'paramlock');
	date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
        $this->is_logged_in();
    }
    function get_accepted(){
        $accepted   = $this->overtime->get_accepted($this->User);
        echo $accepted;
    }
    function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }	


   function index() {
        $fromd  = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');   
        $check1 = ($fromd =='' or $fromd ==null)?'empty':'exist';
        $check2 = ($untild =='' or $untild ==null)?'empty':'exist';       
        
        $data['test1'] = $fromd;
        $data['test2'] = $untild;
        
        if($check1 =='empty' and $check2=='empty'){
            $date = $this->libfun->periode_work();
            $fromdate = substr($date, 0, 10);
            $untildate = substr($date, 11, 10);                
            $this->session->set_userdata('datefrom', $fromdate);
            $this->session->set_userdata('dateuntil', $untildate);            
        }else{            
             $fromdate = $this->session->userdata('fromdate');
             $untildate = $this->session->userdata('untildate');  
             $this->session->set_userdata('datefrom', $fromdate);
             $this->session->set_userdata('dateuntil', $untildate);
        }        
        
        $data['default']['from'] = date('d-m-Y', strtotime($fromdate));
        $data['default']['until'] = date('d-m-Y', strtotime($untildate));
	
        $this->load->view('trx01/home',$data);
    }

    function dataovertime() {	
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
//        echo "<xmp>";
        echo $this->overtime->overtimeemployee($f, $u,$this->User);//asli	
	//echo $this->overtime->overtimeemployee($this->User);
        // tes okierie
//        echo "<br><br>";
//        $data = $this->overtime->overtimeemployee2($f, $u,$this->User)->result();
//        echo json_encode($data);
//        echo "</xmp>";
//        print_r();
    }

    function getstatus(){
        $id = $this->input->post('id');
        $rowh = $this->overtime->get_by_id($id);
        $flag = $rowh->ConfirmFlag;        
        $valid = 'true';
	$json = '{ "flag":"' . $flag . '",
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

    function suggest_employee() {
        $result = $this->employee->search_employee();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                'fullname' => strtoupper($row->FullName)
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    function datecurrent() {
        $f04 = date('Y-m-d', strtotime($this->input->post('f04')));
        $datecurr = date('Y-m-d');

        if ($f04 > $datecurr) {
            $this->form_validation->set_message('datecurrent', 'Invalid Presence Date');
            return false;
        } else {
            return true;
        }
    }

      function checktimein() {
        $time = $this->input->post('f05B');       
        $len = strlen($time);
        $hour = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        if ($len < 4 | $len > 4) {
            $this->form_validation->set_message("checktimein",  "Length of string :".$len." is to short");
            return false;
        } else {
            if ($hour < 0 | $hour > 23) {
                $this->form_validation->set_message("checktimein", "Hour string should not below under 0 or exceed 23 ");
                return false;
            } else {
                if ($minutes < 0 | $minutes > 59) {
                    $this->form_validation->set_message("checktimein", "Minute string should not below under  0 or exceed 59 ");
                    return false;
                } else {
                    return true;
                }
            }
        }
    }

    function checktimeout() {
        $time = $this->input->post('f06B');       
        $len = strlen($time);
        $hour = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        if ($len < 4 | $len > 4) {
            $this->form_validation->set_message("checktimeout",  "Length of string :".$len." is to short");
            return false;
        } else {

            if ($hour < 0 | $hour > 23) {
                $this->form_validation->set_message("checktimeout", "Hour string should not below under 0 or exceed 23 ");
                return false;
            } else {
                if ($minutes < 0 | $minutes > 59) {
                    $this->form_validation->set_message("checktimeout", "Minute string should not below under  0 or exceed 59 ");
                    return false;
                } else {
                    return true;
                }
            }
        }
    }
    function addnew() {
        $data['default']['f01'] = ''; //sub spkl
        $data['default']['f02'] = ''; //idemployee
        $data['default']['f03'] = ''; //fullname
        $data['default']['f04'] = date('d-m-Y'); //presencedate
        $data['default']['f05'] =  date('d-m-Y'); //overtimein
        $data['default']['f05B'] = 'hhmm'; //timein
        $data['default']['f06'] =  date('d-m-Y'); //overtimeout
        $data['default']['f06B'] = 'hhmm'; //timeout
        $data['default']['f07'] = ''; //note

        $data['default']['readonly_f02'] = 'READONLY';
        $data['url_post'] = site_url('trx01/home/addpost');       
        
        date_default_timezone_set("Asia/Jakarta");        
        $date = date('d');
	
	$resultparam  = $this->paramlock->getparamdata('overtime');        
        $checkdata = ($resultparam=='' or $resultparam==null)?'empty':'exist';  
        
        if($checkdata=='exist'){
            $from = $resultparam->Val1; 
            $until = $resultparam->Val2;  

	     if($date >= $from  and $date <=$until){               
               $this->load->view('trx01/notallow', $data);
             }else{                
                $this->load->view('trx01/form', $data);
             }	
        }

	/*
        $time = date('H:i');        
        $data['date'] = $date;
        $data['time'] = '10:00';        
        if($date =='25' and $time >='10:00'){
             $this->load->view('trx01/notallow',$data);            
        }else{
            $this->load->view('trx01/form',$data);
        } 
	*/  
       
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'Sub SPKL', 'required');
        $this->form_validation->set_rules('f02', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f04', 'Presence Date', 'required|callback_datecurrent');
        $this->form_validation->set_rules('f05', 'Overtime In', 'required');
        $this->form_validation->set_rules('f05B', 'Time In', 'required|callback_checktimein');
        $this->form_validation->set_rules('f06', 'Overtime Out', 'required');
        $this->form_validation->set_rules('f06B', 'Time Out', 'required|callback_checktimeout');
        $this->form_validation->set_rules('f07', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->User;
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f05B = $this->input->post('f05B'); $h1 = substr($f05B, 0, 2); $m1 = substr($f05B, 2, 2); $in = $h1.':'.$m1;
            $f06 = $this->anti_xss($this->input->post('f06'));
            $f06B = $this->input->post('f06B'); $h2 = substr($f06B, 0, 2); $m2 = substr($f06B, 2, 2); $out = $h2.':'.$m2;
            $f07 = $this->anti_xss($this->input->post('f07'));
            
            $timein = date('Y-m-d H:i', strtotime($f05.''.$in));
            $timeout = date('Y-m-d H:i', strtotime($f06.''.$out));
            $sumhour = (strtotime($timeout) - strtotime($timein)) / 3600;

            $record = array(
                'IDSPKL' => $f01,
                'IDEmployee' => $f02,
                'PresenceDate' => date('Y-m-d', strtotime($f04)),
                'OvertimeIn' => $timein,
                'OvertimeOut' => $timeout,
                'OvertimeHour' => $sumhour,
                'Note' => TRIM($f07),
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
                'controller' => site_url('trx01/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            if (($sumhour <= 0) OR ($sumhour >= 20)) {
                $mesg = '**WARNING** Overtime Hour :' . $sumhour;
                $valid = 'false';
                $err_f01 = '';
                $err_f02 = '';
                $err_f04 = '';
                $err_f05B = '**TIMEIN ERROR**';
                $err_f06B = '**TIMEOUT ERROR**';
                $err_f07 = '';
            } else {
                $this->overtime->insert($record);
                $this->logs->insert($recordlog);
                $mesg = 'Overtime Hour:' . $f05 . ' to ' . $f06 . '  =' . $sumhour . ' Hour';
                $valid = 'true';
                $err_f01 = '';
                $err_f02 = '';
                $err_f04 = '';
                $err_f05B = '';
                $err_f06B = '';
                $err_f07 = '';
//                kirim email pemberitahuan ke atasan
                $prs    = $this->overtime->get_personal($f02)->row();
                $ats    = $this->overtime->get_prs_public($prs->IDEmployeeParent)->row();
                $sendto = $ats->InternalEmail;
                $subject= "TIS Notification - Overtime";                
                $data['state']  = "confirm";
                $data['sendername'] = $prs->FullName;
                $data['receivername'] = $ats->FullName;
                $message= $this->load->view("trx01/email",$data,TRUE);
                $this->sendmail->internalmail($sendto, $subject, $message);
                $eksmail    = explode(",", $ats->ExternalEmail);
                if ($this->sendmail->externalmail($eksmail, $subject, $message)){
//                        echo $ex." => berhasil";
                    }
                
            }
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f04 = form_error('f04');
            $err_f05B = form_error('f05B');
            $err_f06B = form_error('f06B');
            $err_f07 = form_error('f07');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
                       "err_f02":"' . $err_f02 . '",
                       "err_f04":"' . $err_f04 . '",
                       "err_f05B":"' . $err_f05B . '",
                       "err_f06B":"' . $err_f06B . '",
                       "err_f07":"' . $err_f07 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->overtime->get_by_id($id);
        $data['default']['f01'] = $row->IDSPKL; //sub spkl
        $data['default']['f02'] = $row->IDEmployee; //idemployee
        $data['default']['f03'] = $row->FullName; //fullname
        $data['default']['f04'] = date('d-m-Y', strtotime($row->PresenceDate)); //presencedate
        $data['default']['f05'] = date('d-m-Y', strtotime($row->OvertimeIn));
        $data['default']['f05B'] = date('Hi', strtotime($row->OvertimeIn));
        $data['default']['f06'] = date('d-m-Y', strtotime($row->OvertimeOut));
        $data['default']['f06B'] = date('Hi', strtotime($row->OvertimeOut));
        $data['default']['f07'] = $row->Note; //note

        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';
        $data['default']['readonly_f03'] = 'READONLY';
        $data['url_post'] = site_url('trx01/home/editpost');
        
        date_default_timezone_set("Asia/Jakarta");        
        $date = date('d');
	$resultparam  = $this->paramlock->getparamdata('overtime');        
        $checkdata = ($resultparam=='' or $resultparam==null)?'empty':'exist';    
      
        if($checkdata=='exist'){
            $from = $resultparam->Val1; 
            $until = $resultparam->Val2;  

	     if($date >= $from  and $date <=$until){               
               $this->load->view('trx01/notallow', $data);
             }else{                
                $this->load->view('trx01/form', $data);
             }	
        }



	/*
        $time = date('H:i');        
        $data['date'] = $date;
        $data['time'] = '10:00';
        
        if($date =='25' and $time >='10:00'){
             $this->load->view('trx01/notallow',$data);            
        }else{
            $this->load->view('trx01/form',$data);
        }   
	*/
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Sub SPKL', 'required');
        $this->form_validation->set_rules('f02', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f04', 'Presence Date', 'required');
        $this->form_validation->set_rules('f05', 'Overtime In', 'required');
        $this->form_validation->set_rules('f05B', 'Time In', 'required|callback_checktimein');
        $this->form_validation->set_rules('f06', 'Overtime Out', 'required');
        $this->form_validation->set_rules('f06B', 'Time Out', 'required|callback_checktimeout');
        $this->form_validation->set_rules('f07', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->User;
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f05B = $this->input->post('f05B'); $h1 = substr($f05B, 0, 2); $m1 = substr($f05B, 2, 2); $in = $h1.':'.$m1;
            $f06 = $this->anti_xss($this->input->post('f06'));
            $f06B = $this->input->post('f06B'); $h2 = substr($f06B, 0, 2); $m2 = substr($f06B, 2, 2); $out = $h2.':'.$m2;
            $f07 = $this->anti_xss($this->input->post('f07'));
            
            $timein = date('Y-m-d H:i', strtotime($f05.''.$in));
            $timeout = date('Y-m-d H:i', strtotime($f06.''.$out));
            $sumhour = (strtotime($timeout) - strtotime($timein)) / 3600;          

            $record = array(
                'IDSPKL' => $f01,
                'IDEmployee' => $f02,
                'PresenceDate' => date('Y-m-d', strtotime($f04)),
                'OvertimeIn' => $timein,
                'OvertimeOut' => $timeout,
                'OvertimeHour' => $sumhour,
                'Note' => TRIM($f07),
                'ConfirmFlag'   => '0',
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
                'controller' => site_url('trx01/home/edit'),
                'activities' => 'edit data ' . $f01
            );

            if (($sumhour <= 0) OR ($sumhour >= 20)) {
                $mesg = '**WARNING** Overtime Hour :' . $sumhour;
                $valid = 'false';
                $err_f01 = '';
                $err_f02 = '';
                $err_f04 = '';
                $err_f05B = '**TIMEIN ERROR**';
                $err_f06B = '**TIMEOUT ERROR**';
                $err_f07 = '';
            } else {
                $wh =   array("ID" => $id);
                $overtime = $this->overtime->get_overtime($wh)->row();
                if ($overtime->ConfirmFlag == "2"){
    //                kirim email pemberitahuan ke atasan
                    $prs    = $this->overtime->get_personal($f02)->row();
                    $ats    = $this->overtime->get_prs_public($prs->IDEmployeeParent)->row();
                    $sendto = $ats->InternalEmail;
                    $subject= "TIS Notification - Overtime";
                    
                    $data['state']  = "confirm";
                    $data['sendername'] = $prs->FullName;
                    $data['receivername'] = $ats->FullName;
                    $message= $this->load->view("trx01/email",$data,TRUE);
                    $this->sendmail->internalmail($sendto, $subject, $message);
                    $eksmail    = explode(",", $ats->ExternalEmail);
                    $this->sendmail->externalmail($eksmail, $subject, $message);
                    
                }
                $this->overtime->update($id, $record);
                $this->logs->insert($recordlog);

                $mesg = 'Overtime Hour:' . $f05 . ' to ' . $f06 . '  =' . $sumhour . ' Hour';
                $valid = 'true';
                $err_f01 = '';
                $err_f02 = '';
                $err_f04 = '';
                $err_f05B = '';
                $err_f06B = '';
                $err_f07 = '';
            }
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f04 = form_error('f04');
            $err_f05B = form_error('f05B');
            $err_f06B = form_error('f06B');
            $err_f07 = form_error('f07');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
                       "err_f02":"' . $err_f02 . '",
                       "err_f04":"' . $err_f04 . '",
                       "err_f05B":"' . $err_f05B . '",
                       "err_f06B":"' . $err_f06B . '",
                       "err_f07":"' . $err_f07 . '"' .
                '}';
        echo $json;
    }
    
    function checkdata($id){
        $record = array(
            "CheckData" => '1'           
        );
        $this->overtime->update($id, $record);     
        
        
        $data['default']['from']  = date('d-m-Y',  strtotime($this->session->userdata('fromdate')));
        $data['default']['until'] = date('d-m-Y',  strtotime($this->session->userdata('untildate')));
        
        $this->load->view('trx01/home',$data);
    }
    
    function delete($id) {
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );

        $this->overtime->update($id, $record);
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
        $objSheet->setTitle('overtime report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('ID');
        $objSheet->getCell('B1')->setValue('IDEmployee');
        $objSheet->getCell('C1')->setValue('FullName');
        $objSheet->getCell('D1')->setValue('Presence Date');
        $objSheet->getCell('E1')->setValue('TimeIn');
        $objSheet->getCell('F1')->setValue('TimeOut');
        $objSheet->getCell('G1')->setValue('Work Hour');
        $objSheet->getCell('H1')->setValue('Note');
        
        //$f = $this->session->userdata('datefrom');
       // $u = $this->session->userdata('dateuntil');
        $result = $this->overtime->get_by_user($this->User);
        if ($result != NULL) {   
            $i = 1;
            foreach ($result as $row) {
                $i++;
                
                
                $timein = date('Y-m-d H:i', strtotime($row['OvertimeIn']));
                $timeout = date('Y-m-d H:i', strtotime($row['OvertimeOut']));
                $sumhour = (strtotime($timeout) - strtotime($timein)) / 3600;
                
                $objSheet->getCell('A' . $i)->setValue($row['IDSPKL']);
                $objSheet->getCell('B' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($row['FullName']);
                $objSheet->getCell('D' . $i)->setValue($row['PresenceDate']);
                $objSheet->getCell('E' . $i)->setValue($row['OvertimeIn']);
                $objSheet->getCell('F' . $i)->setValue($row['OvertimeOut']);
                $objSheet->getCell('G' . $i)->setValue($sumhour);
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
            $objWriter->save($path_file . "overtime" . $ext);
            $data = file_get_contents($path_file . "overtime" . $ext);
            force_download("overtime" . $ext, $data);
        }
    }
    function notification(){
        $where      = "ConfirmFlag = '0' AND DeleteFlag = 'A' AND IDEmployee IN (SELECT isib_employee.m01personal.IDEmployee FROM isib_employee.m01personal WHERE isib_employee.m01personal.IDEmployeeParent = '$this->User')";
        $data['overtimes']   = $this->overtime->get_overtime($where);
        $this->load->view("trx01/notifications",$data);
    }
    function captcha(){
        $data['idovertime']  = $this->input->post('idovertime');
        $this->load->view("trx01/confirm",$data);
    }
    function confirm(){
        $idovertime = $this->input->post('idovertime');
        $stat       = $this->input->post('stat');
        $reason     = $this->input->post('reason');
        $record     = array("ConfirmFlag" => $stat, "ConfirmDate" => date('Y-m-d H:i:s'), "ConfirmIP" => $this->input->ip_address(), "ConfirmBy" => $this->User, "RejectReason" => $reason);
        $where      = array("ID" => $idovertime);
        $data['state']  = "status";
        $data['confirm']  = $stat;
        $overtime = $this->overtime->get_overtime($where)->row();
//                kirim email pemberitahuan ke pengaju            
        $prs    = $this->overtime->get_prs_public($overtime->IDEmployee)->row();
        $sendto = $prs->InternalEmail;
        $subject= "TIS Notification - Overtime";
        $data['receivername']       = $prs->FullName;
        $data['overtime']           = $overtime;
        $message = $this->load->view("trx01/email",$data,TRUE);
        $this->sendmail->internalmail($sendto, $subject, $message);
        $eksmail    = explode(",", $prs->ExternalEmail);
        $this->sendmail->externalmail($eksmail, $subject, $message);
        
        $this->overtime->update_overtime($where,$record);
//        $msg        = array("status" => "");
    }
    function is_logged_in() {
//        die();
        if ($this->session->userdata('sess_logged_in') != TRUE) {
            redirect('login', 'refresh');
        }
    }


   }

