<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {


        parent ::__construct();
        $this->load->model('overtime_trx_model', 'overtime');
        $this->load->model('Employee_model', 'employee');
	$this->load->model('Param_model', 'param');
	$this->load->model('Paramlock_model', 'paramlock');
        $this->load->model('logs_model', 'logs');
	$this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('uac_model', 'uac');	
	$this->load->model('libraryfunction_model', 'libfun');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }
   /*	
    function index() {
        $fromd = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');
        $check1 = ($fromd == '' or $fromd == null) ? 'empty' : 'exist';
        $check2 = ($untild == '' or $untild == null) ? 'empty' : 'exist';

        $data['test1'] = $fromd;
        $data['test2'] = $untild;

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

        $this->load->view('trx01a/home', $data);
    }
   */



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

        $iduser = $this->session->userdata('sess_userid');
        $idmodule = '83';

        $rowparam = $this->param->get_param($iduser);
        $rowlogin = $this->login->get_by_user($iduser);
        $rowmenu = $this->access->get_by_idmenu($idmodule);
        $rowpersonal = $this->employee->get_position($iduser);

        $checkdata1 = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $checkdata2 = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';

        $parameter = $rowparam->ParamValue;
          if ($parameter == $iduser) {
              $param ='Y';
          }else{
              $param ='N';
          }


        if ($checkdata1 == 'exist' and $checkdata2 == 'exist') {
            $role = $rowlogin->Role;
            $position = $rowpersonal->Position;
            if ($role == '2') {
		$data['accessbutton']='true';
                $result = $this->overtime->overtimedata_hrd($fromdate, $untildate,'AL');
                 $viewon = 'trx01a/homeajax'; 
            } else if ($role == '1') {
                $parameter = $rowparam->ParamValue;
                if ($parameter == $iduser) {
		    $data['accessbutton']='true';	
                    $result = $this->overtime->overtimedata_hrd($fromdate, $untildate,'AL');
                    $viewon = 'trx01a/homeajax';     
                } else {	
		    $data['accessbutton']='false';	
                    $result = $this->overtime->overtimedata($fromdate, $untildate, $iduser);
                    $viewon = 'trx01a/home'; 
                }
            } else if ($role == '0' and $position == 'DIRECTOR' or $position == 'DIREKTUR' or $position == 'ASS. MANAGER' or $position == 'KOMISARIS' or $position == 'PROJECT TEAM LEADER' or $position =='MANAGER' or $position =='ASSISTANT MANAGER' or $position == 'SUPERVISOR' or $position == 'ASSISTANT SUPERVISOR' or $param=='Y') {
		$data['accessbutton']='false';
                $result = $this->overtime->overtimedata($fromdate, $untildate, $iduser);
                $viewon = 'trx01a/home'; 
            }
            $data['dataovertime'] = $result;
            $checkresult = ($result == '' or $result == null) ? 'empty' : 'exist';
            if ($checkresult == 'exist') {
		$iduser = $this->session->userdata('sess_userid');
                $idmenu = "114";
                $data['buttons'] = $this->uac->get_btnaccess($iduser, $idmenu);	
                $this->load->view($viewon, $data);
            } else {
		$iduser = $this->session->userdata('sess_userid');
                $idmenu = "114";
                $data['buttons'] = $this->uac->get_btnaccess($iduser, $idmenu);	
                $this->load->view('trx01a/homenull', $data);
            }
        }
        
    }



   function dataovertime() {
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $iduser = $this->session->userdata('sess_userid');
        $idmodule = '83';

        $rowlogin = $this->login->get_by_user($iduser);
        $rowmenu = $this->access->get_by_idmenu($idmodule);
	$rowpersonal = $this->employee->get_position($iduser);

        $check1 = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $check2 = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';

        if ($check1 == 'exist' and $check2 == 'exist') {
            $role = $rowlogin->Role;
	    $position = $rowpersonal->Position;	
            if ($role == '1' or $role == '2' or $position=='SUPERVISOR') {
                echo $this->overtime->allovertime($f, $u);
            }
        }
    }
    
     function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '114';
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

    function getstatus() {
        $id = $this->input->post('id');
        $rowh = $this->overtime->get_by_id($id);
        $flag = $rowh->ConfirmFlag;
        $inputby = $rowh->FlagInput;     
        $condition = $rowh->CheckData;     
        $valid = 'true';
        $json = '{ "flag":"' . $flag . '",
                   "inputby":"' . $inputby . '",
                   "checkdata":"' . $condition . '",
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
            $this->form_validation->set_message("checktimein", "Length of string :" . $len . " is to short");
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
            $this->form_validation->set_message("checktimeout", "Length of string :" . $len . " is to short");
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
	$rowhrd = $this->employee->get_by_nip($this->User);
        $dept = $rowhrd->IDDepartement;        
       	
	if($dept=='14'){
            $data['inputdata'] = 'open'; 
            $data['presentdate'] = 'form';
        }else{
            $data['inputdata'] = 'hidden';  
            $data['presentdate'] = 'addpost';
        }	



        $data['default']['f01'] = ''; //sub spkl
        $data['default']['f02'] = ''; //idemployee
        $data['default']['f03'] = ''; //fullname
        $data['default']['f04'] = date('d-m-Y'); //presencedate
        $data['default']['f05'] = date('d-m-Y'); //overtimein
        $data['default']['f05B'] = 'hhmm'; //timein
        $data['default']['f06'] = date('d-m-Y'); //overtimeout
        $data['default']['f06B'] = 'hhmm'; //timeout
        $data['default']['f07'] = ''; //note

        $data['default']['readonly_f02'] = 'READONLY';
        $data['url_post'] = site_url('trx01a/home/addpost');

        date_default_timezone_set("Asia/Jakarta");
        $date = date('d');
        $resultparam  = $this->paramlock->getparamdata('overtime');        
        $checkdata = ($resultparam=='' or $resultparam==null)?'empty':'exist';    
        $rowlogin = $this->login->get_by_user($this->User);
        $rowmenu = $this->access->get_by_idmenu('83');
	$checkrole = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $checkmodule = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';        
        $role = $rowlogin->Role;        
        
        if($checkdata=='exist'){
            $from = $resultparam->Val1; 
            $until = $resultparam->Val2; 
	    $exclude = $resultparam->Val3; 
	    $timecurrent = date('Hi');
            $timelimit = $resultparam->Val4;	
            
            if($checkrole=='exist' and $checkmodule=='exist'){
                if($role=='1' or $role=='2' or $exclude==$this->User){

                    if($exclude==$this->User and $timecurrent >= $timelimit){
                         $data['limittime'] = $timelimit;
                         $this->load->view('trx01a/notallow', $data);
                    }else{
                        $this->load->view('trx01a/form', $data);
                    }

                }else{                    
                    if($date >= $from  and $date <=$until){  
			$data['limittime'] = '';             
                        $this->load->view('trx01a/notallow', $data);
                     }else{                
                        $this->load->view('trx01a/form', $data);
                    }
                }
                
            }
           
        }
	
	
	/*
        $time = date('H:i');
        $data['date'] = $date;
        $data['time'] = '10:00';
        if ($date == '25' and $time >= '10:00') {
            $this->load->view('trx01a/notallow', $data);
        } else {
            $this->load->view('trx01a/form', $data);
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
        //$this->form_validation->set_rules('f07', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->input->post('f01');
            $f02 = $this->input->post('f02');
	    $f04temp = $this->input->post('f04');		
	    /*            
            if($f04temp=='undefined'){
                $f04 = date('Y-m-d');
            }else{
                 $f04 = $f04temp;
            }
	  */	
	
	   $entrydate =$this->input->post('entrydate');
            if($entrydate=='form'){
                $f04 = $f04temp;
            }else if($entrydate=='addpost'){
                $f04 = date('Y-m-d');
            }	
		

            $f05 = $this->input->post('f05');
            $f05B = $this->input->post('f05B');
            $h1 = substr($f05B, 0, 2);
            $m1 = substr($f05B, 2, 2);
            $in = $h1 . ':' . $m1;
            $f06 = $this->input->post('f06');
            $f06B = $this->input->post('f06B');
            $h2 = substr($f06B, 0, 2);
            $m2 = substr($f06B, 2, 2);
            $out = $h2 . ':' . $m2;
            $f07 = $this->input->post('f07');
	
	    $presence = date('Y-m-d', strtotime($f04)); 	
            $timein = date('Y-m-d H:i', strtotime($f05 . '' . $in));
            $timeout = date('Y-m-d H:i', strtotime($f06 . '' . $out));
            $sumhour = (strtotime($timeout) - strtotime($timein)) / 3600;

            $record = array(
		'FlagInput' => 'hrd',
                'IDSPKL' => $f01,
                'IDEmployee' => $f02,
                'ConfirmFlag' => "1",
                'ConfirmDate' => $this->Datetime,
                'ConfirmIP' => $this->Ip,
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
                'controller' => site_url('trx01a/home/addnew'),
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
                $resultdata = $this->overtime->checkovertime($f02,$timein,$timeout);               
                $check = ($resultdata=='' or $resultdata==null )?'empty':'exist';
                if($check =='exist'){                      
                     $rowpersonal = $this->employee->get_by_nip($f02);
                     $name = $rowpersonal->FullName;
                     $txt = 'Overtime for '.$name.' already exist in date '.$presence.' time in '.$timein;
                     $val = 'false';
                }else{
                     $this->overtime->insert($record);
                     $this->logs->insert($recordlog);
                     $txt = 'Overtime Hour:' . $f05 . ' to ' . $f06 . '  =' . $sumhour . ' Hour';
                     $val = 'true';
                }               
               
                $mesg = $txt;
                $valid = $val;
                $err_f01 = '';
                $err_f02 = '';
                $err_f04 = '';
                $err_f05B = '';
                $err_f06B = '';
                $err_f07 = '';
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
	
	$rowhrd = $this->employee->get_by_nip($this->User);
        $dept = $rowhrd->IDDepartement;        
        if($dept=='14'){
            $data['inputdata'] = 'open';            
        }else{
            $data['inputdata'] = 'hidden';  
        }
	

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
        //$data['default']['readonly_f03'] = 'READONLY';
        $data['url_post'] = site_url('trx01a/home/editpost');

        date_default_timezone_set("Asia/Jakarta");
        $date = date('d');
        $resultparam  = $this->paramlock->getparamdata('overtime');        
        $checkdata = ($resultparam=='' or $resultparam==null)?'empty':'exist';    
        $rowlogin = $this->login->get_by_user($this->User);
        $rowmenu = $this->access->get_by_idmenu('83');
	$checkrole = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $checkmodule = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';        
        $role = $rowlogin->Role;        
        
        if($checkdata=='exist'){
            $from = $resultparam->Val1; 
            $until = $resultparam->Val2; 
	    $exclude = $resultparam->Val3; 
	    $timecurrent = date('Hi');
            $timelimit = $resultparam->Val4; 			
                   
            if($checkrole=='exist' and $checkmodule=='exist'){
                if($role=='1' or $role=='2' or $exclude==$this->User){

                    if($exclude==$this->User and $timecurrent >= $timelimit){
                         $data['limittime'] = $timelimit;
                         $this->load->view('trx01a/notallow', $data);
                    }else{
                        $this->load->view('trx01a/form', $data);
                    }

                }else{                    
                    if($date >= $from  and $date <=$until){    
			$data['limittime'] = '';                  
                        $this->load->view('trx01a/notallow', $data);
                     }else{                
                        $this->load->view('trx01a/form', $data);
                    }
                }
                
            }
           
        }

	/*

        $time = date('H:i');
        $data['date'] = $date;
        $data['time'] = '10:00';

        if ($date == '25' and $time >= '10:00') {
            $this->load->view('trx01a/notallow', $data);
        } else {
            $this->load->view('trx01a/form', $data);
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
        //$this->form_validation->set_rules('f07', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->input->post('f01');
            $f02 = $this->input->post('f02');
            $f04 = $this->input->post('f04');
            $f05 = $this->input->post('f05');
            $f05B = $this->input->post('f05B');
            $h1 = substr($f05B, 0, 2);
            $m1 = substr($f05B, 2, 2);
            $in = $h1 . ':' . $m1;
            $f06 = $this->input->post('f06');
            $f06B = $this->input->post('f06B');
            $h2 = substr($f06B, 0, 2);
            $m2 = substr($f06B, 2, 2);
            $out = $h2 . ':' . $m2;
            $f07 = $this->input->post('f07');

            $timein = date('Y-m-d H:i', strtotime($f05 . '' . $in));
            $timeout = date('Y-m-d H:i', strtotime($f06 . '' . $out));
            $sumhour = (strtotime($timeout) - strtotime($timein)) / 3600;

            $record = array(
                'IDSPKL' => $f01,
                'IDEmployee' => $f02,
                'ConfirmFlag' => "1",
                'ConfirmDate' => $this->Datetime,
                'ConfirmIP' => $this->Ip,
                'PresenceDate' => date('Y-m-d', strtotime($f04)),
                'OvertimeIn' => $timein,
                'OvertimeOut' => $timeout,
                'OvertimeHour' => $sumhour,
                'Note' => TRIM($f07),
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
                'controller' => site_url('trx01a/home/edit'),
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

    function checkdata() {
        $id = $this->input->post('id');
        $row = $this->overtime->get_by_id($id);
        $check = $row->CheckData;        
        $val = ($check =='1')?'0':'1';        
        $record = array(
            "CheckData" => $val
        );
        
        $this->overtime->update($id, $record);    
        
        $mesg = "Check Data, Success";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
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

        $this->overtime->update($id, $record);
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
 $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $iduser = $this->session->userdata('sess_userid');
        $idmodule = '83';

        $rowparam = $this->param->get_param($iduser);
        $rowlogin = $this->login->get_by_user($iduser);
        $rowmenu = $this->access->get_by_idmenu($idmodule);
        $rowpersonal = $this->employee->get_position($iduser);

        $checkdata1 = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $checkdata2 = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';

	 $parameter = $rowparam->ParamValue;
          if ($parameter == $iduser) {
              $param ='Y';
          }else{
              $param ='N';
          }

        if ($checkdata1 == 'exist' and $checkdata2 == 'exist') {
            $role = $rowlogin->Role;
            $position = $rowpersonal->Position;

            if ($role == '2') {
                $result = $this->overtime->overtimedata_hrd($f,$u,$g);
            } else if ($role == '1') {
                $parameter = $rowparam->ParamValue;
                if ($parameter == $iduser) {
                    $result = $this->overtime->overtimedata_hrd($f,$u,$g);
                } else {
                    $result = $this->overtime->overtimedata($f, $u, $iduser);
                }
            } else if ($role == '0' and $position == 'DIRECTOR' or $position == 'DIREKTUR' or $position == 'ASS. MANAGER' or $position == 'KOMISARIS' or $position == 'PROJECT TEAM LEADER' or $position =='MANAGER' or $position =='ASSISTANT MANAGER' or $position == 'SUPERVISOR' or $position == 'ASSISTANT SUPERVISOR' or $param=='Y')  {

                $result = $this->overtime->overtimedata($f, $u, $iduser);
            }
        }
        $checkresult = ($result == '' or $result == null) ? 'empty' : 'exist';

        
        
        if ($checkresult =='exist') {
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
            $objWriter->save($path_file . "overtime_attendance" . $ext);
            $data = file_get_contents($path_file . "overtime_attendance" . $ext);
            force_download("overtime_attendance" . $ext, $data);
        }
    }

    function notification() {
        $where = "ConfirmFlag = '0' AND DeleteFlag = 'A' AND IDEmployee IN (SELECT triasnet_employee.m01personal.IDEmployee FROM triasnet_employee.m01personal WHERE triasnet_employee.m01personal.IDEmployeeParent = '$this->User')";
        $data['overtimes'] = $this->overtime->get_overtime($where);
        $this->load->view("trx01a/notifications", $data);
    }

    function captcha() {
        $data['idovertime'] = $this->input->post('idovertime');
        $this->load->view("trx01a/confirm", $data);
    }

    function confirm() {
        $idovertime = $this->input->post('idovertime');
        $stat = $this->input->post('stat');
        $reason = $this->input->post('reason');
        $record = array("ConfirmFlag" => $stat, "ConfirmDate" => date('Y-m-d H:i:s'), "ConfirmIP" => $this->input->ip_address(), "RejectReason" => $reason);
        $where = array("ID" => $idovertime);
        $this->overtime->update_overtime($where, $record);
//        $msg        = array("status" => "");
    }

}

