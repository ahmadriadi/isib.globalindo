<?php

class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Changenip_model', 'cnip');
        $this->load->model('public_model', 'pbl');
        $this->load->model('employee_model', 'employee');
        $this->load->model('libraryfunction_model', 'libfun');
        $this->load->model('logs_model', 'logs');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('Param_model', 'param');
        $this->load->model('uac_model', 'uac');


        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function anti_xss($source) {
        $f = stripslashes(strip_tags(htmlspecialchars($source, ENT_QUOTES)));
        return $f;
    }

    function index() {
	$rowcounter = $this->pbl->counternip();
        $idtis = $rowcounter->IDEmployeeTIS;
        $idos = $rowcounter->IDEmployeeOS;

        $temptis = "0000" . $idtis;
        $tempos = "0000000000" . $idos;

        $niptis = substr($temptis, -4);
        $nipos = substr($tempos, -10);
        
        $data['countertis'] = $niptis.'[Hiredate]';
        $data['counteros'] = $nipos;
	

        $idmenu = "221";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('mst04/home', $data);
    }

    function getdatatables() {
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
                echo $this->cnip->getdata();
            } else if ($role == '0' and $param == 'Y') {
                echo $this->cnip->getdata();
            }
        }
    }

    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '221';
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

    function counternip() {
        $niplama = substr($this->input->post('niplama'), 4, 6);
        $group = $this->input->post('jobgroup');
        $row = $this->pbl->counternip();

        if ($group == 'ST' or $group == 'LT' or $group == 'LK') {
            $id = $row->IDEmployeeTIS + 1;
            $niptemp = "0000" . $id;
            $niptis = substr($niptemp, -4);
            $nip = $niptis . $niplama;
        } else if ($group == 'OS' or $group == 'MAG') {
            $id = $row->IDEmployeeOS + 1;
            $niptemp = "0000000000" . $id;
            $nipos = substr($niptemp, -10);
            $nip = $nipos;
        }

        $valid = 'true';
        $json = '{ "newnip":"' . $nip . '",
                   "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function autocomplete_employee() {
        $result = $this->employee->find_employee_active();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                'fullname' => strtoupper($row->FullName),
                'jobgroup' => $row->IDJobGroup
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    function addnew() {

        $data['default']['f01'] = ''; //idemployee lama
        $data['default']['f02'] = ''; //idemployee baru
        $data['default']['f03'] = ''; //fullname
        $query = $this->employee->get_rjob_change()->result();
        $i = 0;
        foreach ($query as $r) {
            $i++;
            $data['default']['f04'][$i]['value'] = $r->IDJobGroup;
            $data['default']['f04'][$i]['display'] = $r->GroupName;
        }

        $data['default']['f05'] = ''; //Note
        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';
        $data['url_post'] = site_url('mst04/home/addpost');
        $this->load->view('mst04/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'Old IDEmployee', 'required');
        $this->form_validation->set_rules('f02', 'New IDEmployee', 'required');
        $this->form_validation->set_rules('f05', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $rowcounter = $this->pbl->counternip();
            $idtis = $rowcounter->IDEmployeeTIS + 1;
            $idos = $rowcounter->IDEmployeeOS + 1;

            $temptis = "0000" . $idtis;
            $tempos = "0000000000" . $idos;

            $niptis = substr($temptis, -4);
            $nipos = substr($tempos, -10);


            if ($f04 == 'ST' or $f04 == 'LT' or $f04 == 'LK') {
                $countnip = substr($f02, 0, 4);
                $recordcounter = array("IDEmployeeTIS" => $countnip);

                if ($niptis == $countnip) {
                    $this->pbl->update_counter($recordcounter);
                    $alertdata = 'Savedata';
                } else {
                    $alertdata = 'Dontsave';
                }

                $alertconf = $alertdata;
            } else if ($f04 == 'OS' or $f04 == 'MAG') {
                $countnip = $f02;
                $recordcounter = array("IDEmployeeOS" => $countnip);

                if ($nipos == $countnip) {
                    $this->pbl->update_counter($recordcounter);
                    $alertdata = 'Savedata';
                } else {
                    $alertdata = 'Dontsave';
                }

                $alertconf = $alertdata;
            }


            $record = array(
                'NIPLama' => $f01,
                'NIPBaru' => $f02,
                'Note' => $f05,
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
                'controller' => site_url('mst04/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $rcheck = $this->cnip->checkdata($f01);
            if ($rcheck == 'exist' or $alertconf == 'Dontsave') {
                $alert = 'insert failed, idemployee  = ' . $f01 . ' already exist';
                $status = 'false';
            } else {
                $this->cnip->insert($record);
                $this->logs->insert($recordlog);
                $alert = 'insert data, success with old idemployee = ' . $f01;
                $status = 'true';
            }

            $mesg = $alert;
            $valid = $status;
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
                       "err_f02":"' . $err_f02 . '",                    
                       "err_f03":"' . $err_f03 . '",                    
                       "err_f04":"' . $err_f04 . '",                    
                       "err_f05":"' . $err_f05 . '"' .
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

        $this->cnip->update($id, $record);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }
    
    
    
     function change_idemployee() {
        //Security
        $this->logdata();
        $this->printdata();
        $this->canceldata();
        $this->profiledata();
        $this->logindata();
        $this->activationdata();
        $this->buttondata();
        $this->menudata();
        
        //Employee
        $this->personal_emp_h();
        $this->personal_emp_d();
        $this->personal_emp_other();
        $this->master_leave();
        $this->reserve_leave();
        $this->transaction_leave();
        $this->emp_param();
        $this->memofrom();
        $this->memoto();
        
        //Public
        $this->personal_pbl();
        $this->course();
        $this->education();
        $this->family();
        $this->job();
        $this->language();
        $this->experience();
        $this->contact();
        
        //Attendance
        $this->cardmap();
        $this->rawdata();
        $this->presence();
        $this->overtime();
        $this->incomplete();
        $this->sickness();
        $this->travel();
        $this->leavepermit();
        $this->suspension();
        $this->leavework();
        
        //Payroll
        $this->add_overtime();
        $this->add_leave();
        $this->personal_pay();
        $this->loan_h();
        $this->loan_d();
        $this->p_insentive();
        $this->dailysalary();
        $this->dailyovertime();
        $this->addition();
        $this->deduction();
        $this->manualdeduction();
        $this->payrollslip();
        $this->slipspecial();
        
        
        //Estimate
        $this->estimator();
        $this->sales();
        $this->requestby();
        $this->editor();
        
        //Send Email
        $this->prephare_email();
        $this->sendemail();
        
        
                  
        $mesg = "Process Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }
     
    function logdata() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_logs($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("username"=>$rowdata['NIPBaru']);
                        $this->cnip->update_logdata($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
     function printdata() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_print($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_print($id,$record);
                        
                    }                   
                }
            }
        }
    }
        
     function canceldata() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_cancel($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_cancel($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function profiledata() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_profile($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDUser"=>$rowdata['NIPBaru']);
                        $this->cnip->update_profile($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function logindata() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_login($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $password =  md5($rowdata['NIPBaru'].'123');
                        $record = array(
                                    "Username"=>$rowdata['NIPBaru'],
                                    "Password"=>$password,
                                
                                );
                        $this->cnip->update_login($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function activationdata() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_activation($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("Username"=>$rowdata['NIPBaru']);
                        $this->cnip->update_activation($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
      function buttondata() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_button($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDUser"=>$rowdata['NIPBaru']);
                        $this->cnip->update_button($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function menudata() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_menu($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDUser"=>$rowdata['NIPBaru']);
                        $this->cnip->update_menu($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
    function personal_emp_h() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_personalh($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_personalh($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    function personal_emp_d() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_personald($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_personald($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    function personal_emp_other() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_fakes($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_fakes($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function master_leave() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_mleave($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_mleave($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
      function reserve_leave() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_rleave($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_rleave($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
    function transaction_leave() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_tleave($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_tleave($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
    function emp_param() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_eparam($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("ParamValue"=>$rowdata['NIPBaru']);
                        $this->cnip->update_eparam($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
     function memofrom() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_memo($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("FromIDUser"=>$rowdata['NIPBaru']);
                        $this->cnip->update_memo($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
    function memoto() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_memo2($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("ToIDUser"=>$rowdata['NIPBaru']);
                        $this->cnip->update_memo($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
      function personal_pbl() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_person($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_person($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function course() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_course($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_course($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function education() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_education($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_education($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function family() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_family($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_family($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function job() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_job($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_job($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function language() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_lang($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_lang($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function experience() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_exp($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_exp($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
    function contact() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_contact($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['IDEmployee'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_contact($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
    function cardmap() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_card($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_card($id,$record);
                        
                    }                   
                }
            }
        }
    }
    function rawdata() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_rawdata($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_rawdata($id,$record);
                        
                    }                   
                }
            }
        }
    }
    function presence() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_presence($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['IDPresence'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_presence($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    function overtime() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_overtime($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_overtime($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function incomplete() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_incomplete($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_incomplete($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function sickness() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_sick($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_sick($id,$record);
                        
                    }                   
                }
            }
        }
    }
     function travel() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_travel($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_travel($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function leavepermit() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_leavepermit($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_leavepermit($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function suspension() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_suspension($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_suspension($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
     function leavework() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_leavework($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_leavework($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     
     function add_overtime() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_addovertime($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_addovertime($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
      function add_leave() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_addleave($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['IDEmployee'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_addleave($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
    function personal_pay() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_personpay($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_personpay($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    function loan_h() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_person_loanh($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_person_loanh($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    function loan_d() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_person_loand($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_person_loand($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
     function p_insentive() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_pinsentive($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_pinsentive($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function dailysalary() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_dailysalary($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_dailysalary($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function dailyovertime() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_dailyovertime($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_dailypvertime($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
     function addition() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_addition($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_addition($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function deduction() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_deduc($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_deduc($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function manualdeduction() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_mandeduc($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_mandeduc($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function payrollslip() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_payslip($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_payslip($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
     function slipspecial() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_slip($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_slip($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
    
     function estimator() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_estimator($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_estimator($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    
    function sales() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_request($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("ProjectExecutive"=>$rowdata['NIPBaru']);
                        $this->cnip->update_request($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    function requestby() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_request2($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("RequestBy"=>$rowdata['NIPBaru']);
                        $this->cnip->update_request($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    function editor() {
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            foreach ($resultnip as $rowdata) {
                $resultdata = $this->cnip->get_editor($rowdata['NIPLama']);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';
                if ($checkdata == 'exist') {
                    foreach ($resultdata as $row) {
                        $id = $row['ID'];
                        $record = array("IDEmployee"=>$rowdata['NIPBaru']);
                        $this->cnip->update_editor($id,$record);
                        
                    }                   
                }
            }
        }
    }
    
    function prephare_email(){
        $resultnip = $this->cnip->getdata_changenip();
        $checknip = ($resultnip == '' or $resultnip == null) ? 'empty' : 'exist';
          if ($checknip == 'exist') {
               foreach ($resultnip as $rowdata) {
                   $niplama = $rowdata['NIPLama'];
                   $nipbaru = $rowdata['NIPBaru'];
                   $note = $rowdata['Note'];
                   $name = $this->cnip->getname($nipbaru);
                   
                   $recordhis  =array(
                       "NIPLama"=>$niplama,
                       "NIPBaru"=>$nipbaru,
                       "Note"=>$note,
                       "AddedBy"=>$rowdata['AddedBy'],
                       "AddedDate"=>$rowdata['AddedDate'],
                       "AddedIP"=>$rowdata['AddedIP'],                       
                       "EditedBy"=>$this->User,                       
                       "EditedDate"=>$this->Datetime,                       
                       "EditedIP"=>$this->Ip                       
                   );
                   
                    $recordemail  =array(
                       "Nama"=>$name,
                       "NIPLama"=>$niplama,
                       "NIPBaru"=>$nipbaru
                   );
                    
                    
                    $this->cnip->insert_his($recordhis);
                    $this->cnip->insert_mail($recordemail);
                    $this->cnip->truncate_changenip($recordemail);
                   
               }
              
          }
    }
    
    
     function sendemail() {
        $result = $this->cnip->getlist_email();
        if ($result) {
            $no = 0;
            foreach ($result as $row) {
                $no++;
                $nama = $row['Nama'];
                $niplama = $row['NIPLama'];
                $nipbaru = $row['NIPBaru'];
                $dept = $row['Departemen'];
                $group = $this->libfun->get_name_group($row['IDJobGroup']);
                
                $table = "<tr>";
                $table.= "    <td>" . $no . "</td>";
                $table.= "    <td>" . $nama . "</td>";
                $table.= "    <td>" . $niplama . "</td>";
                $table.= "    <td>" . $nipbaru . "</td>";
                $table.= "    <td>" . $dept . "</td>";
                $table.= "    <td>" . $group . "</td>";
                $table.= "</tr>";

                $data['resultdata'][$no]['tabletr'] = $table;
            }
        }

        $data['hrdname'] = 'ARYANA SAPUTRI';
        $html = $this->load->view('mst04/reportnip', $data, true);
        $subject = "PERUBAHAN NIP KARYAWAN DAN MITRA KERJA";
        $to = "aryana@trias.loc";
        $message = $html;
        /*
        $cc = array(
            'doris@trias.loc',
	    'intan@trias.loc',	
            'denny@trias.loc',
            'riadi@trias.loc');
         * 
         */
        
         $cc = array(
            'doris@trias.loc',
	    'intan@trias.loc',	
            'denny@trias.loc',
            'avanki@trias.loc');

        $this->sendmail->internalmail($to,$subject,$message, $cc);
        
        $recordhis = array(
            "Nama"=>$nama,
            "NIPLama"=>$niplama,
            "NIPBaru"=>$nipbaru,
        );
        
        $this->cnip->insertmail_his($recordhis);
        $this->cnip->truncate_sendmail();
        
        
    }
     
    
    
    

}

