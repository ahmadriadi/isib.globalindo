<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Tools_model', 'tools');
        $this->load->model('employee_model', 'employee');
        $this->load->model('uac_model', 'uac');
    }
    
    function index(){      
        
        $idmenu = "263";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('tools/restoreemployee',$data);
    }
    
    function autocomplete_employee() {
        $result = $this->employee->findall_employee();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                           'fullname' => strtoupper($row->FullName)
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    } 
    

    function restore() {
        $nip = $this->input->post('nip');
        
        $this->dataheader($nip);
        $mesg = 'Restore data for header, Done.<br/>';
        $this->datadetail($nip);
        $mesg.= 'Restore data for detail, Done.<br/>';
        
        $valid = "true";
        $json = '{ "mesg":"' . $mesg. '",                                   
                   "valid":"' . $valid . '"'
                    .
                    '}';
            echo $json;
        
    }

    function dataheader($nip) {
        $row = $this->tools->getdatahis_personal_h($nip);
        if ($row !== 'empty') {
            $record = array(
                "IDEmployee" => $row->IDEmployee,
                "IDEmployeeParent" => $row->IDEmployeeParent,
                "FullName" => $row->FullName,
                "EmailExternal" => $row->EmailExternal,
                "EmailInternal" => $row->EmailInternal,
                "Gender" => $row->Gender,
                "BankAccount" => $row->BankAccount,
                "IDLocation" => $row->IDLocation,
                "IDJobGroup" => $row->IDJobGroup,
                "IDDepartement" => $row->IDDepartement,
                "IDUnitGroup" => $row->IDUnitGroup,
                "HireDate" => $row->HireDate,
                "FlagHire" => $row->FlagHire,
                "ResignDate" => $row->ResignDate,
                "FlagResign" => $row->FlagResign,
                "Status" => $row->Status,
                "PublicStatus" => $row->PublicStatus,
            );
            $this->tools->update_personal_h($nip, $record);
        }
    }
    function datadetail($nip){
         $row = $this->tools->getdatahis_personal_d($nip);
        if ($row !== 'empty') {
            $record = array(
                "IDEmployee" => $row->IDEmployee,
                "IDEmployeeParent" => $row->IDEmployeeParent,
                "FullName" => $row->FullName,
                "BankAccount" => $row->BankAccount,
                "NoJamsostek" => $row->NoJamsostek,
                "NickName" => $row->NickName,
                "BirthPlace" => $row->BirthPlace,
                "NoJamsostek" => $row->NoJamsostek,
                "NickName" => $row->NickName,
                "BirthPlace" => $row->BirthPlace,
                "Citizenship" => $row->Citizenship,
                "BirthDate" => $row->BirthDate,
                "Height" => $row->Height,
                "Weight" => $row->Weight,
                "Religion" => $row->Religion,
                "IDEducation" => $row->IDEducation,
                "IDMajors" => $row->IDMajors,
                "MaritalStatus" => $row->MaritalStatus,
                "Gender" => $row->Gender,
                "MarriageCertificate" => $row->MarriageCertificate,
                "FamilyMemberCertificate" => $row->FamilyMemberCertificate,
                "CoupleKTP" => $row->CoupleKTP,
                "NumberChildren" => $row->NumberChildren,
                "FirstChild" => $row->FirstChild,
                "SecondChild" => $row->SecondChild,
                "CoupleName" => $row->CoupleName,
                "BloodType" => $row->BloodType,
                "NoTelp" => $row->NoTelp,
                "NoHp" => $row->NoHp,
                "NoNPWP" => $row->NoNPWP,
                "NoKPJ" => $row->NoKPJ,
                "NoKTP" => $row->NoKTP,
                "LiveAddress" => $row->LiveAddress,
                "LiveAddressNoTelp" => $row->LiveAddressNoTelp,
                "KTPAddress" => $row->KTPAddress,
                "KTPAddressNoTelp" => $row->KTPAddressNoTelp,
                "WorkExperience" => $row->WorkExperience,
                "IDJobPosition" => $row->IDJobPosition,
                "IDLocation" => $row->IDLocation,
                "IDJobGroup" => $row->IDJobGroup,
                "IDDepartement" => $row->IDDepartement,
                "IDUnitGroup" => $row->IDUnitGroup,
                "DateFirstJoint" => $row->DateFirstJoint,
                "DateStartProbation" => $row->DateStartProbation,
                "DatePassProbation" => $row->DatePassProbation,
                "DateNewContract" => $row->DateNewContract,
                "DateEndContract" => $row->DateEndContract,
                "DateInField" => $row->DateInField,
                "EmailInternal" => $row->EmailInternal,
                "EmailExternal" => $row->EmailExternal,
                "Extension" => $row->Extension,
                "HireDate" => $row->HireDate,
                "FlagHire" => $row->FlagHire,
                "ResignDate" => $row->ResignDate,
                "FlagResign" => $row->FlagResign,
                "ReasonResign" => $row->ReasonResign,
                "Status" => $row->Status,
                "EmployeeStatus" => $row->EmployeeStatus,
                "Note" => $row->Note,
                "NoBPJSEmp" => $row->NoBPJSEmp,
                "NoBPJSHlt" => $row->NoBPJSHlt,
                "NoFamCert" => $row->NoFamCert,
                "LiveProvince" => $row->LiveProvince,
                "LiveCity" => $row->LiveCity,
                "LiveSubdistrict" => $row->LiveSubdistrict,
                "LiveVillage" => $row->LiveVillage,
                "LiveRW" => $row->LiveRW,
                "LiveRT" => $row->LiveRT,
                "KTPProvince" => $row->KTPProvince,
                "KTPCity" => $row->KTPCity,
                "KTPSubdistrict" => $row->KTPSubdistrict,
                "KTPVillage" => $row->KTPVillage,
                "KTPRT" => $row->KTPRT,
                "KTPRW" => $row->KTPRW,
                "LivePostalCode" => $row->LivePostalCode,
                "KTPPostalCode" => $row->KTPPostalCode,
               
            );
            $this->tools->update_personal_d($nip, $record);
        }
        
        
        
    }

}

/* End of file main.php */
/* Location: ./application/controllers/welcome.php */
