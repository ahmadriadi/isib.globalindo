<?php
//INCOMPLETE
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        date_default_timezone_set("Asia/Jakarta"); 
        $this->load->model('Incomplete_trx_model', 'incomplete');
        $this->load->model('Employee_model', 'employee');
        $this->load->model('logs_model', 'logs');
        $this->load->model('libraryfunction_model','libfun');
	
        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function get_accepted(){
        $accepted   = $this->incomplete->get_accepted($this->User);
        echo $accepted;
    }
    function anti_xss($source)
    {
            $f=stripslashes(strip_tags(htmlspecialchars ($source,ENT_QUOTES)));
            return $f;
    }

   function index() {
        $fromd = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');   
        $check1 = ($fromd =='' or $fromd ==null)?'empty':'exist';
        $check2 = ($untild =='' or $untild ==null)?'empty':'exist';  
        // $data['test1'] = $fromd;
        //  $data['test2'] = $untild;        
        if($check1 =='empty' and $check2=='empty'){
            $date = $this->libfun->periode_work();
            $fromdate = substr($date, 0, 10);
            $untildate = substr($date, 11, 10);                
            $this->session->set_userdata('datefrom', $fromdate);
            $this->session->set_userdata('dateuntil', $untildate);            
        }else{            
             $fromdate = $this->session->userdata('fromdate');
             $untildate = $this->session->userdata('untildate');  
             $this->session->set_userdata('datefrom', $fromdate);
             $this->session->set_userdata('dateuntil', $untildate);
        }        
        
        $data['default']['from'] = date('d-m-Y', strtotime($fromdate));
        $data['default']['until'] = date('d-m-Y', strtotime($untildate));
	
        $this->load->view('trx03/home',$data);
    }

    function dataincomplete() {
	
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        echo $this->incomplete->incompleteemployee($f, $u,$this->User);
	//echo $this->incomplete->incompleteemployee($this->User);
    }

     function getstatus(){
        $id = $this->input->post('id');
        $rowh = $this->incomplete->get_by_id($id);
        $flag = $rowh->ConfirmFlag;        
        $valid = 'true';
	$json = '{ "flag":"' . $flag . '",
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

    function suggest_employee() {
        $result = $this->employee->search_employee();
        $arr = array();
        foreach ($result->result() as $row) {
            $arr[] = array('idemployee' => $row->IDEmployee,
                'fullname' => strtoupper($row->FullName)
            );
        }
        echo json_encode($arr, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    function checktimein() {
        $time = $this->input->post('f04');       
        $len = strlen($time);
        $hour = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        if ($len < 4 | $len > 4) {
            $this->form_validation->set_message("checktimein",  "Length of string :".$len." is too short");
            return false;
        } else {
            if ($hour < 0 | $hour > 23) {
                $this->form_validation->set_message("checktimein", "Hour string should not below 0 or exceed 23 ");
                return false;
            } else {
                if ($minutes < 0 | $minutes > 59) {
                    $this->form_validation->set_message("checktimein", "Minute string should not below  0 or exceed 59 ");
                    return false;
                } else {
                    return true;
                }
            }
        }
    }

    function checktimeout() {
        $time = $this->input->post('f05');       
        $len = strlen($time);
        $hour = substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        if ($len < 4 | $len > 4) {
            $this->form_validation->set_message("checktimeout",  "Length of string :".$len." is to short");
            return false;
        } else {

            if ($hour < 0 | $hour > 23) {
                $this->form_validation->set_message("checktimeout", "Hour string should not below 0 or exceed 23 ");
                return false;
            } else {
                if ($minutes < 0 | $minutes > 59) {
                    $this->form_validation->set_message("checktimeout", "Minute string should not below  0 or exceed 59 ");
                    return false;
                } else {
                    return true;
                }
            }
        }
    }
    	
   function atluser(){
        $f = date('Y-m-d',  strtotime($this->session->userdata('datefrom')));
        $u = date('Y-m-d',  strtotime($this->session->userdata('dateuntil'))); 
        $count = $this->incomplete->countatl($f,$u,$this->User);   
        $valid = 'true';        
        $json = '{ "youratl":"' . $count . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;        
    }
    
    function addnew() {
        $data['default']['f01'] = ''; //IDEmployee       
        $data['default']['f02'] = ''; //fullname
        $data['default']['f03'] = ''; //IncompleteDate
        $data['default']['f04'] = ''; //TimeIn
        $data['default']['f05'] = ''; //TimeOut
        $data['default']['f06'] = ''; //Note

        $data['default']['readonly_f01'] = 'READONLY';
        $data['url_post'] = site_url('trx03/home/addpost');
        $this->load->view('trx03/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');        
        $this->form_validation->set_rules('f03', 'Incomplete Date', 'required');        
        $this->form_validation->set_rules('f04', 'Time In', 'required|callback_checktimein');
        $this->form_validation->set_rules('f05', 'Time Out', 'required|callback_checktimeout');
        
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->User;
            $f03 = $this->anti_xss($this->input->post('f03'));           
      	    $f04 = $this->input->post('f04');  $h1 = substr($f04, 0, 2); $m1 = substr($f04, 2, 2); $timein = $h1.':'.$m1;
            $f05 = $this->input->post('f05');  $h2 = substr($f05, 0, 2); $m2 = substr($f05, 2, 2); $timeout = $h2.':'.$m2;
            $f06 = $this->anti_xss($this->input->post('f06'));       
            $user = $this->incomplete->get_personal($f01)->row();
            if ($user->IDJobPosition == "MANAGER"){
                $con = "1";
                $condate    = date('Y-m-d H:i:s');
                $conip  = $this->Ip;
                $conby  = "sys";
                $hrdflag    = "1";
                $hrdid      = "sys";
                $hrdip      = "";
                $hrddate    = date('Y-m-d H:i:s');
            }
            else{
                $con = "0";
                $condate    = "0000-00-00";
                $conip  = "";
                $conby  = "";
                $hrdflag    = "0";
                $hrdid      = "";
                $hrdip      = "";
                $hrddate    = "0000-00-00";                
            }
            $record = array(
                'IDEmployee' => $f01,
                'IncompleteDate' => date('Y-m-d', strtotime($f03)),
                'TimeIn' => $timein,
                'TimeOut' => $timeout,                              
                'Note' => TRIM($f06),
                'ConfirmFlag' => $con,
                'ConfirmDate' => $condate,
                'ConfirmIP' => $conip,
                'ConfirmBy' => $conby,
//                'CHRDFlag'  => $hrdflag,
//                'CHRDID'  => $hrdid,
//                'CHRDIP'  => $hrdip,
//                'CHRDDate'  => $hrddate,
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
                'controller' => site_url('trx02/home/addnew'),
                'activities' => 'add new ' . $f01
            );
                        
            $resultcheck = $this->incomplete->checkdata($f01,$f03);
            if($resultcheck =='exist'){                
                $mesg = 'insert data, failed. Employee ID :'.$f01.' on '.$f03.' already exists';
                $valid = 'false';
                $err_f01 = 'ERROR';
                $err_f02 = 'ERROR';
		$err_f03 = 'ERROR';
                $err_f04 = '';
                $err_f05 = '';
                $err_f06 = '';                
            }else{
                $this->incomplete->insert($record);
                $this->logs->insert($recordlog);
                $mesg = 'insert data, success';
                $valid = 'true';
                $err_f01 = '';
                $err_f02 = '';
		$err_f03 = '';
                $err_f04 = '';
                $err_f05 = '';
                $err_f06 = '';
//                kirim email pemberitahuan ke atasan
                $prs    = $this->incomplete->get_personal($f01)->row();
                $ats    = $this->incomplete->get_prs_public($prs->IDEmployeeParent)->row();
                $sendto = $ats->InternalEmail;
                $subject= "TIS Notification - Incomplete";                
                $data['state']  = "confirm";
                $data['for']  = "manager";
                $data['sendername'] = $prs->FullName;
                $data['receivername'] = $ats->FullName;
                $message= $this->load->view("trx03/email",$data,TRUE);
                $this->sendmail->internalmail($sendto, $subject, $message);
                $eksmail    = explode(",", $ats->ExternalEmail);
                if ($this->sendmail->externalmail($eksmail, $subject, $message)){
//                        echo $ex." => berhasil";
                }

            }            
         
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
        $row = $this->incomplete->get_by_id($id);       
        $data['default']['f01'] = $row->IDEmployee; //idemployee
        $data['default']['f02'] = $row->FullName; //fullname
        $data['default']['f03'] = date('d-m-Y', strtotime($row->IncompleteDate)); //presencedate
        $data['default']['f04'] = date('Hi', strtotime($row->TimeIn));
        $data['default']['f05'] = date('Hi', strtotime($row->TimeOut));
        $data['default']['f06'] = $row->Note; //note

        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';
        $data['default']['readonly_f03'] = 'READONLY';
        $data['url_post'] = site_url('trx03/home/editpost');
        $this->load->view('trx03/form', $data);
    }

    function editpost() {
        
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');        
        $this->form_validation->set_rules('f03', 'Presence Date', 'required');        
        $this->form_validation->set_rules('f04', 'Time In', 'required');
        $this->form_validation->set_rules('f05', 'Time Out', 'required');
       
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->User;
            $f03 = $this->anti_xss($this->input->post('f03'));           
            $f04 = $this->input->post('f04');  $h1 = substr($f04, 0, 2); $m1 = substr($f04, 2, 2); $timein = $h1.':'.$m1;
            $f05 = $this->input->post('f05');  $h2 = substr($f05, 0, 2); $m2 = substr($f05, 2, 2); $timeout = $h2.':'.$m2;       
            $f06 = $this->input->post('f06');       
            
            $record = array(
                'IDEmployee' => $f01,
                'IncompleteDate' => date('Y-m-d', strtotime($f03)),
                'TimeIn' => $timein,
                'TimeOut' => $timeout,                              
                'Note' => TRIM($f06),
                'ConfirmFlag'   => "0",
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
                'controller' => site_url('trx02/home/addnew'),
                'activities' => 'edit new ' . $f01
            );
            $wh     = array("ID" => $id);
            $incomp     = $this->incomplete->get_incomplete($wh)->row();
            if($incomp->ConfirmFlag == '2'){
//                kirim email pemberitahuan ke atasan
                $prs    = $this->incomplete->get_personal($f01)->row();
                $ats    = $this->incomplete->get_prs_public($prs->IDEmployeeParent)->row();
                $sendto = $ats->InternalEmail;
                $subject= "TIS Notification - Incomplete";                
                $data['state']  = "confirm";
                $data['sendername'] = $prs->FullName;
                $data['receivername'] = $ats->FullName;
                $message= $this->load->view("trx03/email",$data,TRUE);
                $this->sendmail->internalmail($sendto, $subject, $message);
                $eksmail    = explode(",", $ats->ExternalEmail);
                if ($this->sendmail->externalmail($eksmail, $subject, $message)){
//                        echo $ex." => berhasil";
                }

            }
            $this->incomplete->update($id,$record);
            $this->logs->insert($recordlog);
            $mesg = 'update data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
	    $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
            $err_f06 = '';
            
         
        } else {
            $mesg = 'update data, failed';
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

        $this->incomplete->update($id, $record);
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
        $objSheet->setTitle('incomplete report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:G1')->getFont()->setBold(true)->setSize(12);

        // write header
        
        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');
        $objSheet->getCell('C1')->setValue('Incomplete Date');
        $objSheet->getCell('D1')->setValue('Confirm Data');
        $objSheet->getCell('E1')->setValue('TimeIn');
        $objSheet->getCell('F1')->setValue('TimeOut');        
        $objSheet->getCell('G1')->setValue('Note');
        
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->incomplete->get_all_by_period($f, $u,$this->User);
        if ($result != NULL) {   
            $i = 1;
            foreach ($result as $row) {
                $i++;
                
                
                $objSheet->getCell('A' . $i)->setValue("'" . $row['IDEmployee']);
                $objSheet->getCell('B' . $i)->setValue($row['FullName']);
                $objSheet->getCell('C' . $i)->setValue($row['IncompleteDate']);
                $objSheet->getCell('D' . $i)->setValue($row['ConfirmData']);
                $objSheet->getCell('E' . $i)->setValue($row['TimeIn']);
                $objSheet->getCell('F' . $i)->setValue($row['TimeOut']);                
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
            $objWriter->save($path_file . "incomplete" . $ext);
            $data = file_get_contents($path_file . "incomplete" . $ext);
            force_download("incomplete" . $ext, $data);
        }
    }
   function iframe($id){      
      $data['url_form'] = site_url('trx03/home/formatl/'.$id); 
      $this->load->view('trx03/iframe',$data);  
      
     }    
  function formatl($id){
     $this->load->library('mpdf54/mpdf');   
     $this->mpdf = new mPDF('c', array(216, 304), '12', 'dejavusans', 5, 5, 5, 5, 0, 0);      
     $row = $this->incomplete->get_by_id_request($id);   
     $day = date('w',strtotime($row->IDate));	
     $data['day'] = $this->incomplete->hari($day);	   
     $data['incompletedate']=date('d-m-Y',strtotime($row->IDate));
     $data['departement']=$row->Dept;
     $data['name']=$row->Name;
     $data['position']=$row->Position;
     $data['timein']=$row->TIN;
     $data['timeout']=$row->TOT;
     $data['note']=$row->Note;
     $data['name']=$row->Name;
     $data['parent']=$row->ParentName;  
     
     $html = $this->load->view('trx03/formatl',$data,true);  
     $this->mpdf->SetHTMLFooter('
              <table width="100%" style="vertical-align: bottom; font-family: serif; font-size: 8pt; color: #000000; font-weight: bold; font-style: italic;"><tr>
              <td width="33%"><span style="font-weight: bold; font-style: italic;">{DATE d-m-Y}</span></td>
              <td width="33%" align="center" style="font-weight: bold; font-style: italic;">{PAGENO}/{nbpg}</td>
              <td width="33%" style="text-align: right; "></td>
              </tr></table>
              ');
       $this->output->set_output($html);
       $this->mpdf->WriteHTML($html);
        // $this->mpdf->WriteHTML('<pagebreak sheet-size=210 297;/>');
        set_time_limit(60);
       $this->mpdf->Output('formatl.pdf', 'I');

    }
    function notification(){
        $notfor     = $this->input->post("notfor");
//        $notfor     = "man";
//echo $notfor;
        $data['notfor'] = $notfor;
        if ($notfor == "man"){
            $where      = "ConfirmFlag = '0' AND DeleteFlag = 'A' AND IDEmployee IN (SELECT triasnet_employee.m01personal.IDEmployee FROM triasnet_employee.m01personal WHERE triasnet_employee.m01personal.IDEmployeeParent = '$this->User')";
            $data['incompletes']   = $this->incomplete->get_incomplete($where)->result();
        }
        if ($notfor == "hrd"){
            $where      = "ConfirmFlag = '1' AND CHRDFlag = '0' AND DeleteFlag = 'A' ";
            $data['incompletes']   = $this->incomplete->get_incomplete($where)->result();
        }
//print_r($data['incompletes']);
        $this->load->view("trx03/notifications",$data);
    }
    function captcha(){
        $data['notfor']         = $this->input->post('notfor');
        $data['dticp']         = $this->input->post('dticp');
        $data['idincomplete']   = $this->input->post('idincomplete');
        $this->load->view("trx03/confirm",$data);
    }
    function confirm(){
        $idincomplete  = $this->input->post('idincomplete');
        $stat       = $this->input->post('stat');
        $reason     = $this->input->post('reason');
        $notfor     = $this->input->post('notfor');
//        if($notfor == "man"){
            $record     = array("ConfirmFlag" => $stat, "ConfirmDate" => date('Y-m-d H:i:s'), "ConfirmIP" => $this->input->ip_address(), "ConfirmBy" => $this->User, "RejectReason" => $reason);
//        }
//        if($notfor == "hrd"){
//            $record     = array("CHRDFlag" => $stat, "CHRDDate" => date('Y-m-d H:i:s'), "CHRDIP" => $this->input->ip_address(), "CHRDID" => $this->User, "RejectReason" => $reason);
//        }
        $where      = array("ID" => $idincomplete);
        $incomp = $this->incomplete->get_incomplete($where)->row();
//                kirim email pemberitahuan ke pengaju            
        $prs    = $this->incomplete->get_prs_public($incomp->IDEmployee)->row();
//        if (($notfor == "man" and $stat == '2') or $notfor == 'hrd'){
            $data['state']  = "status";
            $data['confirm']  = $stat;
            $sendto = $prs->InternalEmail;
            $subject= "TIS Notification - Incomplete";
            $data['receivername']   = $prs->FullName;
            $data['incomp']           = $incomp;
            $data['notfor']           = $notfor;
            $message = $this->load->view("trx03/email",$data,TRUE);
            $this->sendmail->internalmail($sendto, $subject, $message);
            $eksmail    = explode(",", $prs->ExternalEmail);
            $this->sendmail->externalmail($eksmail, $subject, $message);
//        }
//        if ($notfor == "man" and $stat == '1'){
//            $subject= "TIS Notification - Incomplete";                
//            $data['state']  = "confirm";
//            $data['for']  = "hrd";
//            $whhrd  = "IDParam = 'IDHRD' OR IDParam = 'IDHRDMGR' ";
//            $hrd    = $this->incomplete->get_param($whhrd)->result();
//            foreach ($hrd as $h){
//                $hrdnya = $this->incomplete->get_prs_public($h->ParamValue)->row();
//                print_r($hrdnya);
//                $sendto = $hrdnya->InternalEmail;
//                
//                $data['sendername'] = $prs->FullName;
//                $data['receivername'] = $hrdnya->FullName;
//                $message= $this->load->view("trx03/email",$data,TRUE);
//                $this->sendlmail($sendto, $subject, $message);
//                echo "terkirim ke ".$hrdnya->FullName." ".$hrdnya->InternalEmail;
//                $eksmail    = explode(",", $hrdnya->ExternalEmail);
//                if ($this->sendpmail($eksmail, $subject, $message)){
//                            echo "eksternal => berhasil";
//                }
//            }
//        }
        $this->incomplete->update_incomplete($where,$record);
        
//        $msg        = array("status" => "");
    }


     function cek_fn(){
        $whhrd  = "IDParam = 'IDHRD' OR IDParam = 'IDHRDMGR' ";
        $hrd = $this->incomplete->get_param($whhrd)->result();
        print_r($hrd);
    }
}


