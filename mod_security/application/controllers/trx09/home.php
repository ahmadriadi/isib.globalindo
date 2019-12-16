<?php

//USER REPORT/REQUEST
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        date_default_timezone_set("Asia/Jakarta");
        $this->load->model('rootcause_model', 'rootcause');
		$this->load->model('historytable_model', 'historytable');
        $this->load->model('logs_model', 'logs');
        $this->load->model('libraryfunction_model', 'libfun');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }


   function reportdata(){       
       $result =  $this->rootcause->getall_data();
       if($result !=='empty'){          
        $data['resultdata'] = $result;  
        $this->load->view('trx09/report',$data); 
       }
        
    }

    function get_accepted() {
        $accepted = $this->rootcause->get_accepted($this->User);
        echo $accepted;
    }

    function anti_xss($source) {
        $f = stripslashes(strip_tags(htmlspecialchars($source, ENT_QUOTES)));
        return $f;
    }

    function index() {
        $fromd 	= $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');
        $check1 = ($fromd == '' or $fromd == null) ? 'empty' : 'exist';
        $check2 = ($untild == '' or $untild == null) ? 'empty' : 'exist';
        $whparam= "IDParam = 'ITOFCR' OR IDParam = 'ITMGR'";
        //echo $whparam;
		$dsysdev	= $this->rootcause->get_param($whparam);
		$sysdev	= array();
		foreach($dsysdev->result() as $sd){
			$sysdev[] = $sd->ParamValue;
		}
		//print_r($sysdev);
		$cekparam	= in_array($this->User,$sysdev);
        if ($check1 == 'empty' and $check2 == 'empty') {
			if ($cekparam == 1){
				$date = $this->libfun->mincurday();
				$fromdate = substr($date, 0, 10);
				$untildate = substr($date, 11, 10);				
			}else{
				$fromdate	= date("Y-m-01");
				$untildate	= date("Y-m-d");
			}
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

        $this->load->view('trx09/home', $data);
    }
    
    
    function home_detail($idh){
        $data['idh'] = $idh;
        $data['condition'] = 'display';
        $this->load->view('trx09/home_detail', $data);
    }

   function getdatatable() {
       $f = date('Y-m-d',  strtotime($this->session->userdata('datefrom')));
       $u =  date('Y-m-d',  strtotime($this->session->userdata('dateuntil')));
      
       $rowchild = $this->rootcause->get_child($this->User)->result_array();
       $rowpersonal = $this->rootcause->getpersonal($this->User);	
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

       if($rowpersonal->IDStructure=='19' or $rowpersonal->IDStructure=='17'){
	/*	
	 if($rowpersonal->IDStructure=='19'){
	   echo $this->rootcause->getalldata($f,$u);
	}else if($rowpersonal->IDStructure=='17' and $this->User=='0249230309'){
	   echo $this->rootcause->getalldata($f,$u);
	}	
	*/
	echo $this->rootcause->getalldata($f,$u);
       }else{
	echo $this->rootcause->getdata_user($f,$u,$user);
       }	

    }
    
    
    function getdatatable_user($idh){
      echo $this->rootcause->datarequest_user($idh);
    }
    function getdatatable_createuser($idh){
      echo $this->rootcause->datarequest_createuser($idh);
    }
    function getdatatable_software($idh){
      echo $this->rootcause->datarequest_software($idh);
    }
    function getdatatable_createfolder($idh){
      echo $this->rootcause->datarequest_createfolder($idh);
    }
    function getdatatable_accessfolder($idh){
      echo $this->rootcause->datarequest_accessfolder($idh);
    }
    
    function getdatatable_agreement($idh){
      echo $this->rootcause->datarequest_agreement($idh);
    }

    function getstatus() {
        $id = $this->input->post('id');
        $rowh = $this->rootcause->getby_id_root($id);
        $flag = $rowh->StatusProblem;
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

    function addnew() {
        $query = $this->rootcause->getrootcause();
        $i = 0;
        foreach ($query as $r) {
            $data['default']['f01'][-1]['value'] = NULL;
            $data['default']['f01'][-1]['display'] = '-Please Select-';
            $data['default']['f01'][$i]['value'] = $r['IDRoot'];
            $data['default']['f01'][$i]['display'] = $r['RootName'];
            $i++;
        }
        $data['default']['f02'] = ''; //ComplainNote

        $location = $this->rootcause->get_location($this->User);
        $data['default']['f03'][1]['value']   = "1";
        $data['default']['f03'][1]['display'] = "Kapuk";        
        $data['default']['f03'][$location]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value']   = "2";
        $data['default']['f03'][2]['display'] = "Bitung";	
       
     
        $data['flag'] = 'add';
        $data['url_post'] = site_url('trx09/home/addpost');
        $this->load->view('trx09/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f02', 'Complain Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->input->post('f02');
	    $f03 = $this->input->post('f03');

            $cari = array('"',"'");
            $ganti= array("'","`");
            $f02	= str_replace($cari,$ganti,$f02);
            $hodconf    = $f01 == '9' ? '0' : '2';
            $record = array(
                'IDRoot' => $f01,
                'ComplainNote' => $f02,
                'ComplainDate' => date('Y-m-d'),	
		'IDLocation' => $f03,
                'TypeProblem' => '0',
                'AddedBy' => $this->User,
                'AddedDate' => $this->Datetime,
                'AddedIP' => $this->Ip,
                'HoDConf'   => $hodconf
            );

            $recordlog = array(
                'ID' => NULL,
                'username' => $this->User,
                'log_date' => $this->Datetime,
                'log_ip' => $this->Ip,
                'log_agent' => $this->Browser,
                'controller' => site_url('trx09/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $this->rootcause->insert_root($record);
            $this->logs->insert($recordlog);
            $mesg = 'insert data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            
            //jika request tidak langsung kirim email harus di 
            //tentukan pengajuannya apa
            if($f01 !=='9'){
            // kirim email pemberitahuan ke atasan


	    $rowpersonal = $this->rootcause->getpersonal($this->User);  
            $emailuser   = $rowpersonal->EmailInternal;    
            $deptuser   = $rowpersonal->Departement;    
            $name   = $rowpersonal->FullName;    
            $nohp   = $rowpersonal->NoHp;    
                			

            $prs = $this->rootcause->get_personal($this->User)->row();
            $ats = $this->rootcause->get_prs_public($prs->IDEmployeeParent)->row();
            $ccin = $ats->InternalEmail;
            $ccex = explode(",", $ats->ExternalEmail);
            $subject = "TIS Notification - Contact IT";
            
	    $data['name']= $name;  
            $data['emailuser']= $emailuser;  
            $data['deptuser']= $deptuser;  
            $data['nohp']= $nohp;  
            $data['note']= $f02;  
            
            
            $resultsendmail = $this->rootcause->sendtomail($f03);
            $tointernal = array();
            $toexternal = array();

                foreach ($resultsendmail as $rowmail) {
                           $rowmailsend = $this->rootcause->get_personal($rowmail['IDEmployee'])->row();
                           $tointernal[] = $rowmailsend->EmailInternal;
                           $externalexplode = explode(',', $rowmailsend->EmailExternal);
                           $toexternal[] = $externalexplode[0];
                          
                }
                
                $message = $this->load->view("trx09/email", $data, TRUE);
                $this->sendmail->internalmail($tointernal, $subject, $message);//cc dihapus, hanya request kirim cc ke atasan
                //$this->sendmail->externalmail($toexternal, $subject, $message);//cc dihapus, hanya request kirim cc ke atasan
           
        }
            
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
        }
        
        $counter = $this->rootcause->getlast_id($this->User);
        $json = '{ "mesg":"' . $mesg . '", 
                           "valid":"' . $valid . '", 
                           "counterdata":"' . $counter . '",
                           "idroot":"' . $f01 . '",
                           "err_f01":"' . $err_f01 . '",
                           "err_f02":"' . $err_f02 . '"' .
                '}';
        echo $json;
                
    }
    
    
    function tabmenu($idh,$condition){
       $data['idh'] = $idh;
       $data['condition'] = $condition;
       $this->load->view('trx09/tabdata',$data);
        
    }
    
    function adduser($idh){      
     $rowcause = $this->rootcause->getby_id_root($idh); 
     $row =  $this->rootcause->getpersonal($rowcause->AddedBy); 
     $data['default']['f01r'] =$row->FullName;
     $data['default']['f02r'] = $row->Departement;
     $data['default']['f03r'] = '';
     $data['default']['f04r'] = 'FR-PD-PP-07.02';
     $data['default']['f05r'] = $idh;
     $data['default']['f06r'] =date('Y-m-d',  strtotime($rowcause->AddedDate));
    
     $data['idh'] = $idh;
     $data['buttonsave'] ='save_user';
     $data['buttoncancel'] ='cancel_user';
     
    $data['default']['checkuser']['disabled'] = '';
    $data['default']['checkuser']['value'] = '1';
    $data['default']['checkuser']['display'] = 'Send data User to IT';
     
     $data['url_post'] = site_url('trx09/home/adduser_post');
     $this->load->view('trx09/user',$data);        
    }
    
    function addcrateuser($idh){
    $data['default']['f07r'][1]['value'] = "1";
    $data['default']['f07r'][1]['display'] = "Create";
    $data['default']['f07r'][1]['checked'] = "CHECKED";
    $data['default']['f07r'][2]['value'] = "0";
    $data['default']['f07r'][2]['display'] = "Ban";    
        
    $data['default']['f08r'] = '';   
    $data['default']['f09r'] = '';  
    $data['default']['f10r'] = '';  
    
    $data['default']['f11r'][1]['value'] = "0";
    $data['default']['f11r'][1]['display'] = "No";
    $data['default']['f11r'][1]['checked'] = "CHECKED";
    $data['default']['f11r'][2]['value'] = "1";
    $data['default']['f11r'][2]['display'] = "Yes";
    
    
    $data['default']['checkcuser']['disabled'] = '';
    $data['default']['checkcuser']['value'] = '1';
    $data['default']['checkcuser']['display'] = 'Send data Create User to IT';
    
    $data['idh'] = $idh;
    $data['buttonsave'] ='save_createuser';
    $data['buttoncancel'] ='cancel_createuser';
    $data['url_post'] = site_url('trx09/home/addcrateuser_post');
    $this->load->view('trx09/createuser',$data);      
        
    }    
    
    
   function addinstallsoftware($idh){
        $data['default']['f12r'] = '';   

        $data['default']['f13r'][1]['value'] = "1";
        $data['default']['f13r'][1]['display'] = "Install";
        $data['default']['f13r'][1]['checked'] = "CHECKED";
        $data['default']['f13r'][2]['value'] = "0";
        $data['default']['f13r'][2]['display'] = "Uninstall";

        $data['idh'] = $idh;
        
         $data['default']['checksoftware']['disabled'] = '';
         $data['default']['checksoftware']['value'] = '1';
         $data['default']['checksoftware']['display'] = 'Send data Software to IT';
        
        $data['buttonsave'] ='save_software';
        $data['buttoncancel'] ='cancel_software';
        $data['url_post'] = site_url('trx09/home/addinstallsoftware_post');
        $this->load->view('trx09/installsoftware',$data);      
        
    }
    
    function addcreatefolder($idh){
        $data['default']['f14r'] = '';   

        $data['default']['f15r'][1]['value'] = "1";
        $data['default']['f15r'][1]['display'] = "Create";
        $data['default']['f15r'][1]['checked'] = "CHECKED";
        $data['default']['f15r'][2]['value'] = "0";
        $data['default']['f15r'][2]['display'] = "Delete";

        $data['idh'] = $idh;
        
        $data['default']['checkcfolder']['disabled'] = '';
        $data['default']['checkcfolder']['value'] = '1';
        $data['default']['checkcfolder']['display'] = 'Send data Create Folder to IT';
        
        $data['buttonsave'] ='save_createfolder';
        $data['buttoncancel'] ='save_createfolder';
        $data['url_post'] = site_url('trx09/home/addcreatefolder_post');
        $this->load->view('trx09/createfolder',$data);       
        
    }
    
     function addaccessfolder($idh){
        $data['default']['f15r'] = '';   
        $data['default']['f17r'][1]['value'] = "1";
        $data['default']['f17r'][1]['display'] = "R/O";
        $data['default']['f17r'][1]['checked'] = "CHECKED";
        $data['default']['f17r'][2]['value'] = "2";
        $data['default']['f17r'][2]['display'] = "R/W";
        $data['default']['f17r'][3]['value'] = "0";
        $data['default']['f17r'][3]['display'] = "N/A";

        $data['idh'] = $idh;
        
        $data['default']['checkafolder']['disabled'] = '';
        $data['default']['checkafolder']['value'] = '1';
        $data['default']['checkafolder']['display'] = 'Send data Access Folder to IT';
        
        $data['buttonsave'] ='save_accessfolder';
        $data['buttoncancel'] ='save_accessfolder';
        $data['url_post'] = site_url('trx09/home/addaccessfolder_post');
        $this->load->view('trx09/accessfolder',$data);       
        
    }
    
    
     function addagreement($idh){
        $rowcause = $this->rootcause->getby_id_root($idh); 
         
        $data['default']['f18r'] = 'Atasan dan user bertanggung jawab penuh atas akibat yang timbul karena penyalahgunaan fasilitas oleh user yang bersangkutan';   
        $data['default']['f19r'][1]['value'] = "1";
        $data['default']['f19r'][1]['display'] = "Yes";
        $data['default']['f19r'][1]['checked'] = "CHECKED";
        $data['default']['f19r'][2]['value'] = "0";
        $data['default']['f19r'][2]['display'] = "No";
     
        $data['idh'] = $idh;
        $data['nip'] = $rowcause->AddedBy;
        $data['buttonsave'] ='save_agreement';
        $data['buttoncancel'] ='cancel_agreement';
        $data['url_post'] = site_url('trx09/home/addagreement_post');
        $this->load->view('trx09/agrement',$data);       
        
    }    
      function addagreement_post() {
        $this->form_validation->set_rules('f18', 'Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $idh = $this->input->post('idh');
            $nip = $this->input->post('nip');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
            $f03 = $this->anti_xss($this->input->post('f03'));
            $f04 = $this->anti_xss($this->input->post('f04'));
            $f05 = $this->anti_xss($this->input->post('f05'));
            $f06 = $this->anti_xss($this->input->post('f06'));
            $f07 = $this->anti_xss($this->input->post('f07'));
            $f08 = $this->anti_xss($this->input->post('f08'));
            $f09 = $this->anti_xss($this->input->post('f09'));
            $f10 = $this->anti_xss($this->input->post('f10'));
            $f11 = $this->anti_xss($this->input->post('f11'));
            $f12 = $this->anti_xss($this->input->post('f12'));
            $f13 = $this->anti_xss($this->input->post('f13'));
            $f14 = $this->anti_xss($this->input->post('f14'));
            $f15 = $this->anti_xss($this->input->post('f15'));
            $f16 = $this->anti_xss($this->input->post('f16'));
            $f17 = $this->anti_xss($this->input->post('f17'));
            $f18 = $this->anti_xss($this->input->post('f18'));
            $f19 = $this->anti_xss($this->input->post('f19'));
            $c1 = ($this->anti_xss($this->input->post('c1'))=='')?'empty':$this->anti_xss($this->input->post('c1'));
            $c2 = ($this->anti_xss($this->input->post('c2'))=='')?'empty':$this->anti_xss($this->input->post('c2'));
            $c3 = ($this->anti_xss($this->input->post('c3'))=='')?'empty':$this->anti_xss($this->input->post('c3'));
            $c4 = ($this->anti_xss($this->input->post('c4'))=='')?'empty':$this->anti_xss($this->input->post('c4'));
            $c5 = ($this->anti_xss($this->input->post('c5'))=='')?'empty':$this->anti_xss($this->input->post('c5'));
          
            
            $recorduser = array(
                'IDEmployee' => $nip,
                'ComputerName' => $f03,
                'NoCounter' => $idh,
                'CurDate' => date('Y-m-d'),              
                'FlagSend' => $c1,              
            );
            
                      
            
            $recordcreateuser = array(
              "IDEmployee"=> $nip, 
              "CounterReq"=> $idh, 
              "StatusUser"=> $f07, 
              "UserID"=> $f08, 
              "InternalEmail"=> $f09, 
              "ExternalEmail"=> $f10, 
              "InternetStatus"=> $f11,
              'FlagSend' => $c2, 
            );

            
            $recordinstall = array(
                "IDEmployee"=>$nip,
                 "CounterReq"=> $idh, 
                 "SoftwareName"=>$f12,
                 "SoftwareStatus"=>$f13,
                 'FlagSend' => $c3, 
            );
            
            $recordcreatefolder = array(
                 "IDEmployee"=>$nip,
                 "CounterReq"=> $idh, 
                 "FolderName"=> $f14, 
                 "FolderStatus"=> $f15,
                 'FlagSend' => $c4, 
            );
            
            
            $recordaccessfolder = array(
                 "IDEmployee"=>$nip,
                 "CounterReq"=> $idh, 
                 "FolderAccess"=> $f16, 
                 "AccessStatus"=> $f17,
                 'FlagSend' => $c5, 
            );
            
            
            $recordagreement = array(
                 "IDEmployee"=>$nip,
                 "CounterReq"=> $idh, 
                 "StatusAgreement"=> $f19,                 
            );
            
           if($c1 !=='empty'){
                $this->rootcause->insert_user($recorduser);
            } 
            
            if($c2 !=='empty'){
                 $this->rootcause->insert_createuser($recordcreateuser);
            }
            
            if($c3 !=='empty'){
                  $this->rootcause->insert_install($recordinstall);
            }

            if($c4 !=='empty'){
                $this->rootcause->insert_createfolder($recordcreatefolder);
            }
            
            if($c5 !=='empty'){
                 $this->rootcause->insert_accessfolder($recordaccessfolder);
            }
            
            if($f19 !=='0'){
                $this->rootcause->insert_agreement($recordagreement);
                $alert ='Insert Data. Success';
                $status='true';
            }else{
                $alert ='Insert Data. Failed';
                $status='false';
            }
            
            
            if($status=='true'){
               $this->reportrequest($idh,$nip);
            }
            
            
            $mesg = $alert;
            $valid = $status;
            $err_f01 = '';
            $err_f02 = '';
       
            
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
        }
        
        $json = '{ "mesg":"' . $mesg . '", 
                    "valid":"' . $valid . '"'.
                '}';
        echo $json;
                
    }
    
  
    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->rootcause->getby_id_root($id);

        $query = $this->rootcause->getrootcause();
        $i = 0;
        foreach ($query as $r) {
            $data['default']['f01'][$i]['value'] = $r['IDRoot'];
            $data['default']['f01'][$i]['display'] = $r['RootName'];
            if ($row->IDRoot == $r['IDRoot']) {
                $data['default']['f01'][$i]['selected'] = 'SELECTED';
            }
            $i++;
        }
        
        $data['default']['f02'] = $row->ComplainNote; //idemployee


	//IDLocation
        $data['default']['f03'][1]['value']   = "1";
        $data['default']['f03'][1]['display'] = "Kapuk";
        $data['default']['f03'][1]['checked'] = "CHECKED";
        $data['default']['f03'][2]['value']   = "2";
        $data['default']['f03'][2]['display'] = "Bitung";

        if ($row->IDLocation == '1') {
            $data['default']['f03'][1]['checked'] = "CHECKED";
        } else if ($row->IDLocation == '2') {
            $data['default']['f03'][2]['checked'] = "CHECKED";
        }
        
        //TypeProblem
        /*
        $data['default']['f03'][1]['value'] = "1";
        $data['default']['f03'][1]['display'] = "Request";
        $data['default']['f03'][2]['value'] = "0";
        $data['default']['f03'][2]['display'] = "Complain";
        if ($row->TypeProblem == '1') {
            $data['default']['f03'][1]['checked'] = "CHECKED";
        } else if ($row->TypeProblem == '2') {
            $data['default']['f03'][2]['checked'] = "CHECKED";
        }
         * 
         */
        
        $data['flag'] = 'edit'; 
        $data['url_post'] = site_url('trx09/home/editpost');
        $this->load->view('trx09/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'Complain Note', 'required');
        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f01 = $this->anti_xss($this->input->post('f01'));
            $f02 = $this->anti_xss($this->input->post('f02'));
	    $f03 = $this->anti_xss($this->input->post('f03'));
            $cari = array('"',"'");
            $ganti= array("'","`");
            $f02	= str_replace($cari,$ganti,$f02);
            $record = array(
                'IDRoot' => $f01,
                'ComplainNote' => $f02,
		'IDLocation' => $f03,	
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
                'controller' => site_url('trx09/home/addnew'),
                'activities' => 'edit new ' . $f02
            );
            
            $incomp = $this->rootcause->getby_id_root($id);
            if ($incomp->StatusProblem == '2' or $incomp->StatusProblem == '3' and $f01 !=='9') {
//                kirim email pemberitahuan ke atasan


		 $rowpersonal = $this->rootcause->getpersonal($this->User);  
		 $emailuser   = $rowpersonal->EmailInternal;    
		 $deptuser   = $rowpersonal->Departement;    
		 $name   = $rowpersonal->FullName;    
		 $nohp   = $rowpersonal->NoHp;    
                			
                $prs = $this->rootcause->get_personal($this->User)->row();
                $ats = $this->rootcause->get_prs_public($prs->IDEmployeeParent)->row();
                $ccin = $ats->InternalEmail;
                $ccex = explode(",", $ats->ExternalEmail);
                $subject = "TIS Notification - Contact IT";
                
                $data['name']= $name;  
            	$data['emailuser']= $emailuser;  
            	$data['deptuser']= $deptuser;  
            	$data['nohp']= $nohp;  
            	$data['note']= $f02;  
                
                $resultsendmail = $this->rootcause->sendtomail($f03);
                $tointernal = array();
                $toexternal = array();

                foreach ($resultsendmail as $rowmail) {
                           $rowmailsend = $this->rootcause->get_personal($rowmail['IDEmployee'])->row();
                           $tointernal[] = $rowmailsend->EmailInternal;
                           $externalexplode = explode(',', $rowmailsend->EmailExternal);
                           $toexternal[] = $externalexplode[0];
                          
                }
                
                $message = $this->load->view("trx09/email", $data, TRUE);
                $this->sendmail->internalmail($tointernal, $subject, $message,$ccin);
               // $this->sendmail->externalmail($toexternal, $subject, $message,$ccex);
            }

	    $this->historydata($id);	
            $this->rootcause->update_root($id, $record);
            $this->logs->insert($recordlog);
            $mesg = 'update data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
        } else {
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
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

        $this->rootcause->update_root($id, $record);
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
        $objSheet->setTitle('rootcause report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:L1')->getFont()->setBold(true)->setSize(12);

        // write header

        $objSheet->getCell('A1')->setValue('No');
        $objSheet->getCell('B1')->setValue('ID');
        $objSheet->getCell('C1')->setValue('User');
        $objSheet->getCell('D1')->setValue('Root Cause');
        $objSheet->getCell('E1')->setValue('PIC');
        $objSheet->getCell('F1')->setValue('Complain or Request');
        $objSheet->getCell('G1')->setValue('Request Time');
        $objSheet->getCell('H1')->setValue('Problem');
        $objSheet->getCell('I1')->setValue('Status');
        $objSheet->getCell('J1')->setValue('Solution');
        $objSheet->getCell('K1')->setValue('Solution Time');
        $objSheet->getCell('L1')->setValue('Duration');

        $result =  $this->rootcause->getall_data();
        
        if ($result !=='empty') {
            $i = 1;
            $no= 0;
            foreach ($result as $row) {
               $i++;              
               $no++;
                if($row['StatusProblem'] =='0'){
                    $status = 'Waiting';                             
                }else if ($row['StatusProblem'] =='1'){
                    $status = 'Solved';  
                }else if($row['StatusProblem'] =='2'){
                    $status = 'Unsolved';  
                }else if($row['StatusProblem'] =='3'){
                     $status = 'In Progess';  
                }
                
                  $duration = ($this->libfun->selisihwaktu($row['AddedDate'],$row['SolutionDate']));
                
                 if($row['RootName']=='REQUEST'){  
                     
                    $objSheet->getCell('A' . $i)->setValue($no);
                    $objSheet->getCell('B' . $i)->setValue($row['ID']);
                    $objSheet->getCell('C' . $i)->setValue($row['diadd']);
                    $objSheet->getCell('D' . $i)->setValue($row['RootName']);
                    $objSheet->getCell('E' . $i)->setValue($row['picoleh']);
                    $objSheet->getCell('F' . $i)->setValue(strip_tags($row['ComplainNote']));
                    $objSheet->getCell('G' . $i)->setValue($row['AddedDate']);
                    $objSheet->getCell('H' . $i)->setValue($row['ProblemNote']);
                    $objSheet->getCell('I' . $i)->setValue($status);
                    $objSheet->getCell('J' . $i)->setValue($row['SolutionNote']);
                    $objSheet->getCell('K' . $i)->setValue(strip_tags($row['SolutionDate']));
                    $objSheet->getCell('L' . $i)->setValue($duration);
                    
                    $rowuser = $this->rootcause->getuser($row['ID']);    
                    if($rowuser !=='empty'){
                       $i = $i+1; 
                       $objSheet->getCell('C' . $i)->setValue('Computer Name : '.$rowuser->ComputerName);
                    }
                    $rowcreateuser = $this->rootcause->getcreateuser($row['ID']);
                    if($rowcreateuser !=='empty'){
                      $i = $i+1;
                      $userid = ($rowcreateuser->UserID =='undefined')?'-':$rowcreateuser->UserID;
                      $internal = ($rowcreateuser->InternalEmail =='undefined')?'-':$rowcreateuser->InternalEmail;
                      $eksternal = ($rowcreateuser->ExternalEmail =='undefined')?'-':$rowcreateuser->ExternalEmail;
                      
                      $objSheet->getCell('C' . $i)->setValue('Create User : '.$userid);
                      $objSheet->getCell('D' . $i)->setValue('Email Internal : '.$internal);
                      $objSheet->getCell('E' . $i)->setValue('Email Eksternal : '.$eksternal);
                      $objSheet->getCell('F' . $i)->setValue('Status User : '.($rowcreateuser->StatusUser=='1')?'Create':'Banned');
                      $objSheet->getCell('G' . $i)->setValue('Status Internet : '.($rowcreateuser->InternetStatus =='1')?'Internet Access':'No Internet');


                    }
                    
                     $rowinstall = $this->rootcause->getinstall($row['ID']); 
                     if($rowinstall !=='empty'){
                          $i = $i+1;
                         if($rowinstall->SoftwareName=='undefined'){             
                                $software = '-';  
                           }else{
                                $software = $rowinstall->SoftwareName;  
                           } 
                           
                             $objSheet->getCell('C' . $i)->setValue('Software Name : '.$software);
                             $objSheet->getCell('D' . $i)->setValue('Status : '.($rowinstall->SoftwareStatus=='1')?'Install':'Uninstall');
                         
                     }
                     
                      $rowcreatefolder = $this->rootcause->getcreatefolder($row['ID']); 
                      if($rowcreatefolder !=='empty'){                           
                          $i = $i+1;  
                          if($rowcreatefolder->FolderName =='undefined'){             
                                $foldername = '-';  
                           }else{
                                $foldername = $rowcreatefolder->FolderName;  
                           }  
                           $objSheet->getCell('C' . $i)->setValue('Create Folder : '.$foldername);
                           $objSheet->getCell('D' . $i)->setValue('Status : '.($rowcreatefolder->FolderStatus=='1')?'Create':'Delete');

                          
                      }
                      
                      $rowaccessfolder = $this->rootcause->getaccessfolder($row['ID']); 
                       if($rowaccessfolder !=='empty'){
                            $i = $i+1;
                           if($rowaccessfolder->AccessStatus=='0'){
                                    $accesstatus ='N/A';
                                }else if($rowaccessfolder->AccessStatus=='1'){
                                    $accesstatus ='R/O';
                               }else if($rowaccessfolder->AccessStatus=='2'){
                                    $accesstatus ='R/W';
                               }
                               
                           $folder = ($rowaccessfolder->FolderAccess =='undefined')?'-':$rowaccessfolder->FolderAccess;    
                           $objSheet->getCell('C' . $i)->setValue('Access Folder : '.$folder);
                           $objSheet->getCell('D' . $i)->setValue('Status : '.$accesstatus);   
                           
                       }
                       
                        $rowagreement = $this->rootcause->getagreement($row['ID']);
                        if($rowagreement !=='empty'){ 
                            $i = $i+1;
                            $useragree = ($rowagreement->StatusAgreement=='1')?'Accept':'Reject';                            
                            $objSheet->getCell('C' . $i)->setValue('User Agreement : '.$useragree);
                        }
                 }else{
                     
                    $objSheet->getCell('A' . $i)->setValue($no);
                    $objSheet->getCell('B' . $i)->setValue($row['ID']);
                    $objSheet->getCell('C' . $i)->setValue($row['diadd']);
                    $objSheet->getCell('D' . $i)->setValue($row['RootName']);
                    $objSheet->getCell('E' . $i)->setValue($row['picoleh']);
                    $objSheet->getCell('F' . $i)->setValue(strip_tags($row['ComplainNote']));
                    $objSheet->getCell('G' . $i)->setValue($row['AddedDate']);
                    $objSheet->getCell('H' . $i)->setValue($row['ProblemNote']);
                    $objSheet->getCell('I' . $i)->setValue($status);
                    $objSheet->getCell('J' . $i)->setValue($row['SolutionNote']);
                    $objSheet->getCell('K' . $i)->setValue(strip_tags($row['SolutionDate']));
                    $objSheet->getCell('L' . $i)->setValue($duration);
                     
                     
                 }
               
              
           
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
            $objWriter->save($path_file . "rootcause" . $ext);
            $data = file_get_contents($path_file . "rootcause" . $ext);
            force_download("rootcause" . $ext, $data);
        }
    }
    function notification() {
        $notfor = $this->input->post("notfor");
        $data['notfor'] = $notfor;
        if ($notfor == "sysdev") {
            $employee = "isib_employee.m01personal";
            $where = "StatusProblem = '2' AND DeleteFlag = 'A' AND 
                           IDEmployee IN (
                                            SELECT $employee.IDEmployee 
                                            FROM $employee 
                                            WHERE $employee.IDEmployeeParent = '$this->User'
                                          )";
            $data['rootcause'] = $this->rootcause->get_rootcause($where)->result();
        }

        $this->load->view("trx09/notifications", $data);
    }
    
    
   function reportrequest($idh,$nip){
       $rowheader = $this->rootcause->getby_id_root($idh);
       $rowuser = $this->rootcause->getuser($idh);        
       if($rowuser !=='empty'){
             $showuser ='show';
             $row =  $this->rootcause->getpersonal($rowuser->AddedBy); 
             $request = $row->FullName;
             $dept = $row->Departement;
             $computer = $rowuser->ComputerName;
             $rdate = $rowuser->AddedDate;
             $nprocess = $idh;
             $note = $rowuser->ComplainNote;
       }else{
             $showuser ='hidden';
             $request = '-';
             $dept = '-';
             $computer = '-';
             $rdate = '-';
             $nprocess = '-';
             $note ='-';
       }      
       
       $rowcreateuser = $this->rootcause->getcreateuser($idh); 
       
       if($rowcreateuser !=='empty'){
         
           $showcuser ='show';
           $userid = ($rowcreateuser->UserID =='undefined')?'-':$rowcreateuser->UserID;
           $mailinternal = ($rowcreateuser->InternalEmail =='undefined')?'-':$rowcreateuser->InternalEmail;
           $mailexternal = ($rowcreateuser->ExternalEmail =='undefined')?'-':$rowcreateuser->ExternalEmail;
           $statususer = $rowcreateuser->StatusUser;
           $internetstatus = $rowcreateuser->InternetStatus;
       }else{
           $showcuser ='hidden';
           $userid = '-';
           $mailinternal = '-';
           $mailexternal = '-';
           $statususer = '';
           $internetstatus = '-';
           
       }
       
       $rowinstall = $this->rootcause->getinstall($idh); 
       
       if($rowinstall !=='empty'){
           
          $showsoftware ='show';  
           
         $sof = $rowinstall->SoftwareName;  

         if($sof =='undefined'){             
              $software = '-';  
         }else{
              $software = $sof;  
         }
         
         $softwarestatus = $rowinstall->SoftwareStatus;  
       }else{
         $showsoftware ='hidden';    
           
         $software = '-';  
         $softwarestatus = '';    
           
       }
       
       $rowcreatefolder = $this->rootcause->getcreatefolder($idh); 
       
       if($rowcreatefolder !=='empty'){
           $showcfolder='show';   
           $fod = $rowcreatefolder->FolderName;
           $folderstatus = $rowcreatefolder->FolderStatus;
           
         if($fod =='undefined'){             
              $foldername = '-';  
         }else{
              $foldername = $fod;  
         }
         
       }else{
           $showcfolder='hidden'; 
           $foldername = '-';
           $folderstatus = '';
       }
       
       $rowaccessfolder = $this->rootcause->getaccessfolder($idh); 
       if($rowaccessfolder !=='empty'){
           $showafolder='show';
           $accessfolder = ($rowaccessfolder->FolderAccess =='undefined')?'-':$rowaccessfolder->FolderAccess;
           $accesstatus = $rowaccessfolder->AccessStatus;
       }else{
           $showafolder='hidden';
           $accessfolder = '-';
           $accesstatus = '';
       }
       
       $rowagreement = $this->rootcause->getagreement($idh); 
       
       if($rowagreement !=='empty'){
           $useragree = $rowagreement->StatusAgreement;
       }else{
           $useragree = '';
       }
       
        
       $data['default']['f01'] = $request; //Request by
       $data['default']['f02'] = 'FR-PD-PP-07.01'; //Departement
       $data['default']['f03'] = $dept; //Computer Name
       $data['default']['f04'] = $idh; //No Doc
       $data['default']['f05'] = $computer; //No Process
       $data['default']['f06'] = date('d-m-Y',strtotime($rdate)); //No Doc
       
       
       
       $data['default']['f07'] = $userid; //User ID
       
       //Status User
       $data['default']['f08'][1]['value'] = "1";
       $data['default']['f08'][1]['display'] = "Create";
       $data['default']['f08'][2]['value'] = "0";
       $data['default']['f08'][2]['display'] = "Ban";
      if ($statususer == '1') {
            $data['default']['f08'][1]['checked'] = "CHECKED";
       } else if ($statususer == '0') {
            $data['default']['f08'][2]['checked'] = "CHECKED";
      }
       
       
       $data['default']['f09'] = $mailinternal; //Internal Email
       $data['default']['f10'] = $mailexternal; //External Email
       
       //Internet Access
       $data['default']['f11'][1]['value'] = "1";
       $data['default']['f11'][1]['display'] = "Yes";
       $data['default']['f11'][2]['value'] = "0";
       $data['default']['f11'][2]['display'] = "No";
       if ($internetstatus == '1') {
            $data['default']['f11'][1]['checked'] = "CHECKED";
       } else if ($internetstatus == '0') {
            $data['default']['f11'][2]['checked'] = "CHECKED";
       }
       
       
       $data['default']['f12'] = $software; //Software
       
        //Install
       $data['default']['f13'][1]['value'] = "1";
       $data['default']['f13'][1]['display'] = "Install";
       $data['default']['f13'][2]['value'] = "0";
       $data['default']['f13'][2]['display'] = "Uninstall";
       if ($softwarestatus == '1') {
            $data['default']['f13'][1]['checked'] = "CHECKED";
       } else if ($softwarestatus == '0') {
            $data['default']['f13'][2]['checked'] = "CHECKED";
       }
       
       
       $data['default']['f14'] = $foldername; //Folder Name
       
        //Create Folder
       $data['default']['f15'][1]['value'] = "1";
       $data['default']['f15'][1]['display'] = "Create";
       $data['default']['f15'][2]['value'] = "0";
       $data['default']['f15'][2]['display'] = "Delete";
       if ($folderstatus == '1') {
            $data['default']['f15'][1]['checked'] = "CHECKED";
       } else if ($folderstatus == '0') {
            $data['default']['f15'][2]['checked'] = "CHECKED";
       }
      
       $data['default']['f16'] = $accessfolder; //Folder Access
       
        //Status Folder
       $data['default']['f17'][1]['value'] = "0";
       $data['default']['f17'][1]['display'] = "N/A";
       $data['default']['f17'][2]['value'] = "1";
       $data['default']['f17'][2]['display'] = "R/O";
       $data['default']['f17'][3]['value'] = "2";
       $data['default']['f17'][3]['display'] = "R/W";
       if ($folderstatus == '0') {
            $data['default']['f17'][1]['checked'] = "CHECKED";
       } else if ($folderstatus == '1') {
            $data['default']['f17'][2]['checked'] = "CHECKED";
       }else if($folderstatus == '2'){
            $data['default']['f17'][3]['checked'] = "CHECKED";
           
       }
       
        //Status Agreement
       $data['default']['f18'][1]['value'] = "1";
       $data['default']['f18'][1]['display'] = "Yes";
       $data['default']['f18'][2]['value'] = "0";
       $data['default']['f18'][2]['display'] = "No";
        if ($useragree == '1') {
            $data['default']['f18'][1]['checked'] = "CHECKED";
       } else if ($useragree == '0') {
            $data['default']['f18'][2]['checked'] = "CHECKED";
       }
       
       
       $data['requestby']=$request; 
       $data['departement']=$dept; 
       $data['computername']=$computer; 
       $data['docno']='FR-PD-PP-07.02'; 
       $data['noprocess']=$idh; 
       $data['daterequest']=$rdate; 
       $data['userid']=$userid; 
       $data['statususer']=$statususer; 
       $data['emailinternal']=$mailinternal; 
       $data['emaileksternal']=$mailexternal; 
       $data['internetaccess']=$internetstatus; 
       $data['softwarename']=$software; 
       $data['statussoftware']=$softwarestatus; 
       $data['foldername']=$foldername; 
       $data['statusfolder']=$folderstatus; 
       $data['folderaccess']=$accessfolder; 
       $data['statusaccess']=$accesstatus; 
       $data['statusagreement']=$useragree; 
       $data['note']=$note; 
       
       
       $data['showuser'] = $showuser;
       $data['showcuser'] = $showcuser;
       $data['showsoftware'] = $showsoftware;
       $data['showcfolder'] = $showcfolder;
       $data['showafolder'] = $showafolder;
       

	$rowpersonal = $this->rootcause->getpersonal($this->User);  
        $emailuser   = $rowpersonal->EmailInternal;    
	$deptuser   = $rowpersonal->Departement;    
	$name   = $rowpersonal->FullName;    
	$nohp   = $rowpersonal->NoHp;   


	$data['name']= $name;  
        $data['emailuser']= $emailuser;  
        $data['deptuser']= $deptuser;  
        $data['nohp']= $nohp;  


    // kirim email pemberitahuan ke atasan
       $prs = $this->rootcause->get_personal($nip)->row();
       $ats = $this->rootcause->get_prs_public($prs->IDEmployeeParent)->row();
       $ccin = $ats->InternalEmail;
       $ccex = explode(",", $ats->ExternalEmail);
       $subject = "TIS Notification - Contact IT";

      // $this->load->view("trx09/emailrequest", $data);
       $resultsendmail = $this->rootcause->sendtomail($rowheader->IDLocation);
       $tointernal = array();
       $toexternal = array();       
       foreach ($resultsendmail as $rowmail) {
                  $rowmailsend = $this->rootcause->get_personal($rowmail['IDEmployee'])->row();
                  $tointernal[] = $rowmailsend->EmailInternal;
                  $externalexplode = explode(',', $rowmailsend->EmailExternal);
                  $toexternal[] = $externalexplode[0];
                
       }
       $message = $this->load->view("trx09/emailrequest", $data, TRUE);
       $this->sendmail->internalmail($tointernal, $subject, $message,$ccin);
       //$this->sendmail->externalmail($toexternal, $subject, $message,$ccex);
            
       
    }


 function historydata($id){
         $row = $this->rootcause->getby_id_root($id);
         $record = array(
             "IDTable"=>$row->ID,
             "IDRoot"=>$row->IDRoot,
	     "IDLocation"=>$row->IDLocation,	
             "ComplainNote"=>$row->ComplainNote,
             "ComplainDate"=>$row->ComplainDate,
             "RootCause"=>$row->RootCause,
             "ProblemNote"=>$row->ProblemNote,
             "SolutionNote"=>$row->SolutionNote,
             "SolutionDate"=>$row->SolutionDate,
             "StatusProblem"=>$row->StatusProblem,
             "TypeProblem"=>$row->TypeProblem,
             "PIC"=>$row->PIC,
             "AddedBy"=>$row->AddedBy,
             "AddedDate"=>$row->AddedDate,
             "AddedIP"=>$row->AddedIP,
             "EditedBy"=>$row->EditedBy,
             "EditedDate"=>$row->EditedDate,
             "EditedIP"=>$row->EditedIP,
             "DeleteBy"=>$row->DeleteBy,
             "DeleteDate"=>$row->DeleteDate,
             "DeleteIP"=>$row->DeleteIP,
             "DeleteFlag"=>$row->DeleteFlag,
             "ViewFlag"=>$row->ViewFlag,
             "HoDConf"=>$row->HoDConf,
             "HoDConfDate"=>$row->HoDConfDate,
             "HodConfBy"=>$row->HodConfBy,
             "HodConfIP"=>$row->HodConfIP,
             "RejectNote"=>$row->RejectNote,
             "HistBy"=>$this->User,
             "HistDate"=>$this->Datetime,
             "HistIP"=>$this->Ip,
             
         );
         
         
         $this->historytable->insert_rootcause($record);
         
        
    }
  

}

