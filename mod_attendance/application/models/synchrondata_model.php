<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Synchrondata_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        //$this->_db_at_apps = $this->load->database('attendance_apps', TRUE);
        $this->_db_at_office = $this->load->database('attendance', TRUE);
        $this->_db_emp = $this->load->database('empcenter', TRUE);
        $this->_db_pbl = $this->load->database('public', TRUE);
        $this->_overtime = 't04overtime';
        $this->_incomplete = 't05incomplete';
        $this->_sickness = 't06sicknessleave';
        $this->_officialtravel = 't07officialtravel';
        $this->_leavepermit = 't08leavepermit';
        $this->_leave = 't09leave';
        $this->_leave_trx = 't01leavetrx';
        $this->_holiday = 'r02holiday';
	$this->_presence= 't03presence';
	$this->_notvalid= 'emailnotvalid';
	$this->_outbond= 'temp01outbond2015';
	$this->_manual= 'tmp02manualpresence';

        $this->_pbl_personal = 'm01personal';
        $this->_personal_d = 'm01personal_d';
        $this->_family = 'm01personal_family';
        $this->_emp_personal = 'isib_employee.m01personal';

    }
    
    function get_tmpmanual(){
        $result = $this->_db_at_office->get($this->_manual);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
        
    }
    
    
    function getworktime($presencedate){
        $this->_db_at_office->where('PresenceDate',$presencedate);
        $result = $this->_db_at_office->get($this->_presence);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return 'empty';
        }
        
    }
    
    
    function updatefor_manual($nip,$presencedate,$record){
        $this->_db_at_office->where('IDEmployee',$nip);
        $this->_db_at_office->where('PresenceDate',$presencedate);
        $this->_db_at_office->update($this->_presence,$record);
        
    }
        
    
    function getdata_outbond ($from,$until){
        $sql = "
                SELECT 
                        a.ActualIn,a.ActualOut,a.WorkDay,a.PresenceDate,
                        b.IDEmployee
                  FROM $this->_presence a
                  JOIN $this->_outbond b ON a.IDEmployee = b.IDEmployee    
                  WHERE
                  a.PresenceDate BETWEEN '$from' AND '$until'
            ";
        
        $result = $this->_db_at_office->query($sql);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
        
    }

    function getdata_presence($from,$until){
        $this->_db_at_office->where('PresenceDate >=',$from);
        $this->_db_at_office->where('PresenceDate <=',$until);
        $result =  $this->_db_at_office->get($this->_presence);
          if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
        
    }


    function update_presence_by_id($id,$record){
      $this->_db_at_office->where('IDPresence',$id);
      $this->_db_at_office->update($this->_presence,$record);
        
    }


     function get_emailnotvalid(){
        $sql = "SELECT b.FullName,a.InternalEmail FROM $this->_notvalid a
                LEFT JOIN $this->_pbl_personal b
                ON a.InternalEmail = b.InternalEmail
                ORDER BY
                b.FullName ASC";
        
         $result = $this->_db_pbl->query($sql);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
        
    }
    
    function getpresence_onsite($location,$presence){
        $sql = "SELECT a.IDEmployee,b.FullName,a.PresenceDate,a.ActualIn,a.ActualOut,a.ManualIn,a.ManualOut,a.Description,b.IDLocation,b.IDJobGroup,a.CatatanProses 
                FROM $this->_presence a
                LEFT JOIN $this->_emp_personal b ON a.IDEmployee = b.IDEmployee
                WHERE 
                b.Status ='A' AND 
                b.IDLocation ='$location' AND
                a.PresenceDate = '$presence'
                ORDER BY 
                b.FullName ASC";
        
        $result = $this->_db_at_office->query($sql);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
        
    }
	


   function getpresence_oncondition($date){
        $sql=   "   SELECT a.IDEmployee,b.FullName,b.IDJobGroup,a.PresenceDate,a.ActualIn,a.ActualOut,a.ManualIn,a.ManualOut,a.Description
                    FROM $this->_presence a
                    LEFT JOIN $this->_emp_personal b 
                    ON a.IDEmployee = b.IDEmployee
                    WHERE a.PresenceDate='$date' AND a.ActualIn is not null AND b.IDJobGroup='ST' AND a.Description='A'

           ";
        
        $result = $this->_db_at_office->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return NUll;
        }
        
        
    }


   function getpresence_oncondition_all($date){
        $sql=   "   SELECT a.IDEmployee,b.FullName,b.IDJobGroup,a.PresenceDate,a.ActualIn,a.ActualOut,a.ManualIn,a.ManualOut,a.Description
                    FROM $this->_presence a
                    LEFT JOIN $this->_emp_personal b 
                    ON a.IDEmployee = b.IDEmployee
                    WHERE a.PresenceDate='$date' AND a.ActualIn is not null AND b.IDJobGroup IN('ST','LT','LK','HL','MAG','OS')  AND a.Description='A' AND b.Status='A' 
           ";
        
        $result = $this->_db_at_office->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return NUll;
        }
        
        
    }
     

    
    
    function update_presence($nip,$date,$record){
        $this->_db_at_office->where('IDEmployee',$nip);
        $this->_db_at_office->where('PresenceDate',$date);
        $this->_db_at_office->update($this->_presence,$record);
        
    }


	
   function getpersonal_byedit($nip,$datetime){
        $sql = "SELECT a.*,b.KTPRT,b.KTPRW FROM $this->_pbl_personal a
                LEFT JOIN isib_public.m01personal b
                ON a.IDEmployee = b.IDEmployee
                WHERE a.EditedBy ='$nip' AND a.EditedDate='$datetime'
                ORDER BY a.FullName";
        $result = $this->_db_emp->query($sql);
        if($result->num_rows()>0){
            return $result->result_array();
            
        }else{
            return NUll;
        }
        
    }
    
    
    function updatertrw_public($nip,$record){
       $this->_db_pbl->where('IDEmployee',$nip);     
       $this->_db_pbl->update($this->_pbl_personal,$record);
        
    }
    
    function updatertrw_employee($nip,$record){
       $this->_db_emp->where('IDEmployee',$nip);     
       $this->_db_emp->update($this->_personal_d,$record);
    }	
	

   function get_pblpersonal(){
        $sql ="SELECT a.IDEmployee,b.FullName,b.KTPAddress,b.CoupleName,b.NoKTP FROM $this->_emp_personal a
               LEFT JOIN $this->_pbl_personal b ON a.IDEmployee = b.IDEmployee
               WHERE a.Status='A'";
        
        $result = $this->_db_pbl->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
        
    }
    
    
    function checkspouse($nip,$record){        
       $this->_db_pbl->where('IDEmployee',$nip);
       $this->_db_pbl->where('FamilyMember','spouse');
       $result = $this->_db_pbl->get($this->_family);
       if($result->num_rows()>0){
           $this->update_family($nip,$record);           
       }else{
          $this->insert_family($record);
           
       }        
        
    }
    
    function insert_family($record){
        $this->_db_pbl->insert($this->_family,$record);
        
    }
    
    function update_family($nip,$record){
       $this->_db_pbl->where('IDEmployee',$nip);
       $this->_db_pbl->where('FamilyMember','spouse');
       $this->_db_pbl->update($this->_family,$record);
    }



    function get_holiday($from, $until) {
        $sql = "SELECT COUNT(*) AS jumlahlibur FROM $this->_holiday WHERE Date BETWEEN '$from' AND '$until'";
        $result = $this->_db_at_apps->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function getall_overtime() {
        $result = $this->_db_at_apps->get($this->_overtime);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function check_overtime($nip, $presence, $start, $end, $record) {
        $this->_db_at_office->where('IDEmployee', $nip);
        $this->_db_at_office->where('PresenceDate', $presence);
        $this->_db_at_office->where('OvertimeIn', $start);
        $this->_db_at_office->where('OvertimeOut', $end);
        $result = $this->_db_at_office->get($this->_overtime);
        if ($result->num_rows() > 0) {
            //$this->update_overtime($nip, $presence, $start, $end, $record);
        } else {
            $this->insert_overtime($record);
        }
    }

    function update_overtime($nip, $presence, $start, $end, $record) {
        $this->_db_at_office->where('IDEmployee', $nip);
        $this->_db_at_office->where('PresenceDate', $presence);
        $this->_db_at_office->where('OvertimeIn', $start);
        $this->_db_at_office->where('OvertimeOut', $end);
        $this->_db_at_office->update($this->_overtime, $record);
    }

    function insert_overtime($record) {
        $this->_db_at_office->insert($this->_overtime, $record);
    }

    function getall_incomplete() {
        $result = $this->_db_at_apps->get($this->_incomplete);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function check_incomplete($nip, $date, $in, $out, $record) {
        $this->_db_at_office->where('IDEmployee', $nip);
        $this->_db_at_office->where('IncompleteDate', $date);
        $this->_db_at_office->where('TimeIn', $in);
        $this->_db_at_office->where('TimeOut', $out);
        $result = $this->_db_at_office->get($this->_incomplete);
        if ($result->num_rows() > 0) {
            //$this->update_incomplete($nip, $date, $in, $out, $record);
        } else {
            $this->insert_incomplete($record);
        }
    }

    function update_incomplete($nip, $date, $in, $out, $record) {
        $this->_db_at_office->where('IDEmployee', $nip);
        $this->_db_at_office->where('IncompleteDate', $date);
        $this->_db_at_office->where('TimeIn', $in);
        $this->_db_at_office->where('TimeOut', $out);
        $this->_db_at_office->update($this->_incomplete, $record);
    }

    function insert_incomplete($record) {
        $this->_db_at_office->insert($this->_incomplete, $record);
    }

    function getall_sickness() {
        $sql = "SELECT a.*,
                (TO_DAYS(a.UntilDate)-TO_DAYS(a.SicknessDate)) AS SumDays
                FROM $this->_sickness a";

        $result = $this->_db_at_apps->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

     function getall_leave($from,$until) {
        $sql = "SELECT a.*,
                (TO_DAYS(a.UntilDate)-TO_DAYS(a.LeaveDate)) AS SumDays
                FROM $this->_leave a 
                WHERE LeaveDate BETWEEN '$from' AND '$until' ";

        $result = $this->_db_at_apps->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function check_leave($nip, $type, $fromdate, $untildate, $record) {
        $this->_db_emp->where('IDEmployee', $nip);
        $this->_db_emp->where('Jenis', $type);
        $this->_db_emp->where('TglCutiDari', $fromdate);
        $this->_db_emp->where('TglCutiSampai', $untildate);
        $result = $this->_db_emp->get($this->_leave_trx);
        if ($result->num_rows() > 0) {
            //$this->update_leave($nip, $type, $fromdate, $untildate, $record);
        } else {
            $this->insert_leave($record);
        }
    }

    function update_leave($nip, $type, $fromdate, $untildate, $record) {
        $this->_db_emp->where('IDEmployee', $nip);
        $this->_db_emp->where('Jenis', $type);
        $this->_db_emp->where('TglCutiDari', $fromdate);
        $this->_db_emp->where('TglCutiSampai', $untildate);
        $this->_db_emp->update($this->_leave_trx, $record);
    }

    function insert_leave($record) {
        $this->_db_emp->insert($this->_leave_trx, $record);
    }

    function getall_travel() {      
        $result = $this->_db_at_apps->get($this->_officialtravel);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function check_travel($nip, $from, $until, $record) {
        $this->_db_at_office->where('IDEmployee', $nip);
        $this->_db_at_office->where('OfficialTravelDate', $from);
        $this->_db_at_office->where('UntilDate', $until);
        $result = $this->_db_at_office->get($this->_officialtravel);
        if ($result->num_rows() > 0) {
            // $this->update_travel($nip, $from, $until, $record);
        } else {
            $this->insert_travel($record);
        }
    }

    function update_travel($nip, $from, $until, $record) {
        $this->_db_at_office->where('IDEmployee', $nip);
        $this->_db_at_office->where('OfficialTravelDate', $from);
        $this->_db_at_office->where('UntilDate', $until);
        $this->_db_at_office->update($this->_officialtravel, $record);
    }

    function insert_travel($record) {
        $this->_db_at_office->insert($this->_officialtravel, $record);
    }

    function getall_leavepermit() {
        $result = $this->_db_at_apps->get($this->_leavepermit);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function check_leavepermit($nip, $date, $out, $in, $record) {
        $this->_db_at_office->where('IDEmployee', $nip);
        $this->_db_at_office->where('LeavePermitDate', $date);
        $this->_db_at_office->where('OutDate', $out);
        $this->_db_at_office->where('InDate', $in);
        $result = $this->_db_at_office->get($this->_leavepermit);
        if ($result->num_rows() > 0) {
            // $this->update_leavepermit($nip, $date, $out, $in, $record);
        } else {
            $this->insert_leavepermit($record);
        }
    }

    function update_leavepermit($nip, $date, $out, $in, $record) {
        $this->_db_at_office->where('IDEmployee', $nip);
        $this->_db_at_office->where('LeavePermitDate', $date);
        $this->_db_at_office->where('OutDate', $out);
        $this->_db_at_office->where('InDate', $in);
        $this->_db_at_office->update($this->_leavepermit, $record);
    }

    function insert_leavepermit($record) {
        $this->_db_at_office->insert($this->_leavepermit, $record);
    }

}
?>

