<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('report_model', 'report');
        $this->load->model('uac_model', 'uac');
        $this->load->model('libraryfunction_model', 'libfun');
        $this->User = $this->session->userdata('sess_userid');
    }

    function index() {

        $date = $this->libfun->periode_one_month();
        $data['default']['f01'] = substr($date, 0, 10);
        $data['default']['f02'] = substr($date, 11, 10);
        $data['default']['f03'][1]['value'] = "1";
        $data['default']['f03'][1]['display'] = "KAPUK";
        $data['default']['f03'][1]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value'] = "2";
        $data['default']['f03'][2]['display'] = "BITUNG";

        $data['default']['f04'][0]['value'] = '16';
        $data['default']['f04'][0]['display'] = 'PRODUCTION';
        $data['default']['f04'][0]['selected'] = "SELECTED";

        $idmenu = "152";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);

        $this->load->view('rpt01/home', $data);
    }

    function presencedata($loc, $dept, $from, $until) {
        $valid = "true";
        $json = '{ "mesg":"' . 'Data Already Exist' . '",                                   
                       "valid":"' . $valid . '"'
                .
                '}';
        echo $json;
    }

    function iframedata($loc, $dept, $from, $until) {
        $data['url'] = site_url('rpt01/home/reportdata/' . $loc . '/' . $dept . '/' . $from . '/' . $until);
        $this->load->view('rpt01/iframe', $data);
    }

    function reportdata($loc, $dept, $from, $until) {
        $start = date('Y-m-d', strtotime($from));
        $until = date('Y-m-d', strtotime($until));

        $periode = $start;
        $no = 0;
        $day = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        while ($periode <= $until) {
            $no++;
            $date = $periode;
            $dayofweek = $day[date('w', strtotime($date))];

            $sumpresence = $this->report->getcount_presence($date, $dept, $loc);
            $sumovertime = $this->report->getcount_overtime($date, $dept, $loc);
            $maxovertime = $this->report->getmax_overtime($date, $dept, $loc);
            $note = $this->report->getcount_presence_note($date, $dept, $loc);

            $table = "<tr>";
            $table.= "    <td>" . $no . "</td>";
            $table.= "    <td>" . date('d-m-Y', strtotime($date)) . "</td>";
            $table.= "    <td>" . $dayofweek . "</td>";
            $table.= "    <td>" . $sumpresence . "</td>";
            $table.= "    <td>" . $sumovertime . "</td>";
            $table.= "    <td>" . $maxovertime . "</td>";
            $table.= "    <td>" . $note . "</td>";
            $table.= "</tr>";


            $data['datadaily'][$no]['tabletr'] = $table;

            $periode = date('Y-m-d', strtotime("+1 day", strtotime($date)));
        }

        $data['fromdate'] = $from;
        $data['untildate'] = $until;
        $data['location'] = ($loc == '1') ? 'KAPUK' : 'BITUNG';
        $data['departemen'] = 'PRODUCTION';
        $data['url_excel'] = site_url('rpt01/home/excel'. '/' . $loc . '/' . $dept . '/' . $from . '/' . $until);
        $this->load->view('rpt01/report', $data);
    }

    function excel($loc, $dept, $from, $until) {
        $ext = '.xlsx';
        $path_file = '/tmp/';
        $lokasi = ($loc=='1')?'KAPUK':'BITUNG';
        
        
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->helper('download');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("title")
                ->setDescription("description");

        $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        $numberFormat = '#,#0.##;[Red]-#,#0.##';
        $objSheet = $objPHPExcel->getActiveSheet();
        $objSheet->setTitle('detail presence');

        $objSheet->getStyle('A1:G8')->getFont()->setBold(true)->setSize(10);
        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('DAILY DETAIL REPORT');
        $objSheet->getCell('A4')->setValue('PERIOD');
        $objSheet->getCell('B4')->setValue(':');
        $objSheet->getCell('C4')->setValue(date('d-m-Y', strtotime($from)) . ' to ' . date('d-m-Y', strtotime($until)));
        $objSheet->getCell('A5')->setValue('LOCATION');
        $objSheet->getCell('B5')->setValue(':');
        $objSheet->getCell('C5')->setValue($lokasi);
        $objSheet->getCell('A6')->setValue('DEPARTEMEN');
        $objSheet->getCell('B6')->setValue(':');
        $objSheet->getCell('C6')->setValue('PRODUCTION');
        $objSheet->getCell('A8')->setValue('NO');
        $objSheet->getCell('B8')->setValue('DATE');
        $objSheet->getCell('C8')->setValue('DAY OF WEEK');
        $objSheet->getCell('D8')->setValue('SUM OF PRESENCE');
        $objSheet->getCell('E8')->setValue('SUM OF OVERTIME');
        $objSheet->getCell('F8')->setValue('MAX HOUR OF OVERTIME');
        $objSheet->getCell('G8')->setValue('NOTE');

        $i = 9;
        $no = 0;
        $day = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $start = date('Y-m-d', strtotime($from));
        $until = date('Y-m-d', strtotime($until));
        $periode = $start;

        while ($periode <= $until) {
            $i++;
            $n++;

            $date = $periode;
            $dayofweek = $day[date('w', strtotime($date))];

            $sumpresence = $this->report->getcount_presence($date, $dept, $loc);
            $sumovertime = $this->report->getcount_overtime($date, $dept, $loc);
            $maxovertime = $this->report->getmax_overtime($date, $dept, $loc);
            $note = $this->report->getcount_presence_note($date, $dept, $loc);

            $objSheet->getCell('A' . $i)->setValue($n);
            $objSheet->getCell('B' . $i)->setValue(date('d-m-Y', strtotime($date)));
            $objSheet->getCell('C' . $i)->setValue($dayofweek);
            $objSheet->getCell('D' . $i)->setValue($sumpresence);
            $objSheet->getCell('E' . $i)->setValue($sumovertime);
            $objSheet->getCell('F' . $i)->setValue($maxovertime);
            $objSheet->getCell('G' . $i)->setValue($note);


            $periode = date('Y-m-d', strtotime("+1 day", strtotime($date)));
        }

        $objSheet->getStyle('A8:G' . $i)->getBorders()->
                getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:G' . $i)->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A8:G' . $i)->getBorders()->
                getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);


        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }

        ob_end_clean();
        $objWriter->save($path_file . "dailymanhourreport_". $lokasi . $ext);
        $data = file_get_contents($path_file . "dailymanhourreport_". $lokasi . $ext);
        force_download("dailymanhourreport_". $lokasi . date('d-m-Y h:i') . $ext, $data);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */




