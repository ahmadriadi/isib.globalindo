<?php

//LEAVE-HRD
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Leave_trx_model', 'leave');
        $this->load->model('Employee_model', 'employee');	
        $this->load->model('logs_model', 'logs');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('Param_model', 'param');
        $this->load->model('uac_model', 'uac');   
        $this->load->model('public_model', 'pbl');
	$this->load->model('libraryfunction_model', 'libfun');

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
	

	$query = $this->employee->get_rjob()->result();
        $i = 0;
        foreach ($query as $r) {
            $i++;
            $data['default']['group'][$i]['value'] = $r->IDJobGroup;
            $data['default']['group'][$i]['display'] = $r->GroupName;   
        }

	$idmenu                    = "121";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);

        $this->load->view('trx07/home', $data);
    }

     function dataleave() {
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
                echo $this->leave->leaveforhrd($f, $u);
            } else if ($role == '0' and $param == 'Y') {
                 echo $this->leave->leaveforhrd($f, $u);
            }
        }    
    }

    function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '121';
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
        $rowh = $this->leave->get_by_id($id);
        $flag = $rowh->ConfirmFlag;
        $valid = 'true';
        $json = '{ "flag":"' . $flag . '",
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

   function autocomplete_change() {
        $result = $this->employee->find_employee_active();
        $arr = array();
        foreach ($result->result() as $row) {
            
                 $arr[] = array('idemployee' => $row->IDEmployee,
                               'fullname' => strtoupper($row->FullName)
                ); 
               
            
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

    function suggest_charge() {
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

    function rest_leave() {
        $nip = $this->input->post('nip');
        $row = $this->leave->get_rest_leave_employee($nip);
        $amountleave = $row->Jml;
        $valid = 'true';
        $json = '{ "restleave":"' . $amountleave . '",
		   "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function long_leave($from, $until) {
        $sumdays = (strtotime($until) - strtotime($from)) / (24 * 3600) + 1;
        $perioddate = $from;
        $day = array('minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu');
        $summinggu = 0;
        while ($perioddate <= $until) {
            $week = $day[date('w', strtotime($perioddate))];
            if ($week == 'minggu') {
                $summinggu+= 1;
            }
            $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
        }

        $resultholiday = $this->leave->get_holiday($from, $until);
        $checkholiday = ($resultholiday == '' or $resultholiday == null) ? 'empty' : 'exist';

        if ($checkholiday == 'exist') {
            $sumlibur = $resultholiday->jumlahlibur;
        } else {
            $sumlibur = 0;
        }

        $jumlahlibur = ($summinggu) + ($sumlibur);
        $jumlahcuti = $sumdays - $jumlahlibur;

        return $jumlahcuti;
    }

    function block_leave() {
        $iduser = $this->session->userdata('sess_userid');
        $rowparam = $this->param->get_param($iduser);        
        $checkparam = ($rowparam == '' or $rowparam == null) ? 'empty' : 'exist'; 
        
        if ($checkparam == 'exist') {
            $parameter = $rowparam->ParamValue;
            if ($parameter == $iduser) {
                $accessdel = 'true';
            } else {
                $accessdel = 'false';
            }
        } else {
                 $accessdel = 'false';
        }
        
        $id = $this->input->post('id');
        $row = $this->leave->get_type_leave($id);
        $check = ($row == null or $row == '') ? 'empty' : 'exist';
        if ($check !== 'empty') {
            $access = $row->FlagInput;
            $valid = 'true';
        } else {
            $access = '';
            $valid = 'false';
        }
        $json = '{ "btnaccess":"' . $access . '",
                    "btndel":"' . $accessdel . '",
                   "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }


     function reference_leave() {
        $nip = $this->input->post('nip');
        $key = $this->input->post('key');
        $row = $this->employee->get_by_nip($nip);
        $job = $row->IDJobGroup;
        $result = $this->leave->getall_reference($job);

        if ($result and $key == 'add') {
            foreach ($result as $row) {
                $data .= "<option value='$row[CodeType]'>$row[DescType]</option>\n";
            }
            echo $data;
            
        } else if ($result and $key == 'edit2') {
            foreach ($result as $row) {
                $data .= "<option value='$row[CodeType]'>$row[DescType]</option>\n";
            }
            echo $data;
            
        } else if ($key == 'edit') {
            $codetype = $this->session->userdata('codetype');
            $desc = $this->session->userdata('desc');
            $data .= "<option value='$codetype'>$desc</option>";
            echo $data;
        }
    }
	

    function addnew() {
        $data['default']['f01'] = ''; //IDemployee
        $data['default']['f02'] = ''; //FullName
        $data['default']['f03'] = ''; //IDPengganti
        $data['default']['f04'] = ''; //TglPengajuan
        $data['default']['f05'] = ''; //TglCutiDari
        $data['default']['f06'] = ''; //TglCutiSampai  
        

       $query = $this->leave->getall_reference_edit()->result();
        $i = 0;
        foreach ($query as $r) {
            $data['default']['f07'][-1]['value'] = NULL;
            $data['default']['f07'][-1]['display'] = '-Please Select-';
            $data['default']['f07'][$i]['value'] = $r->CodeType;
            $data['default']['f07'][$i]['display'] = $r->DescType;
            $i++;
        }


        //SicknessLetter
        $data['default']['f08'][1]['value'] = "1";
        $data['default']['f08'][1]['display'] = "Yes";
        $data['default']['f08'][1]['checked'] = "";
        $data['default']['f08'][2]['value'] = "0";
        $data['default']['f08'][2]['display'] = "No";

        $data['default']['f09'] = ''; //Note  

        $data['type'] = '';
	$data['flag'] = "add";
        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('trx07/home/addpost');
        $this->load->view('trx07/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        //$this->form_validation->set_rules('f03', 'Person In Charger', 'required');
        //$this->form_validation->set_rules('f04', 'Request Date', 'required');
        $this->form_validation->set_rules('f05', 'Leave Date', 'required');
        $this->form_validation->set_rules('f06', 'Until Date', 'required');
        $this->form_validation->set_rules('f09', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f03 = $this->anti_xss($this->input->post('f03'));            
	    $f04 = ($this->input->post("f04") == '' or $this->input->post("f04") == null) ? null : date('Y-m-d', strtotime($this->input->post("f04")));	
            $f05 = date('Y-m-d', strtotime($this->input->post('f05')));
            $f06 = date('Y-m-d', strtotime($this->input->post('f06')));
            $f07 = $this->anti_xss($this->input->post('f07'));
            $f08 = $this->anti_xss($this->input->post('f08'));
            $f09 = $this->anti_xss($this->input->post('f09'));

	    $rowpersonal = $this->employee->get_by_nip($f01);
            $job = $rowpersonal->IDJobGroup;
            if ($f07 == 'AL' AND $job == 'LT' OR $job == 'LK') {
                $leavetype = 'OL';
            } else {
                $leavetype = $f07;
            }

            $row = $this->leave->get_rest_leave_employee($f01);
            $amountleave = $row->Jml;
            $sumleave = $this->long_leave($f05, $f06);
            $rest = $amountleave - $sumleave;
            $restleave = ($leavetype == 'AL') ? $rest : $amountleave;
            //$recordmaster = array("Jml" => $restleave);
            //$this->leave->update_master($f01, $recordmaster);

            if ($restleave <= 0) {
                $sisacuti = 0;
            } else {
                $sisacuti = $restleave;
            }

            $record = array(
                'FlagInput' => 'hrd',
                'IDEmployee' => $f01,
                'IDPengganti' => $f03,
                'TglPengajuan' => $f04,
                'TglCutiDari' => $f05,
                'TglCutiSampai' => $f06,
                'TotalCuti' => $sumleave,
                'SisaCuti' => $sisacuti,
                'Jenis' => $f07,
                'SickLetter' => $f08,
                'Alasan' => TRIM($f09),
                'FPgt' => 'true',
                'FPgt_tgl' => $this->Datetime,
                'FAts' => 'true',
                'FAts_tgl' => $this->Datetime,
                'FHrd' => 'true',
                'FHrd_tgl' => $this->Datetime,
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
                'controller' => site_url('trx07/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $sumdays = (strtotime($f05) - strtotime($f04)) / (24 * 3600) + 1;

            if ($sumdays <= '7' and $f07 == 'AL') {
                $alert = 'Inserting data failed, the Annual Leave must be submitted at least at the last 7 days before leaving date';
                $status = 'false';
                $msg = 'ERROR';
            } else {
                //update reserve==================================================================
                    $whres  = array(
                        "IDEmployee"    => $f01,
                        "Status"        => "1"
                    );
                    $res = $this->leave->get_res($whres)->row();
                    if ($res != NULL){
                        if ($leave->TotalCuti < $res->JmlReserve){
                            $newjml = ($res->JmlReserve)-$leave->TotalCuti;
                        }
                        if ($leave->TotalCuti >= $res->JmlReserve){
                            $newjml = 0;
                        }
                        $recupdres = array("JmlReserve" => $newjml);
                        $this->leave->upd_res($whres,$recupdres);
                    }
//                    ===================================================================================                
                $this->leave->insert($record);
                $this->logs->insert($recordlog);
                $alert = 'insert data, success';
                $status = 'true';
                $msg = '';
            }

            $mesg = $alert;
            $valid = $status;
            $err_f01 = '';
            $err_f03 = '';
            $err_f04 = $msg;
            $err_f05 = '';
            $err_f06 = '';
            $err_f07 = '';
            $err_f08 = '';
            $err_f09 = '';
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
            $err_f07 = form_error('f07');
            $err_f08 = form_error('f08');
            $err_f09 = form_error('f09');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",                      
                       "err_f03":"' . $err_f03 . '",                      
                       "err_f04":"' . $err_f04 . '",                      
                       "err_f05":"' . $err_f05 . '",                      
                       "err_f06":"' . $err_f06 . '",                      
                       "err_f07":"' . $err_f07 . '",                      
                       "err_f08":"' . $err_f08 . '",                      
                       "err_f09":"' . $err_f09 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->leave->get_by_id($id);

        $data['default']['f01'] = $row->IDEmployee;
        $data['default']['f02'] = $row->FullName;
        $data['default']['f03'] = $row->IDPengganti;
        $data['default']['f04'] = date('d-m-Y', strtotime($row->TglPengajuan));
        $data['default']['f05'] = date('d-m-Y', strtotime($row->TglCutiDari));
        $data['default']['f06'] = date('d-m-Y', strtotime($row->TglCutiSampai));
        //typeleave
        $query = $this->leave->getall_reference_edit()->result();
        $i = 0;
        foreach ($query as $r) {
            $data['default']['f07'][$i]['value'] = $r->CodeType;
            $data['default']['f07'][$i]['display'] = $r->DescType;
            if ($r->CodeType == $row->Jenis) {
		 
                $session = array(
                            "codetype"=>$r->CodeType,
                            "desc"=>$r->DescType
                );
                $this->session->set_userdata($session);    
		
                $data['default']['f07'][$i]['selected'] = "SELECTED";
            }
            $i++;
        }
        //SickLetter
        $data['default']['f08'][1]['value'] = "1";
        $data['default']['f08'][1]['display'] = "Yes";
        $data['default']['f08'][1]['checked'] = "";
        $data['default']['f08'][2]['value'] = "0";
        $data['default']['f08'][2]['display'] = "No";

        if ($row->SickLetter == '1') {
            $data['default']['f08'][1]['checked'] = "CHECKED";
        } else if ($row->SickLetter == '0') {
            $data['default']['f08'][2]['checked'] = "CHECKED";
        }
        $data['default']['f09'] = $row->Alasan;
        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';

        $data['flag'] = "edit";
        $data['type'] = $row->Jenis;
        $data['url_post'] = site_url('trx07/home/editpost');
        $this->load->view('trx07/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        //$this->form_validation->set_rules('f03', 'Person In Charger', 'required');
        //$this->form_validation->set_rules('f04', 'Request Date', 'required');
        $this->form_validation->set_rules('f05', 'Leave Date', 'required');
        $this->form_validation->set_rules('f06', 'Until Date', 'required');
        $this->form_validation->set_rules('f09', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = ($this->input->post("f04") == '' or $this->input->post("f04") == null) ? null : date('Y-m-d', strtotime($this->input->post("f04")));
            $f05 = date('Y-m-d', strtotime($this->input->post('f05')));
            $f06 = date('Y-m-d', strtotime($this->input->post('f06')));
            $f07 = $this->anti_xss($this->input->post('f07'));
            $f08 = $this->anti_xss($this->input->post('f08'));
            $f09 = $this->anti_xss($this->input->post('f09'));

	    $rowpersonal = $this->employee->get_by_nip($f01);
            $job = $rowpersonal->IDJobGroup;
            if ($f07 == 'AL' AND $job == 'LT' OR $job == 'LK') {
                $leavetype = 'OL';
            } else {
                $leavetype = $f07;
            }		

            $row = $this->leave->get_rest_leave_employee($f01);
            $amountleave = $row->Jml;
            $sumleave = $this->long_leave($f05, $f06);
            $rest = $amountleave - $sumleave;
            $restleave = ($leavetype == 'AL') ? $rest : $amountleave;
            //$recordmaster = array("Jml" => $restleave);
            //$this->leave->update_master($f01, $recordmaster);

            if ($restleave <= 0) {
                $sisacuti = 0;
            } else {
                $sisacuti = $restleave;
            }

            $record = array(
                'IDEmployee' => $f01,
                'IDPengganti' => $f03,
                'TglPengajuan' => $f04,
                'TglCutiDari' => $f05,
                'TglCutiSampai' => $f06,
                'TotalCuti' => $sumleave,
                'SisaCuti' => $sisacuti,
                'Jenis' => $f07,
                'SickLetter' => $f08,
                'Alasan' => TRIM($f09),
                'FPgt' => 'true',
                'FPgt_tgl' => $this->Datetime,
                'FAts' => 'true',
                'FAts_tgl' => $this->Datetime,
                'FHrd' => 'true',
                'FHrd_tgl' => $this->Datetime,
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
                'controller' => site_url('trx07/home/addnew'),
                'activities' => 'edit data id ' . $id
            );

            $sumdays = (strtotime($f05) - strtotime($f04)) / (24 * 3600) + 1;

            if ($sumdays <= '7' and $f07 == 'AL') {
                $alert = 'Update Data, failed because your range days for Annual Leave :' . $sumdays . 'less than 7 days';
                $status = 'false';
                $msg = 'ERROR';
            } else {
                $this->leave->update($id, $record);
                $this->logs->insert($recordlog);
                $alert = 'Update data, success';
                $status = 'true';
                $msg = '';
            }

            $mesg = $alert;
            $valid = $status;
            $err_f01 = '';
            $err_f03 = '';
            $err_f04 = $msg;
            $err_f05 = '';
            $err_f06 = '';
            $err_f07 = '';
            $err_f08 = '';
            $err_f09 = '';
        } else {
            $mesg = 'Update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
            $err_f07 = form_error('f07');
            $err_f08 = form_error('f08');
            $err_f09 = form_error('f09');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",                      
                       "err_f03":"' . $err_f03 . '",                      
                       "err_f04":"' . $err_f04 . '",                      
                       "err_f05":"' . $err_f05 . '",                      
                       "err_f06":"' . $err_f06 . '",                      
                       "err_f07":"' . $err_f07 . '",                      
                       "err_f08":"' . $err_f08 . '",                      
                       "err_f09":"' . $err_f09 . '"' .
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
        $leave  = $this->leave->get_by_id($id);
        $whres  = array(
            "IDEmployee"    => $leave->IDEmployee,
            "Status"        => "1"
        );
        $getres     = $this->leave->get_res($whres)->row();
        if ($getres->JmlReserve < $getres->JmlDef){
            $newjmlres  = ($leave->TotalCuti)+($getres->JmlReserve);
            if ($newjmlres > $getres->JmlDef){
                $newjmlres = $getres->JmlDef;
            }
            $newrecres = array(
                "JmlReserve" => $newjmlres
            );
            $this->leave->upd_res($whres,$newrecres);
        }
        $this->leave->update($id, $record);
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
        $objSheet->setTitle('leave report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:L1')->getFont()->setBold(true)->setSize(12);

        // write header        
        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');
        $objSheet->getCell('C1')->setValue('Person in Charge');
        $objSheet->getCell('D1')->setValue('Group');
        $objSheet->getCell('E1')->setValue('Request Date');
        $objSheet->getCell('F1')->setValue('Leave Date');
        $objSheet->getCell('G1')->setValue('Until Date');
        $objSheet->getCell('H1')->setValue('Type');
        $objSheet->getCell('I1')->setValue('Sickness Leave');
        $objSheet->getCell('J1')->setValue('Amount of Leave');
        $objSheet->getCell('K1')->setValue('Rest of Leave');
        $objSheet->getCell('L1')->setValue('Note');


        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->leave->getall_data($f,$u,$g);


        if ($result !== 'empty') {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $gp = $this->libfun->get_name_group($row['IDJobGroup']);

                $objSheet->getCell('A' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('B' . $i)->setValue($row['Name']);
                $objSheet->getCell('C' . $i)->setValue($row['PersonCharge']);
                $objSheet->getCell('D' . $i)->setValue($gp);
                $objSheet->getCell('E' . $i)->setValue($row['TglPengajuan']);
                $objSheet->getCell('F' . $i)->setValue($row['TglCutiDari']);
                $objSheet->getCell('G' . $i)->setValue($row['TglCutiSampai']);
                $objSheet->getCell('H' . $i)->setValue($row['DescType']);
                $objSheet->getCell('I' . $i)->setValue($row['SickLetter']);
                $objSheet->getCell('J' . $i)->setValue($row['TotalCuti']);
                $objSheet->getCell('K' . $i)->setValue($row['SisaCuti']);
                $objSheet->getCell('L' . $i)->setValue($row['Alasan']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:L' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:L' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:L1')->getBorders()->
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
            $objSheet->getColumnDimension('J')->setAutoSize(true);
            $objSheet->getColumnDimension('K')->setAutoSize(true);
            $objSheet->getColumnDimension('L')->setAutoSize(true);

            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "leave_attendance" . $ext);
            $data = file_get_contents($path_file . "leave_attendance" . $ext);
            force_download("leave_attendance" . $ext, $data);
        }
    }

    function iframe($id) {
        $data['url_printdata'] = site_url('trx07/home/printdata/' . $id);
        $this->load->view('trx07/iframe', $data);
    }

    function printdata($id) {
        $this->load->library('mpdf54/mpdf');
        $this->mpdf = new mPDF('c', array(216, 304), '12', 'dejavusans', 5, 5, 5, 5, 0, 0);
        $row = $this->leave->get_by_id_request($id);
        $checkdata = ($row == '' or $row == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {

            $data['nip'] = $row->IDEmployee;
            $data['name'] = $row->Name;
            $data['position'] = $row->Position;
            $data['dept'] = $row->DescStructure;
            $data['parent'] = $row->Parent;
            $data['hiredate'] = date('d-m-Y', strtotime($row->HireDate));
            $data['amountleave'] = $this->sisacuti($row->IDEmployee,$row->HireDate);

            $type = $row->Jenis;
            if ($type == 'AL') {
                $data['type1'] = '<b>*</br>';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'MTL') {
                $data['type1'] = '';
                $data['type2'] = '<b>*</br>';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'MRL') {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '<b>*</br>';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'CL') {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '<b>*</br>';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'SL') {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '<b>*</br>';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'OL') {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '<b>*</br>';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'CIR') {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '<b>*</br>';
                $data['type8'] = '';
            } else {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            }

	    $rowhrd = $this->param->get_hrd();
            $rowhrm = $this->param->get_hrdmgr();	
	
            $data['fromdate'] = date('d-m-Y', strtotime($row->TglCutiDari));
            $data['untildate'] = date('d-m-Y', strtotime($row->TglCutiSampai));
            $data['sumleave'] = $row->TotalCuti . " Days";
            $data['cutleave'] =  $row->SisaCuti. '   ';
            $data['reasonleave'] = $row->Alasan;
            $data['pengganti'] = $row->Pengganti;
            $data['daterequest'] = date('d-m-Y', strtotime($row->TglPengajuan));
            $data['supervisor'] = '';
            $data['hrd'] = $rowhrd->FullName;
            $data['hrm'] = $rowhrm->FullName;
            $data['dateacccharge'] =$row->FPgt_tgl;
            $data['dateaccparent'] =$row->FAts_tgl;
            $data['dateacchrd'] =$row->FHrd_tgl; 

            $html = $this->load->view('trx07/printdata', $data, true);
            $this->mpdf->SetHTMLFooter('
              <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
              <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE d-m-Y}</span></td>
              <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
              <td width="33%" style="text-align: right; "></td>
              </tr></table>
              ');
            $this->output->set_output($html);
            $this->mpdf->WriteHTML($html);
            // $this->mpdf->WriteHTML('<pagebreak sheet-size=210 297;/>');
            set_time_limit(60);
            $this->mpdf->Output('leave.pdf', 'I');
        }
    }

   

   function sisacuti($user,$hire){
        $leave              = $this->leave->get_dataleave($user,$hire,date('Y-m-d'));
        if ($leave == NULL) { $jmlna = 0;}
        if ($leave != NULL){
                foreach($leave as $l){
                        $i++;
                        $jml[$i] = $l->Jml;
                }
                $jmlna          = array_sum($jml);
        }
	return $jmlna;	
	
   }	
 


}