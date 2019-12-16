<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('presence_model', 'presence');
	$this->load->model('uac_model', 'uac');


        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {

        $data['default']['f01'] = '';
        $data['default']['f02'] = '';
	$idmenu                 = "92";
        $data['buttons']        = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('proc02/home', $data);
    }

    function movingpresence() {
        $this->form_validation->set_rules('f01', 'Tanggal di liburkan', 'required');
        $this->form_validation->set_rules('f02', 'Tanggal pengganti', 'required');
        if ($this->form_validation->run() == TRUE) {
            $tanggaldiliburkan = date('Y-m-d', strtotime($this->input->post('f01')));
            $tanggalpengganti = date('Y-m-d', strtotime($this->input->post('f02')));
            $this->create_presence($tanggaldiliburkan, $tanggalpengganti);
            $mesg = 'Process Success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
        }
        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '",
                           "err_f01":"' . $err_f01 . '",                       
                           "err_f02":"' . $err_f02 . '"' .
                '}';

        echo $json;
    }

    function create_presence($tanggaldiliburkan, $tanggalpengganti) {
        $result = $this->presence->get_date($tanggalpengganti);
        $check = ($result == "" or $result == null) ? 'empty' : 'exist';
        if ($check == 'exist') {
            foreach ($result as $row) {
                $nip = $row['IDEmployee'];
                $ActualIn = $row['ActualIn'];
                $ActualOut = $row['ActualOut'];
                $Description = $row['Description'];
                $note = 'Data Absensi pada tanggal ' . $tanggalpengganti . ' di pindahkan ke tanggal ' . $tanggaldiliburkan;

                $record = array(
                    "ManualIn" => $ActualIn,
                    "ManualOut" => $ActualOut,
                    "Description" => $Description,
                    "Note" => $note,
                    "AddedBy" => $this->User,
                    "AddedDate" => $this->Datetime,
                    "AddedIP" => $this->Ip
                );
                $this->presence->update_holiday($nip, $tanggaldiliburkan, $record);
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


