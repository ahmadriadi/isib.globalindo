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
        $idmenu = "236";
        $data['buttons'] = $this->uac->get_btnaccess($this->User, $idmenu);
        $this->load->view('mst01/home', $data);
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
                echo $this->inventaris->getdata_mst01();
            } else if ($role == '0' and $param == 'Y') {
                echo $this->inventaris->getdata_mst01();
            }
        }
    }

    function getprinter($id) {
        error_reporting(0);
        $row = $this->inventaris->getby_idmst01($id);
        $code = $row->CounterCode;
        $name = $row->ItemName;
        $note = $row->Note;

        $data['code'] = $code;
        $data['name'] = $name;
        $data['note'] = $note;
        $data['id'] = $id;

        $result = $this->inventaris->getall_r03();
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            $opensocket = "";
            $connection = "";
            $i=0;
            foreach ($result as $rowdata) {
                
                $i++;
                $computername = $rowdata['ComputerName'];
                $ipaddess = $rowdata['IPAddress'];
                $port = $rowdata['PortNumber'];
                $printer = $rowdata['PrinterName'];
                $idprint = $rowdata['ID'];

                if ($socket = socket_create(AF_INET, SOCK_STREAM, 0)) {
                    $opensocket = "Success";
                    //socket_set_timeout($socket, 0, 500);
                    socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => 10, 'usec' => 0));
                    if (socket_connect($socket, $ipaddess, $port)) {
                        $connection = "Ready";
                        socket_close($socket);
                    } else {
                        $connection = "Fail";
                    }
                } else {
                    $opensocket = "Fail";
                }
                
                $btnprint = "<button onClick ='printbarcode(".$idprint.")'  type='button' class='btn btn-mini btn-info'><i class='icon-print icon-white'></i></button>";

                $table = "<tr  class='selectable' width=\"100%\">";
                $table.= "    <td align=\"center\">" . $btnprint . "</td>";
                $table.= "    <td align=\"center\">" . $computername . "</td>";
                $table.= "    <td align=\"center\">" . $ipaddess . "</td>";
                $table.= "    <td align=\"left\">" . $port . "</td>";
                $table.= "    <td align=\"left\">" . $printer . "</td>";
		$table.= "    <td align=\"left\">" . $opensocket . "</td>";
                $table.= "    <td align=\"left\">" . $connection . "</td>";
                $table.= "</tr>";
                
                $data['statusprinter'][$i]['tr'] =$table; 
                                          
            }
            
                 
        }
        
      $this->load->view('mst01/printbarcode',$data);
    }
    
    function viewprintbarcode($iddata){
            $row = $this->inventaris->getby_idmst01($iddata);
           
            $code = $row->CounterCode;
            $name = $row->ItemName;
            $note = $row->Note;
            
            $mesg = "data sukses";
            $valid = "true";
            
              $json = '{ "mesg":"' . $mesg  . '",
                        "code":"' . $code  . '",
                        "name":"' . $name  . '",
                        "note":"' . $note  . '",
                       "valid":"' . $valid . '"' .
                     '}';
             echo $json;    
    }
    
    
    function send_msg($socket, $message) {
             $bSend = FALSE;
             if ( socket_write($socket, $message, strlen($message)) ) {
                  $bSend = TRUE;
             } 
             return $bSend;
        }
        
    function read_msg($socket) {
             if ( $result = socket_read ($socket, 1024) ) {
             } else {
                  $result = 'NULL';
             } 
             return $result;
        }
    
    
    
    function printbarcode($idprinter,$iddata,$qtyrow){
        $rowprinter = $this->inventaris->getby_idr03($idprinter);
        $rowitem = $this->inventaris->getby_idmst01($iddata);
        
        
        $hostaddress = $rowprinter->IPAddress;
        $port = $rowprinter->PortNumber;
        $code = $rowitem->CounterCode;
        $name = $rowitem->ItemName;
        $note = $rowitem->Note;
        
        
       $record = "PrintBarcode#".$code."#"."$name"."#".$note."#".$qtyrow; 
       
       if ( $socket = socket_create(AF_INET, SOCK_STREAM, 0) ) {
                  // create socket, success
                  if ( socket_connect($socket, $hostaddress, $port) ) {
                       // socket connection, success
                       $message = "HELLO";
                       if ( $this->send_msg($socket, $message) ) {
                           // Write to socket, success
                           if ( $this->read_msg($socket) == 'OPENSOCKET' ) {
                                // Read from socket, success
                                $message = $record;
                                
                                if ( $this->send_msg($socket, $message) ) {
                                     // Write to socket, success
                                     if ( $this->read_msg($socket) == 'OK' ) {
                                          // Read from socket, success
                                          $message = "QUIT";
                                          if ( $this->send_msg($socket, $message) ) {
                                               // Write to socket, success
                                               $mesg  = "Data, Success";
                                               $valid = 'true';
                                          } else {
                                               // Write to socket, fail
                                               $mesg  = "Send Message, Fail (07)";
                                               $valid = 'false';
                                          }
                                     } else {
                                          // Read from socket, success
                                          $mesg  = "Read Message, Fail (06)";
                                          $valid = 'false';
                                     }
                                } else {
                                     // Write to socket, fail
                                     $mesg  = "Send Message, Fail (05)";
                                     $valid = 'false';
                                }
                            } else {
                                // Read from socket, success
                                $mesg  = "Read Message, Fail (04)";
                                $valid = 'false';
                            }
                       } else {
                           // Write to socket, success
                           $mesg  = "Send Message, Fail (03)";
                           $valid = 'false';
                       }
                  } else {
                       // socket connection, success
                       $mesg  = "Socket Connection, Fail (02)";
                       $valid = 'false';
                 }
             } else {
                  // create socket, fail
                  $mesg  = "Create Socket, Fail (01)";
                  $valid = 'false';
             }
           

             $json = '{ "mesg":"' . $mesg  . '",
                       "valid":"' . $valid . '"' .
                     '}';
             echo $json; 
            
        }
       

    function get_access() {
        $button = $this->input->post('btn');
        $idmenu = '236';
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

    function getcounter_item() {
        $itemcode = $this->input->post('itemcode');
        $data = $this->inventaris->getdata_counter($itemcode);
        $temp = "000" . $data;
        $counter = substr($temp, -3);

        $codecounter = 'IT-' . $itemcode . '-' . $counter;
        $valid = 'true';
        $mesg = 'success';

        $json = '{ "mesg":"' . $mesg . '",
                   "counterdata":"' . $codecounter . '",
                   "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function addnew() {
        $i = 0;
        $result1 = $this->inventaris->getall_r01();
        foreach ($result1 as $row) {
            $data['default']['f01'][-1]['value'] = NULL;
            $data['default']['f01'][-1]['display'] = '-Please Select-';
            $data['default']['f01'][$i]['value'] = $row['ItemCode'];
            $data['default']['f01'][$i]['display'] = $row['ItemName'];
            $i++;
        }


        $data['default']['f02'] = ''; //CounterCode
        $data['default']['f03'] = ''; //Note

        $data['flagcondition'] = 'add';
        $data['default']['readonly_f02'] = 'READONLY';
        $data['url_post'] = site_url('mst01/home/addpost');
        $this->load->view('mst01/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'Item', 'required');
        $this->form_validation->set_rules('f02', 'Code Counter', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->input->post('f01');
            $f02 = $this->input->post('f02');
            $f03 = $this->input->post('f03');


            $record = array(
                'ItemCode' => $f01,
                'CounterCode' => $f02,
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
                'controller' => site_url('mst01/home/addnew'),
                'activities' => 'add new ' . $f01
            );


            $rinventaris = $this->inventaris->checkmst01($f01, $f02);
            $checkdata = ($rinventaris == '' or $rinventaris == NULL) ? 'empty' : 'exist';

            if ($checkdata == 'empty') {
                $this->inventaris->insert_mst01($record);
                $this->logs->insert($recordlog);

                $alert = "Insert Data, Success";
                $status = "true";
            } else if ($checkdata == 'exist') {
                $alert = "Insert Data, Failed because the data " . $f02 . ' Already Exist';
                $status = "false";
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
        $row = $this->inventaris->getby_idmst01($id);

        $i = 0;
        $result = $this->inventaris->getall_r01();
        foreach ($result as $rowitem) {
            $data['default']['f01'][$i]['value'] = $rowitem['ItemCode'];
            $data['default']['f01'][$i]['display'] = $rowitem['ItemName'];
            if ($row->ItemCode == $rowitem['ItemCode']) {
                $data['default']['f01'][$i]['selected'] = "SELECTED";
            }
            $i++;
        }


        $data['default']['f02'] = $row->CounterCode;
        $data['default']['f03'] = $row->Note;


        $data['flagcondition'] = 'edit';
        $data['default']['readonly_f02'] = 'READONLY';
        $data['url_post'] = site_url('mst01/home/editpost');
        $this->load->view('mst01/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Item', 'required');
        $this->form_validation->set_rules('f02', 'Counter', 'required');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->input->post('f01');
            $f02 = $this->input->post('f02');
            $f03 = $this->input->post('f03');

            $record = array(
                'ItemCode' => $f01,
                'CounterCode' => $f02,
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
                'controller' => site_url('mst01/home/addnew'),
                'activities' => 'edit data ' . $id
            );

            $this->historydata($id, 'edit');
            $this->inventaris->update_mst01($id, $record);
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

        $this->inventaris->update_mst01($id, $record);
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
        $objSheet->setTitle('Master Data Inventory');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(12);

        // write header        
        $objSheet->getCell('A1')->setValue('Item Code');
        $objSheet->getCell('B1')->setValue('Item Name');
        $objSheet->getCell('C1')->setValue('Note');


        $result = $this->inventaris->getall_mst01();

        if ($result !== 'empty') {
            $i = 1;
            foreach ($result as $row) {
                $i++;



                $objSheet->getCell('A' . $i)->setValue($row['CounterCode']);
                $objSheet->getCell('B' . $i)->setValue($row['ItemName']);
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
            $objWriter->save($path_file . "masterinventaris" . $ext);
            $data = file_get_contents($path_file . "masterinventaris" . $ext);
            force_download("masterinventaris" . $ext, $data);
        }
    }

    function historydata($id, $function) {

        $row = $this->inventaris->getby_idmst01($id);
        $record = array(
            "CounterCode" => $row->CounterCode,
            "ItemCode" => $row->ItemCode,
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

        $this->history->insert_mst01_history($record);
    }

}
