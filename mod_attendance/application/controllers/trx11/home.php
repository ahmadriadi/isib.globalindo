<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->load->model("leave_master_model","mas");
        $this->me   = $this->session->userdata('sess_userid');
    }

    function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }

    function index(){
        $this->load->view('trx11/home');
    }
    function addition(){
        $data['employee']   = $this->mas->get_emp_suggest()->result();
        $data['addition']   = $this->mas->get_addition();        
        $this->load->view('trx11/addition',$data);
    }
    function deletion(){
//        $whemp  = "AND "; 
        $data['employee']   = $this->mas->get_emp_suggest()->result();
        $whalldel   = " AND A.RejectReason = 'sys_annual' AND A.Jenis = 'AL'";
        $data['deletion']   = $this->mas->get_deletion($whalldel);
        $this->load->view('trx11/deletion',$data);
    }
    function reserve(){
        $data['employee']   = $this->mas->get_emp_suggest()->result();
        $whallres           = "";
        $data['reserve']    = $this->mas->get_reserve($whallres);
        $this->load->view('trx11/reserve',$data);
    }
    function todayclr(){
        $month = $this->input->post('month');
        $whemp  = array(
            "MONTH(HireDate)"   => $month,
            "IDJobGroup"        => "ST",
            "Status"            => "A",
            );
        $thisyear   = date('Y');

        $emps  = $this->mas->get_employee($whemp)->result();
        if ($emps != NULL){
        $i = 0;
            foreach ($emps as $e){
                $hire = date('Y',  strtotime($e->HireDate));
                if (($thisyear - $hire) > 1 ){
                    $clrdate = date('Y')."-".date('m-d',strtotime($e->HireDate));                    
                    $whsclr = array( "IDEmployee" => $e->IDEmployee, "ClearanceDate" => $clrdate, "Status" => '0');
                    $clr    = $this->mas->get_clearance($whsclr);
//                    print_r($clr->row());
//                    echo "<hr>";
                    $empd['isi']=array();
                    if ($clr->row() != NULL){
                        $empd['isi'][$i]['idemp']   = $e->IDEmployee;
                        $empd['isi'][$i]['nmemp']   = $e->FullName;
                        $empd['isi'][$i]['mondate'] = date('M d',strtotime($e->HireDate));
                        $empd['isi'][$i]['status']  = $clr->num_rows();
                        $empd['isi'][$i]['jmlclr']  = $clr->row()->JmlReserve;
                        $i++;
                    }
                }
            }
            if ($i == 0){
                $empd['isi']=array();
            }            
        }
        if ($emps == NULL){
            $empd=array();
        }
        $empd['nmmonth'] = date('F', strtotime(date('Y')."-".$month."-20"));
        echo json_encode($empd);         
    }
    function todayres(){
        $month = $this->input->post('month');
        $whemp  = array(
            "MONTH(HireDate)"   => $month,
            "IDJobGroup"        => "ST",
            "Status"            => "A",
            );
        $thisyear   = date('Y');

        $emps  = $this->mas->get_employee($whemp)->result();
        if ($emps != NULL){
            $i = 0;
            foreach ($emps as $e){
                $hire = date('Y',  strtotime($e->HireDate));
                if (($thisyear - $hire) > 1 ){                    
                    $sampai = date('Y')."-".date('m-d',strtotime($e->HireDate));
                    $dari = date('Y-m-d',  strtotime($sampai.' -1 year'));
                    $whsres = "AND A.IDEmployee = '$e->IDEmployee'";
                    $jmlres = $this->cuti_ditolak($e->IDEmployee, $dari, $sampai);
                    $empd['isi']=array();
                    if ($jmlres > 0){
                        $empd['isi'][$i]['idemp']   = $e->IDEmployee;
                        $empd['isi'][$i]['nmemp']   = $e->FullName;
                        $empd['isi'][$i]['mondate'] = date('M d',strtotime($e->HireDate));
                        $empd['isi'][$i]['status']  = $this->mas->get_reserve($whsres)->num_rows();
                        $empd['isi'][$i]['jmlres']  = $jmlres;
                        $i++;
                    }
                }
            }
            if ($i == 0){
                $empd['isi']=array();
            }            
        }
        if ($emps == NULL){
            $empd=array();
        }
        $empd['nmmonth'] = date('F', strtotime(date('Y')."-".$month."-20"));
        echo json_encode($empd);         
    }

    function todaydel(){
        $month = $this->input->post('month');
        $whemp  = array(
            "MONTH(HireDate)"   => $month,
            "IDJobGroup"        => "ST",
            "Status"            => "A",
            );
        $thisyear   = date('Y');

        $emps  = $this->mas->get_employee($whemp)->result();
        if ($emps != NULL){
        $i = 0;
            foreach ($emps as $e){
                $hire = date('Y',  strtotime($e->HireDate));
                if (($thisyear - $hire) > 1 ){
                    $today  = date('Y')."-".date('m-d',  strtotime($e->HireDate));
                    $whsdel = "AND A.IDEmployee = '$e->IDEmployee' AND A.Jenis = 'AL' AND YEAR(A.TglPengajuan) = '".date('Y')."' AND A.RejectReason = 'sys_annual'";
                    $sisa   = $this->sisa_cuti($e->IDEmployee, $e->HireDate, $today);
                    
                    $sampai = date('Y')."-".date('m-d',strtotime($e->HireDate));
                    $dari   = date('Y-m-d',  strtotime($sampai.' -1 year'));                    
                    $jmlres = $this->cuti_ditolak($e->IDEmployee, $dari, $sampai);
                    $hangus = $sisa-$jmlres;
                    if ($hangus < 0){ $hangus = 0;}
                    $stat   = $this->mas->get_deletion($whsdel);
                    if ($stat->num_rows() > 0){
                        if ($stat->row()->TotalCuti < $hangus){
                            $st = 2;
                        }
                        if ($stat->row()->TotalCuti == $hangus){
                            $st = 1;
                        }
                        if ($stat->row()->TotalCuti > $hangus){
                            $st = 3;
                        }
                    }else{
                        $st = 0;
                    }
                    
//                    if ($hangus > 0){
                        $empd['isi'][$i]['idemp']   = $e->IDEmployee;
                        $empd['isi'][$i]['nmemp']   = $e->FullName;
                        $empd['isi'][$i]['mondate'] = date('M d',strtotime($e->HireDate));
                        $empd['isi'][$i]['status']  = $st;
                        $empd['isi'][$i]['sisa']    = $sisa;
                        $i++;
//                    }
                }
            }
            if ($i == 0){
                $empd['isi']=array();
            }
        }
        if ($emps == NULL){
            $empd['isi']=array();
        }
        $empd['nmmonth'] = date('F', strtotime(date('Y')."-".$month."-20"));
        echo json_encode($empd);        
    }
    function todayadd(){
        $month = $this->input->post('month');
        $whemp  = array(
            "MONTH(HireDate)"   => $month,
            "IDJobGroup"        => "ST",
            "Status"            => "A",
            );
        $thisyear = date('Y');
        $emps  = $this->mas->get_employee($whemp)->result();
        if ($emps != NULL){
        $i = 0;
            foreach ($emps as $e){
                
                $hire = date('Y',  strtotime($e->HireDate));
                if (($thisyear - $hire) > 0 ){
                    $whsadd = "AND A.IDEmployee = '$e->IDEmployee' AND YEAR(A.TglMaster) = '".date('Y')."' AND A.Keterangan = 'cuti tahunan'";
                    $empd['isi'][$i]['idemp']   = $e->IDEmployee;
                    $empd['isi'][$i]['nmemp']   = $e->FullName;
                    $empd['isi'][$i]['mondate'] = date('M d',strtotime($e->HireDate));
                    $empd['isi'][$i]['status']  = $this->mas->get_addition($whsadd)->num_rows();
                    $empd['isi'][$i]['addhari'] = "12";
                    $i++;
                }
            }
            if ($i == 0){
                $empd['isi']=array();
            }            
        }
        if ($emps == NULL){
            $empd=array();
        }
        $empd['nmmonth'] = date('F', strtotime(date('Y')."-".$month."-20"));
        echo json_encode($empd);
    }
    function addedit(){
        $idmas  = $this->input->post('idmas');
        $where  = "AND A.ID = '$idmas'";
        $res = json_encode($this->mas->get_addition($where)->row());
        echo $res;
    }
    function deledit(){
        $idleave    = $this->input->post('idleave');
        $where      = "AND A.IDLeave = '$idleave'";
        $res = json_encode($this->mas->get_deletion($where)->row());
        echo $res;
    }
    function resedit(){
        $idres      = $this->input->post('idres');
        $where      = "AND A.ID = '$idres'";
        $res    = json_encode($this->mas->get_reserve($where)->row());
        echo $res;
    }
    function addedit_process(){
        $idmas  = $this->anti_xss($this->input->post('idmas'));
        $idemp  = $this->anti_xss($this->input->post('idemp'));
        $nmemp  = $this->anti_xss($this->input->post('nmemp'));
        $jml    = $this->anti_xss($this->input->post('jml'));
        $note   = $this->anti_xss($this->input->post('note'));
        $masdate= $this->anti_xss($this->input->post('masdate'));
        $now    = date('Y-m-d H:i:s');
        $rec    = array(
            "IDEmployee"    => $idemp,
            "TglMaster"     => date('Y-m-d', strtotime($masdate)),
            "Jml"           => $jml,
            "Keterangan"    => $note,
            "EditedBy"      => $this->me,
            "EditedIP"      => $this->input->ip_address(),
            "EditedDate"    => $now,
        );
        $where  = array(
            "ID"    => $idmas
        );
        $edit    = $this->mas->upd_addition($where,$rec);
        if ($edit == "oke"){
            $msg['status']  = "oke";
            $msg['proses']  = 'edit';
            $msg['newrecord']   = array(
                "idmas" => $idmas,
                "idemp" => $idemp,
                "nmemp" => $nmemp,
                "masdate"   => $masdate,
                "jml"   => $jml,
                "note"  => $note
            );
        }else{
            $msg['status']  = "bad";
        }
        echo json_encode($msg);
    }
    function deledit_process(){        
        $idleave= $this->anti_xss($this->input->post('idleave'));
        $idemp  = $this->anti_xss($this->input->post('idemp'));
        $nmemp  = $this->anti_xss($this->input->post('nmemp'));
        $jmldel = $this->anti_xss($this->input->post('jmldel'));
        $note   = $this->anti_xss($this->input->post('note'));
        $datedel= $this->anti_xss($this->input->post('datedel'));
        $now    = date('Y-m-d H:i:s');
        $rec    = array(
            "IDEmployee"    => $idemp,
            "TglPengajuan"  => date('Y-m-d', strtotime($datedel)),
            "TotalCuti"     => $jmldel,
            "Alasan"        => $note,
            "EditedBy"      => $this->me,
            "EditedIP"      => $this->input->ip_address(),
            "EditedDate"    => $now,
        );
        $where  = array(
            "IDLeave"       => $idleave
        );
        $edit    = $this->mas->upd_deletion($where,$rec);
        if ($edit == "oke"){
            $msg['status']  = "oke";
            $msg['proses']  = 'edit';
            $msg['newrecord']   = array(
                "idleave"   => $idleave,
                "idemp"     => $idemp,
                "nmemp"     => $nmemp,
                "datedel"   => $datedel,
                "jmldel"    => $jmldel,
                "note"      => $note
            );
        }else{
            $msg['status']  = "bad";
        }
        echo json_encode($msg);
    }
    function resedit_process(){
        $idres  = $this->anti_xss($this->input->post('idres'));
        $idemp  = $this->anti_xss($this->input->post('idemp'));
        $nmemp  = $this->anti_xss($this->input->post('nmemp'));
        $jmlres = $this->anti_xss($this->input->post('jmlres'));
        $note   = $this->anti_xss($this->input->post('note'));
        $dateres= $this->anti_xss($this->input->post('dateres'));
        $dateclr= date('Y-m-d', strtotime($dateres." +6 months"));
        $now    = date('Y-m-d H:i:s');
        $rec    = array(
            "IDEmployee"    => $idemp,
            "ReserveDate"   => date('Y-m-d', strtotime($dateres)),
            "ClearanceDate" => $dateclr,
            "JmlReserve"    => $jmlres,
            "JmlDef"        => $jmlres,
            "Note"          => $note,
            "EditedBy"      => $this->me,
            "EditedIP"      => $this->input->ip_address(),
            "EditedDate"    => $now,
        );
        $where  = array(
            "ID"       => $idres
        );
        $edit    = $this->mas->upd_reservation($where,$rec);
        if ($edit == "oke"){
            $msg['status']  = "oke";
            $msg['proses']  = 'edit';
            $msg['newrecord']   = array(
                "idres"     => $idres,
                "idemp"     => $idemp,
                "nmemp"     => $nmemp,
                "dateres"   => $dateres,
                "dateclr"   => $dateclr,
                "jmlres"    => $jmlres,
                "jmldef"    => $jmlres,
                "note"      => $note
            );
        }else{
            $msg['status']  = "bad";
        }
        echo json_encode($msg);
    }
    function addadd_process(){
        $idemp  = $this->anti_xss($this->input->post('idemp'));
        $jml    = $this->anti_xss($this->input->post('jml'));
        $note   = $this->anti_xss($this->input->post('note'));
        $masdate= $this->anti_xss($this->input->post('masdate'));
        $now    = date('Y-m-d H:i:s');
        $rec    = array(
            "IDEmployee"    => $idemp,
            "TglMaster"     => date('Y-m-d',  strtotime($masdate)),
            "Jml"           => $jml,
            "Keterangan"    => $note,
            "AddedBy"       => $this->me,
            "AddedIP"       => $this->input->ip_address(),
            "AddedDate"     => $now,
        );
        $add = $this->mas->add_addition($rec);
        if ($add == "oke"){
            $msg['status']  = "oke";
            $msg['proses']  = "add";
    //        $msg['newrecord']  = array(
    //            
    //        );            
        }else{
            $msg['status']  = "bad";
        }
        echo json_encode($msg);
    }
    function deladd_process($idemp=NULL,$jmldel=NULL,$note=NULL,$datedel=NULL,$exec=NULL){
        if ($idemp == NULL and $jmldel == NULL and $note == NULL and $datedel == NULL){
            $idemp  = $this->anti_xss($this->input->post('idemp'));
            $jmldel = $this->anti_xss($this->input->post('jmldel'));
            $note   = $this->anti_xss($this->input->post('note'));
            $datedel= $this->anti_xss($this->input->post('datedel'));
        }
        $now    = date('Y-m-d H:i:s');
        $rec    = array(
            "IDEmployee"    => $idemp,
            "TglPengajuan"  => date('Y-m-d',  strtotime($datedel)),
            "TotalCuti"     => $jmldel,
            "Alasan"        => $note,
            "FlagInput"     => 'sys',
            "Jenis"         => "AL",
            "TglCutiDari"   => $now,
            "TglCutiSampai" => $now,
            "IDPengaju"     => $this->me,
            "FPgt"          => "true",
            "PgtID"         => "sys",
            "FPgt_tgl"      => $now,
            "FAts"          => "true",
            "AtsID"         => "sys",
            "FAts_tgl"      => $now,
            "FHrd"          => "true",
            "HrdID"         => "sys",
            "FHrd_tgl"      => $now,
            "RejectReason"  => "sys_annual",
            "AddedBy"       => $this->me,
            "AddedIP"       => $this->input->ip_address(),
            "AddedDate"     => $now,
        );
        $add = $this->mas->add_deletion($rec);
        if ($exec == NULL){
            if ($add == "oke"){
                $msg['status']  = "oke";
                $msg['proses']  = "add";
        //        $msg['newrecord']  = array(
        //            
        //        );            
            }else{
                $msg['status']  = "bad";
            }
            echo json_encode($msg);
        }
    }
    function resadd_process(){
        $idemp  = $this->anti_xss($this->input->post('idemp'));
        $jmlres = $this->anti_xss($this->input->post('jmlres'));
        $note   = $this->anti_xss($this->input->post('note'));
        $dateres= $this->anti_xss($this->input->post('dateres'));
        $now    = date('Y-m-d H:i:s');
        $rec    = array(
            "IDEmployee"    => $idemp,
            "ReserveDate"   => date('Y-m-d',  strtotime($dateres)),
            "ClearanceDate" => date('Y-m-d',  strtotime($dateres." +6 months")),
            "JmlReserve"    => $jmlres,
            "JmlDef"        => $jmlres,
            "Note"          => $note,
            "Status"        => "1",
            "AddedBy"       => $this->me,
            "AddedIP"       => $this->input->ip_address(),
            "AddedDate"     => $now,
        );
        $add = $this->mas->add_reservation($rec);
        if ($add == "oke"){
            $msg['status']  = "oke";
            $msg['proses']  = "add";
    //        $msg['newrecord']  = array(
    //            
    //        );            
        }else{
            $msg['status']  = "bad";
        }
        echo json_encode($msg);
    }
    function adddelete(){
        $idmas  = $this->input->post('idmas');
        $wh = array(
            "ID"    => $idmas
        );
        $rec    = array(
            "DeleteFlag" => "D",
            "DeletedBy"     => $this->me,
            "DeletedDate"   => date("Y-m-d H:i:s"),
            "DeletedIP"     => $this->input->ip_address(),
        );
        $del =  $this->mas->del_addition($wh,$rec);
        if ($del == "oke"){
            $msg['status']  = "oke";
            $msg['idmas']   = $idmas;
        }else{
            $msg['status']  = "bad";
        }
        echo json_encode($msg);
    }
    function deldelete(){
        $idleave    = $this->input->post('idleave');
        $wh = array(
            "IDLeave"    => $idleave
        );
        $rec    = array(
            "DeleteFlag"    => "D",
            "DeleteBy"     => $this->me,
            "DeleteDate"   => date("Y-m-d H:i:s"),
            "DeleteIP"     => $this->input->ip_address(),
        );
        $del =  $this->mas->del_deletion($wh,$rec);
        if ($del == "oke"){
            $msg['status']  = "oke";
            $msg['idleave']   = $idleave;
        }else{
            $msg['status']  = "bad";
        }
        echo json_encode($msg);
    }
    function resdelete(){
        $idres  = $this->input->post('idres');
        $res    = $this->mas->get_reserve(" AND A.ID = '$idres'")->row();
//        echo $res->IDEmployee;
        $note   = "Penghapusan sisa cuti yang berlaku setengah tahun";
        //hapus sisa cutinya
        $this->deladd_process($res->IDEmployee, $res->JmlReserve, $note, $res->ClearanceDate);
        $wh = array(
            "ID"    => $idres
        );
        $rec    = array(
            "Status"        => "0",
            "DeletedBy"     => $this->me,
            "DeletedDate"   => date("Y-m-d H:i:s"),
            "DeletedIP"     => $this->input->ip_address(),
        );
        //hapus reserve-nya
        $del =  $this->mas->del_reservation($wh,$rec);
        if ($del == "oke"){
            $msg['status']  = "oke";
            $msg['idleave']   = $idleave;
        }else{
            $msg['status']  = "bad";
        }
        echo json_encode($msg);
    }
    function sisa_cuti($idemp,$hiredate,$today){
        $sisa = $this->mas->sisa_cuti($idemp,$hiredate,$today)->result();
        if ($sisa != NULL){
            $i=0;
            foreach ($sisa as $s){
                $jml[$i] = $s->Jml;
                $i++;
            }
            $sisanya = array_sum($jml);
        }
        if ($sisa == NULL){
            $sisanya = 0;
        }
        return $sisanya;        
    }
    function cuti_ditolak($idemp,$dari,$today){
        $where  = "IDEmployee = '$idemp' AND Jenis='AL' AND DeleteFlag = 'A' AND (FAts = 'rejected' OR FHrd = 'rejected') AND (TglPengajuan BETWEEN '$dari' AND '$today')";
        $rej = $this->mas->cek_rejected($where)->result();
        $i = 0;
        if ($rej != NULL){
            foreach ($rej as $r){
                $jmlrej[$i] = $r->TotalCuti; 
//                echo "Tgl pengajuan = ".$r->TglPengajuan." Total Cuti".$r->TotalCuti." Jenis :".$r->Jenis."<br>";
                $i++;
            }
            $jmlreject = array_sum($jmlrej);
        }
        if ($rej == NULL){
            $jmlreject = 0;
        }        
        return $jmlreject;
    }    
}
