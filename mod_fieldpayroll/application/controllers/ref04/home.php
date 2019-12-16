<?php

class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('parampayroll_model', 'parampayroll');
        $this->load->model('historytable_model', 'history');
        $this->load->model('employee_model', 'employee');
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
        $this->load->view('ref04/home');
    }
    
    

    function getdatatables() {
        echo $this->parampayroll->getdata();
    }

    function addnew() {
        $data['default']['f01'] = ''; //SumDaySalary  
        $data['default']['f02'] = ''; //OvertimeWorkHour  
        $data['default']['f03'] = ''; //InsurancePercent  
        $data['default']['f04'] = ''; //BPJSPercent  
        $data['default']['f05'] = ''; //Note  

        $data['url_post'] = site_url('ref04/home/addpost');
        $this->load->view('ref04/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'Sum Day Salary', 'required');
        $this->form_validation->set_rules('f02', 'Overtime Work Hour', 'required');
        $this->form_validation->set_rules('f03', 'Insurance Percent', 'required');
        $this->form_validation->set_rules('f04', 'BPJS Percent', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->input->post('f05');

            $record = array(
                'SumDaySalary' => $f01,
                'OvertimeWorkHour' => $f02,
                'InsurancePercent' => $f03,
                'BPJSPercent' => $f04,
                'Note' => $f05,
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
                'controller' => site_url('ref04/home/addnew'),
                'activities' => 'add new ' . $f01
            );


            $this->parampayroll->insert($record);
            $this->logs->insert($recordlog);

            $mesg = 'Insert data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '", 
                       "err_f02":"' . $err_f02 . '", 
                       "err_f03":"' . $err_f03 . '", 
                       "err_f04":"' . $err_f04 . '", 
                       "err_f05":"' . $err_f05 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->parampayroll->getby_id($id);
        $data['default']['f01'] = $row->SumDaySalary; 
        $data['default']['f02'] = $row->OvertimeWorkHour; 
        $data['default']['f03'] = $row->InsurancePercent; 
        $data['default']['f04'] = $row->BPJSPercent; 
        $data['default']['f05'] = $row->Note; 

        $data['url_post'] = site_url('ref04/home/editpost');
        $this->load->view('ref04/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Sum Day Salary', 'required');
        $this->form_validation->set_rules('f02', 'Overtime Work Hour', 'required');
        $this->form_validation->set_rules('f03', 'Insurance Percent', 'required');
        $this->form_validation->set_rules('f04', 'BPJS Percent', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id= $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->input->post('f05');

            $record = array(
                'SumDaySalary' => $f01,
                'OvertimeWorkHour' => $f02,
                'InsurancePercent' => $f03,
                'BPJSPercent' => $f04,
                'Note' => $f05,
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
                'controller' => site_url('ref04/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $this->historydata($id);
            $this->parampayroll->update($id,$record);
            $this->logs->insert($recordlog);

            $mesg = 'update data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '", 
                       "err_f02":"' . $err_f02 . '", 
                       "err_f03":"' . $err_f03 . '", 
                       "err_f04":"' . $err_f04 . '", 
                       "err_f05":"' . $err_f05 . '"' .
                '}';
        echo $json;
    }

   function delete($id) {
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );

        $this->parampayroll->update($id, $record);
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

        // parampayroll format, &euro; with < 0 being in red color
        $parampayrollFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        // number format, with thousands seperator and two decimal points.
        $numberFormat = '#,#0.##;[Red]-#,#0.##';

        // writer will create the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();
        // rename the sheet
        $objSheet->setTitle('parampayroll report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(12);

        // write header

        $objSheet->getCell('A1')->setValue('Sum Day Salary');
        $objSheet->getCell('B1')->setValue('Overtime Work Hour');
        $objSheet->getCell('C1')->setValue('Insurance Tenaga Kerja Percent');
        $objSheet->getCell('D1')->setValue('BPJS Kesehatan Percent');
        $objSheet->getCell('E1')->setValue('Note');

        $result = $this->parampayroll->getall_data();
        if ($result != NULL) {
            $i = 1;
            foreach ($result as $row) {
                $i++;


                $objSheet->getCell('A' . $i)->setValue($row['SumDaySalary']);
                $objSheet->getCell('B' . $i)->setValue($row['OvertimeWorkHour']);
                $objSheet->getCell('C' . $i)->setValue($row['InsurancePercent']);
                $objSheet->getCell('D' . $i)->setValue($row['BPJSPercent']);
                $objSheet->getCell('E' . $i)->setValue($row['Note']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:E' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:E' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:E1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);
            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "parampayroll" . $ext);
            $data = file_get_contents($path_file . "parampayroll" . $ext);
            force_download("parampayroll" . $ext, $data);
        }
    }
    
    
    function historydata($id){
         $rowh = $this->parampayroll->getby_id($id); 
            $record = array(
                "SumDaySalary"=>$rowh->SumDaySalary,
                "InsurancePercent"=>$rowh->InsurancePercent,
                "BPJSPercent"=>$rowh->BPJSPercent,
                "OvertimeWorkHour"=>$rowh->OvertimeWorkHour,
                "Note"=>$rowh->Note,
                "AddedBy"=>$rowh->AddedBy,
                "AddedDate"=>$rowh->AddedDate,
                "AddedIP"=>$rowh->AddedIP,
                "EditedBy"=>$rowh->EditedBy,
                "EditedDate"=>$rowh->EditedDate,
                "EditedIP"=>$rowh->EditedIP,
                "DeleteBy"=>$rowh->DeleteBy,
                "DeleteDate"=>$rowh->DeleteDate,
                "DeleteIP"=>$rowh->DeleteIP,
                "DeleteFlag"=>$rowh->DeleteFlag,
                "IDTable"=>$rowh->ID,
                "FunctionOn"=>'edit',
                "HistBy"=>$this->User,
                "HistDate"=>$this->Datetime,
                "HistIP"=>$this->Ip
            );
            
            $this->history->insert_history_parampayroll($record);
            
    }

}
