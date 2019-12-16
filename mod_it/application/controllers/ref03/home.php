<?php


class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('inventaris_model', 'inventaris');
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

    function index() {
        $idmenu = "244";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('ref03/home', $data);
    }

    function getdatatable() {
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
                echo $this->inventaris->getdata_r03();
            } else if ($role == '0' and $param == 'Y') {
                echo $this->inventaris->getdata_r03();
            }
        }
    }

    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '244';
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
        $data['default']['f01'] = ''; //ComputerName
        $data['default']['f02'] = ''; //IPAddress
        $data['default']['f03'] = ''; //PortNumber
        $data['default']['f04'] = ''; //PrinterName
        $data['url_post'] = site_url('ref03/home/addpost');
        $this->load->view('ref03/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'Computer Name', 'required');     
        $this->form_validation->set_rules('f02', 'IP Address', 'required');     
        $this->form_validation->set_rules('f04', 'Printer Name', 'required');     
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->input->post('f01');          
            $f02 = $this->input->post('f02');          
            $f03 = $this->input->post('f03');          
            $f04 = $this->input->post('f04');          
            
            $record = array(
                'ComputerName' => $f01,              
                'IPAddress' => $f02,              
                'PortNumber' => $f03,              
                'PrinterName' => $f04,              
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
            
            
            $rinventaris = $this->inventaris->checkr03($f01);
            $checkdata = ($rinventaris=='' or $rinventaris==NULL)?'empty':'exist';
            
            if($checkdata=='empty'){
                 $this->inventaris->insert_r03($record);
                 $this->logs->insert($recordlog);
                 
                 $alert ="Insert Data, Success";
                 $status ="true";
                 
            }else if($checkdata=='exist'){
                 $alert ="Insert Data, Failed because the data ".$f01.'already exist';
                 $status ="false";
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
                       "err_f04":"' . $err_f04
                . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->inventaris->getby_idr03($id);

        $data['default']['f01'] = $row->ComputerName;
        $data['default']['f02'] = $row->IPAddress;
        $data['default']['f03'] = $row->PortNumber;
        $data['default']['f04'] = $row->PrinterName;
        

      //  $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('ref03/home/editpost');
        $this->load->view('ref03/form', $data);
    }

    function editpost() {
       $this->form_validation->set_rules('f01', 'Computer Name', 'required');     
       $this->form_validation->set_rules('f02', 'IP Address', 'required');     
       $this->form_validation->set_rules('f04', 'Printer Name', 'required');     
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');          
            $f01 = $this->input->post('f01');          
            $f02 = $this->input->post('f02');          
            $f03 = $this->input->post('f03');          
            $f04 = $this->input->post('f04');          
          
            
            $record = array(
                'ComputerName' => $f01,              
                'IPAddress' => $f02,              
                'PortNumber' => $f03,              
                'PrinterName' => $f04,              
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
                'activities' => 'edit data ' . $id
            );

            $this->historydata($id, 'edit');
            $this->inventaris->update_r03($id, $record);
            $this->logs->insert($recordlog);

            $mesg = 'update data, success';
            $valid = 'true';
            $err_f01 = '';
           
        
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
          
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '",                                                      
                       "err_f02":"' . $err_f02 . '"' .
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

        $this->inventaris->update_r03($id, $record);
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
        $objSheet->setTitle('Location');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:D1')->getFont()->setBold(true)->setSize(12);

        // write header        
        $objSheet->getCell('A1')->setValue('Computer Name');
        $objSheet->getCell('B1')->setValue('IP Address');
        $objSheet->getCell('C1')->setValue('Port Number');
        $objSheet->getCell('D1')->setValue('Printer Name');
       
      
        $result = $this->inventaris->getall_r03();

        if ($result !== 'empty') {
            $i = 1;
            foreach ($result as $row) {
                $i++;
                
               
                $objSheet->getCell('A' . $i)->setValue($row['ComputerName']);
                $objSheet->getCell('B' . $i)->setValue($row['IPAddress']);
                $objSheet->getCell('C' . $i)->setValue($row['PortNumber']);
                $objSheet->getCell('D' . $i)->setValue($row['PrinterName']);
                
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
            $objWriter->save($path_file . "dataprinter" . $ext);
            $data = file_get_contents($path_file . "dataprinter" . $ext);
            force_download("dataprinter" . $ext, $data);
        }
    }
    
    function historydata($id, $function) {
         
         $row = $this->inventaris->getby_idr03($id);
         $record = array(
             "ComputerName"=>$row->ComputerName,
             "IPAddress"=>$row->IPAddress,
             "PortNumber"=>$row->PortNumber,
             "PrinterName"=>$row->PrinterName,
             "AddedBy"=>$row->AddedBy,
             "AddedDate"=>$row->AddedDate,
             "AddedIP"=>$row->AddedIP,
             "EditedBy"=>$row->EditedBy,
             "EditedDate"=>$row->EditedDate,
             "EditedIP"=>$row->EditedIP,
             "DeleteBy"=>$row->DeleteBy,
             "DeleteFlag"=>$row->DeleteFlag,
             "DeleteDate"=>$row->DeleteDate,
             "DeleteIP"=>$row->DeleteIP,
             "IDTable"=>$id,
             "FunctionOn"=>$function,
             "HistBy"=>$this->User,
             "HistDate"=>$this->Datetime,
             "HistIP"=>$this->Ip
         );
         
         $this->history->insert_r03_history($record);
         
         
     }


}
