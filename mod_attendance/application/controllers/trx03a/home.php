<?php

//INCOMPLETE
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Incomplete_trx_model', 'incomplete');
        $this->load->model('Employee_model', 'employee');
        $this->load->model('logs_model', 'logs');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
	$this->load->model('param_model', 'param');
	$this->load->model('public_model', 'pbl');
	$this->load->model('uac_model', 'uac');	
	$this->load->model('libraryfunction_model','libfun');

	
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
        // $data['test1'] = $fromd;
        //  $data['test2'] = $untild;        
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


        $query = $this->employee->get_rjob()->result();
        $i = 0;
        foreach ($query as $r) {
            $i++;
            $data['default']['group'][$i]['value'] = $r->IDJobGroup;
            $data['default']['group'][$i]['display'] = $r->GroupName;   
        }
        $data['default']['from'] = date('d-m-Y', strtotime($fromdate));
        $data['default']['until'] = date('d-m-Y', strtotime($untildate));

	$idmenu = "115";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('trx03a/home', $data);
    }

    function dataincomplete() {
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $iduser = $this->session->userdata('sess_userid');
        $idmodule = '83';

        $rowlogin = $this->login->get_by_user($iduser);
        $rowmenu = $this->access->get_by_idmenu($idmodule);
        $rowpersonal = $this->employee->get_position($iduser);

        $check1 = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $check2 = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';

        if ($check1 == 'exist' and $check2 == 'exist') {
            $role = $rowlogin->Role;
            $position = $rowpersonal->Position;
            if ($role == '1' or $role == '2' or $position == 'SUPERVISOR') {
                echo $this->incomplete->incompleteall($f, $u);
            }
        }
    }

     function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '115';
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
	

    function getstatus() {
	$iduser = $this->session->userdata('sess_userid');
        $rowparam = $this->param->get_param($iduser);        
        $checkparam = ($rowparam == '' or $rowparam == null) ? 'empty' : 'exist'; 
        
        if ($checkparam == 'exist') {
            $parameter = $rowparam->ParamValue;
            if ($parameter == $iduser) {
                $access = 'true';
            } else {
                $access= 'false';
            }
        } else {
                 $access = 'false';
        }
	
        $id = $this->input->post('id');
        $rowh = $this->incomplete->get_by_id($id);
        $name = $rowh->FullName;
        $status = $rowh->ConfirmFlag;
        $flag = $rowh->FlagInput;
        $valid = 'true';
        $json = '{ "status":"' . $status . '",
                   "inputby":"' . $flag . '",
		   "accessbtn":"' . $access . '",	
                   "name":"' . $name . '",
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
                if ($nip !== $user) { 
                    $arr[] = array('idemployee' => $row->IDEmployee,
                               'fullname' => strtoupper($row->FullName)
                ); 
                }
            
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
	

    function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->search_employee_active($q);
        $data['response'] = 'true';
        $data['message'] = array();
        foreach ($result->result() as $row) {           
            $nip = $row->IDEmployee;
            $user = $this->User;
            if ($nip !== $user) {                
                $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                    'value' => $row->FullName,
                    'idemployee' => $row->IDEmployee
                );               
            }
        }
        echo json_encode($data);
    }

    function checktimein() {
        $time = $this->input->post('f04');
        $len = strlen($time);
        $hour = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        if ($len < 4 | $len > 4) {
            $this->form_validation->set_message("checktimein", "Length of string :" . $len . " is to short");
            return false;
        } else {
            if ($hour < 0 | $hour > 23) {
                $this->form_validation->set_message("checktimein", "Hour string should not below under 0 or exceed 23 ");
                return false;
            } else {
                if ($minutes < 0 | $minutes > 59) {
                    $this->form_validation->set_message("checktimein", "Minute string should not below under  0 or exceed 59 ");
                    return false;
                } else {
                    return true;
                }
            }
        }
    }

    function checktimeout() {
        $time = $this->input->post('f05');
        $len = strlen($time);
        $hour = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        if ($len < 4 | $len > 4) {
            $this->form_validation->set_message("checktimeout", "Length of string :" . $len . " is to short");
            return false;
        } else {

            if ($hour < 0 | $hour > 23) {
                $this->form_validation->set_message("checktimeout", "Hour string should not below under 0 or exceed 23 ");
                return false;
            } else {
                if ($minutes < 0 | $minutes > 59) {
                    $this->form_validation->set_message("checktimeout", "Minute string should not below under  0 or exceed 59 ");
                    return false;
                } else {
                    return true;
                }
            }
        }
    }


    function atluser(){
        $f = date('Y-m-d',  strtotime($this->session->userdata('datefrom')));
        $u = date('Y-m-d',  strtotime($this->session->userdata('dateuntil')));      
        $user = $this->input->post('idemployee');
        $count = $this->incomplete->countatl($f,$u,$user);   
        $valid = 'true';        
        $json = '{ "youratl":"' . $count . '",
                    "valid":"' . $valid . '"' .
                '}';
        echo $json;        
    }
    
    
    function checkatal(){
        $f = date('Y-m-d',  strtotime($this->session->userdata('datefrom')));
        $u = date('Y-m-d',  strtotime($this->input->post('incompletedate')));
        $user = $this->input->post('idemployee');
        $count = $this->incomplete->countatl($f,$u,$user);   
        $valid = 'true';        
        
        if($count > 1){
            $msg='warning';
        }else{
            $msg='info';
        }
        
        $json = '{ "infoatl":"' . $msg . '",
                    "valid":"' . $valid . '"' .
                '}';
        echo $json;        
    }
	


    function addnew() {
        $data['default']['f01'] = ''; //IDEmployee       
        $data['default']['f02'] = ''; //fullname
        $data['default']['f03'] = ''; //IncompleteDate
        $data['default']['f04'] = ''; //TimeIn
        $data['default']['f05'] = ''; //TimeOut
        $data['default']['f06'] = ''; //Note

        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('trx03a/home/addpost');
        $this->load->view('trx03a/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f03', 'Incomplete Date', 'required');
        $this->form_validation->set_rules('f04', 'Time In', 'required|callback_checktimein');
        $this->form_validation->set_rules('f05', 'Time Out', 'required|callback_checktimeout');

        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $h1 = substr($f04, 0, 2);
            $m1 = substr($f04, 2, 2);
            $timein = $h1 . ':' . $m1;
            $f05 = $this->anti_xss($this->input->post('f05'));
            $h2 = substr($f05, 0, 2);
            $m2 = substr($f05, 2, 2);
            $timeout = $h2 . ':' . $m2;
            $f06 = $this->anti_xss($this->input->post('f06'));
           
            $record = array(
                'IDEmployee' => $f01,
                'IncompleteDate' => date('Y-m-d', strtotime($f03)),
                'TimeIn' => $timein,
                'TimeOut' => $timeout,
                'Note' => TRIM($f06),
                'ConfirmFlag' => "1",
                'ConfirmDate' => $this->Datetime,
                'ConfirmIP' => $this->Ip,
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
                'controller' => site_url('trx03a/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $resultcheck = $this->incomplete->checkdata($f01, $f03);
            if ($resultcheck == 'exist') {
                $mesg = 'insert data, failed because idemployee :' . $f01 . ' incomplete date :' . $f03 . ' already exist';
                $valid = 'false';
                $err_f01 = 'ERROR';
                $err_f02 = 'ERROR';
                $err_f03 = 'ERROR';
                $err_f04 = '';
                $err_f05 = '';
                $err_f06 = '';
            } else {
                $this->incomplete->insert($record);
                $this->logs->insert($recordlog);
                $mesg = 'insert data, success';
                $valid = 'true';
                $err_f01 = '';
                $err_f02 = '';
                $err_f03 = '';
                $err_f04 = '';
                $err_f05 = '';
                $err_f06 = '';
            }
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
        $row = $this->incomplete->get_by_id($id);
        $data['default']['f01'] = $row->IDEmployee; //idemployee
        $data['default']['f02'] = $row->FullName; //fullname
        $data['default']['f03'] = date('d-m-Y', strtotime($row->IncompleteDate)); //presencedate
        $data['default']['f04'] = date('Hi', strtotime($row->TimeIn));
        $data['default']['f05'] = date('Hi', strtotime($row->TimeOut));
        $data['default']['f06'] = $row->Note; //note

        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';
        $data['default']['readonly_f03'] = 'READONLY';
        $data['url_post'] = site_url('trx03a/home/editpost');
        $this->load->view('trx03a/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f03', 'Presence Date', 'required');
        $this->form_validation->set_rules('f04', 'Time In', 'required');
        $this->form_validation->set_rules('f05', 'Time Out', 'required');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $h1 = substr($f04, 0, 2);
            $m1 = substr($f04, 2, 2);
            $timein = $h1 . ':' . $m1;
            $f05 = $this->anti_xss($this->input->post('f05'));
            $h2 = substr($f05, 0, 2);
            $m2 = substr($f05, 2, 2);
            $timeout = $h2 . ':' . $m2;
            $f06 = $this->anti_xss($this->input->post('f06'));

            $record = array(
		'FlagInput' => 'hrd',
                'IDEmployee' => $f01,
                'IncompleteDate' => date('Y-m-d', strtotime($f03)),
                'TimeIn' => $timein,
                'TimeOut' => $timeout,
                'Note' => TRIM($f06),
                'ConfirmFlag' => "1",
                'ConfirmDate' => $this->Datetime,
                'ConfirmIP' => $this->Ip,
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
                'controller' => site_url('trx03a/home/addnew'),
                'activities' => 'edit new ' . $f01
            );


            $this->incomplete->update($id, $record);
            $this->logs->insert($recordlog);
            $mesg = 'update data, success';
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

        $this->incomplete->update($id, $record);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

     function exportdata($g) {
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
        $objSheet->setTitle('incomplete report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(12);

        // write header

        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');
        $objSheet->getCell('C1')->setValue('FullName');
        $objSheet->getCell('D1')->setValue('Incomplete Date');
        $objSheet->getCell('E1')->setValue('TimeIn');
        $objSheet->getCell('F1')->setValue('TimeOut');
        $objSheet->getCell('G1')->setValue('Note');        
        
        $pembatas = array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                           'startcolor' => array('rgb' => 'FCFC0C')
                          );
        
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->incomplete->getall_data($f,$u,$g);
        if ($result != NULL) {
            $i = 1;                
            $lastid = '';
            foreach ($result as $row) {
                
                $group = $this->libfun->get_name_group($row['IDJobGroup']);     
                $i++; 
                
                if($lastid == $row['IDEmployee']){
                    $objSheet->getStyle('A' . $i . ':G' . $i)->getFill()->applyFromArray($pembatas);
                    $objSheet->getCell('A' . $i)->setValue("'" . $row['IDEmployee']);
                    $objSheet->getCell('B' . $i)->setValue($row['FullName']);
                    $objSheet->getCell('C' . $i)->setValue($group);
                    $objSheet->getCell('D' . $i)->setValue($row['IncompleteDate']);
                    $objSheet->getCell('E' . $i)->setValue($row['TimeIn']);
                    $objSheet->getCell('F' . $i)->setValue($row['TimeOut']);
                    $objSheet->getCell('G' . $i)->setValue($row['Note']);                     
                }else{
                    $objSheet->getCell('A' . $i)->setValue("'" . $row['IDEmployee']);
                    $objSheet->getCell('B' . $i)->setValue($row['FullName']);
                    $objSheet->getCell('C' . $i)->setValue($group);
                    $objSheet->getCell('D' . $i)->setValue($row['IncompleteDate']);
                    $objSheet->getCell('E' . $i)->setValue($row['TimeIn']);
                    $objSheet->getCell('F' . $i)->setValue($row['TimeOut']);
                    $objSheet->getCell('G' . $i)->setValue($row['Note']);                    
                }
                $lastid = $row['IDEmployee'];
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:G' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:G' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:G1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);
            $objSheet->getColumnDimension('F')->setAutoSize(true);
            $objSheet->getColumnDimension('G')->setAutoSize(true);
            
            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "incomplete" . $ext);
            $data = file_get_contents($path_file . "incomplete" . $ext);
            force_download("incomplete" . $ext, $data);
        }
    }

    function iframe($id) {
        $data['url_form'] = site_url('trx03a/home/formatl/' . $id);
        $this->load->view('trx03a/iframe', $data);
    }

    function formatl($id) {
        $this->load->library('mpdf54/mpdf');
        $this->mpdf = new mPDF('c', array(216, 304), '12', 'dejavusans', 5, 5, 5, 5, 0, 0);
        $row = $this->incomplete->get_by_id_request($id);
	$rowparam = $this->param->get_hrdmgr();
        $rowdept = $this->pbl->get_departement($row->Dept)->row();
        $dept = ($rowdept=='' or $rowdept ==null)?$row->Dept:$rowdept->DescStructure;


        $day = date('w', strtotime($row->IDate));
        $data['day'] = $this->incomplete->hari($day);
        $data['incompletedate'] = date('d-m-Y', strtotime($row->IDate));
        $data['departement'] = $dept;
        $data['name'] = $row->Name;
        $data['position'] = $row->Position;
        $data['timein'] = $row->TIN;
        $data['timeout'] = $row->TOT;
        $data['note'] = $row->Note;
        $data['name'] = $row->Name;
        $data['parent'] = $row->ParentName;
	$data['namehrdmgr']=$rowparam->FullName;  
        $data['dateadd']=$row->AddedDate;  
        $data['dateconfirm']=$row->ConfirmDate;  

        $html = $this->load->view('trx03a/formatl', $data, true);
        $this->mpdf->SetHTMLFooter('
              <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
              <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE d-m-Y}</span></td>
              <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
              <td width="33%" style="text-align: right; "></td>
              </tr></table>
              ');
        $this->output->set_output($html);
        $this->mpdf->WriteHTML($html);
        // $this->mpdf->WriteHTML('<pagebreak sheet-size=210 297;/>');
        set_time_limit(60);
        $this->mpdf->Output('formatl.pdf', 'I');
    }

}
