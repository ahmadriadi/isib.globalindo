<?php

//USER REPORT/REQUEST
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('weeklyactivity_model', 'weekly');
	$this->load->model('historytable_model', 'historytable');
        $this->load->model('logs_model', 'logs');
        $this->load->model('uac_model', 'uac');
        $this->load->model('libraryfunction_model', 'libfun');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

   

    function anti_xss($source) {
        $f = stripslashes(strip_tags(htmlspecialchars($source, ENT_QUOTES)));
        return $f;
    }

    function index() {
        $fromd = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');
        $check1 = ($fromd == '' or $fromd == null) ? 'empty' : 'exist';
        $check2 = ($untild == '' or $untild == null) ? 'empty' : 'exist';


        if ($check1 == 'empty' and $check2 == 'empty') {
            $date = $this->libfun->periode_one_month();
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

        

        $this->load->view('trx04/home', $data);
    }
    
   function getdatatable() {
       $f = date('Y-m-d',  strtotime($this->session->userdata('datefrom')));
       $u =  date('Y-m-d',  strtotime($this->session->userdata('dateuntil')));
      
       $rowchild = $this->weekly->get_child($this->User)->result_array();
       $role = $this->session->userdata('sess_role');
       if($role =='2'){
           $status = 'open';
       }else{
           $status = 'filter';
       }
       
       
       $check = ($rowchild=='' or $rowchild ==null)?'empty':'exist';
       if($check =='exist'){
           $user = array();       
            foreach ($rowchild as $row){
                 $user[] = $row['IDEmployee'];
                 $user[] = $row['IDEmployeeParent'];
            }  
       }else{           
           $user = $this->User;
       }
       

        echo $this->weekly->getdata($f,$u,$user,$status);
      	

    }

      function datacomment($id){
        $result = $this->historytable->getby_his_weekly($id);
        if ($result !== 'empty') {
            $i = 0;
            foreach ($result as $row) {
                $i++;
                $table = "<tr  class='selectable' width=\"100%\">";
                $table.= "    <td align=\"center\">" . $row['FullName'] . "</td>";
                $table.= "    <td align=\"center\">" . $row['TestedNote'] . "</td>";
                $table.= "</tr>";
                $data['resultdata'][$i]['tr'] = $table;
            }
        }

        $this->load->view('trx04/comment', $data);
        
    }	

    
    function getstatus() {
        $id = $this->input->post('id');
        $rowh = $this->weekly->getby_id($id);
        $flag = $rowh->Tested;
        $valid = 'true';
        $json = '{ "flag":"' . $flag . '",
		   "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }
    
    
      function checkdata() {
        $id = $this->anti_xss($this->input->post('id'));
        $row = $this->weekly->getby_id($id);
        $check = $row->Tested;        
        $val = ($check =='1')?'0':'1';    
        
        $record = array(
            "Tested" => $val,
            "TestedBy" => $this->User,
            "TestedDate" => $this->Datetime,
            "TestedIP" => $this->Ip,
           
        );
        
        $this->weekly->update($id, $record);    
        
        $mesg = "Check Data, Success";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
        
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

    function set_pattern_date() {
        $valid = "true";

        $fromdate = date('Y-m-d', strtotime($this->input->post('fromdate')));
        $untildate = date('Y-m-d', strtotime($this->input->post('untildate')));
        $this->session->set_userdata('fromdate', $fromdate);
        $this->session->set_userdata('untildate', $untildate);

        echo '{ "valid":"' . $valid . '"}';
    }

    function autocomplete_employee() {
        $result = $this->employee->search_employee();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                'fullname' => strtoupper($row->FullName)
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

   
     function addnew(){
        $data['default']['f01'] = '';  //JobActivity 
        $data['default']['f02'] = '';  //DateLine 
        $data['default']['f03'][1]['value'] = "0";
        $data['default']['f03'][1]['display'] = "In Progress";
        $data['default']['f03'][1]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value'] = "1";
        $data['default']['f03'][2]['display'] = "Done";
        $data['default']['f03'][3]['value'] = "2";
        $data['default']['f03'][3]['display'] = "Pending";
        $data['default']['f04'] = '';  //Note 
      

	$data['flag'] ='close'; 
        $data['url_post'] = site_url('trx04/home/addpost');
        $this->load->view('trx04/form',$data);       
        
    }
    
     function addpost() {
        $this->form_validation->set_rules('f01', 'Job Activity', 'required');
        $this->form_validation->set_rules('f02', 'Date Line', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->input->post('f01');
            $f02 = date('Y-m-d',  strtotime($this->anti_xss($this->input->post('f02'))));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->input->post('f04');
          
            
            $record = array(
                'JobActivity' => $f01,
                'PIC' => $this->User,
                'DateLine' => $f02,
                'StatusActivity' => $f03,              
                'Note' => $f04,              
                'AddedBy' => $this->User,              
                'AddedDate' => $this->Datetime,              
                'AddedIP' => $this->Ip,              
            );
            
                      
            $this->weekly->insert($record);
           
            $mesg = 'insert data, succes';
            $valid = 'true';
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
                    "err_f01":"' . $err_f01 . '",
                    "err_f02":"' . $err_f02 . '",
                    "err_f03":"' . $err_f03 . '",
                    "err_f04":"' . $err_f04 . '",
                    "valid":"' . $valid . '"'.
                '}';
        echo $json;
                
    }
    
  
    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->weekly->getby_id($id);
     
        $data['default']['f01'] = $row->JobActivity; //JobActivity
        $data['default']['f02'] = date('d-m-Y',  strtotime($row->DateLine)); //DateLine
       
        $data['default']['f03'][1]['value'] = "0";
        $data['default']['f03'][1]['display'] = "In Progress";
        $data['default']['f03'][1]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value'] = "1";
        $data['default']['f03'][2]['display'] = "Done";
        $data['default']['f03'][3]['value'] = "2";
        $data['default']['f03'][3]['display'] = "Pending";
      
        if ($row->StatusActivity == '0') {
            $data['default']['f03'][1]['checked'] = "CHECKED";
        } else if ($row->StatusActivity == '1') {
            $data['default']['f03'][2]['checked'] = "CHECKED";
        }else if ($row->StatusActivity == '2'){
            $data['default']['f03'][3]['checked'] = "CHECKED";
        }
        
         $data['default']['f04'] = $row->Note; //Note
	 $data['default']['f05'] = $row->TestedNote; //Note
         
        
      
	$position = $this->session ->userdata('sess_position');
        $role = $this->session ->userdata('sess_role');
        
        if($position =='MANAGER' OR $role =='2'){
            $status = 'open';
        }else{
            $status = 'close';
            
        }

	$data['flag'] =  $status; 
        $data['url_post'] = site_url('trx04/home/editpost');
        $this->load->view('trx04/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Job Activity', 'required');
        $this->form_validation->set_rules('f02', 'Date Line', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id= $this->session->userdata('id');
            $f01 = $this->input->post('f01');
            $f02 = date('Y-m-d',  strtotime($this->anti_xss($this->input->post('f02'))));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->input->post('f04');
	    $f05 = $this->input->post('f05');	
          
            
            $record = array(
                'JobActivity' => $f01,
                'PIC' => $this->User,
                'DateLine' => $f02,
                'StatusActivity' => $f03,              
                'Note' => $f04,     
		'TestedNote' => $f05,          
                'EditedBy' => $this->User,              
                'EditedDate' => $this->Datetime,              
                'EditedIP' => $this->Ip,              
            );
            
            $this->historydata($id, 'editpost');          
            $this->weekly->update($id,$record);
           
            $mesg = 'update data, succes';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
       
            
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
        }
        
        $json = '{ "mesg":"' . $mesg . '", 
                    "err_f01":"' . $err_f01 . '",
                    "err_f02":"' . $err_f02 . '",
                    "err_f03":"' . $err_f03 . '",
                    "err_f04":"' . $err_f04 . '",
                    "valid":"' . $valid . '"'.
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

        $this->weekly->update($id, $record);
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
        $objSheet->setTitle('weekly report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:H4')->getFont()->setBold(true)->setSize(12);

        // write header
        $objSheet->getCell('A1')->setValue('PT TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A2')->setValue('REPORT WEEKLY ACTIVITY');
        $objSheet->getCell('A3')->setValue('PERIODE : '.$this->session->userdata('datefrom').' s/d '.$this->session->userdata('dateuntil'));
        
        
        $objSheet->getCell('A4')->setValue('No');
        $objSheet->getCell('B4')->setValue('Activity');
        $objSheet->getCell('C4')->setValue('PIC');
        $objSheet->getCell('D4')->setValue('Dateline');     
        $objSheet->getCell('E4')->setValue('Status');
        $objSheet->getCell('F4')->setValue('Tested');
        $objSheet->getCell('G4')->setValue('Note');
	$objSheet->getCell('H4')->setValue('Tested Note');

        $f = date('Y-m-d',  strtotime($this->session->userdata('datefrom')));
        $u = date('Y-m-d',  strtotime($this->session->userdata('dateuntil')));
        
       $rowchild = $this->weekly->get_child($this->User)->result_array();
       $check = ($rowchild=='' or $rowchild ==null)?'empty':'exist';
       if($check =='exist'){
           $user = array();       
            foreach ($rowchild as $row){
                 $user[] = $row['IDEmployee'];
                 $user[] = $row['IDEmployeeParent'];
            }  
       }else{           
           $user = $this->User;
       }
       
      $role = $this->session->userdata('sess_role');
       if($role =='2'){
           $status = 'open';
       }else{
           $status = 'filter';
       }
       
       
        
        $result = $this->weekly->getalldata($f, $u, $user,$status);
        if ($result != NULL) {
            $i = 4;
            $no = 0;
            foreach ($result as $row) {
                $i++;
                $no++;

                if ($row['Tested'] == '0') {
                    $tested = 'No';
                } else if ($row['Tested'] == '1') {
                    $tested = 'Yes';
                } else{
                    $tested = '-';
                }

                if ($row['StatusActivity'] == '0') {
                    $status = 'In Progress';
                } else if ($row['StatusActivity'] == '1') {
                    $status = 'Done';
                } else if($row['StatusActivity'] == '2'){
                    $status = 'Pending';
                }else{
                    $status = '-';
                }


                $objSheet->getCell('A' . $i)->setValue($no);
                $objSheet->getCell('B' . $i)->setValue(strip_tags($row['JobActivity']));
                $objSheet->getCell('C' . $i)->setValue($row['PIC'].'-'.$row['FullName']);              
                $objSheet->getCell('D' . $i)->setValue(date('d-m-Y',  strtotime($row['DateLine'])));
                $objSheet->getCell('E' . $i)->setValue($status);
                $objSheet->getCell('F' . $i)->setValue($tested);
                $objSheet->getCell('G' . $i)->setValue(strip_tags($row['Note']));
		$objSheet->getCell('H' . $i)->setValue(strip_tags($row['TestedNote']));
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A4:H' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A4:H' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A4:H4')->getBorders()->
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
         
            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "weeklyreport" . $ext);
            $data = file_get_contents($path_file . "weeklyreport" . $ext);
            force_download("weeklyreport" . $ext, $data);
        }
    }

 function historydata($id,$function){
         $row = $this->weekly->getby_id($id);
         $record = array(
             "JobActivity"=>$row->JobActivity,
             "PIC"=>$row->PIC,
             "DateLine"=>$row->DateLine,
             "StatusActivity"=>$row->StatusActivity,
             "Tested"=>$row->Tested,
             "TestedBy"=>$row->TestedBy,
	     "TestedNote"=>$row->TestedNote,	
             "TestedDate"=>$row->TestedDate,
             "TestedIP"=>$row->TestedIP,
             "Note"=>$row->Note,
             "AddedBy"=>$row->AddedBy,
             "AddedDate"=>$row->AddedDate,
             "AddedIP"=>$row->AddedIP,
             "EditedBy"=>$row->EditedBy,
             "EditedDate"=>$row->EditedDate,
             "EditedIP"=>$row->EditedIP,
             "DeletedBy"=>$row->DeletedBy,
             "DeletedDate"=>$row->DeletedDate,
             "DeletedIP"=>$row->DeletedIP,
             "DeleteFlag"=>$row->DeleteFlag,
             "IDTable"=>$id,
             "FunctionOn"=>$function,
             "HistBy"=>$this->User,
             "HistDate"=>$this->Datetime,
             "HistIP"=>$this->Ip,
             
         );
         
         
         $this->historytable->insert_weekly($record);
         
        
    }
  

}

