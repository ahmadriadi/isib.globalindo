<?php
//personal
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->model("public_model","pbl");
        $this->load->model("address_model","addr");
        $this->myid = $this->session->userdata('sess_userid');
    }  

    function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }	
   
    function index(){
        $data['employees']  = json_encode($this->pbl->get_employee()->result());
        $data['departement']= $this->pbl->get_department();
        $userid             = $this->session->userdata('sess_userid');
        $data['userid']     = $userid;
        //print_r($data['det']);
        $this->load->view('trx02/home',$data);                 
    }
//    job  =========================================================================
    function get_job(){
        $iduser     = $this->session->userdata('sess_userid');
        $job        = $this->pbl->get_job($iduser)->row();
        echo json_encode($job);
    }
    function save_job(){
        $iduser     = $this->session->userdata('sess_userid');
        $idparent   = $this->anti_xss($this->input->post("idparent"));
        $empstat    = $this->anti_xss($this->input->post("empstat"));
        $jobloc     = $this->anti_xss($this->input->post("jobloc"));
        $jobgrp     = $this->anti_xss($this->input->post("jobgrp"));
        $depart     = $this->anti_xss($this->input->post("depart"));
        $jobpos     = $this->anti_xss($this->input->post("jobpos"));
        $unitjob    = $this->anti_xss($this->input->post("unitjob"));

        $record     = array(
            "IDEmployeeParent"  => $idparent,
            "EmployeeStatus"    => $empstat,
            "Location"          => $jobloc,
            "JobGroup"          => $jobgrp,
            "Department"        => $depart,
            "Position"          => $jobpos,
            "Unit"              => $unitjob
        );
        $cek 	= $this->pbl->get_job($iduser)->result();
	if ($cek == NULL){
		$record['IDEmployee']	= $iduser;
		$this->pbl->insert_job($record);
	}
	else{
        	$this->pbl->update_job($iduser,$record);
	}
        
        $record2    = array(
            "IDEmployeeParent"=> $idparent,
            "IDLocation"    => $jobloc == "KAPUK" ? "1" : "2",
            "IDJobGroup"    => $jobgrp,
            "IDDepartement" => $depart,
            "IDUnitGroup"   => $unitjob,
            "IDJobPosition" => $jobpos
        );
        $cek2 	= $this->pbl->get_det_emp($iduser)->result();
        if ($cek2 == NULL){
		$record2['IDEmployee']	= $iduser;
		$this->pbl->insert_det_emp($record2);
	}
	else{
        	$this->pbl->update_det_emp($iduser,$record2);
	}
        
        $rec['IDEmployeeParent']= $idparent;
        $rec['IDLocation']= $jobloc == "KAPUK" ? "1" : "2";
        $rec['IDJobGroup']= $jobgrp;
        $rec['IDUnitGroup']= $unitjob;
        $rec['IDDepartement']= $depart;
        $cek3 	= $this->pbl->get_prs_emp($iduser)->result();
	if ($cek3 == NULL){
		$rec['IDEmployee']	= $iduser;
		$this->pbl->insert_prs_emp($rec);
	}
	else{
        	$this->pbl->update_prs_emp($iduser,$rec);
	}
        $msg    = array ("status" => "oke");
        echo json_encode($msg);
    }
//    personal =====================================================================
    function get_personal(){
        $iduser     = $this->session->userdata('sess_userid');
        $personal   = $this->pbl->get_employee($iduser)->row();
        echo json_encode($personal);
    }
    function save_personal(){
        $iduser         = $this->session->userdata('sess_userid');
        $fname          = $this->anti_xss($this->input->post("fname"));
        $nname          = $this->anti_xss($this->input->post("nname"));
        $pbirth         = $this->anti_xss($this->input->post("pbirth"));
        $dbirth         = $this->anti_xss($this->input->post("dbirth"));
        $bheight        = $this->anti_xss($this->input->post("bheight"));
        $bweight        = $this->anti_xss($this->input->post("bweight"));
        $gender         = $this->anti_xss($this->input->post("gender"));
        $tblood         = $this->anti_xss($this->input->post("tblood"));
        $czship         = $this->anti_xss($this->input->post("czship"));
        $religion       = $this->anti_xss($this->input->post("religion"));
        $noktp          = $this->anti_xss($this->input->post("noktp"));
        $nonpwp         = $this->anti_xss($this->input->post("nonpwp"));
        $nojamsos       = $this->anti_xss($this->input->post("nojamsos"));
        $nokpj          = $this->anti_xss($this->input->post("nokpj"));
        $abank          = $this->anti_xss($this->input->post("abank"));
        $marital        = $this->anti_xss($this->input->post("marital"));//radio
        $coupname       = $this->anti_xss($this->input->post("coupname"));
        $couplektp      = $this->anti_xss($this->input->post("couplektp"));
        if ($coupname == '-' OR $coupname == ''){
            $couplektp = "N";
        }
        $nchild         = $this->anti_xss($this->input->post("nchild"));
        $nohp           = $this->anti_xss($this->input->post("nohp"));
        $inemail        = $this->anti_xss($this->input->post("inemail"));
        $exmail         = $this->anti_xss($this->input->post("exmail"));
        $laddress       = $this->anti_xss($this->input->post("laddress"));
        $laddressph     = $this->anti_xss($this->input->post("laddressph"));
        $ktpaddress     = $this->anti_xss($this->input->post("ktpaddress"));
        $ktpaddressph   = $this->anti_xss($this->input->post("ktpaddressph"));
        $famcert        = $this->anti_xss($this->input->post("famcert"));
        $marrcert       = $this->anti_xss($this->input->post("marrcert"));

        $nobpjsemp      = $this->anti_xss($this->input->post("nobpjsemp"));
        $nobpjshlt      = $this->anti_xss($this->input->post("nobpjshlt"));
        $famcertno     = $this->anti_xss($this->input->post("famcertno"));

        $laddrprov      = $this->anti_xss($this->input->post("laddrprov"));
        $laddrcity      = $this->anti_xss($this->input->post("laddrcity"));
        $laddrsub       = $this->anti_xss($this->input->post("laddrsub"));
        $laddrvlg       = $this->anti_xss($this->input->post("laddrvlg"));
        $kaddrprov      = $this->anti_xss($this->input->post("kaddrprov"));
        $kaddrcity      = $this->anti_xss($this->input->post("kaddrcity"));
        $kaddrsub       = $this->anti_xss($this->input->post("kaddrsub"));
        $kaddrvlg       = $this->anti_xss($this->input->post("kaddrvlg"));        
        $liverw         = $this->anti_xss($this->input->post("liverw"));        
        $livert         = $this->anti_xss($this->input->post("livert"));        
        $ktprw          = $this->anti_xss($this->input->post("ktprw"));        
        $ktprt          = $this->anti_xss($this->input->post("ktprt"));        
//        +"&ktpkodepos="+ktpkodepos+"&livekodepos="+livekodepos
        $ktpkodepos     = $this->anti_xss($this->input->post("ktpkodepos"));
        $livekodepos    = $this->anti_xss($this->input->post("livekodepos"));
        $record     = array(
            "FullName"                  => $fname,
            "NickName"                  => $nname,
            "BirthPlace"                => $pbirth,
            "BirthDate"                 => date("Y-m-d",  strtotime($dbirth)),
            "Height"                    => $bheight,
            "Weight"                    => $bweight,
            "Gender"                    => $gender,
            "BloodType"                 => $tblood,
            "Citizenship"               => $czship,
            "Religion"                  => $religion,
            "NoKTP"                     => $noktp,
            "NoNPWP"                    => $nonpwp,
            "NoJamsostek"               => $nojamsos,
            "NoKPJ"                     => $nokpj,
            "MarriageCertificate"       => $marrcert,
            "FamilyMemberCertificate"   => $famcert,
            "BankAccount"               => $abank,
            "MaritalStatus"             => $marital,
            "CoupleName"                => $coupname,
            "CoupleKTP"                 => $couplektp,
            "NumberChildren"            => $nchild,
            "NoHP"                      => $nohp,
            "InternalEmail"             => $inemail,
            "ExternalEmail"             => $exmail,
            "LiveAddress"               => $laddress,
            "LiveAddressNoTelp"         => $laddressph,
            "KTPAddress"                => $ktpaddress,
            "KTPAddressNoTelp"          => $ktpaddressph,
            
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
            "BirthDate" => date("Y-m-d",  strtotime($dbirth)),
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
        
        $where      = array(
            "IDEmployee"    => $iduser
        );
//        ================================= check if couple name is not null
        if ($coupname == NULL OR $coupname == "" OR $coupname == "-"){
            $wh = array(
                "IDEmployee"    => $this->myid,
                "FamilyMember"  => "spouse"
            );
            $this->pbl->delete_family($wh);
        }
        else{
            $wh = array(
                "IDEmployee"    => $this->myid,
                "FamilyMember"  => "spouse"
            );
            $sps    = $this->pbl->get_family_member($wh);
            $lastid = $this->pbl->get_lastidfamily($this->myid)->row()->lastid;
            if ($sps->num_rows() == 0){
                $rec=array(
                    "IDEmployee"    => $this->myid,
                    "IDFamily"      => ($lastid*1)+1,
                    "FamilyMember"  => "spouse",
                    "Name"          => $coupname,
                    "NoKTP"         => $couplektp
                );
                $this->pbl->insert_family($rec);
            }
            if ($sps->num_rows() == 1){
                $wh = array(
                    "IDEmployee"    => $this->myid,
                    "FamilyMember"  => "spouse"
                );
                $rec= array(
                    "Name"          => $coupname,
                    "NoKTP"         => $couplektp
                );
                $this->pbl->update_family($wh,$rec);
            }
        }
        $this->pbl->update_prs_emp($this->myid,$recordh);//update to employee centre header
        $this->pbl->update_det_emp($this->myid,$recordd);//update to employee centre details
        $this->pbl->update_personal($where,$record);
        $this->upd_data($iduser);
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
    function upd_data($iduser){
        $editip     = $this->input->ip_address();
        $rec = array(
            "EditedIP"  => $editip,
            "EditedBy"  => $iduser,
            "EditedDate"=> date('Y-m-d')
            );
        $where      = array(
            "IDEmployee"    => $iduser
        );
        $this->pbl->update_personal($where,$rec);
    }   
    function upd_ftab(){
        $iduser     = $this->session->userdata('sess_userid');
        $tab        = $this->input->post('tab');
        $rec        = array("F".$tab => "1");
        $this->pbl->update_ftab($iduser,$rec);
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
//    family ====================================================================
    function get_family(){
        $iduser             = $this->session->userdata('sess_userid');
        $family['lastid']             = $this->pbl->get_lastidfamily($iduser)->row();
        $family['data']     = $this->pbl->get_family($iduser)->result();
        echo json_encode($family);
    }
    function padd_family(){
        $iduser     = $this->session->userdata('sess_userid');
        $member     = $this->anti_xss($this->input->post("member"));
        $nextid     = $this->anti_xss($this->input->post("nextid"));
        $name       = $this->anti_xss($this->input->post("fname"));
        $age        = $this->anti_xss($this->input->post("fage"));
        $address    = $this->anti_xss($this->input->post("faddress"));
        $education  = $this->anti_xss($this->input->post("fedu"));
        $occupation = $this->anti_xss($this->input->post("foccu"));
        $noktp      = $this->anti_xss($this->input->post("fnoktp"));
        $fambplace  = $this->anti_xss($this->input->post("fambplace"));
        $fambdate   = $this->anti_xss($this->input->post("fambdate"));
        $fambdate   = date('Y-m-d',  strtotime($fambdate));
        $rec        = array(
            "IDEmployee"    => $iduser,
            "IDFamily"      => $nextid,
            "FamilyMember"  => $member,
            "NoKTP"         => $noktp,
            "Name"          => $name,
            "Age"           => $age,
            "Address"       => $address,
            "Education"     => $education,
            "Occupation"    => $occupation,
            "BirthPlace"    => $fambplace,
            "BirthDate"     => $fambdate
        );
        $this->pbl->insert_family($rec);
        $msg    = array ("status" => "oke", "msg" => "New Family Member Added!");
        
        echo json_encode($msg);
    }
    function edit_family(){
        $iduser     = $this->session->userdata('sess_userid');
        $famid      = $this->input->post("famid");
        $rec        = $this->pbl->get_family($iduser,$famid)->row();
        
        echo json_encode($rec);
    }
    function del_family(){
        $iduser     = $this->session->userdata('sess_userid');
        $idfamily   = $this->anti_xss($this->input->post("famid"));
        $where      = array("IDEmployee" => $iduser, "IDFamily" => $idfamily);
        $this->pbl->delete_family($where);
        $msg        = array("status" => "oke", "msg" =>"Data Deleted!");
        
        echo json_encode($msg);
    }
    function pedit_family(){
        $iduser     = $this->session->userdata('sess_userid');
        $member     = $this->anti_xss($this->input->post("member"));
        $idfamily   = $this->anti_xss($this->input->post("famid"));
        $name       = $this->anti_xss($this->input->post("fname"));
        $age        = $this->anti_xss($this->input->post("fage"));
        $address    = $this->anti_xss($this->input->post("faddress"));
        $education  = $this->anti_xss($this->input->post("fedu"));
        $occupation = $this->anti_xss($this->input->post("foccu"));
        $noktp      = $this->anti_xss($this->input->post("fnoktp"));
        $fambplace  = $this->anti_xss($this->input->post("fambplace"));
        $fambdate   = $this->anti_xss($this->input->post("fambdate"));
        $fambdate   = date('Y-m-d',  strtotime($fambdate));
        $rec        = array(
            "FamilyMember"  => $member,
            "NoKTP"         => $noktp,
            "Name"          => $name,
            "Age"           => $age,
            "Address"       => $address,
            "Education"     => $education,
            "Occupation"    => $occupation,
            "BirthPlace"    => $fambplace,
            "BirthDate"     => $fambdate            
        );
        $where      = array(
            "IDEmployee"    => $iduser,
            "IDFamily"      => $idfamily
        );
        $this->pbl->update_family($where, $rec);
        
        $msg    = array ("status" => "oke", "msg" => "Data Updated!");
        echo json_encode($msg);
    }
//    end of family =======================================================
//    education ====================================================================
    function get_education(){
        $iduser             = $this->session->userdata('sess_userid');
        $family['lastid']             = $this->pbl->get_lastideducation($iduser)->row();
        $family['data']     = $this->pbl->get_education($iduser)->result();
        
        echo json_encode($family);
    }
    function padd_education(){
        $iduser     = $this->session->userdata('sess_userid');
        $level      = $this->anti_xss($this->input->post("level"));
        $ename      = $this->anti_xss($this->input->post("ename"));
        $course     = $this->anti_xss($this->input->post("course"));
        $ecity      = $this->anti_xss($this->input->post("ecity"));
        $efrom      = $this->anti_xss($this->input->post("efrom"));
        $etill      = $this->anti_xss($this->input->post("etill"));
        $ecert      = $this->anti_xss($this->input->post("ecert"));
        $nextid     = $this->anti_xss($this->input->post("nextid"));
        $rec        = array(
            "IDEmployee"        => $iduser,
            "IDEducation"       => $nextid,
            "EducationLevel"    => $level,
            "Course"            => $course,
            "SchoolName"        => $ename,
            "City"              => $ecity,
            "YearFrom"          => $efrom,
            "YearUntil"         => $etill,
            "Certificate"       => $ecert
        );
        $this->pbl->insert_education($rec);
        
        $msg    = array ("status" => "oke", "msg" => "New Education Background Added!");
        echo json_encode($msg);
    }
    function edit_education($eduid=NULL){
        $iduser     = $this->session->userdata('sess_userid');
        $eduid      = $this->input->post("eduid");
        $rec        = $this->pbl->get_education($iduser,$eduid)->row();
        echo json_encode($rec);
    }
    function del_education(){
        $iduser     = $this->session->userdata('sess_userid');
        $eduid      = $this->input->post("eduid");
        $where      = array("IDEmployee" => $iduser, "IDEducation" => $eduid);
        $this->pbl->delete_education($where);
        $msg        = array("status" => "oke", "msg" =>"Data Deleted!");
        
        echo json_encode($msg);
    }
    function pedit_education(){
        $iduser     = $this->session->userdata('sess_userid');
        $level      = $this->anti_xss($this->input->post("level"));
        $ename      = $this->anti_xss($this->input->post("ename"));
        $course     = $this->anti_xss($this->input->post("course"));
        $ecity      = $this->anti_xss($this->input->post("ecity"));
        $efrom      = $this->anti_xss($this->input->post("efrom"));
        $etill      = $this->anti_xss($this->input->post("etill"));
        $ecert      = $this->anti_xss($this->input->post("ecert"));
        $eduid     = $this->anti_xss($this->input->post("eduid"));
        
        $rec        = array(
            "EducationLevel"    => $level,
            "Course"            => $course,
            "SchoolName"        => $ename,
            "City"              => $ecity,
            "YearFrom"          => $efrom,
            "YearUntil"         => $etill,
            "Certificate"       => $ecert
        );
        $where      = array(
            "IDEmployee"        => $iduser,
            "IDEducation"       => $eduid
        );
        $this->pbl->update_education($where, $rec);
        $msg    = array ("status" => "oke", "msg" => "Data Updated!");
        
        echo json_encode($msg);
    }
//    end of education =======================================================
//    training and course ====================================================================
    function get_tnc(){
        $iduser             = $this->session->userdata('sess_userid');
        $family['lastid']   = $this->pbl->get_lastidtnc($iduser)->row();
        $family['data']     = $this->pbl->get_tnc($iduser)->result();
        echo json_encode($family);
    }
    function padd_tnc(){
        $iduser     = $this->session->userdata('sess_userid');
        
        $program    = $this->anti_xss($this->input->post("program"));
        $fac        = $this->anti_xss($this->input->post("facilitator"));
        $city       = $this->anti_xss($this->input->post("city"));
        $duration   = $this->anti_xss($this->input->post("duration"));
        $from       = $this->anti_xss($this->input->post("from"));
        $until      = $this->anti_xss($this->input->post("until"));
        $nextid     = $this->anti_xss($this->input->post("nextid"));
        $rec        = array(
            "IDEmployee"        => $iduser,
            "IDCourse"          => $nextid,
            "CourseProgram"     => $program,
            "CourseFacilitator" => $fac,
            "City"              => $city,
            "Duration"          => $duration,
            "YearFrom"          => $from,
            "YearUntil"         => $until
        );
        $this->pbl->insert_tnc($rec);
        $msg    = array ("status" => "oke", "msg" => "New Training and Course Added!");
        
        echo json_encode($msg);
    }
    function edit_tnc(){
        $iduser     = $this->session->userdata('sess_userid');
        $tncid      = $this->anti_xss($this->input->post("tncid"));
        $rec        = $this->pbl->get_tnc($iduser,$tncid)->row();
        echo json_encode($rec);
    }

    function del_tnc(){
        $iduser     = $this->session->userdata('sess_userid');
        $tncid      = $this->anti_xss($this->input->post("tncid"));
        $where      = array("IDEmployee" => $iduser, "IDCourse" => $tncid);
        $this->pbl->delete_tnc($where);
        $msg        = array("status" => "oke", "msg" =>"Data Deleted!");
        
        echo json_encode($msg);
    }
    function pedit_tnc(){
        $iduser     = $this->session->userdata('sess_userid');

        $program    = $this->anti_xss($this->input->post("program"));
        $fac        = $this->anti_xss($this->input->post("facilitator"));
        $city       = $this->anti_xss($this->input->post("city"));
        $duration   = $this->anti_xss($this->input->post("duration"));
        $from       = $this->anti_xss($this->input->post("from"));
        $until      = $this->anti_xss($this->input->post("until"));
        $idtnc     = $this->anti_xss($this->input->post("tncid"));
        
        $rec        = array(
            "CourseProgram"     => $program,
            "CourseFacilitator" => $fac,
            "City"              => $city,
            "Duration"          => $duration,
            "YearFrom"          => $from,
            "YearUntil"         => $until
        );
        $where      = array(
            "IDEmployee"        => $iduser,
            "IDCourse"          => $idtnc
        );
        $this->pbl->update_tnc($where, $rec);
        $msg    = array ("status" => "oke", "msg" => "Data Updated!");
        
        echo json_encode($msg);
    }
//    end of training and course =======================================================
//    languages ====================================================================
    function get_language(){
        $iduser             = $this->session->userdata('sess_userid');
        $family['lastid']   = $this->pbl->get_lastidlanguage($iduser)->row();
        $family['data']     = $this->pbl->get_language($iduser)->result();
        echo json_encode($family);
    }
    function padd_language(){
        $iduser     = $this->session->userdata('sess_userid');
        
        $language   = $this->anti_xss($this->input->post("language"));
        $listen     = $this->anti_xss($this->input->post("listen"));
        $read       = $this->anti_xss($this->input->post("read"));
        $convers   = $this->anti_xss($this->input->post("convers"));
        $write      = $this->anti_xss($this->input->post("write"));
        
        $nextid     = $this->anti_xss($this->input->post("nextid"));
        $rec        = array(
            "IDEmployee"    => $iduser,
            "IDLanguage"    => $nextid,
            "Language"      => $language,
            "Listening"     => $listen,
            "Reading"       => $read,
            "Conversation"  => $convers,
            "Writing"       => $write
        );
        $this->pbl->insert_language($rec);
        $msg    = array ("status" => "oke", "msg" => "New Language Added!");
        
        echo json_encode($msg);
    }
    function edit_language(){
        $iduser     = $this->session->userdata('sess_userid');
        $langid     = $this->anti_xss($this->input->post("langid"));
        $rec        = $this->pbl->get_language($iduser,$langid)->row();
        echo json_encode($rec);
    }
    function del_language(){
        $iduser     = $this->session->userdata('sess_userid');
        $langid      = $this->anti_xss($this->input->post("langid"));
        $where      = array("IDEmployee" => $iduser, "IDLanguage" => $langid);
        $this->pbl->delete_language($where);
        $msg        = array("status" => "oke", "msg" =>"Data Deleted!");
        
        echo json_encode($msg);
    }
    function pedit_language(){
        $iduser     = $this->session->userdata('sess_userid');

        $language   = $this->anti_xss($this->input->post("language"));
        $listen     = $this->anti_xss($this->input->post("listen"));
        $read       = $this->anti_xss($this->input->post("read"));
        $convers   = $this->anti_xss($this->input->post("convers"));
        $write      = $this->anti_xss($this->input->post("write"));
        
        $langid     = $this->anti_xss($this->input->post("langid"));
        $rec        = array(

            "Language"      => $language,
            "Listening"     => $listen,
            "Reading"       => $read,
            "Conversation"  => $convers,
            "Writing"       => $write
        );
        $where      = array(
            "IDEmployee"    => $iduser,
            "IDLanguage"    => $langid
        );
        $this->pbl->update_language($where, $rec);
        $msg    = array ("status" => "oke", "msg" => "Data Updated!");
        echo json_encode($msg);
    }
//    end of languages =======================================================
//    working experience ====================================================================
    function get_work(){
        $iduser             = $this->session->userdata('sess_userid');
        $family['lastid']   = $this->pbl->get_lastidwork($iduser)->row();
        $family['data']     = $this->pbl->get_work($iduser)->result();
        echo json_encode($family);
    }
    function padd_work(){
        $iduser     = $this->session->userdata('sess_userid');
        
        $comp   = $this->anti_xss($this->input->post("comp"));
        $address= $this->anti_xss($this->input->post("address"));
        $phone  = $this->anti_xss($this->input->post("phone"));
        $pos    = $this->anti_xss($this->input->post("pos"));
        $dur    = $this->anti_xss($this->input->post("dur"));
        
        $nextid     = $this->anti_xss($this->input->post("nextid"));
        $rec        = array(
            "IDEmployee"    => $iduser,
            "IDWorkExp"     => $nextid,
            "CompanyName"   => $comp,
            "CompanyAddress"=> $address,
            "CompanyPhone"  => $phone,
            "Position"      => $pos,
            "WorkDuration"  => $dur
        );
        $this->pbl->insert_work($rec);
        $msg    = array ("status" => "oke", "msg" => "New Working Experience Added!");
        echo json_encode($msg);
    }
    function edit_work(){
        $iduser     = $this->session->userdata('sess_userid');
        $workid     = $this->anti_xss($this->input->post("workid"));
        $rec        = $this->pbl->get_work($iduser,$workid)->row();
        echo json_encode($rec);
    }
    function del_work(){
        $iduser     = $this->session->userdata('sess_userid');
        $workid      = $this->anti_xss($this->input->post("workid"));
        $where      = array("IDEmployee" => $iduser, "IDWorkExp" => $workid);
        $this->pbl->delete_work($where);
        $msg        = array("status" => "oke", "msg" =>"Data Deleted!");
        echo json_encode($msg);
    }
    function pedit_work(){
        $iduser     = $this->session->userdata('sess_userid');

        $comp   = $this->anti_xss($this->input->post("comp"));
        $address= $this->anti_xss($this->input->post("address"));
        $phone  = $this->anti_xss($this->input->post("phone"));
        $pos    = $this->anti_xss($this->input->post("pos"));
        $dur    = $this->anti_xss($this->input->post("dur"));
        
        $workid     = $this->anti_xss($this->input->post("workid"));
        $rec        = array(

            "CompanyName"   => $comp,
            "CompanyAddress"=> $address,
            "CompanyPhone"   => $phone,
            "Position"      => $pos,
            "WorkDuration"  => $dur
        );
        $where      = array(
            "IDEmployee"    => $iduser,
            "IDWorkExp"    => $workid
        );
        $this->pbl->update_work($where, $rec);
        $msg    = array ("status" => "oke", "msg" => "Data Updated!");
        echo json_encode($msg);
    }
//    end of working experience =======================================================

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
        $kodeprov   = $this->anti_xss($this->input->post("kodeprov"));
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
        $kodeprov   = $this->anti_xss($this->input->post("kodeprov"));
        $kodekota   = $this->anti_xss($this->input->post("kodekota"));
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
        $kodeprov   = $this->anti_xss($this->input->post("kodeprov"));
        $kodekota   = $this->anti_xss($this->input->post("kodekota"));
        $kodekec    = $this->anti_xss($this->input->post("kodekec"));
        $where  = array(
            "lokasi_provinsi"   => $kodeprov,
            "lokasi_kabupaten "  => $kodekota,
            "lokasi_kecamatan"  => $kodekec,
            "lokasi_kelurahan !="  => "0000"
        );
        $desa   = $this->addr->get_location($where)->result();
        echo json_encode($desa);        
    }
    function get_usia(){
        $date1  = date('Y-m-d',strtotime($this->input->post('date1')));
        $date2  = date('Y-m-d',strtotime($this->input->post('date2')));
//        echo $this->input->post('date1').$this->input->post('date2');
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);
        $diff = date_diff($date1, $date2);
        echo $diff->format('%y');
    }
}
/* End of file main.php */
/* Location: ./application/controllers/welcome.php */
