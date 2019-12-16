<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('enroll_model', 'enroll');
        $this->load->model('Employee_model', 'employee');
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
        
        $this->hostremote ="192.168.0.117";
        $this->port =1212;
    }

     function index() { 
       error_reporting(0);  
       $opensocket = "";
       $connection = "";
       if ( $socket = socket_create(AF_INET, SOCK_STREAM, 0) ) {
            $opensocket = "Success";
            //socket_set_timeout($socket, 0, 500);
            socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec'=>10, 'usec'=>0));
            if ( socket_connect($socket, $this->hostremote, $this->port) ) {
                 $connection = "Success";
                 socket_close($socket);
            } else {
                 $connection = "Fail";
           }
       } else {
            $opensocket = "Fail";
       }

        $data['opensocket']         = $opensocket;
        $data['connection']         = $connection;   
        
        $idmenu                    = "146";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('mst03/home',$data);
    }


    function autocomplete_employee() {
        $result = $this->enroll->find_employee_active();
        $arr = array();
        foreach ($result->result() as $row) {
            
                    $enroll = round($row->IDCard);
                    
                    $arr[] = array('enroll' => $enroll,
                                    'fullname' => strtoupper($row->FullName),
                                     'idemployee' => $row->IDEmployee, 
                                     'nocard' => $row->CardNumber, 
                                     'location' => $row->IDLocation 
                     ); 
               
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    } 

 
    function dataenroll(){
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
                echo $this->enroll->getall_data();        
            } else if ($role == '0' and $param == 'Y') {
                echo $this->enroll->getall_data();        
            }
        }
           
    }   
     function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '146';
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
    
    
     function get_dataenroll (){
            
             if ( $socket = socket_create(AF_INET, SOCK_STREAM, 0) ) {
                  // create socket, success
                  if ( socket_connect($socket,  $this->hostremote, $this->port) ) {
                       // socket connection, success
                       $message = "helo";
                       if ( $this->send_msg($socket, $message) ) {
                           // Write to socket, success
                           if ( $this->read_msg($socket) == 'Welcome' ) {
                                // Read from socket, success
                                $message = "enroll";
                                if ( $this->send_msg($socket, $message) ) {
                                     // Write to socket, success
                                     if ( $this->read_msg($socket) == 'OK' ) {
                                          // Read from socket, success
                                          $message = "quit";
                                          if ( $this->send_msg($socket, $message) ) {
                                               // Write to socket, success
                                               $mesg  = "Synchronized Enroll Number, Success";
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

             $json = '{ "mesg":"' . $mesg . '",
                       "valid":"' . $valid . '"' .
                     '}';
             echo $json;            
    }


    function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->search_employee($q);
        $data['response'] = 'true';
        $data['message'] = array();
        foreach ($result->result() as $row) {
            $status = $row->Status;
            $now = $row->Sekarang;
            $lock = $row->BatasFilter;

            if ($status == 'P') {
                if ($now <= $lock) {
                    $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                        'value' => $row->FullName,
                        'idemployee' => $row->IDEmployee
                    );
                }
            } else {
                $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                    'value' => $row->FullName,
                    'idemployee' => $row->IDEmployee
                );
            }
        }
        echo json_encode($data);
    }
    
    
    
     function addnew() {
        $data['default']['f01'] = ''; //Enroll Number
        $data['default']['f02'] = ''; //Name
        $data['default']['f03'] = ''; //Card No
        $data['default']['f04'][1]['value'] = "Kapuk";
        $data['default']['f04'][1]['display'] = "KAPUK";       
        $data['default']['f04'][2]['value'] = "Bitung";
        $data['default']['f04'][2]['display'] = "BITUNG";
        $data['default']['f04'][3]['value'] = "All Site";
        $data['default']['f04'][3]['display'] = "ALL SITE";  
        
        $data['url_post'] = site_url('mst03/home/addpost');
        $this->load->view('mst03/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'EnrollNumber', 'required');
        $this->form_validation->set_rules('f02', 'Name', 'required');
        $this->form_validation->set_rules('f03', 'Card No', 'required');
        $this->form_validation->set_rules('f04', 'Location', 'required');

        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->input->post('f01');
            $f02 = $this->input->post('f02');
            $f03 = $this->input->post('f03');
            $f04 = $this->input->post('f04'); 
            
            if($f04 =='Kapuk'){
                  $record = "Inputonkapuk#".$f01."#"."$f02"."#".$f03;      
            }else if($f04 =='Bitung'){
                  $record = "Inputonbitung#".$f01."#"."$f02"."#".$f03;      
            }else if($f04 =='All Site'){
                  $record = "Inputallsite#".$f01."#"."$f02"."#".$f03;      
            }
              
            
             if ( $socket = socket_create(AF_INET, SOCK_STREAM, 0) ) {
                  // create socket, success
                  if ( socket_connect($socket, $this->hostremote, $this->port) ) {
                       // socket connection, success
                       $message = "helo";
                       if ( $this->send_msg($socket, $message) ) {
                           // Write to socket, success
                           if ( $this->read_msg($socket) == 'Welcome' ) {
                                // Read from socket, success
                                $message = $record;
                                
                                if ( $this->send_msg($socket, $message) ) {
                                     // Write to socket, success
                                     if ( $this->read_msg($socket) == 'OK' ) {
                                          // Read from socket, success
                                          $message = "quit";
                                          if ( $this->send_msg($socket, $message) ) {
                                               // Write to socket, success
                                               $mesg  = "Input Enroll Number, Success";
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
           
    }    

  
 function delete($id){        
            $row = $this->enroll->get_by_id($id);
            $enroll = $row->EnrollNumber;
            $loc = $row->Location;
            
            if($loc=='Kapuk In'){
                  $record = "Deletekapukin#".$enroll;   
            }else if($loc=='Kapuk Out'){
                  $record = "Deletekapukout#".$enroll;   
            }else if($loc=='Bitung In'){
                  $record = "Deletebitungin#".$enroll;   
            }else if($loc=='Bitung Out'){
                   $record = "Deletebitungout#".$enroll;   
            }
            
            
             if ($socket = socket_create(AF_INET, SOCK_STREAM, 0) ) {
                  // create socket, success
                  if ( socket_connect($socket, $this->hostremote, $this->port) ) {
                       // socket connection, success
                       $message = "helo";
                       if ( $this->send_msg($socket, $message) ) {
                           // Write to socket, success
                           if ( $this->read_msg($socket) == 'Welcome' ) {
                                // Read from socket, success
                                $message = $record;
                                if ( $this->send_msg($socket, $message) ) {
                                     // Write to socket, success
                                     if ( $this->read_msg($socket) == 'OK' ) {
                                          // Read from socket, success
                                          $message = "quit";
                                          if ( $this->send_msg($socket, $message) ) {
                                               // Write to socket, success
                                               $this->enroll->delete($enroll,$loc);
                                               $mesg  = "Delete Enroll Number, Success";
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
        $objSheet->setTitle('enroll_ report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:C1')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('Enroll Number');
        $objSheet->getCell('B1')->setValue('Name on Machine');
        $objSheet->getCell('C1')->setValue('Location Presence');


        $result = $this->enroll->get_data();
        if ($result != NULL) {
            $i = 1;
            foreach ($result as $row) {
                $i++;

                $objSheet->getCell('A' . $i)->setValue("'" . $row['EnrollNumber']);
                $objSheet->getCell('B' . $i)->setValue($row['Name']);
                $objSheet->getCell('C' . $i)->setValue($row['Location']);
   
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
            $objWriter->save($path_file . "enroll_attendance" . $ext);
            $data = file_get_contents($path_file . "enroll_attendance" . $ext);
            force_download("enroll_attendance" . $ext, $data);
        }
    }

}

