<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('parampresence_model', 'parampresence');
        $this->load->model('logs_model', 'logs');
        $this->load->model('employee_model', 'employee');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('uac_model', 'uac');


        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function anti_xss($source) {
        $f = stripslashes(strip_tags(htmlspecialchars($source, ENT_QUOTES)));
        return $f;
    }

    function index() {
        $idmenu = "197";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('ref05/home', $data);
    }

    function getdatatable() {
        echo $this->parampresence->getdata();
    }

    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '197';
        $row = $this->uac->getdata_button($this->User, $idmenu, $button);
        $check = ($row == null or $row == '') ? 'empty' : 'exist';

        if ($check !== 'empty') {
            $access = $row->kdbutton;
            $mesg = "Result Button";
            $valid = 'true';
        } else {
            $access = '';
            $mesg = "Result Is Null";
            $valid = 'false';
        }

        $json = '{ "mesg":"' . $mesg . '",
                   "btnaccess":"' . $access . '",
                   "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function addnew() {
        $data['default']['f01'] = ''; 
        $data['default']['f02'][1]['value'] = "1";
        $data['default']['f02'][1]['display'] = "Kapuk";
        $data['default']['f02'][1]['checked'] = "";
        $data['default']['f02'][2]['value'] = "2";
        $data['default']['f02'][2]['display'] = "Bitung";
                
        $data['default']['f03'] = '';
        $data['default']['f04'] = '';
        $data['default']['f05'] = '';
        $data['default']['f06'] = '';
        
        //Calculate working hours
        $data['default']['f07'][1]['value'] = "0";
        $data['default']['f07'][1]['display'] = "No";
        $data['default']['f07'][1]['checked'] = "";
        $data['default']['f07'][2]['value'] = "1";
        $data['default']['f07'][2]['display'] = "Yes";
        
        //Get incentives shift
        $data['default']['f08'][1]['value'] = "0";
        $data['default']['f08'][1]['display'] = "No";
        $data['default']['f08'][1]['checked'] = "";
        $data['default']['f08'][2]['value'] = "1";
        $data['default']['f08'][2]['display'] = "Yes";
        
        
        $data['url_post'] = site_url('ref05/home/addpost');
        $this->load->view('ref05/form', $data);
    }

    
    function checkfromtime() {
        $time = $this->input->post('f05');
        $len = strlen($time);
        $hour = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        if ($len < 4 | $len > 4) {
            $this->form_validation->set_message("checkfromtime", "Length of string :" . $len . " is to short");
            return false;
        } else {
            if ($hour < 0 | $hour > 23) {
                $this->form_validation->set_message("checkfromtime", "Hour string should not below under 0 or exceed 23 ");
                return false;
            } else {
                if ($minutes < 0 | $minutes > 59) {
                    $this->form_validation->set_message("checkfromtime", "Minute string should not below under  0 or exceed 59 ");
                    return false;
                } else {
                    return true;
                }
            }
        }
    }

    function checkuntiltime() {
        $time = $this->input->post('f06');
        $len = strlen($time);
        $hour = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        if ($len < 4 | $len > 4) {
            $this->form_validation->set_message("checkuntiltime", "Length of string :" . $len . " is to short");
            return false;
        } else {

            if ($hour < 0 | $hour > 23) {
                $this->form_validation->set_message("checkuntiltime", "Hour string should not below under 0 or exceed 23 ");
                return false;
            } else {
                if ($minutes < 0 | $minutes > 59) {
                    $this->form_validation->set_message("checkuntiltime", "Minute string should not below under  0 or exceed 59 ");
                    return false;
                } else {
                    return true;
                }
            }
        }
    }
    
    
    function addpost() {
        $this->form_validation->set_rules('f01', 'Date', 'required');
        $this->form_validation->set_rules('f05', 'From time', 'required|callback_checkfromtime');
        $this->form_validation->set_rules('f06', 'Until time', 'required|callback_checkuntiltime');
        $this->form_validation->set_rules('f02', 'Location', 'required');
        $this->form_validation->set_rules('f03', 'Work Hour', 'required');
     
        if ($this->form_validation->run() == TRUE) {
            $f01 = date('Y-m-d', strtotime($this->anti_xss($this->input->post('f01'))));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f06 = $this->anti_xss($this->input->post('f06'));
            $f07 = $this->anti_xss($this->input->post('f07'));
            $f08 = $this->anti_xss($this->input->post('f08'));

            
            $record = array(
                'ParamDate' => $f01,
                'ParamSite' => $f02,
                'ParamWorkHour' => $f03,
                'Note' => $f04,
                'FromTIme' => $f05,
                'UntilTIme' => $f06,
                'BlockWorkTime' => $f07,
                'BlockInsentifeShift' => $f08,
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
                'controller' => site_url('ref05/home/addnew'),
                'activities' => 'add new param ' . $f01
            );

            $rcheck = $this->parampresence->checkdata($f01, $f02);
            if ($rcheck == 'exist') {
                $alert = 'insert failed, parampresence  ' . $f01 . ' already exist';
                $status = 'false';
            } else {
                $this->parampresence->insert($record);
                $this->logs->insert($recordlog);
                $alert = 'insert data, success';
                $status = 'true';
            }

            $mesg = $alert;
            $valid = $status;
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
            $err_f06 = '';
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
                       "err_f02":"' . $err_f02 . '",  
                       "err_f03":"' . $err_f03 . '",  
                       "err_f04":"' . $err_f04 . '",  
                       "err_f05":"' . $err_f05 . '",  
                       "err_f06":"' . $err_f06 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->parampresence->getby_id($id);

        $data['default']['f01'] = date('d-m-Y', strtotime($row->ParamDate)); //
        $data['default']['f02'][1]['value'] = "1";
        $data['default']['f02'][1]['display'] = "Kapuk";
        $data['default']['f02'][1]['checked'] = "";
        $data['default']['f02'][2]['value'] = "2";
        $data['default']['f02'][2]['display'] = "Bitung";
        if ($row->ParamSite == '1') {
            $data['default']['f02'][1]['checked'] = "CHECKED";
        } else if ($row->ParamSite == '2') {
            $data['default']['f02'][2]['checked'] = "CHECKED";
        }


        $data['default']['f03'] = $row->ParamWorkHour; //
        $data['default']['f04'] = $row->Note; //
        
        
        $data['default']['f02'][1]['value'] = "0";
        $data['default']['f02'][1]['display'] = "No";
        $data['default']['f02'][1]['checked'] = "";
        $data['default']['f02'][2]['value'] = "2";
        $data['default']['f02'][2]['display'] = "Bitung";
        if ($row->ParamSite == '1') {
            $data['default']['f02'][1]['checked'] = "CHECKED";
        } else if ($row->ParamSite == '2') {
            $data['default']['f02'][2]['checked'] = "CHECKED";
        }
        

        $data['url_post'] = site_url('ref05/home/editpost');

        $this->load->view('ref05/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Date', 'required');
        $this->form_validation->set_rules('f02', 'Location', 'required');
        $this->form_validation->set_rules('f03', 'Value 2', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = date('Y-m-d', strtotime($this->anti_xss($this->input->post('f01'))));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));

            $record = array(
                'ParamDate' => $f01,
                'ParamSite' => $f02,
                'ParamWorkHour' => $f03,
                'Note' => $f04,
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
                'controller' => site_url('ref05/home/addnew'),
                'activities' => 'edit data id ' . $id
            );


            $this->parampresence->update($id, $record);
            $this->logs->insert($recordlog);
            $mesg = 'update data, success ';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",                                      
                       "err_f02":"' . $err_f02 . '",                    
                       "err_f03":"' . $err_f03 . '",                    
                       "err_f04":"' . $err_f04 . '"' .
                '}';
        echo $json;
    }

    function delete($id) {
        $this->parampresence->delete($id);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function excel() {
        $ext = '.xlsx';
        $path_file = '/tmp/';

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
        $objSheet->setTitle('parampresence report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('Date');
        $objSheet->getCell('B1')->setValue('Location');
        $objSheet->getCell('C1')->setValue('Work Hour');
        $objSheet->getCell('D1')->setValue('Note');

        $result = $this->parampresence->getall_data();
        if ($result != NULL) {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $objSheet->getCell('A' . $i)->setValue($row['ParamDate']);
                $objSheet->getCell('B' . $i)->setValue($row['ParamSite']);
                $objSheet->getCell('C' . $i)->setValue($row['ParamWorkHour']);
                $objSheet->getCell('D' . $i)->setValue($row['Note']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:D' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:D' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:D1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);


            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "parampresence_attendance" . $ext);
            $data = file_get_contents($path_file . "parampresence_attendance" . $ext);
            force_download("parampresence_attendance" . $ext, $data);
        }
    }

}
