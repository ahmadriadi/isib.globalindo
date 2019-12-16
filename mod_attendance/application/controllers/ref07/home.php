<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('paramlate_model', 'paramlate');
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
        $idmenu = "226";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('ref07/home', $data);
    }

    function getdatatable() {
        echo $this->paramlate->getdata();
    }

    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '226';
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
        $data['default']['f01'] = ''; //PresenceDate
        //IDLocation
        $data['default']['f02'][1]['value'] = "1";
        $data['default']['f02'][1]['display'] = "Kapuk";
        $data['default']['f02'][1]['checked'] = "";
        $data['default']['f02'][2]['value'] = "2";
        $data['default']['f02'][2]['display'] = "Bitung";
        //StartTimeLate
        $data['default']['f03'][-1]['value'] = NULL;
        $data['default']['f03'][-1]['display'] = '-Please Select-';
        $data['default']['f03'][1]['value'] = 'N2';
        $data['default']['f03'][1]['display'] = '16:30';
        $data['default']['f03'][2]['value'] = 'N3';
        $data['default']['f03'][2]['display'] = '13:00';
        $data['default']['f03'][3]['value'] = 'OFF';
        $data['default']['f03'][3]['display'] = '--:--';

        $data['default']['f04'] = ''; ///Note
        $data['url_post'] = site_url('ref07/home/addpost');
        $this->load->view('ref07/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'Date', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = date('Y-m-d', strtotime($this->anti_xss($this->input->post('f01'))));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));


            $record = array(
                'ParamDate' => $f01,
                'ParamSite' => $f02,
                'StartTimeLate' => $f03,
                'Note' => $f04,
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
                'controller' => site_url('ref07/home/addnew'),
                'activities' => 'add new param ' . $f01
            );

            $rcheck = $this->paramlate->checkdata($f01, $f02);
            if ($rcheck == 'exist') {
                $alert = 'insert failed, paramlate  ' . $f01 . ' already exist';
                $status = 'false';
            } else {
                $this->paramlate->insert($record);
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
        } else {
            $mesg = 'insert data, failed';
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

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->paramlate->getby_id($id);
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

        //StartTimeLate
        $data['default']['f03'][1]['value'] = 'N2';
        $data['default']['f03'][1]['display'] = '16:30';
        $data['default']['f03'][2]['value'] = 'N3';
        $data['default']['f03'][2]['display'] = '13:00';
	$data['default']['f03'][3]['value'] = 'OFF';
        $data['default']['f03'][3]['display'] = '--:--';

        if ($row->StartTimeLate == 'N2') {
            $data['default']['f03'][1]['selected'] = "SELECTED";
        } else if ($row->StartTimeLate == 'N3') {
            $data['default']['f03'][2]['selected'] = "SELECTED";
        } else if ($row->StartTimeLate == 'OFF') {
            $data['default']['f03'][3]['selected'] = "SELECTED";
        }

        $data['default']['f04'] = $row->Note; //
        $data['url_post'] = site_url('ref07/home/editpost');
        $this->load->view('ref07/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Date', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = date('Y-m-d', strtotime($this->anti_xss($this->input->post('f01'))));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));

            $record = array(
                'ParamDate' => $f01,
                'ParamSite' => $f02,
                'StartTimeLate' => $f03,
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
                'controller' => site_url('ref07/home/addnew'),
                'activities' => 'edit data id ' . $id
            );


            $this->paramlate->update($id, $record);
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
        $this->paramlate->delete($id);
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
        $objSheet->setTitle('paramlate report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('Date');
        $objSheet->getCell('B1')->setValue('Location');
        $objSheet->getCell('C1')->setValue('Work Hour');
        $objSheet->getCell('D1')->setValue('Note');

        $result = $this->paramlate->getall_data();
        if ($result != NULL) {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $objSheet->getCell('A' . $i)->setValue($row['ParamDate']);
                $objSheet->getCell('B' . $i)->setValue($row['ParamSite']);
                $objSheet->getCell('C' . $i)->setValue($row['StartTimeLate']);
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
            $objWriter->save($path_file . "paramlate_attendance" . $ext);
            $data = file_get_contents($path_file . "paramlate_attendance" . $ext);
            force_download("paramlate_attendance" . $ext, $data);
        }
    }

}
