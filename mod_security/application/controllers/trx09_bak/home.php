<?php

//USER REPORT/REQUEST
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('rootcause_model', 'rootcause');
        $this->load->model('logs_model', 'logs');
        $this->load->model('libraryfunction_model', 'libfun');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function get_accepted() {
        $accepted = $this->rootcause->get_accepted($this->User);
        echo $accepted;
    }

    function anti_xss($source) {
        $f = stripslashes(strip_tags(htmlspecialchars($source, ENT_QUOTES)));
        return $f;
    }

    function index() {
        $fromd = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');
        $check1 = ($fromd == '' or $fromd == null) ? 'empty' : 'exist';
        $check2 = ($untild == '' or $untild == null) ? 'empty' : 'exist';


        if ($check1 == 'empty' and $check2 == 'empty') {
            $date = $this->libfun->periode_work();
            $fromdate = substr($date, 0, 10);
            $untildate = substr($date, 11, 10);
            $this->session->set_userdata('datefrom', $fromdate);
            $this->session->set_userdata('dateuntil', $untildate);
        } else {
            $fromdate = $this->session->userdata('fromdate');
            $untildate = $this->session->userdata('untildate');
            $this->session->set_userdata('datefrom', $fromdate);
            $this->session->set_userdata('dateuntil', $untildate);
        }

        $data['default']['from'] = date('d-m-Y', strtotime($fromdate));
        $data['default']['until'] = date('d-m-Y', strtotime($untildate));

        $this->load->view('trx09/home', $data);
    }

    function getdatatable() {
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        echo $this->rootcause->getdata_user($f,$u,$this->User);
        
       // echo $user;
    }

    function getstatus() {
        $id = $this->input->post('id');
        $rowh = $this->rootcause->getby_id_root($id);
        $flag = $rowh->StatusProblem;
        $valid = 'true';
        $json = '{ "flag":"' . $flag . '",
		   "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function set_pattern_date() {
        $valid = "true";

        $fromdate = date('Y-m-d', strtotime($this->input->post('fromdate')));
        $untildate = date('Y-m-d', strtotime($this->input->post('untildate')));
        $this->session->set_userdata('fromdate', $fromdate);
        $this->session->set_userdata('untildate', $untildate);

        echo '{ "valid":"' . $valid . '"}';
    }

    function autocomplete_employee() {
        $result = $this->employee->search_employee();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                'fullname' => strtoupper($row->FullName)
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    function addnew() {
        $query = $this->rootcause->getrootcause();
        $i = 0;
        foreach ($query as $r) {
            $data['default']['f01'][-1]['value'] = NULL;
            $data['default']['f01'][-1]['display'] = '-Please Select-';
            $data['default']['f01'][$i]['value'] = $r['IDRoot'];
            $data['default']['f01'][$i]['display'] = $r['RootName'];
            $i++;
        }
        $data['default']['f02'] = ''; //ComplainNote
        
         //TypeProblem
        $data['default']['f03'][1]['value'] = "1";
        $data['default']['f03'][1]['display'] = "Request";
        $data['default']['f03'][1]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value'] = "2";
        $data['default']['f03'][2]['display'] = "Complain";

        
        $data['url_post'] = site_url('trx09/home/addpost');
        $this->load->view('trx09/form', $data);
    }

    function addpost() {
			
        $this->form_validation->set_rules('f02', 'Complain Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $record = array(
                'IDRoot' => $f01,
                'ComplainNote' => $f02,
                'ComplainDate' => date('Y-m-d'),
                'TypeProblem' => $f03,
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
                'controller' => site_url('trx09/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $this->rootcause->insert_root($record);
            $this->logs->insert($recordlog);
            $mesg = 'insert data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';

            // kirim email pemberitahuan ke atasan
            $prs = $this->rootcause->get_personal($this->User)->row();
            $ats = $this->rootcause->get_prs_public($prs->IDEmployeeParent)->row();
            $ccin = $ats->InternalEmail;
            $ccex = explode(",", $ats->ExternalEmail);
            $subject = "TIS Notification - Contact IT";
            $data['message']= $f02;  
            
            $message = $this->load->view("trx09/email", $data, TRUE);
            $this->sendmail->internalmail('admintec@tis.loc', $subject, $message,$ccin);

            if ($this->sendmail->externalmail('admintec@tis.loc', $subject, $message,$ccex)) {

            }
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
        $row = $this->rootcause->getby_id_root($id);

        $query = $this->rootcause->getrootcause();
        $i = 0;
        foreach ($query as $r) {
            $data['default']['f01'][$i]['value'] = $r['IDRoot'];
            $data['default']['f01'][$i]['display'] = $r['RootName'];
            if ($row->IDRoot == $r['IDRoot']) {
                $data['default']['f01'][$i]['selected'] = 'SELECTED';
            }
            $i++;
        }
        
        $data['default']['f02'] = $row->ComplainNote; //idemployee
        
        //TypeProblem
        $data['default']['f03'][1]['value'] = "1";
        $data['default']['f03'][1]['display'] = "Request";
        $data['default']['f03'][2]['value'] = "0";
        $data['default']['f03'][2]['display'] = "Complain";
        if ($row->TypeProblem == '1') {
            $data['default']['f03'][1]['checked'] = "CHECKED";
        } else if ($row->TypeProblem == '2') {
            $data['default']['f03'][2]['checked'] = "CHECKED";
        }
        
        
        $data['url_post'] = site_url('trx09/home/editpost');
        $this->load->view('trx09/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Complain Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));

            $record = array(
                'IDRoot' => $f01,
                'ComplainNote' => $f02,
                'TypeProblem' => $f03,
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
                'controller' => site_url('trx09/home/addnew'),
                'activities' => 'edit new ' . $f02
            );
            
            $incomp = $this->rootcause->getby_id_root($id);
            if ($incomp->StatusProblem == '2' or $incomp->StatusProblem == '3') {
//                kirim email pemberitahuan ke atasan
                $prs = $this->rootcause->get_personal($this->User)->row();
                $ats = $this->rootcause->get_prs_public($prs->IDEmployeeParent)->row();
                $ccin = $ats->InternalEmail;
                $ccex = explode(",", $ats->ExternalEmail);
                $subject = "TIS Notification - Contact IT";
                
                $data['message'] = $f02;
                
                $message = $this->load->view("trx09/email", $data, TRUE);
                $this->sendmail->internalmail('admintec@tis.loc', $subject, $message,$ccin);
              
                if ($this->sendmail->externalmail('info.testerdata@gmail.com', $subject, $message,$ccex)) {
//                        echo $ex." => berhasil";
                }
            }
            $this->rootcause->update_root($id, $record);
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
        
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );

        $this->rootcause->update_root($id, $record);
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

        $fromdate = date('Y-m-d', strtotime($fromdate));
        $untildate = date('Y-m-d', strtotime($untildate));

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->helper('download');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("title")
                ->setDescription("description");

        // currency format, &euro; with < 0 being in red color
        $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        // number format, with thousands seperator and two decimal points.
        $numberFormat = '#,#0.##;[Red]-#,#0.##';

        // writer will create the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();
        // rename the sheet
        $objSheet->setTitle('rootcause report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(12);

        // write header

        $objSheet->getCell('A1')->setValue('Route Couse');
        $objSheet->getCell('B1')->setValue('Complain Note');
        $objSheet->getCell('C1')->setValue('Complain Date');
       // $objSheet->getCell('D1')->setValue('Root Couse');
       // $objSheet->getCell('E1')->setValue('Problem Note');
       // $objSheet->getCell('F1')->setValue('Solution Note');
        //$objSheet->getCell('G1')->setValue('Solution Date');
        $objSheet->getCell('D1')->setValue('Status Problem');
        $objSheet->getCell('E1')->setValue('Type Problem');
      //  $objSheet->getCell('F1')->setValue('PIC');

        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->rootcause->getall_datauser($f, $u, $this->User);
        if ($result != NULL) {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                if ($row['RootCouse'] == '1') {
                    $rootcause = 'Human';
                } else if ($row['RootCouse'] == '2') {
                    $rootcause = 'System';
                } else if($row['RootCouse'] == '3') {
                    $rootcause = 'Eksternal';
                }else{
                    $rootcause = '-';
                }

                if ($row['StatusProblem'] == '1') {
                    $status = 'Finish';
                } else if ($row['StatusProblem'] == '2') {
                    $status = 'Pending';
                } else if($row['StatusProblem'] == '3'){
                    $status = 'Stack';
                }else{
                    $status = '-';
                }

                $type = ($row['StatusProblem'] == '1') ? 'Request' : 'Complain';

                $objSheet->getCell('A' . $i)->setValue($row['RootName']);
                $objSheet->getCell('B' . $i)->setValue($row['ComplainNote']);
                $objSheet->getCell('C' . $i)->setValue($row['ComplainDate']);
               // $objSheet->getCell('D' . $i)->setValue($rootcause);
                //$objSheet->getCell('E' . $i)->setValue($row['ProblemNote']);
                //$objSheet->getCell('F' . $i)->setValue($row['SolutionNote']);
                //$objSheet->getCell('G' . $i)->setValue($row['SolutionDate']);
                $objSheet->getCell('D' . $i)->setValue($status);
                $objSheet->getCell('E' . $i)->setValue($type);
                //$objSheet->getCell('J' . $i)->setValue($row['FullName']);
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
          //  $objSheet->getColumnDimension('F')->setAutoSize(true);
          //  $objSheet->getColumnDimension('G')->setAutoSize(true);
          //  $objSheet->getColumnDimension('H')->setAutoSize(true);
         //   $objSheet->getColumnDimension('I')->setAutoSize(true);
         //   $objSheet->getColumnDimension('J')->setAutoSize(true);


            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "rootcause" . $ext);
            $data = file_get_contents($path_file . "rootcause" . $ext);
            force_download("rootcause" . $ext, $data);
        }
    }

    function notification() {
        $notfor = $this->input->post("notfor");
        $data['notfor'] = $notfor;
        if ($notfor == "sysdev") {
            $employee = "isib_employee.m01personal";
            $where = "StatusProblem = '2' AND DeleteFlag = 'A' AND 
                           IDEmployee IN (
                                            SELECT $employee.IDEmployee 
                                            FROM $employee 
                                            WHERE $employee.IDEmployeeParent = '$this->User'
                                          )";
            $data['rootcause'] = $this->rootcause->get_rootcause($where)->result();
        }

        $this->load->view("trx09/notifications", $data);
    }

}

