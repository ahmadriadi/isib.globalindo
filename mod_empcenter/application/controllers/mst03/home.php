<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->is_logged_in();
        $this->load->model('org_model','org');
        
    }
    function is_logged_in() {
        if ($this->session->userdata('sess_logged_in') != TRUE) {
            redirect('../../', 'refresh');
        }
    }
    function index (){
        $data['all']    = $this->org->getall()->result();
        
        //print_r( $this->org->getall()->result());
        $ni             = $this->org->maxid()->row()->max;
        $ni == NULL ? $data['nextid'] = '1' : $data['nextid'] = $ni+1;
        $data['units']  = json_encode($this->org->getdata()->result());
        //echo $data['nextid'].$data['units'];
        $this->load->view('mst03/home',$data);         
    }
    function add_process(){
        $userid     = $this->session->userdata("sess_userid");
        $idunit     = $this->input->post("idunit");
        $idparent   = $this->input->post("idparent");
        $descunit   = $this->input->post("descunit");
        $type       = $this->input->post("type");
        $level      = $this->input->post("level");
        $record     = array(
            "IDStructure"   => $idunit,
            "IDStructureParent" => $idparent,
            "DescStructure" => str_replace('&', '\&', $descunit),
            "RelType"       => $type,
            "Level"         => $level,
            "AddedBy"       => $userid,
            "AddedDate"     => date('Y-m-d H:i:s'),
            "AddedIP"       => $this->input->ip_address(),
            "DeleteFlag"    => "A"
        );
        if ($idparent != '0'){
            $cekparent  = $this->org->cek_parent($idparent)->row();
            $parentlevel= $cekparent->Level;
            if ($cekparent != NULL){
                if ($level > $parentlevel or $level == $parentlevel){
                    $this->org->insert($record);
                    $msg = array("status" => "oke");
                }
                else if ($level < $parentlevel){
                    $msg = array("status" => "bad", "msg" => "Level error! \n Please check the level you inserted");
                }
            }
            else{
                $msg = array("status" => "bad", "msg" => "Parent error! \n The parent unit you inserted doesn't exist");
            }            
        }
        else{
            $this->org->insert($record);
            $msg = array("status" => "oke");            
        }
        echo json_encode($msg);        
    }
    function edit(){
        $ids        = $this->input->post("ids");
        $data       = json_encode($this->org->getdata($ids)->row());
//        print_r($data);
        echo $data;
    }
    function edit_process(){
        $userid     = $this->session->userdata("sess_userid");
        $idunit     = $this->input->post("idunit");
        $idparent   = $this->input->post("idparent");
        $descunit   = $this->input->post("descunit");
        $type       = $this->input->post("type");
        $level      = $this->input->post("level");
        $where      = array("IDStructure" => $idunit);
        $record     = array(
            "IDStructureParent" => $idparent,
            "DescStructure"     => $descunit,
            "RelType"           => $type,
            "Level"             => $level,
            "EditedBy"          => $userid,
            "EditedDate"        => date('Y-m-d H:i:s'),
            "EditedIP"          => $this->input->ip_address()
        );
        if ($idparent != "0"){
            $cekparent  = $this->org->cek_parent($idparent)->row();
            $parentlevel= $cekparent->Level;
            if ($cekparent != NULL){
                    if ($level > $parentlevel or $level == $parentlevel){
                        $this->org->inshist($idunit);
                        $this->org->update($where,$record);
                        $msg = array("status" => "oke");
                    }                
                    else if ($level < $parentlevel){
                        $msg = array("status" => "bad", "msg" => "Level error! <br> Please check the level you inserted");
                    }
            }
            else{
                $msg = array("status" => "bad", "msg" => "Parent error! <br> The parent unit you inserted doesn't exist");
            }
        }else{
            $this->org->inshist($idunit);
            $this->org->update($where,$record);
            $msg = array("status" => "oke");
        }
        echo json_encode($msg);         
    }
    function delete(){
        $userid     = $this->session->userdata("sess_userid");
        $idunit     = $this->input->post('idunit');
        $where      = array("IDStructure" => $idunit);
        $record     = array(
            "DeleteFlag"=> "D", 
            "DeleteBy"  => $userid,
            "DeleteDate"=> date('Y-m-d H:i:s'),
            "DeleteIP"  => $this->input->ip_address()
            );
        $jml  = $this->org->cek_ids($idunit)->row()->jml;
        if ($jml == 0){
        $this->org->update($where,$record);
        $msg    = array(
            "status"    => "oke",
            "title"     => "Deleted!",
            "text"      => "The selected unit has been deleted"
            );
        }
        if ($jml > 0){
        $msg    = array(
            "status"    => "bad",
            "title"     => "Failed!",
            "text"      => "The selected unit can't be deleted"
            );        
        }
        echo json_encode($msg);
    }
    function chart($id){
 $base_url = $this->session->userdata('sess_base_url');
echo "<html>
<head>
	<!-- first include library files -->
        <script src=' $base_url/public/theme/scripts/plugins/system/jquery-latest.js'></script>
	<script type='text/javascript' src=' $base_url/public/theme/scripts/plugins/system/raphael-min.js'></script>  
	<script type='text/javascript' src=' $base_url/public/theme/scripts/plugins/system/lib_gg_orgchart_v043.js'></script>
	<script type='text/javascript' src=' $base_url/public/theme/scripts/plugins/system/jsrender.js'></script>
	<!--<script type='text/javascript' src=' $base_url/public/theme/scripts/plugins/jquery-1.9.0.min.js'></script>-->
        <!-- JQuery -->


	<!-- define some general styles -->
    <style>
	     body { 
                 font-family: arial;
                 background-image: url(' $base_url/public/avatar/photo/logotis.png');
                 background-size: contain;
             }
    </style>

	<!-- now define the organizational chart content and style -->
	<script type='text/javascript'>
		var oc_data_2 = {
			title : 'PT TRIAS INDRA SAPUTRA',   // not used
			root : {
        ";
        $data['gen']    = $this->gen();
        $this->load->view("mst03/chart".$id,$data);
    }
    function gen(){
        $all    = $this->org->getall()->result();
        foreach ($all as $a){
            if ($a->Level == '1' && $a->RelType == '0'){
                echo "id : '$a->IDStructure', title : '$a->DescStructure', children : [";
                foreach ($all as $b){
                    if ($b->IDParent == $a->IDStructure){
                        echo "{ id : '$b->IDStructure', title : '$b->DescStructure', "; 
                        echo $b->RelType == 'Co'? "type : 'collateral', " : "type : 'subordinate', ";
                        echo "children :[";
                        echo $this->gen_strc($b->IDStructure);
                        echo "]},";
                    }
                }
                echo "]";
            }  
        }
    }
    function gen_strc($idparent){
        $child   = $this->org->get_children($idparent)->result();
        foreach ($child as $a){
            echo "{ id : 'asik $a->IDStructure', title : '$a->DescStructure', "; 
            echo $a->RelType == 'Co'? "type : 'collateral', " : "type : 'subordinate', ";
            echo "children :[";
            $cek = $this->org->cek_ids($idparent)->result();
            if ($cek > 0){
            echo$this->gen_strc($a->IDStructure);
            }
            echo "]},";                                           
        }
    }
}

/* End of file main.php */
/* Location: ./application/controllers/welcome.php */
