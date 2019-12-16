<?php
//Report : employee-untakenleave
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->model("report_model","rpt",TRUE);
    }
    function index(){
        $where  = array(
            "IDJobGroup"    => "ST"
        );
        $emp    = $this->rpt->get_employee($where,"yes")->result();
        $data['emp']    = json_encode($emp);
//        print_r($data['emp']);
        $this->load->view("rpt19/home",$data);
    }
    function get_data($idemp=NULL,$hdate=NULL,$ret=NULL,$sampai=NULL){
        if ($idemp == NULL and $hdate == NULL){
            $idemp  = $this->input->post("idemp");
            $hdate  = $this->input->post("hdate");            
        }
//        $skr    = "2014-07-26";
        if ($sampai == NULL){
            $skr    = date("Y-m-d");
        }else{
            $skr    = date("Y-m-d", strtotime($sampai));
        }
        $untaken    = $this->rpt->get_emp_leave($idemp,$hdate,$skr);
//        print_r($untaken);
//        echo "<hr>";
        $utk = array();
        $master = array();
        $trx    = array();
        $i=0;
        if ($untaken != "kosong"){
            foreach ($untaken as $u){
                $i++;
                if ($u->Ket == "master"){
                    $master[] = $u; 
                }
                if ($u->Ket == "trx"){
                    $trx[]    = $u;
                }
                $utk[$i]    = $u->Jml;
            }
        }
        $data['thisemp']= $this->rpt->get_employee(array("IDEmployee" => $idemp))->row();
        $data['utk']    = array_sum($utk);
        $data['all']   = $idemp == NULL ? "yes" : "no";
        if ($idemp == NULL){
            $data['allemp'] = $this->rpt->get_all_emp_annual($skr)->result();            
        }
        $data['leaves'] = $trx;
        $data['master'] = $master;
        if ($ret == NULL){
            echo json_encode($data);
        }else{
            return json_encode($data);
        }
    }
    function tes(){
        $skr    = date('Y-m-d');
        $a = $this->rpt->get_all_emp_annual($skr)->result();
        print_r($a);
    }
    function print_data($idemp,$range){
//        $idemp  = $this->input->post('idemp');
//        $range  = $this->input->post('printrange');
        $range  = explode("xxx", $range);
        $data = $this->get_data_toprint($idemp, $range[0], "yes", $range[1]);
        $data = json_decode($data);
        $pass['range']= date("d-m-Y",strtotime($range[0])) ." <i>upto</i> ". date("d-m-Y", strtotime($range[1]));
        $pass['data'] = $data;
        $isi = $this->load->view("rpt19/print",$pass, TRUE);
        $this->mpdf = new mPDF();
        $this->mpdf->WriteHTML($isi);
        $this->mpdf->Output("Memo.pdf","I");
    }
    function get_data_toprint($idemp=NULL,$hdate=NULL,$ret=NULL,$sampai=NULL){
        if ($idemp == NULL and $hdate == NULL){
            $idemp  = $this->input->post("idemp");
            $hdate  = $this->input->post("hdate");            
        }
//        $skr    = "2014-07-26";
        if ($sampai == NULL){
            $skr    = date("Y-m-d");
        }else{
            $skr    = date("Y-m-d", strtotime($sampai));
        }
        $untaken    = $this->rpt->get_emp_leave($idemp,$hdate,$skr);
//        print_r($untaken);
//        echo "<hr>";
        $utk = array();
        $master = array();
        $trx    = array();
        $i=0;
        if ($untaken != "kosong"){
            foreach ($untaken as $u){
                $i++;
                $tm = explode("-", date('Y-m-d',  strtotime($u->Tanggal)));
                $ts = explode("-", date('Y-m-d',  strtotime($sampai)));        
                $td = explode("-", date('Y-m-d',  strtotime($hdate)));        
                if ($u->Ket == "master"){
                    //echo $tm[0]." => ".$ts[0]." <br>";
                    if ($tm[0] < $ts[0]){
                        $master[] = $u;
                        $utk[$i]    = $u->Jml;
                    }
                }
                if ($u->Ket == "trx"){
                    if( ($tm[0] == $td[0]) AND ($u->Alasan == "PENGHAPUSAN SISA CUTI OLEH SISTEM")){
                    }else{
                        $trx[]    = $u;
                        $utk[$i]    = $u->Jml;
                    }
                }
                
                
            }
        }
        $data['thisemp']= $this->rpt->get_employee(array("IDEmployee" => $idemp))->row();
        $data['utk']    = array_sum($utk);
        $data['all']   = $idemp == NULL ? "yes" : "no";
        if ($idemp == NULL){
            $data['allemp'] = $this->rpt->get_all_emp_annual($skr)->result();            
        }
        $data['leaves'] = $trx;
        $data['master'] = $master;
//        echo "<xmp>";
//        print_r($master);
//        echo "</xmp>";
//        echo "<xmp>";
//        print_r($trx);
//        echo "</xmp>";
        if ($ret == NULL){
            echo json_encode($data);
        }else{
            return json_encode($data);
        }
    }    
    function print_opt(){
        $idemp  = $this->input->post('idemp');
        $hdate  = $this->input->post('hdate');
        
        $yearfrom   = date('Y',  strtotime($hdate));
        $blntgl     = date('-m-d',  strtotime($hdate));     
        $yearnow    = date('Y', strtotime(' +1 year'));
//        $yearnow    = 2017;
        
        if ($yearfrom <= 2013){
            $yearfrom = 2013;
        }
        
        $cyear  = $yearnow-$yearfrom;
        echo "<select id='printrange'>";
        for($i=1;$i <= $cyear; $i++){
            $jadi   = $yearfrom+$i;
            $fr     = $jadi-1;
            echo "<option value='".$fr.$blntgl."xxx".$jadi.$blntgl."' >$fr-$jadi</option>";
        }
        echo "</select>";
        echo '<span class="add-on btn btn-icon btn-success glyphicons print" onclick="print_rpt()" style="cursor: pointer; margin-top: -10px;"><i></i>Print Data</span>';
    }
}

