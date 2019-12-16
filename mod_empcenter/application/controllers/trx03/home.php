<?php
//  MEMO
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->model("memo_model",'mmm');
        $this->iduser = $this->session->userdata("sess_userid");
    }

	
   function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }
	

    function index($state=NULL){
        $data['state']      = $state;
        $data['lastid']     = $this->mmm->get_lastid()->row()->IDMemo;
        $other  = $this->mmm->get_emp_suggest($this->iduser)->result();
        $data['suggest']    = json_encode($other);
        $data['in']         = $this->mmm->get_inbox($this->iduser);
        $data['out']        = $this->mmm->get_outbox($this->iduser);
        $this->load->view("trx03/home",$data);
    }
    function send_memo(){
//        $this->input->post("memoid");
        $lastid     = $this->mmm->get_lastid()->row()->IDMemo;
        $nextid     = ($lastid*1)+1;
        $memodate   = $this->anti_xss($this->input->post("memodate"));
        $text       = $this->input->post("text");
        $subject    = $this->input->post("subject");
        $to         = $this->input->post("to");
        $ccmemo     = $this->input->post("ccmemo");
        $user       = $this->mmm->get_personal($this->iduser)->row();
        if ($user->IDJobPosition == "MANAGER" OR $u->IDJobPosition == "DIRECTOR" OR $u->IDJobPosition == 'ASSISTANT DIRECTOR'){
            $con    = "1";
            $condate= date("Y-m-d H:i:s");
            $conip  = $this->input->ip_address();
            $conby  = "system";
        }
        else{
            $con    = "0";
            $condate= "0000-00-00";
            $conip  = "";
            $conby  = "";
        }
        $record     = array(
            "IDMemo"        => $nextid,
            "MemoDate"      => $memodate,
            "MemoSubject"   => $subject,
            "MemoText"      => $text,
            "ToIDUser"      => $to,
            "CC"            => $ccmemo,
            "FromIDUser"    => $this->iduser,
            "ConfirmFlag"   => $con,
            "ConfirmDate"   => $condate,
            "ConfirmIP"     => $conip,
            "ConfirmBy"     => $conby,
            "AddedDate"     => date('Y-m-d H:i:s'),
            "AddedBy"       => $this->iduser,
            "AddedIP"       => $this->input->ip_address()
        );
        //rename("../public/attachmen/file_");
        $this->mmm->send_memo($record);
        if ($user->IDJobPosition == "MANAGER" OR $u->IDJobPosition == "DIRECTOR" OR $u->IDJobPosition == 'ASSISTANT DIRECTOR'){

        }
        else{
            $ats    = $this->mmm->get_prs_public($user->IDEmployeeParent)->row();
            $sendto = $ats->InternalEmail;
            $subject    = "TIS Notification - Memo";
            $data['state']  = "confirm";
            $data['sendername'] = $user->FullName;
            $data['receivername']= $ats->FullName;
            $message    = $this->load->view("trx03/email",$data,TRUE);
            $eksmail    = explode(",", $ats->ExternalEmail);
            $this->sendmail->internalmail($sendto, $subject, $message);
            $this->sendmail->externalmail($eksmail, $subject, $message);
        }        
        $msg    = array ("status" => "oke", "idmemo" => $nextid);
        echo json_encode($msg);
    }
    function cek_email(){
        $data['state']  = "confirm";
        $data['sendername'] = 'pengirim';
        $data['receivername']= 'penerima';
        $this->load->view("trx03/email",$data,FALSE);
    }
    function send_feed(){
        $text   = $this->input->post("text");
        $idmemo = $this->input->post("idmemo");
        $ip     = $this->input->ip_address();
        $where  = array("IDMemo" => $idmemo);
        $lastc  = $this->mmm->get_lastfeedcount($where)->row()->Count;
        $rec    = array(
            "IDMemo"    => $idmemo,
            "FolUpdate" => $text,
            "Count"     => $lastc+1,
            "AddedDate" => date('Y-m-d H:i:s'),
            "AddedBy"   => $this->iduser,
            "AddedIP"   => $ip
        );
        $this->mmm->send_feed($rec);
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
    function get_memo($idmemo=NULL){
        $idmemo = $this->input->post("idmemo");
        $from   = $this->input->post("from");
        if ($from == "in"){
            $where  = array("IDMemo" => $idmemo);
            $rec    = array("MemoStatus" => "1");
            $this->mmm->update_memo($where,$rec);
        }
        else{}
        $data['memo']   = $this->mmm->get_memo($idmemo)->row();
        $data['att']    = $this->mmm->get_attachment($idmemo)->result();
        echo json_encode($data);
    }
    function get_folup($idmemo=NULL){
        $from   = $this->input->post("v");
        $idmemo = $this->input->post("idmemo");
        $where1 = array("IDMemo" => $idmemo , "DeleteFlag" => "A");
        $result = $this->mmm->get_folup($where1);
        $jml    = $result->num_rows();
        if ($from == "out"){
            foreach ($result->result() as $r){
                $max[]  = $r->Count;
                $read[] = $r->FolRead;
            }
            $re = array_sum($read);
            $max = max($max);
            if ($jml == $re){
                $where  = array("IDMemo" => $idmemo, "Count" => $max);
                $fol    = $this->mmm->get_folup($where)->result();
            }
            if ($jml > $re){
                $where  = array("IDMemo" => $idmemo, "FolRead" => "0");
                $fol    = $this->mmm->get_folup($where)->result();
            }
        }else{
            $where  = array("IDMemo" => $idmemo);
            $fol    = $this->mmm->get_folup($where)->result();
        }

        echo json_encode($fol);
//        echo $jml."|".$re."|".$max;
    }
    function upd_memo(){
        $idmemo     = $this->input->post("memoid");
//        $nextid     = $this->input->post("nextid");
        $memodate   = $this->input->post("memodate");
        $text       = $this->input->post("text");
        $subject    = $this->input->post("subject");
        $to         = $this->input->post("to");
        $ccmemo     = $this->input->post("ccmemo");
        $record     = array(
            "MemoDate"      => $memodate,
            "MemoSubject"   => $subject,
            "MemoText"      => $text,
            "ToIDUser"      => $to,
            "CC"            => $ccmemo,
            "FromIDUser"    => $this->iduser,
            "EditedDate"    => date('Y-m-d H:i:s'),
            "EditedBy"      => $this->iduser,
            "EditedIP"      => $this->input->ip_address(),
            "ConfirmFlag"   => 0
        );
        $where      = array("IDMemo"    => $idmemo);
        $memo   = $this->mmm->get_memo($idmemo)->row();
//         jika revisi atau memo yang diupdate adalah memo yang direject, maka kirim email pemberitahuan ke atasan
        if ($memo->ConfirmFlag == "2"){
            $data['state']      = "confirm";
            $data['sendername'] = $memo->FromName;
            $recvr= $this->mmm->get_prs_public($memo->FromIDParent)->row();
            $data['receivername']= $recvr->FullName;
            $sendto     = $recvr->InternalEmail;
            $esubject   = "TIS Notification - Memo";
            $message    = $this->load->view("trx03/email",$data,TRUE);
            $this->sendmail->internalmail($sendto, $esubject, $message);
            $eksmail    = explode(",", $recvr->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $esubject, $message);
            
        }
        // jika memo yang diupdate belum terkonfirmasi, maka tidak perlu mengirim pemberitahuan
        if ($memo->ConfirmFlag == "0"){
            
        }
        $this->mmm->update_memo($where,$record);
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
    function upd_feed(){
        $idmemo = $this->input->post("idmemo");
        $where  = array("IDMemo" => $idmemo);
        $rec    = array("FolRead" => "1");
        $this->mmm->update_feed($where,$rec);
        $msg    = array("status" => "oke");
        echo json_encode($msg);
    }
    function delete_memo(){
        $idmemo = $this->input->post("idmemo");
        $where  = array("IDMemo"    => $idmemo);
//        delete flag memo ==========================
        $record = array(
            "DeleteFlag" => "D",
            "DeletedBy"  => $this->iduser,
            "DeletedIP"  => $this->input->ip_address(),
            "DeletedDate"=> date('Y-m-d H:i:s')
        );
        $this->mmm->update_memo($where,$record);
//        end of delete flag memo ===================
        
//        delete memo permanen >>>>>>>>>>>>>>>>>>>>>>
//        $this->mmm->delete_memo($where);
//        $this->mmm->delete_feed($where);
//        end of delete memo permanen >>>>>>>>>>>>>>>
        $msg    = array("status" => "oke");
        echo json_encode($msg);        
    }
    function delete_feed(){
        
    }
    function update_feed(){
        
    }
    function print_memo($idmemo){
        $data['data'] = $this->mmm->get_memo($idmemo);
        $isi = $this->load->view("trx03/print",$data,TRUE);
        $this->mpdf = new mPDF;
        $this->mpdf->WriteHTML($isi);
        $this->mpdf->Output("Memo.pdf","I");
    }
    function upload($idmemo,$fileke,$of){

        $f = $_FILES['file'];
//        echo $f['name'];
//        echo $f['tmp_name'];
        $ftype  = $f['type'];
        $file   = explode(".", $f['name']);
        $filename = "file_$fileke"."_memo".$idmemo."_".$this->iduser.".".$file[1];
        if (move_uploaded_file($f['tmp_name'], "../public/attachment/$filename")){
            $record = array("IDMemo" => $idmemo,"NameOnDrive" => $filename, "NameOnWeb" => $f['name']);
            $this->mmm->insert_attachment($record);                
            $valid = "true";
        }else{
            $valid = "false";
        }
//        rename("../public/attachment/".$f['name'], "../public/attachment/");
//        echo $this->input->post->
//        print_r($f);
        $msg    = array(
            "valid" => $valid,
        );
        echo json_encode($msg);
    }
    function upload_multi(){
        $f = $_FILES['file'];
    }
    function del_upfile(){
        $idmemo = $this->input->post('idmemo');
        $idmemo = $idmemo != '' ? "idmemo".$idmemo : $idmemo;
        $fileke = $this->input->post('fileke');
        $filename = "file_$fileke"."_".$this->iduser."_".$idmemo;
        unlink("../public/attachment/".$filename);
        echo "berhasil";
    }
    
//    function tes($idmemo){
//        $where  = array("IDMemo" => $idmemo);
//        $a  = $this->mmm->get_lastfeedcount($where)->row()->Count;
//        echo $a.$this->input->ip_address();;
////        echo json_encode($a);
//    }
    function notification(){
//        echo "fafafaf";
        $where      = "ConfirmFlag = '0' AND DeleteFlag = 'A' AND FromIDUser IN (SELECT isib_employee.m01personal.IDEmployee FROM isib_employee.m01personal WHERE isib_employee.m01personal.IDEmployeeParent = '$this->iduser')";
        $data['memos']   = $this->mmm->get_memo_con($where);
//        print_r($data['memos']);
        $this->load->view("trx03/notifications",$data);
    }
    function captcha(){
        $data['idmemo']  = $this->input->post('idmemo');
        $this->load->view("trx03/confirm",$data);
    }
    function confirm(){
        $idmemo     = $this->input->post('idmemo');
        $stat       = $this->input->post('stat');
        $reason     = $this->input->post('reason');
        $record     = array("ConfirmFlag" => $stat, "ConfirmDate" => date('Y-m-d H:i:s'), "ConfirmIP" => $this->input->ip_address(), "ConfirmBy" => $this->iduser, "RejectReason" => $reason);
        $where      = array("IDMemo" => $idmemo);
        $this->mmm->update_memo($where,$record);
        $memo = $this->mmm->get_memo($idmemo)->row();
        $subject    = "TIS Notification - Memo";
        $data['state']  = "incoming";
        $data['confirm']= $stat;
        // kirim email pemberitahuan ke penerima memo (cc ke atasan dan + ke anggota divisi ybs jika ccmemo = 1) jika disetujui
        if ($stat == "1"){
            $data['sendername'] = $memo->FromName;
            $data['receivername']= $memo->ToName;
            $recvr= $this->mmm->get_prs_public($memo->ToID)->row();
            $sendto     = $recvr->InternalEmail;
            $message    = $this->load->view("trx03/email",$data,TRUE);
            $rc         = $this->mmm->get_personal($memo->ToID)->row();
            $atsrc      = $this->mmm->get_prs_public($rc->IDEmployeeParent)->row();
            $ccin   = array($atsrc->InternalEmail);
            $ccout  = explode(",", $atsrc->ExternalEmail);
            if ($memo->CC == "1"){
                $wh     = array("IDDepartement" => $rc->IDDepartement);
                $dvs    = $this->mmm->get_employee($wh)->result();
                foreach($dvs as $dv){
                    if ($dv->IDEmployee != $memo->ToID){
                        $dvm    = $this->mmm->get_prs_public($dv->IDEmployee)->row();
                        array_push($ccin, $dvm->InternalEmail);
                        foreach ( explode(",", $dvm->ExternalEmail) as $ex){
                            array_push($ccout, $ex);
                        }
                    }
                }
            }
            else{
            }

            $this->sendmail->internalmail($sendto, $subject, $message,$ccin);
            $eksmail    = explode(",", $recvr->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $subject, $message,$ccout);
//            echo "email internal penerima = ".$sendto;
//            echo "\nemail eksternal penerima = ";
//            print_r($eksmail);
//            echo "cc internal : ";
//            print_r($ccin);
//            echo "\n cc eksternal : ";
//            print_r($ccout);            
        }
        // kirim email pemberitahuan penolakan memo kepada pengirim memo
        if ($stat == "2"){
            $data['receivername'] = $memo->FromName;
            $recvr= $this->mmm->get_prs_public($memo->FromID)->row();
            $sendto     = $recvr->InternalEmail;
            $message    = $this->load->view("trx03/email",$data,TRUE);
            $this->sendmail->internalmail($sendto, $subject, $message);
            $eksmail    = explode(",", $recvr->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $subject, $message);
        }
//        $msg        = array("status" => "");
    }



    }


