<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('picket_model', 'picket');
        $this->load->model('employee_model', 'employee');
        $this->load->model('logs_model', 'logs');
        $this->load->model('historydata_model', 'history');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('param_model', 'pharam');
        $this->load->model('uac_model', 'uac');
	$this->load->model('libraryfunction_model','libfun');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }


    function index() {
        $fromd = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');
        $check1 = ($fromd == '' or $fromd == null) ? 'empty' : 'exist';
        $check2 = ($untild == '' or $untild == null) ? 'empty' : 'exist';

	$query = $this->employee->get_rjob()->result();
        $i = 0;
        foreach ($query as $r) {
            $i++;
            $data['default']['group'][$i]['value'] = $r->IDJobGroup;
            $data['default']['group'][$i]['display'] = $r->GroupName;   
        }

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

        $idmenu = "312";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);

        $this->load->view('trx12/home', $data);
    }

    function getdatatable() {

        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        
        
        /*
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
        
         * 
         */
         echo $this->picket->getdata($f, $u);
        
       
    }

    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '312';
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

   
    function set_pattern_date() {
        $valid = "true";

        $fromdate = date('Y-m-d', strtotime($this->input->post('fromdate')));
        $untildate = date('Y-m-d', strtotime($this->input->post('untildate')));
        $this->session->set_userdata('fromdate', $fromdate);
        $this->session->set_userdata('untildate', $untildate);

        echo '{ "valid":"' . $valid . '"}';
    }

    function autocomplete_employee() {
        $result = $this->employee->find_employee_active();
        $arr = array();
        foreach ($result->result() as $row) {
            $nip = $row->IDEmployee;
            $user = $this->User;
               // if ($nip !== $user) { 
                    $arr[] = array('idemployee' => $row->IDEmployee,
                                   'fullname' => strtoupper($row->FullName)
                ); 
               // }
            
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
 
 
    function addnew() {
        $data['default']['f01'] = ''; //IDemployee
        $data['default']['f02'] = ''; //FullName
        
        //RangePicket
        $data['default']['f03'][1]['value'] = "1";
        $data['default']['f03'][1]['display'] = "One Day";
        $data['default']['f03'][1]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value'] = "2";
        $data['default']['f03'][2]['display'] = "More than one days";
        
        
        $data['default']['f04'] = ''; //Fromdate      
        $data['default']['f05'] = ''; //Untildate    
        
        //StatusPicket
        $data['default']['f06'][1]['value'] = "A";
        $data['default']['f06'][1]['display'] = "Active";
        $data['default']['f06'][1]['checked'] = "CHECKED";
        $data['default']['f06'][2]['value'] = "P";
        $data['default']['f06'][2]['display'] = "Passive";
        
        $data['default']['f07'] = '';//Note

        $data['iddata'] = '';
        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('trx12/home/addpost');
        $this->load->view('trx12/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f04', 'From Date', 'required');
        $this->form_validation->set_rules('f05', 'Until Date', 'required');
        
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->input->post('f01');
            $f03 = $this->input->post('f03');
            $f04 = date('Y-m-d',  strtotime($this->input->post('f04')));
            $f05 = date('Y-m-d',  strtotime($this->input->post('f05')));
            $f06 = $this->input->post('f06');
            $f07 = $this->input->post('f07');
            
           
            
              $record = array(
                 'IDEmployee' => $f01,
                 'RangePicket' => $f03,
                 'FromDate' => $f04,
                 'UntilDate' => $f05,
                 'StatusPicket' => $f06,
                 'Note' => $f07,
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
                'controller' => site_url('trx12/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $checkdata = $this->picket->checkdata($f01,$f04,$f05);
            
            
            if($checkdata =='empty'){
                 $this->picket->insert($record);
                 $this->logs->insert($recordlog);                 
                 $status = 'true';
                 $alert = 'insert data, success';
                
            }else{               
                 $status = 'false';
                 $alert = 'insert data, failed because the data already exist';
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
            $err_f09 = '';
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
            $err_f09 = form_error('f09');
         
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
                       "err_f08":"' . $err_f08 . '",
                       "err_f09":"' . $err_f09 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $row = $this->picket->getby_id($id);        
        $data['default']['f01'] =$row->IDEmployee;
        $data['default']['f02'] =$row->FullName;
        
        //RangePicket
        $data['default']['f03'][1]['value'] = "1";
        $data['default']['f03'][1]['display'] = "One Day";
        $data['default']['f03'][2]['value'] = "2";
        $data['default']['f03'][2]['display'] = "More than one days";
        if($row->RangePicket == '1'){
            $data['default']['f03'][1]['checked'] = "CHECKED";
        }else if($row->RangePicket == '2'){
            $data['StatusPicket']['f03'][2]['checked'] = "CHECKED";
        }
        
        $data['default']['f04'] = date('d-m-Y',  strtotime($row->FromDate));      
        $data['default']['f05'] = date('d-m-Y',  strtotime($row->UntilDate));         
        
        //StatusPicket
        $data['default']['f06'][1]['value'] = "A";
        $data['default']['f06'][1]['display'] = "Active";
        $data['default']['f06'][1]['checked'] = "";
        $data['default']['f06'][2]['value'] = "P";
        $data['default']['f06'][2]['display'] = "Passive";
        if($row->StatusPicket == 'A'){
            $data['default']['f06'][1]['checked'] = "CHECKED";
        }else if($row->StatusPicket == 'P'){
            $data['StatusPicket']['f06'][2]['checked'] = "CHECKED";
        }
        
        
        $data['default']['f07'] = $row->Note;

        $data['iddata'] = $id;
        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('trx12/home/editpost');
        $this->load->view('trx12/form', $data);

    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f04', 'From Date', 'required');
        $this->form_validation->set_rules('f05', 'Until Date', 'required');
        
        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post('id'); 
            $f03 = $this->input->post('f03');
            $f04 = date('Y-m-d',  strtotime($this->input->post('f04')));
            $f05 = date('Y-m-d',  strtotime($this->input->post('f05')));
            $f06 = $this->input->post('f06');
            $f07 = $this->input->post('f07');           
            
              $record = array(
                 'RangePicket' => $f03,
                 'FromDate' => $f04,
                 'UntilDate' => $f05,
                 'StatusPicket' => $f06,
                 'Note' => $f07,
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
                'controller' => site_url('trx12/home/addnew'),
                'activities' => 'edit data id :' . $id
            );

           
            $this->historydata($id);
            $this->picket->update($id,$record);
            $this->logs->insert($recordlog);                 
            $status = 'true';
            $alert = 'update data, success';
            
           

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
            $err_f09 = '';
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
            $err_f09 = form_error('f09');
         
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
                       "err_f08":"' . $err_f08 . '",
                       "err_f09":"' . $err_f09 . '"' .
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

        $this->picket->update($id, $record);
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
        $objSheet->setTitle('Permission to Leave Work Report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(12);

        // write header        
        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');
        $objSheet->getCell('C1')->setValue('Group');
        $objSheet->getCell('D1')->setValue('From Date');
        $objSheet->getCell('E1')->setValue('Until Date');
        $objSheet->getCell('F1')->setValue('Range Picket');
        $objSheet->getCell('G1')->setValue('Status');
        $objSheet->getCell('H1')->setValue('Note');


        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->picket->getall_data($f,$u);


        if ($result !== 'empty') {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $group = $this->libfun->get_name_group($row['IDJobGroup']);

                if($row['StatusPicket']=='A'){
                    $status='Active';                    
                }else{                    
                    $status = 'Passive';
                }
                
                
                  if($row['RangePicket']=='1'){
                     $range='One Day';                    
                }else{                    
                    $range = 'More than one days';
                }
                
                
                
                $objSheet->getCell('A' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('B' . $i)->setValue($row['FullName']);
                $objSheet->getCell('C' . $i)->setValue($group);
                $objSheet->getCell('D' . $i)->setValue($row['FromDate']);
                $objSheet->getCell('E' . $i)->setValue($row['UntilDate']);
                $objSheet->getCell('F' . $i)->setValue($range);
                $objSheet->getCell('G' . $i)->setValue($status);
                $objSheet->getCell('H' . $i)->setValue($row['Note']);
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
            $objWriter->save($path_file . "employeepicket" . $ext);
            $data = file_get_contents($path_file . "employeepicket" . $ext);
            force_download("employeepicket" . $ext, $data);
        }
    }
    
    
       function historydata($id){
         $rowh = $this->picket->getby_id($id); 
            $record = array(
                "IDEmployee"=>$rowh->IDEmployee,
                "RangePicket"=>$rowh->RangePicket,
                "FromDate"=>$rowh->FromDate,
                "UntilDate"=>$rowh->UntilDate,
                "StatusPicket"=>$rowh->StatusPicket,
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
            
            $this->history->insert_his_employeepicket($record);
            
    }

}
