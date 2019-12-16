<?php
//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        
        
        parent ::__construct();
        $this->load->model('personalfield_model', 'datafield');
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
	$query = $this->employee->get_rjob_field()->result();
        $i = 0;
        foreach ($query as $r) {
            $i++;
            $data['default']['group'][$i]['value'] = $r->IDJobGroup;
            $data['default']['group'][$i]['display'] = $r->GroupName;   
        }

			
	$data['default']['status'][0]['value'] = 'AL';
        $data['default']['status'][0]['display'] = 'ALL';
        $data['default']['status'][1]['value'] = 'A';
        $data['default']['status'][1]['display'] = 'ACTIVE';
        $data['default']['status'][2]['value'] = 'P';
        $data['default']['status'][2]['display'] = 'PASSIVE';
	           
        $idmenu                    = "71";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('mst01/home',$data);        
     }
    function resultemployeefield() {        
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
                echo $this->datafield->datafieldemployee();
            } else if ($role == '0' and $param == 'Y') {
                echo $this->datafield->datafieldemployee();
            }
        }
    }
    
    
     function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '71';
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
    
    function get_group(){
        $nip = $this->anti_xss($this->input->post('nip'));
        $row = $this->employee->get_by_nip($nip);
        $group = $row->IDJobGroup;        
        $valid = 'true';
	$json = '{ "group":"' . $group . '",
		   "valid":"' . $valid . '"' .
		'}';                
	echo $json;  
        
    } 
    
    
    function syncrondata(){
        $i=0;
        $result = $this->employee->getall_data_lapangan();
        foreach ($result as $row) {
            $i++;        
            $nip        = $row['IDEmployee']; 
            $bank       = $row['BankAccount'];             
            $bankdata = ($bank =='' or $bank ==null)?'-':$bank;
            
            
            $record = array(
                'IDEmployee'=>$nip,
                'BankAccountNo'=>$bankdata
                );
            
            $result = $this->datafield->check($nip);
            $check = ($result =='' or $result ==null)?'empty':'exist';             
            if($check =='empty'){
                    $this->datafield->insert($record);  
            }
            
        }
        
        $valid = 'true';
	$json = '{ "valid":"' . $valid . '"' .
		'}';                
	echo $json; 
        
    }

   
   function autocomplete_employee() {
        $result = $this->employee->find_employee_active();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                           'fullname' => strtoupper($row->FullName),
                           'bank' => $row->BankAccount
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
	      $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName."|".$row->BankAccount,
	                                  'value' => $row->FullName,
                                          'idemployee' => $row->IDEmployee,
                                          'bank' => $row->BankAccount);	
	 }		
             
        }        
        echo json_encode($data);
    }
    
    function addnew() {
	$rowparam = $this->datafield->getparam_payroll();
        $data['pointdaily']=$rowparam->SumDaySalary;
        $data['pointovertime']=$rowparam->OvertimeWorkHour;
        $data['pointinsurance']=$rowparam->InsurancePercent;
        $data['pointbpjs']=$rowparam->BPJSPercent;
	

        $data['default']['f01'] = '';//IDEmployee
        $data['default']['f02'] = '';//FullName
        $data['default']['f03'] = '';//BankAccount
        $data['default']['f04'] = '';//MothlySalary
        $data['default']['f05'] = '';//Insurance      
        $data['default']['f06'] = '';//BPJS      
        $data['default']['f07'] = '';//DailySalary  
        $data['default']['f08'] = '';//OvertimePerHour
        $data['default']['f09'] = '';//OvertimeMeal
        $data['default']['f10'] = '';//OvertimeIncentivePaid

        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('mst01/home/addpost'); 
        $this->load->view('mst01/form',$data);
     
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f02', 'FullName', 'required');
        $this->form_validation->set_rules('f03', 'Bank Account No', 'required');
        $this->form_validation->set_rules('f04', 'Monthly Salary', 'required|numeric');
        $this->form_validation->set_rules('f05', 'Insurance', 'required|numeric');
        $this->form_validation->set_rules('f06', 'BPJS', 'required|numeric');
        $this->form_validation->set_rules('f07', 'Daily Salary', 'required|numeric');
        $this->form_validation->set_rules('f08', 'Overtime Per Hour', 'required|numeric');     
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f06 = $this->anti_xss($this->input->post('f06'));
            $f07 = $this->anti_xss($this->input->post('f07'));
            $f08 = $this->anti_xss($this->input->post('f08'));       
            
            $record = array(
                'IDEmployee' => $f01,
                'BankAccountNo' => $f03,
                'MonthlySalary' => $f04,
                'Insurance' => $f05,
                'BPJS' => $f06,
                'DailySalary' => $f07,               
                'OvertimePerHour' => $f08,  
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
                'controller' => site_url('mst01/home/addnew'),
                'activities' => 'add new ' . $f01
            );
            
            $result = $this->datafield->check($f01);
            $check = ($result =='' or $result ==null)?'empty':'exist';
            if($check =='exist'){
                $alert = 'Insert Data, Failed, because IDEmployee :'.$f01.' Already Exist';
                $status = 'false';
            }else{
                $alert = 'Insert Data, Success';
                $status = 'true';
                $this->datafield->insert($record);
                $this->logs->insert($recordlog);
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
        $row = $this->datafield->get_by_id($id);  

	$rowparam = $this->datafield->getparam_payroll();
        $data['pointdaily']=$rowparam->SumDaySalary;
        $data['pointovertime']=$rowparam->OvertimeWorkHour;
        $data['pointinsurance']=$rowparam->InsurancePercent;
        $data['pointbpjs']=$rowparam->BPJSPercent;
      
        $data['default']['f01'] = $row->IDEmployee;
        $data['default']['f02'] = $row->FullName;
        $data['default']['f03'] = $row->BankAccountNo;
        $data['default']['f04'] = $row->MonthlySalary;
        $data['default']['f05'] = $row->Insurance;      
        $data['default']['f06'] = $row->BPJS;      
        $data['default']['f07'] = $row->DailySalary;  
        $data['default']['f08'] = $row->OvertimePerHour;         

        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';
        //$data['default']['readonly_f03'] = 'READONLY';
        $data['url_post'] = site_url('mst01/home/editpost');        
      
        $this->load->view('mst01/form',$data);
        
    }

    function editpost() {
      $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f02', 'FullName', 'required');
        $this->form_validation->set_rules('f03', 'Bank Account No', 'required');
        $this->form_validation->set_rules('f04', 'Monthly Salary', 'required|numeric');
        $this->form_validation->set_rules('f05', 'Insurance', 'required|numeric');
        $this->form_validation->set_rules('f06', 'BPJS', 'required|numeric');
        $this->form_validation->set_rules('f07', 'Daily Salary', 'required|numeric');
        $this->form_validation->set_rules('f08', 'Daily Overtime', 'required|numeric');
      
        if ($this->form_validation->run() == TRUE) {
            $id  = $this->session->userdata('id');
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f06 = $this->anti_xss($this->input->post('f06'));
            $f07 = $this->anti_xss($this->input->post('f07'));          
            $f08 = $this->anti_xss($this->input->post('f08'));          
            

            $record = array(               
                'BankAccountNo' => $f03,
                'MonthlySalary' => $f04,
                'Insurance' => $f05,
                'BPJS' => $f06,
                'DailySalary' => $f07,               
                'OvertimePerHour' => $f08,  
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
                'controller' => site_url('mst01/home/addnew'),
                'activities' => 'edit data ' . $f01
            );
            
          
            $this->historydata($id);
            $this->datafield->update($id,$record);
            $this->logs->insert($recordlog);
            
            $mesg = "Update Data, Success";
            $valid = "true";
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
            $err_f06 = '';
            $err_f07 = '';
            $err_f08 = '';
          
         
            
        } else {
            $mesg = 'Update Data, Failed';
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
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );

        $this->datafield->update($id, $record);
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
        $objSheet->setTitle('datafield report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:L1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('No');
        $objSheet->getCell('B1')->setValue('IDEmployee');
        $objSheet->getCell('C1')->setValue('FullName');
        $objSheet->getCell('D1')->setValue('Group');
        $objSheet->getCell('E1')->setValue('Location');
        $objSheet->getCell('F1')->setValue('BankAccountNo');
        $objSheet->getCell('G1')->setValue('MonthlySalary');
        $objSheet->getCell('H1')->setValue('BPJS Tenaga Kerja');
        $objSheet->getCell('I1')->setValue('BPJS Kesehatan');
        $objSheet->getCell('J1')->setValue('DailySalary');
        $objSheet->getCell('K1')->setValue('OvertimePerHour');
	$objSheet->getCell('L1')->setValue('HireDate');
       
        $result = $this->datafield->getall_data($g);
        if ($result != NULL) {   
            $i = 1;
		$no=0;
            foreach ($result as $row) {
                $i++;
		$no++;	
                
                $group = $this->libfun->get_name_group($row['IDJobGroup']);  
                $location = $this->libfun->get_location($row['IDLocation']);  
                $objSheet->getCell('A' . $i)->setValue($no);
                $objSheet->getCell('B' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('C' . $i)->setValue($row['FullName']);
                $objSheet->getCell('D' . $i)->setValue($group);
                $objSheet->getCell('E' . $i)->setValue($location);
                $objSheet->getCell('F' . $i)->setValue($row['BankAccountNo']);
                $objSheet->getCell('G' . $i)->setValue($row['MonthlySalary']);
                $objSheet->getCell('H' . $i)->setValue($row['Insurance']);
                $objSheet->getCell('I' . $i)->setValue($row['BPJS']);
                $objSheet->getCell('J' . $i)->setValue($row['DailySalary']);
                $objSheet->getCell('K' . $i)->setValue($row['OvertimePerHour']);
		$objSheet->getCell('L' . $i)->setValue($row['HireDate']);
                /*
		$objSheet->getCell('F' . $i)->setValue(number_format($row['MonthlySalary'],'2',',','.'));
                $objSheet->getCell('G' . $i)->setValue(number_format($row['Insurance'],'2',',','.'));
                $objSheet->getCell('H' . $i)->setValue(number_format($row['DailySalary'],'2',',','.'));
                $objSheet->getCell('I' . $i)->setValue(number_format($row['OvertimePerHour'],'2',',','.'));
		*/
		
	

              
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:L' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:L' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:L1')->getBorders()->
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
            $objSheet->getColumnDimension('I')->setAutoSize(true);
            $objSheet->getColumnDimension('J')->setAutoSize(true);
            $objSheet->getColumnDimension('K')->setAutoSize(true);
	    $objSheet->getColumnDimension('L')->setAutoSize(true);
           

            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "datafield" . $ext);
            $data = file_get_contents($path_file . "datafield" . $ext);
            force_download("datafield" . $ext, $data);
        }
    }

function historydata($id){
         $rowh = $this->history->getby_id_personal($id); 
            $record = array(
                "IDEmployee"=>$rowh->IDEmployee,
                "BankAccountNo"=>$rowh->BankAccountNo,
                "MonthlySalary"=>$rowh->MonthlySalary,
                "Insurance"=>$rowh->Insurance,
                "BPJS"=>$rowh->BPJS,
                "DailySalary"=>$rowh->DailySalary,
                "OvertimePerHour"=>$rowh->OvertimePerHour,
                "OvertimeMeal"=>$rowh->OvertimeMeal,
                "OvertimeIncentivePaid"=>$rowh->OvertimeIncentivePaid,
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
            
            $this->history->insert_hispersonal($record);
            
    }


   

}
