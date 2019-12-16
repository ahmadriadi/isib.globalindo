<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('jobg_model','jobgroup');
        $this->load->model('logs_model', 'logs');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('Param_model', 'param');
        $this->load->model('uac_model', 'uac');


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
        $idmenu = "154";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('ref03/home', $data);
    }

    function datajobgroup() {
        $idmodule = '83';
        $rowlogin = $this->login->get_by_user($this->User);
        $rowmenu = $this->access->get_by_idmenu($idmodule);
        $rowparam = $this->param->get_param($this->User);

        $check1 = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $check2 = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';

        $parameter = $rowparam->ParamValue;
        if ($parameter == $this->User) {
            $param = 'Y';
        } else {
            $param = 'N';
        }

        if ($check1 == 'exist' and $check2 == 'exist') {
            $role = $rowlogin->Role;
            if ($role == '1' or $role == '2') {
                echo $this->jobgroup->alljobgroup();
            } else if ($role == '0' and $param == 'Y') {
                echo $this->jobgroup->alljobgroup();
            }
        }
    }

    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '154';
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
        $data['default']['f01'] = ''; //ID Group      
        $data['default']['f02'] = ''; //Desc Group

        $data['url_post'] = site_url('ref03/home/addpost');
        $this->load->view('ref03/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'ID Group ', 'required');
        $this->form_validation->set_rules('f02', 'Desc Group', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));


            $record = array(
                'IDJobGroup' => $f01,
                'GroupName' => $f02,
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
                'controller' => site_url('ref03/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $rcheck = $this->jobgroup->checkdata($f01);
            if ($rcheck == 'exist') {
                $alert = 'insert failed, attendance date = ' . $f01 . ' already exist';
                $status = 'false';
            } else {
                $this->jobgroup->insert($record);
                $this->logs->insert($recordlog);
                $alert = 'insert data, success';
                $status = 'true';
            }

            $mesg = $alert;
            $valid = $status;
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
        $row = $this->jobgroup->get_by_id($id);
        $data['default']['f01'] = $row->IDJobGroup; //       
        $data['default']['f02'] = $row->GroupName; //  

        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('ref03/home/editpost');

        $this->load->view('ref03/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'ID Group ', 'required');
        $this->form_validation->set_rules('f02', 'Desc Group', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));

            $record = array(
                'IDJobGroup' => $f01,
                'GroupName' => $f02,
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
                'controller' => site_url('ref03/home/addnew'),
                'activities' => 'edit data id ' . $id
            );


            $this->jobgroup->update($id, $record);
            $this->logs->insert($recordlog);
            $mesg = 'update data, success ';
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
                       "err_f02":"' . $err_f02 . '",                    
                       "err_f03":"' . $err_f03 . '"' .
                '}';
        echo $json;
    }

    function delete($id) {
        $this->jobgroup->delete($id);
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
        $objSheet->setTitle('attendance report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:B1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('ID Group');
        $objSheet->getCell('B1')->setValue('Description Group');

        $result = $this->jobgroup->getall_data();
        if ($result != NULL) {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $objSheet->getCell('A' . $i)->setValue($row['IDJobGroup']);
                $objSheet->getCell('B' . $i)->setValue($row['GroupName']);
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
            $objWriter->save($path_file . "jobgroup_attendance" . $ext);
            $data = file_get_contents($path_file . "jobgroup_attendance" . $ext);
            force_download("jobgroup_attendance" . $ext, $data);
        }
    }

}
