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

	$idmenu                    = "90";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('proc01/home', $data);
    }

   

    function postingpresence($from,$until) {
        $fromdate = date('Y-m-d', strtotime($from));
        $untildate = date('Y-m-d', strtotime($until));
        //get data text from machine and insert to rawdata
        $resultunload = $this->unload_all_loaction();
        //echo '1. Create Datatext for Rawdata, Done'.'<br/>';	
	
         //$date = date('d');	
         //create presence
         //if ($date == '25'){
         $this->create_periode_presence($fromdate, $untildate);
         //echo '2. Create Periode Presence, Done'.'<br/>';
	 //}  
        // $this->create_actualin_and_actualout($fromdate, $untildate);
	 $this->create_actualin_actualout($fromdate, $untildate);
	  //echo '3. Create Actualin and ActualOut Presence, Done'.'<br/>';
         $this->create_worktime($fromdate, $untildate);
	  //echo '4. Create Worktime for Presence, Done'.'<br/>';
	 $this->holiday_emp($fromdate, $untildate);
	 //echo '5. Process Holiday to presence, Done'.'<br/>'; 
         //update presence with status
         
         $this->checkpicket($fromdate, $untildate);
         
         $this->proceed_incomplete($fromdate, $untildate);
	  //echo '6. Process Incomplete to presence, Done'.'<br/>';
         $this->proceed_officialtravel($fromdate, $untildate);
	  //echo '7. Process Official Travel to presence, Done'.'<br/>';
         $this->proceed_leavepermit($fromdate, $untildate);  
          //echo '8. Process Leavepermit to presence, Done'.'<br/>';    
         $this->proceed_leave_emp($fromdate, $untildate);
	  //echo '9. Process Leave to presence, Done'.'<br/>';   
         $this->maternity_emp();
	  //echo '10. Process Leave Maternity to presence, Done'.'<br/>'; 
       
         $this->proceed_suspension($fromdate, $untildate);  
	 // echo '11. Process Suspension Employee to presence, Done'.'<br/>'; 
	 $this->proceed_leavework();
	 // echo '12. Process Permission Leave to Work Employee to presence, Done'.'<br/>';        
	 //$this->checkpicket($fromdate, $untildate);
         // echo 13
         //$this->proceed_leave($fromdate, $untildate);
        // $this->proceed_sicknessleave($fromdate, $untildate);       
       
	$post = $this->session->userdata('sess_number');
        $mesg = "All Process Sucess with Post = " . $post . " Data";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                      "valid":"' . $valid . '"'
                .
                '}';

        echo $json;
    }

    function unload_all_loaction() {
        $path = "/tmp/rawdata.txt";       
        $this->post_unload($path);
        $this->finish_unload_all();        
    }
   
   /*
    function post_unload($path, $truncate = 0) {
        ini_set('memory_limit', '-1'); // for unlimited size     
        if ($truncate == 0) {
            $this->processpresence->truncatecardraw();
        }        
        $rawdatamachine = fopen($path, 'rb'); 
            while (!feof($rawdatamachine) ) {
                $line_of_text = fgets($rawdatamachine);
                $parts = explode('=', $line_of_text);
                $datatext =  $parts[0];            
                $record = array('DataText' => $datatext);
                $this->processpresence->insert_cardraw($record);
	       
            }	
        fclose($rawdatamachine);    
        
       
    }	
  */
    function post_unload($path, $truncate = 0) {
        ini_set('memory_limit', '-1'); // for unlimited size 
        ini_set ("display_errors", "1");
        error_reporting(E_ALL);
        
        if ($truncate == 0) {
            $this->processpresence->truncatecardraw();
        }
        $this->processpresence->insert_infilecardraw($path);
        
    }

    function finish_unload_all() {
        ini_set('memory_limit', '-1'); // for unlimited size  
        set_time_limit(0);
        $num = 0;
        $result = $this->processpresence->get_allcardraw();
        foreach ($result as $row) {
            $buffer = trim($row->DataText);
            if (intval($buffer) > 0) {
                // split buffer
                $enroll = substr($buffer, 0, 8);
                $tahun = substr($buffer, 8, 4);
                $bulan = substr($buffer, 12, 2);
                $hari = substr($buffer, 14, 2);
                $jam = substr($buffer, 16, 2);
                $menit = substr($buffer, 18, 2);
                //$detik = substr($buffer, 20, 2);
                $inout = substr($buffer, 22, 1);
                $queue = substr($buffer, 23, 3);
                $location = substr($queue, -1);


                // specified some var
                $nip = $this->processpresence->get_nip_by_enroll($enroll);
                $tanggal = $tahun . "-" . $bulan . "-" . $hari;
                $waktu = $jam . ":" . $menit . ":00";

                $record = array('DataText' => $buffer,
                    'IDCard' => $enroll,
                    'IDEmployee' => $nip,
                    'PresenceDate' => $tanggal,
                    'PresenceTime' => $waktu,
                    'Direction' => $inout,
                    'Location' => $location
                );
                //if record not exist, added! 
                if ($this->processpresence->check_rawdata($buffer) == False) {
                    $num++;
                    $this->processpresence->insert_rawdata($record);
                } else {
                    $this->processpresence->update_rawdata($buffer, $record);
                }
            }
        }
        set_time_limit(600);
        $this->session->set_userdata('sess_number',$num);
    }

    function create_periode_presence($fromdate, $untildate) {
        $resultpersonal = $this->processpresence->getall_employee();
        $check = ($resultpersonal == '' or $resultpersonal == null) ? 'empty' : 'exist';

        if ($check == 'exist') {
            foreach ($resultpersonal as $row) {
                $perioddate = $fromdate;
                while ($perioddate <= $untildate) {
                    $dayofweek = date('w', strtotime($perioddate));
                    if ($this->processpresence->check_holiday_emp($perioddate)) {
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
                    $record = array(
                        'IDPresence' => NULL,
                        'IDEmployee' => $row['IDEmployee'],
                        'PresenceDate' => $perioddate,
                        'WorkDay' => $workday,
                        'DayOfWeek' => $dayofweek
                    );
                    $this->processpresence->create_period_presence($row['IDEmployee'], $perioddate, $record);
                    $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
                }
            }
        }
    }


function create_actualin_actualout($fromdate, $untildate) {
        $resultdata = $this->processpresence->getall_period_rawdata($fromdate, $untildate);
        $checkdata = ($resultdata == '' or $resultdata == null) ? "empty" : "exist";

        if ($checkdata == 'exist') {
            $perioddate = $fromdate;
            while ($perioddate <= $untildate) {

                $resultin = $this->processpresence->getall_presence_rawdata_in($perioddate);
                $resultout = $this->processpresence->getall_presence_rawdata_out($perioddate);
                $checkdatain = ($resultin == '' or $resultin == null) ? "empty" : "exist";
                $checkdataout = ($resultout == '' or $resultout == null) ? "empty" : "exist";

                if ($checkdatain == 'exist') {
                    foreach ($resultin as $rowdatain) {
                        $nip = $rowdatain['IDEmployee'];
                        $presencedate = $rowdatain['PresenceDate'];
                        $time = $rowdatain['PresenceTime'];

                        $result_current = $this->processpresence->get_current_presence($nip, $presencedate);
                        $check_cur = ($result_current == "" or $result_current == null) ? "empty" : "exist";

                        if ($check_cur == 'exist') {
                            $actualin = $presencedate . ' ' . $time;
                            $record = array('ActualIn' => $actualin);
                            $this->processpresence->update_presence($result_current->IDPresence, $record);
                        }
                    }
                }

	        if ($checkdataout == 'exist') {
                    foreach ($resultout as $rowout) {

                        $nip2 = $rowout['IDEmployee'];
                        $presencedate2 = $rowout['PresenceDate'];
                        $time2 = $rowout['PresenceTime'];

                        $rowpresence = $this->processpresence->get_current_presence($nip2, $presencedate2);
                        $rowin = $this->processpresence->check_actualin_presence($nip2, $presencedate2);
                        $rowout = $this->processpresence->check_actualout_presence($nip2, $presencedate2);

                        $checkin = ($rowin == "" or $rowin == null) ? "empty" : "exist";
                        $checkout = ($rowout == "" or $rowout == null) ? "empty" : "exist";
                        $checkpresence = ($rowpresence == "" or $rowpresence == null) ? "empty" : "exist";

                        if ($checkpresence == 'exist') {

                            if ($checkin == 'exist' and $checkout == 'empty') {

                               
                                $id = $rowin->IDPresence;
                                $acin = $rowin->ActualIn;
                                $workday = $rowin->WorkDay;

                                $row_schedule = $this->processpresence->get_work_schedule($workday);
                                $breakduration = $row_schedule->BreakDuration;
                                $actualout = $presencedate2 . ' ' . $time2;
                                
                                $hour = (strtotime($actualout) - strtotime($acin)) / 3600;
                                $sumhour = $this->decimaltominutes($hour);
                                
                                if($sumhour <='0000'){
                                    $absensi = 'absensiout-mundur';
                                }else{
                                     $absensi = 'absensiout-current';
                                }
                                
                                if($absensi=='absensiout-mundur'){ 
                                    $rowinprev = $this->processpresence->get_prev_actualin($nip2, $presencedate2);
                                    $idpresence = $rowinprev->IDPresence;
                                    $actualin = $rowinprev->ActualIn;
                                    $presencedate = $rowinprev->PresenceDate;

                                    if (strlen($actualin) == 19) {
                                        $out = (int) date("H", strtotime($actualout));
                                        if ($out <= 13)
                                            $breakduration = 0;
                                        $actualhour = (strtotime($actualout) - strtotime($actualin)) / 3600 - $breakduration;
                                    } else {
                                        $actualhour = 0;
                                    }
                                    
                                    $hour2 = (strtotime($actualout) - strtotime($actualin)) / 3600;
                                    $sumhour2 = $this->decimaltominutes($hour2);

                                    $record = array(
                                            'ActualOut' => $actualout,
                                            'ActualHour' => $actualhour
                                        );
                                    
                                    if ($sumhour2 <= '2400') {
                                         $this->processpresence->update_presence($idpresence, $record);
                                        
                                    }
                                   // echo  'Harusnya '.$nip2.' Presence :'.$presencedate.' In :'.$actualin.' Out:'.$actualout.' Hour :'.$sumhour2.'<br/>';
                                                                        
                                }else{
                                    $idpresence = $id;
                                    $actualin =$acin;
                                    $presencedate =$presencedate2;
                                    
                                    if (strlen($actualin) == 19) {
                                        $out = (int) date("H", strtotime($actualout));
                                        if ($out <= 13)
                                            $breakduration = 0;
                                        $actualhour = (strtotime($actualout) - strtotime($actualin)) / 3600 - $breakduration;
                                    } else {
                                        $actualhour = 0;
                                    }
                                    
                                    $record = array(
                                         'ActualOut' => $actualout,
                                         'ActualHour' => $actualhour
                                     );

                                    $this->processpresence->update_presence($idpresence, $record);
                                    //echo  $nip2.' Presence :'.$presencedate.' In :'.$actualin.' Out:'.$actualout.' Hour :'.$sumhour.'<br/>';
                                   
                                }
                            } else if ($checkin == 'empty' and $checkout == 'empty') {

                                $rowin_prev_presence = $this->processpresence->get_prev_actualin($nip2, $presencedate2);
                                $rowout_prev_presence = $this->processpresence->get_prev_actualout($nip2, $presencedate2);

                                $checkprevin = ($rowin_prev_presence == "" or $rowin_prev_presence == null) ? "empty" : "exist";
                                $checkprevout = ($rowout_prev_presence == "" or $rowout_prev_presence == null) ? "empty" : "exist";

                                if ($checkprevin == 'exist' and $checkprevout == 'empty') {

                                    $id = $rowin_prev_presence->IDPresence;
                                    $nip = $rowin_prev_presence->IDEmployee;
                                    $workday = $rowin_prev_presence->WorkDay;
                                    $actualin = $rowin_prev_presence->ActualIn;
                                    $row_schedule = $this->processpresence->get_work_schedule($workday);
                                    $breakduration = $row_schedule->BreakDuration;

                                    $actualout = $presencedate . ' ' . $time;
                                    if (strlen($actualin) == 19) {
                                        $actualhour = (strtotime($actualout) - strtotime($actualin)) / 3600 - $breakduration;
                                    } else {
                                        $actualhour = 0;
                                    }

                                    $record = array(
                                        'ActualIn' => $actualin,
                                        'ActualOut' => $actualout,
                                        'ActualHour' => $actualhour
                                    );

                                    $hour = (strtotime($actualout) - strtotime($actualin)) / 3600;
                                    $sumhour = $this->decimaltominutes($hour);

                                    if ($sumhour <= '2400') {
                                        $this->processpresence->update_presence($id, $record);
                                    }
                                } else if ($checkprevin == 'exist' and $checkprevout == 'exist') {

                                    $id = $rowpresence->IDPresence;
                                    $actualin = $rowin_prev_presence->ActualIn;
                                    $actualout = $presencedate2 . ' ' . $time2;
                                    $nip = $rowin_prev_presence->IDEmployee;
                                    $actualhour = 0;


                                    $record = array(
                                        'ActualOut' => $actualout,
                                        'ActualHour' => $actualhour
                                    );


                                    $hour = (strtotime($actualout) - strtotime($actualin)) / 3600;
                                    $sumhour = $this->decimaltominutes($hour);

                                    if ($sumhour >= '2400') {
                                        $this->processpresence->update_presence($id, $record);
                                    }
                                } else if ($checkprevin == 'empty' and $checkprevout == 'empty' and $checkout == 'empty') {

                                    $id = $rowpresence->IDPresence;
                                    $actualout = $presencedate2 . ' ' . $time2;
                                    $actualhour = 0;
                                    $nip = $rowpresence->IDEmployee;

                                    $record = array(
                                        'ActualOut' => $actualout,
                                        'ActualHour' => $actualhour
                                    );

                                    $this->processpresence->update_presence($id, $record);
                                }
                            }
                        }
                    }
                }

                $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
            }
        }
    }


function create_actualin_actualoutxxxx($fromdate, $untildate) {
        $result = $this->processpresence->getall_period_rawdata($fromdate, $untildate);
        $check = ($result == '' or $result == null) ? "empty" : "exist";

        if ($check == 'exist') {
            $perioddate = $fromdate;
            while ($perioddate <= $untildate) {

                foreach ($result as $row) {
                    $nip = $row['IDEmployee'];
                    $inout = $row['Direction'];
                    $presencedate = $row['PresenceDate'];
                    $time = $row['PresenceTime'];

                    if ($inout == '1') {
                        $result_current = $this->processpresence->get_current_presence($nip, $presencedate);
                        $check_cur = ($result_current == "" or $result_current == null) ? "empty" : "exist";

                        if ($check_cur == 'exist') {
                            $actualin = $presencedate . ' ' . $time;
                            $record = array('ActualIn' => $actualin);
                            $this->processpresence->update_presence($result_current->IDPresence, $record);
                        }
                    }else if ($inout == '0') {
                        $rowpresence = $this->processpresence->get_current_presence($nip, $presencedate);
                        $rowin = $this->processpresence->check_actualin_presence($nip, $presencedate);
                        $rowout = $this->processpresence->check_actualout_presence($nip, $presencedate);

                        $checkin = ($rowin == "" or $rowin == null) ? "empty" : "exist";
                        $checkout = ($rowout == "" or $rowout == null) ? "empty" : "exist";
                        $checkpresence = ($rowpresence == "" or $rowpresence == null) ? "empty" : "exist";

                        if ($checkpresence == 'exist') {

                            if ($checkin == 'exist' and $checkout == 'empty') {

                                $id = $rowin->IDPresence;
                                $actualin = $rowin->ActualIn;
                                $workday = $rowin->WorkDay;

                                $row_schedule = $this->processpresence->get_work_schedule($workday);
                                $breakduration = $row_schedule->BreakDuration;
                                $actualout = $presencedate . ' ' . $time;

                                if (strlen($actualin) == 19) {
                                    $out = (int) date("H", strtotime($actualout));
                                    if ($out <= 13)
                                        $breakduration = 0;
                                    $actualhour = (strtotime($actualout) - strtotime($actualin)) / 3600 - $breakduration;
                                } else {
                                    $actualhour = 0;
                                }


                                $record = array(
                                    'ActualOut' => $actualout,
                                    'ActualHour' => $actualhour
                                );

                                $this->processpresence->update_presence($id, $record);
                            } else if ($checkin == 'empty' and $checkout == 'empty') {

                                $rowin_prev_presence = $this->processpresence->get_prev_actualin($nip, $presencedate);
                                $rowout_prev_presence = $this->processpresence->get_prev_actualout($nip, $presencedate);

                                $checkprevin = ($rowin_prev_presence == "" or $rowin_prev_presence == null) ? "empty" : "exist";
                                $checkprevout = ($rowout_prev_presence == "" or $rowout_prev_presence == null) ? "empty" : "exist";

                                if ($checkprevin == 'exist' and $checkprevout == 'empty') {

                                    $id = $rowin_prev_presence->IDPresence;
                                    $nip = $rowin_prev_presence->IDEmployee;
                                    $workday = $rowin_prev_presence->WorkDay;
                                    $actualin = $rowin_prev_presence->ActualIn;
                                    $row_schedule = $this->processpresence->get_work_schedule($workday);
                                    $breakduration = $row_schedule->BreakDuration;

                                    $actualout = $presencedate . ' ' . $time;
                                    if (strlen($actualin) == 19) {
                                        $actualhour = (strtotime($actualout) - strtotime($actualin)) / 3600 - $breakduration;
                                    } else {
                                        $actualhour = 0;
                                    }

                                    $record = array(
                                        'ActualIn' => $actualin,
                                        'ActualOut' => $actualout,
                                        'ActualHour' => $actualhour
                                    );

                                    $hour = (strtotime($actualout) - strtotime($actualin)) / 3600;
                                    $sumhour = $this->decimaltominutes($hour);

                                    if ($sumhour <= '2400') {
                                        $this->processpresence->update_presence($id, $record);
                                    }
                                } else if ($checkprevin == 'exist' and $checkprevout == 'exist') {

                                    $id = $rowpresence->IDPresence;
                                    $actualin = $rowin_prev_presence->ActualIn;
                                    $actualout = $presencedate . ' ' . $time;
                                    $nip = $rowin_prev_presence->IDEmployee;
                                    $actualhour = 0;


                                    $record = array(
                                        'ActualOut' => $actualout,
                                        'ActualHour' => $actualhour
                                    );


                                    $hour = (strtotime($actualout) - strtotime($actualin)) / 3600;
                                    $sumhour = $this->decimaltominutes($hour);

                                    if ($sumhour >= '2400') {
                                        $this->processpresence->update_presence($id, $record);
                                    }
                                } else if ($checkprevin == 'empty' and $checkprevout == 'empty' and $checkout == 'empty') {

                                    $id = $rowpresence->IDPresence;
                                    $actualout = $presencedate . ' ' . $time;
                                    $actualhour = 0;
                                    $nip = $rowpresence->IDEmployee;

                                    $record = array(
                                        'ActualOut' => $actualout,
                                        'ActualHour' => $actualhour
                                    );

                                    $this->processpresence->update_presence($id, $record);
                                }
                            }
                        }
                    }
                    
                }

                $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
            }
        }
    }





/*

function create_actualin_and_actualout($fromdate, $untildate) {
        //Unload Rawdata
        $result1 = $this->processpresence->getall_period_rawdata($fromdate, $untildate);
        $check1 = ($result1 == '' or $result1 == null) ? "empty" : "exist";
        if ($check1 == 'exist') {
            $perioddate = $fromdate;
            while ($perioddate <= $untildate) {
                $result2 = $this->processpresence->getall_presence_rawdata($perioddate);
                $check2 = ($result2 == '' or $result2 == null) ? "empty" : "exist";

                if ($check2 == 'exist') {
                    foreach ($result2 as $row) {
                        $nip = $row['IDEmployee'];
                        $direction = $row['Direction'];
                        $presencedate = $row['PresenceDate'];
                        $time = $row['PresenceTime'];

                        if ($direction == '1') {
                            //get ActualIn
                            $rowin = $this->processpresence->check_actualin_presence($nip, $presencedate);
                            $checkin = ($rowin == "" or $rowin == null) ? "empty" : "exist";
                            if ($checkin == 'empty') {
                                $result_current = $this->processpresence->get_current_presence($nip, $presencedate);
                                $check_cur = ($result_current == "" or $result_current == null) ? "empty" : "exist";
                                if ($check_cur == 'exist') {
                                    $actualin = $presencedate . ' ' . $time;
                                    $record = array('ActualIn' => $actualin);
                                    $this->processpresence->update_presence($result_current->IDPresence, $record);
                                }
                            }
                        } elseif ($direction == '0') {
                            //get ActualOut
                            $rowin = $this->processpresence->check_actualin_presence($nip, $presencedate);
                            $rowout = $this->processpresence->check_actualout_presence($nip, $presencedate);

                            $checkin = ($rowin == "" or $rowin == null) ? "empty" : "exist";
                            $checkout = ($rowout == "" or $rowout == null) ? "empty" : "exist";

                            if ($checkin == 'exist') {

                                $row_current = $this->processpresence->get_current_presence($nip, $presencedate);
                                $check_cur = ($row_current == "" or $row_current == null) ? "empty" : "exist";

                                if ($check_cur == 'exist') {

                                    $id = $row_current->IDPresence;
                                    $actualin = $row_current->ActualIn;
                                    $workday = $row_current->WorkDay;

                                    $row_schedule = $this->processpresence->get_work_schedule($workday);
                                    $breakduration = $row_schedule->BreakDuration;
                                    $actualout = $presencedate . ' ' . $time;

                                    if (strlen($actualin) == 19) {
                                        $out = (int) date("H", strtotime($actualout));
                                        if ($out <= 13)
                                            $breakduration = 0;
                                        $actualhour = (strtotime($actualout) - strtotime($actualin)) / 3600 - $breakduration;
                                    } else {
                                        $actualhour = 0;
                                    }
                                    $record = array(
                                        'ActualOut' => $actualout,
                                        'ActualHour' => $actualhour
                                    );

                                    $this->processpresence->update_presence($id, $record);
                                }
                            } else if ($checkin == 'empty') {  
                                $rowin_prev_presence = $this->processpresence->get_prev_presence($nip, $presencedate);
                                $checkdata2 = ($rowin_prev_presence == "" or $rowin_prev_presence == null) ? "empty" : "exist";
                                if ($checkdata2 == 'exist') {
                                    $id = $rowin_prev_presence->IDPresence;
                                    $workday = $rowin_prev_presence->WorkDay;
                                    $actualin = $rowin_prev_presence->ActualIn;  
                                    $row_schedule = $this->processpresence->get_work_schedule($workday);
                                    $breakduration = $row_schedule->BreakDuration;

                                    $actualout = $presencedate . ' ' . $time;

                                    if (strlen($actualin) == 19) {
                                        $actualhour = (strtotime($actualout) - strtotime($actualin)) / 3600 - $breakduration;
                                    } else {
                                        $actualhour = 0;
                                    }

                                    $record = array(
                                        'ActualIn' => $actualin,
                                        'ActualOut' => $actualout,
                                        'ActualHour' => $actualhour
                                    );

                                    $this->processpresence->update_presence($id, $record);
                                    
                                }
                            }
                        }
                    }//endforeach
                }//end if

                $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
            }//end while           
        }
    }

*/
	
/*
    function create_actualin_and_actualout($fromdate, $untildate) {
        //Unload Rawdata
        $result1 = $this->processpresence->getall_period_rawdata($fromdate, $untildate);
        $check1 = ($result1 == '' or $result1 == null) ? "empty" : "exist";
        if ($check1 == 'exist') {
            $perioddate = $fromdate;
            while ($perioddate <= $untildate) {
                $result2 = $this->processpresence->getall_presence_rawdata($perioddate);
                $check2 = ($result2 == '' or $result2 == null) ? "empty" : "exist";

                if ($check2 == 'exist') {
                    foreach ($result2 as $row) {
                        $nip = $row['IDEmployee'];
                        $direction = $row['Direction'];
                        $presencedate = $row['PresenceDate'];
                        $time = $row['PresenceTime'];

                        if ($direction == '1') {
                            //get ActualIn
                            $rowin = $this->processpresence->check_actualin_presence($nip, $presencedate);
                            $checkin = ($rowin == "" or $rowin == null) ? "empty" : "exist";
                            if ($checkin == 'empty') {
                                $result_current = $this->processpresence->get_current_presence($nip, $presencedate);
                                $check_cur = ($result_current == "" or $result_current == null) ? "empty" : "exist";
                                if ($check_cur == 'exist') {

                                    $actualin = $presencedate . ' ' . $time;
                                    $record = array('ActualIn' => $actualin);
                                    $this->processpresence->update_presence($result_current->IDPresence, $record);
                                }
                            }
                        } elseif ($direction == '0') {
                            //get ActualOut
                            $rowin = $this->processpresence->check_actualin_presence($nip, $presencedate);
                            $rowout = $this->processpresence->check_actualout_presence($nip, $presencedate);

                            $checkin = ($rowin == "" or $rowin == null) ? "empty" : "exist";
                            $checkout = ($rowout == "" or $rowout == null) ? "empty" : "exist";

                            if ($checkin == 'exist' and $checkout == 'empty') {

                                $row_current = $this->processpresence->get_current_presence($nip, $presencedate);
                                $check_cur = ($row_current == "" or $row_current == null) ? "empty" : "exist";

                                if ($check_cur == 'exist') {

                                    $id = $row_current->IDPresence;
                                    $actualin = $row_current->ActualIn;
                                    $workday = $row_current->WorkDay;

                                    $row_schedule = $this->processpresence->get_work_schedule($workday);
                                    $breakduration = $row_schedule->BreakDuration;
                                    $actualout = $presencedate . ' ' . $time;

                                    if (strlen($actualin) == 19) {
                                        $out = (int) date("H", strtotime($actualout));
                                        if ($out <= 13)
                                            $breakduration = 0;
                                        $actualhour = (strtotime($actualout) - strtotime($actualin)) / 3600 - $breakduration;
                                    } else {
                                        $actualhour = 0;
                                    }
                                    $record = array(
                                        'ActualOut' => $actualout,
                                        'ActualHour' => $actualhour
                                    );

                                    $this->processpresence->update_presence($id, $record);
                                }
                            } else if ($checkin == 'empty' and $checkout == 'exist') {

                                $row_current = $this->processpresence->get_current_presence($nip, $presencedate);
                                $rowin_prev = $this->processpresence->get_prev_rawdata($nip, $presencedate);

                                $checkdata1 = ($row_current == "" or $row_current == null) ? "empty" : "exist";
                                $checkdata2 = ($rowin_prev == "" or $rowin_prev == null) ? "empty" : "exist";

                                if ($checkdata1 == 'exist' and $checkdata2 == 'exist') {

                                    $id = $row_current->IDPresence;
                                    $predate = $rowin_prev->PresenceDate;
                                    $pretime = $rowin_prev->PresenceTime;
                                    $workday = $row_current->WorkDay;

                                    $actualin = $predate . ' ' . $pretime;

                                    $row_schedule = $this->processpresence->get_work_schedule($workday);
                                    $breakduration = $row_schedule->BreakDuration;

                                    $actualout = $presencedate . ' ' . $time;

                                    if (strlen($actualin) == 19) {
                                        $actualhour = (strtotime($actualout) - strtotime($actualin)) / 3600 - $breakduration;
                                    } else {
                                        $actualhour = 0;
                                    }

                                    $record = array(
                                        'ActualIn' => $actualin,
                                        'ActualOut' => $actualout,
                                        'ActualHour' => $actualhour
                                    );

                                    $this->processpresence->update_presence($id, $record);
                                }
                            }
                        }
                    }//endforeach
                }//end if

                $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
            }//end while           
        }
    }

*/
    function create_worktime($fromdate, $untildate) {
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
                        //$description = $row['Description'] ;
			$description = 'A';
                    }else{
                        $description = 'A';
                    }

                    $manualIn = $row['ManualIn'];
                    $manualOut = $row['ManualOut'];

                  if ($manualIn !== null and $manualOut !== null ) {
                        $totalhour = (strtotime($manualOut) - strtotime($manualIn) / 3600 );

                        if ($totalhour >= 4 and $row['Description'] =='MP') {
                            $description = 'MP';
                        } else if($totalhour >= 4 and $row['Description'] !=='MP') {
                            $description = 'P';
                        }else{
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

    function proceed_sicknessleave($fromdate, $untildate) {
        $result = $this->processpresence->get_sickness($fromdate, $untildate);
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == 'exist') {
            foreach ($result as $row) {
                $proceeddate = $row['SicknessDate'];
                while ($proceeddate <= $row['UntilDate']) {
                    if ($row['SicknessLetter'] == 'Y') {
                        $record = array('Description' => 'LSN');
                    } else {
                        $record = array('Description' => 'SN');
                    }

                    $nip = $row['IDEmployee'];
                    $date = $proceeddate;
                    $this->processpresence->update_presence_by_date($nip, $date, $record);
                    $proceeddate = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                }
            }
        }
    }

    function proceed_officialtravel($fromdate, $untildate) {
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

    function proceed_leave($fromdate, $untildate) {
        $result = $this->processpresence->get_leave($fromdate, $untildate);
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == 'exist') {
            foreach ($result as $row) {
                $proceeddate = $row['LeaveDate'];
                while ($proceeddate <= $row['UntilDate']) {
                    $record = array('Description' => $row['TypeLeave']);
                    $nip = $row['IDEmployee'];
                    $date = $proceeddate;

                    $this->processpresence->update_presence_by_date($nip, $date, $record);

                    $proceeddate = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                }
            }
        }
    }
    
  function proceed_leave_emp($fromdate, $untildate) {
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

    function maternity($fromdate, $untildate) {
        $tanggal = date('Y-m-d', strtotime("-3 month", strtotime($fromdate)));
        $result = $this->processpresence->get_maternity($tanggal, $untildate, 'MTL');
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == 'exist') {
            foreach ($result as $row) {
                $proceeddate = $row['LeaveDate'];
                while ($proceeddate <= $row['UntilDate']) {
                    $record = array(
                        'Description' => $row['TypeLeave']
                    );
                    $nip = $row['IDEmployee'];
                    $date = $proceeddate;

                    $this->processpresence->update_presence_by_date($nip, $date, $record);

                    $proceeddate = date('Y-m-d', strtotime("+1 day", strtotime($date)));
                }
            }
        }
    }
    
     function maternity_emp() {
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


    function holiday($fromdate, $untildate) {
        $result = $this->processpresence->getperiod_presence($fromdate, $untildate);
        $check = ($result == "" or $result == null) ? "empty" : "exist";
        if ($check == "exist") {
            foreach ($result as $row) {
                $nip = $row['IDEmployee'];
                $presence = $row['PresenceDate'];
                $Description = $row['Description'];
                $dayofweek = date('w', strtotime($presence));

                if ($this->processpresence->holiday_deduction($presence, 'ALD')) {
                    $workday = 'OFF';
                    $desc = 'ALD';
                } else if ($this->processpresence->check_holiday($presence)) {
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
    
    function holiday_emp($fromdate, $untildate) {
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



   function decimaltominutes($dec) {
        // start by converting to seconds
        $seconds = $dec * 3600;
        // we're given hours, so let's get those the easy way
        $hours = floor($dec);
        // since we've "calculated" hours, let's remove them from the seconds variable
        $seconds -= $hours * 3600;
        // calculate minutes left
        $minutes = floor($seconds / 60);
        // remove those from seconds as well
        $seconds -= $minutes * 60;
        // return the time formatted HH:MM:SS
        return $this->lz($hours).$this->lz($minutes);
    }

    function lz($num) {
        return (strlen($num) < 2) ? "0{$num}" : $num;
    }



}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */



