<?php
//personal
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->model("public_model","pbl");
        $this->load->model("Historytable_model","history");
        $this->load->model("address_model","addr");
        $this->myid = $this->session->userdata('sess_userid');
        
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
   
    function index(){
        $data['employees']  = json_encode($this->pbl->get_employee()->result());
        $data['departement']= $this->pbl->get_department();
        $userid             = $this->session->userdata('sess_userid');
        $data['userid']     = $userid;
        //print_r($data['det']);
        $this->load->view('trx02/home',$data);                 
    }
//    checker ====================================================================== 
    function cek_job(){
        $iduser     = $this->session->userdata('sess_userid');
        $idparent   = $this->anti_xss($this->input->post("idparent"));
        $empstat    = $this->anti_xss($this->input->post("empstat"));
        $jobloc     = $this->anti_xss($this->input->post("jobloc"));
        $jobgrp     = $this->anti_xss($this->input->post("jobgrp"));
        $depart     = $this->anti_xss($this->input->post("depart"));
        $jobpos     = $this->anti_xss($this->input->post("jobpos"));
        $unitjob    = $this->anti_xss($this->input->post("unitjob"));
        
        $job        = $this->pbl->get_job($iduser)->row();
        $hasil[0]   = $job->IDEmployeeParent== $idparent ? 0  : 1;
        $hasil[1]   = $job->EmployeeStatus  == $empstat ? 0  : 1;
        $hasil[2]   = $job->Location    == $jobloc ? 0  : 1;
        $hasil[3]   = $job->JobGroup    == $jobgrp ? 0  : 1;
        $hasil[4]   = $job->Department  == $depart ? 0  : 1;
        $hasil[5]   = $job->Position    == $jobpos ? 0  : 1;
        $hasil[6]   = $job->Unit        == $unitjob ? 0  : 1;
//        echo $job->EmployeeStatus." ==> $empstat \n";
//        print_r($hasil);
        $hasil      = array_sum($hasil);
//        echo $hasil;
        $msg['status']  = $hasil == 0 ? "bad" : "oke";
        echo json_encode($msg);
    }
    function cek_form(){
        $fieldname  = $this->input->post("field");
        $fieldval   = $this->input->post("value");
//        echo $fieldname;
        $fieldmatch = array(
            "fname"     => "FullName",
            "nname"     => "NickName",
            "pbirth"    => "BirthPlace",
            "dbirth"    => "BirthDate",
            "bheight"   => "Height",
            "bweight"   => "Weight",
            "gender"    => "Gender",
            "tblood"    => "BloodType",
            "czship"    => "Citizenship",
            "religion"  => "Religion",
            "noktp"     => "NoKTP",
            "nonpwp"    => "NoNPWP",
            "nojamsos"  => "NoJamsostek",
            "nokpj"     => "NoKPJ",
            "abank"     => "BankAccount",
            "marital"   => "MaritalStatus",
            "coupname"  => "CoupleName",
            "couplektp" => "CoupleKTP",
            "nchild"    => "NumberChildren",
            "nohps"     => "NoHP",
            "inemail"   => "InternalEmail",
            "exmails"   => "ExternalEmail",
            "laddress"  => "LiveAddress",
            "telpls"    => "LiveAddressNoTelp",
            "telpks"    => "KTPAddressNoTelp",
            "ktpaddress"=> "KTPAddress",
            "famcert"   => "FamilyMemberCertificate",
            "famcertno" => "NoFamCert",
            "marrcert"  => "MarriageCertificate",
            "nobpjsemp" => "NoBPJSEmp",
            "nobpjshlt" => "NoBPJSHlt",
            "laddrvlg"  => "LiveVillage",
            "laddrsub"  => "LiveSubdistrict",
            "laddrcity" => "LiveCity",
            "laddrprov" => "LiveProvince",
            "livert"    => "LiveRT",
            "liverw"    => "LiveRW",
            "lkodepos"  => "LivePostalCode",
            "kaddrvlg"  => "KTPVillage",
            "kaddrsub"  => "KTPSubdistrict",
            "kaddrcity" => "KTPCity",
            "kaddrprov" => "KTPProvince",
            "ktprt"    => "KTPRT",
            "ktprw"    => "KTPRW",
            "ktpkodepos"  => "KTPPostalCode"
        );
        $fieldname  = explode("-(x)-", $fieldname);
        $fieldval   = explode("-(x)-", $fieldval);
//        $lfield     = count($fieldname);
//        $lval       = count($fieldval);
        $emp    = $this->pbl->get_employee($this->myid)->row_array();
        $i = 0;
        foreach ($fieldname as $fn){
            $cval   = $fn == 'dbirth' ? date("Y-m-d",  strtotime($fieldval[$i])) : $fieldval[$i];
            $col    = $fieldmatch[$fn];
            $check[$i]  = $emp[$col] == $cval ? 0 : 1;// jika sama = 0 jika beda = 1
//            echo $col."=>".$check[$i]."=> $emp[$col] ? $cval"."\n";
            $i++;
        }
        $check  = array_sum($check);
        $exec   = $check == 0 ? "bad" : "oke";
        $msg['status']  = $exec;
        echo json_encode($msg);
//                var marital   = $("input[type='radio'][name='marital']:checked").val();//radio
//                var coupname   = $("#coupname").val();
//                var couplektp   = $("#couplektp").val();
//                var nchild   = $("#nchild").val();
//                var nohp   = getnohp();
//                var inemail   = $("#inemail").val();
//                var exmail   = getexmail();
//                var laddress   = $("#laddress").val();
//                var laddressph   = gettelpl();
//                var ktpaddress   = $("#ktpaddress").val();
//                var ktpaddressph   = gettelpk();
//                var famcert = $("#famcert").val();
//                var marrcert = $("#marrcert").val();
//                
//                var nobpjsemp   = $("#nobpjsemp").val();
//                var nobpjshlt   = $("#nobpjshlt").val();
//                var famcertno  = $("#famcertno").val();      
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
        $changes    = $this->anti_xss($this->input->post("changes"));

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
                $this->historydata($iduser, 'save_job (on public)', 'job');
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
                $this->historydata($iduser, 'save_job (on public)', 'personalemp_detail');
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
		$this->historydata($iduser, 'save_job (on public)', 'personalemp');
		$this->pbl->update_prs_emp($iduser,$rec);
		$whprs['IDEmployee']    = $iduser;
		$rec2['IDEmployeeParent']=$idparent;
		$this->pbl->update_personal($whprs,$rec2);        	
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
        $f1s1          = $this->anti_xss($this->input->post("f1s1"));        
        $f1s2          = $this->anti_xss($this->input->post("f1s2"));        
        $f1s3          = $this->anti_xss($this->input->post("f1s3"));        
        $f1s4          = $this->anti_xss($this->input->post("f1s4"));        
        $f1s5          = $this->anti_xss($this->input->post("f1s5"));        
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
            "LivePostalCode"            => $livekodepos,
            
            "F1s1"                      => $f1s1,
            "F1s2"                      => $f1s2,
            "F1s3"                      => $f1s3,
            "F1s4"                      => $f1s4,
            "F1s5"                      => $f1s5
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
                    "FamilyMember"  => "spouse",
                    "DeleteFlag"  => "A",
                );
                $rec= array(
                    "Name"          => $coupname,
                    "NoKTP"         => $couplektp
                );
                $this->historydata($wh, 'save_personal (on public)', 'familyrow');
                $this->pbl->update_family($wh,$rec);
            }
        }
        
        $this->historydata($this->myid, 'save_personal (on public)', 'personalemp');
        $this->historydata($this->myid, 'save_personal (on public)', 'personalemp_detail');
        $this->historydata($this->myid, 'save_personal (on public)', 'personalpbl');
        
        
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
        $emp    = $this->pbl->get_employee($iduser)->row();
        if ($tab == "1"){
            
            $s1 = $emp->F1s1;
            $s2 = $emp->F1s2;
            $s3 = $emp->F1s3;
            $s4 = $emp->F1s4;
            $s5 = $emp->F1s5;
            $jmls   = $s1+$s2+$s3+$s4+$s5;
            $ket    = $jmls == 5 ? 1 : 0;
        }
        else if ($tab == "2"){
            $ket    = $emp->F2f1 == "1" ? 1 : 0 ;
        }else{
            $ket    = 1;
        }
        $rec        = array("F".$tab => "$ket");
        $this->pbl->update_ftab($iduser,$rec);
        $stat   = $ket == 1 ? "oke" : "bad";
        $msg    = array("status" => $stat);
        echo json_encode($msg);
    }
//    family ====================================================================
    function get_family(){
        $iduser             = $this->session->userdata('sess_userid');
        $family['lastid']             = $this->pbl->get_lastidfamily($iduser)->row();
        $family['data']     = $this->pbl->get_family($iduser)->result();
        $family['mother']   = $this->pbl->get_mother($iduser);
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
        if ($member == "child"){
            //ambil alamat jika yang dimasukkan adalah anak
            $user   = $this->pbl->get_employee($iduser)->row();
            $addrktp= $user->KTPAddress;
            $addr_rt= $user->KTPRT;
            $addr_rw= $user->KTPRW;
            $addr_vl= $user->KTPVillage;
            $addr_sd= $user->KTPSubdistrict;
            $addr_ct= $user->KTPCity;
            $addr_pv= $user->KTPProvince;
            $addr_np= $this->KTPPostalCode;
            $childaddr  = "
                $addrktp RT$addr_rt/RW$addr_rw, Kelurahan/Desa $addr_vl, 
                Kecamatan $addr_sd, Kota/Kabupaten $addr_ct, 
                $addr_pv $addr_np";
            $address    = $childaddr;
        }
		else if ($member == "spouse"){
            $recp['CoupleName'] = $name;
            $recp['CoupleKTP']  = $noktp;
            $whp['IDEmployee']  = $iduser;
            $this->pbl->update_personal($whp,$recp);
            $this->upd_data($this->myid);
        }        
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
        //session cek ibu ganti jadi ada jika yang dimasukkan adalah informasi ibu -- okierie
        if ($member == "mother"){
			$this->session->set_userdata("sess_mother","exists");
		}
        //
        $msg    = array ("status" => "oke", "msg" => "New Family Member Added!","member" => $member);
        
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
        $fam    = $this->pbl->get_family($this->myid,$idfamily)->row();
        
        if ($fam->FamilyMember == "spouse"){
            $recp['CoupleName'] = "-";
            $recp['CoupleKTP']  = "N";
            $whp['IDEmployee']  = $this->myid;
            $this->pbl->update_personal($whp,$recp);
            $this->upd_data($this->myid);
            $this->pbl->delete_family($where);
        }
        else{
			
			$this->pbl->delete_family($where);
		}
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
            "IDFamily"      => $idfamily,
            "DeleteFlag"      => 'A',
        );
        $this->historydata($where, 'pedit_family (on public)', 'familyrow');
        $this->pbl->update_family($where, $rec);

        if ($member == "spouse"){
            $recp['CoupleName'] = $name;
            $recp['CoupleKTP']  = $noktp;
            $whp['IDEmployee']  = $this->myid;
            $this->pbl->update_personal($whp,$recp);
            $this->upd_data($this->myid);
        }
        //session cek ibu ganti jadi ada jika yang dimasukkan adalah informasi ibu -- okierie
        if ($member == "mother"){
			$this->session->set_userdata("sess_mother","exists");
		}
        //        
        $msg    = array ("status" => "oke", "msg" => "Data Updated!","member"=>$member);
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
        $this->historydata($where, 'pedit_education (on public)', 'educationrow');
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
            "IDCourse"          => $idtnc,
            "DeleteFlag"          => 'A'
        );
        
        $this->historydata($where, 'pedit_tnc (on public)', 'courserow');
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
            "IDLanguage"    => $langid,
            "DeleteFlag"    => 'A'
        );
        
        $this->historydata($where, 'pedit_language (on public)', 'languagerow');
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
            "IDWorkExp"    => $workid,
            "DeleteFlag"    => 'A'
        );
      
        $this->historydata($where, 'pedit_work (on public)', 'workexprow');
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
    
    
}
/* End of file main.php */
/* Location: ./application/controllers/welcome.php */
