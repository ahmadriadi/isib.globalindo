<?php

// LEAVE PERMIT
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("leavepermit_model", 'leavepermit');
        $this->load->model("presence_model", 'presence');
        $this->load->model('employee_model', 'employee');
	$this->load->model('param_model', 'param');
        $this->load->model('logs_model', 'logs');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('uac_model', 'uac');	

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

        $this->load->view('trx02a/home', $data);
    }

 */

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

        $iduser = $this->session->userdata('sess_userid');
        $idmodule = '83';

        $rowparam = $this->param->get_param($iduser);
        $rowlogin = $this->login->get_by_user($iduser);
        $rowmenu = $this->access->get_by_idmenu($idmodule);
        $rowpersonal = $this->employee->get_position($iduser);

        $checkdata1 = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $checkdata2 = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';

        if ($checkdata1 == 'exist' and $checkdata2 == 'exist') {
            $role = $rowlogin->Role;
            $position = $rowpersonal->Position;

            if ($role == '2') {
		$data['accessbutton'] ='true';
                $result = $this->leavepermit->leavepermit_hrd($fromdate, $untildate);
            } else if ($role == '1') {
                $parameter = $rowparam->ParamValue;
                if ($parameter == $iduser) {
		    $data['accessbutton'] ='true';	
                    $result = $this->leavepermit->leavepermit_hrd($fromdate, $untildate);
                } else {
		    $data['accessbutton'] ='false';	
                    $result = $this->leavepermit->leavepermit($fromdate, $untildate, $iduser);
                }
            } else if ($role == '0' and $position == 'DIRECTOR' or $position == 'DIREKTUR' or $position == 'ASS. MANAGER' or $position == 'KOMISARIS' or $position == 'PROJECT TEAM LEADER' or $position == 'SUPERVISOR') {
		$data['accessbutton'] ='false';
                $result = $this->leavepermit->leavepermit($fromdate, $untildate, $iduser);
            }

            $data['dataleavepermit'] = $result;
            $checkresult = ($result == '' or $result == null) ? 'empty' : 'exist';
	    $idmenu = "116";
            $data['buttons'] = $this->uac->get_btnaccess($iduser, $idmenu);	
		
            if ($checkresult == 'exist') {
                $this->load->view('trx02a/homeajax', $data);
            } else {
                $this->load->view('trx02a/homenull', $data);
            }
        }
    }
 

    function dataleavepermit() {
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
            if ($role == '1' or $role == '2' or $position == 'SUPERVISOR') {
                echo $this->leavepermit->allleavepermit($f, $u);
            }
        }
    }

     function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '116';
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
        $rowh = $this->leavepermit->get_by_id($id);
        $flag = $rowh->ConfirmFlag;
        $inputby = $rowh->FlagInput;
        $name = $rowh->FullName;
        $valid = 'true';
        $json = '{ "flag":"' . $flag . '",
                   "inputby":"' . $inputby . '",
                   "fullname":"' . $name . '",
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

    function checktime() {
        $time = $this->input->post('f04B');
        $len = strlen($time);
        $hour = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        if ($len < 4 | $len > 4) {
            $this->form_validation->set_message("checktime", "Length of string :" . $len . " is to short");
            return false;
        } else {
            if ($hour < 0 | $hour > 23) {
                $this->form_validation->set_message("checktime", "Hour string should not below under 0 or exceed 23 ");
                return false;
            } else {
                if ($minutes < 0 | $minutes > 59) {
                    $this->form_validation->set_message("checktime", "Minute string should not below under  0 or exceed 59 ");
                    return false;
                } else {
                    return true;
                }
            }
        }
    }


   

    function addnew() {
        $data['default']['f01'] = ''; //idemployee
        $data['default']['f02'] = ''; //fullname
        //Necessity
        $data['default']['f03'][0]['value'] = "1";
        $data['default']['f03'][0]['display'] = "Personal";
        $data['default']['f03'][0]['checked'] = "CHECKED";
        $data['default']['f03'][1]['value'] = "2";
        $data['default']['f03'][1]['display'] = "Office";
        $data['default']['f03'][1]['checked'] = "";

        $data['default']['f04A'] = ''; //
        $data['default']['f04B'] = 'hhmm'; //        
        $data['default']['f05A'] = ''; // 
        $data['default']['f05B'] = 'hhmm'; //  

        $data['default']['f06'] = ''; //Note        
        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('trx02a/home/addpost');

        $this->load->view('trx02a/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f04A', 'Out Date', 'required');
        $this->form_validation->set_rules('f04B', 'Time Out', 'required|numeric|callback_checktime');
        $this->form_validation->set_rules('f06', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->input->post('f01');
            $f03 = $this->input->post('f03');
            $f04A = date('Y-m-d', strtotime($this->input->post('f04A')));
            $f04 = $f04A . " " . substr($this->input->post('f04B'), 0, 2) . ":" . substr($this->input->post('f04B'), 2, 2);
            $f05A = ($this->input->post("f05A") == '' or $this->input->post("f05A") == null) ? null : date('Y-m-d', strtotime($this->input->post("f05A")));
            $f05B = ($this->input->post("f05B") == '' or $this->input->post("f05B") == null) ? null : $this->input->post("f05B");
            $f05 = $f05A . " " . substr($f05B, 0, 2) . ":" . substr($f05B, 2, 2);            
            $indate = ($f05==' hh:mm' or $f05==' :')?null:$f05;            
            $f06 = $this->input->post('f06');
            $hour = (strtotime($indate) - strtotime($f04)) / 3600;            
            $sumhour = ($indate==null)?null:$hour;

            $record = array(
                'FlagInput' =>'hrd',
		'IDEmployee' => $f01,
                'Necessity' => $f03,
                'LeavePermitDate' => $f04A,
                'OutDate' => $f04,
                'InDate' => $indate,
                'IMKHour' => $sumhour,
                'Note' => $f06,
                'ConfirmFlag' => '1',
                'ConfirmDate' => $this->Datetime,
                'ConfirmIP' => $this->Ip,
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
                'controller' => site_url('trx02a/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $this->leavepermit->insert($record);
            $this->logs->insert($recordlog);

	    $recordpresence = array(            
             'ManualOut'=>$f04,
             'IMKOut'=>$f04,
             'IMKIn'=>$indate,
             'IMKHour'=>$sumhour,
             'Description'=>'LP',     
             'Necessity' => $f03                
            );      
            $this->leavepermit->presenceby_necessity($f01,$f04A,$recordpresence); 
			

            $mesg = 'Insert Data, Success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04A = '';
            $err_f04B = '';
            $err_f05A = '';
            $err_f05B = '';
            $err_f06 = '';
        } else {
            $mesg = 'Insert Data, Failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04A = form_error('f04A');
            $err_f04B = form_error('f04B');
            $err_f05A = form_error('f05A');
            $err_f05B = form_error('f05B');
            $err_f06 = form_error('f06');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
                       "err_f02":"' . $err_f02 . '",
                       "err_f03":"' . $err_f03 . '",
                       "err_f04A":"' . $err_f04A . '",
                       "err_f04B":"' . $err_f04B . '",
                       "err_f05A":"' . $err_f05A . '",
                       "err_f05B":"' . $err_f05B . '",
                       "err_f06":"' . $err_f06 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $row = $this->leavepermit->get_by_id($id);
        $datepresence = date('Y-m-d', strtotime($row->LeavePermitDate));	
        $data['default']['f01'] = $row->IDEmployee;
        $data['default']['f02'] = $row->FullName;
        $data['default']['f03'][0]['value'] = "1";
        $data['default']['f03'][0]['display'] = "Personal";
        $data['default']['f03'][1]['value'] = "2";
        $data['default']['f03'][1]['display'] = "Office";
        if ($row->Necessity == '1') {
            $data['default']['f03'][0]['checked'] = "CHECKED";
        } else {
            if ($row->Necessity == '2') {
                $data['default']['f03'][1]['checked'] = "CHECKED";
            }
        }
        $timeout = date('Hi', strtotime($row->OutDate));
        $indate =  date('d-m-Y', strtotime($row->InDate));
        $timein = date('Hi', strtotime($row->InDate));

        $data['default']['f04A'] = date('d-m-Y',strtotime($datepresence));
        $data['default']['f04B'] = $timeout;
        $data['default']['f05A'] = $indate;
        $data['default']['f05B'] = $timein;
        $data['default']['f06'] = $row->Note;
        
        
        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';

        $session = array(
            "id" => $id,
            "lastpresence" => $row->LeavePermitDate
        );
        $this->session->set_userdata($session);
        $data['url_post'] = site_url('trx02a/home/editpost');
        $this->load->view('trx02a/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f04A', 'Out Date', 'required');
        $this->form_validation->set_rules('f04B', 'Out Time', 'required|numeric|callback_checktime');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $idpresence = $this->session->userdata('lastpresence');
            $f01 = $this->input->post('f01');
            $f03 = $this->input->post('f03');
            $f04A = date('Y-m-d', strtotime($this->input->post('f04A')));
            $f04 = $f04A . " " . substr($this->input->post('f04B'), 0, 2) . ":" . substr($this->input->post('f04B'), 2, 2);
            $f05A = ($this->input->post("f05A") == '01-01-1970' or $this->input->post("f05A") == null) ? null : date('Y-m-d', strtotime($this->input->post("f05A")));
            $f05B = ($this->input->post("f05B") == '' or $this->input->post("f05B") == null) ? null : $this->input->post("f05B");
            $f05 = $f05A . " " . substr($f05B, 0, 2) . ":" . substr($f05B, 2, 2);            
            $indate = ($f05==' hh:mm' or $f05==' :' or $f05=' 00:00')?null:$f05;            
            $f06 = $this->input->post('f06');
            $hour = (strtotime($indate) - strtotime($f04)) / 3600;            
            $sumhour = ($indate==null)?null:$hour;

            $record = array(
                'IDEmployee' => $f01,
                'Necessity' => $f03,
                'LeavePermitDate' => $f04A,
                'OutDate' => $f04,
                'InDate' => $indate,
                'IMKHour' => $sumhour,
                'Note' => $f06,
                'ConfirmFlag' => '1',
                'ConfirmDate' => $this->Datetime,
                'ConfirmIP' => $this->Ip,
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
                'controller' => site_url('trx02a/home/edit'),
                'activities' => 'edit data ' . $f01
            );

            $this->leavepermit->update($id, $record);
            $this->logs->insert($recordlog);

                       
           $recordlastpresence = array(            
             'ManualOut'  =>null, 
             'IMKOut'  =>null, 
             'IMKIn'  =>null, 
             'IMKHour'  =>null, 
             'Description'  =>null,  
             'Necessity' =>null                
            );
            $this->leavepermit->lastpresenceby_necessity($f01,$idpresence,$recordlastpresence);  

            $recordpresence = array(
                'ManualOut' => $f04,
                'IMKOut' => $f04,
                'IMKIn' => $indate,
                'IMKHour' => $sumhour,
                'Description' => 'LP',
                'Necessity' => $f03
            );

            $this->leavepermit->presenceby_necessity($f01, $f04A, $recordpresence);

            $mesg = 'Update Data, Success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04A = '';
            $err_f04B = '';
            $err_f05A = '';
            $err_f05B = '';
            $err_f06 = '';
        } else {
            $mesg = 'Update Data, Failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04A = form_error('f04A');
            $err_f04B = form_error('f04B');
            $err_f05A = form_error('f05A');
            $err_f05B = form_error('f05B');
            $err_f06 = form_error('f06');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
                       "err_f02":"' . $err_f02 . '",
                       "err_f03":"' . $err_f03 . '",
                       "err_f04A":"' . $err_f04A . '",
                       "err_f04B":"' . $err_f04B . '",
                       "err_f05A":"' . $err_f05A . '",
                       "err_f05B":"' . $err_f05B . '",
                       "err_f06":"' . $err_f06 . '"' .
                '}';
        echo $json;
    }

    function delete($id) {
        $record = array(
            "DeletedBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeletedDate" => $this->Datetime,
            "DeletedIP" => $this->Ip
        );

        $this->leavepermit->update($id, $record);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function excel() {
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
        $objSheet->setTitle('leavepermit report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('NO');
        $objSheet->getCell('B1')->setValue('IDEmployee');
        $objSheet->getCell('C1')->setValue('FullName');
        $objSheet->getCell('D1')->setValue('Goup');
        $objSheet->getCell('E1')->setValue('Necessity');
        $objSheet->getCell('F1')->setValue('LeavePermitDate');
        $objSheet->getCell('G1')->setValue('Out from Office');
        $objSheet->getCell('H1')->setValue('In to Office');
        $objSheet->getCell('I1')->setValue('Note');

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

        if ($checkdata1 == 'exist' and $checkdata2 == 'exist') {
            $role = $rowlogin->Role;
            $position = $rowpersonal->Position;

            if ($role == '2') {
                $result = $this->leavepermit->leavepermit_hrd($f, $u);
            } else if ($role == '1') {
                $parameter = $rowparam->ParamValue;
                if ($parameter == $iduser) {
                    $result = $this->leavepermit->leavepermit_hrd($f, $u);
                } else {
                    $result = $this->leavepermit->leavepermit($f, $u, $iduser);
                }
            } else if ($role == '0' and $position == 'DIRECTOR' or $position == 'DIREKTUR' or $position == 'ASS. MANAGER' or $position == 'KOMISARIS' or $position == 'PROJECT TEAM LEADER' or $position == 'SUPERVISOR') {

                $result = $this->leavepermit->leavepermit($f, $u, $iduser);
            }
        }
        $checkresult = ($result == '' or $result == null) ? 'empty' : 'exist';
       
        if ($checkresult == 'exist') {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $Necessity = ($row['InDate'] == '1') ? 'PERSONAL' : 'OFFICE';

                if ($row['IDJobGroup'] == 'ST') {
                    $group = 'STAFF';
                } else if ($row['IDJobGroup'] == 'LT') {
                    $group = 'lAPANGAN TETAP';
                } else if ($row['IDJobGroup'] == 'LK') {
                    $group = 'lAPANGAN KONTRAK';
                } else if ($row['IDJobGroup'] == 'HL') {
                    $group = 'HARIAN LEPAS';
                } else if ($row['IDJobGroup'] == 'LL') {
                    $group = 'LAIN-LAIN';
                }


                $objSheet->getCell('A' . $i)->setValue($i);
                $objSheet->getCell('B' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($row['FullName']);
                $objSheet->getCell('D' . $i)->setValue($group);
                $objSheet->getCell('E' . $i)->setValue($Necessity);
                $objSheet->getCell('F' . $i)->setValue($row['LeavePermitDate']);
                $objSheet->getCell('G' . $i)->setValue($row['OutDate']);
                $objSheet->getCell('H' . $i)->setValue($row['InDate']);
                $objSheet->getCell('I' . $i)->setValue($row['Note']);
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
            $objWriter->save($path_file . "leavepermit" . $ext);
            $data = file_get_contents($path_file . "leavepermit" . $ext);
            force_download("leavepermit" . $ext, $data);
        }
    }

}
