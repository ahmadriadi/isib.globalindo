<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('officialtravel_model', 'officialtravel');
        $this->load->model('Employee_model', 'employee');
        $this->load->model('param_model', 'param');
        $this->load->model('logs_model', 'logs');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
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
        //error_reporting(0);
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

        $data['test1'] = $fromd;
        $data['test2'] = $untild;

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
        
        $iduser = $this->User;
        $idmodule = '83';

        $rowparam = $this->param->get_param($iduser);
        $rowlogin = $this->login->get_by_user($iduser);
        $rowmenu = $this->access->get_by_idmenu($idmodule);
        $rowpersonal = $this->employee->get_position($iduser);
        
        $checkdata1 = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $checkdata2 = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';
        
         if ($checkdata1 == 'exist' and $checkdata2 == 'exist') {
            $role = $rowlogin->Role;
            $position = $rowpersonal->Position;
            if ($role == '2') {
		$data['accessbutton'] ='true';
                $result = $this->officialtravel->getall_data_hrd($fromdate, $untildate,'AL');
                $viewon = 'trx04a/homeajax';
            } else if ($role == '1') {
                $parameter = $rowparam->ParamValue;                
              	
                    if ($parameter == $iduser) {
			$data['accessbutton'] ='true';
                        $result = $this->officialtravel->getall_data_hrd($fromdate, $untildate,'AL');
                        $viewon = 'trx04a/homeajax';
                    } else {
			$data['accessbutton'] ='false';	
                        $result = $this->officialtravel->getall_data_user($fromdate, $untildate, $iduser,'AL');
                        $viewon = 'trx04a/home';
                    }
               
               
            } else if ($role == '0' and $position == 'DIRECTOR' or $position == 'DIREKTUR' or $position == 'ASS. MANAGER' or $position == 'KOMISARIS' or $position == 'PROJECT TEAM LEADER' or $position == 'SUPERVISOR' or $rowparam->ParamValue == $iduser)  {
		$data['accessbutton'] ='false';
                $result = $this->officialtravel->getall_data_user($fromdate, $untildate, $iduser,'AL');
                $viewon = 'trx04a/home';
            }

            $data['dataofficialtravel'] = $result;
            $checkresult = ($result == '' or $result == null) ? 'empty' : 'exist';

	    $idmenu = "117";
            $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);	
            if ($checkresult == 'exist') {
                $this->load->view($viewon, $data);
            } else {
                $this->load->view('trx04a/homenull', $data);
            }
        }
    }

   
    function datatravel() {
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
                echo $this->officialtravel->officialtraveldata($f, $u);
            }
        }
    }
	  

     function getstatus() {
        $id = $this->anti_xss($this->input->post('id'));
        $rowh = $this->officialtravel->get_by_id($id);
        $flag = $rowh->ConfirmFlag;
        $inputby = $rowh->FlagInput;
        $name = $rowh->FullName;
        $valid = 'true';
        $json = '{ "flag":"' . $flag . '",
                   "inputby":"' . $inputby . '",
                   "fullname":"' . $name . '",
		   "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }
    
   
    
     function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '117';
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
    

    function addnew() {
        $data['default']['f01'] = ''; //IDemployee
        $data['default']['f02'] = ''; //FullName
        $data['default']['f03'] = ''; //OfficialtravelDate
        $data['default']['f04'] = ''; //UntilDate
        $data['default']['f05'] = ''; //VehicleNo
        $data['default']['f06'] = ''; //Note       
        
        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('trx04a/home/addpost');
        $this->load->view('trx04a/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f03', 'Official Travel Date', 'required');
        $this->form_validation->set_rules('f04', 'Until Date', 'required');       
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));           
            $f03 = date('Y-m-d', strtotime($this->input->post('f03')));
            $f04 = date('Y-m-d', strtotime($this->input->post('f04')));         
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f06 = $this->anti_xss($this->input->post('f06'));
                    
            $record = array(              
                'FlagInput' => 'hrd',
                'ConfirmFlag' => '1',
                'IDEmployee' => $f01,
                'OfficialTravelDate' => $f03,
                'UntilDate' => $f04,       
                'VehicleNo' => $f05,             
                'Note' => trim($f06),             
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
                'controller' => site_url('trx04a/home/addnew'),
                'activities' => 'add new ' . $f01
            );

           
            $this->officialtravel->insert($record);
            $this->logs->insert($recordlog);
            
            $mesg = 'insert data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';           
            $err_f06 = '';           
          
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
         
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",                      
                       "err_f03":"' . $err_f03 . '",                      
                       "err_f04":"' . $err_f04 . '",                                        
                       "err_f05":"' . $err_f05 . '",                                        
                       "err_f06":"' . $err_f06 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->officialtravel->get_by_id($id);

        $data['default']['f01'] = $row->IDEmployee;
        $data['default']['f02'] = $row->FullName;   
        $data['default']['f03'] = date('d-m-Y', strtotime($row->OfficialTravelDate));
        $data['default']['f04'] = date('d-m-Y', strtotime($row->UntilDate));  
        $data['default']['f05'] = $row->VehicleNo;
        $data['default']['f06'] = $row->Note;
        
        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';
   
        $data['url_post'] = site_url('trx04a/home/editpost');
        $this->load->view('trx04a/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f03', 'Official Travel Date', 'required');
        $this->form_validation->set_rules('f04', 'Until Date', 'required');       
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));           
            $f03 = date('Y-m-d', strtotime($this->input->post('f03')));
            $f04 = date('Y-m-d', strtotime($this->input->post('f04')));         
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f06 = $this->anti_xss($this->input->post('f06'));
                    
            $record = array(              
                'IDEmployee' => $f01,
                'OfficialTravelDate' => $f03,
                'UntilDate' => $f04,       
                'VehicleNo' => $f05,             
                'Note' => trim($f06),             
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
                'controller' => site_url('trx04a/home/addnew'),
                'activities' => 'edit data ' . $f01
            );

           
            $this->officialtravel->update($id,$record);
            $this->logs->insert($recordlog);
            
            $mesg = 'insert data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';           
            $err_f06 = '';           
          
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
         
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",                      
                       "err_f03":"' . $err_f03 . '",                      
                       "err_f04":"' . $err_f04 . '",                                        
                       "err_f05":"' . $err_f05 . '",                                        
                       "err_f06":"' . $err_f06 . '"' .
                '}';
        echo $json;
    }

   function delete($id) {
        $record = array(
            "DeletedBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeletedDate" => $this->Datetime,
            "DeletedIP" => $this->Ip
        );

        $this->officialtravel->update($id, $record);
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
        $objSheet->setTitle('officialtravel report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(12);

        // write header        
        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');
        $objSheet->getCell('C1')->setValue('Group');
        $objSheet->getCell('D1')->setValue('Official Travel Date');
        $objSheet->getCell('E1')->setValue('Until Date');
        $objSheet->getCell('F1')->setValue('Vehicle No');
        $objSheet->getCell('G1')->setValue('Note');      


       $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $iduser = $this->session->userdata('sess_userid');
        $idmodule = '83';

        $rowparam = $this->param->get_param($iduser);
        $rowlogin = $this->login->get_by_user($iduser);
        $rowmenu = $this->access->get_by_idmenu($idmodule);
        $rowpersonal = $this->employee->get_position($iduser);

        $checkdata1 = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $checkdata2 = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';

        if ($checkdata1 == 'exist' and $checkdata2 == 'exist') {
            $role = $rowlogin->Role;
            $position = $rowpersonal->Position;

            if ($role == '2') {
                $result = $this->officialtravel->getall_data_hrd($f, $u,$g);
            } else if ($role == '1') {
                $parameter = $rowparam->ParamValue;
                if ($parameter == $iduser) {
                    $result = $this->officialtravel->getall_data_hrd($f, $u,$g);
                } else {
                    $result = $this->officialtravel->getall_data_user($f, $u, $iduser,$g);
                }
            } else if ($role == '0' and $position == 'DIRECTOR' or $position == 'DIREKTUR' or $position == 'ASS. MANAGER' or $position == 'KOMISARIS' or $position == 'PROJECT TEAM LEADER' or $position == 'SUPERVISOR' or $rowparam->ParamValue = $iduser) {

                $result = $this->officialtravel->getall_data_user($f, $u, $iduser,$g);
            }
        }
        $checkresult = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkresult =='exist') {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $group = $this->libfun->get_name_group($row['IDJobGroup']);

                $objSheet->getCell('A' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('B' . $i)->setValue($row['FullName']);
                $objSheet->getCell('C' . $i)->setValue($group);
                $objSheet->getCell('D' . $i)->setValue($row['OfficialTravelDate']);
                $objSheet->getCell('E' . $i)->setValue($row['UntilDate']);
                $objSheet->getCell('F' . $i)->setValue($row['VehicleNo']);
                $objSheet->getCell('G' . $i)->setValue($row['Note']);
               
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
            $objWriter->save($path_file . "officialtravel_attendance" . $ext);
            $data = file_get_contents($path_file . "officialtravel_attendance" . $ext);
            force_download("officialtravel_attendance" . $ext, $data);
        }
    }

  

}
