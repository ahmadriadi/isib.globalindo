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
	$this->load->model("param_model", "parameter");
	$this->load->model("libraryfunction_model", "libfun");
	
        $this->load->model("address_model","addr");
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
        $data['default']['location'][1]['display'] = 'KAPUK';
        $data['default']['location'][2]['value'] = '2';
        $data['default']['location'][2]['display'] = 'BITUNG';
	      
        $idmenu                    = "124";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('mst01/main',$data);
    }
    
    
  
   function delete_employee($id){
        $row_employee = $this->pbl->get_by_id($id);
        $idemployee = $row_employee->IDEmployee;
        
        $this->pbl->delete_personal_h_emp($idemployee);
        $this->pbl->delete_personal_d_emp($idemployee);
        $this->pbl->delete_personal_pbl($idemployee);
        $this->pbl->delete_personal_edu_pbl($idemployee);
        $this->pbl->delete_personal_fam_pbl($idemployee);
        $this->pbl->delete_personal_lang_pbl($idemployee);
        $this->pbl->delete_personal_workexp_pbl($idemployee);
        $this->pbl->delete_personal_job_pbl($idemployee);    
        
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


    function get_departement(){
         $id     = $this->input->post('iddept');
         $rowdepartement = $this->pbl->get_departement($id)->row();
         
         $value = ($rowdepartement=='' or $rowdepartement==null)?$id:$rowdepartement->DescStructure;
                  
         $json = '{ 
                   "departemen":"' . $value . '",
                   "valid":"true"' .
                '}';
         echo $json;
         
        }
    

    
      function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '124';
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
        if ($flag == "dup"){
            $this->session->set_userdata('idemp_on_clipboard',$nip);
        }
    }

//    job  =========================================================================
    function get_job($nip) {
        $iduser = $nip;
        $job = $this->pbl->get_job($iduser)->row();
        echo json_encode($job);
    }

     function save_job($nip) {
        $iduser = $nip;
        $idparent = $this->input->post("idparent");
        $empstat = $this->input->post("empstat");
        $jobloc = $this->input->post("jobloc");
        $jobgrp = $this->input->post("jobgrp");
        $depart = $this->input->post("depart");
        $jobpos = $this->input->post("jobpos");
        $unitjob = $this->input->post("unitjob");
        $hiredate = ($this->input->post("hiredate") == '' or $this->input->post("hiredate") == null) ? null : date('Y-m-d', strtotime($this->input->post("hiredate")));
        $datefirst = ($this->input->post("datefirst") == '' or $this->input->post("datefirst") == null) ? null : date('Y-m-d', strtotime($this->input->post("datefirst")));
        $dateprob = ($this->input->post("dateprob") == '' or $this->input->post("dateprob") == null) ? null : date('Y-m-d', strtotime($this->input->post("dateprob")));
        $contnew = ($this->input->post("contnew") == '' or $this->input->post("contnew") == null) ? null : date('Y-m-d', strtotime($this->input->post("contnew")));
        $contend = ($this->input->post("contend") == '' or $this->input->post("contend") == null) ? null : date('Y-m-d', strtotime($this->input->post("contend")));
        $fullname = $this->input->post("fullname");
        $nickname = $this->input->post("nickname");
        $bankaccount = $this->input->post("nobank");
        $note = $this->input->post("note");
	$status = $this->input->post("status");
	$flag = $this->input->post("flag");

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
            "DatePassProbation" => $dateprob,
            "DateNewContract" => $contnew,
            "DateEndContract" => $contend
        );
        $cek = $this->pbl->get_job($iduser)->result();
        if ($cek == NULL) {
            $record['IDEmployee'] = $iduser;
            $this->pbl->insert_job($record);
        } else {
            $this->pbl->update_job($iduser, $record);
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
            "DateFirstJoint" => $datefirst,
            "DatePassProbation" => $dateprob,
            "DateNewContract" => $contnew,
            "DateEndContract" => $contend,
            "Note" => trim($note)
        );
        $cek2 = $this->pbl->get_det_emp($iduser)->result();
        if ($cek2 == NULL) {
            $record2['IDEmployee'] = $iduser;
            $this->pbl->insert_det_emp($record2);
        } else {
            $this->pbl->update_det_emp($iduser, $record2);
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
        $cek3 = $this->pbl->get_prs_emp($iduser)->result();
        if ($cek3 == NULL) {
            $rec['IDEmployee'] = $iduser;
            $this->pbl->insert_prs_emp($rec);
        } else {
            $this->pbl->update_prs_emp($iduser, $rec);
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
        if ($chekc_pbl == 'empty') {
            $this->pbl->insert_pbl_personal($recorpbl);
           
        } else {
            $this->pbl->update_pbl_personal($iduser, $recorpbl);
        }
        if ($cek == NULL and $cek2 == NULL and $chekc_pbl == 'empty'){
            if ($flag == "dup"){
                $fromid = $this->session->userdata('idemp_on_clipboard');
                $this->duplicate($fromid, $iduser );
            }
        }
        $msg = array("status" => "oke");
        echo json_encode($msg);
    }
//    personal =====================================================================
    function get_personal($nip) {
        $personal = $this->pbl->get_employee($nip)->row();
        echo json_encode($personal);
    }
    function duplicate($fromid,$toid){
        $this->session->set_userdata('duplicate_id',$toid);
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
            
            "NoBPJSEmp"                 =>$asal_d->NoBPJSEmp,
            "NoBPJSHlt"                 =>$asal_d->NoBPJSHlt,
            "NoFamCert"                 =>$asal_d->NoFamCert,

            "LiveProvince"              =>$asal_d->LiveProvince,
            "LiveCity"                  =>$asal_d->LiveCity,
            "LiveSubdistrict"           =>$asal_d->LiveSubdistrict,
            "LiveVillage"               =>$asal_d->LiveVillage,
            "LiveRW"                    =>$asal_d->LiveRW,
            "LiveRT"                    =>$asal_d->LiveRT,
            "KTPProvince"               =>$asal_d->KTPProvince,
            "KTPCity"                   =>$asal_d->KTPCity,
            "KTPSubdistrict"            =>$asal_d->KTPSubdistrict,
            "KTPVillage"                =>$asal_d->KTPVillage,
            "KTPRW"                     =>$asal_d->KTPRW,
            "KTPRT"                     =>$asal_d->KTPRT,
            
            "KTPPostalCode"             => $ktpkodepos->KTPPostalCode,
            "LivePostalCode"            => $livekodepos->LivePostalCode            
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
            
            "NoBPJSEmp"                 =>$asal_pbl->NoBPJSEmp,
            "NoBPJSHlt"                 =>$asal_pbl->NoBPJSHlt,
            "NoFamCert"                 =>$asal_pbl->NoFamCert,

            "LiveProvince"              =>$asal_pbl->LiveProvince,
            "LiveCity"                  =>$asal_pbl->LiveCity,
            "LiveSubdistrict"           =>$asal_pbl->LiveSubdistrict,
            "LiveVillage"               =>$asal_pbl->LiveVillage,
            "LiveRW"                    =>$asal_pbl->LiveRW,
            "LiveRT"                    =>$asal_pbl->LiveRT,
            "KTPProvince"               =>$asal_pbl->KTPProvince,
            "KTPCity"                   =>$asal_pbl->KTPCity,
            "KTPSubdistrict"            =>$asal_pbl->KTPSubdistrict,
            "KTPVillage"                =>$asal_pbl->KTPVillage,
            "KTPRW"                     =>$asal_pbl->KTPRW,
            "KTPRT"                     =>$asal_pbl->KTPRT,
            
            "KTPPostalCode"             => $ktpkodepos->KTPPostalCode,
            "LivePostalCode"            => $livekodepos->LivePostalCode
        );
        //duplicate family member
        $asal_pbl_fam = $this->pbl->get_family($fromid)->result();
        foreach ($asal_pbl_fam as $afam){
            $recordpblfam = array(
                "IDEmployee"    => $toid,
                "IDFamily"      => $afam->IDFamily,
                "NoKTP"         => $afam->NoKTP,
                "FamilyMember"  => $afam->FamilyMember,
                "Name"          => $afam->Name,
                "Age"           => $afam->Age,
                "Address"       => $afam->Address,
                "Education"     => $afam->Education,
                "Occupation"    => $afam->Occupation
            );
            $this->pbl->insert_family($recordpblfam);
        }
        //duplicate education background
        $asal_pbl_edu = $this->pbl->get_education($fromid)->result();
        foreach ($asal_pbl_edu as $aedu){
            $recordpbledu = array(
                "IDEmployee"    => $toid,
                "IDEducation"   => $aedu->IDEducation,
                "EducationLevel"=> $aedu->EducationLevel,
                "Course"        => $aedu->Course,
                "SchoolName"    => $aedu->SchoolName,
                "City"          => $aedu->City,
                "YearFrom"      => $aedu->YearFrom,
                "YearUntil"     => $aedu->YearUntil,
                "Certificate"   => $aedu->Certificate
            );
            $this->pbl->insert_education($recordpbledu);
        }
        //duplicate language
        $asal_pbl_lang = $this->pbl->get_language($fromid)->result();
        foreach ($asal_pbl_lang as $alang){
            $recordpbllang = array(
                "IDEmployee"    => $toid,
                "IDLanguage"    => $alang->IDLanguage,
                "Language"      => $alang->Language,
                "Reading"       => $alang->Reading,
                "Listening"     => $alang->Listening,
                "Conversation"  => $alang->Conversation,
                "Writing"       => $alang->Writing
            );
            $this->pbl->insert_language($recordpbllang);
        }
        //duplicate working experience
        $asal_pbl_wexp = $this->pbl->get_work($fromid)->result();
        foreach ($asal_pbl_wexp as $awexp){
            $recordpblwexp = array(
                "IDEmployee"    => $toid,
                "IDWorkExp"     => $awexp->IDWorkExp,
                "CompanyName"   => $awexp->CompanyName,
                "CompanyAddress"=> $awexp->CompanyAddress,
                "CompanyPhone"  => $awexp->CompanyPhone,
                "Position"      => $awexp->Position,
                "WorkDuration"  => $awexp->WorkDuration
            );
            $this->pbl->insert_work($recordpblwexp);
        }
        //duplicate training and course
        $asal_pbl_train = $this->pbl->get_tnc($fromid)->result();
        foreach ($asal_pbl_train as $atrain){
            $recordpbltrain = array(
                "IDEmployee"        => $toid,
                "IDCourse"          => $atrain->IDCourse,
                "CourseProgram"     => $atrain->CourseProgram,
                "CourseFacilitator" => $atrain->CourseFacilitator,
                "City"              => $atrain->City,
                "Duration"          => $atrain->Duration,
                "YearFrom"          => $atrain->YearFrom,
                "YearUntil"         => $atrain->YearUntil
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
        $this->pbl->update_header_personal_emp($toid, $recordh);
        $this->pbl->update_detail_personal_emp($toid, $recordd);
        $this->pbl->update_pbl_personal($toid, $recorpbl); 
	
        $resultenroll  = $this->pbl->get_enroll($fromid);
        $checkenroll = ($resultenroll=='' or $resultenroll==null)?'empty':'exist';
        if($checkenroll=='exist'){
            foreach ($resultenroll as $rowenroll) {
                
                $recordenrollaktif = array(
                    "IDCard"=>$rowenroll['IDCard'],
                    "IDEmployee"=>$toid,
                    "CardType"=>$rowenroll['CardType'],
                    "LastStatus"=>$rowenroll['LastStatus'],
                    "CardNumber"=>$rowenroll['CardNumber'],
                    "AddedBy"=>$this->User,
                    "AddedDate"=>$this->Datetime,
                    "AddedIP"=>$this->Ip                   
                  );
                
                $this->pbl->insert_enroll($recordenrollaktif);
                
                $recordenrollpasif = array(
                    "IDCard"=>$rowenroll['IDCard'],
                    "IDEmployee"=>$fromid,
                    "CardType"=>$rowenroll['CardType'],
                    "LastStatus"=>'F',
                    "CardNumber"=>$rowenroll['CardNumber'],
                    "EditedBy"=>$this->User,
                    "EditedDate"=>$this->Datetime,
                    "EditedIP"=>$this->Ip     
                );
               
                $this->pbl->update_enroll($fromid,$rowenroll['IDCard'],$recordenrollpasif); 
                                              
            }
            
        }
       
    }
    function cancel_dup(){
        $dup_id = $this->session->userdata('duplicate_id');
        $this->pbl->delete_personal_h_emp($dup_id);
        $this->pbl->delete_personal_d_emp($dup_id);
        $this->pbl->delete_personal_pbl($dup_id);
        $this->pbl->delete_personal_train_pbl($dup_id);
        $this->pbl->delete_personal_edu_pbl($dup_id);
        $this->pbl->delete_personal_fam_pbl($dup_id);
        $this->pbl->delete_personal_lang_pbl($dup_id);
        $this->pbl->delete_personal_workexp_pbl($dup_id);
        $this->pbl->delete_personal_job_pbl($dup_id);
	$this->pbl->delete_enroll($dup_id);
        $this->session->unset_userdata('duplicate_id');
        $this->session->unset_userdata('idemp_on_clipboard');
    } 
    function save_personal($nip) {
        $iduser = $nip;
        $fname = $this->input->post("fname");
        $nname = $this->input->post("nname");
        $pbirth = $this->input->post("pbirth");
        $dbirth = ($this->input->post("dbirth") == '' or $this->input->post("dbirth") == null) ? null : date('Y-m-d', strtotime($this->input->post("dbirth")));
        $bheight = $this->input->post("bheight");
        $bweight = $this->input->post("bweight");
        $gender = $this->input->post("gender");
        $tblood = $this->input->post("tblood");
        $czship = $this->input->post("czship");
        $religion = $this->input->post("religion");
        $noktp = $this->input->post("noktp");
        $nonpwp = $this->input->post("nonpwp");
        $nojamsos = $this->input->post("nojamsos");
        $nokpj = $this->input->post("nokpj");
        $abank = $this->input->post("abank");
        $marital = $this->input->post("marital"); //radio
        $coupname = $this->input->post("coupname");
        $couplektp = $this->input->post("couplektp");
        if ($coupname == '-' OR $coupname == ''){
            $couplektp = "N";
        }
        $nchild = $this->input->post("nchild");
        $nohp = $this->input->post("nohp");
        $inemail = $this->input->post("inemail");
        $exmail = $this->input->post("exmail");
        $laddress = $this->input->post("laddress");
        $laddressph = $this->input->post("laddressph");
        $ktpaddress = $this->input->post("ktpaddress");
        $ktpaddressph = $this->input->post("ktpaddressph");
        $famcert = $this->input->post("famcert");
        $marrcert = $this->input->post("marrcert");

        $nobpjsemp      = $this->input->post("nobpjsemp");
        $nobpjshlt      = $this->input->post("nobpjshlt");
        $famcertno     = $this->input->post("famcertno");

        $laddrprov      = $this->input->post("laddrprov");
        $laddrcity      = $this->input->post("laddrcity");
        $laddrsub       = $this->input->post("laddrsub");
        $laddrvlg       = $this->input->post("laddrvlg");
        $kaddrprov      = $this->input->post("kaddrprov");
        $kaddrcity      = $this->input->post("kaddrcity");
        $kaddrsub       = $this->input->post("kaddrsub");
        $kaddrvlg       = $this->input->post("kaddrvlg");        
        $liverw         = $this->input->post("liverw");        
        $livert         = $this->input->post("livert");        
        $ktprw          = $this->input->post("ktprw");        
        $ktprt          = $this->input->post("ktprt");        
//        +"&ktpkodepos="+ktpkodepos+"&livekodepos="+livekodepos
        $ktpkodepos     = $this->input->post("ktpkodepos");
        $livekodepos    = $this->input->post("livekodepos");
        
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
            "LiveAddressNoTelp" => "'".$laddressph,
            "KTPAddress" => $ktpaddress,
            "KTPAddressNoTelp" => "'".$ktpaddressph,
            
            "NoBPJSEmp"                 =>$nobpjsemp,
            "NoBPJSHlt"                 =>$nobpjshlt,
            "NoFamCert"                 =>$famcertno,

            "LiveProvince"              =>$laddrprov,
            "LiveCity"                  =>$laddrcity,
            "LiveSubdistrict"           =>$laddrsub,
            "LiveVillage"               =>$laddrvlg,
            "LiveRW"                    =>$liverw,
            "LiveRT"                    =>$livert,
            "KTPProvince"               =>$kaddrprov,
            "KTPCity"                   =>$kaddrcity,
            "KTPSubdistrict"            =>$kaddrsub,
            "KTPVillage"                =>$kaddrvlg,
            "KTPRW"                     =>$ktprw,
            "KTPRT"                     =>$ktprt,
            
            "KTPPostalCode"             => $ktpkodepos,
            "LivePostalCode"            => $livekodepos
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
            "LiveAddressNoTelp" => "'".$laddressph,
            "KTPAddress" => $ktpaddress,
            "KTPAddressNoTelp" => "'".$ktpaddressph,
                
            "NoBPJSEmp"                 =>$nobpjsemp,
            "NoBPJSHlt"                 =>$nobpjshlt,
            "NoFamCert"                 =>$famcertno,

            "LiveProvince"              =>$laddrprov,
            "LiveCity"                  =>$laddrcity,
            "LiveSubdistrict"           =>$laddrsub,
            "LiveVillage"               =>$laddrvlg,
            "LiveRW"                    =>$liverw,
            "LiveRT"                    =>$livert,
            "KTPProvince"               =>$kaddrprov,
            "KTPCity"                   =>$kaddrcity,
            "KTPSubdistrict"            =>$kaddrsub,
            "KTPVillage"                =>$kaddrvlg,
            "KTPRW"                     =>$ktprw,
            "KTPRT"                     =>$ktprt,
            
            "KTPPostalCode"             => $ktpkodepos,
            "LivePostalCode"            => $livekodepos                
        );

        if ($coupname == NULL OR $coupname == "" OR $coupname == "-"){
            $wh = array(
                "IDEmployee"    => $iduser,
                "FamilyMember"  => "spouse"
            );
            $this->pbl->delete_family($wh);
        }
        else{
            $wh = array(
                "IDEmployee"    => $iduser,
                "FamilyMember"  => "spouse"
            );
            $sps    = $this->pbl->get_family_member($wh);
            $lastid = $this->pbl->get_lastidfamily($iduser)->row()->lastid;
            if ($sps->num_rows() == 0){
                $rec=array(
                    "IDEmployee"    => $iduser,
                    "IDFamily"      => ($lastid*1)+1,
                    "FamilyMember"  => "spouse",
                    "Name"          => $coupname,
                    "NoKTP"         => $couplektp
                );
                $this->pbl->insert_family($rec);
            }
            if ($sps->num_rows() == 1){
                $wh = array(
                    "IDEmployee"    => $iduser,
                    "FamilyMember"  => "spouse"
                );
                $rec= array(
                    "Name"          => $coupname,
                    "NoKTP"         => $couplektp
                );
                $this->pbl->update_family($wh,$rec);
            }
        }        
        $this->pbl->update_header_personal_emp($iduser, $recordh);
        $this->pbl->update_detail_personal_emp($iduser, $recordd);
        $this->pbl->update_pbl_personal($iduser, $recorpbl);

        $this->upd_data($iduser);
        $msg = array("status" => "oke");
        echo json_encode($msg);
    }

    function upd_data($iduser) {
        $editip = $this->input->ip_address();
        $rec = array(
            "EditedIP" => $editip,
            "EditedBy" => $this->session->userdata('sess_userid'),
            "EditedDate" => date('Y-m-d')
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
        $fambplace  = $this->input->post("fambplace");
        $fambdate   = $this->input->post("fambdate");
        $fambdate   = date('Y-m-d',  strtotime($fambdate));
        
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
            "BirthPlace"    => $fambplace,
            "BirthDate"     => $fambdate            
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
        $idfamily = $this->input->post("famid");
        $where = array("IDEmployee" => $iduser, "IDFamily" => $idfamily);
        $this->pbl->delete_family($where);
        $msg = array("status" => "oke", "msg" => "Data Deleted!");

        echo json_encode($msg);
    }

    function pedit_family($nip) {
        $iduser = $nip;
        $member = $this->input->post("member");
        $idfamily = $this->input->post("famid");
        $name = $this->input->post("fname");
        $age = $this->input->post("fage");
        $address = $this->input->post("faddress");
        $education = $this->input->post("fedu");
        $occupation = $this->input->post("foccu");
        $noktp = $this->input->post("fnoktp");
        $fambplace  = $this->input->post("fambplace");
        $fambdate   = $this->input->post("fambdate");
        $fambdate   = date('Y-m-d',  strtotime($fambdate));
        
        $rec = array(
            "FamilyMember" => $member,
            "NoKTP" => $noktp,
            "Name" => $name,
            "Age" => $age,
            "Address" => $address,
            "Education" => $education,
            "Occupation" => $occupation,
            "BirthPlace"    => $fambplace,
            "BirthDate"     => $fambdate             
        );
        $where = array(
            "IDEmployee" => $iduser,
            "IDFamily" => $idfamily
        );
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
        $level = $this->input->post("level");
        $ename = $this->input->post("ename");
        $course = $this->input->post("course");
        $ecity = $this->input->post("ecity");
        $efrom = $this->input->post("efrom");
        $etill = $this->input->post("etill");
        $ecert = $this->input->post("ecert");
        $nextid = $this->input->post("nextid");
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
        $eduid = $this->input->post("eduid");
        $rec = $this->pbl->get_education($iduser, $eduid)->row();
        echo json_encode($rec);
    }

    function del_education($nip) {
        $iduser = $nip;
        $eduid = $this->input->post("eduid");
        $where = array("IDEmployee" => $iduser, "IDEducation" => $eduid);
        $this->pbl->delete_education($where);
        $msg = array("status" => "oke", "msg" => "Data Deleted!");

        echo json_encode($msg);
    }

    function pedit_education($nip) {
        $iduser = $nip;
        $level = $this->input->post("level");
        $ename = $this->input->post("ename");
        $course = $this->input->post("course");
        $ecity = $this->input->post("ecity");
        $efrom = $this->input->post("efrom");
        $etill = $this->input->post("etill");
        $ecert = $this->input->post("ecert");
        $eduid = $this->input->post("eduid");

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
            "IDEducation" => $eduid
        );
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
        $program = $this->input->post("program");
        $fac = $this->input->post("facilitator");
        $city = $this->input->post("city");
        $duration = $this->input->post("duration");
        $from = $this->input->post("from");
        $until = $this->input->post("until");
        $nextid = $this->input->post("nextid");
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
        $tncid = $this->input->post("tncid");
        $rec = $this->pbl->get_tnc($iduser, $tncid)->row();
        echo json_encode($rec);
    }

    function del_tnc($nip) {
        $iduser = $nip;
        $tncid = $this->input->post("tncid");
        $where = array("IDEmployee" => $iduser, "IDCourse" => $tncid);
        $this->pbl->delete_tnc($where);
        $msg = array("status" => "oke", "msg" => "Data Deleted!");

        echo json_encode($msg);
    }

    function pedit_tnc($nip) {
        $iduser = $nip;
        $program = $this->input->post("program");
        $fac = $this->input->post("facilitator");
        $city = $this->input->post("city");
        $duration = $this->input->post("duration");
        $from = $this->input->post("from");
        $until = $this->input->post("until");
        $idtnc = $this->input->post("tncid");

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
            "IDCourse" => $idtnc
        );
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
        $language = $this->input->post("language");
        $listen = $this->input->post("listen");
        $read = $this->input->post("read");
        $convers = $this->input->post("convers");
        $write = $this->input->post("write");

        $nextid = $this->input->post("nextid");
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
        $langid = $this->input->post("langid");
        $rec = $this->pbl->get_language($iduser, $langid)->row();
        echo json_encode($rec);
    }

    function del_language($nip) {
        $iduser = $nip;
        $langid = $this->input->post("langid");
        $where = array("IDEmployee" => $iduser, "IDLanguage" => $langid);
        $this->pbl->delete_language($where);
        $msg = array("status" => "oke", "msg" => "Data Deleted!");

        echo json_encode($msg);
    }

    function pedit_language($nip) {
        $iduser = $nip;
        $language = $this->input->post("language");
        $listen = $this->input->post("listen");
        $read = $this->input->post("read");
        $convers = $this->input->post("convers");
        $write = $this->input->post("write");

        $langid = $this->input->post("langid");
        $rec = array(
            "Language" => $language,
            "Listening" => $listen,
            "Reading" => $read,
            "Conversation" => $convers,
            "Writing" => $write
        );
        $where = array(
            "IDEmployee" => $iduser,
            "IDLanguage" => $langid
        );
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
        $comp = $this->input->post("comp");
        $address = $this->input->post("address");
        $phone = $this->input->post("phone");
        $pos = $this->input->post("pos");
        $dur = $this->input->post("dur");

        $nextid = $this->input->post("nextid");
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
        $workid = $this->input->post("workid");
        $rec = $this->pbl->get_work($iduser, $workid)->row();
        echo json_encode($rec);
    }

    function del_work($nip) {
        $iduser = $nip;
        $workid = $this->input->post("workid");
        $where = array("IDEmployee" => $iduser, "IDWorkExp" => $workid);
        $this->pbl->delete_work($where);
        $msg = array("status" => "oke", "msg" => "Data Deleted!");
        echo json_encode($msg);
    }

    function pedit_work($nip) {
        $iduser = $nip;

        $comp = $this->input->post("comp");
        $address = $this->input->post("address");
        $phone = $this->input->post("phone");
        $pos = $this->input->post("pos");
        $dur = $this->input->post("dur");

        $workid = $this->input->post("workid");
        $rec = array(
            "CompanyName" => $comp,
            "CompanyAddress" => $address,
            "CompanyPhone" => $phone,
            "Position" => $pos,
            "WorkDuration" => $dur
        );
        $where = array(
            "IDEmployee" => $iduser,
            "IDWorkExp" => $workid
        );
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
        $status = $this->input->post("statusemployee");
	$sendmail = $this->input->post("sendmail");	
        $hiredate = ($this->input->post("tglmasuk") == '' or $this->input->post("tglmasuk") == null) ? null : date('Y-m-d', strtotime($this->input->post("tglmasuk")));
        $resigndate = ($this->input->post("tglkeluar") == '' or $this->input->post("tglkeluar") == null) ? null : date('Y-m-d', strtotime($this->input->post("tglkeluar")));
        $reasonresgin = $this->input->post("reasonresgin");
        $record_emp_h = array(
            "Status" => $status,
            "HireDate" => $hiredate,
            "ResignDate" => $resigndate
        );

        $cekh = $this->pbl->get_parent_turnonoff($iduser)->result();
        if ($cekh !== NULL) {
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
        if ($checkdata !=='empty') {
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

    function email_hire($nip,$sendmail) {
        $row = $this->pbl->get_detail_turnonoff($nip);
        $checkdata = ($row == '' or $row == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {

            $flaghire = $row->FlagHire;
            $name = $row->FullName;
            $jb = $row->IDJobGroup;
            $gen = $row->Gender;
            $hiredate = date('d-m-Y', strtotime($row->HireDate));

	    $pst = $this->pbl->get_position($nip);            
            if($pst=='LAIN LAIN' or $pst=='LAIN-LAIN' ){
                $position ='';
            }else{
                $position =$pst;
            } 		

            $gender =$this->libfun->get_gender($gen);
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

            $subject = "New Employee Announcement";
            $message = $this->load->view('mst01/contenthire', $data, true);
            $recordflag = array("FlagHire" => '1');

            if ($flaghire =='0' and $jb == 'ST' or $jb == 'LT' or $jb == 'LK') {
              
		if ($sendmail == 'Y') {
                    $this->send_email_internal($subject, $message);
                }
                $this->pbl->update_prs_emp($nip, $recordflag);
                $this->pbl->update_det_emp($nip, $recordflag);
                $this->pbl->update_job($nip, $recordflag);
            }
        }
    }

    function email_resign($nip,$sendmail) {
        $row = $this->pbl->get_detail_turnonoff($nip);
        $checkdata = ($row == '' or $row == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            $flagresign = $row->FlagResign;
            $name = $row->FullName;
            $jb = $row->IDJobGroup;
            $gen = $row->Gender;
            $resigndate = date('d-m-Y', strtotime($row->ResignDate));

	    $pst = $this->pbl->get_position($nip);            
            if($pst=='LAIN LAIN' or $pst=='LAIN-LAIN' ){
                $position ='';
            }else{
                $position =$pst;
            } 	

            $gender =$this->libfun->get_gender($gen);
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


            $subject = "Information of Employee Resignation";
            $message = $this->load->view('mst01/contentresign', $data, true);
            $recordflag = array("FlagResign" => '1');


            if ($flagresign=='0' and $jb == 'ST' or $jb == 'LT' or $jb == 'LK') {
               
		if ($sendmail == 'Y') {
                    $this->send_email_internal($subject, $message);
                }
                $this->pbl->update_prs_emp($nip, $recordflag);
                $this->pbl->update_det_emp($nip, $recordflag);
                $this->pbl->update_job($nip, $recordflag);
            }
        }
    }

    function send_email_internal($subject = '', $message = '') {
        $email_config = Array(
            'protocol' => 'smtp',
            'smtp_host' => '192.168.0.11',
            'smtp_port' => '25',
            'smtp_user' => 'admintec',
            'smtp_pass' => '123',
            'mailtype' => 'html',
            'starttls' => true,
            'newline' => "\r\n",
            'crlf' => "\r\n"
        );
	

	//$cc =array('denny@triasindrasaputra.loc','riadi@triasindrasaputra.loc','okierie@triasindrasaputra.loc'); 
        $this->load->library('email');
        $this->email->initialize($email_config);
        //$this->email->clear(TRUE);
        $this->email->from('donotreply@triasindrasaputra.loc', 'SYSTEM');
        $this->email->to('alluser@triasindrasaputra.loc');
        $this->email->cc('doris@triasindrasaputra.loc');
        $this->email->subject($subject);
        $this->email->message($message);
        if (!$this->email->send()) {
            return FALSE;
        }
            return TRUE;
    }

//===========END PASSIVE OR ACTIVE======================    

function exportdata($param,$group,$loc,$ext = '.xlsx', $path_file = '/tmp/') {  
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
        $objSheet->getStyle('A1:AT1')->getFont()->setBold(true)->setSize(12);

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
        $objSheet->getCell('K1')->setValue('Unit Group');
        $objSheet->getCell('L1')->setValue('EmailInternal');
        $objSheet->getCell('M1')->setValue('Extension');       
        $objSheet->getCell('N1')->setValue('First Join Date');
        $objSheet->getCell('O1')->setValue('Pass Probation Date');
        $objSheet->getCell('P1')->setValue('New Contact Date');
        $objSheet->getCell('Q1')->setValue('End Contact Date');
        $objSheet->getCell('R1')->setValue('In Field Date');
        $objSheet->getCell('S1')->setValue('Birth Place');
        $objSheet->getCell('T1')->setValue('Birth Date');
        $objSheet->getCell('U1')->setValue('Gender');
        $objSheet->getCell('V1')->setValue('Religion');
        $objSheet->getCell('W1')->setValue('Education Level');
        $objSheet->getCell('X1')->setValue('Education Majors');
        $objSheet->getCell('Y1')->setValue('Marital Status');
        $objSheet->getCell('Z1')->setValue('Marriage Certificate');
        $objSheet->getCell('AA1')->setValue('Family Member Certificate');
        $objSheet->getCell('AB1')->setValue('NPWP');
        $objSheet->getCell('AC1')->setValue('KPJ');      
        $objSheet->getCell('AD1')->setValue('Citized Card(KTP)');
        $objSheet->getCell('AE1')->setValue('ID Married Couple');
        $objSheet->getCell('AF1')->setValue('Number of Children');
        $objSheet->getCell('AG1')->setValue('First Child');
        $objSheet->getCell('AH1')->setValue('Second Child');
        $objSheet->getCell('AI1')->setValue('Couple Name');
        $objSheet->getCell('AJ1')->setValue('Blood Type');
        $objSheet->getCell('AK1')->setValue('Telphone');
        $objSheet->getCell('AL1')->setValue('Handphone');             
        $objSheet->getCell('AM1')->setValue('Address (Current)');      
        $objSheet->getCell('AN1')->setValue('Address (KTP)');      
        $objSheet->getCell('AO1')->setValue('Work Experience');      
        $objSheet->getCell('AP1')->setValue('Resign Date');      
        $objSheet->getCell('AQ1')->setValue('Reason Explain Resignation');
        $objSheet->getCell('AR1')->setValue('Employee Status');
        $objSheet->getCell('AS1')->setValue('Status');
        $objSheet->getCell('AT1')->setValue('Note');
        
	 $pembatas = array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'startcolor' => array('rgb' => 'FCFC0C')
                        );
	
       $result = $this->pbl->getall_employee($param,$group,$loc);
        if ($result != NULL) {
            $array = $result->result_array();

            $i = 1;
            $point = 0;
            $laststatus = "";
            foreach ($array as $row) {
                $point++;
                if ($laststatus != $row['Status'] && $point > 1) {
                    $i++;      
		     $objSheet->getStyle('A' . $i . ':AT' . $i)->getFill()->applyFromArray($pembatas); 	             
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
                                    
                    
                }
                
                $i++;

               $laststatus = $row['Status'];               
               $NMJobGroup = $this->libfun->get_name_group($row['IDJobGroup']);
               $bdate = $this->libfun->check_value_date($row['BirthDate']);         
               $fdate = $this->libfun->check_value_date($row['DateFirstJoint']);         
               $passdate = $this->libfun->check_value_date($row['DatePassProbation']);         
               $newdate = $this->libfun->check_value_date($row['DateNewContract']);         
               $enddate = $this->libfun->check_value_date($row['DateEndContract']);         
               $fielddate = $this->libfun->check_value_date($row['DateInField']);   
               
               
               $rowdept = $this->pbl->get_departement($row['IDDepartement'])->row();
               $dept = ($rowdept=='' or $rowdept ==null)?$row['IDDepartement']:$rowdept->DescStructure;
               
               $location = ($row['IDLocation']=='1')?"KAPUK":"BITUNG";               
               $jk = $this->libfun->get_gender($row['Gender']);
                             
                  
                $objSheet->getCell('A' . $i)->setValue($point);
                $objSheet->getCell('B' . $i)->setValue("'".$row['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($row['FullName']);
                $objSheet->getCell('D' . $i)->setValue($row['NickName']);
                $objSheet->getCell('E' . $i)->setValue($row['HireDate']);
                $objSheet->getCell('F' . $i)->setValue($row['BankAccount']);
               // $objSheet->getCell('G' . $i)->setValue("'".$row['IDEmployeeParent']);
                $objSheet->getCell('G' . $i)->setValue($dept);
                $objSheet->getCell('H' . $i)->setValue($location);
                $objSheet->getCell('I' . $i)->setValue($NMJobGroup);
                $objSheet->getCell('J' . $i)->setValue($row['IDJobPosition']);
                $objSheet->getCell('K' . $i)->setValue($row['IDUnitGroup']);
                $objSheet->getCell('L' . $i)->setValue($row['EmailInternal']);               
                $objSheet->getCell('M' . $i)->setValue("'".$row['Extension']);              
                $objSheet->getCell('N' . $i)->setValue($fdate);
                $objSheet->getCell('O' . $i)->setValue($passdate);
                $objSheet->getCell('P' . $i)->setValue($newdate);
                $objSheet->getCell('Q' . $i)->setValue($enddate);
                $objSheet->getCell('R' . $i)->setValue($fielddate);
                $objSheet->getCell('S' . $i)->setValue($row['BirthPlace']);
                $objSheet->getCell('T' . $i)->setValue($bdate);
                $objSheet->getCell('U' . $i)->setValue($jk);
                $objSheet->getCell('V' . $i)->setValue($row['Religion']);
                $objSheet->getCell('W' . $i)->setValue($row['IDEducation']);
                $objSheet->getCell('X' . $i)->setValue($row['IDMajors']);
                $objSheet->getCell('Y' . $i)->setValue($row['MaritalStatus']);                   
                $objSheet->getCell('Z' . $i)->setValue($row['MarriageCertificate']);
                $objSheet->getCell('AA' . $i)->setValue($row['FamilyMemberCertificate']);
                $objSheet->getCell('AB' . $i)->setValue("'".$row['NoNPWP']);
                $objSheet->getCell('AC' . $i)->setValue("'".$row['NoKPJ']);
                $objSheet->getCell('AD' . $i)->setValue("'".$row['NoKTP']);
                $objSheet->getCell('AE' . $i)->setValue($row['CoupleKTP']);
                $objSheet->getCell('AF' . $i)->setValue($row['NumberChildren']);
                $objSheet->getCell('AG' . $i)->setValue($row['FirstChild']);
                $objSheet->getCell('AH' . $i)->setValue($row['SecondChild']);
                $objSheet->getCell('AI' . $i)->setValue($row['CoupleName']);
                $objSheet->getCell('AJ' . $i)->setValue($row['BloodType']);
                $objSheet->getCell('AK' . $i)->setValue("'".$row['NoTelp']);
                $objSheet->getCell('AL' . $i)->setValue("'".$row['NoHp']);              
                $objSheet->getCell('AM' . $i)->setValue($row['LiveAddress']);
                $objSheet->getCell('AN' . $i)->setValue($row['KTPAddress']);
                $objSheet->getCell('AO' . $i)->setValue($row['WorkExperience']);
                $objSheet->getCell('AP' . $i)->setValue($row['ResignDate']);
                $objSheet->getCell('AQ' . $i)->setValue($row['ReasonResign']);
                $objSheet->getCell('AR' . $i)->setValue($row['EmployeeStatus']);
                $objSheet->getCell('AS' . $i)->setValue($row['Status']);
                $objSheet->getCell('AT' . $i)->setValue($row['Note']);
               
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:AT' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:AT' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:AT1')->getBorders()->
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
        

            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }
	    ob_end_clean();
            $objWriter->save($path_file . "masteremployee_".$param. $ext);
            $data = file_get_contents($path_file . "masteremployee_".$param. $ext);
            force_download("masteremployee_".$param. $ext, $data);
        }
    }

    function createnip(){
        $hire = $this->input->post('hiredate');
        $flag = $this->input->post('flag');
        
        $lastnip = $this->pbl->lastnip();
        $temp = "0000" .$lastnip;
        $newnip = substr($temp, -4);        
        $hiredate = date('dmy', strtotime($hire));
        
        if($flag=='add'){            
            $nip = $newnip.$hiredate;  
        }else if($flag=='dup'){            
            $nip = substr($this->session->userdata('idemp_on_clipboard'),0,4).$hiredate;
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
        $worksheet->getStyle('A1:AH15')->getFont()->setBold(true)->setSize(12);


//add header      
        $worksheet->getCell('A9')->setValue('PT. TRIAS INDRA SAPUTRA');
        $worksheet->getCell('A10')->setValue('LAPORAN DATA BPJS');


//add sub header
        //$worksheet->getCell('A14')->setValue('No.');
        $worksheet->getCell('B14')->setValue('No Kartu Keluarga');
        $worksheet->getCell('C14')->setValue('No KTP');
        $worksheet->getCell('D14')->setValue('Nama Lengkap');
        $worksheet->getCell('E14')->setValue('HUBKEL');
        $worksheet->getCell('E15')->setValue('1=P, 2=S, 3=I, 4=A, 5=T');
        $worksheet->getCell('F14')->setValue('Tgl Lahir');
        $worksheet->getCell('F15')->setValue('Tempat Lahir');
        $worksheet->getCell('G15')->setValue('dd/mm/yyyy');
        $worksheet->getCell('H14')->setValue('Jenis Ke1amin');
        $worksheet->getCell('H15')->setValue('1=laki-laki, Perempuan=2');
        $worksheet->getCell('I14')->setValue('Status kawin');
        $worksheet->getCell('I15')->setValue('1=BK, 2=K, 3=CH, 4=CM, 5= TDK TAU');
        $worksheet->getCell('J14')->setValue('Alamat Tempat Tinggal');
        $worksheet->getCell('K14')->setValue('RT');
        $worksheet->getCell('L14')->setValue('RW');
        $worksheet->getCell('M14')->setValue('Kode Pos');
        $worksheet->getCell('N14')->setValue('Kode Kecamatan ');
        $worksheet->getCell('O14')->setValue('Nama Kecamatan');
        $worksheet->getCell('P14')->setValue('Kode Desa');
        $worksheet->getCell('Q14')->setValue('Nama Desa');
        $worksheet->getCell('R14')->setValue('Kode Faskes Tk.I ');
        $worksheet->getCell('S14')->setValue('Nama Faskes Tk.I');
        $worksheet->getCell('T14')->setValue('Kode Faskes Dokter Gigi');
        $worksheet->getCell('U14')->setValue('Nama Faskes Dokter Gigi');
        $worksheet->getCell('V14')->setValue('Nomor Telepon Peserta');
        $worksheet->getCell('W14')->setValue('Email');
        $worksheet->getCell('X14')->setValue('NPP');
        $worksheet->getCell('Y14')->setValue('Jabatan');
        $worksheet->getCell('Z14')->setValue('Status');
        $worksheet->getCell('Z15')->setValue('1=Tetap, 2=Kontrak, 3=Paruh waktu, 4=Penerima Pensiun');
        $worksheet->getCell('AA14')->setValue('Kelas Rawat');
        $worksheet->getCell('AA15')->setValue('1=Kelas I, 2=Kelas II, 3=Kelas III');
        $worksheet->getCell('AB14')->setValue('TMT Kerja (Kary. Aktif)');
        $worksheet->getCell('AC14')->setValue('Gaji Pokok + Tunj. Tetap (Kary. Aktif)');
        $worksheet->getCell('AD14')->setValue('Kewarga Negaraan');
        $worksheet->getCell('AD15')->setValue('1=WNI, 2=WNA');
        $worksheet->getCell('AE14')->setValue('Asuransi Lainnya');
        $worksheet->getCell('AE15')->setValue('No. Polis');
        $worksheet->getCell('AF15')->setValue('Nama Asuransi');
        $worksheet->getCell('AG14')->setValue('No. NPWP');
        $worksheet->getCell('AH14')->setValue('No Passport');

// add mergecell 
        $sheet = $excel->getActiveSheet();
        $sheet->mergeCells('F14:G14');
        $sheet->mergeCells('AE14:AF14');

//add center   
        $sheet->getStyle('F14:G14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AE14:AF14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        // $sheet->getStyle('A14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('M14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('O14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('P14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Q14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('R14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('S14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('T14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('U14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('V14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('W14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('X14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Y14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Z14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Z15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AA14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AA15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AB14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AC15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AD14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AD15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AE14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AE15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AF14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AG14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AH14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



//add border
        $worksheet->getStyle('B14:AH15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        // $worksheet->getStyle('A14:A15')->getBorders()->
        //         getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('B14:B15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('C14:C15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('D14:D15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('F14:G15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('H14:H15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('I14:I15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('J14:J15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('K14:K15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('L14:L15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('M14:M15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('N14:N15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('O14:O15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('P14:P15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Q14:Q15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('R14:R15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('S14:S15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('T14:T15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('U14:U15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('V14:V15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('W14:W15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('X14:X15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Y14:Y15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Z14:Z15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AA14:AA15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AB14:AB15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AC14:AC15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AD14:AD15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AE14:AE15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AF14:AF15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AG14:AG15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AH14:AH15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $result = $this->pbl->getall_employee_public('A', 'AL', 'AL')->result_array();


        if ($result != NULL) {
            $i = 15;
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

                $addressktp = ($row["KTPAddress"] == NULL or $row['KTPAddress'] == "") ? "-" : "'" . $row["KTPAddress"];
                $telphp = ($row["NoHp"] == NULL or $row['NoHp'] == "") ? "-" : "'" . $row["NoHp"];
                $telprmh = ($row["KTPAddressNoTelp"] == NULL or $row['KTPAddressNoTelp'] == "") ? " " : "'" . $row["KTPAddressNoTelp"] . ',';
                $email = ($row["ExternalEmail"] == NULL or $row['ExternalEmail'] == "") ? " " : $row["ExternalEmail"];
                $npwp = ($row["NoNPWP"] == NULL or $row['NoNPWP'] == "") ? " " : "'" . $row["NoNPWP"];

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

                if (($row['IDEmployee'] != $id) OR ($no == 1)) {
                    // $worksheet->getCell('A' . $i)->setValue($no);
                    $worksheet->getCell('B' . $i)->setValue("-");
                    $worksheet->getCell('C' . $i)->setValue("'" . $ktp);
                    $worksheet->getCell('D' . $i)->setValue($name);
                    $worksheet->getCell('E' . $i)->setValue($p);
                    $worksheet->getCell('F' . $i)->setValue($tempatlahir);
                    $worksheet->getCell('G' . $i)->setValue($tgllahir);
                    $worksheet->getCell('H' . $i)->setValue($gender);
                    $worksheet->getCell('I' . $i)->setValue($statuskawin);
                    $worksheet->getCell('J' . $i)->setValue($addressktp);
                    $worksheet->getCell('K' . $i)->setValue('-');
                    $worksheet->getCell('L' . $i)->setValue('-');
                    $worksheet->getCell('M' . $i)->setValue('-');
                    $worksheet->getCell('N' . $i)->setValue('-');
                    $worksheet->getCell('O' . $i)->setValue('-');
                    $worksheet->getCell('P' . $i)->setValue('-');
                    $worksheet->getCell('Q' . $i)->setValue('-');
                    $worksheet->getCell('R' . $i)->setValue('-');
                    $worksheet->getCell('S' . $i)->setValue('-');
                    $worksheet->getCell('T' . $i)->setValue('-');
                    $worksheet->getCell('U' . $i)->setValue('-');
                    $worksheet->getCell('V' . $i)->setValue($telprmh . $telphp);
                    $worksheet->getCell('W' . $i)->setValue($email);
                    $worksheet->getCell('X' . $i)->setValue("-");
                    $worksheet->getCell('Y' . $i)->setValue($row['IDJobPosition']);
                    $worksheet->getCell('Z' . $i)->setValue($statuskerja);
                    $worksheet->getCell('AA' . $i)->setValue('-');
                    $worksheet->getCell('AB' . $i)->setValue($hd);
                    $worksheet->getCell('AC' . $i)->setValue('-');
                    $worksheet->getCell('AD' . $i)->setValue('1');
                    $worksheet->getCell('AE' . $i)->setValue('-');
                    $worksheet->getCell('AF' . $i)->setValue('-');
                    $worksheet->getCell('AG' . $i)->setValue($npwp);
                    $worksheet->getCell('AH' . $i)->setValue('-');
                }


                if ($lastnip != $row['IDEmployee'] && $tanggungan !== '') {
                    $i++;


                    $rowspouse = $this->pbl->spouse($row['IDEmployee']);

                    $worksheet->getCell('B' . $i)->setValue("-");
                    $worksheet->getCell('C' . $i)->setValue($rowspouse->NoKTP);
                    $worksheet->getCell('D' . $i)->setValue($rowspouse->Name);
                    $worksheet->getCell('E' . $i)->setValue($tanggungan);
                    $worksheet->getCell('F' . $i)->setValue('-');
                    $worksheet->getCell('G' . $i)->setValue('-');
                    $worksheet->getCell('H' . $i)->setValue($genderp);
                    $worksheet->getCell('I' . $i)->setValue($statuskawin);
                    $worksheet->getCell('J' . $i)->setValue($rowspouse->Address);
                    $worksheet->getCell('K' . $i)->setValue('-');
                    $worksheet->getCell('L' . $i)->setValue('-');
                    $worksheet->getCell('M' . $i)->setValue('-');
                    $worksheet->getCell('N' . $i)->setValue('-');
                    $worksheet->getCell('O' . $i)->setValue('-');
                    $worksheet->getCell('P' . $i)->setValue('-');
                    $worksheet->getCell('Q' . $i)->setValue('-');
                    $worksheet->getCell('R' . $i)->setValue('-');
                    $worksheet->getCell('S' . $i)->setValue('-');
                    $worksheet->getCell('T' . $i)->setValue('-');
                    $worksheet->getCell('U' . $i)->setValue('-');
                    $worksheet->getCell('V' . $i)->setValue($telprmh . $telphp);
                    $worksheet->getCell('W' . $i)->setValue($email);
                    $worksheet->getCell('X' . $i)->setValue("-");
                    $worksheet->getCell('Y' . $i)->setValue('-');
                    $worksheet->getCell('Z' . $i)->setValue('-');
                    $worksheet->getCell('AA' . $i)->setValue('-');
                    $worksheet->getCell('AB' . $i)->setValue('-');
                    $worksheet->getCell('AC' . $i)->setValue('-');
                    $worksheet->getCell('AD' . $i)->setValue('1');
                    $worksheet->getCell('AE' . $i)->setValue('-');
                    $worksheet->getCell('AF' . $i)->setValue('-');
                    $worksheet->getCell('AG' . $i)->setValue('-');
                    $worksheet->getCell('AH' . $i)->setValue('-');


                    if ($lastnip2 != $row['IDEmployee'] && $anak !== '0') {

                        $resultchild = $this->pbl->children($row['IDEmployee']);

                        if ($resultchild) {
                            foreach ($resultchild as $rowchild) {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("'" . $rowchild['NoKTP']);
                                $worksheet->getCell('D' . $i)->setValue($rowchild['Name']);
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue('-');
                                $worksheet->getCell('G' . $i)->setValue('-');
                                $worksheet->getCell('H' . $i)->setValue('-');
                                $worksheet->getCell('I' . $i)->setValue('-');
                                $worksheet->getCell('J' . $i)->setValue($rowchild['Address']);
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
                                $worksheet->getCell('T' . $i)->setValue('-');
                                $worksheet->getCell('U' . $i)->setValue('-');
                                $worksheet->getCell('V' . $i)->setValue($telprmh . $telphp);
                                $worksheet->getCell('W' . $i)->setValue($email);
                                $worksheet->getCell('X' . $i)->setValue("-");
                                $worksheet->getCell('Y' . $i)->setValue('-');
                                $worksheet->getCell('Z' . $i)->setValue('-');
                                $worksheet->getCell('AA' . $i)->setValue('-');
                                $worksheet->getCell('AB' . $i)->setValue('-');
                                $worksheet->getCell('AC' . $i)->setValue('-');
                                $worksheet->getCell('AD' . $i)->setValue('1');
                                $worksheet->getCell('AE' . $i)->setValue('-');
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue('-');
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            }
                        } else {

                            if ($a == '1') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            } else if ($a == '2') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            } else if ($a == '3') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            } else if ($a == '4') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');


                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            } else if ($a == '5') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');


                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');


                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            } else if ($a == '6') {
                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');


                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');

                                $i++;
                                $worksheet->getCell('B' . $i)->setValue("-");
                                $worksheet->getCell('C' . $i)->setValue("-");
                                $worksheet->getCell('D' . $i)->setValue("-");
                                $worksheet->getCell('E' . $i)->setValue($anak);
                                $worksheet->getCell('F' . $i)->setValue("-");
                                $worksheet->getCell('G' . $i)->setValue("-");
                                $worksheet->getCell('H' . $i)->setValue("-");
                                $worksheet->getCell('I' . $i)->setValue("-");
                                $worksheet->getCell('J' . $i)->setValue("-");
                                $worksheet->getCell('K' . $i)->setValue('-');
                                $worksheet->getCell('L' . $i)->setValue('-');
                                $worksheet->getCell('M' . $i)->setValue('-');
                                $worksheet->getCell('N' . $i)->setValue('-');
                                $worksheet->getCell('O' . $i)->setValue('-');
                                $worksheet->getCell('P' . $i)->setValue('-');
                                $worksheet->getCell('Q' . $i)->setValue('-');
                                $worksheet->getCell('R' . $i)->setValue('-');
                                $worksheet->getCell('S' . $i)->setValue('-');
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
                                $worksheet->getCell('AF' . $i)->setValue('-');
                                $worksheet->getCell('AG' . $i)->setValue("-");
                                $worksheet->getCell('AH' . $i)->setValue('-');
                            }
                        }
                    }
                }













                // $worksheet->getCell('A' . $i)->setValue($no);
                /*
                  $worksheet->getCell('B' . $i)->setValue("-");
                  $worksheet->getCell('C' . $i)->setValue($ktp);
                  $worksheet->getCell('D' . $i)->setValue($name);
                  $worksheet->getCell('E' . $i)->setValue($p.$tanggungan.$anak);
                  $worksheet->getCell('F' . $i)->setValue($tempatlahir);
                  $worksheet->getCell('G' . $i)->setValue($tgllahir);
                  $worksheet->getCell('H' . $i)->setValue($gender);
                  $worksheet->getCell('I' . $i)->setValue($statuskawin);
                  $worksheet->getCell('J' . $i)->setValue($addressktp);
                  $worksheet->getCell('K' . $i)->setValue('-');
                  $worksheet->getCell('L' . $i)->setValue('-');
                  $worksheet->getCell('M' . $i)->setValue('-');
                  $worksheet->getCell('N' . $i)->setValue('-');
                  $worksheet->getCell('O' . $i)->setValue('-');
                  $worksheet->getCell('P' . $i)->setValue('-');
                  $worksheet->getCell('Q' . $i)->setValue('-');
                  $worksheet->getCell('R' . $i)->setValue('-');
                  $worksheet->getCell('S' . $i)->setValue('-');
                  $worksheet->getCell('T' . $i)->setValue('-');
                  $worksheet->getCell('U' . $i)->setValue('-');
                  $worksheet->getCell('V' . $i)->setValue($telprmh.$telphp);
                  $worksheet->getCell('W' . $i)->setValue($email);
                  $worksheet->getCell('X' . $i)->setValue("-");
                  $worksheet->getCell('Y' . $i)->setValue($row['IDJobPosition']);
                  $worksheet->getCell('Z' . $i)->setValue($statuskerja);
                  $worksheet->getCell('AA' . $i)->setValue('-');
                  $worksheet->getCell('AB' . $i)->setValue($hd);
                  $worksheet->getCell('AC' . $i)->setValue('-');
                  $worksheet->getCell('AD' . $i)->setValue('1');
                  $worksheet->getCell('AE' . $i)->setValue('-');
                  $worksheet->getCell('AF' . $i)->setValue('-');
                  $worksheet->getCell('AG' . $i)->setValue($npwp);
                  $worksheet->getCell('AH' . $i)->setValue('-');
                 */
            }
        }

        /// body border 
        $worksheet->getStyle('A16:AH' . $i)->getBorders()->getAllBorders()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A16:AH' . $i)->getBorders()->getOutline()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A16:AH' . $i)->getBorders()->getBottom()->
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





/*

 function excel_bpjs(){
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
        $worksheet->getStyle('A1:AH15')->getFont()->setBold(true)->setSize(12);


//add header      
        $worksheet->getCell('A9')->setValue('PT. TRIAS INDRA SAPUTRA');
        $worksheet->getCell('A10')->setValue('LAPORAN DATA BPJS');


//add sub header
        $worksheet->getCell('A14')->setValue('No.');
        $worksheet->getCell('B14')->setValue('No Kartu Keluarga');
        $worksheet->getCell('C14')->setValue('No KTP');
        $worksheet->getCell('D14')->setValue('Nama Lengkap');
        $worksheet->getCell('E14')->setValue('HUBKEL');   
        $worksheet->getCell('E15')->setValue('1=P, 2=S, 3=I, 4=A, 5=T');
        $worksheet->getCell('F14')->setValue('Tgl Lahir');
        $worksheet->getCell('F15')->setValue('Tempat Lahir');
        $worksheet->getCell('G15')->setValue('dd/mm/yyyy');
        $worksheet->getCell('H14')->setValue('Jenis Ke1amin');
        $worksheet->getCell('H15')->setValue('1=laki-laki, Perempuan=2');
        $worksheet->getCell('I14')->setValue('Status kawin');
        $worksheet->getCell('I15')->setValue('1=BK, 2=K, 3=CH, 4=CM, 5= TDK TAU');
        $worksheet->getCell('J14')->setValue('Alamat Tempat Tinggal');
        $worksheet->getCell('K14')->setValue('RT');
        $worksheet->getCell('L14')->setValue('RW');
        $worksheet->getCell('M14')->setValue('Kode Pos');
        $worksheet->getCell('N14')->setValue('Kode Kecamatan ');       
        $worksheet->getCell('O14')->setValue('Nama Kecamatan');
        $worksheet->getCell('P14')->setValue('Kode Desa');
        $worksheet->getCell('Q14')->setValue('Nama Desa');
        $worksheet->getCell('R14')->setValue('Kode Faskes Tk.I ');
        $worksheet->getCell('S14')->setValue('Nama Faskes Tk.I');      
        $worksheet->getCell('T14')->setValue('Kode Faskes Dokter Gigi');
        $worksheet->getCell('U14')->setValue('Nama Faskes Dokter Gigi');
        $worksheet->getCell('V14')->setValue('Nomor Telepon Peserta');
        $worksheet->getCell('W14')->setValue('Email');
        $worksheet->getCell('X14')->setValue('NPP');
        $worksheet->getCell('Y14')->setValue('Jabatan');
        $worksheet->getCell('Z14')->setValue('Status');
        $worksheet->getCell('Z15')->setValue('1=Tetap, 2=Kontrak, 3=Paruh waktu, 4=Penerima Pensiun');
        $worksheet->getCell('AA14')->setValue('Kelas Rawat');
        $worksheet->getCell('AA15')->setValue('1=Kelas I, 2=Kelas II, 3=Kelas III');
        $worksheet->getCell('AB14')->setValue('TMT Kerja (Kary. Aktif)');
        $worksheet->getCell('AC14')->setValue('Gaji Pokok + Tunj. Tetap (Kary. Aktif)');
        $worksheet->getCell('AD14')->setValue('Kewarga Negaraan');
        $worksheet->getCell('AD15')->setValue('1=WNI, 2=WNA');
        $worksheet->getCell('AE14')->setValue('Asuransi Lainnya');
        $worksheet->getCell('AE15')->setValue('No. Polis');
        $worksheet->getCell('AF15')->setValue('Nama Asuransi');
        $worksheet->getCell('AG14')->setValue('No. NPWP');
        $worksheet->getCell('AH14')->setValue('No Passport');

// add mergecell 
        $sheet = $excel->getActiveSheet();
        $sheet->mergeCells('F14:G14');
        $sheet->mergeCells('AE14:AF14');

//add center   
        $sheet->getStyle('F14:G14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AE14:AF14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
        $sheet->getStyle('F14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('M14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('O14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('P14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Q14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('R14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('S14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('T14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('U14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('V14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('W14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('X14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Y14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Z14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Z15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AA14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AA15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AB14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AC15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AD14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AD15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AE14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AE15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AF14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AG14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AH14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);



//add border
        $worksheet->getStyle('A14:AH15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A14:A15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('B14:B15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('C14:C15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('D14:D15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('F14:G15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('H14:H15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('I14:I15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('J14:J15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('K14:K15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('L14:L15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('M14:M15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('N14:N15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('O14:O15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('P14:P15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Q14:Q15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('R14:R15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('S14:S15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('T14:T15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('U14:U15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('V14:V15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('W14:W15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('X14:X15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Y14:Y15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('Z14:Z15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AA14:AA15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AB14:AB15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AC14:AC15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AD14:AD15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AE14:AE15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AF14:AF15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AG14:AG15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('AH14:AH15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $result =  $this->pbl->getall_employee_public('A','AL','AL')->result_array();
        
        
        if ($result != NULL) {
            $i = 15;
            $no =0;
            foreach ($result as $row) {
                $i++;
                $no++;
                
                $jk = $this->libfun->get_gender($row['Gender']);
                    if($jk=='MALE'){
                        $gender = '1';
                   }else{
                       $gender = '2';
                 }
                   
                 "'".$ktp = ($row["NoKTP"] ==NULL or $row['NoKTP']=="") ? "-" : $row["NoKTP"];
                 $name = ($row["FullName"] ==NULL or $row['FullName']=="") ? "-" : $row["FullName"];
                 $p = ($row["IDEmployee"] ==NULL or $row['IDEmployee']=="") ? "5" :"1";
                 $a = $row["NumberChildren"];
                 
                 if($row["MaritalStatus"]=='MARRIED'){                     
                     $suamiistri='exist';
                 }else{
                     $suamiistri='empty';                     
                 }
                 
                 if($a=='0'){
                     $anak ='';
                 }else if($a=='1'){
                     $anak =',4';
                 }else if($a=='2'){
                     $anak =',4,4';
                 }else if($a=='3'){
                     $anak =',4,4,4';                 
                 }else if($a=='4'){
                     $anak =',4,4,4,4';                 
                 }else if($a=='5'){
                     $anak =',4,4,4,4,4';                 
                 }else if($a=='6'){
                     $anak =',4,4,4,4,4,4';
                 }
                 
                 if($suamiistri=='exist'){
                     if($gender=='1'){
                         $menanggung = ',3';
                     }else if($gender=='2'){
                         $menanggung = ',2';
                     }
                     
                     $tanggungan = $menanggung;
                     
                 }else{
                     $tanggungan ='';
                     
                 }
                                  
                 
                 $tgllahir =($row["BirthDate"] ==NULL or $row['BirthDate']=="") ? "-" : date('d/m/Y',strtotime($row["BirthDate"]));
                 $tempatlahir =($row["BirthPlace"] ==NULL or $row['BirthPlace']=="") ? "-" : $row["BirthPlace"];
                 
                
                
                
                
                
                
                if($row["MaritalStatus"]=='SINGLE'){
                    $statuskawin = '1';
                }else if($row["MaritalStatus"]=='MARRIED'){
                    $statuskawin = '2';
                }else if($row["MaritalStatus"]=='DIVORCED'){
                    $statuskawin = '3';
                }else{
                    $statuskawin = '5';
                }
                
               $addressktp = ($row["KTPAddress"] ==NULL or $row['KTPAddress']=="") ? "-" : "'".$row["KTPAddress"];
               $telphp       = ($row["NoHp"] ==NULL or $row['NoHp']=="") ? "-" : "'".$row["NoHp"];
               $telprmh       = ($row["KTPAddressNoTelp"] ==NULL or $row['KTPAddressNoTelp']=="") ? " " : "'".$row["KTPAddressNoTelp"].',';
               $email       = ($row["ExternalEmail"] ==NULL or $row['ExternalEmail']=="") ? " " : $row["ExternalEmail"];
               $npwp       = ($row["NoNPWP"] ==NULL or $row['NoNPWP']=="") ? " " : "'".$row["NoNPWP"];
                 
               $group = $row['IDJobGroup'];
               
               if($group=='LT'){                   
                   $statuskerja = '1';
                   $hire = date('d/m/Y',strtotime($row['HireDate'])); 
               }else if($group=='LK'){
                   $statuskerja = '2';
                   $hire = date('d/m/Y',strtotime($row['DateNewContract'])); 
               }else if($group=='ST'){
                   $statuskerja = '1';                   
                   $hire = date('d/m/Y',strtotime($row['HireDate']));                  
               }
               
                $hd = ($hire=='01/01/1970')?' - ':$hire;
               
                $worksheet->getCell('A' . $i)->setValue($no);
                $worksheet->getCell('B' . $i)->setValue("-");
                $worksheet->getCell('C' . $i)->setValue("'".$ktp);
                $worksheet->getCell('D' . $i)->setValue($name);
                $worksheet->getCell('E' . $i)->setValue($p.$tanggungan.$anak);
                $worksheet->getCell('F' . $i)->setValue($tempatlahir);
                $worksheet->getCell('G' . $i)->setValue($tgllahir);
                $worksheet->getCell('H' . $i)->setValue($gender);
                $worksheet->getCell('I' . $i)->setValue($statuskawin);
                $worksheet->getCell('J' . $i)->setValue($addressktp);
                $worksheet->getCell('K' . $i)->setValue('-');
                $worksheet->getCell('L' . $i)->setValue('-');
                $worksheet->getCell('M' . $i)->setValue('-');
                $worksheet->getCell('N' . $i)->setValue('-');
                $worksheet->getCell('O' . $i)->setValue('-');
                $worksheet->getCell('P' . $i)->setValue('-');
                $worksheet->getCell('Q' . $i)->setValue('-');
                $worksheet->getCell('R' . $i)->setValue('-');
                $worksheet->getCell('S' . $i)->setValue('-');
                $worksheet->getCell('T' . $i)->setValue('-');
                $worksheet->getCell('U' . $i)->setValue('-');
                $worksheet->getCell('V' . $i)->setValue($telprmh.$telphp);
                $worksheet->getCell('W' . $i)->setValue($email);
                $worksheet->getCell('X' . $i)->setValue('-');
                $worksheet->getCell('Y' . $i)->setValue($row['IDJobPosition']);
                $worksheet->getCell('Z' . $i)->setValue($statuskerja);
                $worksheet->getCell('AA' . $i)->setValue('-');
                $worksheet->getCell('AB' . $i)->setValue($hd);
                $worksheet->getCell('AC' . $i)->setValue('-');
                $worksheet->getCell('AD' . $i)->setValue('1');
                $worksheet->getCell('AE' . $i)->setValue('-');
                $worksheet->getCell('AF' . $i)->setValue('-');
                $worksheet->getCell('AG' . $i)->setValue($npwp);
                $worksheet->getCell('AH' . $i)->setValue('-');

              
            }
        }

        /// body border 
        $worksheet->getStyle('A16:AH' . $i)->getBorders()->getAllBorders()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A16:AH' . $i)->getBorders()->getOutline()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A16:AH' . $i)->getBorders()->getBottom()->
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

*/

 
    
    function getallprovince(){
        $where  = array(
            "lokasi_kabupaten"  => "00",
            "lokasi_kecamatan"      => "00",
            "lokasi_kelurahan"      => "0000"
        );
        $prov   = $this->addr->get_location($where)->result();
        echo json_encode($prov);
    }
    function get_cities(){
        $kodeprov   = $this->input->post("kodeprov");
        $where  = array(
            "lokasi_provinsi"   => $kodeprov,
            "lokasi_kabupaten !="  => "00",
            "lokasi_kecamatan"  => "00",
            "lokasi_kelurahan"  => "0000"
        );
        $cities   = $this->addr->get_location($where)->result();
        echo json_encode($cities);        
    }
    function get_subs(){
        $kodeprov   = $this->input->post("kodeprov");
        $kodekota   = $this->input->post("kodekota");
        $where  = array(
            "lokasi_provinsi"   => $kodeprov,
            "lokasi_kabupaten "  => $kodekota,
            "lokasi_kecamatan !="  => "00",
            "lokasi_kelurahan"  => "0000"
        );
        $subs   = $this->addr->get_location($where)->result();
        echo json_encode($subs);        
    }
    function get_vlgs(){
        $kodeprov   = $this->input->post("kodeprov");
        $kodekota   = $this->input->post("kodekota");
        $kodekec    = $this->input->post("kodekec");
        $where  = array(
            "lokasi_provinsi"   => $kodeprov,
            "lokasi_kabupaten "  => $kodekota,
            "lokasi_kecamatan"  => $kodekec,
            "lokasi_kelurahan !="  => "0000"
        );
        $desa   = $this->addr->get_location($where)->result();
        echo json_encode($desa);        
    }

}

/* End of file main.php */
/* Location: ./application/controllers/welcome.php */
