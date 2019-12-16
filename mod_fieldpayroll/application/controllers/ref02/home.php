<?php

class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('reference_model', 'reference');
        $this->load->model('logs_model', 'logs');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }	

    function index() {
        $this->load->view('ref02/home');
    }

    function datadeduction() {
        echo $this->reference->refdeduction();
    }

    function addnew() {
        $data['default']['f01'] = ''; //CodeType  
        $data['default']['f02'] = ''; //Description  

        $data['url_post'] = site_url('ref02/home/addpost');
        $this->load->view('ref02/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'Code', 'required');
        $this->form_validation->set_rules('f02', 'Description', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));

            $record = array(
                'CodeType' => $f01,
                'Description' => $f02,
                'AddedBy' => $this->User,
                'AddedDate' => $this->Datetime,
                'AddedIP' => $this->Ip
            );

            $recordlog = array(
                'ID' => NULL,
                'username' => $this->User,
                'log_date' => $this->Datetime,
                'log_ip' => $this->Ip,
                'log_agent' => $this->Browser,
                'controller' => site_url('ref02/home/addnew'),
                'activities' => 'add new ' . $f01
            );


            $this->reference->insert_deduction($record);
            $this->logs->insert($recordlog);

            $mesg = 'Insert data, success';
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

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->reference->get_by_id_deduction($id)->row();
        $data['default']['f01'] = $row->CodeType; 
        $data['default']['f02'] = $row->Description; 

        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('ref02/home/editpost');
        $this->load->view('ref02/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Code', 'required');
        $this->form_validation->set_rules('f02', 'Description', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));

            $record = array(
                'Description' => $f02,
                'EditedBy' => $this->User,
                'EditedDate' => $this->Datetime,
                'EditedIP' => $this->Ip
            );

            $recordlog = array(
                'ID' => NULL,
                'username' => $this->User,
                'log_date' => $this->Datetime,
                'log_ip' => $this->Ip,
                'log_agent' => $this->Browser,
                'controller' => site_url('ref02/home/addnew'),
                'activities' => 'edit new ' . $f01
            );


            $this->reference->update_deduction($id, $record);
            $this->logs->insert($recordlog);
            $mesg = 'update data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
        } else {
            $mesg = 'update data, failed';
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

    function delete($id) {
        $this->reference->delete_deduction($id);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function exportdata() {
        $ext = '.xlsx';
        $path_file = '/tmp/';

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->helper('download');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("title")
                ->setDescription("description");

        // reference format, &euro; with < 0 being in red color
        $referenceFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        // number format, with thousands seperator and two decimal points.
        $numberFormat = '#,#0.##;[Red]-#,#0.##';

        // writer will create the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();
        // rename the sheet
        $objSheet->setTitle('reference report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:B1')->getFont()->setBold(true)->setSize(12);

        // write header

        $objSheet->getCell('A1')->setValue('Code Type');
        $objSheet->getCell('B1')->setValue('Description');

        $result = $this->reference->get_alldata_deduction()->result_array();
        if ($result != NULL) {
            $i = 1;
            foreach ($result as $row) {
                $i++;


                $objSheet->getCell('A' . $i)->setValue($row['CodeType']);
                $objSheet->getCell('B' . $i)->setValue($row['Description']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:B' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:B' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:B1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "reference" . $ext);
            $data = file_get_contents($path_file . "reference" . $ext);
            force_download("reference" . $ext, $data);
        }
    }

}
