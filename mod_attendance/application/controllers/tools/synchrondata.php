<?php

//OVERTIME
class Synchrondata extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('synchrondata_model', 'synchrondata');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $data['overtime'] =''; //site_url('tools/synchrondata/overtime');
        $data['incomplete'] = '';//site_url('tools/synchrondata/incomplete');
        $data['sickness'] ='';// site_url('tools/synchrondata/sickness');
        $data['officialtravel'] = '';//site_url('tools/synchrondata/officialtravel');
        $data['leavepermit'] = '';//site_url('tools/synchrondata/leavepermit');
        $data['leave'] ='';// site_url('tools/synchrondata/leave');
	$data['family_spouse'] = //site_url('tools/family');
	$data['manualpresence'] =// site_url('tools/manualpresence');
	$data['allmanualpresence'] = site_url('tools/allmanualpresence');		
        $this->load->view('tools/home', $data);
	
    }

    function overtime() {
        $this->synchron_overtime();
        echo "Synchron overtime finish";
    }

    function incomplete() {
        $this->synchron_incomplete();
        echo "Synchron incomplete finish";
    }

    function sickness() {
        $this->synchron_sickness();
        echo "Synchron sickness finish";
    }

    function officialtravel() {
        $this->synchron_travel();
        echo "Synchron official travel finish";
    }

    function leavepermit() {
        
        $this->synchron_leavepermit();
        echo "Synchron leavepermit finish";
    }

    function leave() {
        $this->synchron_leave();
        echo "Synchron leave finish";
    }

    function synchron_overtime() {
        ini_set('memory_limit', '-1'); // for unlimited size  
        $result = $this->synchrondata->getall_overtime();
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            foreach ($result as $row) {
                $spkl = $row['IDSPKL'];
                $nip = $row['IDEmployee'];
                $presence = $row['PresenceDate'];
                $start = $row['OvertimeIn'];
                $end = $row['OvertimeOut'];
                $hour = (strtotime($end) - strtotime($start)) / 3600;
                $note = $row['Note'];
                $checkdata = $row['CheckData'];

                $record = array(
                    "FlagInput" => 'hrd',
                    "IDSPKL" => $spkl,
                    "IDEmployee" => $nip,
                    "PresenceDate" => $presence,
                    "OvertimeIn" => $start,
                    "OvertimeOut" => $end,
                    "OvertimeHour" => $hour,
                    "Note" => $note,
                    "CheckData" => $checkdata,
                    "ConfirmFlag" => '1',
                    "ConfirmDate" => date('Y-m-d H:i:s'),
                    "AddedBy" => $row['AddedBy'],
                    "AddedDate" => $row['AddedDate'],
                    "AddedIP" => $row['AddedIP'],
                    "EditedBy" => $row['EditedBy'],
                    "EditedDate" => $row['EditedDate'],
                    "EditedIP" => $row['EditedIP']
                );

                $this->synchrondata->check_overtime($nip, $presence, $start, $end, $record);
            }
        }
    }

    function synchron_incomplete() {
        ini_set('memory_limit', '-1'); // for unlimited size  
        $result = $this->synchrondata->getall_incomplete();
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            foreach ($result as $row) {
                $nip = $row['IDEmployee'];
                $date = $row['IncompleteDate'];
                $in = $row['TimeIn'];
                $out = $row['TimeOut'];
                $note = $row['Note'];

                $record = array(
                    "FlagInput" => 'hrd',
                    "IDEmployee" => $nip,
                    "IncompleteDate" => $date,
                    "TimeIn" => $in,
                    "TimeOut" => $out,
                    "Note" => $note,
                    "ConfirmFlag" => '1',
                    "ConfirmDate" => date('Y-m-d H:i:s'),
                    "AddedBy" => $row['AddedBy'],
                    "AddedDate" => $row['AddedDate'],
                    "AddedIP" => $row['AddedIP'],
                    "EditedBy" => $row['EditedBy'],
                    "EditedDate" => $row['EditedDate'],
                    "EditedIP" => $row['EditedIP']
                );

                $this->synchrondata->check_incomplete($nip, $date, $out, $in, $record);
            }
        }
    }

    function synchron_sickness() {
        ini_set('memory_limit', '-1'); // for unlimited size  
        $result = $this->synchrondata->getall_sickness();
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            $i = 0;
            foreach ($result as $row) {
                $i++;
                $nip = $row['IDEmployee'];
                $from = $row['SicknessDate'];
                $until = $row['UntilDate'];
                $sumdays = $row['SumDays'] + 1;
                $type = $row['TypeLeave'];
                $note = $row['Note'];
                $letter = ($row['SicknessLetter'] == 'Y') ? '1' : '0';
                //echo "No " . $i . " NIP :" . $nip . " Tanggal Cuti Sakit :" . $from . " Jumlah Hari :" . ($sumdays) . "Dengan Surat = " . $letter . "<br/>";

                $perioddate = $from;
                $day = array('minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu');
                $summinggu = 0;
                while ($perioddate <= $until) {
                    $week = $day[date('w', strtotime($perioddate))];
                    if ($week == 'minggu') {
                        $summinggu+= 1;
                    }
                    $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
                }

                $resultholiday = $this->synchrondata->get_holiday($from, $until);
                $checkholiday = ($resultholiday == '' or $resultholiday == null) ? 'empty' : 'exist';

                if ($checkholiday == 'exist') {
                    $sumlibur = $resultholiday->jumlahlibur;
                } else {
                    $sumlibur = 0;
                }

                $jumlahlibur = ($summinggu) + ($sumlibur);
                $jumlahcuti = $sumdays - $jumlahlibur;
		
		$requestdate = date('Y-m-d', strtotime("+1 day", strtotime($until)));
		

                $record = array(
                    "FlagInput" => 'hrd',
                    "IDEmployee" => $nip,
                    "Jenis" => $type,
                    "SickLetter" => $letter,
		    "TglPengajuan" => $requestdate,	
                    "TglCutiDari" => $from,
                    "TglCutiSampai" => $until,
                    "TotalCuti" => $jumlahcuti,
                    "Alasan" => $note,
                    "SisaCuti" => 0,
                    "FPgt" => 'true',
                    "FAts" => 'true',
                    "FHrd" => 'true',
                    "AddedBy" => $row['AddedBy'],
                    "AddedDate" => $row['AddedDate'],
                    "AddedIP" => $row['AddedIP'],
                    "EditedBy" => $row['EditedBy'],
                    "EditedDate" => $row['EditedDate'],
                    "EditedIP" => $row['EditedIP']
                );

                $this->synchrondata->check_leave($nip, $type, $from, $until, $record);
            }
        }
    }

    function synchron_travel() {
        ini_set('memory_limit', '-1'); // for unlimited size  
        $result = $this->synchrondata->getall_travel();
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            foreach ($result as $row) {
                $nip = $row['IDEmployee'];
                $from = $row['OfficialTravelDate'];
                $until = $row['UntilDate'];
                $note = $row['Note'];

                $record = array(
                    "FlagInput" => 'hrd',
                    "IDEmployee" => $nip,
                    "OfficialTravelDate" => $from,
                    "UntilDate" => $until,
                    "Note" => $note,
                    "ConfirmFlag" => '1',
                    "ConfirmDate" => date('Y-m-d H:i:s'),
                    "AddedBy" => $row['AddedBy'],
                    "AddedDate" => $row['AddedDate'],
                    "AddedIP" => $row['AddedIP'],
                    "EditedBy" => $row['EditedBy'],
                    "EditedDate" => $row['EditedDate'],
                    "EditedIP" => $row['Edi192tedIP']
                );

                $this->synchrondata->check_travel($nip, $from, $until, $record);
            }
        }
    }

    function synchron_leavepermit() {
        ini_set('memory_limit', '-1');// for unlimited size  
        $result = $this->synchrondata->getall_leavepermit();
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            foreach ($result as $row) {
                $nip = $row['IDEmployee'];
                $date = $row['LeavePermitDate'];
                $out = $row['OutDate'];
                $in = $row['InDate'];
                $hour = $row['IMKHour'];
                $status = $row['Necessity'];
                $note = $row['Note'];

                $record = array(
                    "FlagInput" => 'hrd',
                    "IDEmployee" => $nip,
                    "LeavePermitDate" => $date,
                    "OutDate" => $out,
                    "InDate" => $in,
                    "IMKHour" => $hour,
                    "Necessity" => $status,
                    "Note" => $note,
                    "ConfirmFlag" => '1',
                    "ConfirmDate" => date('Y-m-d H:i:s'),
                );
                $this->synchrondata->check_leavepermit($nip, $date, $out, $in, $record);
            }
        }
    }

    function synchron_leave() {
        ini_set('memory_limit', '-1'); // for unlimited size  
        $result = $this->synchrondata->getall_leave('2014-04-25','2014-05-24');
        $checkdata = ($result == '' or $result == null) ? 'empty' : 'exist';
        if ($checkdata == 'exist') {
            $i = 0;
            foreach ($result as $row) {
                $i++;
                $nip = $row['IDEmployee'];
                $from = $row['LeaveDate'];
                $until = $row['UntilDate'];
                $sumdays = $row['SumDays'] + 1;
                $type = $row['TypeLeave'];
                $note = $row['Note'];

                $perioddate = $from;
                $day = array('minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu');
                $summinggu = 0;
                while ($perioddate <= $until) {
                    $week = $day[date('w', strtotime($perioddate))];
                    if ($week == 'minggu') {
                        $summinggu+= 1;
                    }
                    $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
                }

                $resultholiday = $this->synchrondata->get_holiday($from, $until);
                $checkholiday = ($resultholiday == '' or $resultholiday == null) ? 'empty' : 'exist';

                if ($checkholiday == 'exist') {
                    $sumlibur = $resultholiday->jumlahlibur;
                } else {
                    $sumlibur = 0;
                }

                $jumlahlibur = ($summinggu) + ($sumlibur);
                $jumlahcuti = $sumdays - $jumlahlibur;

	        $requestdate = date('Y-m-d', strtotime("-7 day", strtotime($from)));

                $record = array(
                    "FlagInput" => 'hrd',
                    "IDEmployee" => $nip,
                    "Jenis" => $type,
		    "TglPengajuan" => $requestdate,	
                    "TglCutiDari" => $from,
                    "TglCutiSampai" => $until,
                    "TotalCuti" => $jumlahcuti,
                    "Alasan" => $note,
                    "SisaCuti" => 0,
                    "FPgt" => 'true',
                    "FAts" => 'true',
                    "FHrd" => 'true',
                    "AddedBy" => $row['AddedBy'],
                    "AddedDate" => $row['AddedDate'],
                    "AddedIP" => $row['AddedIP'],
                    "EditedBy" => $row['EditedBy'],
                    "EditedDate" => $row['EditedDate'],
                    "EditedIP" => $row['EditedIP'],
                );

                $this->synchrondata->check_leave($nip, $type, $from, $until, $record);
            }
        }
    }

}
