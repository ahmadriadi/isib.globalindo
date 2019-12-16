<?php
// Official Travel
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->model("officialtravel_trx_model","otr");
        $this->iduser = $this->session->userdata("sess_userid");
    }

   function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }

    function index(){
        $where      = array("IDEmployee" => $this->iduser, "DeleteFlag" => "A");
        $data['travels']    = $this->otr->get_travel($where);
        //$data['lastid']     = $this->otr->get_lastid()->row()->IDTravel;//pengecekan dipindahkan ke 'pada saat menyimpan', untuk menghindari bentrok id
        $this->load->view("trx04/home",$data);
    }
    function get_personal(){
        $personal = $this->otr->get_personal($this->iduser)->row();
        echo json_encode($personal);
    }
    //$from,$until,$note,$vehicle,$nextid
    function save_travel(){
//        $from   = date("Y-m-d", strtotime($from));
//        $until  = date("Y-m-d", strtotime($until));
        $from   = date("Y-m-d", strtotime($this->input->post("from")));
        $until  = date("Y-m-d", strtotime($this->input->post("until")));
        $note   = $this->anti_xss($this->input->post("note"));
        $vehicle= $this->anti_xss($this->input->post("vehicle"));
//        $lastid = $this->otr->get_lastid()->row()->IDTravel;
//        $nextid = ($lastid*1)+1;
        $addby  = $this->iduser;
        $adddate= date("Y-m-d H:i:s");
        $addip  = $this->input->ip_address();
        
        $user   = $this->otr->get_personal($this->iduser)->row();
        if ($user->IDJobPosition == "MANAGER"){
            $con    = "1";
            $condate= date("Y-m-d H:i:s");
            $conip  = $this->input->ip_address();
        }
        else{            
            $con    = "0";
            $condate= "0000-00-00";
            $conip  = "";
            
        }
        $record = array(
//            "IDTravel"      => $nextid,
            "IDEmployee"    => $this->iduser,
            "OfficialTravelDate" => $from, 
            "UntilDate" => $until, 
            "Note" => $note,
            "VehicleNo" => $vehicle,
            "ConfirmFlag"       => $con,
            "ConfirmDate"       => $condate,
            "ConfirmIP"         => $conip,            
            "AddedBy"   => $addby,
            "AddedDate"     => $adddate,
            "AddedIP"  => $addip
        );
        $this->otr->insert_travel($record);
        if ($con == "0"){
            $prs    = $this->otr->get_personal($this->iduser)->row();
            $ats    = $this->otr->get_prs_public($prs->IDEmployeeParent)->row();
            $data['state']      = "confirm";
            $data['sendername'] = $prs->FullName;
            $data['receivername'] = $ats->FullName;

            $sendto     = $ats->InternalEmail;
            $subject    = "TIS Notification - Official Travel";
            $message    = $this->load->view("trx04/email",$data,TRUE);
            $this->sendmail->internalmail($sendto, $subject, $message);
            $eksmail    = explode(",",$ats->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $subject, $message);
        }
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
    function edit_travel(){
        $idtravel   = $this->input->post("idtravel");
        $where      = array("ID" => $idtravel);
        $travel     = $this->otr->get_travel($where)->row();
        echo json_encode($travel);
    }
    function update_travel(){
        $idtravel     = $this->input->post("idtravel");
        $from   = date("Y-m-d", strtotime($this->input->post("from")));
        $until  = date("Y-m-d", strtotime($this->input->post("until")));
        $note   = $this->anti_xss($this->input->post("note"));
        $vehicle= $this->anti_xss($this->input->post("vehicle"));
        $editby  = $this->iduser;
        $editdate= date("Y-m-d H:i:s");
        $editip  = $this->input->ip_address();
        $record = array(
            "OfficialTravelDate" => $from, 
            "UntilDate" => $until, 
            "Note" => $note,
            "VehicleNo"  => $vehicle,
            "ConfirmFlag"=> "0",
            "EditedBy"   => $editby,
            "EditedDate"     => $editdate,
            "EditedIP"  => $editip
        );
        $where  = array(
            "IDEmployee"    => $this->iduser,
            "ID"      => $idtravel
        );
        
        $travel    = $this->otr->get_travel($where)->row(); // cek lp yg akan diupdate
        // jika yg akan diupdate sebelumnya ditolak, maka kirim email lagi keatasan
        if ($travel->ConfirmFlag == "2"){
            $prs    = $this->otr->get_personal($this->iduser)->row();
            $ats    = $this->otr->get_prs_public($prs->IDEmployeeParent)->row();
            $data['state']      = "confirm";
            $data['sendername'] = $prs->FullName;
            $data['receivername'] = $ats->FullName;

            $sendto     = $ats->InternalEmail;
            $subject    = "TIS Notification - Official Travel";
            $message    = $this->load->view("trx04/email",$data,TRUE);
            $this->sendmail->internalmail($sendto, $subject, $message);
            $eksmail    = explode(",",$ats->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $subject, $message);
        }
        
        $this->otr->update_travel($where,$record);
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
    function delete_travel(){
        $idtravel   = $this->anti_xss($this->input->post("idtravel"));
        $where      = array("ID" => $idtravel);
        // metode delete flag =========================
        $record     = array(
            "DeleteFlag"    => "1",
            "DeletedBy"     => $this->iduser,
            "DeletedDate"   => date('Y-m-d H:i:s'),
            "DeletedIP"     => $this->input->ip_address()
            );
        $this->otr->update_travel($where,$record);
        // end of metode delete flag ==================
        // metode delete permanen >>>>>>>>>>>>>>>>>>>>>
//        $this->otr->delete_travel($where);
        // end of metode delete permanen >>>>>>>>>>>>>>
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
    function print_travel($idtravel){
        $where  = array("ID" => $idtravel);
        $data['data']   = $this->otr->get_travel($where);
        $nmfile = "Official_Travel.pdf";
        $isi    = $this->load->view("trx04/print",$data,TRUE);
        $this->mpdf = new mPDF;
        $this->mpdf->WriteHTML($isi);
        $this->mpdf->Output($nmfile,"I");
    }
    function notifications(){
        $where      = "ConfirmFlag = '0' AND DeleteFlag = 'A' AND IDEmployee IN (SELECT isib_employee.m01personal.IDEmployee FROM isib_employee.m01personal WHERE isib_employee.m01personal.IDEmployeeParent = '$this->iduser')";
        $data['otravels']   = $this->otr->get_travel($where);
        $this->load->view("trx04/notifications",$data);
    }
    function captcha(){
        $data['idtravel']  = $this->input->post('idtravel');
        $this->load->view("trx04/confirm",$data);        
    }
    function confirm(){
        $idtravel   = $this->input->post('idtravel');
        $stat       = $this->input->post('stat');
        $reason     = $this->input->post('reason');
        $record     = array("ConfirmFlag" => $stat, "ConfirmDate" => date('Y-m-d H:i:s'), "ConfirmIP" => $this->input->ip_address(), "ConfirmBy" => $this->iduser, "RejectReason" => $reason);
        $where      = array("ID" => $idtravel);
        $this->otr->update_travel($where,$record);
        
        $travel    = $this->otr->get_travel($where)->row();
        $prs    = $this->otr->get_prs_public($travel->IDEmployee)->row();
        $data['state']  = "status";
        $data['confirm']= $stat;
        $data['travel']= $travel;
        $data['receivername']= $prs->FullName;
        $sendto     = $prs->InternalEmail;
        $subject    = "TIS Notification - Official Travel";
        $message    = $this->load->view("trx04/email",$data,TRUE);
        $this->sendmail->internalmail($sendto, $subject, $message);
        $eksmail    = explode(",", $prs->ExternalEmail);
        $this->sendmail->externalmail($eksmail, $subject, $message);
    }

   }

