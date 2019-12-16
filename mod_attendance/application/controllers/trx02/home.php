<?php
// LEAVE PERMIT
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->model("leavepermit_trx_model",'lpt');
        $this->iduser = $this->session->userdata("sess_userid");
    }
    function get_accepted(){
        $accepted = $this->lpt->get_accepted($this->iduser);
        echo $accepted;
    }
	function anti_xss($source)
	{
			$f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
			return $f;
	}


    function index(){
        $wdata = array ("IDEmployee" => $this->iduser, "DeleteFlag" => "A");
        $data['all']    = $this->lpt->get_leavepermit($wdata); 
        //$data['lastid'] = $this->lpt->get_last_id()->row()->IDLeavePermit;
        $this->load->view("trx02/home",$data);
    }
    function get_personal(){
        $data = $this->lpt->get_personal($this->iduser)->row();
        echo json_encode($data);
    }
    function get_other(){
        $u      = $this->input->post('userids');
        $userids= explode(",", $u);
        array_push($userids, $this->iduser);
        $other  = $this->lpt->get_other($userids)->result();
        echo json_encode($other);
    }
    // nonaktif sementara
//    function get_parts(){
//        $idlpermit  = $this->input->post("idlpermit");
//        $where      = array ("IDLeavePermit" => $idlpermit);
//        $parts      = $this->lpt->get_participant($where)->result();
//        echo json_encode($parts);      
//    }
    function save_lpermit(){
        $iduser = $this->iduser;
//        $lastid = $this->lpt->get_last_id()->row()->IDLeavePermit;
        $nextid = ($lastid*1)+1;
        $out    = date('Y-m-d H:i:s', strtotime($this->input->post('outtime')));
        $in     = date('Y-m-d H:i:s', strtotime($this->input->post('intime')));
        $necess = $this->anti_xss($this->input->post('necessity'));
        $note   = $this->anti_xss($this->input->post('note'));
        $vehicle= $this->anti_xss($this->input->post('vehicleno'));
        $a = date_create($out);
        $b = date_create($in);
        $t = date_diff($a, $b);
        $jam    = $t->format("%h");
        $menit  = $t->format("%i");
        $total  = $jam+($menit/60);
        if ($jam > 9){
            $total = substr($total, 0,5);
        }
        if ($jam < 10){
            $total = substr($total, 0,4);
        }
        $other  = $this->input->post('other');
        
        $user   = $this->lpt->get_personal($iduser)->row();
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
            "IDEmployee"        => $iduser,
//            "IDLeavePermit"     => $nextid,
            "LeavePermitDate"   => date("Y-m-d"),
            "OutDate"           => $out,
            "InDate"            => $in,
            "IMKHour "          => $total,
            "Necessity "        => $necess,
            "Note"              => $note,
            "VehicleNo"         => $vehicle,
            "ConfirmFlag"       => $con,
            "ConfirmDate"       => $condate,
            "ConfirmIP"         => $conip,
            "AddedBy"           => $this->iduser,
            "AddedIP"           => $this->input->ip_address(),
            "AddedDate"         => date('Y-m-d H:i:s')
        );
        $others = explode(",", $other);
        $this->lpt->insert_lpermit($record);
        if ($con == "0"){
            $prs    = $this->lpt->get_personal($this->iduser)->row();
            $ats    = $this->lpt->get_prs_public($prs->IDEmployeeParent)->row();
            $data['state']      = "confirm";
            $data['sendername'] = $prs->FullName;
            $data['receivername'] = $ats->FullName;

            $sendto     = $ats->InternalEmail;
            $subject    = "TIS Notification - Leave Permit";
            $message    = $this->load->view("trx02/email",$data,TRUE);
            $this->sendmail->internalmail($sendto, $subject, $message);
            $eksmail    = explode(",",$ats->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $subject, $message);
        }
//        nonaktif sementara
//        if ($others[0] != ""){
//            foreach ($others as $o){
//                $rec = array ("IDLeavePermit" => $nextid, "IDEmployee" => $o);
////                echo $o."|";
//                $this->lpt->insert_lpermit_d($rec);
//            }
//        }
        $msg = array("status" => "oke");
        echo json_encode($msg);
    }
    function edit_lpermit($idlpermit=NULL){
        $idlpermit = $this->input->post("idlpermit");
        $wdata  = array("ID" => $idlpermit );
        if ($idlpermit != ""){
            $data['status'] = "oke";
            $data['data']   = $this->lpt->get_leavepermit($wdata)->row();
            $data['part']   = $this->lpt->get_participant($wdata)->result();            
        }else{
            $data['status'] = "bad";
        }
        echo json_encode($data);
    }
    function update_lpermit(){
        $idlpermit = $this->input->post("idlpermit");
        $out    = date('Y-m-d H:i:s', strtotime($this->input->post('outtime')));
        $in     = date('Y-m-d H:i:s', strtotime($this->input->post('intime')));
        $necess = $this->anti_xss($this->input->post('necessity'));
        $note   = $this->anti_xss($this->input->post('note'));
        $vehicle= $this->anti_xss($this->input->post('vehicleno'));
        $a = date_create($out);
        $b = date_create($in);
        $t = date_diff($a, $b);
        $jam    = $t->format("%h");
        $menit  = $t->format("%i");
        $total  = $jam+($menit/60);
        if ($jam > 9){
            $total = substr($total, 0,5);
        }
        if ($jam < 10){
            $total = substr($total, 0,4);
        }
        $other  = $this->input->post('other');
        $record = array(
//            "LeavePermitDate"   => date("Y-m-d"),
            "OutDate"           => $out,
            "InDate"            => $in,
            "IMKHour "          => $total,
            "Necessity "        => $necess,
            "Note"              => $note,
            "VehicleNo"         => $vehicle,
            "EditedBy"          => $this->iduser,
            "EditedIP"          => $this->input->ip_address(),
            "EditedDate"        => date('Y-m-d H:i:s'),
            "ConfirmFlag"       => "0",
        );
        $where = array(
            "ID"     => $idlpermit
        );
        
        $lpermit    = $this->lpt->get_leavepermit($where)->row(); // cek lp yg akan diupdate
        // jika yg akan diupdate sebelumnya ditolak, maka kirim email lagi keatasan
        if ($lpermit->ConfirmFlag == "2"){
            $prs    = $this->lpt->get_personal($this->iduser)->row();
            $ats    = $this->lpt->get_prs_public($prs->IDEmployeeParent)->row();
            $data['state']      = "confirm";
            $data['sendername'] = $prs->FullName;
            $data['receivername'] = $ats->FullName;

            $sendto     = $ats->InternalEmail;
            $subject    = "TIS Notification - Leave Permit";
            $message    = $this->load->view("trx02/email",$data,TRUE);
            $this->sendmail->internalmail($sendto, $subject, $message);
            $eksmail    = explode(",",$ats->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $subject, $message);

        }
        $this->lpt->update_lpermit($where,$record);
        $others = explode(",", $other);
        $this->lpt->delete_lpermit_d($where);
//        nonaktif sementara
//        if ($others[0] != ""){
//            foreach ($others as $o){
//                $rec = array ("IDLeavePermit" => $idlpermit, "IDEmployee" => $o);
////                echo $o."|";
//                $this->lpt->insert_lpermit_d($rec);
//            }
//        }
        $msg = array("status" => "oke");
        echo json_encode($msg);
    }
    function delete_lpermit(){
        $idlpermit  = $this->input->post("idlpermit");
        //metode flag delete =====================
        $where      = array("ID" => $idlpermit);
        $record     = array(
            "DeleteFlag"    => "D",
            "DeletedBy"      => $this->iduser,
            "DeletedIP"      => $this->input->ip_address(),
            "DeletedDate"    => date('Y-m-d H:i:s')
            );
        $this->lpt->update_lpermit($where,$record);
        //end of flag delete ======================
        //metode delete permanen >>>>>>>>>>>>>>>>>>>
//        $this->lpt->delete_lpermit($where);
//        $this->lpt->delete_lpermit_d($where);
        //end of metode delete permanen >>>>>>>>>>>>>>>>
        $msg = array("status" => "oke");
        echo json_encode($msg);
    }
    function printlpermit($idlpermit){
        $where  = array("ID" => $idlpermit,"DeleteFlag" => "A");
        $data['data']   = $this->lpt->get_leavepermit($where);
        $nmfile = "Leave_Permit.pdf";
        $isi    = $this->load->view('trx02/print',$data,TRUE);
//        echo $isi;
        $this->mpdf=new mPDF('c','A4','','',7,7,7,7,0,0);
        $this->mpdf->WriteHTML($isi);
        return $this->mpdf->Output($nmfile,'I');
//        $data['exc'] = base_url().$nmfile;
//        $html = $data['exc'];
    }
    function notification(){
        $where      = "ConfirmFlag = '0' AND DeleteFlag = 'A' AND IDEmployee IN (SELECT isib_employee.m01personal.IDEmployee FROM isib_employee.m01personal WHERE isib_employee.m01personal.IDEmployeeParent = '$this->iduser')";
        $data['lpermits']   = $this->lpt->get_leavepermit($where);
        $this->load->view("trx02/notifications",$data);
    }
    function captcha(){
        $data['idlpermit']  = $this->input->post('idlpermit');
        $this->load->view("trx02/confirm",$data);
    }
    function confirm(){
        $idlpermit  = $this->anti_xss($this->input->post('idlpermit'));
        $stat       = $this->anti_xss($this->input->post('stat'));
        $reason     = $this->anti_xss($this->input->post('reason'));
        $record     = array("ConfirmFlag" => $stat, "ConfirmDate" => date('Y-m-d H:i:s'), "ConfirmIP" => $this->input->ip_address(), "ConfirmBy" => $this->iduser,"RejectReason" => $reason);
        $where      = array("ID" => $idlpermit);
        $this->lpt->update_lpermit($where,$record);

        $lpermit    = $this->lpt->get_leavepermit($where)->row();
        $prs    = $this->lpt->get_prs_public($lpermit->IDEmployee)->row();
        $data['state']  = "status";
        $data['confirm']= $stat;
        $data['lpermit']= $lpermit;
        $data['receivername']= $prs->FullName;
        $sendto     = $prs->InternalEmail;
        $subject    = "TIS Notification - Leave Permit";
        $message    = $this->load->view("trx02/email",$data,TRUE);
        $this->sendmail->internalmail($sendto, $subject, $message);
        $eksmail    = explode(",", $prs->ExternalEmail);
        $this->sendmail->externalmail($eksmail, $subject, $message);

//        $msg        = array("status" => "");
    }



   }

