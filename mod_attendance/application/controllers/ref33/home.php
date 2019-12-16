<?php
//ATTENDANCE INFORMATION
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('attendance_info_model', 'attif');
	date_default_timezone_set("Asia/Jakarta");
        $this->iduser   = $this->session->userdata("sess_userid");
    }

    function index() {	
        echo $this->iduser;
        $a = $this->attif->get_data($this->iduser)->result();
        print_r($a);
    }
    function get_data(){
        $tgl    = $this->input->post("tgl");
        $a = $this->attif->get_data($this->iduser,$tgl)->result();
        echo json_encode($a);
    }
	function cek_data($tgl){
		//$tgl    = $this->input->post("tgl");
		$a = $this->attif->get_data($this->iduser,$tgl)->result();
		echo json_encode($a);	
	}
}
