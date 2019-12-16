<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("public_model","pbl");
    }     
    function index(){
        $data['unitjob']    = json_encode($this->pbl->get_unitjob()->result());
        $data['departement']= $this->pbl->get_departement();
        $data['position']   = $this->pbl->get_position();
        $data['relation']   = $this->pbl->get_relation();
        $data['edulevel']   = $this->pbl->get_edulevel();
        $data['location']   = $this->pbl->get_location();
        $data['jobgroup']   = $this->pbl->get_jobgroup();
        $data['religion']   = $this->pbl->get_religion();
        $data['majors']     = json_encode($this->pbl->get_majors()->result());
        $userid             = $this->session->userdata('sess_userid');
        $data['userid']     = $userid;
        $data['det']        = $this->pbl->get_data($userid);
        $data['parents']    = json_encode($this->pbl->get_employee()->result());
        //print_r($data['det']);
        $this->load->view('trx02/home',$data);                 
    }
    function update(){
        $userid     = $this->session->userdata('sess_userid');
        $account    = $this->input->post("account");
        $addktp     = $this->input->post("addktp");
        $addlive    = $this->input->post("addlive");
        $birthdate  = $this->input->post("birthdate");
        $birthplace = $this->input->post("birthplace");
        $bloodtype  = $this->input->post("bloodtype");
        $dprt       = $this->input->post("dprt");
        $e_eks      = $this->input->post("e_eks");
        $e_int      = $this->input->post("e_int");
        $fcert      = $this->input->post("fcert");
        $fullname   = $this->input->post("fullname");
        $gender     = $this->input->post("gender");
        $group      = $this->input->post("group");
        $idcoup     = $this->input->post("idcoup");
        $ideducation= $this->input->post("ideducation");
        $idloc      = $this->input->post("idloc");
        $idmajor    = $this->input->post("idmajor");
        $idmarital  = $this->input->post("idmarital");
        $idparent   = $this->input->post("idparent");
        $idpos      = $this->input->post("idpos");
        $idreligion = $this->input->post("idreligion");
        $idunit     = $this->input->post("idunit");
        $jmlanak    = $this->input->post("jmlanak");
        $mcert      = $this->input->post("mcert");
        $nanak1     = $this->input->post("nanak1");
        $nanak2     = $this->input->post("nanak2");
        $ncoup      = $this->input->post("ncoup");
        $nickname   = $this->input->post("nickname");
        $noext      = $this->input->post("noext");
        $nohp       = $this->input->post("nohp");
        $nokpj      = $this->input->post("nokpj");
        $noktp      = $this->input->post("noktp");
        $nonpwp     = $this->input->post("nonpwp");
        $notlp      = $this->input->post("notlp");
        $workexp    = $this->input->post("workexp");
        $recordh    = array(
            "FullName"          =>  $fullname,
            "IDEmployeeParent"  =>  $idparent,
            "Gender"            =>  $gender,
            "BankAccount"       =>  $account,
            "EmailInternal"     =>  $e_int,
            "EmailExternal"     =>  $e_eks,
            "Extension"         =>  $noext
        );
        $whereh     = array("IDEmployee" => $userid);
        $recordd    = array(
            "FullName"                  => $fullname,
            "IDEmployeeParent"          =>  $idparent,
            "BankAccount"               => $account,
            "NickName"                  => $nickname,
            "BirthPlace"                => $birthplace,
            "BirthDate"                 => date('Y-m-d',strtotime($birthdate)),
            "IDReligion"                => $idreligion,
            "IDEducation"               => $ideducation,
            "IDMajors"                  => $idmajor,
            "IDMarital"                 => $idmarital,
            "Gender"                    => $gender,
            "MarriageCertificate"       => $mcert,
            "FamilyMemberCertificate"   => $fcert,
            "IDMarriedCouple"           => $idcoup,
            "NumberChildren"            => $jmlanak,
            "FirstChild"                => $nanak1,
            "SecondChild"               => $nanak2,
            "CoupleName"                => $ncoup,
            "BloodType"                 => $bloodtype,
            "NoTelp"                    => $notlp,
            "NoHp"                      => $nohp,
            "NoNPWP"                    => $nonpwp,
            "NoKPJ"                     => $nokpj,
            "NoKTP"                     => $noktp,
            "LiveAddress"               => $addlive,
            "KTPAddress"                => $addktp,
            "WorkExperience"            => $workexp,
            "IDJobPosition"             => $idpos,
            "IDLocation"                => $idloc,
            "IDJobGroup"                => $group,
            "IDDepartement"             => $dprt,
            "IDUnitGroup"               => $idunit
        );
//        print_r($recordh);
//        echo "\n";
//        print_r($recordd);
        $this->pbl->updateh($whereh,$recordh);
        $this->pbl->updated($whereh,$recordd);
        $message    = array( "status" => "oke");
        echo json_encode($message);
    }
}

/* End of file main.php */
/* Location: ./application/controllers/welcome.php */
