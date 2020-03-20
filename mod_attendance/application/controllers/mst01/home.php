<?php

//organisasi
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('employee_model', 'employee');
        $this->load->model("public_model", "pbl");
        $this->load->model("Historytable_model", "history");
        $this->load->model("param_model", "parameter");
        $this->load->model("libraryfunction_model", "libfun");
        $this->load->model('menu_model', 'menu');
        $this->load->model('ulogin_model', 'ulogin');


        $this->load->model("address_model", "addr");
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('Param_model', 'param');
        $this->load->model('uac_model', 'uac');
        $this->load->model('picture_model', 'picturedata');

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
        $query = $this->employee->get_rjob()->result();
        $i = 0;
        foreach ($query as $r) {
            $i++;
            $data['default']['group'][$i]['value'] = $r->IDJobGroup;
            $data['default']['group'][$i]['display'] = $r->GroupName;
        }

        $data['default']['location'][0]['value'] = 'AL';
        $data['default']['location'][0]['display'] = 'ALL';
        $data['default']['location'][0]['selected'] = "SELECTED";
        $data['default']['location'][1]['value'] = '1';
        $data['default']['location'][1]['display'] = 'Kantor';
        $data['default']['location'][2]['value'] = '2';
        $data['default']['location'][2]['display'] = 'Laur Kantor';

        $idmenu = "124";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('mst01/main', $data);
    }

    function delete_employee($id) {
        $row_employee = $this->pbl->get_by_id($id);
        $idemployee = $row_employee->IDEmployee;

        $record = array(
            "DeletedBy" => $this->User,
            "DeletedIP" => $this->Ip,
            "DeletedDate" => $this->Datetime,
            "DeleteFlag" => 'D'
        );

        $this->pbl->delete_personal_h_emp($idemployee, $record);
        $this->pbl->delete_personal_d_emp($idemployee, $record);
        $this->pbl->delete_personal_pbl($idemployee, $record);
        $this->pbl->delete_personal_train_pbl($idemployee, $record);
        $this->pbl->delete_personal_edu_pbl($idemployee, $record);
        $this->pbl->delete_personal_fam_pbl($idemployee, $record);
        $this->pbl->delete_personal_lang_pbl($idemployee, $record);
        $this->pbl->delete_personal_workexp_pbl($idemployee, $record);
        $this->pbl->delete_personal_job_pbl($idemployee, $record);

        /*
          $this->pbl->delete_personal_h_emp($idemployee);
          $this->pbl->delete_personal_d_emp($idemployee);
          $this->pbl->delete_personal_pbl($idemployee);
          $this->pbl->delete_personal_edu_pbl($idemployee);
          $this->pbl->delete_personal_fam_pbl($idemployee);
          $this->pbl->delete_personal_lang_pbl($idemployee);
          $this->pbl->delete_personal_workexp_pbl($idemployee);
          $this->pbl->delete_personal_job_pbl($idemployee);
         */

        $mesg = "Delete Data, Success";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function dataemployee() {

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
                echo $this->pbl->allemployee();
            } else if ($role == '0' and $param == 'Y') {
                echo $this->pbl->allemployee();
            }
        }
    }

    function dataemployee_pasif() {
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
                echo $this->pbl->allemployee_pasif();
            } else if ($role == '0' and $param == 'Y') {
                echo $this->pbl->allemployee_pasif();
            }
        }
    }

    function get_departement() {
        $id = $this->input->post('iddept');
        $rowdepartement = $this->pbl->get_departement($id)->row();

        $value = ($rowdepartement == '' or $rowdepartement == null) ? $id : $rowdepartement->DescStructure;

        $json = '{ 
                   "departemen":"' . $value . '",
                   "valid":"true"' .
                '}';
        echo $json;
    }

    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '124';
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

    function datahome($flag = '', $id = '') {
        error_reporting(0);
        $row = $this->pbl->get_by_id($id);
        $nip = $row->IDEmployee;
        $data['employees'] = json_encode($this->pbl->get_employee()->result());
        $data['departement'] = $this->pbl->get_department();
        $data['flag'] = $flag;
        $data['userid'] = $nip;
        $data['statusemployee'] = $row->Status;
        $this->load->view('mst01/home', $data);
        if ($flag == "dup") {
            $this->session->set_userdata('idemp_on_clipboard', $nip);
        }
    }

//    job  =========================================================================
    function get_job($nip='') {
        $iduser = $nip;
        $job = $this->pbl->get_job($iduser)->row();
        echo json_encode($job);
    }

    function save_job($nip) {
        $iduser = $nip;
        $idparent = $this->anti_xss($this->input->post("idparent"));
        $empstat = $this->anti_xss($this->input->post("empstat"));
        $jobloc = $this->anti_xss($this->input->post("jobloc"));
        $jobgrp = $this->anti_xss($this->input->post("jobgrp"));
        $depart = $this->anti_xss($this->input->post("depart"));
        $jobpos = $this->anti_xss($this->input->post("jobpos"));
        $unitjob = $this->anti_xss($this->input->post("unitjob"));
        $hiredate = ($this->input->post("hiredate") == '' or $this->input->post("hiredate") == null) ? null : date('Y-m-d', strtotime($this->input->post("hiredate")));
        $datefirst = ($this->input->post("datefirst") == '' or $this->input->post("datefirst") == null) ? null : date('Y-m-d', strtotime($this->input->post("datefirst")));
        $datestartprob = ($this->input->post("datestartprob") == '' or $this->input->post("datestartprob") == null) ? null : date('Y-m-d', strtotime($this->input->post("datestartprob")));
		  $dateendprob = ($this->input->post("dateendprob") == '' or $this->input->post("dateendprob") == null) ? null : date('Y-m-d', strtotime($this->input->post("dateendprob")));        
        $dateprob = ($this->input->post("dateprob") == '' or $this->input->post("dateprob") == null) ? null : date('Y-m-d', strtotime($this->input->post("dateprob")));
        $contnew = ($this->input->post("contnew") == '' or $this->input->post("contnew") == null) ? null : date('Y-m-d', strtotime($this->input->post("contnew")));
        $contend = ($this->input->post("contend") == '' or $this->input->post("contend") == null) ? null : date('Y-m-d', strtotime($this->input->post("contend")));
        $fullname = $this->anti_xss($this->input->post("fullname"));
        $nickname = $this->anti_xss($this->input->post("nickname"));
        $bankaccount = $this->anti_xss($this->input->post("nobank"));
        $note = $this->anti_xss($this->input->post("note"));
        $status = $this->anti_xss($this->input->post("status"));
        $flag = $this->anti_xss($this->input->post("flag"));



        $rowcounter = $this->pbl->counternip();

        $idtis = $rowcounter->IDEmployeeTIS + 1;
        $idos = $rowcounter->IDEmployeeOS + 1;

        $temptis = "0000" . $idtis;
        $tempos = "0000000000" . $idos;

        $niptis = substr($temptis, -4);
        $nipos = substr($tempos, -10);




        if ($flag == 'add' and $jobgrp == 'ST' or $jobgrp == 'LT' or $jobgrp == 'LK') {
            $countnip = substr($nip, 0, 4);
            $recordcounter = array("IDEmployeeTIS" => $countnip);

            if ($niptis == $countnip) {
                $this->pbl->update_counter($recordcounter);
                $alertdata = 'Savedata';
            } else {
                $alertdata = 'Dontsave';
            }

            $alertconf = $alertdata;
        } else if ($flag == 'add' and $jobgrp == 'OS' or $jobgrp == 'MAG') {
            $countnip = $nip;
            $recordcounter = array("IDEmployeeOS" => $countnip);

            if ($nipos == $countnip) {
                $this->pbl->update_counter($recordcounter);
                $alertdata = 'Savedata';
            } else {
                $alertdata = 'Dontsave';
            }

            $alertconf = $alertdata;
        } else if ($flag == 'dup' and $jobgrp == 'ST' or $jobgrp == 'LT' or $jobgrp == 'LK') {
            $countnip = substr($nip, 0, 4);
            $recordcounter = array("IDEmployeeTIS" => $countnip);

            if ($niptis == $countnip) {
                $this->pbl->update_counter($recordcounter);
                $alertdata = 'Savedata';
            } else {
                $alertdata = 'Dontsave';
            }

            $alertconf = $alertdata;
        } else if ($flag == 'dup' and $jobgrp == 'OS' or $jobgrp == 'MAG') {
            $countnip = $nip;
            $recordcounter = array("IDEmployeeOS" => $countnip);

            if ($nipos == $countnip) {
                $this->pbl->update_counter($recordcounter);
                $alertdata = 'Savedata';
            } else {
                $alertdata = 'Dontsave';
            }

            $alertconf = $alertdata;
        } else if ($flag == 'edit') {
            $alertconf = 'Savedata';
        }

        if ($flag == 'edit') {
            $alertconf = 'Savedata';
        }




        $record = array(
            "IDEmployeeParent" => $idparent,
            "EmployeeStatus" => $empstat,
            "Location" => $jobloc,
            "JobGroup" => $jobgrp,
            "Department" => $depart,
            "Position" => $jobpos,
            "Unit" => $unitjob,
            "HireDate" => $hiredate,
            "Status" => $status,
            "DateFirstJoin" => $datefirst,
            "DateStartProbation" => $datestartprob,
            "DateEndProbation" => $dateendprob,	
            "DatePassProbation" => $dateprob,
            "DateNewContract" => $contnew,
            "DateEndContract" => $contend
        );
        $cek = $this->pbl->get_job($iduser)->result();
        if ($cek == NULL and $alertconf == 'Savedata') {
            $record['IDEmployee'] = $iduser;
            $this->pbl->insert_job($record);
        } else {
            if ($alertconf == 'Savedata') {
                $this->historydata($iduser, 'save_job', 'job');
                $this->pbl->update_job($iduser, $record);
            }
        }

        $record2 = array(
            "IDEmployeeParent" => $idparent,
            "FullName" => $fullname,
            "NickName" => $nickname,
            "BankAccount" => $bankaccount,
            "IDLocation" => $jobloc == "KAPUK" ? "1" : "2",
            "IDJobGroup" => $jobgrp,
            "IDDepartement" => $depart,
            "IDUnitGroup" => $unitjob,
            "IDJobPosition" => $jobpos,
            "HireDate" => $hiredate,
            "Status" => $status,
            "EmployeeStatus" => $empstat,
            "DateFirstJoint" => $datefirst,
            "DateStartProbation" => $datestartprob,
            "DateEndProbation" => $dateendprob,		
            "DatePassProbation" => $dateprob,
            "DateNewContract" => $contnew,
            "DateEndContract" => $contend,
            "Note" => trim($note),
            "AddedBy" => $this->User,
            "AddedDate" => $this->Datetime,
            "AddedIP" => $this->Ip
        );
        $cek2 = $this->pbl->get_det_emp($iduser)->result();
        if ($cek2 == NULL and $alertconf == 'Savedata') {
            $record2['IDEmployee'] = $iduser;
            $this->pbl->insert_det_emp($record2);
        } else {
            if ($alertconf == 'Savedata') {
                $this->historydata($iduser, 'save_job', 'personalemp_detail');
                $this->pbl->update_det_emp($iduser, $record2);
            }
        }

        $rec['IDEmployeeParent'] = $idparent;
        $rec['IDLocation'] = $jobloc == "KAPUK" ? "1" : "2";
        $rec['IDJobGroup'] = $jobgrp;
        $rec['IDUnitGroup'] = $unitjob;
        $rec['IDDepartement'] = $depart;
        $rec['HireDate'] = $hiredate;
        $rec['Status'] = $status;
        $rec['FullName'] = $fullname;
        $rec['BankAccount'] = $bankaccount;
        $rec['AddedBy'] = $this->User;
        $rec['AddedDate'] = $this->Datetime;
        $rec['AddedIP'] = $this->Ip;
        $cek3 = $this->pbl->get_prs_emp($iduser)->result();
        if ($cek3 == NULL and $alertconf == 'Savedata') {
            $rec['IDEmployee'] = $iduser;
            $this->pbl->insert_prs_emp($rec);
        } else {

            if ($alertconf == 'Savedata') {
                $this->historydata($iduser, 'save_job', 'personalemp');
                $this->pbl->update_prs_emp($iduser, $rec);
            }
        }



        $recorpbl = array(
            "IDEmployee" => $iduser,
            "IDEmployeeParent" => $idparent,
            "FullName" => $fullname,
            "NickName" => $nickname,
            "BankAccount" => $bankaccount
        );

        $result_pbl = $this->pbl->get_prs_public($iduser);
        $chekc_pbl = ($result_pbl == '' or $result_pbl == null) ? 'empty' : 'exist';
        if ($chekc_pbl == 'empty' and $alertconf == 'Savedata') {
            $this->pbl->insert_pbl_personal($recorpbl);
        } else {
            if ($alertconf == 'Savedata') {
                $this->historydata($iduser, 'save_job', 'personalpbl');
                $this->pbl->update_pbl_personal($iduser, $recorpbl);
            }
        }
        if ($cek == NULL and $cek2 == NULL and $chekc_pbl == 'empty') {
            if ($flag == "dup" and $alertconf == 'Savedata') {
                $fromid = $this->session->userdata('idemp_on_clipboard');
                $this->duplicate($fromid, $iduser);
            }
        }

        if ($alertconf == 'Savedata') {
            $statussave = 'oke';
            $warning = '';

            /* 		
              if($flag=='add' and $jobgrp=='ST'){
              $this->create_login($iduser);
              }
             */
        } else {
            $statussave = 'failed';
            $warning = 'Data failed save because IDEmployee not same with Counter in System';
        }


        $msg = array("status" => $statussave,
            "alertdata" => $warning
        );
        echo json_encode($msg);
    }

    function create_login($iduser) {
        $record = array(
            'Username' => $iduser,
            'Password' => md5($iduser . '123'),
            'Status' => 'A',
            'Role' => '0',
            'Log' => '0'
        );

        $rowlogin = $this->ulogin->check_username($f01);
        $check = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';

        if ($check == 'empty') {
            $this->add_a_user($iduser);
            $this->ulogin->insert($record);
            $this->ulogin->update_modpublic();
        }
    }

    function add_a_user($iduser) {
        $menu = $this->menu->get_menu()->result();
        $button = $this->menu->get_button()->result();
        $i = 0;
        foreach ($menu as $m) {
            $i++;
            $rec = array("IDUser" => $iduser, "IDMenu" => $m->IDMenu, "Access" => "0");
            $this->menu->add_access($rec);
            //echo $i . $m->MenuDesc . " OKE <br>";
        }
        $o = 0;
        foreach ($button as $b) {
            $o++;
            $recbtn = array("IDUser" => $iduser, "IDMenu" => $b->IDMenu, "IDButton" => $b->IDButton, "Access" => "0");
            $this->menu->add_btnaccess($recbtn);
            //echo $o . $b->IDMenu . $b->ButtonDesc . " OKE <br>";
        }
    }

//    personal =====================================================================
    function get_personal($nip) {
        $personal = $this->pbl->get_employee($nip)->row();
        echo json_encode($personal);
    }

    function duplicate($fromid, $toid) {
        $this->session->set_userdata('duplicate_id', $toid);
        $whfrom = array(
            "IDEmployee" => $fromid
        );
        $asal_h = $this->pbl->get_emp_head($whfrom)->row();
        $recordh = array(
            "FullName" => $asal_h->FullName,
            "EmailInternal" => $asal_h->EmailInternal,
            "EmailExternal" => $asal_h->EmailExternal,
            "Gender" => $asal_h->Gender
        );
        $asal_d = $this->pbl->get_emp_det($whfrom)->row();
        $recordd = array(
            "FullName" => $asal_d->FullName,
            "NickName" => $asal_d->NickName,
            "BirthPlace" => $asal_d->BirthPlace,
            "BirthDate" => $asal_d->BirthDate,
            "Height" => $asal_d->Height,
            "Weight" => $asal_d->Weight,
            "Gender" => $asal_d->Gender,
            "BloodType" => $asal_d->BloodType,
            "Citizenship" => $asal_d->Citizenship,
            "Religion" => $asal_d->Religion,
            "NoKTP" => $asal_d->NoKTP,
            "NoAKDHK" => $asal_d->NoAKDHK,
            "NoNPWP" => $asal_d->NoNPWP,
            "NoJamsostek" => $asal_d->NoJamsostek,
            "NoKPJ" => $asal_d->NoKPJ,
            "MarriageCertificate" => $asal_d->MarriageCertificate,
            "FamilyMemberCertificate" => $asal_d->FamilyMemberCertificate,
            "BankAccount" => $asal_d->BankAccount,
            "MaritalStatus" => $asal_d->MaritalStatus,
            "CoupleName" => $asal_d->CoupleName,
            "CoupleKTP" => $asal_d->CoupleKTP,
            "NumberChildren" => $asal_d->NumberChildren,
            "NoHP" => $asal_d->NoHP,
            "EmailInternal" => $asal_d->EmailInternal,
            "EmailExternal" => $asal_d->EmailExternal,
            "LiveAddress" => $asal_d->LiveAddress,
            "LiveAddressNoTelp" => $asal_d->LiveAddressNoTelp,
            "KTPAddress" => $asal_d->KTPAddress,
            "KTPAddressNoTelp" => $asal_d->KTPAddressNoTelp,
            "NoBPJSEmp" => $asal_d->NoBPJSEmp,
            "NoBPJSHlt" => $asal_d->NoBPJSHlt,
            "NoFamCert" => $asal_d->NoFamCert,
            "LiveProvince" => $asal_d->LiveProvince,
            "LiveCity" => $asal_d->LiveCity,
            "LiveSubdistrict" => $asal_d->LiveSubdistrict,
            "LiveVillage" => $asal_d->LiveVillage,
            "LiveRW" => $asal_d->LiveRW,
            "LiveRT" => $asal_d->LiveRT,
            "KTPProvince" => $asal_d->KTPProvince,
            "KTPCity" => $asal_d->KTPCity,
            "KTPSubdistrict" => $asal_d->KTPSubdistrict,
            "KTPVillage" => $asal_d->KTPVillage,
            "KTPRW" => $asal_d->KTPRW,
            "KTPRT" => $asal_d->KTPRT,
            "KTPPostalCode" => $asal_d->KTPPostalCode,
            "LivePostalCode" => $asal_d->LivePostalCode
        );
        $asal_pbl = $this->pbl->get_emp_pbl($whfrom)->row();
        $recorpbl = array(
            "FullName" => $asal_pbl->FullName,
            "NickName" => $asal_pbl->NickName,
            "BirthPlace" => $asal_pbl->BirthPlace,
            "BirthDate" => $asal_pbl->BirthDate,
            "Height" => $asal_pbl->Height,
            "Weight" => $asal_pbl->Weight,
            "Gender" => $asal_pbl->Gender,
            "BloodType" => $asal_pbl->BloodType,
            "Citizenship" => $asal_pbl->Citizenship,
            "Religion" => $asal_pbl->Religion,
            "NoKTP" => $asal_pbl->NoKTP,
            "NoAKDHK" => $asal_pbl->NoAKDHK,
            "NoNPWP" => $asal_pbl->NoNPWP,
            "NoJamsostek" => $asal_pbl->NoJamsostek,
            "NoKPJ" => $asal_pbl->NoKPJ,
            "MarriageCertificate" => $asal_pbl->MarriageCertificate,
            "FamilyMemberCertificate" => $asal_pbl->FamilyMemberCertificate,
            "BankAccount" => $asal_pbl->BankAccount,
            "MaritalStatus" => $asal_pbl->MaritalStatus,
            "CoupleName" => $asal_pbl->CoupleName,
            "CoupleKTP" => $asal_pbl->CoupleKTP,
            "NumberChildren" => $asal_pbl->NumberChildren,
            "NoHP" => $asal_pbl->NoHP,
            "InternalEmail" => $asal_pbl->InternalEmail,
            "ExternalEmail" => $asal_pbl->ExternalEmail,
            "LiveAddress" => $asal_pbl->LiveAddress,
            "LiveAddressNoTelp" => $asal_pbl->LiveAddressNoTelp,
            "KTPAddress" => $asal_pbl->KTPAddress,
            "KTPAddressNoTelp" => $asal_pbl->KTPAddressNoTelp,
            "NoBPJSEmp" => $asal_pbl->NoBPJSEmp,
            "NoBPJSHlt" => $asal_pbl->NoBPJSHlt,
            "NoFamCert" => $asal_pbl->NoFamCert,
            "LiveProvince" => $asal_pbl->LiveProvince,
            "LiveCity" => $asal_pbl->LiveCity,
            "LiveSubdistrict" => $asal_pbl->LiveSubdistrict,
            "LiveVillage" => $asal_pbl->LiveVillage,
            "LiveRW" => $asal_pbl->LiveRW,
            "LiveRT" => $asal_pbl->LiveRT,
            "KTPProvince" => $asal_pbl->KTPProvince,
            "KTPCity" => $asal_pbl->KTPCity,
            "KTPSubdistrict" => $asal_pbl->KTPSubdistrict,
            "KTPVillage" => $asal_pbl->KTPVillage,
            "KTPRW" => $asal_pbl->KTPRW,
            "KTPRT" => $asal_pbl->KTPRT,
            "KTPPostalCode" => $asal_pbl->KTPPostalCode,
            "LivePostalCode" => $asal_pbl->LivePostalCode
        );
        //duplicate family member
        $asal_pbl_fam = $this->pbl->get_family($fromid)->result();
        foreach ($asal_pbl_fam as $afam) {
            $recordpblfam = array(
                "IDEmployee" => $toid,
                "IDFamily" => $afam->IDFamily,
                "NoKTP" => $afam->NoKTP,
                "FamilyMember" => $afam->FamilyMember,
                "Name" => $afam->Name,
                "Age" => $afam->Age,
                "Address" => $afam->Address,
                "Education" => $afam->Education,
                "Occupation" => $afam->Occupation
            );
            $this->pbl->insert_family($recordpblfam);
        }
        //duplicate education background
        $asal_pbl_edu = $this->pbl->get_education($fromid)->result();
        foreach ($asal_pbl_edu as $aedu) {
            $recordpbledu = array(
                "IDEmployee" => $toid,
                "IDEducation" => $aedu->IDEducation,
                "EducationLevel" => $aedu->EducationLevel,
                "Course" => $aedu->Course,
                "SchoolName" => $aedu->SchoolName,
                "City" => $aedu->City,
                "YearFrom" => $aedu->YearFrom,
                "YearUntil" => $aedu->YearUntil,
                "Certificate" => $aedu->Certificate
            );
            $this->pbl->insert_education($recordpbledu);
        }
        //duplicate language
        $asal_pbl_lang = $this->pbl->get_language($fromid)->result();
        foreach ($asal_pbl_lang as $alang) {
            $recordpbllang = array(
                "IDEmployee" => $toid,
                "IDLanguage" => $alang->IDLanguage,
                "Language" => $alang->Language,
                "Reading" => $alang->Reading,
                "Listening" => $alang->Listening,
                "Conversation" => $alang->Conversation,
                "Writing" => $alang->Writing
            );
            $this->pbl->insert_language($recordpbllang);
        }
        //duplicate working experience
        $asal_pbl_wexp = $this->pbl->get_work($fromid)->result();
        foreach ($asal_pbl_wexp as $awexp) {
            $recordpblwexp = array(
                "IDEmployee" => $toid,
                "IDWorkExp" => $awexp->IDWorkExp,
                "CompanyName" => $awexp->CompanyName,
                "CompanyAddress" => $awexp->CompanyAddress,
                "CompanyPhone" => $awexp->CompanyPhone,
                "Position" => $awexp->Position,
                "WorkDuration" => $awexp->WorkDuration
            );
            $this->pbl->insert_work($recordpblwexp);
        }
        //duplicate training and course
        $asal_pbl_train = $this->pbl->get_tnc($fromid)->result();
        foreach ($asal_pbl_train as $atrain) {
            $recordpbltrain = array(
                "IDEmployee" => $toid,
                "IDCourse" => $atrain->IDCourse,
                "CourseProgram" => $atrain->CourseProgram,
                "CourseFacilitator" => $atrain->CourseFacilitator,
                "City" => $atrain->City,
                "Duration" => $atrain->Duration,
                "YearFrom" => $atrain->YearFrom,
                "YearUntil" => $atrain->YearUntil
            );
            $this->pbl->insert_tnc($recordpbltrain);
        }

//        echo $this->session->userdata('idemp_on_clipboard');
//        echo "<hr>";
//        print_r($asal_h);
//        echo "<hr>";
//        print_r($asal_d);
//        echo "<hr>";
//        print_r($asal_pbl);

        $this->historydata($toid, 'duplicate', 'personalemp');
        $this->historydata($toid, 'duplicate', 'personalemp_detail');
        $this->historydata($toid, 'duplicate', 'personalpbl');

        $this->pbl->update_header_personal_emp($toid, $recordh);
        $this->pbl->update_detail_personal_emp($toid, $recordd);
        $this->pbl->update_pbl_personal($toid, $recorpbl);

        $resultenroll = $this->pbl->get_enroll($fromid);
        $checkenroll = ($resultenroll == '' or $resultenroll == null) ? 'empty' : 'exist';
        if ($checkenroll == 'exist') {
            foreach ($resultenroll as $rowenroll) {

                $recordenrollaktif = array(
                    "IDCard" => $rowenroll['IDCard'],
                    "IDEmployee" => $toid,
                    "CardType" => $rowenroll['CardType'],
                    "LastStatus" => $rowenroll['LastStatus'],
                    "CardNumber" => $rowenroll['CardNumber'],
                    "AddedBy" => $this->User,
                    "AddedDate" => $this->Datetime,
                    "AddedIP" => $this->Ip
                );

                $this->pbl->insert_enroll($recordenrollaktif);

                $recordenrollpasif = array(
                    "IDCard" => $rowenroll['IDCard'],
                    "IDEmployee" => $fromid,
                    "CardType" => $rowenroll['CardType'],
                    "LastStatus" => 'F',
                    "CardNumber" => $rowenroll['CardNumber'],
                    "EditedBy" => $this->User,
                    "EditedDate" => $this->Datetime,
                    "EditedIP" => $this->Ip
                );



                $this->pbl->update_enroll($fromid, $rowenroll['IDCard'], $recordenrollpasif);
            }
        }
    }

    function cancel_dup() {
        $dup_id = $this->session->userdata('duplicate_id');
        $record = array(
            //"DeleteBy"=>$this->User,
            //"DeleteIP"=>$this->Ip,
            //"DeleteDate"=>$this->Datetime,
            "DeleteFlag" => 'D'
        );

        $this->pbl->delete_personal_h_emp($dup_id, $record);
        $this->pbl->delete_personal_d_emp($dup_id, $record);
        $this->pbl->delete_personal_pbl($dup_id, $record);
        $this->pbl->delete_personal_train_pbl($dup_id, $record);
        $this->pbl->delete_personal_edu_pbl($dup_id, $record);
        $this->pbl->delete_personal_fam_pbl($dup_id, $record);
        $this->pbl->delete_personal_lang_pbl($dup_id, $record);
        $this->pbl->delete_personal_workexp_pbl($dup_id, $record);
        $this->pbl->delete_personal_job_pbl($dup_id, $record);
        $this->pbl->delete_enroll($dup_id, $record);
        $this->session->unset_userdata('duplicate_id');
        $this->session->unset_userdata('idemp_on_clipboard');
    }

    function save_personal($nip) {
        $iduser = $nip;
        $fname = $this->anti_xss($this->input->post("fname"));
        $nname = $this->anti_xss($this->input->post("nname"));
        $pbirth = $this->anti_xss($this->input->post("pbirth"));
        $dbirth = ($this->input->post("dbirth") == '' or $this->input->post("dbirth") == null) ? null : date('Y-m-d', strtotime($this->input->post("dbirth")));
        $bheight = $this->anti_xss($this->input->post("bheight"));
        $bweight = $this->anti_xss($this->input->post("bweight"));
        $gender = $this->anti_xss($this->input->post("gender"));
        $tblood = $this->anti_xss($this->input->post("tblood"));
        $czship = $this->anti_xss($this->input->post("czship"));
        $religion = $this->anti_xss($this->input->post("religion"));
        $noktp = $this->anti_xss($this->input->post("noktp"));
        $nonpwp = $this->anti_xss($this->input->post("nonpwp"));
        $nojamsos = $this->anti_xss($this->input->post("nojamsos"));
        $nokpj = $this->anti_xss($this->input->post("nokpj"));
        $abank = $this->anti_xss($this->input->post("abank"));
        $marital = $this->anti_xss($this->input->post("marital")); //radio
        $coupname = $this->anti_xss($this->input->post("coupname"));
        $couplektp = $this->anti_xss($this->input->post("couplektp"));
        if ($coupname == '-' OR $coupname == '' OR $coupname == NULL) {
            $couplektp = "N";
        }
        $nchild = $this->anti_xss($this->input->post("nchild"));
        $nohp = $this->anti_xss($this->input->post("nohp"));
        $inemail = $this->anti_xss($this->input->post("inemail"));
        $exmail = $this->anti_xss($this->input->post("exmail"));
        $laddress = $this->anti_xss($this->input->post("laddress"));
        $laddressph = $this->anti_xss($this->input->post("laddressph"));
        $ktpaddress = $this->anti_xss($this->input->post("ktpaddress"));
        $ktpaddressph = $this->anti_xss($this->input->post("ktpaddressph"));
        $famcert = $this->anti_xss($this->input->post("famcert"));
        $marrcert = $this->anti_xss($this->input->post("marrcert"));

        $nobpjsemp = $this->anti_xss($this->input->post("nobpjsemp"));
        $nobpjshlt = $this->anti_xss($this->input->post("nobpjshlt"));
        $famcertno = $this->anti_xss($this->input->post("famcertno"));

        $laddrprov = $this->anti_xss($this->input->post("laddrprov"));
        $laddrcity = $this->anti_xss($this->input->post("laddrcity"));
        $laddrsub = $this->anti_xss($this->input->post("laddrsub"));
        $laddrvlg = $this->anti_xss($this->input->post("laddrvlg"));
        $kaddrprov = $this->anti_xss($this->input->post("kaddrprov"));
        $kaddrcity = $this->anti_xss($this->input->post("kaddrcity"));
        $kaddrsub = $this->anti_xss($this->input->post("kaddrsub"));
        $kaddrvlg = $this->anti_xss($this->input->post("kaddrvlg"));
        $liverw = $this->anti_xss($this->input->post("liverw"));
        $livert = $this->anti_xss($this->input->post("livert"));
        $ktprw = $this->anti_xss($this->input->post("ktprw"));
        $ktprt = $this->anti_xss($this->input->post("ktprt"));
//        +"&ktpkodepos="+ktpkodepos+"&livekodepos="+livekodepos
        $ktpkodepos = $this->anti_xss($this->input->post("ktpkodepos"));
        $livekodepos = $this->anti_xss($this->input->post("livekodepos"));
        $noakdhk = $this->anti_xss($this->input->post("noakdhk"));

        $recordh = array(
            "FullName" => $fname,
            "EmailInternal" => $inemail,
            "EmailExternal" => $exmail,
            "Gender" => $gender
        );

        $recordd = array(
            "IDEmployee" => $iduser,
            "FullName" => $fname,
            "NickName" => $nname,
            "BirthPlace" => $pbirth,
            "BirthDate" => $dbirth,
            "Height" => $bheight,
            "Weight" => $bweight,
            "Gender" => $gender,
            "BloodType" => $tblood,
            "Citizenship" => $czship,
            "Religion" => $religion,
            "NoKTP" => $noktp,
            "NoNPWP" => $nonpwp,
            "NoJamsostek" => $nojamsos,
            "NoKPJ" => $nokpj,
            "MarriageCertificate" => $marrcert,
            "FamilyMemberCertificate" => $famcert,
            "BankAccount" => $abank,
            "MaritalStatus" => $marital,
            "CoupleName" => $coupname,
            "CoupleKTP" => $couplektp,
            "NumberChildren" => $nchild,
            "NoHP" => $nohp,
            "EmailInternal" => $inemail,
            "EmailExternal" => $exmail,
            "LiveAddress" => $laddress,
            "LiveAddressNoTelp" => "'" . $laddressph,
            "KTPAddress" => $ktpaddress,
            "KTPAddressNoTelp" => "'" . $ktpaddressph,
            "NoBPJSEmp" => $nobpjsemp,
            "NoBPJSHlt" => $nobpjshlt,
            "NoFamCert" => $famcertno,
            "LiveProvince" => $laddrprov,
            "LiveCity" => $laddrcity,
            "LiveSubdistrict" => $laddrsub,
            "LiveVillage" => $laddrvlg,
            "LiveRW" => $liverw,
            "LiveRT" => $livert,
            "KTPProvince" => $kaddrprov,
            "KTPCity" => $kaddrcity,
            "KTPSubdistrict" => $kaddrsub,
            "KTPVillage" => $kaddrvlg,
            "KTPRW" => $ktprw,
            "KTPRT" => $ktprt,
            "KTPPostalCode" => $ktpkodepos,
            "LivePostalCode" => $livekodepos,
            "NoAKDHK" => $noakdhk,
        );


        $recorpbl = array(
            "IDEmployee" => $iduser,
            "FullName" => $fname,
            "NickName" => $nname,
            "BirthPlace" => $pbirth,
            "BirthDate" => $dbirth,
            "Height" => $bheight,
            "Weight" => $bweight,
            "Gender" => $gender,
            "BloodType" => $tblood,
            "Citizenship" => $czship,
            "Religion" => $religion,
            "NoKTP" => $noktp,
            "NoNPWP" => $nonpwp,
            "NoJamsostek" => $nojamsos,
            "NoKPJ" => $nokpj,
            "MarriageCertificate" => $marrcert,
            "FamilyMemberCertificate" => $famcert,
            "BankAccount" => $abank,
            "MaritalStatus" => $marital,
            "CoupleName" => $coupname,
            "CoupleKTP" => $couplektp,
            "NumberChildren" => $nchild,
            "NoHP" => $nohp,
            "InternalEmail" => $inemail,
            "ExternalEmail" => $exmail,
            "LiveAddress" => $laddress,
            "LiveAddressNoTelp" => "'" . $laddressph,
            "KTPAddress" => $ktpaddress,
            "KTPAddressNoTelp" => "'" . $ktpaddressph,
            "NoBPJSEmp" => $nobpjsemp,
            "NoBPJSHlt" => $nobpjshlt,
            "NoFamCert" => $famcertno,
            "LiveProvince" => $laddrprov,
            "LiveCity" => $laddrcity,
            "LiveSubdistrict" => $laddrsub,
            "LiveVillage" => $laddrvlg,
            "LiveRW" => $liverw,
            "LiveRT" => $livert,
            "KTPProvince" => $kaddrprov,
            "KTPCity" => $kaddrcity,
            "KTPSubdistrict" => $kaddrsub,
            "KTPVillage" => $kaddrvlg,
            "KTPRW" => $ktprw,
            "KTPRT" => $ktprt,
            "KTPPostalCode" => $ktpkodepos,
            "LivePostalCode" => $livekodepos,
            "NoAKDHK" => $noakdhk,
        );

        if ($coupname == NULL OR $coupname == "" OR $coupname == "-") {
            $wh = array(
                "IDEmployee" => $iduser,
                "FamilyMember" => "spouse"
            );
            $this->pbl->delete_family($wh);
        } else {
            $wh = array(
                "IDEmployee" => $iduser,
                "FamilyMember" => "spouse"
            );
            $sps = $this->pbl->get_family_member($wh);
            $lastid = $this->pbl->get_lastidfamily($iduser)->row()->lastid;
            if ($sps->num_rows() == 0) {
                $rec = array(
                    "IDEmployee" => $iduser,
                    "IDFamily" => ($lastid * 1) + 1,
                    "FamilyMember" => "spouse",
                    "Name" => $coupname,
                    "NoKTP" => $couplektp
                );
                $this->pbl->insert_family($rec);
            }
            if ($sps->num_rows() == 1) {
                $wh = array(
                    "IDEmployee" => $iduser,
                    "FamilyMember" => "spouse",
                    "DeleteFlag" => "A"
                );
                $rec = array(
                    "Name" => $coupname,
                    "NoKTP" => $couplektp
                );
                $this->historydata($wh, 'save_personal', 'familyrow');
                $this->pbl->update_family($wh, $rec);
            }
        }

        $this->historydata($iduser, 'save_personal', 'personalemp');
        $this->historydata($iduser, 'save_personal', 'personalemp_detail');
        $this->historydata($iduser, 'save_personal', 'personalpbl');

        $this->pbl->update_header_personal_emp($iduser, $recordh);
        $this->pbl->update_detail_personal_emp($iduser, $recordd);
        $this->pbl->update_pbl_personal($iduser, $recorpbl);

        $this->upd_data($this->myid);
        $msg = array("status" => "oke");
        echo json_encode($msg);
    }

    function upd_data($iduser) {
        $editip = $this->input->ip_address();
        $rec = array(
            "EditedIP" => $editip,
            "EditedBy" => $this->session->userdata('sess_userid'),
            "EditedDate" => $this->Datetime
        );

        $this->pbl->update_header_personal_emp($iduser, $rec);
        $this->pbl->update_detail_personal_emp($iduser, $rec);
        $this->pbl->update_pbl_personal($iduser, $rec);
    }

    function upd_ftab($nip) {
        $iduser = $nip;
        $tab = $this->input->post('tab');
        $rec = array("F" . $tab => "1");
        $this->pbl->update_ftab($iduser, $rec);
        $msg = array("status" => "oke");
        echo json_encode($msg);
    }

//    family ====================================================================
    function get_family($nip) {
        $iduser = $nip;
        $family['lastid'] = $this->pbl->get_lastidfamily($iduser)->row();
        $family['data'] = $this->pbl->get_family($iduser)->result();
        echo json_encode($family);
    }

    function padd_family($nip) {
        $iduser = $nip;
        $member = $this->input->post("member");
        $nextid = $this->input->post("nextid");
        $name = $this->input->post("fname");
        $age = $this->input->post("fage");
        $address = $this->input->post("faddress");
        $education = $this->input->post("fedu");
        $occupation = $this->input->post("foccu");
        $noktp = $this->input->post("fnoktp");
        $fambplace = $this->input->post("fambplace");
        $fambdate = $this->input->post("fambdate");
        $fambdate = date('Y-m-d', strtotime($fambdate));

        if ($member == "child") {
            //ambil alamat jika yang dimasukkan adalah anak
            $user = $this->pbl->get_employee($iduser)->row();
            $addrktp = $user->KTPAddress;
            $addr_rt = $user->KTPRT;
            $addr_rw = $user->KTPRW;
            $addr_vl = $user->KTPVillage;
            $addr_sd = $user->KTPSubdistrict;
            $addr_ct = $user->KTPCity;
            $addr_pv = $user->KTPProvince;
            $addr_np = $this->KTPPostalCode;
            $childaddr = "
                $addrktp RT$addr_rt/RW$addr_rw, Kelurahan/Desa $addr_vl, 
                Kecamatan $addr_sd, Kota/Kabupaten $addr_ct, 
                $addr_pv $addr_np";
            $address = $childaddr;
        } else if ($member == "spouse") {
            $recp['CoupleName'] = $name;
            $recp['CoupleKTP'] = $noktp;
            $whp['IDEmployee'] = $iduser;
            //$this->pbl->update_prs_emp($iduser,$recp);
            $this->pbl->update_det_emp($iduser, $recp);
            $this->pbl->update_pbl_personal($iduser, $recp);

            $this->upd_data($this->myid);
        }

        $rec = array(
            "IDEmployee" => $iduser,
            "IDFamily" => $nextid,
            "FamilyMember" => $member,
            "NoKTP" => $noktp,
            "Name" => $name,
            "Age" => $age,
            "Address" => $address,
            "Education" => $education,
            "Occupation" => $occupation,
            "BirthPlace" => $fambplace,
            "BirthDate" => $fambdate
        );
        $this->pbl->insert_family($rec);
        $msg = array("status" => "oke", "msg" => "New Family Member Added!");

        echo json_encode($msg);
    }

    function edit_family($nip) {
        $iduser = $nip;
        $famid = $this->input->post("famid");
        $rec = $this->pbl->get_family($iduser, $famid)->row();

        echo json_encode($rec);
    }

    function del_family($nip) {
        $iduser = $nip;
        $idfamily = $this->anti_xss($this->input->post("famid"));
        $where = array("IDEmployee" => $iduser, "IDFamily" => $idfamily);
        $fam = $this->pbl->get_family($iduser, $idfamily)->row();

        if ($fam->FamilyMember == "spouse") {
            $recp['CoupleName'] = "-";
            $recp['CoupleKTP'] = "N";
            $whp['IDEmployee'] = $iduser;
            $this->pbl->update_det_emp($iduser, $recp);
            $this->pbl->update_pbl_personal($iduser, $recp);
            $this->upd_data($this->myid);
        }

        $this->pbl->delete_family($where);
        $msg = array("status" => "oke", "msg" => "Data Deleted!");

        echo json_encode($msg);
    }

    function pedit_family($nip) {
        $iduser = $nip;
        $member = $this->anti_xss($this->input->post("member"));
        $idfamily = $this->anti_xss($this->input->post("famid"));
        $name = $this->anti_xss($this->input->post("fname"));
        $age = $this->anti_xss($this->input->post("fage"));
        $address = $this->anti_xss($this->input->post("faddress"));
        $education = $this->anti_xss($this->input->post("fedu"));
        $occupation = $this->anti_xss($this->input->post("foccu"));
        $noktp = $this->anti_xss($this->input->post("fnoktp"));
        $fambplace = $this->anti_xss($this->input->post("fambplace"));
        $fambdate = $this->anti_xss($this->input->post("fambdate"));
        $fambdate = date('Y-m-d', strtotime($fambdate));

        $rec = array(
            "FamilyMember" => $member,
            "NoKTP" => $noktp,
            "Name" => $name,
            "Age" => $age,
            "Address" => $address,
            "Education" => $education,
            "Occupation" => $occupation,
            "BirthPlace" => $fambplace,
            "BirthDate" => $fambdate
        );
        $where = array(
            "IDEmployee" => $iduser,
            "IDFamily" => $idfamily,
            "DeleteFlag" => 'A',
        );

        $this->historydata($where, 'pedit_family', 'familyrow');
        //cek family yg diedit
        $cek = $this->pbl->get_family_member($where)->row();
        if ($cek->FamilyMember == "spouse" and $member != "spouse") {
            $recp['CoupleName'] = "-";
            $recp['CoupleKTP'] = "N";
            $whp['IDEmployee'] = $iduser;
            $this->pbl->update_det_emp($iduser, $recp);
            $this->pbl->update_pbl_personal($iduser, $recp);
            $this->upd_data($this->myid);
        }
        if ($member == "spouse") {
            $recp['CoupleName'] = $name;
            $recp['CoupleKTP'] = $noktp;
            $whp['IDEmployee'] = $iduser;
            $this->pbl->update_det_emp($iduser, $recp);
            $this->pbl->update_pbl_personal($iduser, $recp);
            $this->upd_data($this->myid);
        }
        $this->pbl->update_family($where, $rec);


        $msg = array("status" => "oke", "msg" => "Data Updated!");
        echo json_encode($msg);
    }

//    end of family =======================================================
//    education ====================================================================
    function get_education($nip) {
        $iduser = $nip;
        $family['lastid'] = $this->pbl->get_lastideducation($iduser)->row();
        $family['data'] = $this->pbl->get_education($iduser)->result();

        echo json_encode($family);
    }

    function padd_education($nip) {
        $iduser = $nip;
        $level = $this->anti_xss($this->input->post("level"));
        $ename = $this->anti_xss($this->input->post("ename"));
        $course = $this->anti_xss($this->input->post("course"));
        $ecity = $this->anti_xss($this->input->post("ecity"));
        $efrom = $this->anti_xss($this->input->post("efrom"));
        $etill = $this->anti_xss($this->input->post("etill"));
        $ecert = $this->anti_xss($this->input->post("ecert"));
        $nextid = $this->anti_xss($this->input->post("nextid"));
        $rec = array(
            "IDEmployee" => $iduser,
            "IDEducation" => $nextid,
            "EducationLevel" => $level,
            "Course" => $course,
            "SchoolName" => $ename,
            "City" => $ecity,
            "YearFrom" => $efrom,
            "YearUntil" => $etill,
            "Certificate" => $ecert
        );
        $this->pbl->insert_education($rec);

        $msg = array("status" => "oke", "msg" => "New Education Background Added!");
        echo json_encode($msg);
    }

    function edit_education($nip, $eduid = NULL) {
        $iduser = $nip;
        $eduid = $this->anti_xss($this->input->post("eduid"));
        $rec = $this->pbl->get_education($iduser, $eduid)->row();
        echo json_encode($rec);
    }

    function del_education($nip) {
        $iduser = $nip;
        $eduid = $this->anti_xss($this->input->post("eduid"));
        $where = array("IDEmployee" => $iduser, "IDEducation" => $eduid);
        $this->pbl->delete_education($where);
        $msg = array("status" => "oke", "msg" => "Data Deleted!");

        echo json_encode($msg);
    }

    function pedit_education($nip) {
        $iduser = $nip;
        $level = $this->anti_xss($this->input->post("level"));
        $ename = $this->anti_xss($this->input->post("ename"));
        $course = $this->anti_xss($this->input->post("course"));
        $ecity = $this->anti_xss($this->input->post("ecity"));
        $efrom = $this->anti_xss($this->input->post("efrom"));
        $etill = $this->anti_xss($this->input->post("etill"));
        $ecert = $this->anti_xss($this->input->post("ecert"));
        $eduid = $this->anti_xss($this->input->post("eduid"));

        $rec = array(
            "EducationLevel" => $level,
            "Course" => $course,
            "SchoolName" => $ename,
            "City" => $ecity,
            "YearFrom" => $efrom,
            "YearUntil" => $etill,
            "Certificate" => $ecert
        );
        $where = array(
            "IDEmployee" => $iduser,
            "IDEducation" => $eduid,
            "DeleteFlag" => 'A'
        );

        $this->historydata($where, 'pedit_education', 'educationrow');
        $this->pbl->update_education($where, $rec);
        $msg = array("status" => "oke", "msg" => "Data Updated!");

        echo json_encode($msg);
    }

//    end of education =======================================================
//    training and course ====================================================================
    function get_tnc($nip) {
        $iduser = $nip;
        $family['lastid'] = $this->pbl->get_lastidtnc($iduser)->row();
        $family['data'] = $this->pbl->get_tnc($iduser)->result();
        echo json_encode($family);
    }

    function padd_tnc($nip) {
        $iduser = $nip;
        $program = $this->anti_xss($this->input->post("program"));
        $fac = $this->anti_xss($this->input->post("facilitator"));
        $city = $this->anti_xss($this->input->post("city"));
        $duration = $this->anti_xss($this->input->post("duration"));
        $from = $this->anti_xss($this->input->post("from"));
        $until = $this->anti_xss($this->input->post("until"));
        $nextid = $this->anti_xss($this->input->post("nextid"));
        $rec = array(
            "IDEmployee" => $iduser,
            "IDCourse" => $nextid,
            "CourseProgram" => $program,
            "CourseFacilitator" => $fac,
            "City" => $city,
            "Duration" => $duration,
            "YearFrom" => $from,
            "YearUntil" => $until
        );
        $this->pbl->insert_tnc($rec);
        $msg = array("status" => "oke", "msg" => "New Training and Course Added!");

        echo json_encode($msg);
    }

    function edit_tnc($nip) {
        $iduser = $nip;
        $tncid = $this->anti_xss($this->input->post("tncid"));
        $rec = $this->pbl->get_tnc($iduser, $tncid)->row();
        echo json_encode($rec);
    }

    function del_tnc($nip) {
        $iduser = $nip;
        $tncid = $this->anti_xss($this->input->post("tncid"));
        $where = array("IDEmployee" => $iduser, "IDCourse" => $tncid);
        $this->pbl->delete_tnc($where);
        $msg = array("status" => "oke", "msg" => "Data Deleted!");

        echo json_encode($msg);
    }

    function pedit_tnc($nip) {
        $iduser = $nip;
        $program = $this->anti_xss($this->input->post("program"));
        $fac = $this->anti_xss($this->input->post("facilitator"));
        $city = $this->anti_xss($this->input->post("city"));
        $duration = $this->anti_xss($this->input->post("duration"));
        $from = $this->anti_xss($this->input->post("from"));
        $until = $this->anti_xss($this->input->post("until"));
        $idtnc = $this->anti_xss($this->input->post("tncid"));

        $rec = array(
            "CourseProgram" => $program,
            "CourseFacilitator" => $fac,
            "City" => $city,
            "Duration" => $duration,
            "YearFrom" => $from,
            "YearUntil" => $until
        );
        $where = array(
            "IDEmployee" => $iduser,
            "IDCourse" => $idtnc,
            "DeleteFlag" => 'A'
        );


        $this->historydata($where, 'pedit_tnc', 'courserow');
        $this->pbl->update_tnc($where, $rec);
        $msg = array("status" => "oke", "msg" => "Data Updated!");

        echo json_encode($msg);
    }

//    end of training and course =======================================================
//    languages ====================================================================
    function get_language($nip) {
        $iduser = $nip;
        $family['lastid'] = $this->pbl->get_lastidlanguage($iduser)->row();
        $family['data'] = $this->pbl->get_language($iduser)->result();
        echo json_encode($family);
    }

    function padd_language($nip) {
        $iduser = $nip;
        $language = $this->anti_xss($this->input->post("language"));
        $listen = $this->anti_xss($this->input->post("listen"));
        $read = $this->anti_xss($this->input->post("read"));
        $convers = $this->anti_xss($this->input->post("convers"));
        $write = $this->anti_xss($this->input->post("write"));

        $nextid = $this->anti_xss($this->input->post("nextid"));
        $rec = array(
            "IDEmployee" => $iduser,
            "IDLanguage" => $nextid,
            "Language" => $language,
            "Listening" => $listen,
            "Reading" => $read,
            "Conversation" => $convers,
            "Writing" => $write
        );
        $this->pbl->insert_language($rec);
        $msg = array("status" => "oke", "msg" => "New Language Added!");

        echo json_encode($msg);
    }

    function edit_language($nip) {
        $iduser = $nip;
        $langid = $this->anti_xss($this->input->post("langid"));
        $rec = $this->pbl->get_language($iduser, $langid)->row();
        echo json_encode($rec);
    }

    function del_language($nip) {
        $iduser = $nip;
        $langid = $this->anti_xss($this->input->post("langid"));
        $where = array("IDEmployee" => $iduser, "IDLanguage" => $langid);
        $this->pbl->delete_language($where);
        $msg = array("status" => "oke", "msg" => "Data Deleted!");

        echo json_encode($msg);
    }

    function pedit_language($nip) {
        $iduser = $nip;
        $language = $this->anti_xss($this->input->post("language"));
        $listen = $this->anti_xss($this->input->post("listen"));
        $read = $this->anti_xss($this->input->post("read"));
        $convers = $this->anti_xss($this->input->post("convers"));
        $write = $this->anti_xss($this->input->post("write"));

        $langid = $this->anti_xss($this->input->post("langid"));
        $rec = array(
            "Language" => $language,
            "Listening" => $listen,
            "Reading" => $read,
            "Conversation" => $convers,
            "Writing" => $write
        );
        $where = array(
            "IDEmployee" => $iduser,
            "IDLanguage" => $langid,
            "DeleteFlag" => 'A',
        );

        $this->historydata($where, 'pedit_language', 'languagerow');
        $this->pbl->update_language($where, $rec);
        $msg = array("status" => "oke", "msg" => "Data Updated!");
        echo json_encode($msg);
    }

//    end of languages =======================================================
//    working experience ====================================================================
    function get_work($nip) {
        $iduser = $nip;
        $family['lastid'] = $this->pbl->get_lastidwork($iduser)->row();
        $family['data'] = $this->pbl->get_work($iduser)->result();
        echo json_encode($family);
    }

    function padd_work($nip) {
        $iduser = $nip;
        $comp = $this->anti_xss($this->input->post("comp"));
        $address = $this->anti_xss($this->input->post("address"));
        $phone = $this->anti_xss($this->input->post("phone"));
        $pos = $this->anti_xss($this->input->post("pos"));
        $dur = $this->anti_xss($this->input->post("dur"));

        $nextid = $this->anti_xss($this->input->post("nextid"));
        $rec = array(
            "IDEmployee" => $iduser,
            "IDWorkExp" => $nextid,
            "CompanyName" => $comp,
            "CompanyAddress" => $address,
            "CompanyPhone" => $phone,
            "Position" => $pos,
            "WorkDuration" => $dur
        );
        $this->pbl->insert_work($rec);
        $msg = array("status" => "oke", "msg" => "New Working Experience Added!");
        echo json_encode($msg);
    }

    function edit_work($nip) {
        $iduser = $nip;
        $workid = $this->anti_xss($this->input->post("workid"));
        $rec = $this->pbl->get_work($iduser, $workid)->row();
        echo json_encode($rec);
    }

    function del_work($nip) {
        $iduser = $nip;
        $workid = $this->anti_xss($this->input->post("workid"));
        $where = array("IDEmployee" => $iduser, "IDWorkExp" => $workid);
        $this->pbl->delete_work($where);
        $msg = array("status" => "oke", "msg" => "Data Deleted!");
        echo json_encode($msg);
    }

    function pedit_work($nip) {
        $iduser = $nip;

        $comp = $this->anti_xss($this->input->post("comp"));
        $address = $this->anti_xss($this->input->post("address"));
        $phone = $this->anti_xss($this->input->post("phone"));
        $pos = $this->anti_xss($this->input->post("pos"));
        $dur = $this->anti_xss($this->input->post("dur"));

        $workid = $this->anti_xss($this->input->post("workid"));
        $rec = array(
            "CompanyName" => $comp,
            "CompanyAddress" => $address,
            "CompanyPhone" => $phone,
            "Position" => $pos,
            "WorkDuration" => $dur
        );
        $where = array(
            "IDEmployee" => $iduser,
            "IDWorkExp" => $workid,
            "DeleteFlag" => 'A'
        );
        $this->historydata($where, 'pedit_work', 'workexprow');
        $this->pbl->update_work($where, $rec);
        $msg = array("status" => "oke", "msg" => "Data Updated!");
        echo json_encode($msg);
    }

//    end of working experience =======================================================
//=============PASSIVE OR ACTIVE=======================
    function get_passive_or_active($nip) {
        $result = $this->pbl->get_detail_turnonoff($nip);
        echo json_encode($result);
    }

    function save_passive_or_active($nip) {
        $iduser = $nip;
        $status = $this->anti_xss($this->input->post("statusemployee"));
        $sendmail = $this->anti_xss($this->input->post("sendmail"));
        $hiredate = ($this->input->post("tglmasuk") == '' or $this->input->post("tglmasuk") == null) ? null : date('Y-m-d', strtotime($this->input->post("tglmasuk")));
        $resigndate = ($this->input->post("tglkeluar") == '' or $this->input->post("tglkeluar") == null) ? null : date('Y-m-d', strtotime($this->input->post("tglkeluar")));
        $reasonresgin = $this->anti_xss($this->input->post("reasonresgin"));
        $record_emp_h = array(
            "Status" => $status,
            "HireDate" => $hiredate,
            "ResignDate" => $resigndate
        );

        $cekh = $this->pbl->get_parent_turnonoff($iduser)->result();
        if ($cekh !== NULL) {
            $this->historydata($iduser, 'save_passive_or_active', 'personalemp');
            $this->pbl->update_parent_turnonoff($iduser, $record_emp_h);
        }
        $record_emp_d = array(
            "Status" => $status,
            "HireDate" => $hiredate,
            "ResignDate" => $resigndate,
            "ReasonResign" => $reasonresgin
        );

        $row = $this->pbl->get_detail_turnonoff($iduser);
        $checkdata = ($row == '' or $row == null) ? 'empty' : 'exist';
        if ($checkdata !== 'empty') {
            $this->historydata($iduser, 'save_passive_or_active', 'personalemp_detail');
            $this->pbl->update_detail_turnonoff($iduser, $record_emp_d);
        }

        $record_pbl_job = array(
            "Status" => $status,
            "HireDate" => $hiredate,
            "ResignDate" => $resigndate,
            "ResignReason" => $reasonresgin
        );

        $cekjob = $this->pbl->get_job($iduser)->result();
        if ($cekjob !== NULL) {
            $this->historydata($iduser, 'save_passive_or_active', 'job');
            $this->pbl->update_job($iduser, $record_pbl_job);
        }

        if ($checkdata == 'exist') {
            if ($status == 'A') {
                $this->email_hire($iduser, $sendmail);
            } else if ($status == 'P') {
                $this->email_resign($iduser, $sendmail);
            }
        }

        $msg = array("status" => 'oke');
        echo json_encode($msg);
    }

    function email_hire($nip, $sendmail) {
        $row = $this->pbl->get_detail_turnonoff($nip);
        $checkdata = ($row == '' or $row == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {

            $flaghire = $row->FlagHire;
            $name = $row->FullName;
            $jb = $row->IDJobGroup;
            $gen = $row->Gender;
            $hiredate = date('d-m-Y', strtotime($row->HireDate));

            $pst = $this->pbl->get_position($nip);
            if ($pst == 'LAIN LAIN' or $pst == 'LAIN-LAIN') {
                $position = '';
            } else {
                $position = $pst;
            }

            $gender = $this->libfun->get_gender($gen);
            $jobgroup = $this->libfun->get_name_group($jb);

            $dp = $row->IDDepartement;
            $rowdepartement = $this->pbl->get_departement($dp)->row();
            $row_param = $this->parameter->get_hrd();
            $departemen = $rowdepartement->DescStructure;

            $data['nip'] = $nip;
            $data['nama'] = $name;
            $data['gender'] = $gender;
            $data['status'] = $jobgroup;
            $data['position'] = $position;
            $data['departemen'] = $departemen;
            $data['hire'] = $hiredate;
            $data['namehrd'] = $row_param->FullName;
            $data['emailhrd'] = $row_param->ExternalEmail;
            $data['phonehrd'] = $row_param->NoHP;

            $sendto = 'AllUser@tis.loc';
            $cc = 'doris@tis.loc';
            $subject = "New Employee Announcement";
            $message = $this->load->view('mst01/contenthire', $data, true);
            $recordflag = array("FlagHire" => '1');

            if ($flaghire == '0' and $jb == 'ST' or $jb == 'LT' or $jb == 'LK' or $jb == 'OS' or $jb == 'MAG') {

                if ($sendmail == 'Y') {
                    $this->sendmail->internalmail($sendto, $subject, $message, $cc);
                }

                $this->historydata($nip, 'email_hire', 'personalemp');
                $this->historydata($nip, 'email_hire', 'personalemp_detail');
                $this->historydata($nip, 'email_hire', 'job');
                $this->pbl->update_prs_emp($nip, $recordflag);
                $this->pbl->update_det_emp($nip, $recordflag);
                $this->pbl->update_job($nip, $recordflag);
            }
        }
    }

    function email_resign($nip, $sendmail) {
        $row = $this->pbl->get_detail_turnonoff($nip);
        $checkdata = ($row == '' or $row == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            $flagresign = $row->FlagResign;
            $name = $row->FullName;
            $jb = $row->IDJobGroup;
            $gen = $row->Gender;
            $resigndate = date('d-m-Y', strtotime($row->ResignDate));

            $pst = $this->pbl->get_position($nip);
            if ($pst == 'LAIN LAIN' or $pst == 'LAIN-LAIN') {
                $position = '';
            } else {
                $position = $pst;
            }

            $gender = $this->libfun->get_gender($gen);
            $jobgroup = $this->libfun->get_name_group($jb);

            $dp = $row->IDDepartement;
            $rowdepartement = $this->pbl->get_departement($dp)->row();
            $row_param = $this->parameter->get_hrd();
            $departemen = $rowdepartement->DescStructure;

            $data['nip'] = $nip;
            $data['nama'] = $name;
            $data['gender'] = $gender;
            $data['status'] = $jobgroup;
            $data['position'] = $position;
            $data['departemen'] = $departemen;
            $data['resign'] = $resigndate;
            $data['namehrd'] = $row_param->FullName;
            $data['emailhrd'] = $row_param->ExternalEmail;
            $data['phonehrd'] = $row_param->NoHP;

            $sendto = 'AllUser@tis.loc';
            $cc = 'doris@tis.loc';
            $subject = "Information of Employee Resignation";
            $message = $this->load->view('mst01/contentresign', $data, true);
            $recordflag = array("FlagResign" => '1');


            if ($flagresign == '0' and $jb == 'ST' or $jb == 'LT' or $jb == 'LK' or $jb == 'OS' or $jb == 'MAG') {

                if ($sendmail == 'Y') {
                    $this->sendmail->internalmail($sendto, $subject, $message, $cc);
                }

                $this->historydata($nip, 'email_resign', 'personalemp');
                $this->historydata($nip, 'email_resign', 'personalemp_detail');
                $this->historydata($nip, 'email_resign', 'job');

                $this->pbl->update_prs_emp($nip, $recordflag);
                $this->pbl->update_det_emp($nip, $recordflag);
                $this->pbl->update_job($nip, $recordflag);
            }
        }
    }

//===========END PASSIVE OR ACTIVE======================    

    function exportdata($param, $group, $loc, $ext = '.xlsx', $path_file = '/tmp/') {
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
        $objSheet->setTitle('master employee report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:AV1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('No');
        $objSheet->getCell('B1')->setValue('IDEmployee');
        $objSheet->getCell('C1')->setValue('Full Name');
        $objSheet->getCell('D1')->setValue('Nick Name');
        $objSheet->getCell('E1')->setValue('Hire Date');
        $objSheet->getCell('F1')->setValue('Bank Account');
        //$objSheet->getCell('G1')->setValue('Leader');
        $objSheet->getCell('G1')->setValue('Departement');
        $objSheet->getCell('H1')->setValue('Location');
        $objSheet->getCell('I1')->setValue('Job Group');
        $objSheet->getCell('J1')->setValue('Job Position');
     //   $objSheet->getCell('K1')->setValue('Unit Group');
        $objSheet->getCell('K1')->setValue('EmailInternal');
     //   $objSheet->getCell('M1')->setValue('Extension');
        $objSheet->getCell('L1')->setValue('First Join Date');
        $objSheet->getCell('M1')->setValue('Start Probation Date');
        $objSheet->getCell('N1')->setValue('Pass Probation Date');
        $objSheet->getCell('O1')->setValue('New Contact Date');
        $objSheet->getCell('P1')->setValue('End Contact Date');
        $objSheet->getCell('Q1')->setValue('In Field Date');
        $objSheet->getCell('R1')->setValue('Birth Place');
        $objSheet->getCell('S1')->setValue('Birth Date');
        $objSheet->getCell('T1')->setValue('Gender');
        $objSheet->getCell('U1')->setValue('Religion');
        $objSheet->getCell('V1')->setValue('Education Level');
        $objSheet->getCell('W1')->setValue('Education School');
        $objSheet->getCell('X1')->setValue('Education Majors');
        $objSheet->getCell('YA1')->setValue('Marital Status');
        $objSheet->getCell('Z1')->setValue('Marriage Certificate');
        $objSheet->getCell('AA1')->setValue('Family Member Certificate');
        $objSheet->getCell('AB1')->setValue('ID Family Certificate');
        $objSheet->getCell('AC1')->setValue('NPWP');
        $objSheet->getCell('AD1')->setValue('KPJ');
        $objSheet->getCell('AE1')->setValue('Citized Card(KTP)');
        $objSheet->getCell('AF1')->setValue('ID Married Couple');
        $objSheet->getCell('AG1')->setValue('Certificate AKDHK No.');
        $objSheet->getCell('AH1')->setValue('Number of Children');
        $objSheet->getCell('AI1')->setValue('First Child');
        $objSheet->getCell('AJ1')->setValue('Second Child');
        $objSheet->getCell('AK1')->setValue('Couple Name');
        $objSheet->getCell('AL1')->setValue('Blood Type');
        $objSheet->getCell('AM1')->setValue('Telphone');
        $objSheet->getCell('AN1')->setValue('Handphone');
        $objSheet->getCell('AO1')->setValue('Address (Current)');
        $objSheet->getCell('AP1')->setValue('Address (KTP)');
        $objSheet->getCell('AQ1')->setValue('Work Experience');
        $objSheet->getCell('AR1')->setValue('Resign Date');
        $objSheet->getCell('AS1')->setValue('Reason Explain Resignation');
        $objSheet->getCell('AT1')->setValue('Employee Status');
        $objSheet->getCell('AU1')->setValue('Status');
        $objSheet->getCell('AV1')->setValue('Note');

        $pembatas = array('type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array('rgb' => 'FCFC0C')
        );

        $result = $this->pbl->getall_employee($param, $group, $loc);
        if ($result != NULL) {
            $array = $result->result_array();

            $i = 1;
            $point = 0;
            $laststatus = "";
            foreach ($array as $row) {
                $point++;
                if ($laststatus != $row['Status'] && $point > 1) {
                    $i++;
                    $objSheet->getStyle('A' . $i . ':AY' . $i)->getFill()->applyFromArray($pembatas);
                    $objSheet->getCell('A' . $i)->setValue('');
                    $objSheet->getCell('B' . $i)->setValue('');
                    $objSheet->getCell('C' . $i)->setValue('');
                    $objSheet->getCell('D' . $i)->setValue('');
                    $objSheet->getCell('E' . $i)->setValue('');
                    $objSheet->getCell('F' . $i)->setValue('');
                    $objSheet->getCell('G' . $i)->setValue('');
                    $objSheet->getCell('H' . $i)->setValue('');
                    $objSheet->getCell('I' . $i)->setValue('');
                    $objSheet->getCell('J' . $i)->setValue('');
                    $objSheet->getCell('K' . $i)->setValue('');
                    $objSheet->getCell('L' . $i)->setValue('');
                    $objSheet->getCell('M' . $i)->setValue('');
                    $objSheet->getCell('N' . $i)->setValue('');
                    $objSheet->getCell('O' . $i)->setValue('');
                    $objSheet->getCell('P' . $i)->setValue('');
                    $objSheet->getCell('Q' . $i)->setValue('');
                    $objSheet->getCell('R' . $i)->setValue('');
                    $objSheet->getCell('S' . $i)->setValue('');
                    $objSheet->getCell('T' . $i)->setValue('');
                    $objSheet->getCell('U' . $i)->setValue('');
                    $objSheet->getCell('V' . $i)->setValue('');
                    $objSheet->getCell('W' . $i)->setValue('');
                    $objSheet->getCell('X' . $i)->setValue('');
                    $objSheet->getCell('Y' . $i)->setValue('');
                    $objSheet->getCell('Z' . $i)->setValue('');
                    $objSheet->getCell('AA' . $i)->setValue('');
                    $objSheet->getCell('AB' . $i)->setValue('');
                    $objSheet->getCell('AC' . $i)->setValue('');
                    $objSheet->getCell('AD' . $i)->setValue('');
                    $objSheet->getCell('AE' . $i)->setValue('');
                    $objSheet->getCell('AF' . $i)->setValue('');
                    $objSheet->getCell('AG' . $i)->setValue('');
                    $objSheet->getCell('AH' . $i)->setValue('');
                    $objSheet->getCell('AI' . $i)->setValue('');
                    $objSheet->getCell('AJ' . $i)->setValue('');
                    $objSheet->getCell('AK' . $i)->setValue('');
                    $objSheet->getCell('AL' . $i)->setValue('');
                    $objSheet->getCell('AM' . $i)->setValue('');
                    $objSheet->getCell('AN' . $i)->setValue('');
                    $objSheet->getCell('AO' . $i)->setValue('');
                    $objSheet->getCell('AP' . $i)->setValue('');
                    $objSheet->getCell('AQ' . $i)->setValue('');
                    $objSheet->getCell('AR' . $i)->setValue('');
                    $objSheet->getCell('AS' . $i)->setValue('');
                    $objSheet->getCell('AT' . $i)->setValue('');
                    $objSheet->getCell('AU' . $i)->setValue('');
                    $objSheet->getCell('AV' . $i)->setValue('');
                 //   $objSheet->getCell('AW' . $i)->setValue('');
                  //  $objSheet->getCell('AY' . $i)->setValue('');
                }

                $i++;

                $laststatus = $row['Status'];
                $NMJobGroup = $this->libfun->get_name_group($row['IDJobGroup']);

                if ($row['BirthDate'] == '' or $row['BirthDate'] == '0000-00-00' or $row['BirthDate'] == '1970-01-01') {
                    $bdate = '';
                } else {
                    $bdate = $row['BirthDate'];
                }

                if ($row['DateFirstJoint'] == '' or $row['DateFirstJoint'] == '0000-00-00' or $row['DateFirstJoint'] == '1970-01-01') {
                    $fdate = '';
                } else {
                    $fdate = $row['DateFirstJoint'];
                }
                if ($row['DateStartProbation'] == '' or $row['DateStartProbation'] == '0000-00-00' or $row['DateStartProbation'] == '1970-01-01') {
                    $startprobdate = '';
                } else {
                    $startprobdate = $row['DateStartProbation'];
                }

                if ($row['DatePassProbation'] == '' or $row['DatePassProbation'] == '0000-00-00' or $row['DatePassProbation'] == '1970-01-01') {
                    $passdate = '';
                } else {
                    $passdate = $row['DatePassProbation'];
                }

                if ($row['DateNewContract'] == '' or $row['DateNewContract'] == '0000-00-00' or $row['DateNewContract'] == '1970-01-01') {
                    $newdate = '';
                } else {
                    $newdate = $row['DateNewContract'];
                }

                if ($row['DateEndContract'] == '' or $row['DateEndContract'] == '0000-00-00' or $row['DateEndContract'] == '1970-01-01') {
                    $enddate = '';
                } else {
                    $enddate = $row['DateEndContract'];
                }

                if ($row['DateInField'] == '' or $row['DateInField'] == '0000-00-00' or $row['DateInField'] == '1970-01-01') {
                    $fielddate = '';
                } else {
                    $fielddate = $row['DateInField'];
                }


                $rowdept = $this->pbl->get_departement($row['IDDepartement'])->row();
                $dept = ($rowdept == '' or $rowdept == null) ? $row['IDDepartement'] : $rowdept->DescStructure;

                $location = ($row['IDLocation'] == '1') ? "KAPUK" : "BITUNG";
                $jk = $this->libfun->get_gender($row['Gender']);

                $redu = $this->pbl->get_lastedu($row['IDEmployee']);

                if ($row['IDEducation']) {
                    $edu = $row['IDEducation'];
                    $major = $row['IDMajors'];
                    $school = $redu->SchoolName;
                } else {
                    $edu = $redu->EducationLevel;
                    $major = $redu->Course;
                    $school = $redu->SchoolName;
                }


                $objSheet->getCell('A' . $i)->setValue($point);
                $objSheet->getCell('B' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($row['FullName']);
                $objSheet->getCell('D' . $i)->setValue($row['NickName']);
                $objSheet->getCell('E' . $i)->setValue($row['HireDate']);
                $objSheet->getCell('F' . $i)->setValue($row['BankAccount']);
                // $objSheet->getCell('G' . $i)->setValue("'".$row['IDEmployeeParent']);
                $objSheet->getCell('G' . $i)->setValue($dept);
                $objSheet->getCell('H' . $i)->setValue($location);
                $objSheet->getCell('I' . $i)->setValue($NMJobGroup);
                $objSheet->getCell('J' . $i)->setValue($row['IDJobPosition']);
              //  $objSheet->getCell('K' . $i)->setValue($row['IDUnitGroup']);
                $objSheet->getCell('K' . $i)->setValue($row['EmailInternal']);
              //  $objSheet->getCell('M' . $i)->setValue("'" . $row['Extension']);
                $objSheet->getCell('L' . $i)->setValue($fdate);
                $objSheet->getCell('M' . $i)->setValue($startprobdate);
                $objSheet->getCell('N' . $i)->setValue($passdate);
                $objSheet->getCell('O' . $i)->setValue($newdate);
                $objSheet->getCell('P' . $i)->setValue($enddate);
                $objSheet->getCell('Q' . $i)->setValue($fielddate);
                $objSheet->getCell('R' . $i)->setValue($row['BirthPlace']);
                $objSheet->getCell('S' . $i)->setValue($bdate);
                $objSheet->getCell('T' . $i)->setValue($jk);
                $objSheet->getCell('U' . $i)->setValue($row['Religion']);
                $objSheet->getCell('V' . $i)->setValue($edu);
                $objSheet->getCell('W' . $i)->setValue($school);
                $objSheet->getCell('X' . $i)->setValue($major);
                $objSheet->getCell('Y' . $i)->setValue($row['MaritalStatus']);
                $objSheet->getCell('Z' . $i)->setValue($row['MarriageCertificate']);
                $objSheet->getCell('AA' . $i)->setValue($row['FamilyMemberCertificate']);
                $objSheet->getCell('AB' . $i)->setValue("'" . $row['NoFamCert']);
                $objSheet->getCell('AC' . $i)->setValue("'" . $row['NoNPWP']);
                $objSheet->getCell('AD' . $i)->setValue("'" . $row['NoKPJ']);
                $objSheet->getCell('AE' . $i)->setValue("'" . $row['NoKTP']);
                $objSheet->getCell('AF' . $i)->setValue("'" . $row['CoupleKTP']);
                $objSheet->getCell('AG' . $i)->setValue("'" . $row['NoAKDHK']);
                $objSheet->getCell('AH' . $i)->setValue($row['NumberChildren']);
                $objSheet->getCell('AI' . $i)->setValue($row['FirstChild']);
                $objSheet->getCell('AJ' . $i)->setValue($row['SecondChild']);
                $objSheet->getCell('AK' . $i)->setValue($row['CoupleName']);
                $objSheet->getCell('AL' . $i)->setValue($row['BloodType']);
                $objSheet->getCell('AM' . $i)->setValue("'" . $row['NoTelp']);
                $objSheet->getCell('AN' . $i)->setValue("'" . $row['NoHp']);
                $objSheet->getCell('AO' . $i)->setValue($row['LiveAddress']);
                $objSheet->getCell('AP' . $i)->setValue($row['KTPAddress']);
                $objSheet->getCell('AQ' . $i)->setValue($row['WorkExperience']);
                $objSheet->getCell('AR' . $i)->setValue($row['ResignDate']);
                $objSheet->getCell('AS' . $i)->setValue($row['ReasonResign']);
                $objSheet->getCell('AT' . $i)->setValue($row['EmployeeStatus']);
                $objSheet->getCell('AU' . $i)->setValue($row['Status']);
                $objSheet->getCell('AV' . $i)->setValue($row['Note']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:AV' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:AV' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:AV1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);
            $objSheet->getColumnDimension('F')->setAutoSize(true);
            //$objSheet->getColumnDimension('G')->setAutoSize(true);
            $objSheet->getColumnDimension('G')->setAutoSize(true);
            $objSheet->getColumnDimension('H')->setAutoSize(true);
            $objSheet->getColumnDimension('I')->setAutoSize(true);
            $objSheet->getColumnDimension('J')->setAutoSize(true);
            $objSheet->getColumnDimension('K')->setAutoSize(true);
            $objSheet->getColumnDimension('L')->setAutoSize(true);
            $objSheet->getColumnDimension('M')->setAutoSize(true);
            $objSheet->getColumnDimension('N')->setAutoSize(true);
            $objSheet->getColumnDimension('O')->setAutoSize(true);
            $objSheet->getColumnDimension('P')->setAutoSize(true);
            $objSheet->getColumnDimension('Q')->setAutoSize(true);
            $objSheet->getColumnDimension('R')->setAutoSize(true);
            $objSheet->getColumnDimension('S')->setAutoSize(true);
            $objSheet->getColumnDimension('T')->setAutoSize(true);
            $objSheet->getColumnDimension('U')->setAutoSize(true);
            $objSheet->getColumnDimension('V')->setAutoSize(true);
            $objSheet->getColumnDimension('W')->setAutoSize(true);
            $objSheet->getColumnDimension('X')->setAutoSize(true);
            $objSheet->getColumnDimension('Y')->setAutoSize(true);
            $objSheet->getColumnDimension('Z')->setAutoSize(true);
            $objSheet->getColumnDimension('AA')->setAutoSize(true);
            $objSheet->getColumnDimension('AB')->setAutoSize(true);
            $objSheet->getColumnDimension('AC')->setAutoSize(true);
            $objSheet->getColumnDimension('AD')->setAutoSize(true);
            $objSheet->getColumnDimension('AE')->setAutoSize(true);
            $objSheet->getColumnDimension('AF')->setAutoSize(true);
            $objSheet->getColumnDimension('AG')->setAutoSize(true);
            $objSheet->getColumnDimension('AH')->setAutoSize(true);
            $objSheet->getColumnDimension('AI')->setAutoSize(true);
            $objSheet->getColumnDimension('AJ')->setAutoSize(true);
            $objSheet->getColumnDimension('AK')->setAutoSize(true);
            $objSheet->getColumnDimension('AL')->setAutoSize(true);
            $objSheet->getColumnDimension('AM')->setAutoSize(true);
            $objSheet->getColumnDimension('AN')->setAutoSize(true);
            $objSheet->getColumnDimension('AO')->setAutoSize(true);
            $objSheet->getColumnDimension('AP')->setAutoSize(true);
            $objSheet->getColumnDimension('AQ')->setAutoSize(true);
            $objSheet->getColumnDimension('AR')->setAutoSize(true);
            $objSheet->getColumnDimension('AS')->setAutoSize(true);
            $objSheet->getColumnDimension('AT')->setAutoSize(true);
            $objSheet->getColumnDimension('AU')->setAutoSize(true);
            $objSheet->getColumnDimension('AV')->setAutoSize(true);
           // $objSheet->getColumnDimension('AW')->setAutoSize(true);
           // $objSheet->getColumnDimension('AY')->setAutoSize(true);


            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }
            ob_end_clean();
            $objWriter->save($path_file . "masteremployee_" . $param . $ext);
            $data = file_get_contents($path_file . "masteremployee_" . $param . $ext);
            force_download("masteremployee_" . $param . $ext, $data);
        }
    }

    function createnip() {
        $hire = $this->input->post('hiredate');
        $flag = $this->input->post('flag');

        $lastnip = $this->pbl->lastnip();
        $temp = "0000" . $lastnip;
        $newnip = substr($temp, -4);
        $hiredate = date('dmy', strtotime($hire));

        if ($flag == 'add') {
            $nip = $newnip . $hiredate;
        } else if ($flag == 'dup') {
            $nip = substr($this->session->userdata('idemp_on_clipboard'), 0, 4) . $hiredate;
        }

        $valid = 'true';
        $json = '{ "newnip":"' . $nip . '",
                   "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function excel_bpjs() {
        // ini_set('memory_limit', '-1'); // for unlimited size from file excel

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        // add class excel
        $excel = new PHPExcel();

        //add property
        $excel->getProperties()->setCreator("PHP Excel")
                ->setLastModifiedBy("PHP Excel")
                ->setTitle("DATA BPJS")
                ->setSubject("DATA BPJS")
                ->setDescription("DATA BPJS")
                ->setKeywords("DATA BPJS")
                ->setCategory("DATA BPJS");

        //add style
        $worksheet = $excel->getActiveSheet();
        $worksheet->getStyle('B1:AH16')->getFont()->setBold(true)->setSize(12);


//add header      
        $worksheet->getCell('A9')->setValue('PT. TRIAS INDRA SAPUTRA');
        $worksheet->getCell('A10')->setValue('LAPORAN DATA BPJS');


//add sub header
        //$worksheet->getCell('A14')->setValue('No.');
        $worksheet->getCell('B15')->setValue('No KK');
        $worksheet->getCell('B16')->setValue('2');
        $worksheet->getCell('C15')->setValue('NIK/KITAS/KITAP');
        $worksheet->getCell('C16')->setValue('3');
        $worksheet->getCell('D15')->setValue('Nama Lengkap');
        $worksheet->getCell('D16')->setValue('4');
        $worksheet->getCell('E14')->setValue('HUBKEL');
        $worksheet->getCell('E15')->setValue('1=P, 2=S, 3=I, 4=A, 5=T');
        $worksheet->getCell('E16')->setValue('5');
        $worksheet->getCell('F14')->setValue('Tgl Lahir');
        $worksheet->getCell('F15')->setValue('Tempat Lahir');
        $worksheet->getCell('F16')->setValue('6');
        $worksheet->getCell('G15')->setValue('dd/mm/yyyy');
        $worksheet->getCell('G16')->setValue('7');
        $worksheet->getCell('H14')->setValue('Jenis Ke1amin');
        $worksheet->getCell('H15')->setValue('1=L, 2=P');
        $worksheet->getCell('H16')->setValue('8');
        $worksheet->getCell('I14')->setValue('Status kawin');
        $worksheet->getCell('I15')->setValue('1=BK, 2=K, 3=CH, 4=CM, 5= TDK TAU');
        $worksheet->getCell('I16')->setValue('9');
        $worksheet->getCell('J15')->setValue('Alamat Tempat Tinggal');
        $worksheet->getCell('J16')->setValue('10');
        $worksheet->getCell('K15')->setValue('RT');
        $worksheet->getCell('K16')->setValue('11');
        $worksheet->getCell('L15')->setValue('RW');
        $worksheet->getCell('L16')->setValue('12');
        $worksheet->getCell('M15')->setValue('Kode Pos');
        $worksheet->getCell('M16')->setValue('13');
        $worksheet->getCell('N15')->setValue('Kode Kecamatan');
        $worksheet->getCell('N16')->setValue('14');
        $worksheet->getCell('O15')->setValue('Nama Kecamatan');
        $worksheet->getCell('O16')->setValue('15');
        $worksheet->getCell('P15')->setValue('Kode Desa');
        $worksheet->getCell('P16')->setValue('16');
        $worksheet->getCell('Q15')->setValue('Nama Desa');
        $worksheet->getCell('Q16')->setValue('17');
        $worksheet->getCell('R15')->setValue('Kode Faskes Tk.I ');
        $worksheet->getCell('R16')->setValue('18');
        $worksheet->getCell('S15')->setValue('Nama Faskes Tk.I');
        $worksheet->getCell('S16')->setValue('19');
        $worksheet->getCell('T15')->setValue('Kode Faskes Dokter Gigi');
        $worksheet->getCell('T16')->setValue('20');
        $worksheet->getCell('U15')->setValue('Nama Faskes Dokter Gigi');
        $worksheet->getCell('U16')->setValue('21');
        $worksheet->getCell('V15')->setValue('Nomor Telepon Peserta');
        $worksheet->getCell('V16')->setValue('22');
        $worksheet->getCell('W15')->setValue('Email');
        $worksheet->getCell('W16')->setValue('23');
        $worksheet->getCell('X15')->setValue('NPP');
        $worksheet->getCell('X16')->setValue('24');
        $worksheet->getCell('Y15')->setValue('Jabatan');
        $worksheet->getCell('Y16')->setValue('25');
        $worksheet->getCell('Z14')->setValue('Status');
        $worksheet->getCell('Z15')->setValue('1=Tetap, 2=Kontrak, 3=Paruh waktu, 4=Penerima Pensiun');
        $worksheet->getCell('Z16')->setValue('26');
        $worksheet->getCell('AA14')->setValue('Kelas Rawat');
        $worksheet->getCell('AA15')->setValue('1=Kelas I, 2=Kelas II, 3=Kelas III');
        $worksheet->getCell('AA16')->setValue('27');
        $worksheet->getCell('AB15')->setValue('TMT Kerja (Kary. Aktif)');
        $worksheet->getCell('AB16')->setValue('28');
        $worksheet->getCell('AC15')->setValue('Gaji Pokok + Tunj. Tetap (Kary. Aktif)');
        $worksheet->getCell('AC16')->setValue('29');
        $worksheet->getCell('AD14')->setValue('Kewarga Negaraan');
        $worksheet->getCell('AD15')->setValue('1=WNI, 2=WNA');
        $worksheet->getCell('AD16')->setValue('30');
        $worksheet->getCell('AE14')->setValue('Asuransi Lainnya');
        $worksheet->getCell('AE15')->setValue('No. Polis');
        $worksheet->getCell('AE16')->setValue('31');
        $worksheet->getCell('AF15')->setValue('Nama Asuransi');
        $worksheet->getCell('AF16')->setValue('32');
        $worksheet->getCell('AG15')->setValue('No. NPWP');
        $worksheet->getCell('AG16')->setValue('33');
        $worksheet->getCell('AH15')->setValue('No Passport');
        $worksheet->getCell('AH16')->setValue('34');

// add mergecell 
        $sheet = $excel->getActiveSheet();
        $sheet->mergeCells('F14:G14');
        $sheet->mergeCells('AE14:AF14');

//add center   
        $sheet->getStyle('F14:G14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AE14:AF14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B14:B16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C14:C16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D14:D16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E14:E16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F14:F16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G14:G16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H14:H16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J14:J16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K14:K16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L14:L16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('M14:M16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N14:N16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('O14:O16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('P14:P16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Q14:Q16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('R14:R16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('S14:S16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('T14:T16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('U14:U16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('V14:V16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('W14:W16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('X14:X16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Y14:Y16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Z14:Z16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AA14:AA16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AB14:AB16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AC14:AC16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AD14:AD16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AE14:AE16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AF14:AF16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AG14:AG16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AH14:AH16')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



//add border
        $worksheet->getStyle('B14:AH15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        // $worksheet->getStyle('A14:A15')->getBorders()->
        //         getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('B14:B15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('B16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('C14:C15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('C16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('D14:D15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('D16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('F14:G14')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('F15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('F16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('G15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('G16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('H14')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('H15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('H16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('I14')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('I15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('I16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('J14:J15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('J16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('K14:K15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('K16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('L14:L15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('L16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('M14:M15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('M16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('N14:N15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('N16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('O14:O15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('O16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('P14:P15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('P16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Q14:Q15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Q16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('R14:R15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('R16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('S14:S15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('S16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('T14:T15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('T16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('U14:U15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('U16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('V14:V15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('V16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('W14:W15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('W16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('X14:X15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('X16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Y14:Y15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Y16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Z14')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Z15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Z16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AA14')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AA15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AA16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AB14:AB15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AC14:AC15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AD14')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AD15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AD16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AE14')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AE15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AE16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AF14:AF15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AF16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AG14:AG15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AG16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AH14:AH15')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AH16')->getBorders()->getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $result = $this->pbl->getall_employee_public('A', 'AL', 'AL')->result_array();


        if ($result != NULL) {
            $i = 16;
            $counter = 0;
            $no = 0;
            $lastnip = '';
            $lastnip2 = '';
            $id = '';

            foreach ($result as $row) {
                if ($counter == 0) {
                    $counter = 1;
                    $id = $row['IDEmployee'];
                }
                $no++;
                $i++;

                $jk = $this->libfun->get_gender($row['Gender']);
                if ($jk == 'MALE') {
                    $gender = '1';
                } else {
                    $gender = '2';
                }

                $ktp = ($row["NoKTP"] == NULL or $row['NoKTP'] == "") ? "-" : $row["NoKTP"];
                $name = ($row["FullName"] == NULL or $row['FullName'] == "") ? "-" : $row["FullName"];
                $p = ($row["IDEmployee"] == NULL or $row['IDEmployee'] == "") ? "5" : "1";
                $a = $row["NumberChildren"];

                if ($row["MaritalStatus"] == 'MARRIED') {
                    $suamiistri = 'exist';
                } else {
                    $suamiistri = 'empty';
                }

                if ($a !== '0') {
                    $anak = '4';
                } else {
                    $anak = '0';
                }

                if ($suamiistri == 'exist') {
                    if ($gender == '1') {
                        $menanggung = '3';
                        $genderp = '2';
                    } else if ($gender == '2') {
                        $menanggung = '2';
                        $genderp = '1';
                    }

                    $tanggungan = $menanggung;
                } else {
                    $tanggungan = '';
                }


                $tgllahir = ($row["BirthDate"] == NULL or $row['BirthDate'] == "") ? "-" : date('d/m/Y', strtotime($row["BirthDate"]));
                $tempatlahir = ($row["BirthPlace"] == NULL or $row['BirthPlace'] == "") ? "-" : $row["BirthPlace"];

                if ($row["MaritalStatus"] == 'SINGLE') {
                    $statuskawin = '1';
                } else if ($row["MaritalStatus"] == 'MARRIED') {
                    $statuskawin = '2';
                } else if ($row["MaritalStatus"] == 'DIVORCED') {
                    $statuskawin = '3';
                } else {
                    $statuskawin = '5';
                }

                $addressktp = ($row["KTPAddress"] == NULL or $row['KTPAddress'] == "") ? "-" : $row["KTPAddress"];
                $nort = ($row["KTPRT"] == NULL or $row['KTPRT'] == "") ? "-" : "'" . $row["KTPRT"];
                $norw = ($row["KTPRW"] == NULL or $row['KTPRW'] == "") ? "-" : "'" . $row["KTPRW"];
                $nopos = ($row["KTPPostalCode"] == NULL or $row['KTPPostalCode'] == "") ? "-" : $row["KTPPostalCode"];
                $desa = ($row["KTPVillage"] == NULL or $row['KTPVillage'] == "") ? "-" : $row["KTPVillage"];
                $kecamatan = ($row["KTPSubdistrict"] == NULL or $row['KTPSubdistrict'] == "") ? "-" : $row["KTPSubdistrict"];



                $idfamcert = ($row["NoFamCert"] == NULL or $row['NoFamCert'] == "") ? "-" : $row["NoFamCert"];
                $telphp = ($row["NoHp"] == NULL or $row['NoHp'] == "") ? "-" : "'" . $row["NoHp"];
                $telprmh = ($row["KTPAddressNoTelp"] == NULL or $row['KTPAddressNoTelp'] == "") ? " " : $row["KTPAddressNoTelp"];
                $email = ($row["ExternalEmail"] == NULL or $row['ExternalEmail'] == "") ? " " : $row["ExternalEmail"];
                $npwp = ($row["NoNPWP"] == NULL or $row['NoNPWP'] == "") ? " " : "'" . $row["NoNPWP"];
                $faskescode = ($row["FakesCode"] == NULL or $row['FakesCode'] == "") ? "-" : "'" . $row["FakesCode"];
                $faskesname = ($row["FakesName"] == NULL or $row['FakesName'] == "") ? "-" : $row["FakesName"];
                $npp = ($row["NPPNo"] == NULL or $row['NPPNo'] == "") ? "-" : "'" . $row["NPPNo"];
                $police = ($row["PoliceNo"] == NULL or $row['PoliceNo'] == "") ? "-" : "'" . $row["PoliceNo"];
                $passport = ($row["PassportNo"] == NULL or $row['PassportNo'] == "") ? "-" : "'" . $row["PassportNo"];
                $insurancename = ($row["InsuranceName"] == NULL or $row['InsuranceName'] == "-") ? "'" : $row["InsuranceName"];
                $wni = $row['Citizenship'];

                if ($wni == 'INDONESIA') {
                    $statuswni = '1';
                } else if ($wni == 'WNI') {
                    $statuswni = '1';
                } else if ($wni == '') {
                    $statuswni = '1';
                } else if ($wni == NULL) {
                    $statuswni = '1';
                } else {
                    $statuswni = '2';
                }


                $group = $row['IDJobGroup'];

                if ($group == 'LT') {
                    $statuskerja = '1';
                    $hire = date('d/m/Y', strtotime($row['HireDate']));
                } else if ($group == 'LK') {
                    $statuskerja = '2';
                    $hire = date('d/m/Y', strtotime($row['DateNewContract']));
                } else if ($group == 'ST') {
                    $statuskerja = '1';
                    $hire = date('d/m/Y', strtotime($row['HireDate']));
                }
                $hd = ($hire == '01/01/1970') ? ' - ' : $hire;

                if (($row['IDEmployee'] != $id) OR ( $no == 1)) {
                    // $worksheet->getCell('A' . $i)->setValue($no);
                    $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                    $worksheet->getCell('C' . $i)->setValue("'" . $ktp);
                    $worksheet->getCell('D' . $i)->setValue($name);
                    $worksheet->getCell('E' . $i)->setValue($p);
                    $worksheet->getCell('F' . $i)->setValue($tempatlahir);
                    $worksheet->getCell('G' . $i)->setValue($tgllahir);
                    $worksheet->getCell('H' . $i)->setValue($gender);
                    $worksheet->getCell('I' . $i)->setValue($statuskawin);
                    $worksheet->getCell('J' . $i)->setValue($addressktp);
                    $worksheet->getCell('K' . $i)->setValue($nort);
                    $worksheet->getCell('L' . $i)->setValue($norw);
                    $worksheet->getCell('M' . $i)->setValue($nopos);
                    $worksheet->getCell('N' . $i)->setValue('-');
                    $worksheet->getCell('O' . $i)->setValue($kecamatan);
                    $worksheet->getCell('P' . $i)->setValue('-');
                    $worksheet->getCell('Q' . $i)->setValue($desa);
                    $worksheet->getCell('R' . $i)->setValue($faskescode);
                    $worksheet->getCell('S' . $i)->setValue($faskesname);
                    $worksheet->getCell('T' . $i)->setValue('-');
                    $worksheet->getCell('U' . $i)->setValue('-');
                    $worksheet->getCell('V' . $i)->setValue($telphp . $telprmh);
                    $worksheet->getCell('W' . $i)->setValue($email);
                    $worksheet->getCell('X' . $i)->setValue($npp);
                    $worksheet->getCell('Y' . $i)->setValue($row['IDJobPosition']);
                    $worksheet->getCell('Z' . $i)->setValue($statuskerja);
                    $worksheet->getCell('AA' . $i)->setValue('-');
                    $worksheet->getCell('AB' . $i)->setValue($hd);
                    $worksheet->getCell('AC' . $i)->setValue('-');
                    $worksheet->getCell('AD' . $i)->setValue($statuswni);
                    $worksheet->getCell('AE' . $i)->setValue($police);
                    $worksheet->getCell('AF' . $i)->setValue($insurancename);
                    $worksheet->getCell('AG' . $i)->setValue($npwp);
                    $worksheet->getCell('AH' . $i)->setValue($passport);
                }


                if ($lastnip != $row['IDEmployee'] && $tanggungan !== '') {
                    $i++;


                    $rowspouse = $this->pbl->spouse($row['IDEmployee']);

                    $birthplacespouse = ($rowspouse->BirthPlace == NULL or $rowspouse->BirthPlace == "") ? "-" : $rowspouse->BirthPlace;
                    $tgllahirspouse = ($rowspouse->BirthDate == NULL or $rowspouse->BirthDate == "") ? "-" : date('d/m/Y', strtotime($rowspouse->BirthDate));


                    $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                    $worksheet->getCell('C' . $i)->setValue("'" . $rowspouse->NoKTP);
                    $worksheet->getCell('D' . $i)->setValue($rowspouse->Name);
                    $worksheet->getCell('E' . $i)->setValue($tanggungan);
                    $worksheet->getCell('F' . $i)->setValue($birthplacespouse);
                    $worksheet->getCell('G' . $i)->setValue($tgllahirspouse);
                    $worksheet->getCell('H' . $i)->setValue($genderp);
                    $worksheet->getCell('I' . $i)->setValue($statuskawin);
                    $worksheet->getCell('J' . $i)->setValue($rowspouse->Address);
                    $worksheet->getCell('K' . $i)->setValue($nort);
                    $worksheet->getCell('L' . $i)->setValue($norw);
                    $worksheet->getCell('M' . $i)->setValue($nopos);
                    $worksheet->getCell('N' . $i)->setValue('-');
                    $worksheet->getCell('O' . $i)->setValue($kecamatan);
                    $worksheet->getCell('P' . $i)->setValue('-');
                    $worksheet->getCell('Q' . $i)->setValue($desa);
                    $worksheet->getCell('R' . $i)->setValue($faskescode);
                    $worksheet->getCell('S' . $i)->setValue($faskesname);
                    $worksheet->getCell('T' . $i)->setValue('-');
                    $worksheet->getCell('U' . $i)->setValue('-');
                    $worksheet->getCell('V' . $i)->setValue($telphp . $telprmh);
                    $worksheet->getCell('W' . $i)->setValue($email);
                    $worksheet->getCell('X' . $i)->setValue("-");
                    $worksheet->getCell('Y' . $i)->setValue('-');
                    $worksheet->getCell('Z' . $i)->setValue('-');
                    $worksheet->getCell('AA' . $i)->setValue('-');
                    $worksheet->getCell('AB' . $i)->setValue('-');
                    $worksheet->getCell('AC' . $i)->setValue('-');
                    $worksheet->getCell('AD' . $i)->setValue('1');
                    $worksheet->getCell('AE' . $i)->setValue('-');
                    $worksheet->getCell('AF' . $i)->setValue($insurancename);
                    $worksheet->getCell('AG' . $i)->setValue('-');
                    $worksheet->getCell('AH' . $i)->setValue('-');


                    if ($lastnip2 != $row['IDEmployee'] && $anak !== '0') {

                        $resultchild = $this->pbl->children($row['IDEmployee']);

                        if ($resultchild) {
                            foreach ($resultchild as $rowchild) {

                                $birthplacechild = ($rowchild["BirthPlace"] == NULL or $rowchild['BirthPlace'] == "") ? "-" : $rowchild["BirthPlace"];
                                $tgllahirchild = ($rowchild["BirthDate"] == NULL or $rowchild['BirthDate'] == "") ? "-" : date('d/m/Y', strtotime($rowchild["BirthDate"]));

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("'" . $rowchild['NoKTP']);
                                $worksheet->getCell('D' . $i)->setValue($rowchild['Name']);
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue($birthplacechild);
                                $worksheet->getCell('G' . $i)->setValue($tgllahirchild);
                                $worksheet->getCell('H' . $i)->setValue('-');
                                $worksheet->getCell('I' . $i)->setValue('-');
                                $worksheet->getCell('J' . $i)->setValue($rowchild['Address']);
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue($telphp . $telprmh);
                                $worksheet->getCell('W' . $i)->setValue($email);
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue('-');
                                $worksheet->getCell('Z' . $i)->setValue('-');
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue('-');
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue('-');
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            }
                        } else {

                            if ($a == '1') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            } else if ($a == '2') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            } else if ($a == '3') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            } else if ($a == '4') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');


                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            } else if ($a == '5') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');


                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');


                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            } else if ($a == '6') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');


                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("'" . $idfamcert);
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue($nort);
                                $worksheet->getCell('L' . $i)->setValue($norw);
                                $worksheet->getCell('M' . $i)->setValue($nopos);
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue($kecamatan);
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue($desa);
                                $worksheet->getCell('R' . $i)->setValue($faskescode);
                                $worksheet->getCell('S' . $i)->setValue($faskesname);
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue("-");
                                $worksheet->getCell('W' . $i)->setValue("-");
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue("-");
                                $worksheet->getCell('Z' . $i)->setValue("-");
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue("-");
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue($insurancename);
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            }
                        }
                    }
                }
            }
        }

        /// body border 
        $worksheet->getStyle('B17:AH' . $i)->getBorders()->getAllBorders()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('B17:AH' . $i)->getBorders()->getOutline()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('B17:AH' . $i)->getBorders()->getBottom()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        // footer
        $j = $i + 3;
        $k = $i + 4;
        $l = $i + 8;
        $worksheet->getCell('AF' . $j)->setValue('Jakarta, ' . date('d-F-Y'));
        $worksheet->getCell('AF' . $k)->setValue('PT. Trias Indra Saputra');
        $worksheet->getCell('AF' . $l)->setValue('System Development');

        ob_end_clean();

        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="employeeforbpjs.xlsx"');


        $objWriter = IOFactory::createWriter($excel, 'Excel2007');
        //$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        $excel->disconnectWorksheets();
        unset($excel);
    }

    function getallprovince() {
        $where = array(
            "lokasi_kabupaten" => "00",
            "lokasi_kecamatan" => "00",
            "lokasi_kelurahan" => "0000"
        );
        $prov = $this->addr->get_location($where)->result();
        echo json_encode($prov);
    }

    function get_cities() {
        $kodeprov = $this->anti_xss($this->input->post("kodeprov"));
        $where = array(
            "lokasi_provinsi" => $kodeprov,
            "lokasi_kabupaten !=" => "00",
            "lokasi_kecamatan" => "00",
            "lokasi_kelurahan" => "0000"
        );
        $cities = $this->addr->get_location($where)->result();
        echo json_encode($cities);
    }

    function get_subs() {
        $kodeprov = $this->anti_xss($this->input->post("kodeprov"));
        $kodekota = $this->anti_xss($this->input->post("kodekota"));
        $where = array(
            "lokasi_provinsi" => $kodeprov,
            "lokasi_kabupaten " => $kodekota,
            "lokasi_kecamatan !=" => "00",
            "lokasi_kelurahan" => "0000"
        );
        $subs = $this->addr->get_location($where)->result();
        echo json_encode($subs);
    }

    function get_vlgs() {
        $kodeprov = $this->anti_xss($this->input->post("kodeprov"));
        $kodekota = $this->anti_xss($this->input->post("kodekota"));
        $kodekec = $this->anti_xss($this->input->post("kodekec"));
        $where = array(
            "lokasi_provinsi" => $kodeprov,
            "lokasi_kabupaten " => $kodekota,
            "lokasi_kecamatan" => $kodekec,
            "lokasi_kelurahan !=" => "0000"
        );
        $desa = $this->addr->get_location($where)->result();
        echo json_encode($desa);
    }

    function historydata($nip, $function, $historyto) {


        if ($historyto == 'personalemp') {
            $rowpersonal = $this->history->get_emp_personal($nip);

            $recordhispersonal = array(
                "IDEmployee" => $rowpersonal->IDEmployee,
                "IDEmployeeParent" => $rowpersonal->IDEmployeeParent,
                "FullName" => $rowpersonal->FullName,
                "EmailExternal" => $rowpersonal->EmailExternal,
                "EmailInternal" => $rowpersonal->EmailInternal,
                "Gender" => $rowpersonal->Gender,
                "BankAccount" => $rowpersonal->BankAccount,
                "IDLocation" => $rowpersonal->IDLocation,
                "IDJobGroup" => $rowpersonal->IDJobGroup,
                "IDDepartement" => $rowpersonal->IDDepartement,
                "IDUnitGroup" => $rowpersonal->IDUnitGroup,
                "HireDate" => $rowpersonal->HireDate,
                "FlagHire" => $rowpersonal->FlagHire,
                "ResignDate" => $rowpersonal->ResignDate,
                "FlagResign" => $rowpersonal->FlagResign,
                "Status" => $rowpersonal->Status,
                "AddedBy" => $rowpersonal->AddedBy,
                "AddedDate" => $rowpersonal->AddedDate,
                "AddedIP" => $rowpersonal->AddedIP,
                "EditedBy" => $rowpersonal->EditedBy,
                "EditedDate" => $rowpersonal->EditedDate,
                "EditedIP" => $rowpersonal->EditedIP,
                "PublicStatus" => $rowpersonal->PublicStatus,
                "DeletedBy" => $rowpersonal->DeletedBy,
                "DeletedIP" => $rowpersonal->DeletedIP,
                "DeletedDate" => $rowpersonal->DeletedDate,
                "DeleteFlag" => $rowpersonal->DeleteFlag,
                "HistBy" => $this->User,
                "HistDate" => $this->Datetime,
                "HistIP" => $this->Ip,
                "IDTable" => $rowpersonal->ID,
                "FunctionOn" => $function
            );

            $this->history->insert_his_personal_emp($recordhispersonal);
        } else if ($historyto == 'personalemp_detail') {

            $rowpersonal_d = $this->history->get_emp_personal_detail($nip);
            $recordhispersonald = array(
                "IDEmployee" => $rowpersonal_d->IDEmployee,
                "IDEmployeeParent" => $rowpersonal_d->IDEmployeeParent,
                "FullName" => $rowpersonal_d->FullName,
                "BankAccount" => $rowpersonal_d->BankAccount,
                "NoJamsostek" => $rowpersonal_d->NoJamsostek,
                "NickName" => $rowpersonal_d->NickName,
                "BirthPlace" => $rowpersonal_d->BirthPlace,
                "Citizenship" => $rowpersonal_d->Citizenship,
                "BirthDate" => $rowpersonal_d->BirthDate,
                "Height" => $rowpersonal_d->Height,
                "Weight" => $rowpersonal_d->Weight,
                "Religion" => $rowpersonal_d->Religion,
                "IDEducation" => $rowpersonal_d->IDEducation,
                "IDMajors" => $rowpersonal_d->IDMajors,
                "MaritalStatus" => $rowpersonal_d->MaritalStatus,
                "Gender" => $rowpersonal_d->Gender,
                "MarriageCertificate" => $rowpersonal_d->MarriageCertificate,
                "FamilyMemberCertificate" => $rowpersonal_d->FamilyMemberCertificate,
                "CoupleKTP" => $rowpersonal_d->CoupleKTP,
                "NumberChildren" => $rowpersonal_d->NumberChildren,
                "FirstChild" => $rowpersonal_d->FirstChild,
                "SecondChild" => $rowpersonal_d->SecondChild,
                "CoupleName" => $rowpersonal_d->CoupleName,
                "BloodType" => $rowpersonal_d->BloodType,
                "NoTelp" => $rowpersonal_d->NoTelp,
                "NoHp" => $rowpersonal_d->NoHp,
                "NoNPWP" => $rowpersonal_d->NoNPWP,
                "NoKPJ" => $rowpersonal_d->NoKPJ,
                "NoKTP" => $rowpersonal_d->NoKTP,
                "NoAKDHK" => $rowpersonal_d->NoAKDHK,
                "LiveAddress" => $rowpersonal_d->LiveAddress,
                "LiveAddressNoTelp" => $rowpersonal_d->LiveAddressNoTelp,
                "KTPAddress" => $rowpersonal_d->KTPAddress,
                "KTPAddressNoTelp" => $rowpersonal_d->KTPAddressNoTelp,
                "IDJobPosition" => $rowpersonal_d->IDJobPosition,
                "IDLocation" => $rowpersonal_d->IDLocation,
                "IDJobGroup" => $rowpersonal_d->IDJobGroup,
                "IDDepartement" => $rowpersonal_d->IDDepartement,
                "IDUnitGroup" => $rowpersonal_d->IDUnitGroup,
                "DateFirstJoint" => $rowpersonal_d->DateFirstJoint,
                "DateStartProbation" => $rowpersonal_d->DateStartProbation,
                "DateEndProbation" => $rowpersonal_d->DateEndProbation,
                "DatePassProbation" => $rowpersonal_d->DatePassProbation,
                "DateNewContract" => $rowpersonal_d->DateNewContract,
                "DateEndContract" => $rowpersonal_d->DateEndContract,
                "DateInField" => $rowpersonal_d->DateInField,
                "EmailInternal" => $rowpersonal_d->EmailInternal,
                "EmailExternal" => $rowpersonal_d->EmailExternal,
                "Extension" => $rowpersonal_d->Extension,
                "HireDate" => $rowpersonal_d->HireDate,
                "FlagHire" => $rowpersonal_d->FlagHire,
                "ResignDate" => $rowpersonal_d->ResignDate,
                "FlagResign" => $rowpersonal_d->FlagResign,
                "ReasonResign" => $rowpersonal_d->ReasonResign,
                "Status" => $rowpersonal_d->Status,
                "EmployeeStatus" => $rowpersonal_d->EmployeeStatus,
                "Note" => $rowpersonal_d->Note,
                "AddedBy" => $rowpersonal_d->AddedBy,
                "AddedDate" => $rowpersonal_d->AddedDate,
                "AddedIP" => $rowpersonal_d->AddedIP,
                "EditedBy" => $rowpersonal_d->EditedBy,
                "EditedDate" => $rowpersonal_d->EditedDate,
                "EditedIP" => $rowpersonal_d->EditedIP,
                "NoBPJSEmp" => $rowpersonal_d->NoBPJSEmp,
                "NoBPJSHlt" => $rowpersonal_d->NoBPJSHlt,
                "NoFamCert" => $rowpersonal_d->NoFamCert,
                "LiveProvince" => $rowpersonal_d->LiveProvince,
                "LiveCity" => $rowpersonal_d->LiveCity,
                "LiveSubdistrict" => $rowpersonal_d->LiveSubdistrict,
                "LiveVillage" => $rowpersonal_d->LiveVillage,
                "LiveRW" => $rowpersonal_d->LiveRW,
                "LiveRT" => $rowpersonal_d->LiveRT,
                "KTPProvince" => $rowpersonal_d->KTPProvince,
                "KTPCity" => $rowpersonal_d->KTPCity,
                "KTPSubdistrict" => $rowpersonal_d->KTPSubdistrict,
                "KTPVillage" => $rowpersonal_d->KTPVillage,
                "KTPRT" => $rowpersonal_d->KTPRT,
                "KTPRW" => $rowpersonal_d->KTPRW,
                "LivePostalCode" => $rowpersonal_d->LivePostalCode,
                "KTPPostalCode" => $rowpersonal_d->KTPPostalCode,
                "DeletedBy" => $rowpersonal_d->DeletedBy,
                "DeletedIP" => $rowpersonal_d->DeletedIP,
                "DeletedDate" => $rowpersonal_d->DeletedDate,
                "DeleteFlag" => $rowpersonal_d->DeleteFlag,
                "HistBy" => $this->User,
                "HistDate" => $this->Datetime,
                "HistIP" => $this->Ip,
                "IDTable" => $rowpersonal_d->ID,
                "FunctionOn" => $function,
                "WorkExperience" => $rowpersonal_d->WorkExperience,
            );


            $this->history->insert_his_personal_d_emp($recordhispersonald);
        } else if ($historyto == 'personalpbl') {

            $rowpersonalpbl = $this->history->get_pbl_personal($nip);
            $recordhispersonalpbl = array(
                "IDEmployee" => $rowpersonalpbl->IDEmployee,
                "IDEmployeeParent" => $rowpersonalpbl->IDEmployeeParent,
                "FullName" => $rowpersonalpbl->FullName,
                "NickName" => $rowpersonalpbl->NickName,
                "BirthPlace" => $rowpersonalpbl->BirthPlace,
                "BirthDate" => $rowpersonalpbl->BirthDate,
                "Gender" => $rowpersonalpbl->Gender,
                "BloodType" => $rowpersonalpbl->BloodType,
                "Citizenship" => $rowpersonalpbl->Citizenship,
                "Height" => $rowpersonalpbl->Height,
                "Weight" => $rowpersonalpbl->Weight,
                "Religion" => $rowpersonalpbl->Religion,
                "MaritalStatus" => $rowpersonalpbl->MaritalStatus,
                "MarriageCertificate" => $rowpersonalpbl->MarriageCertificate,
                "CoupleName" => $rowpersonalpbl->CoupleName,
                "CoupleKTP" => $rowpersonalpbl->CoupleKTP,
                "FamilyMemberCertificate" => $rowpersonalpbl->FamilyMemberCertificate,
                "NoKTP" => $rowpersonalpbl->NoKTP,
                "NoAKDHK" => $rowpersonalpbl->NoAKDHK,
                "NoNPWP" => $rowpersonalpbl->NoNPWP,
                "NoJamsostek" => $rowpersonalpbl->NoJamsostek,
                "NoKPJ" => $rowpersonalpbl->NoKPJ,
                "BankAccount" => $rowpersonalpbl->BankAccount,
                "LiveAddress" => $rowpersonalpbl->LiveAddress,
                "KTPAddress" => $rowpersonalpbl->KTPAddress,
                "NoHP" => $rowpersonalpbl->NoHP,
                "LiveAddressNoTelp" => $rowpersonalpbl->LiveAddressNoTelp,
                "KTPAddressNoTelp" => $rowpersonalpbl->KTPAddressNoTelp,
                "NumberChildren" => $rowpersonalpbl->NumberChildren,
                "InternalEmail" => $rowpersonalpbl->InternalEmail,
                "ExternalEmail" => $rowpersonalpbl->ExternalEmail,
                "EditedBy" => $rowpersonalpbl->EditedBy,
                "EditedDate" => $rowpersonalpbl->EditedDate,
                "EditedIP" => $rowpersonalpbl->EditedIP,
                "F1" => $rowpersonalpbl->F1,
                "F2" => $rowpersonalpbl->F2,
                "F3" => $rowpersonalpbl->F3,
                "F4" => $rowpersonalpbl->F4,
                "F5" => $rowpersonalpbl->F5,
                "F6" => $rowpersonalpbl->F6,
                "F7" => $rowpersonalpbl->F7,
                "NoBPJSEmp" => $rowpersonalpbl->NoBPJSEmp,
                "NoBPJSHlt" => $rowpersonalpbl->NoBPJSHlt,
                "NoFamCert" => $rowpersonalpbl->NoFamCert,
                "LiveProvince" => $rowpersonalpbl->LiveProvince,
                "LiveCity" => $rowpersonalpbl->LiveCity,
                "LiveSubdistrict" => $rowpersonalpbl->LiveSubdistrict,
                "LiveVillage" => $rowpersonalpbl->LiveVillage,
                "LiveRW" => $rowpersonalpbl->LiveRW,
                "LiveRT" => $rowpersonalpbl->LiveRT,
                "KTPProvince" => $rowpersonalpbl->KTPProvince,
                "KTPCity" => $rowpersonalpbl->KTPCity,
                "KTPSubdistrict" => $rowpersonalpbl->KTPSubdistrict,
                "KTPVillage" => $rowpersonalpbl->KTPVillage,
                "KTPRT" => $rowpersonalpbl->KTPRT,
                "KTPRW" => $rowpersonalpbl->KTPRW,
                "LivePostalCode" => $rowpersonalpbl->LivePostalCode,
                "KTPPostalCode" => $rowpersonalpbl->KTPPostalCode,
                "DeletedBy" => $rowpersonalpbl->DeletedBy,
                "DeletedIP" => $rowpersonalpbl->DeletedIP,
                "DeletedDate" => $rowpersonalpbl->DeletedDate,
                "DeleteFlag" => $rowpersonalpbl->DeleteFlag,
                "HistBy" => $this->User,
                "HistDate" => $this->Datetime,
                "HistIP" => $this->Ip,
                "IDTable" => $rowpersonalpbl->ID,
                "FunctionOn" => $function
            );

            $this->history->insert_his_personal_pbl($recordhispersonalpbl);
        } else if ($historyto == 'job') {

            $rowjob = $this->history->get_job($nip);
            $recordhisjob = array(
                "IDEmployee" => $rowjob->IDEmployee,
                "IDEmployeeParent" => $rowjob->IDEmployeeParent,
                "Location" => $rowjob->Location,
                "JobGroup" => $rowjob->JobGroup,
                "Department" => $rowjob->Department,
                "Position" => $rowjob->Position,
                "Unit" => $rowjob->Unit,
                "DateFirstJoin" => $rowjob->DateFirstJoin,
                "DateStartProbation" => $rowjob->DateStartProbation,
                "DateEndProbation" => $rowjob->DateEndProbation,	
                "DatePassProbation" => $rowjob->DatePassProbation,
                "DateNewContract" => $rowjob->DateNewContract,
                "DateEndContract" => $rowjob->DateEndContract,
                "DateInField" => $rowjob->DateInField,
                "Status" => $rowjob->Status,
                "HireDate" => $rowjob->HireDate,
                "FlagHire" => $rowjob->FlagHire,
                "ResignDate" => $rowjob->ResignDate,
                "FlagResign" => $rowjob->FlagResign,
                "EmployeeStatus" => $rowjob->EmployeeStatus,
                "ResignReason" => $rowjob->ResignReason,
                "Note" => $rowjob->Note,
                "DeletedBy" => $rowjob->DeletedBy,
                "DeletedIP" => $rowjob->DeletedIP,
                "DeletedDate" => $rowjob->DeletedDate,
                "DeleteFlag" => $rowjob->DeleteFlag,
                "HistBy" => $this->User,
                "HistDate" => $this->Datetime,
                "HistIP" => $this->Ip,
                "IDTable" => $rowjob->ID,
                "FunctionOn" => $function
            );

            $this->history->insert_his_job($recordhisjob);
        } else if ($historyto == 'family') {
            $resultfam = $this->history->get_family($nip);
            $checkfam = ($resultfam == '' or $resultfam == NULl) ? 'empty' : 'exist';
            if ($checkfam == 'exist') {
                foreach ($resultfam as $rowfam) {
                    $recordfam = array(
                        "IDEmployee" => $rowfam['IDEmployee'],
                        "IDFamily" => $rowfam['IDFamily'],
                        "NoKTP" => $rowfam['NoKTP'],
                        "FamilyMember" => $rowfam['FamilyMember'],
                        "Name" => $rowfam['Name'],
                        "BirthPlace" => $rowfam['BirthPlace'],
                        "BirthDate" => $rowfam['BirthDate'],
                        "Age" => $rowfam['Age'],
                        "Address" => $rowfam['Address'],
                        "Education" => $rowfam['Education'],
                        "Occupation" => $rowfam['Occupation'],
                        "DeletedBy" => $rowfam['DeletedBy'],
                        "DeletedIP" => $rowfam['DeletedIP'],
                        "DeletedDate" => $rowfam['DeletedDate'],
                        "DeleteFlag" => $rowfam['DeleteFlag'],
                        "HistBy" => $this->User,
                        "HistDate" => $this->Datetime,
                        "HistIP" => $this->Ip,
                        "IDTable" => $rowfam['ID'],
                        "FunctionOn" => $function
                    );

                    $this->history->insert_his_family($recordfam);
                }
            }
        } else if ($historyto == 'familyrow') {
            $rowfam = $this->history->get_familyrow($nip);
            $recordfamrow = array(
                "IDEmployee" => $rowfam->IDEmployee,
                "IDFamily" => $rowfam->IDFamily,
                "NoKTP" => $rowfam->NoKTP,
                "FamilyMember" => $rowfam->FamilyMember,
                "Name" => $rowfam->Name,
                "BirthPlace" => $rowfam->BirthPlace,
                "BirthDate" => $rowfam->BirthDate,
                "Age" => $rowfam->Age,
                "Address" => $rowfam->Address,
                "Education" => $rowfam->Education,
                "Occupation" => $rowfam->Occupation,
                "DeletedBy" => $rowfam->DeletedBy,
                "DeletedIP" => $rowfam->DeletedIP,
                "DeletedDate" => $rowfam->DeletedDate,
                "DeleteFlag" => $rowfam->DeleteFlag,
                "HistBy" => $this->User,
                "HistDate" => $this->Datetime,
                "HistIP" => $this->Ip,
                "IDTable" => $rowfam->ID,
                "FunctionOn" => $function
            );

            $this->history->insert_his_family($recordfamrow);
        } else if ($historyto == 'course') {
            $resultcourse = $this->history->get_course($nip);
            $checkcourse = ($resultcourse == '' or $resultcourse == NULl) ? 'empty' : 'exist';

            if ($checkcourse == 'exist') {
                foreach ($resultcourse as $rowcourse) {
                    $recordcourse = array(
                        "IDEmployee" => $rowcourse['IDEmployee'],
                        "IDCourse" => $rowcourse['IDCourse'],
                        "CourseProgram" => $rowcourse['CourseProgram'],
                        "CourseFacilitator" => $rowcourse['CourseFacilitator'],
                        "City" => $rowcourse['City'],
                        "Duration" => $rowcourse['Duration'],
                        "YearFrom" => $rowcourse['YearFrom'],
                        "YearUntil" => $rowcourse['YearUntil'],
                        "DeletedBy" => $rowcourse['DeletedBy'],
                        "DeletedIP" => $rowcourse['DeletedIP'],
                        "DeletedDate" => $rowcourse['DeletedDate'],
                        "DeleteFlag" => $rowcourse['DeleteFlag'],
                        "HistBy" => $this->User,
                        "HistDate" => $this->Datetime,
                        "HistIP" => $this->Ip,
                        "IDTable" => $rowcourse['ID'],
                        "FunctionOn" => $function
                    );

                    $this->history->insert_his_course($recordcourse);
                }
            }
        } else if ($historyto == 'courserow') {
            $rowcourse = $this->history->get_courserow($nip);
            $recordcourserow = array(
                "IDEmployee" => $rowcourse->IDEmployee,
                "IDCourse" => $rowcourse->IDCourse,
                "CourseProgram" => $rowcourse->CourseProgram,
                "CourseFacilitator" => $rowcourse->CourseFacilitator,
                "City" => $rowcourse->City,
                "Duration" => $rowcourse->Duration,
                "YearFrom" => $rowcourse->YearFrom,
                "YearUntil" => $rowcourse->YearUntil,
                "DeletedBy" => $rowcourse->DeletedBy,
                "DeletedIP" => $rowcourse->DeletedIP,
                "DeletedDate" => $rowcourse->DeletedDate,
                "DeleteFlag" => $rowcourse->DeleteFlag,
                "HistBy" => $this->User,
                "HistDate" => $this->Datetime,
                "HistIP" => $this->Ip,
                "IDTable" => $rowcourse->ID,
                "FunctionOn" => $function
            );

            $this->history->insert_his_course($recordcourserow);
        } else if ($historyto == 'education') {
            $resulteducation = $this->history->get_education($nip);
            $checkeducation = ($resulteducation == '' or $resulteducation == NULl) ? 'empty' : 'exist';

            if ($checkeducation == 'exist') {
                foreach ($resulteducation as $roweducation) {
                    $recordeducation = array(
                        "IDEmployee" => $roweducation['IDEmployee'],
                        "IDEducation" => $roweducation['IDEducation'],
                        "EducationLevel" => $roweducation['EducationLevel'],
                        "Course" => $roweducation['Course'],
                        "SchoolName" => $roweducation['SchoolName'],
                        "City" => $roweducation['City'],
                        "YearFrom" => $roweducation['YearFrom'],
                        "YearUntil" => $roweducation['YearUntil'],
                        "Certificate" => $roweducation['Certificate'],
                        "DeletedBy" => $roweducation['DeletedBy'],
                        "DeletedIP" => $roweducation['DeletedIP'],
                        "DeletedDate" => $roweducation['DeletedDate'],
                        "DeleteFlag" => $roweducation['DeleteFlag'],
                        "HistBy" => $this->User,
                        "HistDate" => $this->Datetime,
                        "HistIP" => $this->Ip,
                        "IDTable" => $roweducation['ID'],
                        "FunctionOn" => $function
                    );
                    $this->history->insert_his_education($recordeducation);
                }
            }
        } else if ($historyto == 'educationrow') {
            $roweducation = $this->history->get_educationrow($nip);
            $recordeducationrow = array(
                "IDEmployee" => $roweducation->IDEmployee,
                "IDEducation" => $roweducation->IDEducation,
                "EducationLevel" => $roweducation->EducationLevel,
                "Course" => $roweducation->Course,
                "SchoolName" => $roweducation->SchoolName,
                "City" => $roweducation->City,
                "YearFrom" => $roweducation->YearFrom,
                "YearUntil" => $roweducation->YearUntil,
                "Certificate" => $roweducation->Certificate,
                "DeletedBy" => $roweducation->DeletedBy,
                "DeletedIP" => $roweducation->DeletedIP,
                "DeletedDate" => $roweducation->DeletedDate,
                "DeleteFlag" => $roweducation->DeleteFlag,
                "HistBy" => $this->User,
                "HistDate" => $this->Datetime,
                "HistIP" => $this->Ip,
                "IDTable" => $roweducation->ID,
                "FunctionOn" => $function
            );
            $this->history->insert_his_education($recordeducationrow);
        } else if ($historyto == 'language') {
            $resultlanguage = $this->history->get_language($nip);
            $checklanguage = ($resultlanguage == '' or $resultlanguage == NULl) ? 'empty' : 'exist';

            if ($checklanguage == 'exist') {
                foreach ($resultlanguage as $rowlanguage) {
                    $recordlanguage = array(
                        "IDEmployee" => $rowlanguage['IDEmployee'],
                        "IDLanguage" => $rowlanguage['IDLanguage'],
                        "Language" => $rowlanguage['Language'],
                        "Reading" => $rowlanguage['Reading'],
                        "Listening" => $rowlanguage['Listening'],
                        "Conversation" => $rowlanguage['Conversation'],
                        "Writing" => $rowlanguage['Writing'],
                        "DeletedBy" => $rowlanguage['DeletedBy'],
                        "DeletedIP" => $rowlanguage['DeletedIP'],
                        "DeletedDate" => $rowlanguage['DeletedDate'],
                        "DeleteFlag" => $rowlanguage['DeleteFlag'],
                        "HistBy" => $this->User,
                        "HistDate" => $this->Datetime,
                        "HistIP" => $this->Ip,
                        "IDTable" => $rowlanguage['ID'],
                        "FunctionOn" => $function
                    );


                    $this->history->insert_his_language($recordlanguage);
                }
            }
        } else if ($historyto == 'languagerow') {
            $rowlanguage = $this->history->get_languagerow($nip);
            $recordlanguagerow = array(
                "IDEmployee" => $rowlanguage->IDEmployee,
                "IDLanguage" => $rowlanguage->IDLanguage,
                "Language" => $rowlanguage->Language,
                "Reading" => $rowlanguage->Reading,
                "Listening" => $rowlanguage->Listening,
                "Conversation" => $rowlanguage->Conversation,
                "Writing" => $rowlanguage->Writing,
                "DeletedBy" => $rowlanguage->DeletedBy,
                "DeletedIP" => $rowlanguage->DeletedIP,
                "DeletedDate" => $rowlanguage->DeletedDate,
                "DeleteFlag" => $rowlanguage->DeleteFlag,
                "HistBy" => $this->User,
                "HistDate" => $this->Datetime,
                "HistIP" => $this->Ip,
                "IDTable" => $rowlanguage->ID,
                "FunctionOn" => $function
            );


            $this->history->insert_his_language($recordlanguagerow);
        } else if ($historyto == 'workexp') {
            $resultwork = $this->history->get_workexp($nip);
            $checkwork = ($resultwork == '' or $resultwork == NULl) ? 'empty' : 'exist';
            if ($checkwork == 'exist') {
                foreach ($resultwork as $rowwork) {
                    $recordwork = array(
                        "IDEmployee" => $rowwork['IDEmployee'],
                        "IDWorkExp" => $rowwork['IDWorkExp'],
                        "CompanyName" => $rowwork['CompanyName'],
                        "CompanyAddress" => $rowwork['CompanyAddress'],
                        "CompanyPhone" => $rowwork['CompanyPhone'],
                        "Position" => $rowwork['Position'],
                        "WorkDuration" => $rowwork['WorkDuration'],
                        "DeletedBy" => $rowwork['DeletedBy'],
                        "DeletedIP" => $rowwork['DeletedIP'],
                        "DeletedDate" => $rowwork['DeletedDate'],
                        "DeleteFlag" => $rowwork['DeleteFlag'],
                        "HistBy" => $this->User,
                        "HistDate" => $this->Datetime,
                        "HistIP" => $this->Ip,
                        "IDTable" => $rowwork['ID'],
                        "FunctionOn" => $function
                    );

                    $this->history->insert_his_workexp($recordwork);
                }
            }
        } else if ($historyto == 'workexprow') {
            $rowwork = $this->history->get_workexprow($nip);
            $recordwork = array(
                "IDEmployee" => $rowwork->IDEmployee,
                "IDWorkExp" => $rowwork->IDWorkExp,
                "CompanyName" => $rowwork->CompanyName,
                "CompanyAddress" => $rowwork->CompanyAddress,
                "CompanyPhone" => $rowwork->CompanyPhone,
                "Position" => $rowwork->Position,
                "WorkDuration" => $rowwork->WorkDuration,
                "DeletedBy" => $rowwork->DeletedBy,
                "DeletedIP" => $rowwork->DeletedIP,
                "DeletedDate" => $rowwork->DeletedDate,
                "DeleteFlag" => $rowwork->DeleteFlag,
                "HistBy" => $this->User,
                "HistDate" => $this->Datetime,
                "HistIP" => $this->Ip,
                "IDTable" => $rowwork->ID,
                "FunctionOn" => $function
            );

            $this->history->insert_his_workexp($recordwork);
        }
    }

    function counternip() {
        $key = $this->input->post('flag');
        $group = $this->input->post('jobgroup');
        $hiredate = date('dmy', strtotime($this->input->post('hiredate')));

        $row = $this->pbl->counternip();

        if ($key == 'add' and $group == 'ST' or $group == 'LT' or $group == 'LK') {
            $id = $row->IDEmployeeTIS + 1;
            $niptemp = "0000" . $id;
            $niptis = substr($niptemp, -4);
            $nip = $niptis . $hiredate;
        } else if ($key == 'add' and $group == 'OS' or $group == 'MAG') {
            $id = $row->IDEmployeeOS + 1;
            $niptemp = "0000000000" . $id;
            $nipos = substr($niptemp, -10);
            $nip = $nipos;
        } else if ($key == 'dup' and $group == 'ST' or $group == 'LT' or $group == 'LK') {

            $id = $row->IDEmployeeTIS + 1;
            $niptemp = "0000" . $id;
            $niptis = substr($niptemp, -4);
            $nip = $niptis . $hiredate;
        } else if ($key == 'dup' and $group == 'OS' or $group == 'MAG') {

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

//===================START OTHER DATA==================================
    function autocomplete_fakes() {
        $result = $this->pbl->get_fakes();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('fcode' => $row->FaskesCode,
                'fname' => strtoupper($row->FakesName)
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    function get_otherdata($nip) {
        $result = $this->pbl->get_otherdata($nip);
        echo json_encode($result);
    }

    function save_otherdata($nip) {
        $fakescode = $this->input->post('fakescode');
        $nppno = $this->input->post('nppno');
        $policeno = $this->input->post('policeno');
        $passportno = $this->input->post('passportno');
        $insurancename = $this->input->post('insurancename');

        $record = array(
            "IDEmployee" => $nip,
            "FakesCode" => $fakescode,
            "NPPNo" => $nppno,
            "PoliceNo" => $policeno,
            "PassportNo" => $passportno,
            "InsuranceName" => $insurancename
        );

        $this->pbl->checkfakes($nip, $record);
        $msg = array("status" => 'oke');
        echo json_encode($msg);
    }

//=========================END OTHER DATA===================================    
    //=========================START UPLOAD FOTO ====================================

    function get_urlimages($nip) {
        $foto = $this->picturedata->getfoto($nip);
        $json = '{  "datafoto":"' . $foto . '"' .
                '}';
        echo $json;
    }

    function uploaddata($nip) {
        $error = "";
        $msg = "";
        $config['upload_path'] = '../public/fotokaryawan';
        $config['allowed_types'] = 'gif|jpg|png|JPEG|jpeg|BMP|bmp|TIFF|tiff';
        $config['overwrite'] = TRUE;
        $config['file_name'] = $nip;
        $config['max_size'] = '10024';
        $this->load->library('upload', $config);
        $this->upload->display_errors('', '');

        if (!$this->upload->do_upload("fileToUpload")) {
            $error = $this->upload->display_errors();
        } else {
            $data = $this->upload->data();
            $path = 'public/fotokaryawan/' . $data['orig_name'];
            $record = array(
                'IDEmployee' => $nip,
                'UrlImage' => $path,
            );
            $msg = $path;
            $error = '';
            $this->picturedata->checkfoto($nip, $record);
        }

        $json = '{  "error":"' . $error . '",
						 "msg":"' . $msg . '"
					' .
                '}';
        echo $json;
    }

    //=========================END UPLOAD FOTO ====================================  
}

/* End of file main.php */
/* Location: ./application/controllers/welcome.php */

