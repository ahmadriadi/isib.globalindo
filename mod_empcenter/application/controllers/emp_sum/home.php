<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("chart_model","crt");
    }
    
    function index (){
        // active employee
        $thn    = date("Y");
        $bln    = date("m");
        //active employee
        $thn == date("Y") ? $bln = date("m") : $bln = 12;
        $jmla['label']   = "Active";
        for ($i=0;$i<=($bln-1);$i++){
            $get_all_in = $this->crt->get_all_in($i,$thn)->row()->jml;
            $get_all_res = $this->crt->get_all_res($i,$thn)->row()->jml;
            $getjmlres  = $this->crt->get_resign($i,$thn)->row()->jml;
            $getjml = $get_all_in - $get_all_res - $getjmlres;
            $jmla['data'][] = [$i, intval($getjml)];
        }
        //new employee
        $jmln['label']   = "New";
        for ($i=0;$i<=($bln-1);$i++){
            $getjml  = $this->crt->get_new($i,$thn)->row()->jml;
            $jmln['data'][] = [$i, intval($getjml)];
        }
        //resigned employee
        $jmlr['label']   = "Resigned";
        for ($i=0;$i<=($bln-1);$i++){
            $getjml  = $this->crt->get_resign($i,$thn)->row()->jml;
            $jmlr['data'][] = [$i, intval($getjml)];
        }
        $all[] = $jmla;
        $all[] = $jmlr;
        $all[] = $jmln;
        
        $data['chart_data'] = json_encode($all);
        $this->load->view('emp_sum/home',$data); 
    }
    function get_data($thn=NULL){
        $thn    = $this->input->post("tahun");
        //active employee
        $thn == date("Y") ? $bln = date("m") : $bln = 12;
        $jmla['label']   = "Active";
        for ($i=0;$i<=($bln-1);$i++){
            $get_all_in = $this->crt->get_all_in($i,$thn)->row()->jml;
            $get_all_res = $this->crt->get_all_res($i,$thn)->row()->jml;
            $getjmlres  = $this->crt->get_resign($i,$thn)->row()->jml;
            $getjml = $get_all_in - $get_all_res - $getjmlres;
            $jmla['data'][] = [$i, intval($getjml)];
        }
        //new employee
        $jmln['label']   = "New";
        for ($i=0;$i<=($bln-1);$i++){
            $getjml  = $this->crt->get_new($i,$thn)->row()->jml;
            $jmln['data'][] = [$i, intval($getjml)];
        }
        //resigned employee
        $jmlr['label']   = "Resigned";
        for ($i=0;$i<=($bln-1);$i++){
            $getjml  = $this->crt->get_resign($i,$thn)->row()->jml;
            $jmlr['data'][] = [$i, intval($getjml)];
        }
        $all[] = $jmla;
        $all[] = $jmlr;
        $all[] = $jmln;

        echo json_encode($all);        
    }
    function exportdata() {
        $ext = '.xlsx';
        $path_file = '/tmp/';

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->helper('download');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("title")->setDescription("description");

        // currency format, &euro; with < 0 being in red color
        $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        // number format, with thousands seperator and two decimal points.
        $numberFormat = '#,#0.##;[Red]-#,#0.##';

        // writer will create the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();
        // rename the sheet
        $objSheet->setTitle('data karyawan');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(12);

//        // write header
        $objSheet->getCell('A1')->setValue('No');
        $objSheet->getCell('B1')->setValue('IDEmployee');
        $objSheet->getCell('C1')->setValue('FullName');
        $objSheet->getCell('D1')->setValue('Email Internal');
        $objSheet->getCell('E1')->setValue('Email Eksternal');
        $objSheet->getCell('F1')->setValue('No Telp');
        
        $result = $this->crt->get_available_emp();
        if ($result != NULL) {   
            $i = 1;
            foreach ($result->result() as $row) {
                $i++;
                //echo $i."->".$row->IDEmployee."->".$row->FullName."<br>";
                $objSheet->getCell('A' . $i)->setValue($i-1);
                $objSheet->getCell('B' . $i)->setValue("'" . $row->IDEmployee);
                $objSheet->getCell('C' . $i)->setValue($row->FullName);
                $objSheet->getCell('D' . $i)->setValue($row->InternalEmail);
                $objSheet->getCell('E' . $i)->setValue($row->ExternalEmail);
                $objSheet->getCell('F' . $i)->setValue("'" .$row->NoHP);


//                $objSheet->getCell('G' . $i)->setValue($sumhour);
//                $objSheet->getCell('H' . $i)->setValue($row['Note']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:H' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:H' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:H1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

//            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);
            $objSheet->getColumnDimension('F')->setAutoSize(true);
//            $objSheet->getColumnDimension('G')->setAutoSize(true);
//            $objSheet->getColumnDimension('H')->setAutoSize(true);

            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "karyawan" . $ext);
            $data = file_get_contents($path_file . "karyawan" . $ext);
            force_download("data_karyawan" . $ext, $data);
        }
    }

    
}

