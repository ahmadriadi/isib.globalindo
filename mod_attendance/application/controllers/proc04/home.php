<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Processpresence_model', 'processpresence');    
	$this->load->model('uac_model', 'uac');   
        $this->load->model('libraryfunction_model', 'libfun'); 

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

   function index() {
        $date = $this->libfun->periode_work();
        $data['default']['f01'] = substr($date, 0, 10);
        $data['default']['f02'] = substr($date, 11, 10);
        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));

	$idmenu                    = "125";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('proc04/home', $data);
    }

   

    function postingpresence($from,$until) {
        $fromdate = date('Y-m-d', strtotime($from));
        $untildate = date('Y-m-d', strtotime($until));
        
         $this->create_worktime($fromdate, $untildate);
	  //echo '1. Create Worktime for Presence, Done'.'<br/>';

	  $this->holiday_emp($fromdate, $untildate);
	 //echo '2. Process Holiday to presence, Done'.'<br/>'; 
	
          $this->checkpicket($fromdate, $untildate);

         //update presence with status
         $this->proceed_incomplete($fromdate, $untildate);
	  //echo '3. Process Incomplete to presence, Done'.'<br/>';
         $this->proceed_officialtravel($fromdate, $untildate);
	  //echo '4. Process Official Travel to presence, Done'.'<br/>';
         $this->proceed_leavepermit($fromdate, $untildate);  
          //echo '5. Process Leavepermit to presence, Done'.'<br/>';    
         $this->proceed_leave_emp($fromdate, $untildate);
	  //echo '6. Process Leave to presence, Done'.'<br/>';   
         $this->maternity_emp();
	  //echo '7. Process Leave Maternity to presence, Done'.'<br/>'; 
                 
         $this->proceed_suspension($fromdate, $untildate);  
	 // echo '8. Process Suspension Employee to presence, Done'.'<br/>'; 
	 $this->proceed_leavework();
	 // echo '9. Process Permission Leave to Work Employee to presence, Done'.'<br/>';
	
         // echo 13	
               

        $mesg = "Process Update Data Presence,Sucess..";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                      "valid":"' . $valid . '"'
                .
                '}';

        echo $json;
    }
   
    function create_worktime($fromdate, $untildate) {
	 ini_set('memory_limit', '-1'); // for unlimited size 
        $resultpresence = $this->processpresence->getperiod_presence($fromdate, $untildate);
        $checkpresence = ($resultpresence == "" or $resultpresence == null) ? "empty" : "exist";
        if ($checkpresence == 'exist') {
            foreach ($resultpresence as $row) {
                $row_schedule = $this->processpresence->get_work_schedule($row['WorkDay']);

                if ($row['ManualIn'] != NULL) {
                    $actualin = date('H:i:s', strtotime($row['ManualIn']));
                    $actualout = date('H:i:s', strtotime($row['ManualOut']));
                } else {
                    $actualin = date('H:i:s', strtotime($row['ActualIn']));
                    $actualout = date('H:i:s', strtotime($row['ActualOut']));
                }

                if ($row['WorkDay'] != "OFF" && $row['WorkDay'] != "SUN") {
                    $workin = date('H:i:s', strtotime($row_schedule->TimeIn));
                    $workout = date('H:i:s', strtotime($row_schedule->TimeOut));

                    $workbreak = $row_schedule->BreakDuration;
                    $workhour = (strtotime($workout) - strtotime($workin)) / 3600 - $workbreak;

                    if ((strtotime($actualin) - strtotime($workin)) / 3600 > 0) {
                        $latehour = (strtotime($actualin) - strtotime($workin)) / 3600;
                    } else {
                        $latehour = 0;
                    }

                    if ((strtotime($actualout) - strtotime($workout)) / 3600 > 0) {
                        $ecxesshour = (strtotime($actualout) - strtotime($workout)) / 3600;
                    } else {
                        $ecxesshour = 0;
                    }
                } else {
                    if ($row['ManualIn'] != NULL || $row['ActualIn'] != NULL) {
                        if ($row['WorkDay'] == "SUN") {
                            $workin = date('H:i:s', strtotime("08:00:00"));
                            $workout = date('H:i:s', strtotime("16:00:00"));
                            $workbreak = 1;
                        } elseif ($row['WorkDay'] == "OFF" && $row['DayOfWeek'] == 5) {
                            $workin = date('H:i:s', strtotime("08:00:00"));
                            $workout = date('H:i:s', strtotime("16:30:00"));
                            $workbreak = 1.5;
                        } elseif ($row['WorkDay'] == "OFF" && $row['DayOfWeek'] == 6) {
                            $workin = date('H:i:s', strtotime("08:00:00"));
                            $workout = date('H:i:s', strtotime("13:00:00"));
                            $workbreak = 0;
                        }


                        $workhour = (strtotime($workout) - strtotime($workin)) / 3600 - $workbreak;

                        if ((strtotime($actualin) - strtotime($workin)) / 3600 > 0) {
                            $latehour = (strtotime($actualin) - strtotime($workin)) / 3600;
                        } else {
                            $latehour = 0;
                        }

                        if ((strtotime($actualout) - strtotime($workout)) / 3600 > 0) {
                            $ecxesshour = (strtotime($actualout) - strtotime($workout)) / 3600;
                        } else {
                            $ecxesshour = 0;
                        }
                    } else {
                        $workin = $row_schedule->TimeIn;
                        $workout = $row_schedule->TimeOut;
                        $workhour = NULL;
                        $latehour = 0;
                        $ecxesshour = 0;
                    }
                }



                if ($row['ManualHour'] > 0) {
                    $description = 'MP';
                } elseif ($row['ActualHour'] > 0) {
                    $description = 'P';
                } elseif ($row['ActualHour'] == 0 && $row['WorkHour'] > 0) {
                    if($row['Description'] !==NULL){
                       // $description = $row['Description'] ;
			$description = 'A';	
                    }else{
                        $description = 'A';
                    }

                    $manualIn = $row['ManualIn'];
                    $manualOut = $row['ManualOut'];

                    if ($manualIn !== null and $manualOut !== null) {
                        $totalhour = (strtotime($manualOut) - strtotime($manualIn) / 3600 );

                        if ($totalhour >= 4) {
                            $description = 'P';
                        } else {
                            $description = 'A';
                        }
                    }
                } else {
                    $description = NULL;
                }


                $id = $row['IDPresence'];
                $record = array(
                    'WorkIn' => $workin,
                    'WorkOut' => $workout,
                    'WorkHour' => $workhour,
                    'LateHour' => $latehour,
                    'ExcessHour' => $ecxesshour,
                    'Description' => $description
                );
                $this->processpresence->update_presence($id, $record);
            }
        }
    }

   

    function proceed_officialtravel($fromdate, $untildate) {
	 ini_set('memory_limit', '-1'); // for unlimited size 
        $result = $this->processpresence->get_officialtravel($fromdate, $untildate);
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == 'exist') {
            foreach ($result as $row) {
                $proceeddate = $row['OfficialTravelDate'];
                while ($proceeddate <= $row['UntilDate']) {
                    $record = array('Description' => 'OT');
                    $nip = $row['IDEmployee'];
                    $date = $proceeddate;
		    
		    $checkday =  $this->processpresence->get_workday($date,$nip);
                    if($checkday=='empty'){
			 $this->processpresence->update_presence_by_date($nip, $date, $record);
			
		    }	
	
                   
	 
                    $proceeddate = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                }
            }
        }
    }

    function proceed_leavepermit($fromdate, $untildate) {
	 ini_set('memory_limit', '-1'); // for unlimited size 	
        $result = $this->processpresence->get_leavepermit($fromdate, $untildate);
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == 'exist') {
            foreach ($result as $row) {
                $OutDate = $row['OutDate'];
                $InDate = $row['InDate'];
                $IMKHour = $row['IMKHour'];
                $Necessity = $row['Necessity'];
                $proceeddate = $row['LeavePermitDate'];

                $record = array(
                    'ManualOut' => $OutDate,
                    'IMKOut' => $OutDate,
                    'IMKIn' => $InDate,
                    'IMKHour' => $IMKHour,
                    'Description' => 'LP',
                    'Necessity' => $Necessity
                );
                $nip = $row['IDEmployee'];
                // $date = $proceeddate; di remark karena bu kasus bu juliani menginput outdate bukan pada tanggal leavepermit - 2014-05-23
                $date = date('Y-m-d',strtotime($OutDate));
                $this->processpresence->update_presence_by_date($nip, $date, $record);
            }
        }
    }

    function proceed_incomplete($fromdate, $untildate) {
	 ini_set('memory_limit', '-1'); // for unlimited size 
        $result = $this->processpresence->get_incomplete($fromdate, $untildate);
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == 'exist') {
            foreach ($result as $row) {
                $proceeddate = $row['IncompleteDate'];

                $dayofweek = date('w', strtotime($proceeddate));
                if ($this->processpresence->check_holiday_emp($proceeddate)) {
                    $workday = 'OFF';
                } else {
                    if ($dayofweek == 0)
                        $workday = 'SUN';
                    elseif ($dayofweek > 0 && $dayofweek < 5)
                        $workday = 'N1';
                    elseif ($dayofweek == 5)
                        $workday = 'N2';
                    elseif ($dayofweek == 6)
                        $workday = 'N3';
                }

                $row_schedule = $this->processpresence->get_work_schedule($workday);
                $breakduration = $row_schedule->BreakDuration;


                $timein = $row['IncompleteDate'] . ' ' . $row['TimeIn'];
                if ($row['TimeIn'] > $row['TimeOut']) {
                    $dateafter = date('Y-m-d', strtotime("+1 day", strtotime($proceeddate)));
                    $timeout = $dateafter . ' ' . $row['TimeOut'];
                } else {
                    $timeout = $proceeddate . ' ' . $row['TimeOut'];
                }

                $manualhour = (strtotime($timeout) - strtotime($timein)) / 3600 - $breakduration;

                $record = array('Description' => 'NC',
                    'ManualIn' => $timein,
                    'ManualOut' => $timeout,
                    'ManualHour' => $manualhour);
                $nip = $row['IDEmployee'];
                $date = $proceeddate;

                $this->processpresence->update_presence_by_date($nip, $date, $record);
            }
        }
    }

    
     function proceed_leave_emp($fromdate, $untildate) {
	 ini_set('memory_limit', '-1'); // for unlimited size 
        $result = $this->processpresence->get_leave_emp($fromdate, $untildate);
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == 'exist') {
            foreach ($result as $row) {
                $proceeddate = $row['TglCutiDari'];   
                while ($proceeddate <= $row['TglCutiSampai']) { 
                    
                    $Typeleave = $row['Jenis'];  
                    $letter = $row['SickLetter'];   
		    $flag = $row['FlagInput'];
                   
                   if($Typeleave =='SL' and $letter=='1'){
                        $leave = 'LSN';
                   }else if($Typeleave =='SL' and $letter=='0'){
                        $leave = 'SN';
                   }else if($Typeleave !=='SL'){
                        $leave = $Typeleave;
                   }
                     //echo $row['IDEmployee']."---------".$leave.'<br/>';                      
                    $record = array('Description' => $leave);
                    $nip = $row['IDEmployee'];
                    $date = $proceeddate;

		    $checkday =  $this->processpresence->get_workday_leave($date,$nip);                    
                    if(($checkday=='empty') ){                       
                          $this->processpresence->update_presence_by_date($nip, $date, $record);   
                    }else{
                        if($flag =='sys'){
                            if($Typeleave !=='AL'){
                                $this->processpresence->update_presence_by_date($nip, $date, $record);  
                            }
                          
                        }
                        
                    }   
                    
                    

                    $proceeddate = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                }
            }
        }
    }


       function maternity_emp() {
	 ini_set('memory_limit', '-1'); // for unlimited size 
        $result = $this->processpresence->get_maternity_emp('MTL');
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == 'exist') {
            foreach ($result as $row) {
                $proceeddate = $row['TglCutiDari'];
                while ($proceeddate <= $row['TglCutiSampai']) {
                    $record = array(
                        'Description' => $row['Jenis']
                    );
                    $nip = $row['IDEmployee'];
                    $date = $proceeddate;
		
		    $checkday =  $this->processpresence->get_workday($date,$nip);
                    if($checkday=='empty'){
			 $this->processpresence->update_presence_by_date($nip, $date, $record);
	            }

                   
                    $proceeddate = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                }
            }
        }
    }
	
	
    /*	
     function maternity_emp($fromdate, $untildate) {
	 ini_set('memory_limit', '-1'); // for unlimited size 
        $tanggal = date('Y-m-d', strtotime("-3 month", strtotime($fromdate)));
        $result = $this->processpresence->get_maternity_emp($tanggal, $untildate, 'MTL');
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == 'exist') {
            foreach ($result as $row) {
                $proceeddate = $row['TglCutiDari'];
                while ($proceeddate <= $row['TglCutiSampai']) {
                    $record = array(
                        'Description' => $row['Jenis']
                    );
                    $nip = $row['IDEmployee'];
                    $date = $proceeddate;

                    $this->processpresence->update_presence_by_date($nip, $date, $record);
                    $proceeddate = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                }
            }
        }
    }
	*/
    
    function holiday_emp($fromdate, $untildate) {
	 ini_set('memory_limit', '-1'); // for unlimited size 
        $result = $this->processpresence->getperiod_presence($fromdate, $untildate);
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == "exist") {
            foreach ($result as $row) {
                $nip = $row['IDEmployee'];
                $presence = $row['PresenceDate'];
                $Description = $row['Description'];
                $dayofweek = date('w', strtotime($presence));

                if ($this->processpresence->holiday_deduction_emp($presence, 'ALD')) {
                    $workday = 'OFF';
                    $desc = 'ALD';
                } else if ($this->processpresence->check_holiday_emp($presence)) {
                    $workday = 'OFF';
                    $desc = null;
                } else {

                    if ($dayofweek == 0) {
                        $workday = 'SUN';
                        $desc = $Description;
                    } elseif ($dayofweek > 0 && $dayofweek < 5) {
                        $workday = 'N1';
                        $desc = $Description;
                    } elseif ($dayofweek == 5) {
                        $workday = 'N2';
                        $desc = $Description;
                    } elseif ($dayofweek == 6)
                        $workday = 'N3';
                    $desc = $Description;
                }
                $record = array(
                    'WorkDay' => $workday,
                    'Description' => $desc
                );
                $this->processpresence->update_presence_by_date($nip, $presence, $record);
            }
        }
    }


    function proceed_suspension($fromdate, $untildate) {
	 ini_set('memory_limit', '-1'); // for unlimited size 
		$result = $this->processpresence->get_suspension($fromdate, $untildate);
		$check = ($result == "" or $result == null) ? "empty" : "exist";
		if ($check == "exist") {
		    foreach ($result as $row) {
		        $proceeddate = $row['SuspensionDate'];
		        while ($proceeddate <= $row['UntilDate']) {
		            $record = array('Description' => 'SP');
		            $nip = $row['IDEmployee'];
		            $date = $proceeddate;

			    $checkday =  $this->processpresence->get_workday($date,$nip);
                            if($checkday=='empty'){
				$this->processpresence->update_presence_by_date($nip, $date, $record);
			    }			
	
		            
		            $proceeddate = date('Y-m-d', strtotime("+1 day", strtotime($date)));
		        }
		    }
		}
	    }

  function proceed_leavework() { 
	 ini_set('memory_limit', '-1'); // for unlimited size        
        $result = $this->processpresence->getall_leavework();
        $check = ($result == "" or $result == null) ? "empty" : "exist";     
        if ($check == "exist") {
            foreach ($result as $row) {
                $proceeddate = $row['StartDate'];
                while ($proceeddate <= $row['FinishDate']) {
                    $day = date('w', strtotime($proceeddate));                    
                    if ($day == '0') {
                        $daysondb = $row['Day0'];
                    } else if ($day == '1') {
                        $daysondb = $row['Day1'];
                    } else if ($day == '2') {
                        $daysondb = $row['Day2'];
                    } else if ($day == '3') {
                        $daysondb = $row['Day3'];
                    } else if ($day == '4') {
                        $daysondb = $row['Day4'];
                    } else if ($day == '5') {
                        $daysondb = $row['Day5'];
                    } else if ($day == '6') {
                        $daysondb = $row['Day6'];
                    }                   
                    
                    $date = $proceeddate;                    
                    if($daysondb == $day){
                        $nip = $row['IDEmployee']; 
                        $leavehour = $row['LeaveHour']; 
                        
                        $record = array('Description' => 'PLW',
                                        'Note'=>$leavehour.' Hour'
                                        );  
                        
                        $this->processpresence->update_presence_by_date($nip, $date, $record);                        
                    }                    
                                                            
                    $proceeddate = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                }
            }
        }
    }

function checkpicket($from,$until){
        $result = $this->processpresence->getpresence($from, $until);
        $check  = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == "exist") {
            foreach ($result as $row) {
                $proceeddate = $from;
                while ($proceeddate <= $until) {
                    $id = $row['IDPresence'];                    
                    $nip = $row['IDEmployee'];
                    $date = $proceeddate;                     
                    $dayofweek = date('w', strtotime($date));
                    
                    if ($dayofweek == 0) {
                        $workday = 'SUN';
                    } elseif ($dayofweek > 0 && $dayofweek < 5) {
                        $workday = 'N1';
                    } elseif ($dayofweek == 5) {
                        $workday = 'N2';
                    } elseif ($dayofweek == 6){
                        $workday = 'N3';
                    }
                    
                    
                    $checkpicket = $this->processpresence->checkpicket($nip,$date);                    
                    if($checkpicket !=='empty'){                        
                        $record = array(
                         "Description"=>'P',    
                         "CatatanProses"=>'piket',    
                            
                        );
                        
                        
                        $this->processpresence->update_presence_by_date($nip, $date, $record);
                      
                       
                    }                  
                    
                    $proceeddate = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                }
            }
        }
        
    }
  

	

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


