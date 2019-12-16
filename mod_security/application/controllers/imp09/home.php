<?php

class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('personalpayroll_model', 'personalpayroll');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $data['url_post'] = site_url('imp09/home/uploaddata');
        $this->load->view('imp09/form', $data);
    }

    function uploaddata() {
        $error = "";
        $msg = "";
        $config['upload_path'] = '/tmp/';
        $config['allowed_types'] = '*';
        $config['overwrite'] = TRUE;
        $config['max_size'] = '10024';
        $this->load->library('upload', $config);
        $this->upload->display_errors('', '');
        if (!$this->upload->do_upload("fileToUpload")) {
            $error = $this->upload->display_errors();
        } else {
            $data = $this->upload->data();
            $msg = $this->import_excel($data['file_ext'], $data['full_path']); //di tujukan ke function import_excel
        }

        $json = "{";
        $json .= "error: '" . $error . "',\n";
        $json .= "msg: '" . $msg . "'\n";
        $json .= "}";
        echo $json;
    }

    function import_excel($ext = '.xls', $path_file = '/tmp/data.xlsx') {
        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        if ($ext == '.xls') {
            $objReader = new PHPExcel_Reader_Excel5();
            $msg = "file excel 2003 berhasil di import";
        } elseif ($ext == '.xlsx') {
            $objReader = new PHPExcel_Reader_Excel2007();
            $msg = "file excel 2007 berhasil di import";
        } else {
            $objReader = new PHPExcel_Reader_OOCalc();
            $msg = "file openoffice spreadsheet berhasil di import";
        }

        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($path_file);
        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

        $array_data = array();
        foreach ($rowIterator as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
            //skip first 1'st row
            if ($row->getRowIndex() <= 0)
                continue;
            $rowIndex = $row->getRowIndex();
            $array_data[$rowIndex] = array('A' => '',
                'B' => ''
            );

            // loop on current row
            foreach ($cellIterator as $cell) {
                if ($cell->getColumn() == 'A') {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
                } else if ('B' == $cell->getColumn()) {
                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getValue();
                } 
            }

            $gapok = $array_data[$rowIndex]['B'];
            
            $dailysalary  = $gapok/30;
            $insurance  =  (2/100)*$gapok;
            $overtime  = $gapok/173;
            
            
            $record = array(
                'MonthlySalary' => $array_data[$rowIndex]['B'],
                'Insurance' =>  $insurance,
                'DailySalary' =>  $dailysalary,
                'OvertimePerHour' => $overtime,
            );

            $this->personalpayroll->updatedata($array_data[$rowIndex]['A'], $record);
        }
        return $msg;
    }
    
    
  


}
