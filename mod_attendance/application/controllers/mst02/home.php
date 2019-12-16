<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('cardmap_model', 'card');
        $this->load->model('Employee_model', 'employee');
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
	$query = $this->employee->get_rjob()->result();
        $i = 0;
        foreach ($query as $r) {
            $i++;
            $data['default']['group'][$i]['value'] = $r->IDJobGroup;
            $data['default']['group'][$i]['display'] = $r->GroupName;   
        }		
        
        $data['default']['status'][0]['value'] = 'AL';
        $data['default']['status'][0]['display'] = 'ALL';
        $data['default']['status'][0]['selected'] = "SELECTED";
        $data['default']['status'][1]['value'] = 'T';
        $data['default']['status'][1]['display'] = 'ACTIVE';
        $data['default']['status'][2]['value'] = 'F';
        $data['default']['status'][2]['display'] = 'PASSIVE';
	
        $idmenu                    = "89";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('mst02/home',$data);
    }

    function datacard() {
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
                echo $this->card->allcard();
            } else if ($role == '0' and $param == 'Y') {
                echo $this->card->allcard();
            }
        }        
      
    }
    
     function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '89';
        $row        = $this->uac->getdata_button($this->User,$idmenu,$button);
        $check      = ($row ==null or $row =='')?'empty':'exist';
        
        if($check !=='empty'){
                $access = $row->kdbutton;  
                $mesg = "Result Button";
                $valid = 'true';
        }else{           
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

   function autocomplete_employee() {
        $result = $this->employee->find_employee_active();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                           'fullname' => strtoupper($row->FullName)
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

   function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->search_employee_active($q);
        $data['response'] = 'true';
        $data['message'] = array();
        foreach ($result->result() as $row) {
                        $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                        'value' => $row->FullName,
                        'idemployee' => $row->IDEmployee
                    );
        }
        echo json_encode($data);
    }

    function addnew() {
        $data['default']['f01'] = ''; //idemployee
        $data['default']['f02'] = ''; //fullname
        $data['default']['f03'] = ''; //enroll
        $data['default']['f04'][1]['value'] = "2";
        $data['default']['f04'][1]['display'] = "RFID";
        $data['default']['f04'][1]['checked'] = "CHECKED";
        $data['default']['f04'][2]['value'] = "1";
        $data['default']['f04'][2]['display'] = "Barcode";
        $data['default']['f05'] = ''; //RFID Number
        
        $data['default']['f06'][0]['value'] = "T";
        $data['default']['f06'][0]['display'] = "Active";
        $data['default']['f06'][0]['checked'] = "CHECKED";
        $data['default']['f06'][1]['value'] = "F";
        $data['default']['f06'][1]['display'] = "Pasive";

        $data['url_post'] = site_url('mst02/home/addpost');
        $data['default']['readonly_f01'] = 'READONLY';
        $this->load->view('mst02/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required|numeric');
        $this->form_validation->set_rules('f03', 'Enroll Number', 'required|numeric');
        $this->form_validation->set_rules('f05', 'RFID Number', 'required|numeric');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f06 = $this->anti_xss($this->input->post('f06'));

            $temp = "00000000" . $f03;
            $enroll = substr($temp, -8);


            $record = array(
                'IDCard' => $enroll,
                'IDEmployee' => $f01,
                'CardType' => $f04,
                'CardNumber' => $f05,
                'LastStatus' => $f06,
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
                'controller' => site_url('mst02/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $rcheck = $this->card->checkdata($enroll);
            if ($rcheck == 'exist') {
                $alert = 'insert failed, enroll number = ' . $f03 . ' already exist';
                $status = 'false';
            } else {
                $this->card->insert($record);
                $this->logs->insert($recordlog);
                $alert = 'insert data, success with enroll number = ' . $f03;
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
        $row = $this->card->get_by_id($id);
        $data['default']['f01'] = $row->IDEmployee; //idemployee
        $data['default']['f02'] = $row->FullName; //fullname 
        $data['default']['f03'] = $row->IDCard; //
        $data['default']['f04'][1]['value'] = "2";
        $data['default']['f04'][1]['display'] = "RFID";
        $data['default']['f04'][2]['value'] = "1";
        $data['default']['f04'][2]['display'] = "Barcode";

        if ($row->CardType == '2') {
            $data['default']['f04'][1]['checked'] = "CHECKED";
        } else if ($row->CardType == '1') {
            $data['default']['f04'][2]['checked'] = "CHECKED";
        }

        $data['default']['f05'] = $row->CardNumber;
        $data['default']['f06'][0]['value'] = "T";
        $data['default']['f06'][0]['display'] = "Active";
        $data['default']['f06'][0]['checked'] = "";
        $data['default']['f06'][1]['value'] = "F";
        $data['default']['f06'][1]['display'] = "Pasive";
        
        if ($row->LastStatus == 'T') {
            $data['default']['f06'][0]['checked'] = "CHECKED";
        } else {
            $data['default']['f06'][1]['checked'] = "CHECKED";
        }
        
        
        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f03'] = 'READONLY';
        $data['url_post'] = site_url('mst02/home/editpost');

        $this->load->view('mst02/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required|numeric');
        $this->form_validation->set_rules('f03', 'Enroll Number', 'required|numeric');
        $this->form_validation->set_rules('f05', 'RFID Number', 'required|numeric');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f06 = $this->anti_xss($this->input->post('f06'));

            $temp = "00000000" . $f03;
            $enroll = substr($temp, -8);

            $record = array(
                'IDCard' => $enroll,
                'IDEmployee' => $f01,
                'CardType' => $f04,
                'CardNumber' => $f05,
                'LastStatus' => $f06,
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
                'controller' => site_url('mst02/home/addnew'),
                'activities' => 'edit data ' . $f01
            );


            $this->card->update($id, $record);
            $this->logs->insert($recordlog);
            $mesg = 'update data, success with enroll number = ' . $enroll;
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
            $err_f06 = '';
        } else {
            $mesg = 'update data, failed';
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

    function delete($id) {
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );

        $this->card->update($id, $record);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function exportdata($group,$status) {
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
        $objSheet->setTitle('card report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:F1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('Enroll Number');
        $objSheet->getCell('B1')->setValue('IDEmployee');
        $objSheet->getCell('C1')->setValue('FullName');
        $objSheet->getCell('D1')->setValue('Card Type');
        $objSheet->getCell('E1')->setValue('Status');
        $objSheet->getCell('F1')->setValue('RFID Number');

        $result = $this->card->getall_data($group,$status);
        if ($result != NULL) {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $type = ($row['CardType'] == '1') ? 'Barcode' : 'RFID';
                $status = ($row['LastStatus'] == 'T') ? 'Active' : 'Passive';

                $objSheet->getCell('A' . $i)->setValue("'" . $row['IDCard']);
                $objSheet->getCell('B' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($row['FullName']);
                $objSheet->getCell('D' . $i)->setValue($type);
                $objSheet->getCell('E' . $i)->setValue($status);
                $objSheet->getCell('F' . $i)->setValue("'" . $row['CardNumber']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:F' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:F' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:F1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);
            $objSheet->getColumnDimension('F')->setAutoSize(true);

            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "card_attendance" . $ext);
            $data = file_get_contents($path_file . "card_attendance" . $ext);
            force_download("card_attendance" . $ext, $data);
        }
    }

}

