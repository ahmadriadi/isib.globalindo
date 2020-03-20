<?php
//TASKS
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->model('tasks_model', 'task');
        $this->load->model('reportation_model', 'rpt');
        $this->iduser = $this->session->userdata("sess_userid");
    }
    function index(){
        $this->load->view("trx03/home");
    }
    function notif_check(){
        
        $whrpt['AddedBy']   = $this->iduser;
        $whrpt['ViewFlag']  = "0";
        $whrpt['DeleteFlag']= "A";
        $report = $this->rpt->get_report($whrpt);
        $jml    = $report->num_rows();
        $i=0;
        $hsl    = array();
        foreach ($report->result() as $r){
            $hsl[$i]['idbug']       = $r->ID;
            $hsl[$i]['bugstatus']   = $r->StatusProblem;
            $hsl[$i]['needconf']    = $r->HoDConf;
            $hsl[$i]['statdate']    = $r->SolutionDate;
            $i++;
        }
        $msg['jml']     = $jml;
        $msg['hasil']   = $hsl;
        echo json_encode($msg);
    }
    function notif_discard(){
        $whrpt['AddedBy']   = $this->iduser;
        $whrpt['ViewFlag']  = "0";
        $rec['ViewFlag']    = "1";
        $this->rpt->upd_report($whrpt,$rec);
        $msg['status']  = "oke";
        echo json_encode($msg);
    }
    function tasks_check(){
        // get memo
        $where      = array("ToIDUser" => $this->iduser, "MemoStatus" => "0", "ConfirmFlag" => "1");
        $newmemo    = $this->task->get_memo_in($where)->num_rows();
        $newmemofeed= $this->task->get_memo_feed($this->iduser);
        $jmlfeed    = $newmemofeed->num_rows();
        // get memo conf
        $where      = "ConfirmFlag = '0' AND DeleteFlag = 'A' AND FromIDUser IN (SELECT IDEmployee FROM m01personal WHERE IDEmployeeParent = '$this->iduser')";
        $newmemocon = $this->task->get_memo($where);
//        print_r($newmemocon->result());
        $jmlmemocon = $newmemocon->num_rows();
        //get leave
        $where      = array("IDPengganti" => $this->iduser, "FPgt" => "false", "DeleteFlag" => "A");
        $leavepgt   = $this->task->get_leavereq($where);
        $where      = "FPgt = 'true' AND FAts = 'false' and DeleteFlag = 'A' and IDPengaju IN (SELECT IDEmployee FROM m01personal WHERE IDEmployeeParent = '$this->iduser')";
        $leaveats   = $this->task->get_leavereq($where);
        $where      = array("FPgt" => "true", "FAts" => "true", "FHrd" => "false", "DeleteFlag" => "A");
        $leavehrd   = $this->task->get_leavereq($where);
//        incomplete yang ada konfirmasi hrdnya didisable dulu
//        $where      = array("ConfirmFlag" => "1", "CHRDFlag"    => "0", "DeleteFlag" => "A");
//        $icphrd     = $this->task->get_incomplete($where);
        $ureport    = $this->task->get_hodconf($this->iduser);
        $where      = "ConfirmFlag = '0' AND DeleteFlag = 'A' AND IDEmployee IN (SELECT isib_employee.m01personal.IDEmployee FROM isib_employee.m01personal WHERE isib_employee.m01personal.IDEmployeeParent = '$this->iduser')";
        //get leave permit
        $lpermit    = $this->task->get_lpermit($where);
        //get official travel
        $offtravel  = $this->task->get_officialtravel($where);
        //get overtime
        $overtime   = $this->task->get_overtime($where);
        //get incomplete
        $incomplete = $this->task->get_incomplete($where);
        
     
        
        
//        echo "<br> new incoming memo = ".$newmemo."<br>";
//        echo "new memo feeds = ".$jmlfeed."<br>";
//        echo "leave request as pic = ".$leavepgt->num_rows()."<br>";
//        echo "leave request as ats = ".$leaveats->num_rows()."<br>";
//        echo "leave permit confirmation = ".$lpermit->num_rows()."<br>";
//        echo "official travel confirmation = ".$offtravel->num_rows()."<br>";
//        echo "overtime confirmation = ".$overtime->num_rows()."<br>";
//        echo "incomplete confirmation = ".$incomplete->num_rows()."<br><br>";
//        echo "total = ".$total;
//        
//        echo "<xmp>";
//        print_r($leaves);
//        echo "</xmp>";
//        0556220313 0091010601
        $whhrd  = "IDParam = 'IDHRD' OR IDParam = 'IDHRDMGR'";
        $hrd    = $this->task->get_hrd($whhrd)->result();
        $hrnya  = array();
        foreach ($hrd as $h){
            $hrnya[] = $h->ParamValue;
        }
        if (in_array($this->iduser,$hrnya)){
//            incomplete yang ada konfirmasi hrdnya didisable dulu
//            $total      = $newmemo+$jmlfeed+$jmlmemocon+$leavepgt->num_rows()+$leaveats->num_rows()+$leavehrd->num_rows()+$lpermit->num_rows()+$offtravel->num_rows()+$overtime->num_rows()+$incomplete->num_rows()+$icphrd->num_rows();
            $total      = $newmemo+$jmlfeed+$jmlmemocon+$leavepgt->num_rows()+$leaveats->num_rows()+$leavehrd->num_rows()+$lpermit->num_rows()+$offtravel->num_rows()+$overtime->num_rows()+$incomplete->num_rows()+$ureport->num_rows()+$digitalform;
            $msg = array (
                "inmemo"    => $newmemo,
                "memofeed"  => $jmlfeed,
                "memocon"   => $jmlmemocon,
                "leavepic"  => $leavepgt->num_rows(),
                "leaveats"  => $leaveats->num_rows(),
                "leavehrd"  => $leavehrd->num_rows(),
                "lpermit"   => $lpermit->num_rows(),
                "offtravel" => $offtravel->num_rows(),
                "overtime"  => $overtime->num_rows(),
                "incomplete"=> $incomplete->num_rows(),
                "ureport"   => $ureport->num_rows(),
//                "icphrd"    => $icphrd->num_rows(),                
                "total"     => $total
            );
            
        }else{
            $total      = $newmemo+$jmlfeed+$jmlmemocon+$leavepgt->num_rows()+$leaveats->num_rows()+$lpermit->num_rows()+$offtravel->num_rows()+$overtime->num_rows()+$incomplete->num_rows()+$ureport->num_rows();
            $msg = array (
                "inmemo"    => $newmemo,
                "memofeed"  => $jmlfeed,
                "memocon"   => $jmlmemocon,
                "leavepic"  => $leavepgt->num_rows(),
                "leaveats"  => $leaveats->num_rows(),
                "lpermit"   => $lpermit->num_rows(),
                "offtravel" => $offtravel->num_rows(),
                "overtime"  => $overtime->num_rows(),
                "incomplete"=> $incomplete->num_rows(),
                "ureport"   => $ureport->num_rows(),

                "total"     => $total
            );
            
        }
        echo json_encode($msg);
    }
    function tasks_user($iduser){
        // get memo
//        $where      = array("ToIDUser" => $iduser, "MemoStatus" => "0", "ConfirmFlag" => "1");
//        $newmemo    = $this->task->get_memo_in($where)->num_rows();
//        $newmemofeed= $this->task->get_memo_feed($iduser);
//        $jmlfeed    = $newmemofeed->num_rows();
//        // get memo conf
//        $where      = "ConfirmFlag = '0' AND DeleteFlag = 'A' AND FromIDUser IN (SELECT IDEmployee FROM m01personal WHERE IDEmployeeParent = '$iduser')";
//        $newmemocon = $this->task->get_memo($where);
////        print_r($newmemocon->result());
//        $jmlmemocon = $newmemocon->num_rows();
//        //get leave
//        $where      = array("IDPengganti" => $iduser, "FPgt" => "false", "DeleteFlag" => "A");
//        $leavepgt   = $this->task->get_leavereq($where);
//        $where      = "FPgt = 'true' AND FAts = 'false' and DeleteFlag = 'A' and IDPengaju IN (SELECT IDEmployee FROM m01personal WHERE IDEmployeeParent = '$iduser')";
//        $leaveats   = $this->task->get_leavereq($where);
//        $where      = array("FPgt" => "true", "FAts" => "true", "FHrd" => "false", "DeleteFlag" => "A");
//        $leavehrd   = $this->task->get_leavereq($where);
//        
//        $where      = "ConfirmFlag = '0' AND DeleteFlag = 'A' AND IDEmployee IN (SELECT isib_employee.m01personal.IDEmployee FROM isib_employee.m01personal WHERE isib_employee.m01personal.IDEmployeeParent = '$iduser')";
//        //get leave permit
//        $lpermit    = $this->task->get_lpermit($where);
//        //get official travel
//        $offtravel  = $this->task->get_officialtravel($where);
//        //get overtime
//        $overtime   = $this->task->get_overtime($where);
//        //get incomplete
//        $incomplete = $this->task->get_incomplete($where);
//        
////        echo "<br> new incoming memo = ".$newmemo."<br>";
////        echo "new memo feeds = ".$jmlfeed."<br>";
////        echo "leave request as pic = ".$leavepgt->num_rows()."<br>";
////        echo "leave request as ats = ".$leaveats->num_rows()."<br>";
////        echo "leave permit confirmation = ".$lpermit->num_rows()."<br>";
////        echo "official travel confirmation = ".$offtravel->num_rows()."<br>";
////        echo "overtime confirmation = ".$overtime->num_rows()."<br>";
////        echo "incomplete confirmation = ".$incomplete->num_rows()."<br><br>";
////        echo "total = ".$total;
////        
////        echo "<xmp>";
////        print_r($leaves);
////        echo "</xmp>";
////        0556220313 0091010601
//        $whhrd  = "IDParam = 'IDHRD' OR IDParam = 'IDHRDMGR'";
//        $hrd    = $this->task->get_hrd($whhrd)->result();
//        $hrnya  = array();
//        foreach ($hrd as $h){
//            $hrnya[] = $h->ParamValue;
//        }
//        if (in_array($iduser,$hrnya)){
//            $total      = $newmemo+$jmlfeed+$jmlmemocon+$leavepgt->num_rows()+$leaveats->num_rows()+$leavehrd->num_rows()+$lpermit->num_rows()+$offtravel->num_rows()+$overtime->num_rows()+$incomplete->num_rows();
//            $msg = array (
//                "inmemo"    => $newmemo,
//                "memofeed"  => $jmlfeed,
//                "memocon"   => $jmlmemocon,
//                "leavepic"  => $leavepgt->num_rows(),
//                "leaveats"  => $leaveats->num_rows(),
//                "leavehrd"  => $leavehrd->num_rows(),
//                "lpermit"   => $lpermit->num_rows(),
//                "offtravel" => $offtravel->num_rows(),
//                "overtime"  => $overtime->num_rows(),
//                "incomplete"=> $incomplete->num_rows(),
//                "total"     => $total
//            );
//            
//        }else{
//            $total      = $newmemo+$jmlfeed+$jmlmemocon+$leavepgt->num_rows()+$leaveats->num_rows()+$lpermit->num_rows()+$offtravel->num_rows()+$overtime->num_rows()+$incomplete->num_rows();            
//            $msg = array (
//                "inmemo"    => $newmemo,
//                "memofeed"  => $jmlfeed,
//                "memocon"   => $jmlmemocon,
//                "leavepic"  => $leavepgt->num_rows(),
//                "leaveats"  => $leaveats->num_rows(),
//                "lpermit"   => $lpermit->num_rows(),
//                "offtravel" => $offtravel->num_rows(),
//                "overtime"  => $overtime->num_rows(),
//                "incomplete"=> $incomplete->num_rows(),
//                "total"     => $total
//            );
//            
//        }
//        echo json_encode($msg);
        echo "disabled by administrator!";
    }
    function check_ureport(){
        $whpar['ParamValue']    = $this->iduser;
        $upar   = $this->task->get_upar($whpar)->result();
        $iu     = 0;
        $uprm   = Array();
        foreach ($upar as $up){
            $uprm[$iu]  = $up->IDParam;
            $iu++;
        }
        if (in_array("ITMGR", $uprm)){
            $whrpt['StatusProblem'] = '0';
            $whrpt['DeleteFlag'] = 'A';
//            $whrpt['HodConf'] = '1';
            $rpt    = $this->rpt->get_report($whrpt);
            $msg['jmlrpt']  = $rpt->num_rows();            
        }
        else if (in_array("ITOFCR", $uprm)){
            $whrpt['StatusProblem'] = '0';
            $whrpt['DeleteFlag'] = 'A';
//            $whrpt['HodConf'] = '1';
            $whrpt['PIC']   = $this->iduser;
            $rpt    = $this->rpt->get_report($whrpt);
            $msg['jmlrpt']  = $rpt->num_rows();
        }else{
            $msg['jmlrpt']  = 0;
        }
//        print_r($whrpt);

        echo json_encode($msg);
    }
}
