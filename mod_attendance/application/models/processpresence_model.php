<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Processpresence_model extends CI_Model {

    public function __construct() {
         parent::__construct();
        //table master employee
          
        $this->_db = $this->load->database('attendance', TRUE); 
        $this->_db_emp = $this->load->database('empcenter', TRUE); 
        // Table on employee center
        $this->_holiday_emp = 'r02holiday';
        $this->_leave_emp = 't01leavetrx';
        $this->_personal = 'm01personal'; 
	     
        //table transaction attendance
        $this->_cardmap = 'm02cardmap';
        $this->_cardraw = 't01cardraw';
        $this->_rawdata = 't02rawdata';
        $this->_presence = 't03presence';
        $this->_incomplete = 't05incomplete';
        $this->_sickness = 't06sicknessleave';
        $this->_officialtravel = 't07officialtravel';
        $this->_leavepermit = 't08leavepermit';
        $this->_leave = 't09leave';
        $this->_suspension = 't10suspension';
	$this->_leavework = 't11leavework';
	$this->_picket = 't12employeepicket';

        //table reference attendance
        $this->_holiday = 'r02holiday';
        $this->_schedule = 'r01workschedule';
    }

    function getpresence($from,$until){
        $this->_db->where('PresenceDate >=',$from);
        $this->_db->where('PresenceDate <=',$until);
        //$this->_db->where('Description','ALD');
        $result = $this->_db->get($this->_presence);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    
    function checkpicket($nip,$date){
        $sql = "
                        SELECT * FROM $this->_picket WHERE '$date' BETWEEN  FromDate AND UntilDate AND IDEmployee='$nip'
                ";
        $result = $this->_db->query($sql);
        if($result->num_rows()>0){
            return 'exist';
        }else{
            return 'empty';
        }        
        
    }
    
   function get_workday_leave($date,$nip){
        $sql = "SELECT WorkDay FROM $this->_presence WHERE PresenceDate='$date' AND IDEmployee='$nip' AND WorkDay IN('SUN','OFF')";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return 'exist';
        } else {
            return 'empty';
        }
        
    }


    function get_workday($date,$nip){
        $sql = "SELECT WorkDay FROM $this->_presence WHERE PresenceDate='$date' AND IDEmployee='$nip' AND WorkDay IN('SUN','OFF') AND CatatanProses NOT IN('piket')";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return 'exist';
        } else {
            return 'empty';
        }
        
    }
 

    function truncatecardraw() {
        $this->_db->truncate($this->_cardraw);
    }
	
    function insert_cardraw($record){
        $this->_db->insert($this->_cardraw,$record);
    }

    function insert_infilecardraw($path) {
        $sql = "LOAD DATA LOCAL INFILE '" . $path . "' INTO TABLE $this->_cardraw LINES TERMINATED BY '\n'";
         /* $sql = "LOAD DATA LOCAL INFILE '".$path."' INTO TABLE $this->_cardraw
                 FIELDS TERMINATED BY '\t' 
                 ENCLOSED BY '\"' 
                 LINES TERMINATED BY '\n'
                 IGNORE 1 LINES"; */
        
        $this->_db->query($sql);
    }

    function get_allcardraw() {
        return $this->_db->get($this->_cardraw)->result();
    }

    function get_nip_by_enroll($enroll) {
        $this->_db->where('IDCard', $enroll);
	$this->_db->where('LastStatus','T');
	 $this->_db->where('DeleteFlag','A');
        if ($this->_db->count_all_results($this->_cardmap) == 1) {
            $this->_db->where('IDCard', $enroll);
	    $this->_db->where('LastStatus','T');
	      $this->_db->where('DeleteFlag','A');	
            $query = $this->_db->get($this->_cardmap)->row();
            return $query->IDEmployee;
        } else {
            return '-ERR';
        }
    }

    function check_rawdata($buffer) {
        $this->_db->where('DataText', $buffer);
        if ($this->_db->count_all_results($this->_rawdata) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function insert_rawdata($record) {
        $this->_db->insert($this->_rawdata, $record);
    }

    function update_rawdata($buffer, $record) {
        $this->_db->where('DataText', $buffer);
        $this->_db->update($this->_rawdata, $record);
    }

    function getall_employee() {
        $this->_db_emp->where('Status', 'A');
        $this->_db_emp->order_by('FullName', 'ASC');
        $result = $this->_db_emp->get($this->_personal);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function check_holiday($date) {
        $this->_db->where('Date', $date);
        $result = $this->_db->get($this->_holiday);
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
     function check_holiday_emp($date) {
        $this->_db_emp->where('Date', $date);
	$this->_db_emp->where('DeleteFlag','A');
        $result = $this->_db_emp->get($this->_holiday_emp);
        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

   

    function getall_period_rawdata($fromdate, $untildate) {
        $this->_db->where("PresenceDate >=", $fromdate);
        $this->_db->where("PresenceDate <=", $untildate);
        $this->_db->order_by("PresenceDate", "asc");
        $this->_db->order_by("IDEmployee", "asc");
        $this->_db->order_by("PresenceTime", "asc");
        $result = $this->_db->get($this->_rawdata);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function getall_presence_rawdata($date) {
        $this->_db->where("PresenceDate", $date);
        $this->_db->order_by("PresenceDate", "asc");
        $this->_db->order_by("IDEmployee", "asc");
        $this->_db->order_by("PresenceTime", "asc");
        $result = $this->_db->get($this->_rawdata);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function create_period_presence($nip, $date, $record) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PresenceDate', $date);
        $result = $this->_db->get($this->_presence);
        if ($result->num_rows() > 0) {
            return false;
        } else {
            $this->insert_presence($record);
        }
    }

    function getperiod_presence($fromdate, $untildate) {
        $this->_db->where('PresenceDate >=', $fromdate);
        $this->_db->where('PresenceDate <=', $untildate);
        $result = $this->_db->get($this->_presence);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function check_actualin_presence($nip, $date) {
        $sql = "SELECT * FROM $this->_presence WHERE IDEmployee='$nip' AND PresenceDate ='$date' AND ActualIn is not null";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function check_actualout_presence($nip, $date) {
        $sql = "SELECT * FROM $this->_presence WHERE IDEmployee='$nip' AND PresenceDate ='$date' AND ActualOut is not null";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_current_presence($nip, $date) {
        $this->_db->where('PresenceDate', $date);
        $this->_db->where('IDEmployee', $nip);
        $result = $this->_db->get($this->_presence);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

   function get_prev_presence($nip, $date) {
        $prevdate = date('Y-m-d', strtotime("-1 day", strtotime($date)));
        $this->_db->where('PresenceDate', $prevdate);
        $this->_db->where('IDEmployee', $nip);
        $result = $this->_db->get($this->_presence);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_prev_rawdata($nip, $date) {
        $prevdate = date('Y-m-d', strtotime("-1 day", strtotime($date)));
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PresenceDate', $prevdate);
        $this->_db->where('Direction', '1');
        $this->_db->order_by('PresenceTime', 'Desc');
        $result = $this->_db->get($this->_rawdata);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function update_presence($id, $record) {
        $this->_db->where('IDPresence', $id);
        $this->_db->update($this->_presence, $record);
    }

    function update_presence_by_date($nip, $date, $record) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PresenceDate', $date);
        $this->_db->update($this->_presence, $record);
    }

    function insert_presence($record) {
        $this->_db->insert($this->_presence, $record);
    }

    function get_work_schedule($id) {
        $this->_db->where('IDSchedule', $id);
        $result = $this->_db->get($this->_schedule);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_sickness($from, $until) {
        $this->_db->where('SicknessDate >=', $from);
        $this->_db->where('SicknessDate <=', $until);
        $result = $this->_db->get($this->_sickness);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function get_officialtravel($from, $until) {
        $this->_db->where('OfficialTravelDate >=', $from);
        $this->_db->where('OfficialTravelDate <=', $until);
	$this->_db->where('ConfirmFlag','1');
	$this->_db->where('DeleteFlag','A'); 
        $result = $this->_db->get($this->_officialtravel);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function get_leavepermit($from, $until) {
        $this->_db->where('LeavePermitDate >=', $from);
        $this->_db->where('LeavePermitDate <=', $until);
	$this->_db->where('ConfirmFlag','1');
	$this->_db->where('DeleteFlag','A'); 
        $result = $this->_db->get($this->_leavepermit);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function get_incomplete($from, $until) {
        $this->_db->where('IncompleteDate >=', $from);
        $this->_db->where('IncompleteDate <=', $until);
	$this->_db->where('ConfirmFlag','1');
	$this->_db->where('DeleteFlag','A'); 	
        $result = $this->_db->get($this->_incomplete);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function get_leave($from, $until) {
        $this->_db->where('LeaveDate >=', $from);
        $this->_db->where('LeaveDate <=', $until);
        $result = $this->_db->get($this->_leave);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    function get_leave_emp($from, $until) {
        $this->_db_emp->where('TglCutiDari >=', $from);
        $this->_db_emp->where('TglCutiSampai <=', $until);
        $this->_db_emp->where('FPgt','true');
        $this->_db_emp->where('FAts','true');
        $this->_db_emp->where('FHrd','true');    
        $this->_db_emp->where('DeleteFlag','A');    
        $result = $this->_db_emp->get($this->_leave_emp);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function get_maternity($from, $until, $type) {
        $this->_db->where('LeaveDate >=', $from);
        $this->_db->where('LeaveDate <=', $until);
        $this->_db->where('TypeLeave', $type);
        $result = $this->_db->get($this->_leave);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    function get_maternity_emp($type) {      
        $this->_db_emp->where('Jenis', $type);
        $this->_db_emp->where('FPgt','true');
        $this->_db_emp->where('FAts','true');
        $this->_db_emp->where('FHrd','true');
        $this->_db_emp->where('DeleteFlag','A');   
        $result = $this->_db_emp->get($this->_leave_emp);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
   /*	
   function get_maternity_emp($from, $until, $type) {
        $this->_db_emp->where('TglCutiDari >=', $from);
        $this->_db_emp->where('TglCutiSampai <=', $until);
        $this->_db_emp->where('Jenis', $type);
        $this->_db_emp->where('FPgt','true');
        $this->_db_emp->where('FAts','true');
        $this->_db_emp->where('FHrd','true');
        $this->_db_emp->where('DeleteFlag','A');   
        $result = $this->_db_emp->get($this->_leave_emp);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
  */

 	
	
    function get_suspension($from, $until) {
        $this->_db->where('SuspensionDate >=', $from);
        $this->_db->where('SuspensionDate <=', $until);
	$this->_db->where('DeleteFlag','A');
        $result = $this->_db->get($this->_suspension);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function holiday_deduction($date, $data) {
        $this->_db->where('Date', $date);
        $this->_db->where('Flag', $data); 
        $result = $this->_db->get($this->_holiday);
        if ($result->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    function holiday_deduction_emp($date, $data) {
        $this->_db_emp->where('Date', $date);
        $this->_db_emp->where('Flag', $data);
        $this->_db_emp->where('DeleteFlag','A'); 
        $result = $this->_db_emp->get($this->_holiday_emp);
        if ($result->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

  function getall_leavework(){
        $this->_db->where('DeleteFlag','A');
        $result = $this->_db->get($this->_leavework);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }  
        
    }

	

   function getall_presence_rawdata_in($date) {
        $this->_db->where("PresenceDate", $date);
        $this->_db->where("Direction", '1');
        $this->_db->order_by("PresenceDate", "asc");
        $this->_db->order_by("IDEmployee", "asc");
        $this->_db->order_by("PresenceTime", "desc");
        $result = $this->_db->get($this->_rawdata);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    
    
    function getall_presence_rawdata_out($date) {
        $this->_db->where("PresenceDate", $date);
        $this->_db->where("Direction", '0');
        $this->_db->order_by("PresenceDate", "asc");
        $this->_db->order_by("IDEmployee", "asc");
        $this->_db->order_by("PresenceTime", "desc");
        $result = $this->_db->get($this->_rawdata);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
	


      function get_prev_actualin($nip, $date) {
        $prevdate = date('Y-m-d', strtotime("-1 day", strtotime($date)));
        $sql = "SELECT * FROM $this->_presence WHERE IDEmployee='$nip' AND PresenceDate ='$prevdate' AND ActualIn is not null";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
        
       
    }

    function get_prev_actualout($nip, $date) {
        $prevdate = date('Y-m-d', strtotime("-1 day", strtotime($date)));
        $sql = "SELECT * FROM $this->_presence WHERE IDEmployee='$nip' AND PresenceDate ='$prevdate' AND ActualOut is not null";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
        
       
    }	
 	



}
?>


