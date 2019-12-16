<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Processpresence_model', 'processpresence');
        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {

        $this->load->view('proc05/home');
    }

    function postingpresence() {
        //get data text from machine and insert to rawdata
        $resultunload = $this->unload_all_loaction();
        //echo ' Create Datatext for Rawdata, Done'.'<br/>';
	$post = $this->session->userdata('sess_number');
        $mesg = "Process create rawdata success with Post = " . $post . " Data";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                      "valid":"' . $valid . '"'
                .
                '}';

        echo $json;
    }

    function unload_all_loaction() {
        $path = "/tmp/rawdata.txt";       
        $this->post_unload($path);
        $this->finish_unload_all();       
    }

    function post_unload($path, $truncate = 0) {
        ini_set('memory_limit', '-1'); // for unlimited size     
        if ($truncate == 0) {
            $this->processpresence->truncatecardraw();
        }
        $rawdatamachine = fopen($path, 'rb');
        while (!feof($rawdatamachine)) {
            $line_of_text = fgets($rawdatamachine);
            $parts = explode('=', $line_of_text);
            $datatext = $parts[0];
            $record = array('DataText' => $datatext,
                'ProcessBy' => $this->User,
                'ProcessDate' => $this->Datetime,
                'ProcessIP' => $this->Ip);
            $this->processpresence->insert_cardraw($record);
        }
        fclose($rawdatamachine);
       
    }

    function finish_unload_all() {
        ini_set('memory_limit', '-1'); // for unlimited size  
        set_time_limit(0);
        $num = 0;
        $result = $this->processpresence->get_allcardraw();
        foreach ($result as $row) {
            $buffer = trim($row->DataText);
            if (intval($buffer) > 0) {
                // split buffer
                $enroll = substr($buffer, 0, 8);
                $tahun = substr($buffer, 8, 4);
                $bulan = substr($buffer, 12, 2);
                $hari = substr($buffer, 14, 2);
                $jam = substr($buffer, 16, 2);
                $menit = substr($buffer, 18, 2);
                //$detik = substr($buffer, 20, 2);
                $inout = substr($buffer, 22, 1);
                $queue = substr($buffer, 23, 3);
                $location = substr($queue, -1);


                // specified some var
                $nip = $this->processpresence->get_nip_by_enroll($enroll);
                $tanggal = $tahun . "-" . $bulan . "-" . $hari;
                $waktu = $jam . ":" . $menit . ":00";

                $record = array('DataText' => $buffer,
                    'IDCard' => $enroll,
                    'IDEmployee' => $nip,
                    'PresenceDate' => $tanggal,
                    'PresenceTime' => $waktu,
                    'Direction' => $inout,
                    'Location' => $location,
                    'ProcessBy' => $this->User,
                    'ProcessDate' => $this->Datetime,
                    'ProcessIP' => $this->Ip
                );
                //if record not exist, added! 
                if ($this->processpresence->check_rawdata($buffer) == False) {
                    $num++;
                    $this->processpresence->insert_rawdata($record);
                } else {
                    $this->processpresence->update_rawdata($buffer, $record);
                }
            }
        }
        set_time_limit(30);
       $this->session->set_userdata('sess_number',$num);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */



