<?php
//OVERTIME
class Home extends CI_Controller {

    public function __construct() {        
        parent ::__construct();
        $this->load->model('additional_model', 'additional');
        $this->load->model('Employee_model', 'employee');
        $this->load->model('logs_model', 'logs');
	$this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('Param_model', 'param');
        $this->load->model('uac_model', 'uac');
	$this->load->model('libraryfunction_model', 'libfun');
	$this->load->model('historytable_model', 'history');

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
        $fromd  = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');   
        $check1 = ($fromd =='' or $fromd ==null)?'empty':'exist';
        $check2 = ($untild =='' or $untild ==null)?'empty':'exist';       
        
        $data['test1'] = $check1;
        $data['test2'] = $check2;
        
        if($check1 =='empty' and $check2=='empty'){
            $date = $this->libfun->periode_work();
            $fromdate = substr($date, 0, 10);
            $untildate = substr($date, 11, 10);                
            $this->session->set_userdata('datefrom', date('Y-m-d',strtotime($fromdate)));
            $this->session->set_userdata('dateuntil', date('Y-m-d',strtotime($untildate)));       
        }else{            
             $fromdate = $this->session->userdata('fromdate');
             $untildate = $this->session->userdata('untildate');  
             $this->session->set_userdata('datefrom', date('Y-m-d',strtotime($fromdate)));
             $this->session->set_userdata('dateuntil', date('Y-m-d',strtotime($untildate)));  
        }        
        
        $data['default']['from'] = date('d-m-Y', strtotime($fromdate));
        $data['default']['until'] = date('d-m-Y', strtotime($untildate));
	
	$query = $this->employee->get_rjob_field()->result();
        $i = 0;
        foreach ($query as $r) {
            $i++;
            $data['default']['group'][$i]['value'] = $r->IDJobGroup;
            $data['default']['group'][$i]['display'] = $r->GroupName;   
        }
	$idmenu                    = "78";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);	
	$data['datadate'] = date('d');
        $data['datahour'] = date('H:i');
        $this->load->view('trx03/home',$data);
    }

    function resultadditional() {	
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
       
        $idmodule = '65';
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
                   echo $this->additional->dataaddition($f,$u);
            } else if ($role == '0' and $param == 'Y') {
                    echo $this->additional->dataaddition($f,$u);
            }
        }
        

    }

    function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '78';
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
            $arr[] = array('idemployee' => $row->IDEmployee,
                           'fullname' => strtoupper($row->FullName)
                          
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }



  function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->search_employee_fieldpayroll($q);
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
	$date = $this->libfun->periode_work(); 
        $data['default']['f01'] = ''; //IDEmployee
        $data['default']['f02'] = ''; //FullName
        $data['default']['f03'] = substr($date, 11, 10);
        $data['default']['f04'] = '';//Amount
        $query = $this->additional->get_all_reference()->result();                
        $i = 0;
        foreach ($query as $r) {
            $data['default']['f05'][$i]['value'] = $r->CodeType;
            $data['default']['f05'][$i]['display'] = $r->Description;
            $i++;
        } 
        $data['default']['f06'] = '';//Note     
        $data['default']['readonly_f01'] = 'READONLY';
	$data['default']['readonly_f03'] = 'READONLY';
        $data['url_post'] = site_url('trx03/home/addpost');        
        $this->load->view('trx03/form',$data);
        
       
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f02', 'FullName', 'required');
        $this->form_validation->set_rules('f03', 'PostingDate', 'required');
        $this->form_validation->set_rules('f04', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('f05', 'Paramenter', 'required');      
         if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));           
            $f02 = $this->anti_xss($this->input->post('f02'));           
            $f03 = date('Y-m-d', strtotime($this->input->post('f03')));        
            $f04 = $this->anti_xss($this->input->post('f04'));           
            $f05 = $this->anti_xss($this->input->post('f05'));           
            $f06 = $this->anti_xss($this->input->post('f06'));           
           

            $record = array(
                'IDEmployee' => $f01,
                'PostingDate' => $f03,   
                'Amount' => $f04,               
                'Parameter' => $f05,               
                'Note' => TRIM($f06),
		'FlagEntry'=>'TRX',
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
                'controller' => site_url('trx03/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $result = $this->additional->checkdata($f01,$f03,'TRX');
            $checkdata = ($result =='exist')?'exist':'empty';  
          
            if($checkdata =='exist'){
		$alert = 'insert failed, because idemployee :'.$f01.' and posting date :'.$f03.' already exist';
                $ket = 'false';               
            }else if($checkdata =='empty') {      
		$this->additional->insert($record);
                $this->logs->insert($recordlog);
                $alert = 'insert data, sucess';
                $ket = 'true';
            }                  
            $mesg  = $alert;
            $valid = $ket;
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
        $row = $this->additional->get_by_id($id);
        $data['default']['f01'] = $row->IDEmployee; //sub spkl
        $data['default']['f02'] = $row->FullName; //idemployee
        $data['default']['f03'] = date('d-m-Y',strtotime($row->PostingDate)); //idemployee
        $data['default']['f04'] = $row->Amount; //idemployee
        $query = $this->additional->get_all_reference()->result();                
        $i = 0;
        foreach ($query as $r) {
            $data['default']['f05'][$i]['value'] = $r->CodeType;
            $data['default']['f05'][$i]['display'] = $r->Description;
            if ($r->CodeType == $row->Parameter) {
                $data['default']['f05'][$i]['selected'] = "SELECTED";
            }
            $i++;
        } 
        $data['default']['f06'] = $row->Note; //idemployee        
        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY'; 
	$data['default']['readonly_f03'] = 'READONLY';     
        $data['url_post'] = site_url('trx03/home/editpost');
        
       
        $this->load->view('trx03/form',$data);
           
    }

    function editpost() {
       $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f02', 'FullName', 'required');
        $this->form_validation->set_rules('f03', 'PostingDate', 'required');
        $this->form_validation->set_rules('f04', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('f05', 'Paramenter', 'required');      
         if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');               
            $f01 = $this->anti_xss($this->input->post('f01'));           
            $f02 = $this->anti_xss($this->input->post('f02'));           
            $f03 = $this->anti_xss($this->input->post('f03'));           
            $f04 = $this->anti_xss($this->input->post('f04'));           
            $f05 = $this->anti_xss($this->input->post('f05'));           
            $f06 = $this->anti_xss($this->input->post('f06'));           
           

            $record = array(
                'IDEmployee' => $f01,
                'PostingDate' => date('Y-m-d', strtotime($f03)),   
                'Amount' => $f04,               
                'Parameter' => $f05,               
                'Note' => TRIM($f06),
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
                'controller' => site_url('trx03/home/addnew'),
                'activities' => 'edit data ' . $f01
            );

           
	    $this->historydata($id);	
            $this->additional->update($id,$record);
            $this->logs->insert($recordlog);
            $mesg = 'insert data, sucess';
            $valid = 'true';
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
    
    function delete($id) {
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );

        $this->additional->update($id, $record);
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
        $objSheet->setTitle('additional report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(12);

        // write header       
        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');	
	$objSheet->getCell('C1')->setValue('Group');
        $objSheet->getCell('D1')->setValue('Posting Date');
        $objSheet->getCell('E1')->setValue('Amount');
        $objSheet->getCell('F1')->setValue('Parameter');
        $objSheet->getCell('G1')->setValue('Note');
      
        
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->additional->getall_data($f,$u,$g);
        if ($result != NULL) {   
            $i = 1;
            foreach ($result as $row) {
                $i++;
                  	
		$group = $this->libfun->get_name_group($row['IDJobGroup']);
		              
                $objSheet->getCell('A' . $i)->setValue("'".$row['IDEmployee']);
                $objSheet->getCell('B' . $i)->setValue($row['FullName']);
		$objSheet->getCell('C' . $i)->setValue($group);
                $objSheet->getCell('D' . $i)->setValue(date('d-m-Y',strtotime($row['PostingDate'])));
                $objSheet->getCell('E' . $i)->setValue(number_format($row['Amount'],'2',',','.'));
                $objSheet->getCell('F' . $i)->setValue($row['Parameter']);
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
            $objWriter->save($path_file . "additional" . $ext);
            $data = file_get_contents($path_file . "additional" . $ext);
            force_download("additional" . $ext, $data);
        }
    }


  function historydata($id){
         $rowh = $this->history->getby_id_addition($id); 
            $record = array(
                "PostingDate"=>$rowh->PostingDate,
                "IDEmployee"=>$rowh->IDEmployee,
                "Amount"=>$rowh->Amount,
                "Parameter"=>$rowh->Parameter,
                "FlagEntry"=>$rowh->FlagEntry,
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
            
            $this->history->insert_hisaddition($record);
            
    }
   

}

