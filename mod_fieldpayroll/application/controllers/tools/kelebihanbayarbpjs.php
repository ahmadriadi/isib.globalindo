<?php

class Kelebihanbayarbpjs extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('additional_model', 'addition');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->generate_additionbecausebpjs();
        echo 'process finish';
    }

    function generate_additionbecausebpjs() {
        $resultbpjs = $this->addition->gettemp_additionbpjs();
        if ($resultbpjs !== 'empty') {
            foreach ($resultbpjs as $row) {

                $nip = $row['IDEmployee'];
                $amount = $row['Amount'];
                $postingdate = '2015-10-24';
                $param = 'INSENTIF';
                $note = 'Kelebihan bayar iuran BPJS selama 3 Bulan ( Juli, Agustus, September )'
                        . ' dikarenakan nilai pemotongan mengambil Mountly Salary x 1 %, seharusnya '
                        . ' mengambil gaji pokok di sama ratakan 2.700.000 (GP Jkt - digeneralkan) x 1%';

                $flag = 'Process by System';
                $addedby = 'System';
                $addeddate = $this->Datetime;
                $addedip = $this->IP;


                $record = array(
                    "IDEmployee" => $nip,
                    "Amount" => $amount,
                    "PostingDate" => $postingdate,
                    "Parameter" => $param,
                    "FlagEntry" => $flag,
                    "Note" => $note,
                    "AddedBy" => $addedby,
                    "AddedDate" => $addeddate,
                    "AddedIP" => $addedip
                );


                $data = $this->addition->checkdata($nip, $postingdate, $flag);
                $checkdata = ($data == 'exist') ? 'exist' : 'empty';

                if ($checkdata == 'exist') {
                    $this->addition->update_employee($nip, $record);
                } else {
                    $this->addition->insert($record);
                }
            }
        }
    }

}
