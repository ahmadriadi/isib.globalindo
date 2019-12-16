<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('fakes_model', 'fakes');
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
        $this->load->view('ref06/home', $data);
    }

    function getdatatable() {
        echo $this->fakes->getdata();
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
        $data['default']['f02'] = '';
        $data['default']['f03'] = '';
        $data['default']['f04'] = '';
        $data['default']['f05'] = '';
        $data['default']['f06'] = '';
        $data['default']['f07'] = '';
        $data['default']['f08'] = '';
        $data['url_post'] = site_url('ref06/home/addpost');
        $this->load->view('ref06/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'Divre Code', 'required');
        $this->form_validation->set_rules('f02', 'Regional Name', 'required');
        $this->form_validation->set_rules('f03', 'KC Name', 'required');
        $this->form_validation->set_rules('f04', 'Dati Name', 'required');
        $this->form_validation->set_rules('f05', 'Faskes Code', 'required');
        $this->form_validation->set_rules('f06', 'Fakes Name', 'required');
        $this->form_validation->set_rules('f07', 'Fakes Type', 'required');
        $this->form_validation->set_rules('f08', 'Fakes Address', 'required');
        
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f06 = $this->anti_xss($this->input->post('f06'));
            $f07 = $this->anti_xss($this->input->post('f07'));
            $f08 = $this->anti_xss($this->input->post('f08'));


            $record = array(
                'DivreCode' => $f01,
                'RegionalName' => $f02,
                'KCName' => $f03,
                'DatiName2' => $f04,
                'FaskesCode' => $f05,
                'FakesName' => $f06,
                'FakesType' => $f07,
                'FakesAddress' => $f08,
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
                'controller' => site_url('ref06/home/addnew'),
                'activities' => 'add new param ' . $f01
            );

            $rcheck = $this->fakes->checkdata($f05);
            if ($rcheck == 'exist') {
                $alert = 'insert failed, fakes code ' . $f05 . ' already exist';
                $status = 'false';
            } else {
                $this->fakes->insert($record);
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
            $err_f07 = '';
            $err_f08 = '';
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
            $err_f07 = form_error('f07');
            $err_f08 = form_error('f08');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
                       "err_f02":"' . $err_f02 . '",  
                       "err_f03":"' . $err_f03 . '",  
                       "err_f04":"' . $err_f04 . '",  
                       "err_f05":"' . $err_f05 . '",  
                       "err_f06":"' . $err_f06 . '",  
                       "err_f07":"' . $err_f07 . '",  
                       "err_f08":"' . $err_f08 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->fakes->getby_id($id);

        $data['default']['f01'] =$row->DivreCode; //
        $data['default']['f02'] =$row->RegionalName; //
        $data['default']['f03'] = $row->KCName; //
        $data['default']['f04'] = $row->DatiName2; //
        $data['default']['f05'] = $row->FaskesCode; //
        $data['default']['f06'] = $row->FakesName; //
        $data['default']['f07'] = $row->FakesType; //
        $data['default']['f08'] = $row->FakesAddress; //

        $data['url_post'] = site_url('ref06/home/editpost');

        $this->load->view('ref06/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Date', 'required');
        $this->form_validation->set_rules('f02', 'Location', 'required');
        $this->form_validation->set_rules('f03', 'Value 2', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f06 = $this->anti_xss($this->input->post('f06'));
            $f07 = $this->anti_xss($this->input->post('f07'));
            $f08 = $this->anti_xss($this->input->post('f08'));


            $record = array(
                'DivreCode' => $f01,
                'RegionalName' => $f02,
                'KCName' => $f03,
                'DatiName2' => $f04,
                'FaskesCode' => $f05,
                'FakesName' => $f06,
                'FakesType' => $f07,
                'FakesAddress' => $f08,
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
                'controller' => site_url('ref06/home/addnew'),
                'activities' => 'edit data id ' . $id
            );


            $this->fakes->update($id, $record);
            $this->logs->insert($recordlog);
            $mesg = 'update data, success ';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
            $err_f06 = '';
            $err_f07 = '';
            $err_f08 = '';
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
            $err_f07 = form_error('f07');
            $err_f08 = form_error('f08');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",                                      
                       "err_f02":"' . $err_f02 . '",                    
                       "err_f03":"' . $err_f03 . '",                    
                       "err_f04":"' . $err_f04 . '",                    
                       "err_f05":"' . $err_f05 . '",                    
                       "err_f06":"' . $err_f06 . '",                    
                       "err_f07":"' . $err_f07 . '",                    
                       "err_f08":"' . $err_f08 . '"' .
                '}';
        echo $json;
    }

    function delete($id) {
        $this->fakes->update($id);
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
        $objSheet->setTitle('fakes report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('Divre Code');
        $objSheet->getCell('B1')->setValue('Regional Name');
        $objSheet->getCell('C1')->setValue('KC Name');
        $objSheet->getCell('D1')->setValue('Dati Name');
        $objSheet->getCell('E1')->setValue('Faskes Code');
        $objSheet->getCell('F1')->setValue('Fakes Name');
        $objSheet->getCell('G1')->setValue('Fakes Type');
        $objSheet->getCell('H1')->setValue('Fakes Address');

        $result = $this->fakes->getall_data();
        if ($result != NULL) {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $objSheet->getCell('A' . $i)->setValue("'".$row['DivreCode']);
                $objSheet->getCell('B' . $i)->setValue($row['RegionalName']);
                $objSheet->getCell('C' . $i)->setValue($row['KCName']);
                $objSheet->getCell('D' . $i)->setValue($row['DatiName2']);
                $objSheet->getCell('E' . $i)->setValue("'".$row['FaskesCode']);
                $objSheet->getCell('F' . $i)->setValue($row['FakesName']);
                $objSheet->getCell('G' . $i)->setValue($row['FakesType']);
                $objSheet->getCell('H' . $i)->setValue($row['FakesAddress']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:H' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:H' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:H1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);
            $objSheet->getColumnDimension('F')->setAutoSize(true);
            $objSheet->getColumnDimension('G')->setAutoSize(true);
            $objSheet->getColumnDimension('H')->setAutoSize(true);


            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "fakes" . $ext);
            $data = file_get_contents($path_file . "fakes" . $ext);
            force_download("fakes" . $ext, $data);
        }
    }

}
