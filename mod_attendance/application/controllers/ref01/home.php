<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('holiday_model', 'holiday');
        $this->load->model('historydata_model', 'history');
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
        $fromd = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');
        $check1 = ($fromd == '' or $fromd == null) ? 'empty' : 'exist';
        $check2 = ($untild == '' or $untild == null) ? 'empty' : 'exist';

        $data['test1'] = $fromd;
        $data['test2'] = $untild;

        if ($check1 == 'empty' and $check2 == 'empty') {
            $date = $this->periodpayroll();
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

	$idmenu                    = "94";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);

        $this->load->view('ref01/home', $data);
    }

    function periodpayroll() {
        // Periode Staff 25-24
        if (date('d') >= 25) {
            $selisih = date('d') - 25;
            //eg: 27-04-2012
            $from = date('d-m-Y', strtotime("-" . $selisih . " days", strtotime(date('d-m-Y'))));
            $until = date('d-m-Y', strtotime("+1 month -1 day", strtotime($from)));
        } else {
            // eg: 04-02-2012
            $selisih = 25 - date('d');
            $from = date('d-m-Y', strtotime("-1 month +" . $selisih . "days", strtotime(date('d-m-Y'))));
            $until = date('d-m-Y', strtotime("+1 month -1 day", strtotime($from)));
        }
        return $from . " " . $until;
    }

    function set_pattern_date() {
        $valid = "true";

        $fromdate = date('Y-m-d', strtotime($this->input->post('fromdate')));
        $untildate = date('Y-m-d', strtotime($this->input->post('untildate')));
        $this->session->set_userdata('fromdate', $fromdate);
        $this->session->set_userdata('untildate', $untildate);

        echo '{ "valid":"' . $valid . '"}';
    }

     function dataholiday() {
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        
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
               echo $this->holiday->allholiday($f, $u);
            } else if ($role == '0' and $param == 'Y') {
               echo $this->holiday->allholiday($f, $u);
            }
        }        
        
        
    }
    
     function get_access(){
        $button     = $this->anti_xss($this->input->post('btn'));
        $idmenu     = '94';
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

    function addnew() {
        $data['default']['f01'] = ''; //Date
        $data['default']['f02'][0]['value'] = 'OFF';
        $data['default']['f02'][0]['display'] = '-';
        $data['default']['f02'][1]['value'] = 'ALD';
        $data['default']['f02'][1]['display'] = 'ANNUAL LEAVE DEDUCTION';
        $data['default']['f03'] = ''; //Note

        $data['url_post'] = site_url('ref01/home/addpost');
        $this->load->view('ref01/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'Date Holiday', 'required');
        $this->form_validation->set_rules('f03', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = date('Y-m-d', strtotime($this->input->post('f01')));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));

            $record = array(
                'Date' => $f01,
                'Flag' => $f02,
                'Note' => $f03,
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
                'controller' => site_url('ref01/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $rcheck = $this->holiday->checkdata($f01);
            if ($rcheck == 'exist') {
                $alert = 'insert failed, holiday date = ' . $f03 . ' already exist';
                $status = 'false';
            } else {
                $this->holiday->insert($record);
                $this->logs->insert($recordlog);
                $alert = 'insert data, success';
                $status = 'true';
            }

            $mesg = $alert;
            $valid = $status;
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
                       "err_f02":"' . $err_f02 . '",  
                       "err_f03":"' . $err_f03 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->holiday->get_by_id($id);
        $data['default']['f01'] = date('d-m-Y', strtotime($row->Date)); //Date
        $type = $row->Flag;
        if ($type == 'ALD') {
            $data['default']['f02'][0]['value'] = 'ALD';
            $data['default']['f02'][0]['display'] = 'ANNUAL LEAVE DEDUCTION';
            $data['default']['f02'][1]['value'] = 'OFF';
            $data['default']['f02'][1]['display'] = '-';
        } else {
            $data['default']['f02'][0]['value'] = 'OFF';
            $data['default']['f02'][0]['display'] = '-';
            $data['default']['f02'][1]['value'] = 'ALD';
            $data['default']['f02'][1]['display'] = 'ANNUAL LEAVE DEDUCTION';
        }
        $data['default']['f03'] = $row->Note; //
        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('ref01/home/editpost');

        $this->load->view('ref01/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Holiday Date', 'required');
        $this->form_validation->set_rules('f03', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));

            $record = array(
                'Flag' => $f02,
                'Note' => $f03,
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
                'controller' => site_url('ref01/home/addnew'),
                'activities' => 'edit data id ' . $id
            );

            $this->historydata($id);
            $this->holiday->update($id, $record);
            $this->logs->insert($recordlog);
            $mesg = 'update data, success ';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
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
        $this->holiday->delete($id);
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
        $objSheet->setTitle('holiday report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('Holiday Date');
        $objSheet->getCell('B1')->setValue('Type');
        $objSheet->getCell('C1')->setValue('Note');

        $result = $this->holiday->getall_data();
        if ($result != NULL) {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $objSheet->getCell('A' . $i)->setValue(date('d-m-Y', strtotime($row['Date'])));
                $objSheet->getCell('B' . $i)->setValue($row['Flag']);
                $objSheet->getCell('C' . $i)->setValue($row['Note']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:C' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:C' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:C1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);


            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "holiday_attendance" . $ext);
            $data = file_get_contents($path_file . "holiday_attendance" . $ext);
            force_download("holiday_attendance" . $ext, $data);
        }
    }
    
    function historydata($id){
         $rowh = $this->holiday->get_by_id($id); 
            $record = array(
                "Date"=>$rowh->Date,
                "Note"=>$rowh->Note,
                "Flag"=>$rowh->Flag,
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
                "IDTable"=>$rowh->IDHoliday,
                "FunctionOn"=>'edit',
                "HistBy"=>$this->User,
                "HistDate"=>$this->Datetime,
                "HistIP"=>$this->Ip
            );
            
            $this->history->holiday($record);
            
    }

}

