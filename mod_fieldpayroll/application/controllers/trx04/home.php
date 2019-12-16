<?php
//OVERTIME
class Home extends CI_Controller {

    public function __construct() {        
        parent ::__construct();
        $this->load->model('Insentif_model', 'insentif');
        $this->load->model('Employee_model', 'employee');
        $this->load->model('logs_model', 'logs');
        $this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('Param_model', 'param');
        $this->load->model('uac_model', 'uac');
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
        $idmenu                    = "79";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('trx04/home',$data);
    }

    function resultinsentif() {	
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
                    echo $this->insentif->datainsentif();	
            } else if ($role == '0' and $param == 'Y') {
                    echo $this->insentif->datainsentif();	
            }
        }
        

    }

   function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '79';
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

    function periodpayroll() {
        // Periode Staff 25-24
        if (date('d') >= 25) {
            $selisih = date('d') - 25;
            //eg: 27-04-2012
            $from = date('d-m-Y', strtotime("-" . $selisih . " days", strtotime(date('d-m-Y'))));
            $until = date('d-m-Y', strtotime("+1 month -1 day", strtotime($from)));
        } else {
            // eg: 04-02-2012
            $selisih = 25 - date('d');
            $from = date('d-m-Y', strtotime("-1 month +" . $selisih . "days", strtotime(date('d-m-Y'))));
            $until = date('d-m-Y', strtotime("+1 month -1 day", strtotime($from)));
        }
        return $from . " " . $until;
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
        $data['default']['f01'] = ''; //IDEmployee
        $data['default']['f02'] = ''; //FullName
        //IDJobGroup        
        $data['default']['f03'][1]['value']   = "A";
        $data['default']['f03'][1]['display'] = "Active";
        $data['default']['f03'][1]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value']   = "P";
        $data['default']['f03'][2]['display'] = "Passive";                           
        $data['default']['f04'] = ''; //Amount   
        $data['default']['f05'] = ''; //Note  

        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('trx04/home/addpost');        
        $this->load->view('trx04/form',$data);
        
       
    }

    function addpost() {
       $this->form_validation->set_rules('f01',  'IDEmployee', 'required');              
       $this->form_validation->set_rules('f04',  'Amount ', 'required|numeric');
                               
                if ($this->form_validation->run() == TRUE) {
                    $f01 = $this->anti_xss($this->input->post('f01')); 
                    $f03 = $this->anti_xss($this->input->post('f03'));
                    $f04 = $this->anti_xss($this->input->post('f04'));                   
                    $f05 = $this->anti_xss($this->input->post('f05'));  
                    $record = array(                
                        'IDEmployee'=> $f01,
                        'Status'=> $f03, 
                        'Amount'=> $f04,
                        'Note'=> TRIM($f05),
                        'AddedBy'   => $this->User,
                        'AddedDate' => $this->Datetime,
                        'AddedIP'   => $this->Ip
                        

                    );
                    $this->insentif->insert($record);
                    $mesg = 'insert data, success';
                    $valid = 'true';
                    $err_f01 = '';              
                    $err_f02 = '';
                    $err_f03 = '';
                    $err_f04 = '';
                    $err_f05 = ''; 

                } else {
                    $mesg = 'insert data, failed';
                    $valid = 'false';            
                    $err_f01 = form_error('f01');            
                    $err_f02 = form_error('f02');
                    $err_f03 = form_error('f03');
                    $err_f04 = form_error('f04');
                    $err_f05 = form_error('f05');


                }
                $json = '{ "mesg":"' . $mesg . '", 
                        "valid":"' . $valid . '", 
                        "err_f01":"' . $err_f01 . '",                 
                        "err_f02":"' . $err_f02 . '",
                        "err_f03":"' . $err_f03 . '",
                        "err_f04":"' . $err_f04 . '",
                        "err_f05":"' . $err_f05 . '"
                        ' .
                        '}';
                echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->insentif->get_by_id($id);
        $data['default']['f01'] = $row->IDEmployee;//IDEmployee    
        $data['default']['f02'] = $row->FullName; //FullName               
        //Status
        $data['default']['f03'][1]['value']   = "A";
        $data['default']['f03'][1]['display'] = "Active";
        $data['default']['f03'][2]['value']   = "P";
        $data['default']['f03'][2]['display'] = "Passive";

        if($row->Status == 'A'){
            $data['default']['f03'][1]['checked'] = "CHECKED";
        }else if($row->Status == 'P'){
            $data['default']['f03'][2]['checked'] = "CHECKED";
        }

        $data['default']['f04'] = $row->Amount;
        $data['default']['f05'] = $row->Note;     
        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';      
        $data['url_post'] = site_url('trx04/home/editpost');
        
       
        $this->load->view('trx04/form',$data);
           
    }

    function editpost() {
                $this->form_validation->set_rules('f01',  'IDEmployee', 'required');
                $this->form_validation->set_rules('f04',  'Amount', 'required|numeric'); 
                
                if ($this->form_validation->run() == TRUE) {
                    $f01 = $this->anti_xss($this->input->post('f01'));
                    $id = $this->session->userdata('id');   
                    $f03 = $this->anti_xss($this->input->post('f03'));
                    $f04 = $this->anti_xss($this->input->post('f04'));               
                    $f05 = $this->anti_xss($this->input->post('f05'));   
                    
                    $record = array(  
                        'Status'=> $f03,
                        'Amount'=> $f04,
                        'Note'=> TRIM($f05),
                        'EditedBy'   => $this->User,
                        'EditedDate' => $this->Datetime,
                        'EditedIP'   => $this->Ip
                        

                    );

		    $this->historydata($id);	
                    $this->insentif->update($id, $record);
                                        
                   if($f03 =='P'){                       
                     $date     = $this->periodpayroll();                            
                     $datepost = substr($date, 11, 10);                             
                     $posdate  = date('Y-m-d', strtotime($datepost));                     
                     $param    = 'INSENTIF';
                     $flag     = 'SYSTEM';                     
                     $rowdata = $this->insentif->check_additonal($f01,$posdate,$param,$flag);
                     $check = ($rowdata=='' or $rowdata==null)?'empty':'exist'; 
                     
                     if($check =='exist'){     
                              $this->insentif->delete_additional($f01,$posdate,$param,$flag);
                                         
                        }                      
                    }                   
                    
                    $mesg = 'update data, success';
                    $valid = 'true';
                    $err_f01 = '';              
                    $err_f02 = '';
                    $err_f03 = '';
                    $err_f04 = '';
                    $err_f05 = ''; 

                } else {
                    $mesg = 'update data, failed';
                    $valid = 'false';            
                    $err_f01 = form_error('f01');            
                    $err_f02 = form_error('f02');
                    $err_f03 = form_error('f03');
                    $err_f04 = form_error('f04');
                    $err_f05 = form_error('f05');

                }
                $json = '{ "mesg":"' . $mesg . '", 
                        "valid":"' . $valid . '", 
                        "err_f01":"' . $err_f01 . '",                 
                        "err_f02":"' . $err_f02 . '",
                        "err_f03":"' . $err_f03 . '",
                        "err_f04":"' . $err_f04 . '",
                        "err_f05":"' . $err_f05 . '"
                        ' .
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
        
        $row = $this->insentif->get_by_id($id);	
	
        $nip 	  = $row->IDEmployee;
        $date     = $this->periodpayroll();                            
        $datepost = substr($date, 11, 10);                             
        $posdate  = date('Y-m-d', strtotime($datepost));                     
        $param    = 'INSENTIF';
        $flag     = 'SYSTEM';                     
        $rowdata = $this->insentif->check_additonal($nip,$posdate,$param,$flag);
        $check = ($rowdata=='' or $rowdata==null)?'empty':'exist';                             
        if($check =='exist'){     
                 $this->insentif->delete_additional($nip,$posdate,$param,$flag);

        }        
        $this->insentif->update($id, $record);
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
        $objSheet->setTitle('insentif report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:E1')->getFont()->setBold(true)->setSize(12);

        // write header       
        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');
	$objSheet->getCell('C1')->setValue('Group');      
        $objSheet->getCell('D1')->setValue('Amount');      
        $objSheet->getCell('E1')->setValue('Note');
      
        
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->insentif->getall_data($f,$u);
        if ($result != NULL) {   
            $i = 1;
            foreach ($result as $row) {
                $i++;
                        
		if($row['IDJobGroup']=='LT'){
                    $group = "LAPANGAN TETAP";
                }else if($row['IDJobGroup'] =='LK'){
                     $group = "LAPANGAN KONTRAK";
                }else if($row['IDJobGroup'] =='HL'){
                     $group = "HARIAN LEPAS";
                }else if($row['IDJobGroup'] =='LL'){
                     $group = "LAIN-LAIN";
                }else{
                    $group = "-";
                }    	
        
                $objSheet->getCell('A' . $i)->setValue("'".$row['IDEmployee']);
                $objSheet->getCell('B' . $i)->setValue($row['FullName']);
		$objSheet->getCell('C' . $i)->setValue($group);               
                $objSheet->getCell('D' . $i)->setValue(number_format($row['Amount'],'2',',','.'));               
                $objSheet->getCell('E' . $i)->setValue($row['Note']);
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:E' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:E' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:E1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);            

            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "insentif" . $ext);
            $data = file_get_contents($path_file . "insentif" . $ext);
            force_download("insentif" . $ext, $data);
        }
    }

   function historydata($id){
         $rowh = $this->history->getby_id_insentif($id); 
            $record = array(
                "IDEmployee"=>$rowh->IDEmployee,
                "Amount"=>$rowh->Amount,
                "Status"=>$rowh->Status,
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
            
            $this->history->insert_hisinsentif($record);
            
    }

   

}

