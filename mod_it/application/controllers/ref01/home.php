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
        $idmenu = "237";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('ref01/home', $data);
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
                echo $this->inventaris->getdata_r01();
            } else if ($role == '0' and $param == 'Y') {
                echo $this->inventaris->getdata_r01();
            }
        }
    }

    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '237';
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
        $data['default']['f01'] = ''; //ItemName
        $data['default']['f02'] = ''; //ItemCode
        $data['default']['f03'] = ''; //Note
        
        $data['url_post'] = site_url('ref01/home/addpost');
        $this->load->view('ref01/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'ItemName', 'required');     
        $this->form_validation->set_rules('f02', 'ItemCode', 'required');     
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->input->post('f01');          
            $f02 = $this->input->post('f02');          
            $f03 = trim($this->input->post('f03'));          
            
            $record = array(
                'ItemName' => $f01,              
                'ItemCode' => $f02,              
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
            
            
            $rinventaris = $this->inventaris->checkr01($f01,$f02);
            $checkdata = ($rinventaris=='' or $rinventaris==NULL)?'empty':'exist';
            
            if($checkdata=='empty'){
                 $this->inventaris->insert_r01($record);
                 $this->logs->insert($recordlog);
                 
                 $alert ="Insert Data, Success";
                 $status ="true";
                 
            }else if($checkdata=='exist'){
                 $alert ="Insert Data, Failed because the data ".$f01.' with code :'.$f02.' already exist';
                 $status ="false";
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
                       "err_f03":"' . $err_f03
                . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->inventaris->getby_idr01($id);

        $data['default']['f01'] = $row->ItemName;
        $data['default']['f02'] = $row->ItemCode;
        $data['default']['f03'] = $row->Note;

        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('ref01/home/editpost');
        $this->load->view('ref01/form', $data);
    }

    function editpost() {
       $this->form_validation->set_rules('f01', 'ItemName', 'required');     
       $this->form_validation->set_rules('f02', 'ItemCode', 'required');     
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');          
            $f01 = $this->input->post('f01');          
            $f02 = $this->input->post('f02');          
            $f03 = trim($this->input->post('f03'));          
                     
            
            $record = array(
                'ItemName' => $f01,              
                'ItemCode' => $f02,              
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
                'activities' => 'edit data ' . $id
            );

            $this->historydata($id, 'edit');
            $this->inventaris->update_r01($id, $record);
            $this->logs->insert($recordlog);

            $mesg = 'update data, success';
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
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );

        $this->inventaris->update_r01($id, $record);
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
        $objSheet->setTitle('Inventaris');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(12);

        // write header        
        $objSheet->getCell('A1')->setValue('Item Name');
        $objSheet->getCell('B1')->setValue('Item Code');
        $objSheet->getCell('C1')->setValue('Note');
       
      
        $result = $this->inventaris->getall_r01();

        if ($result !== 'empty') {
            $i = 1;
            foreach ($result as $row) {
                $i++;
                
               
                $objSheet->getCell('A' . $i)->setValue($row['ItemName']);
                $objSheet->getCell('B' . $i)->setValue($row['ItemCode']);
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
            $objWriter->save($path_file . "inventaris" . $ext);
            $data = file_get_contents($path_file . "inventaris" . $ext);
            force_download("inventaris" . $ext, $data);
        }
    }
    
     function historydata($id, $function) {
         
         $row = $this->inventaris->getby_idr01($id);
         $record = array(
             "ItemName"=>$row->ItemName,
             "ItemCode"=>$row->ItemCode,
             "Note"=>$row->Note,
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
         
         $this->history->insert_r01_history($record);
         
         
     }


}
