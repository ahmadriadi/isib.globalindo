<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Chart_Model', 'chart');
        $this->load->model('libraryfunction_model', 'libfun');

        $this->User = $this->session->userdata('sess_userid');
    }

    function index() {
        $date = $this->libfun->periode_one_month();
        $from = substr($date, 0, 10);
        $until = date('Y-m-d');

        $sumpresence['label'] = "Sum Presence";
        $sumovertime['label'] = "Sum Overtime";
        $maxovertime['label'] = "Max Overtime Hour";
        $periode = $from;
        $no = -1;
        while ($periode <= $until) {
            $no++;
            $date = $periode;

            $sumpresence['data'][] = [$no, intval($this->chart->getcount_presence($date, '1', '16'))];
            $sumovertime['data'][] = [$no, intval($this->chart->getcount_overtime($date, '1', '16'))];
            $maxovertime['data'][] = [$no, round($this->chart->getmax_overtime($date, '1', '16'))];


            $periode = date('Y-m-d', strtotime("+1 day", strtotime($date)));
        }

        $all[] = $sumpresence;
        $all[] = $sumovertime;
        $all[] = $maxovertime;
        $data['chart_data'] = json_encode($all);

        $data['default']['location'][1]['value'] = "1";
        $data['default']['location'][1]['display'] = "KAPUK";
        $data['default']['location'][1]['checked'] = "CHECKED";
        $data['default']['location'][2]['value'] = "2";
        $data['default']['location'][2]['display'] = "BITUNG";

        $data['default']['dept'][0]['value'] = '16';
        $data['default']['dept'][0]['display'] = 'PRODUCTION';
        $data['default']['dept'][0]['selected'] = "SELECTED";


        $data['default']['monthdata'] = date('m, Y');
        $data['years1'] = $this->chart->getpresence_year_asc();
        $data['years2'] = $this->chart->getpresence_year_desc();
        $data['flag'] = 'add';
        $data['url_param'] = site_url('chart02/getchart_param');

        $this->load->view('chart02/home', $data);
    }

    function getchart_param() {
        $valdays = $this->input->post('valmonth');
        $loc = $this->input->post('loc');
        $dept = $this->input->post('dept');
        $datetemp = explode(',', $valdays);
        $amountdays = $this->libfun->days_in_month($datetemp[0], $datetemp[1]) - 1;


        $from =  trim($datetemp[1]) .'-'. trim($datetemp[0]).'-'.'01';
        $until = date('Y-m-d', strtotime("+" . $amountdays . "day", strtotime($from)));


        $sumpresence['label'] = "Sum Presence";
        $sumovertime['label'] = "Sum Overtime";
        $maxovertime['label'] = "Max Overtime Hour";
        
        $periode = $from;
        $no = -1;
        while ($periode <= $until) {
            $no++;
            $date = $periode;

            $sumpresence['data'][] = [$no, intval($this->chart->getcount_presence($date, $loc, $dept))];
            $sumovertime['data'][] = [$no, intval($this->chart->getcount_overtime($date, $loc, $dept))];
            $maxovertime['data'][] = [$no, round($this->chart->getmax_overtime($date, $loc, $dept))];


            $periode = date('Y-m-d', strtotime("+1 day", strtotime($date)));
        }
        

        $years1 = $this->chart->getpresence_year_asc();
        $years2 = $this->chart->getpresence_year_desc();
        
           
        
        $all[] = $sumpresence;
        $all[] = $sumovertime;
        $all[] = $maxovertime;
       
       /* 
        $valid = "true";
        $json = '{ "location":"' . 'Data Already Exist' . '",  
                    "monthdata":"' . $valdays . '",
                    "years1":"' . $years1 . '",
                    "years2":"' . $years2 . '",                    
                    "datachart":"' . $all . '",
                    "valid":"' . $valid . '"'
                .
                '}';
         $json;
        * 
        */
         
         echo json_encode($all);      
       
    }

}
