<?php

class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('ipdata_model', 'ipdata');
        $this->load->model('employee_model', 'employee');
        $this->load->model('historytable_model', 'history');
        $this->load->model('logs_model', 'logs');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('param_model', 'param');
        $this->load->model('uac_model', 'uac');


        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }
    
    function generate_ip(){
		for ($i = 1; $i <= 255; $i++) {
			$ip = '192.168.0.'.$i;			
			 $record = array(
                'IPAddress' => $ip,               
                'AddedBy' => $this->User,
                'AddedDate' => $this->Datetime,
                'AddedIP' => $this->Ip
            );
			
			$this->ipdata->insert($record);
		}
		
	}

    function index() {	
		
        $idmenu = "392";
        $data['flag'] = 'ipactive';    
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        
        $idmodule = '230';
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
                $this->load->view('mst02/tabmenu', $data);
            } else if ($role == '0' and $param == 'Y') {
                 $this->load->view('mst02/tabmenu', $data);
            }
        }else{
			
			echo "<H2>YOU DO NOT ACCESS THIS APPS</H2>";
		}
        
        
      
    }
    
    function index_ipactive() {
        $data['flag'] = 'ipactive';        
        $idmenu = "392";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('mst02/home_ipactive', $data);
    }

    function index_ippassive() {
        $data['flag'] = 'ippassive';        
        $idmenu = "392";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('mst02/home_ippassive', $data);
    }
    
    function tabmenu($flag) {
        $data['flag'] = $flag;     
        $this->load->view('mst02/tabmenu', $data);
    }
    
    
    
    

    function getdatatable($param) {  	
			 echo $this->ipdata->getdata($param);
    }


    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '392';
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

   function autocomplete_employee() {
        $result = $this->employee->findall_employee();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                           'fullname' => strtoupper($row->FullName)
                          
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
	


    function addnew($tab) { 
		$data['default']['f00'] = ''; //MacAddress  
		$data['default']['f01'] = ''; //IPAddress 
        $data['default']['f02'] = ''; //GateWay
        $data['default']['f03'] = ''; //DomainServer
        $data['default']['f04'] = ''; //ComputerName
		$data['default']['f05'] = ''; //FullName
		$data['default']['f06'] = ''; //IDEmployee
		$data['default']['f07'] = ''; //Note
		
		$data['tab'] =$tab;
		$data['iddata'] = '';
        $data['flagcondition'] = 'add';
        $data['default']['readonly_f06'] = 'READONLY';
        $data['url_post'] = site_url('mst02/home/addpost');
        $this->load->view('mst02/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IP Address', 'required');
        $this->form_validation->set_rules('f02', 'Gateway', 'required');
        $this->form_validation->set_rules('f03', 'Domain Name Server', 'required');
        $this->form_validation->set_rules('f04', 'Computer Name', 'required');
        
        if ($this->form_validation->run() == TRUE) {
			$f00 = $this->input->post('f00');
            $f01 = $this->input->post('f01');
            $f02 = $this->input->post('f02');
            $f03 = $this->input->post('f03');
            $f04 = $this->input->post('f04');
            $f05 = $this->input->post('f05');
            $f06 = $this->input->post('f06');
            $f07 = $this->input->post('f07');       


            $record = array(
                'MacAddress' => $f00,
                'IPAddress' => $f01,
                'GateWay' => $f02,
                'DomainServer' => $f03,
                'ComputerName' => $f04,
                'IDEmployee' => $f06,              
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
                'controller' => site_url('mst02/home/addnew'),
                'activities' => 'add new ' . $f01
            );


          
			$this->ipdata->insert($record);
			$this->logs->insert($recordlog);

			$alert = "Insert Data, Success";
			$status = "true";
         

            $mesg = $alert;
            $valid = $status;
            $err_f00 = '';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
            $err_f06 = '';
            $err_f07 = '';
    
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f00 = form_error('f00');
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
            $err_f07 = form_error('f07');
       
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f00":"' . $err_f00 . '", 
                       "err_f01":"' . $err_f01 . '", 
                       "err_f02":"' . $err_f02 . '", 
                       "err_f03":"' . $err_f03 . '", 
                       "err_f04":"' . $err_f04 . '", 
                       "err_f05":"' . $err_f05 . '", 
                       "err_f06":"' . $err_f06 . '",                       
                       "err_f09":"' . $err_f07
                . '"' .
                '}';
        echo $json;
    }

    function edit($id,$flag) {
        $this->session->set_userdata('id', $id);
        $row = $this->ipdata->getby_id($id);
        
        $data['default']['f00'] = $row->MacAddress;
        $data['default']['f01'] = $row->IPAddress;
        $data['default']['f02'] = $row->GateWay;
	    $data['default']['f03'] = $row->DomainServer;
        $data['default']['f04'] = $row->ComputerName;
        $data['default']['f05'] = $row->FullName;
        $data['default']['f06'] = $row->IDEmployee;      
        $data['default']['f07'] = $row->Note;
	
		$data['iddata'] = $row->ID;
		$data['tab'] = $flag;
        $data['flagcondition'] = 'edit';
        $data['default']['readonly_f06'] = 'READONLY';
        $data['url_post'] = site_url('mst02/home/editpost');
        $this->load->view('mst02/form', $data);
    }

    function editpost() {
		 $this->form_validation->set_rules('f01', 'IP Address', 'required');
        $this->form_validation->set_rules('f02', 'Gateway', 'required');
        $this->form_validation->set_rules('f03', 'Domain Name Server', 'required');
        $this->form_validation->set_rules('f04', 'Computer Name', 'required');
        
        if ($this->form_validation->run() == TRUE) {
			$id = $this->input->post('iddata');
			$f00 = $this->input->post('f00');
            $f01 = $this->input->post('f01');
            $f02 = $this->input->post('f02');
            $f03 = $this->input->post('f03');
            $f04 = $this->input->post('f04');
            $f05 = $this->input->post('f05');
            $f06 = $this->input->post('f06');
            $f07 = $this->input->post('f07');
          


            $record = array(
                'MacAddress' => $f00,
                'IPAddress' => $f01,
                'GateWay' => $f02,
                'DomainServer' => $f03,
                'ComputerName' => $f04,
                'IDEmployee' => $f06,          
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
                'controller' => site_url('mst02/home/addnew'),
                'activities' => 'add new ' . $f01
            );


            $this->historydata($id, 'editpost');
			$this->ipdata->update($id,$record);
			$this->logs->insert($recordlog);

			$alert = "update Data, Success";
			$status = "true";
         

            $mesg = $alert;
            $valid = $status;
            $err_f00 = '';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
            $err_f06 = '';
            $err_f07 = '';    
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
			$err_f00 = form_error('f00');	
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
            $err_f07 = form_error('f07');  
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f00":"' . $err_f00 . '", 
                       "err_f01":"' . $err_f01 . '", 
                       "err_f02":"' . $err_f02 . '", 
                       "err_f03":"' . $err_f03 . '", 
                       "err_f04":"' . $err_f04 . '", 
                       "err_f05":"' . $err_f05 . '", 
                       "err_f06":"' . $err_f06 . '",                
                       "err_f09":"' . $err_f07
                . '"' .
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

        $this->ipdata->update($id, $record);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function excel($param) {
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
        $objSheet->setTitle('Master Data IP Address');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:K1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('Mac Address');        
        $objSheet->getCell('B1')->setValue('IP Address');
        $objSheet->getCell('C1')->setValue('Gateway');
        $objSheet->getCell('D1')->setValue('DNS');
        $objSheet->getCell('E1')->setValue('Computer Name');
        $objSheet->getCell('F1')->setValue('IDEmployee');
        $objSheet->getCell('G1')->setValue('Name');
        $objSheet->getCell('H1')->setValue('Status');
        $objSheet->getCell('I1')->setValue('Departement');
        $objSheet->getCell('J1')->setValue('Group');   
        $objSheet->getCell('K1')->setValue('Note');


        $result = $this->ipdata->getall_data($param);

        if ($result !== 'empty') {
            $i = 1;
            foreach ($result as $row) {
                $i++;


			    $objSheet->getCell('A' . $i)->setValue($row['MacAddress']); 
                $objSheet->getCell('B' . $i)->setValue($row['IPAddress']);
                $objSheet->getCell('C' . $i)->setValue($row['GateWay']);
                $objSheet->getCell('D' . $i)->setValue($row['DomainServer']);
                $objSheet->getCell('E' . $i)->setValue($row['ComputerName']);
                $objSheet->getCell('F' . $i)->setValue($row['IDEmployee']);
                $objSheet->getCell('G' . $i)->setValue($row['FullName']);
                $objSheet->getCell('H' . $i)->setValue($row['Status']);
                $objSheet->getCell('I' . $i)->setValue($row['DescStructure']);
                $objSheet->getCell('J' . $i)->setValue($row['GroupName']);            
                $objSheet->getCell('K' . $i)->setValue($row['Note']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:K' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:K' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:K1')->getBorders()->
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

            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "masteripdata" . $ext);
            $data = file_get_contents($path_file . "masteripdata" . $ext);
            force_download("masteripdata" . $ext, $data);
        }
    }

    function historydata($id, $function) {

        $row = $this->ipdata->getby_id($id);
        $record = array(
            "MacAddress" => $row->MacAddress,
            "IPAddress" => $row->IPAddress,
            "GateWay" => $row->GateWay,
            "DomainServer" => $row->DomainServer,
            "ComputerName" => $row->ComputerName,
            "IDEmployee" => $row->IDEmployee,        
            "Note" => $row->Note,
            "AddedBy" => $row->AddedBy,
            "AddedDate" => $row->AddedDate,
            "AddedIP" => $row->AddedIP,
            "EditedBy" => $row->EditedBy,
            "EditedDate" => $row->EditedDate,
            "EditedIP" => $row->EditedIP,
            "DeleteBy" => $row->DeleteBy,
            "DeleteFlag" => $row->DeleteFlag,
            "DeleteDate" => $row->DeleteDate,
            "DeleteIP" => $row->DeleteIP,
            "IDTable" => $id,
            "FunctionOn" => $function,
            "HistBy" => $this->User,
            "HistDate" => $this->Datetime,
            "HistIP" => $this->Ip
        );

        $this->history->insert_mst02_history($record);
    }

}
