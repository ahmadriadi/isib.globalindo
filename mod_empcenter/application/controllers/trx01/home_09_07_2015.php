<?php
//LEAVE/ CUTI
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {
    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->model('personal_model','prs');
        $this->load->model('public_model','pbl');
        $this->load->model('leave_model','lvm');
	$this->load->model('param_model','param');
        $this->me   = $this->session->userdata('sess_userid');
        $this->inemail  = $this->session->userdata('sess_email');
        $this->exemail  = $this->session->userdata('sess_emaileks');
    }
    function get_accepted($state){
        $accepted   = $this->lvm->get_accepted($this->me,$state);
        echo $accepted;
    }
	function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }


    function check_tgl(){
        $cektglfr = $this->input->post('from');
        $cektglut = $this->input->post('until');
        
//        $today  = date("Y-m-d");
        $wh = array(
//            "TglCutiDari >=" => $today,
            "IDEmployee"    => $this->me,
            "DeleteFlag"    => "A"
        );
        $tgls   = $this->lvm->getleave($wh);
        $o  = 0;
        foreach ($tgls->result() as $t){            
            $diff   = $this->selisihtgl($t->TglCutiDari,$t->TglCutiSampai);
//            echo $t->TglCutiDari." <=> ".$t->TglCutiSampai." >> $diff<br>";
            for($i = 0; $i<= $diff;$i++){
                $o++;
                $dates[$o]  = date('Y-m-d',  strtotime("$t->TglCutiDari +$i days"));
//                echo "===>".$dates['a'.$o]."<br>";
            }
        }
//        echo "<hr>";
//        echo $tgls->num_rows()."<br>";
//        print_r($dates);
//        echo "<hr>";
        if (in_array($cektgl, $dates)){
            echo "ada";
        }else{
            echo "tidak ada";
        }
    }
    function selisihtgl($tgl1,$tgl2){
        $a  = date_create($tgl1);
        $b  = date_create($tgl2);
        $i  = date_diff($a, $b);
        $diff   = $i->format('%a');
        return $diff;
    }
    function index(){
        $userid = $this->session->userdata('sess_userid');
        $i = 0;
        $personal       = $this->prs->get_data($userid);
        $libur		= $this->lvm->get_holiday()->result_array();
        foreach($libur as $li){
			$libss[] = $li['Date'];
		}
        $data['libur']      = $libss;
        $data['nama']       = $personal->FullName;
        $data['jabatan']    = $personal->IDUnitGroup;
        $data['bagian']     = $this->lvm->get_depart(array("IDStructure" => $personal->IDDepartement))->row()->DescStructure;
        $data['tglmasuk']   = date('d-m-Y',strtotime($personal->HireDate));
        $data['userid']     = $userid;
            $date1	=date_create($data['tglmasuk']);
            $date2	=date_create(date('Y-m-d'));
            $diff	=date_diff($date1,$date2);
        $data['lama']       = $diff->format("%y");
        $terakhir           = $this->lvm->get_tglterakhir($userid)->row()->Tanggal;  
        $data['utk'] 		= $this->sisacuti($userid,$personal->HireDate);
        $pengganti 		= $this->lvm->get_pengganti(NULL,$userid)->result();		
        $data['pengganti']	=  json_encode($pengganti);
        $data['trx']		= $this->lvm->get_transaksi_all($userid);

        $rowcuti = $this->lvm->get_mastercuti($userid);
        $fromdate = $rowcuti->TglMaster;
        $untildate = date('Y-m-d', strtotime("+12 month", strtotime($rowcuti->TglMaster)));
        $data['checkald']       = $this->lvm->checkald_on_thisyears($this->me,$fromdate,$untildate);


        $this->load->view('trx01/home',$data);
    }

   function sisacuti($user,$hire){
        $leave              = $this->lvm->get_data($user,$hire,date('Y-m-d'));
        if ($leave == NULL) { $jmlna = 0;}
        if ($leave != NULL){
                foreach($leave as $l){
                        $i++;
                        $jml[$i] = $l->Jml;
                }
                $jmlna          = array_sum($jml);
        }

	return $jmlna;	
	
   }		
   

    function add_process(){
        $userid 	= $this->session->userdata('sess_userid');
        $sickletter     = $this->anti_xss($this->input->post("sickletter"));
        $jenis		= $this->anti_xss($this->input->post("jenis"));
        $concl          = $this->anti_xss($this->input->post("concl"));
        $conmrl         = $this->anti_xss($this->input->post("conmrl"));
        $conmtl         = $this->anti_xss($this->input->post("conmtl"));
        if ($jenis == "CL"){
            $conmrl = NULL;
            $conmtl = NULL;
            $sickletter = 0;
        }
        if ($jenis == "MRL"){
            $concl  = NULL;
            $conmtl = NULL;
            $sickletter = 0;
        }
        if ($jenis == "MTL"){
            $concl  = NULL;
            $conmrl = NULL;
            $sickletter = 0;
        }
        if ($jenis == "SL"){
            $concl  = NULL;
            $conmrl = NULL;
            $conmtl = NULL;
        }
        if ($jenis == "AL"){
            $concl  = NULL;
            $conmrl = NULL;
            $conmtl = NULL;
            $sickletter = 0;
        }
        $alasan		= $this->anti_xss($this->input->post("alasan"));
        $dari		= date('Y-m-d',strtotime($this->input->post("dari")));
        $sampai		= date('Y-m-d',strtotime($this->input->post("sampai")));
        $total		= $this->anti_xss($this->input->post("total"));
        $sisa           = $this->anti_xss($this->input->post("sisa"));
        $idpengganti    = $this->anti_xss($this->input->post("idpengganti"));
        $idpengaju	= $this->session->userdata("sess_userid");
        $tglpengajuan   = date("Y-m-d");
        $ckode          = $userid.$tglpengajuan.mt_rand();
        if ($idpengganti == ""){
            $fpgt       = "true";
            $datefpgt   = date("Y-m-d");
        }else{
            $fpgt       = "false";
            $datefpgt   = NULL;
        }
        $record		= array(
                            "IDEmployee" 	=> $userid,
                            "Jenis"		=> $jenis,
                            "SickLetter"        => $sickletter,
                            "Alasan"		=> $alasan,
                            "TglCutiDari"	=> $dari,
                            "TglCutiSampai"	=> $sampai,
                            "TotalCuti"		=> $total,
                            "SisaCuti"          => $sisa,
                            "IDPengganti"	=> $idpengganti,
                            "IDPengaju"		=> $idpengaju,
                            "TglPengajuan"	=> $tglpengajuan,
                            "FPgt"              => $fpgt,
                            "FPgt_tgl"          => $datefpgt,
                            "FAts"              => "false",
                            "FHrd"              => "false",
                            "Ckode"             => $ckode,
                            "ConCL"             => $concl,
                            "ConMRL"            => $conmrl,
                            "ConMTL"            => $conmtl,
                            "AddedDate"         => date('Y-m-d H:i:s'),
                            "AddedBy"           => $this->me,
                            "AddedIP"           => $this->input->ip_address()
                        );
        $where      = array("IDEmployee" => $userid, "TglPengajuan" => date('Y-m-d'), "DeleteFlag" => "A", "Jenis"  => $jenis);
        $cek        = $this->lvm->get_transaksi($where)->row();        
        
        if ($cek == NULL){
            $this->lvm->insert($record);

            $where          = array("IDEmployee" => $userid, "TglPengajuan" => date('Y-m-d'), "DeleteFlag" => "A");
            $leave          = $this->lvm->get_transaksi($where)->row();
            $dataemail['userid']= $userid;
            $data['state']      = "confirm";
            $data['step']       = "pgt";
            $prs    = $this->lvm->get_employee($userid)->row();
            $pgt    = $this->lvm->get_prs_public($idpengganti)->row();
            $data['leave']  = $leave;
            $data['sendername'] = $prs->FullName;
            $data['receivername'] = $pgt->FullName;
            
            switch ($leave->Jenis){
                case "SL"   : $ttljenis = "[Sick Leave]"; break;
                case "AL"   : $ttljenis = "[Annual Leave]"; break;
                case "MRL"  : $ttljenis = "[Marriage Leave]"; break;
                case "MTL"  : $ttljenis = "[Maternity Leave]"; break;
                case "CL"   : $ttljenis = "[Condolence Leave]"; break;
                case "OL"   : $ttljenis = "[Unpaid Leave]"; break;
                case "CIR"  : $ttljenis = "[Circumcision Leave]"; break;
            }
            $data['ttljenis']   = $ttljenis;
            $sendto     = $pgt->InternalEmail;
            $subject    =   "TIS Notification - Leave Request $ttljenis";
            $msg        = $this->load->view('trx01/email',$data, TRUE);
            // get manajernya pengaju
            $empl	= $this->pbl->get_job($userid)->row();
            $whmgr['Department']	= $empl->Department;
            $whmgr['Position']		= "MANAGER";
            $MGR	= $this->pbl->get_job_wharray($whmgr)->row();
            $MGR	= $this->pbl->get_employee($MGR->IDEmployee);
            if ($MGR->num_rows() > 0){
				$MGR	= $MGR->row();
			}
			else{
				$MGR->InternalEmail = "oki@tis.loc";
				$MGR->ExternalEmail	= "triasdumb@gmail.com";
			}
            // get atasannya pengaju
            $ATS	= $this->pbl->get_employee($prs->IDEmployeeParent);
            if ($ATS->num_rows() > 0){
				$ATS	= $ATS->row();
			}
			else{
				$ATS->InternalEmail = "oki@tis.loc";
				$ATS->ExternalEmail	= "triasdumb@gmail.com";
			}            
            $incc	= $this->inemail.",".$MGR->InternalEmail.",".$ATS->InternalEmail;
            $excc	= $this->exemail.",".$MGR->ExternalEmail.",".$ATS->ExternalEmail;
//            if ($sendto != "" OR $sendto != NULL){
                $this->sendmail->internalmail($sendto, $subject, $msg, $incc);
//                $einstat    = "oke";
//            }
//            if ($sendto == "" OR $sendto == NULL){
//                $eintstat  = "bad";
//            }
            
//            if ($pgt->ExternalEmail != "" OR $pgt->ExternalEmail != NULL){
//            
//            }
            
            $eksmail    = explode(",", $pgt->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $subject, $msg,$excc);
            
            $message = array(
                    "status" => "oke"
//                    "isi"    => $mailto.$subject.$message
                                );
        }
        else{
            $message = array(
                   "status" => "error"
                                );
        }
        echo json_encode($message);
    }
    function edit(){
        $idleave 	= $this->input->post('idleave');
        $userid 	= $this->session->userdata('sess_userid');
        $where		= array(
                            "IDLeave" 	=> $idleave,
                            "IDEmployee"=> $userid,
                            "DeleteFlag"=> "A"
                            );
        $result 	= $this->lvm->get_transaksi($where)->row();
        $alasan		= $result->Alasan;
        $jenis		= $result->Jenis;
        $sickletter     = $result->SickLetter;
        $concl          = $result->ConCL;
        $conmrl         = $result->ConMRL;
        $conmtl         = $result->ConMTL;
        $dari		= date('d-m-Y',strtotime($result->TglCutiDari));
        $sampai 	= date('d-m-Y',strtotime($result->TglCutiSampai));
        $total		= $result->TotalCuti;
        $idpengganti    = $result->IDPengganti;
        $tglpengajuan   = $result->TglPengajuan;
        $sisa		= $result->SisaCuti;
        $p 		= $this->lvm->get_pengganti($idpengganti)->row();
        $nmpengganti    = $p->FullName;
        $fpgt           = $result->FPgt;
        $json		= array(
                                "idleave"	=> $idleave,
                                "alasan" 	=> $alasan,
                                "dari"	=> $dari,
                                "sampai"	=> $sampai,
                                "total"	=> $total,
                                "idpengganti"	=> $idpengganti,
                                "nmpengganti"	=> $nmpengganti,
                                "jenis"	=> $jenis,
                                "sickletter"	=> $sickletter,
                                "tglpengajuan"=> $tglpengajuan,
                                "sisa"	=> $sisa,
                                "fpgt"  => $fpgt,
                                "concl" => $concl,
                                "conmrl"=> $conmrl,
                                "conmtl"=> $conmtl
                                  );
        echo json_encode($json);
    }
    function edit_process(){
        $userid 	= $this->session->userdata('sess_userid');
        $idleave	= $this->anti_xss($this->input->post("idleave"));
        $wh = array("IDLeave" => $idleave);
        $prev   = $this->lvm->get_transaksi($wh)->row();
        $jenis		= $this->anti_xss($this->input->post("jenis"));
        $sickletter     = $this->anti_xss($this->input->post("sickletter"));
        $concl          = $this->anti_xss($this->input->post("concl"));
        $conmrl         = $this->anti_xss($this->input->post("conmrl"));
        $conmtl         = $this->anti_xss($this->input->post("conmtl"));
        $incc       = $this->inemail;
        $excc       = $this->exemail;        
        if ($jenis == "CL"){
            $conmrl = NULL;
            $conmtl = NULL;
            $sickletter = 0;
        }
        if ($jenis == "MRL"){
            $concl  = NULL;
            $conmtl = NULL;
            $sickletter = 0;
        }
        if ($jenis == "SL"){
            $concl  = NULL;
            $conmrl = NULL;
            $conmtl = NULL;
        }
        if ($jenis == "MTL"){
            $concl  = NULL;
            $conmrl = NULL;
            $sickletter = 0;
        }
        $alasan		= $this->anti_xss($this->input->post("alasan"));
        $dari		= date('Y-m-d',strtotime($this->input->post("dari")));
        $sampai		= date('Y-m-d',strtotime($this->input->post("sampai")));
        $total		= $this->anti_xss($this->input->post("total"));
        $sisa           = $this->anti_xss($this->input->post("sisa"));
        $idpengganti    = $this->anti_xss($this->input->post("idpengganti"));
        $idpengaju	= $this->session->userdata("sess_userid");
        $where		= array(
                                "IDEmployee"  => $userid,
                                "IDLeave"     => $idleave
                          );
        $record		= array(
                                "Jenis"		=> $jenis,
                                "SickLetter"	=> $sickletter,
                                "Alasan"	=> $alasan,
                                "TglCutiDari"	=> $dari,
                                "TglCutiSampai"	=> $sampai,
                                "TotalCuti"	=> $total,
                                "SisaCuti"      => $sisa,
                                "IDPengganti"	=> $idpengganti,
                                "IDPengaju"	=> $idpengaju,
                                "ConCL"         => $concl,
                                "ConMRL"        => $conmrl,
                                "ConMTL"        => $conmtl
                          );
//        blok 1 satu dan blok 2 tidak boleh ditukar urutannya
//        blok 1
        if ($prev->IDPengganti != $idpengganti){
            $newpgt = array("FPgt" => "false", "FPgt_tgl" => NULL);
            $this->lvm->update($where,$newpgt);
        }
//        blok 2
        if ($idpengganti == ""){
            $fpgt       = "true";
            $datefpgt   = date("Y-m-d");
            $reflag     = array(
                "FPgt"              => $fpgt,
                "FPgt_tgl"          => $datefpgt,
            );
            $this->lvm->update($where,$reflag);
        }
        
        switch ($jenis){
            case "SL"   : $ttljenis = "[Sick Leave]"; break;
            case "AL"   : $ttljenis = "[Annual Leave]"; break;
            case "MRL"  : $ttljenis = "[Marriage Leave]"; break;
            case "MTL"  : $ttljenis = "[Maternity Leave]"; break;
            case "CL"   : $ttljenis = "[Condolence Leave]"; break;
            case "OL"   : $ttljenis = "[Unpaid Leave]"; break;
            case "CIR"  : $ttljenis = "[Circumcision Leave]"; break;
        }        
        $data['ttljenis']   = $ttljenis;
        $leave  = $this->lvm->get_transaksi($where)->row();
        if ($leave->FPgt == "rejected"){
            $record['FPgt'] = "false";
//      kirim email ke pengganti
            $data['state']      = "confirm";
            $data['step']       = "pgt";
            $prs    = $this->lvm->get_employee($userid)->row();
            $pgt    = $this->lvm->get_prs_public($idpengganti)->row();
            
            $data['sendername'] = $prs->FullName;
            $data['receivername'] = $pgt->FullName;
            
            $sendto     = $pgt->InternalEmail;
            $subject    =   "TIS Notification - Leave Request $ttljenis";
            $msg        = $this->load->view('trx01/email',$data, TRUE);
            
            $this->sendmail->internalmail($sendto, $subject, $msg, $incc);
            
            $eksmail    = explode(",", $pgt->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $subject, $msg, $excc);
        }
        if ($leave->FAts == "rejected"){
            $record['FAts'] = "false";
//            kirim email ke atasan
            $data['state']      = "confirm";
            $data['step']       = "ats";
            $prs    = $this->lvm->get_employee($userid)->row();
            $ats    = $this->lvm->get_prs_public($prs->IDEmployeeParent)->row();
            
            $data['sendername'] = $prs->FullName;
            $data['receivername'] = $ats->FullName;
            
            $sendto     = $ats->InternalEmail;
            $subject    =   "TIS Notification - Leave Request $ttljenis";
            $msg        = $this->load->view('trx01/email',$data, TRUE);
            
            $this->sendmail->internalmail($sendto, $subject, $msg, $incc);
            
            $eksmail    = explode(",", $ats->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $subject, $msg, $excc);
        }
        if ($leave->FHrd == "rejected"){
            $record['FHrd'] = "false";
//            kirim email ke hrd
            $prs    = $this->lvm->get_employee($leave->IDEmployee)->row();
            $par    = $this->lvm->get_param("IDHRD")->result();
            $par2   = $this->lvm->get_param("IDHRDMGR")->row();
            $mgrhrd = $this->lvm->get_prs_public($par2->ParamValue)->row();
            // dikirim satu per satu. jika petugas hrd lebih dari satu orang, maka manager hrd akan menerima email cc sebanyak jumlah petugas hrd
            foreach ($par as $p){
                $hrd    = $this->lvm->get_prs_public($p->ParamValue)->row();
                $ccin   = $mgrhrd->InternalEmail;
                $inccs  = array($ccin, $incc);
                $ccout  = explode(",",$mgrhrd->ExternalEmail);
                $ccout[]= $excc;
                $data['state']  = "confirm";
                $data['step']   = "hrd";
                $data['sendername'] = $prs->FullName;
                $data['receivername']= $hrd->FullName;
//                    kirim email ke email internal hrd
                $sendto = $hrd->InternalEmail;
                $subject= "TIS Notification - Leave Request $ttljenis";
                $msg    = $this->load->view("trx01/email",$data,TRUE);
                $this->sendmail->internalmail($sendto, $subject, $msg, $inccs);
//                    kirim email ke email eksternal hrd
                $eksmail    = explode(",", $hrd->ExternalEmail);
                $this->sendmail->externalmail($eksmail, $subject, $msg, $ccout);
            }
        }
//        $this->lvm->ins_hist($idleave);
        $this->lvm->update($where,$record);
        
        $message = array(
                           "status" => "oke"
                        );
        echo json_encode($message);		
    }
    function delete(){
        $idleave 	= $this->input->post('idleave');
        $userid 	= $this->session->userdata('sess_userid');
        $where		= array(
                            "IDLeave"       => $idleave,
                            "IDEmployee"    => $userid
                        );
        $record         = array (
                            "DeleteFlag"    => "D"
                        );
        $this->lvm->update($where,$record);
        $message        = array (
                            "status" => "oke"
                        );
        echo json_encode($message);
    }

function iframe($id) {
        $data['url_printdata'] = site_url('trx01/home/printdata/' . $id);
        $this->load->view('trx01/iframe', $data);
    }

function printdata($id) {
        $this->load->library('mpdf54/mpdf');
        $this->mpdf = new mPDF('c', array(216, 304), '12', 'dejavusans', 5, 5, 5, 5, 0, 0);
        $row = $this->lvm->get_by_id_request($id);
        $checkdata = ($row == '' or $row == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {

            $data['nip'] = $row->IDEmployee;
            $data['name'] = $row->Name;
            $data['position'] = $row->Position;
            $data['dept'] = $row->DescStructure;
            $data['parent'] = $row->Parent;
            $data['hiredate'] = date('d-m-Y', strtotime($row->HireDate));
            $data['amountleave'] =$this->sisacuti($row->IDEmployee,$row->HireDate);

            $type = $row->Jenis;
            if ($type == 'AL') {
                $data['type1'] = '<b>*</br>';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'MTL') {
                $data['type1'] = '';
                $data['type2'] = '<b>*</br>';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'MRL') {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '<b>*</br>';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'CL') {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '<b>*</br>';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'SL') {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '<b>*</br>';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'OL') {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '<b>*</br>';
                $data['type7'] = '';
                $data['type8'] = '';
            } else if ($type == 'CIR') {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '<b>*</br>';
                $data['type8'] = '';
            } else {
                $data['type1'] = '';
                $data['type2'] = '';
                $data['type3'] = '';
                $data['type4'] = '';
                $data['type5'] = '';
                $data['type6'] = '';
                $data['type7'] = '';
                $data['type8'] = '';
            }
            $rowhrd = $this->param->get_hrd();
            $rowhrm = $this->param->get_hrdmgr();	
            
            $data['fromdate'] = date('d-m-Y', strtotime($row->TglCutiDari));
            $data['untildate'] = date('d-m-Y', strtotime($row->TglCutiSampai));
            $data['sumleave'] = $row->TotalCuti." Days";
            $data['cutleave'] = $row->SisaCuti.'   ';
            $data['reasonleave'] = $row->Alasan;
            $data['pengganti'] = $row->Pengganti;
            $data['daterequest'] = date('d-m-Y', strtotime($row->TglPengajuan));
            $data['supervisor'] = '';
            $data['hrd'] = $rowhrd->FullName;
            $data['hrm'] = $rowhrm->FullName;
            $data['dateacccharge'] =$row->FPgt_tgl;
            $data['dateaccparent'] =$row->FAts_tgl;
            $data['dateacchrd'] =$row->FHrd_tgl; 

            $html = $this->load->view('trx01/printdata', $data, true);
            $this->mpdf->SetHTMLFooter('
              <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
              <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE d-m-Y}</span></td>
              <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
              <td width="33%" style="text-align: right; "></td>
              </tr></table>
              ');
            $this->output->set_output($html);
            $this->mpdf->WriteHTML($html);
            // $this->mpdf->WriteHTML('<pagebreak sheet-size=210 297;/>');
            set_time_limit(60);
            $this->mpdf->Output('leave.pdf', 'I');
        }
    }


// sementara -=-=-=-=-=-=-=-
    function load_confirm($ckode,$step,$userid,$via){
        $data['ckode']  = $ckode;
        $data['step']   = $step;
        $data['userid'] = $userid;
        $data['via']    = $via;
        $data['url']    = base_url()."mod_empcenter/index.php/trx01/home/confirm/true/$ckode/$step/$userid";
        $this->load->view("trx01/confirm",$data);
    }
    function check_confirm($stat,$ckode,$step,$userid){
        $data['stat']   = $stat;
        $data['ckode']  = $ckode;
        $data['step']   = $step;
        $data['userid'] = $userid;
        $where = array("Ckode" => $ckode, "DeleteFlag" => "A");
        $data['det']    = $this->lvm->get_transaksi($where)->row();
        $data['r']      =$this->lvm->get_employee($userid)->row();
//        $data['capimg'] = $this->create_cap();
//        $data['captext']= $this->session->userdata('captext');
        $this->load->view('trx01/reject',$data);
    }
    function cek_email(){
        $prs = $this->lvm->get_prs_public('0579120613')->row();
        
        $eksmail    = explode(",", $prs->ExternalEmail);
//        if ($this->sendmail->externalmail($eksmail, "kirim ke banyak", "nah ini berhasil", "okierie@yahoo.com")){
//            echo "berhasil";
//        }
    }
    function confirm($stat,$ckode,$step,$userid){
        $via    = $this->input->post('via');
        $where  = array("Ckode" => $ckode , "DeleteFlag"    => "A");
        $leave  = $this->lvm->get_transaksi($where)->row();
                //----- baru 25-05-2015 ================
        switch ($leave->Jenis){
            case "SL"   : $ttljenis = "[Sick Leave]"; break;
            case "AL"   : $ttljenis = "[Annual Leave]"; break;
            case "MRL"  : $ttljenis = "[Marriage Leave]"; break;
            case "MTL"  : $ttljenis = "[Maternity Leave]"; break;
            case "CL"   : $ttljenis = "[Condolence Leave]"; break;
            case "OL"   : $ttljenis = "[Unpaid Leave]"; break;
            case "CIR"  : $ttljenis = "[Circumcision Leave]"; break;
        }
        $data['ttljenis']   = $ttljenis;
        // ======================================================        
        if ($stat == "true"){
            if ($step == "1"){
//                $where  = array("Ckode" => $ckode , "DeleteFlag"    => "A");
                $record = array("FPgt"  => "true" , "FPgt_Tgl"      => date('Y-m-d'), "PgtID"   => $this->me); 
                $this->lvm->update($where,$record);
                
                // dari step 1, kirim email ke atasan
//                $leave  = $this->lvm->get_transaksi($where)->row();
                $prs    = $this->lvm->get_employee($leave->IDEmployee)->row();
                $ats    = $this->lvm->get_prs_public($prs->IDEmployeeParent)->row();
                $incc   = $prs->EmailInternal;
                $excc   = $prs->EmailExternal;
                $data['leave']  = $leave;
                $data['state']  = "confirm";
                $data['step']   = "ats";
                $data['sendername'] = $prs->FullName;
                $data['receivername']= $ats->FullName;
                $sendto = $ats->InternalEmail;
                

                $subject= "TIS Notification - Leave Request $ttljenis";
                
                $msg    = $this->load->view("trx01/email",$data,TRUE);
                $this->sendmail->internalmail($sendto, $subject, $msg,$incc);
                
                $eksmail    = explode(",", $ats->ExternalEmail);
                $this->sendmail->externalmail($eksmail, $subject, $msg, $excc);
            }
            if ($step == "2"){
//                $where = array("Ckode" => $ckode, "DeleteFlag" => "A");
                $record = array("FAts" => "true" , "FAts_Tgl" => date('Y-m-d'), "AtsID" => $this->me); 
                $this->lvm->update($where,$record);
             
                // dari step 2, kirim email ke hrd
//                $leave  = $this->lvm->get_transaksi($where)->row();
                $prs    = $this->lvm->get_employee($leave->IDEmployee)->row();
                $incc   = $prs->EmailInternal;
                $excc   = $prs->EmailExternal;                
                $par    = $this->lvm->get_param("IDHRD")->result();
                $par2   = $this->lvm->get_param("IDHRDMGR")->row();
                $mgrhrd = $this->lvm->get_prs_public($par2->ParamValue)->row();
                // dikirim satu per satu. jika hrd lebih dari satu orang, maka manager hrd akan menerima email cc sebanyak jumlah petugas di hrd
                foreach ($par as $p){
                    $hrd    = $this->lvm->get_prs_public($p->ParamValue)->row();
                    $ccin[] = $mgrhrd->InternalEmail;
                    $ccin[] = $incc; 
                    $ccout  = explode(",",$mgrhrd->ExternalEmail);
                    $ccout[]= $excc;
                    $data['leave']  = $leave;
                    $data['state']  = "confirm";
                    $data['step']   = "hrd";
                    $data['sendername'] = $prs->FullName;
                    $data['receivername']= $hrd->FullName;
//                    kirim email ke email internal hrd
                    $sendto = $hrd->InternalEmail;
                    $subject= "TIS Notification - Leave Request $ttljenis";
                    $msg    = $this->load->view("trx01/email",$data,TRUE);
                   $this->sendmail->internalmail($sendto, $subject, $msg, $ccin);
//                    kirim email ke email eksternal hrd
                    $eksmail    = explode(",", $hrd->ExternalEmail);
                    $this->sendmail->externalmail($eksmail, $subject, $msg, $ccout);
                }                
            }
            if ($step == "3"){
//                $where = array("Ckode" => $ckode, "DeleteFlag" => "A");
                $record = array("FHrd" => "true" , "FHrd_Tgl" => date('Y-m-d'), "HrdID" => $this->me); 
                $this->lvm->update($where,$record);
                //step 3 kirim email pemberitahuan ke pengaju
//                $leave  = $this->lvm->get_transaksi($where)->row();
                $prs    = $this->lvm->get_prs_public($leave->IDEmployee)->row();
                //update reserve==================================================================
                    $whres  = array(
                        "IDEmployee"    => $leave->IDEmployee,
                        "Status"        => "1"
                    );
                    $res = $this->lvm->get_res($whres)->row();
                    if ($res != NULL){
                        if ($leave->TotalCuti < $res->JmlReserve){
                            $newjml = ($res->JmlReserve)-$leave->TotalCuti;
                        }
                        if ($leave->TotalCuti >= $res->JmlReserve){
                            $newjml = 0;
                        }
                        $recupdres = array("JmlReserve" => $newjml);
                        $this->lvm->upd_res($whres,$recupdres);
                    }
//                    ===================================================================================
                $data['leave']  = $leave;
                $data['state']  = "accept";
                $data['step']   = "hrd";
                $data['receivername']   = $prs->FullName;
                $data['tglreq'] = $leave->TglPengajuan;
                $sendto = $prs->InternalEmail;
                $subject= "TIS Notification - Leave Request $ttljenis";
                $msg    = $this->load->view("trx01/email",$data, TRUE);
                
                $this->sendmail->internalmail($sendto, $subject, $msg);
                $eksmail    = explode(",", $prs->ExternalEmail);
                $this->sendmail->externalmail($eksmail, $subject, $msg);
                }
            if ($step == "reject"){
                $rej_reason = $this->input->post("r_reason");
                $fromstep = $this->input->post('from_step');
//                $where = array("Ckode" => $ckode, "DeleteFlag" => "A");
                switch ($fromstep){
                    case "1" : $flag = "FPgt"; $st  = "pgt"; $flagtgl = "FPgt_Tgl";break;
                    case "2" : $flag = "FAts"; $st  = "ats"; $flagtgl = "FAts_Tgl";break;
                    case "3" : $flag = "FHrd"; $st  = "hrd"; $flagtgl = "FHrd_Tgl";break;
                }
                $record = array($flag => "rejected" , $flagtgl => date('Y-m-d') , "RejectReason" => $rej_reason, "RejectID" => $this->me); 
                $this->lvm->update($where,$record);
                //step reject
//                $leave  = $this->lvm->get_transaksi($where)->row();                
                $prs    = $this->lvm->get_prs_public($leave->IDEmployee)->row();
                
                $data['leave']  = $leave;
                $data['state']  = "reject";
                $data['step']   = $st;
                $data['receivername']= $prs->FullName;
                $data['tglreq'] = $leave->TglPengajuan;
                $sendto = $prs->InternalEmail;
                $subject= "TIS Notification - Leave Request $ttljenis";
                $msg    = $this->load->view("trx01/email",$data,TRUE);
               $this->sendmail->internalmail($sendto, $subject, $msg);
                
                $eksmail    = explode(",", $prs->ExternalEmail);
                $this->sendmail->externalmail($eksmail, $subject, $msg);                
            }
//            $message    = $this->load->view('trx01/appr_email',$dataemail,TRUE);
//            echo "ini alamat emailnya:".$mailto;
//            echo $message;
//            send-email temporary disabled
//           $this->sendmail->internalmail($mailto, $subject, $message);            
            if ($via != "web"){
                $this->session->set_userdata('accept',"<b>jangan reload halaman ini!</b><hr>Terima kasih atas konfirmasi Anda ");
                redirect('trx01/home/redir');                
            }
        }
        else if($stat == "false"){
            $this->session->set_userdata('data',$ckode."/".$step."/".$userid);
            if ($via != "web"){
                redirect('trx01/home/redir');                
            }
        }
        else{
            if ($via != "web"){
                redirect('trx01/home/redir');                
            }
        }
    }
    // redirect, sekali visit, hanya dari email
    function redir(){
        if($this->session->userdata('data') != NULL and $this->session->userdata('accept') == NULL){
            $datas = explode("/",$this->session->userdata('data'));       
            //print_r($datas);
            $ckode  = $datas[0];
            $step   = $datas[1];
            $userid = $datas[2];
            $where  = array("Ckode" => $ckode, "DeleteFlag" => "A");
            $data['r']      = $this->lvm->get_employee($userid)->row();
            $data['det']    = $this->lvm->get_transaksi($where)->row();
            $data['step']   = $step;
            $data['ckode']  = $ckode;
            $data['userid'] = $userid;
            $this->load->view('trx01/reject',$data);
            $this->session->unset_userdata('data');
        }
        else if($this->session->userdata('accept') != NULL and $this->session->userdata('data') == NULL){
            echo $this->session->userdata('accept');
            $this->session->unset_userdata('accept');
            
        }
        else{
            echo "Access Denied!";
        }
    }
    function notification($state){
        $userid = $this->session->userdata('sess_userid');
        if ($state == "pic"){
            $where  = array("IDPengganti" => $userid, "FPgt" => "false", "DeleteFlag" => "A");            
        }
        if ($state == "hod"){
            $where      = "FPgt = 'true' AND FAts = 'false' and DeleteFlag = 'A' and IDPengaju IN (SELECT IDEmployee FROM m01personal WHERE IDEmployeeParent = '$userid')";
        }
        if ($state == "hrd"){
            $where  = array("FPgt" => "true", "FAts" => "true", "FHrd" => "false", "DeleteFlag" => "A");            
        }
        $data['state']  = $state;
        $data['detail'] = $this->lvm->get_transaksi($where)->result();
//        print_r($data['detail']);
        $this->load->view('trx01/notifications',$data);
    }

}

/* End of file main.php */
/* Location: ./application/controllers/welcome.php */

