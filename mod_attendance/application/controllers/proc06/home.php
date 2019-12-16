<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Processpresence_model', 'processpresence');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $date = $this->periodpayroll();
        $data['default']['f01'] = substr($date, 0, 10);
        $data['default']['f02'] = substr($date, 11, 10);
        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));


        $this->load->view('proc06/home', $data);
    }

    function periodpayroll() {
        // Periode Staff 25-24
        if (date('d') >= 28) {
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

    function postingpresence($from, $until) {
        $fromdate = date('Y-m-d', strtotime($from));
        $untildate = date('Y-m-d', strtotime($until));

        //create presence
        $this->create_periode_presence($fromdate, $untildate);
        //echo ' Create Periode Presence, Done'.'<br/>';
        $this->create_actualin_and_actualout($fromdate, $untildate);
        //echo 'Create Actualin and ActualOut Presence, Done'.'<br/>';

        $mesg = "Process Create Periode and data Actual In and Actual Out From ".$fromdate.' And Until '.$untildate.' Success ';
        $valid = 'true';
        $json = '{ "mesg":"' . $mesg . '",
                      "valid":"' . $valid . '"'
                .
                '}';

        echo $json;
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

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */



