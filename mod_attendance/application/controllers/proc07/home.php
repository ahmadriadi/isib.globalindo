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
	$idmenu                    = "90";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('proc07/home', $data);
    }

   

    function postingpresence($errdate,$recdate) {
        $errordate = date('Y-m-d', strtotime($errdate));
        $recoverydate = date('Y-m-d', strtotime($recdate));
        //get data text from machine and insert to rawdata
        $this->unload_all_loaction($errordate,$recoverydate);
        //echo '1. Create Datatext for Rawdata, Done'.'<br/>';	
	 $this->create_actualin_actualout($recoverydate, $recoverydate);
	 //echo '2. Create Actualin and ActualOut Presence, Done'.'<br/>';
         $this->create_worktime($recoverydate, $recoverydate);
	  //echo '3. Create Worktime for Presence, Done'.'<br/>';
	
       
	$post = $this->session->userdata('sess_number');
        $mesg = "All Process Sucess with Post = " . $post . " Data";
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                      "valid":"' . $valid . '"'
                .
                '}';

        echo $json;
    }

    function unload_all_loaction($errordate,$recoverydate) {
        $path = "/tmp/rawdata.txt";       
        $this->post_unload($path);
        $this->finish_unload_all($errordate,$recoverydate);        
    }
   
  
    function post_unload($path, $truncate = 0) {
        ini_set('memory_limit', '-1'); // for unlimited size 
        ini_set ("display_errors", "1");
        error_reporting(E_ALL);
        
        if ($truncate == 0) {
            $this->processpresence->truncatecardraw();
        }
        $this->processpresence->insert_infilecardraw($path);
        
    }

    function finish_unload_all($errordate,$recoverydate) {
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
                $detik = substr($buffer, 20, 2);
                $inout = substr($buffer, 22, 1);
                $queue = substr($buffer, 23, 3);
                $location = substr($queue, -1);
                
                $datecur = date('Y-m-d',  strtotime($tahun.'-'.$bulan.'-'.$hari));
                
                if($datecur == $errordate){
                  $temp = explode('-',$recoverydate);
                  $year = $temp[0];
                  $month = $temp[1];
                  $days = $temp[2];
                }else{
                  $year = $tahun;
                  $month = $bulan;
                  $days = $hari;
                    
                }
                
                
                // specified some var
		$rawdata = $enroll.$year.$month.$days.$jam.$menit.$detik.$inout.$queue.$location;
                $nip = $this->processpresence->get_nip_by_enroll($enroll);
                $tanggal = $year . "-" . $month . "-" . $days;
                $waktu = $jam . ":" . $menit . ":00";

                $record = array('DataText' => $rawdata,
                    'IDCard' => $enroll,
                    'IDEmployee' => $nip,
                    'PresenceDate' => $tanggal,
                    'PresenceTime' => $waktu,
                    'Direction' => $inout,
                    'Location' => $location
                );
                //if record not exist, added! 
                if ($this->processpresence->check_rawdata($rawdata) == False) {
                    $num++;
                    $this->processpresence->insert_rawdata($record);
                } else {
                    $this->processpresence->update_rawdata($rawdata, $record);
                }
            }
        }
        set_time_limit(600);
        $this->session->set_userdata('sess_number',$num);
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



