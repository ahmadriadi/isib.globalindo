<?php
class Home extends CI_Controller{
    public function __construct() {
        parent::__construct();
        $this->load->model("reportation_model","rpt");
        $this->load->model("historytable_model","historytable");
        $this->iduser = $this->session->userdata("sess_userid");
        $this->ip   = $this->input->ip_address();
        
        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }
    function index(){
        $this->load->view("trx10/home");
    }
    function main(){
        $this->load->view("trx10/main");
    }
    function get_request_detail(){
        $idrpt  = $this->input->post("idrpt");
        $wh['CounterReq']   = $idrpt;
        $folacc     = $this->rpt->get_folacc($wh);
        $folcrt     = $this->rpt->get_folcrt($wh);
        $usrcrt     = $this->rpt->get_usrcrt($wh);
        $sofins     = $this->rpt->get_sofins($wh);
        $wha['NoCounter']   = $idrpt;
        $accusr     = $this->rpt->get_accusr($wha);
        echo "<style>.subdet{margin-left : 15px;}</style>";
        echo "<h4>List of Request</h4>";
        echo "<div class='row-fluid'>";
        if ($folacc->num_rows() > 0){
            echo "<div class='span6'>";
                $fa = $folacc->row();
                echo "<h5><u>Folder Access</u></h5>";
                echo "<div class='subdet'>";
                    echo "Folder name : ";
                    echo $fa->FolderAccess."<br>";
                    echo "Access Type : ";
                    $tacc   = $fa->AccessStatus == "0" ? "N/A" : ($fa->AccessStatus == "1" ? "Read Only" : "Read Write" );
                    echo $tacc."<br>";
                    echo "Note : ";
                    echo "<p>$fa->Note</p>";
                echo "</div>";
            echo "</div>";
            
        }
        if ($folcrt->num_rows() > 0){
            echo "<div class='span6'>";
                $fc = $folcrt->row();
                echo "<h5><u>Folder Create/Delete </u></h5>";
                echo "<div class='subdet'>";
                    echo "Folder name : ";
                    echo $fc->FolderName."<br>";
                    echo "Action : ";
                    $act   = $fc->FolderStatus == 0 ? "Delete folder" : "Create folder";
                    echo $act."<br>";
                    echo "Note : ";
                    echo "<p>$fc->Note</p>";
                echo "</div>";
            echo "</div>";
        }
        echo "</div>";
        echo "<div class='row-fluid'>";
        if ($usrcrt->num_rows() > 0){
            echo "<div class='span6'>";
                $uc = $usrcrt->row();
                echo "<h5><u>User Create/Ban </u></h5>";
                echo "<div class='subdet'>";
                    echo "User ID : ";
                    echo $uc->UserID."<br>";
                    $act    = $uc->StatusUser == "1" ? "Create user" : "Ban user";
                    echo "Action : ";
                    echo $act."<br>";
                    echo "Internet Access : ";
                    $intacc = $uc->InternetStatus == "1" ? "Yes" :"No";
                    echo $intacc."<br>";
                    echo "Internal Email : ";
                    echo $uc->InternalEmail."<br>";
                    echo "External Email : ";
                    echo $uc->ExternalEmail."<br>";
                echo "</div>";
            echo "</div>";
        }
        if ($sofins->num_rows() > 0){
            echo "<div class='span6'>";
                $si = $sofins->row();
                echo "<h5><u>Software Installation/Uninstallation</u></h5>";
                echo "<div class='subdet'>";
                    echo "Software Name : ";
                    echo $si->SoftwareName."<br>";
                    echo "Action : ";
                    $act    = $si->SoftwareStatus == "1" ? "Install" : "Uninstall";
                    echo $act."<br>";
                    echo "Note : ";
                    echo "<p>$si->Note</p>";
                echo "</div>";
            echo "</div>";
        }
        echo "</div>";
//        if ($accusr->num_rows() > 0){
//            $au = $accusr->row();
//            echo "<h5><u></u></h5>";
//            
//        }
//        echo $accusr->num_rows()."<br>";
    }
    function notification(){
        $nots   = $this->rpt->get_hodconf($this->iduser);
//        print_r($nots->result());
        $data['reports']    = $nots;
        
        $this->load->view("trx10/notification",$data);
    }
    function load_confirm(){
        $idrpt  = $this->input->post("idrpt");
        $data['idrpt']  = $idrpt;
        $this->load->view("trx10/confirm",$data);
    }
    function confirm(){
        $conf   = $this->input->post("conf");
        $idrpt  = $this->input->post("idrpt");
        $rnote  = $this->input->post("rnote");
        
        $wh['ID']       = $idrpt;
        $rec['HoDConf'] = $conf;
        $rec['HoDConfDate'] = date("Y-m-d H:i:s");
        $rec['HoDConfBy']   = $this->iduser;
        $rec['HoDConfIP']   = $this->ip;
        $rec['RejectNote']  = $rnote;
        
        $this->historydata($idrpt);
        $this->rpt->upd_report($wh,$rec);
    }
    function get_data($status){
//        $status = $this->input->post("status");

        $whpar['ParamValue']    = $this->iduser;
        $upar   = $this->rpt->get_upar($whpar)->result();
        $iu     = 0;
        $uprm   = Array();
        foreach ($upar as $up){
            $uprm[$iu]  = $up->IDParam;
            $iu++;
        }
        if (in_array("ITMGR", $uprm) OR in_array("ITOFCR", $uprm)){
            // Status :
            // 0 = New, Needs response, waiting response
            // 1 = Solved
            // 2 = Suspended
            // 3 = Unsolved
            // 4 = In Progress
            // 
            //Query Clue:
            // T = t01rootcause, table, db = isib_public
            // R = r01rootcause, table db = isib_public
            // E = m01employee, table, db = isib_public
            $pic    = in_array("ITOFCR", $uprm) ? "AND T.PIC = '$this->iduser'" : "";
            $wh     = "T.StatusProblem = '$status' $pic AND T.DeleteFlag='A'";
            $hasil  = $this->rpt->get_report_dtb($wh);
            echo $hasil;
        }else{
            show_404();
        }

        
    }
    function loadrpt(){
        $type   = $this->input->post("type");
        $whpar['ParamValue']    = $this->iduser;
        $upar   = $this->rpt->get_upar($whpar)->result();
        $iu     = 0;
        $uprm   = Array();
        foreach ($upar as $up){
            $uprm[$iu]  = $up->IDParam;
            $iu++;
        }        
        $types  = array("new_rpt","solved_rpt","suspended_rpt","unsolved_rpt","inprog_rpt");
        $data["upar"]   = $uprm;
        $this->load->view("trx10/".$types[$type],$data);
    }
    function get_uparam(){
        $key    = $this->input->post('key');
        //E = m01personal | P = m04param
        $whpar  = "E.FullName LIKE '%$key%' AND (P.IDParam = 'ITMGR' OR P.IDParam = 'ITOFCR')";
        $hasil  = $this->rpt->get_itofcr($whpar);
        $i      = 0;
        $ret    = array();
        foreach ($hasil->result() as $h){
            $ret[$i]['label']   = $h->EName;
            $ret[$i]['idpic']   = $h->IDEmp;
            $i++;
        }
        echo json_encode($ret);
    }
    function set_cpic(){
        $idrpt  = $this->input->post("idrpt");
        $idpic  = $this->input->post("idpic");
        $action = $this->input->post("action");
        $whupd['ID']    = $idrpt;
        if ($action == "set"){
            $rec['PIC'] = $idpic;
        }else{
            $rec['PIC'] = "";
        }
        $this->historydata($idrpt);
        $this->rpt->upd_report($whupd,$rec);
        $msg['status']  = "oke";
        echo json_encode($msg);
    }
    function set_report(){
        $idrpt  = $this->input->post("idrpt");
        $action = $this->input->post("action");
        $snote  = $this->input->post("snote");
        $sdate  = $this->input->post("sdate");
        $spbl   = $this->input->post("spbl");
        
        $whupd['ID']    = $idrpt;

        if ($action == "solved") {
            $status = "1";
            $rec['SolvedBy'] = $this->User;
            $rec['SolvedDate'] = $this->Datetime;
            $rec['SolvedIP'] = $this->Ip;
        } else if ($action == "unsolved") {
            $status = "3";
            $rec['UnsolvedBy'] = $this->User;
            $rec['UnsolvedDate'] = $this->Datetime;
            $rec['UnsolvedIP'] = $this->Ip;
        } else if ($action == "suspended") {
            $status = "2";
            $rec['SuspendedBy'] = $this->User;
            $rec['SuspendedDate'] = $this->Datetime;
            $rec['SuspendedIP'] = $this->Ip;
        } else if ($action == "inprogress") {
            $status = "4";
            $rec['ProgressBy'] = $this->User;
            $rec['ProgressDate'] = $this->Datetime;
            $rec['ProgressIP'] = $this->Ip;
        }


//        echo $action;
        if ($action != "needconf"){
            $rec['ProblemNote']     = $spbl;
            $rec['SolutionNote']    = $snote;
            $rec['SolutionDate']    = $sdate.date(" H:i:s");
            $rec['StatusProblem']   = $status;
            $rec['ViewFlag']        = "0";
            

        }else{
            $rec['ProblemNote']     = $spbl;
            $rec['SolutionDate']    = $sdate.date(" H:i:s");
            $rec['HoDConf']         = "0";
            $rec['ViewFlag']        = "0";
        }

        $this->historydata($idrpt);
        $this->rpt->upd_report($whupd,$rec);
        
        //kirim email-----------------------
        $rpt    = $this->rpt->get_report($whupd)->row();
        $whemp['IDEmployee']    = $rpt->AddedBy;
        $user   = $this->rpt->get_employee($whemp)->row();
        $uemail = $user->InternalEmail;
        
        $isi    = "Dear $user->FullName, <br>";
        if ($action != "needconf"){
            $action = $action != "inprogress" ? "has been $action" : " is in progress";
            $akhir  = $action != "solved"   ? "Please be patient with the action we took" : "Please be pleasure to take advantage of our services.";
            $isi    = $isi."Your submitted report/request (<b>ref.#$idrpt</b>) $action. $akhir <br>";        
        }
        else{            
            $isi    = $isi."Your submitted report/request (<b>ref.#$idrpt</b>) needs your HoD's confirmation. We sent an email to your HoD to confirm your request/report.";
        }
        $isi    = $isi."<i>".date("Y-m-d H:i:s")."</i>";
        $this->load->library("sendmail");
        $this->sendmail->internalmail($uemail,"TIS System",$isi);
        //----------------------------------        
        $msg['status']  = "oke";
        echo json_encode($msg);        
    }
    function get_accepted(){
        $hasil  = $this->rpt->get_accepted($this->iduser);
        echo $hasil;
    }
    
    
    function historydata($id){
         $row = $this->rpt->getby_id_root($id);
         $record = array(
             "IDTable"=>$row->ID,
             "IDRoot"=>$row->IDRoot,
             "ComplainNote"=>$row->ComplainNote,
             "ComplainDate"=>$row->ComplainDate,
             "RootCause"=>$row->RootCause,
             "ProblemNote"=>$row->ProblemNote,
             "SolutionNote"=>$row->SolutionNote,
             "SolutionDate"=>$row->SolutionDate,
             "StatusProblem"=>$row->StatusProblem,
             "TypeProblem"=>$row->TypeProblem,
             "PIC"=>$row->PIC,
             "AddedBy"=>$row->AddedBy,
             "AddedDate"=>$row->AddedDate,
             "AddedIP"=>$row->AddedIP,
             "EditedBy"=>$row->EditedBy,
             "EditedDate"=>$row->EditedDate,
             "EditedIP"=>$row->EditedIP,
             "DeleteBy"=>$row->DeleteBy,
             "DeleteDate"=>$row->DeleteDate,
             "DeleteIP"=>$row->DeleteIP,
             "DeleteFlag"=>$row->DeleteFlag,
             "ViewFlag"=>$row->ViewFlag,
             "HoDConf"=>$row->HoDConf,
             "HoDConfDate"=>$row->HoDConfDate,
             "HodConfBy"=>$row->HodConfBy,
             "HodConfIP"=>$row->HodConfIP,
             "RejectNote"=>$row->RejectNote,
             "HistBy"=>$this->User,
             "HistDate"=>$this->Datetime,
             "HistIP"=>$this->Ip,
             
         );
         
         
         $this->historytable->insert_rootcause($record);
         
        
    }
    
}


