<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Changenip_model extends CI_Model {

  
    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_secur = $this->load->database('security', TRUE);
        $this->_emp = $this->load->database('empcenter', TRUE);
        $this->_at = $this->load->database('attendance', TRUE);
        $this->_fld = $this->load->database('fieldpayroll', TRUE);
        $this->_pbl = $this->load->database('public', TRUE);
        $this->_est = $this->load->database('estimasi', TRUE);
        
        //Security
        $this->_logdata = 'h01logs';
        $this->_print = 'r04authorization_print';
        $this->_cancel = 'r05authorization_cancel';
        $this->_profile = 'r06userprofile';
        $this->_login = 't01userlogin';
        $this->_activation = 't02useractivation';
        $this->_button = 'user_buttonaccess';
        $this->_menu = 'user_menuaccess';
        
        
        //Employee
        $this->_personal_h= 'm01personal';
        $this->_personal_d= 'm01personal_d';
        $this->_other = 'm01personal_other';
        $this->_mleave = 'm02leave';
        $this->_rleave = 'm02leave_reserve';
        $this->_tleave = 't01leavetrx';
        $this->_eparam = 'm04param';
        $this->_memo = 't03memo';
        $this->_changenip = 'tmp01changenip';
        $this->_changenip_his = 'tmp01changenip_his';
        $this->_sendemail = 'tmp02sendmail';
        $this->_sendemail_his = 'tmp02sendmail_his';
        $this->_dept = 'm03organization';
       
        //Public
        $this->_personal= 'm01personal';
        $this->_course= 'm01personal_course';
        $this->_education= 'm01personal_education';
        $this->_family= 'm01personal_family';
        $this->_job= 'm01personal_job';
        $this->_language= 'm01personal_language';
        $this->_experience= 'm01personal_workexp';
        $this->_contact= 'tmp01contact';
        
        
        //Attendance
        $this->_cardmap = 'm02cardmap';
        $this->_rawdata = 't02rawdata';
        $this->_presence = 't03presence';
        $this->_overtime = 't04overtime';
        $this->_incomplete = 't05incomplete';
        $this->_sick = 't06sicknessleave';
        $this->_travel = 't07officialtravel';
        $this->_leavepermit = 't08leavepermit';
        $this->_suspension = 't10suspension';
        $this->_leavework = 't11leavework';
       
        
        //Payroll
        $this->_addovertime= 'additionalovertime';
        $this->_addleave= 'addtionalleave';
        $this->_personalpayroll= 'm01fieldpayroll';
        $this->_personalloan_h= 'm02personalloan_h';
        $this->_personalloan_d= 'm02personalloan_d';
        $this->_pinsentive= 'prm01insentive';
        $this->_dailysalary= 't01dailysalary';
        $this->_dailyovertime= 't02dailyovertime';
        $this->_addition= 't03addition';
        $this->_deduction= 't04deduction';
        $this->_mandeduction= 't04deductionmanual';
        $this->_payslip= 't05payrollslip';
        $this->_slip= 't05slip';
        
        
        //Estimasi
        $this->_estimator = 'r01estimator';
        $this->_request = 't03estimate_request';
        $this->_editor = 't04estimate_editor';
               
    }
    
    
 /*==============================START SECURITY ====================================== */   
    function get_logs($niplama){
        $this->_secur->where('username',$niplama);
        $result =  $this->_secur->get($this->_logdata);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
     function update_logdata($id, $record) {
        $this->_secur->where('ID', $id);
        $this->_secur->update($this->_logdata, $record);
    }
    
    
    function get_print($niplama){
        $this->_secur->where('IDEmployee',$niplama);
        $result =  $this->_secur->get($this->_print);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_print($id,$record){
       $this->_secur->where('ID',$id);
       $this->_secur->update($this->_print,$record);
        
    }
    
    
   function get_cancel($niplama){
        $this->_secur->where('IDEmployee',$niplama);
        $result =  $this->_secur->get($this->_cancel);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_cancel($id,$record){
       $this->_secur->where('ID',$id);
       $this->_secur->update($this->_cancel,$record);
        
    }
    
    
   function get_profile($niplama){
        $this->_secur->where('IDUser',$niplama);
        $result =  $this->_secur->get($this->_profile);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_profile($id,$record){
       $this->_secur->where('ID',$id);
       $this->_secur->update($this->_profile,$record);
        
    }
    
      
   function get_login($niplama){
        $this->_secur->where('Username',$niplama);
        $result =  $this->_secur->get($this->_login);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_login($id,$record){
       $this->_secur->where('ID',$id);
       $this->_secur->update($this->_login,$record);
        
    }
    
    
   function get_activation($niplama){
        $this->_secur->where('Username',$niplama);
        $result =  $this->_secur->get($this->_activation);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_activation($id,$record){
       $this->_secur->where('ID',$id);
       $this->_secur->update($this->_activation,$record);
        
    }
    
    function get_button($niplama){
        $this->_secur->where('IDUser',$niplama);
        $result =  $this->_secur->get($this->_button);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_button($id,$record){
       $this->_secur->where('ID',$id);
       $this->_secur->update($this->_button,$record);
        
    }
    
    function get_menu($niplama){
        $this->_secur->where('IDUser',$niplama);
        $result =  $this->_secur->get($this->_menu);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_menu($id,$record){
       $this->_secur->where('ID',$id);
       $this->_secur->update($this->_menu,$record);
        
    }
    
    /* =============================END SECURITY========================================= */
    
    
    /*============================START EMPLOYEE===========================================*/
    function get_personalh($niplama){
        $this->_emp->where('IDEmployee',$niplama);
        $result =  $this->_emp->get($this->_personal_h);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_personalh($id,$record){
       $this->_emp->where('ID',$id);
       $this->_emp->update($this->_personal_h,$record);
        
    }
    
    function getname($nipbaru){
        $this->_emp->where('IDEmployee',$nipbaru);
        $result =  $this->_emp->get($this->_personal_h);
        if($result->num_rows()>0){
             $row = $result->row();
             return $row->FullName;
        }else{
             return NULL;
        }
    }
    
    function get_personald($niplama){
        $this->_emp->where('IDEmployee',$niplama);
        $result =  $this->_emp->get($this->_personal_d);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_personald($id,$record){
       $this->_emp->where('ID',$id);
       $this->_emp->update($this->_personal_d,$record);
        
    }
    
    function get_fakes($niplama){
        $this->_emp->where('IDEmployee',$niplama);
        $result =  $this->_emp->get($this->_other);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_fakes($id,$record){
       $this->_emp->where('ID',$id);
       $this->_emp->update($this->_other,$record);
    }
    
    function get_mleave($niplama){
        $this->_emp->where('IDEmployee',$niplama);
        $result =  $this->_emp->get($this->_mleave);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_mleave($id,$record){
       $this->_emp->where('ID',$id);
       $this->_emp->update($this->_mleave,$record);
    }
    
    function get_rleave($niplama){
        $this->_emp->where('IDEmployee',$niplama);
        $result =  $this->_emp->get($this->_rleave);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_rleave($id,$record){
       $this->_emp->where('ID',$id);
       $this->_emp->update($this->_rleave,$record);
    }
    
    function get_tleave($niplama){
        $this->_emp->where('IDEmployee',$niplama);
        $result =  $this->_emp->get($this->_tleave);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_tleave($id,$record){
       $this->_emp->where('IDLeave',$id);
       $this->_emp->update($this->_tleave,$record);
    }
  
    function get_eparam($niplama){
        $this->_emp->where('ParamValue',$niplama);
        $result =  $this->_emp->get($this->_eparam);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_eparam($id,$record){
       $this->_emp->where('ID',$id);
       $this->_emp->update($this->_eparam,$record);
    }
    
    function get_memo($niplama){
        $this->_emp->where('FromIDUser',$niplama);
        $result =  $this->_emp->get($this->_memo);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_memo($id,$record){
       $this->_emp->where('ID',$id);
       $this->_emp->update($this->_memo,$record);
    }
   
    function get_memo2($niplama){
        $this->_emp->where('ToIDUser',$niplama);
        $result =  $this->_emp->get($this->_memo);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_memo2($id,$record){
       $this->_emp->where('ID',$id);
       $this->_emp->update($this->_memo,$record);
    }
    
    
    
    /*=============================END EMPLOYEE=============================================*/
    
    
    /*=============================START PUBLIC==============================================*/
     function get_person($niplama){
        $this->_pbl->where('IDEmployee',$niplama);
        $result =  $this->_pbl->get($this->_personal);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_person($id,$record){
       $this->_pbl->where('ID',$id);
       $this->_pbl->update($this->_personal,$record);
    }
    
    function get_course($niplama){
        $this->_pbl->where('IDEmployee',$niplama);
        $result =  $this->_pbl->get($this->_course);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_course($id,$record){
       $this->_pbl->where('ID',$id);
       $this->_pbl->update($this->_course,$record);
    }
    
    function get_education($niplama){
        $this->_pbl->where('IDEmployee',$niplama);
        $result =  $this->_pbl->get($this->_education);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_education($id,$record){
       $this->_pbl->where('ID',$id);
       $this->_pbl->update($this->_education,$record);
    }
    
     function get_family($niplama){
        $this->_pbl->where('IDEmployee',$niplama);
        $result =  $this->_pbl->get($this->_family);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_family($id,$record){
       $this->_pbl->where('ID',$id);
       $this->_pbl->update($this->_family,$record);
    }
    
     function get_job($niplama){
        $this->_pbl->where('IDEmployee',$niplama);
        $result =  $this->_pbl->get($this->_job);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_job($id,$record){
       $this->_pbl->where('ID',$id);
       $this->_pbl->update($this->_job,$record);
    }
    
    function get_lang($niplama){
        $this->_pbl->where('IDEmployee',$niplama);
        $result =  $this->_pbl->get($this->_language);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_lang($id,$record){
       $this->_pbl->where('ID',$id);
       $this->_pbl->update($this->_language,$record);
    }
    
    function get_exp($niplama){
        $this->_pbl->where('IDEmployee',$niplama);
        $result =  $this->_pbl->get($this->_experience);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_exp($id,$record){
       $this->_pbl->where('ID',$id);
       $this->_pbl->update($this->_experience,$record);
    }
    
    function get_contact($niplama){
        $this->_pbl->where('IDEmployee',$niplama);
        $result =  $this->_pbl->get($this->_contact);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_contact($id,$record){
       $this->_pbl->where('IDEmployee',$id);
       $this->_pbl->update($this->_contact,$record);
    }
    
    /*=============================END PUBLIC==============================================*/
   
    
    
    /*=============================START ATTENDANCE==============================================*/
   
     function get_card($niplama){
        $this->_at->where('IDEmployee',$niplama);
        $result =  $this->_at->get($this->_cardmap);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_card($id,$record){
       $this->_at->where('ID',$id);
       $this->_at->update($this->_cardmap,$record);
    }
    
    function get_rawdata($niplama){
        $this->_at->where('IDEmployee',$niplama);
        $result =  $this->_at->get($this->_rawdata);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_rawdata($id,$record){
       $this->_at->where('ID',$id);
       $this->_at->update($this->_rawdata,$record);
    }
    
    
    function get_presence($niplama){
        $this->_at->where('IDEmployee',$niplama);
        $result =  $this->_at->get($this->_presence);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_presence($id,$record){
       $this->_at->where('IDPresence',$id);
       $this->_at->update($this->_presence,$record);
    }
    
    
    function get_overtime($niplama){
        $this->_at->where('IDEmployee',$niplama);
        $result =  $this->_at->get($this->_overtime);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_overtime($id,$record){
       $this->_at->where('ID',$id);
       $this->_at->update($this->_overtime,$record);
    }
    
    
    function get_incomplete($niplama){
        $this->_at->where('IDEmployee',$niplama);
        $result =  $this->_at->get($this->_incomplete);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_incomplete($id,$record){
       $this->_at->where('ID',$id);
       $this->_at->update($this->_incomplete ,$record);
    }
    
    
     function get_sick($niplama){
        $this->_at->where('IDEmployee',$niplama);
        $result =  $this->_at->get($this->_sick);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_sick($id,$record){
       $this->_at->where('ID',$id);
       $this->_at->update($this->_sick ,$record);
    }
    
    function get_travel($niplama){
        $this->_at->where('IDEmployee',$niplama);
        $result =  $this->_at->get($this->_travel);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_travel($id,$record){
       $this->_at->where('ID',$id);
       $this->_at->update($this->_travel ,$record);
    }
    
   function get_leavepermit($niplama){
        $this->_at->where('IDEmployee',$niplama);
        $result =  $this->_at->get($this->_leavepermit);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_leavepermit($id,$record){
       $this->_at->where('ID',$id);
       $this->_at->update($this->_leavepermit,$record);
    }
    
    
     function get_suspension($niplama){
        $this->_at->where('IDEmployee',$niplama);
        $result =  $this->_at->get($this->_suspension);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_suspension($id,$record){
       $this->_at->where('ID',$id);
       $this->_at->update($this->_suspension,$record);
    }
    
    function get_leavework($niplama){
        $this->_at->where('IDEmployee',$niplama);
        $result =  $this->_at->get($this->_leavework);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_leavework($id,$record){
       $this->_at->where('ID',$id);
       $this->_at->update($this->_leavework,$record);
    }
    
    
    
    
    /*=============================END ATTENDANCE==============================================*/
    
    
    
    
    /*=============================START PAYROLL==============================================*/
    function get_addovertime($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_addovertime);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_addovertime($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_addovertime,$record);
    }
    
    function get_addleave($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_addleave);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_addleave($id,$record){
       $this->_fld->where('IDEmployee',$id);
       $this->_fld->update($this->_addleave,$record);
    }
    
    function get_personpay($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_personalpayroll);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_personpay($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_personalpayroll,$record);
    }
    
    function get_person_loanh($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_personalloan_h);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_person_loanh($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_personalloan_h,$record);
    }
    
    function get_person_loand($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_personalloan_d);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_person_loand($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_personalloan_d,$record);
    }
    
    function get_pinsentive($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_pinsentive);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_pinsentive($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_pinsentive,$record);
    }
    
    function get_dailysalary($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_dailysalary);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_dailysalary($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_dailysalary,$record);
    }
    
    function get_dailyovertime($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_dailyovertime);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_dailypvertime($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_dailyovertime,$record);
    }
    
   function get_addition($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_addition);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_addition($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_addition,$record);
    }
   function get_deduc($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_deduction);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_deduc($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_deduction,$record);
    }
    
    function get_mandeduc($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_mandeduction);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_mandeduc($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_mandeduction,$record);
    }
    
     function get_payslip($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_payslip);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_payslip($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_payslip,$record);
    }
    
    function get_slip($niplama){
        $this->_fld->where('IDEmployee',$niplama);
        $result =  $this->_fld->get($this->_slip);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_slip($id,$record){
       $this->_fld->where('ID',$id);
       $this->_fld->update($this->_slip,$record);
    }
    
    
    /*=============================END PAYROLL==============================================*/
  
    
    /*=============================START ESTIMATES==============================================*/
    
    function get_estimator($niplama){
        $this->_est->where('IDEmployee',$niplama);
        $result =  $this->_est->get($this->_estimator);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_estimator($id,$record){
       $this->_est->where('ID',$id);
       $this->_est->update($this->_estimator,$record);
    }
    
    
     function get_request($niplama){
        $this->_est->where('ProjectExecutive',$niplama);
        $result =  $this->_est->get($this->_request);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
     function get_request2($niplama){
        $this->_est->where('RequestBy',$niplama);
        $result =  $this->_est->get($this->_request);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_request($id,$record){
       $this->_est->where('ID',$id);
       $this->_est->update($this->_request,$record);
    }
    
    function get_editor($niplama){
        $this->_est->where('IDEmployee',$niplama);
        $result =  $this->_est->get($this->_editor);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return null;
        }
    }
    
    function update_editor($id,$record){
       $this->_est->where('ID',$id);
       $this->_est->update($this->_editor,$record);
    }
    
    
    
    /*=============================END ESTIMATES==============================================*/
       
    
    
    function getdata() {
        $a = 'isib_employee.'.$this->_changenip;
        $b = 'isib_employee.'.$this->_personal_h;
        $this->datatables->select("$a.ID AS ID,
                                 $a.NIPLama AS NIPLama, 
                                 $b.FullName AS FullName,    
                                 $a.NIPBaru AS NIPBaru,
				 $a.Note AS Note,
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.NIPLama = $b.IDEmployee", 'left');
        return $this->datatables->generate();
    }
    
    function getdata_changenip(){
       $result = $this->_emp->get($this->_changenip);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        } 
    }
    
    function getlist_email(){
        $sql = "
                SELECT a.*,b.IDJobGroup ,c.DescStructure AS Departemen
                FROM $this->_sendemail a
                LEFT JOIN  $this->_personal_h b ON a.NIPBaru = b.IDEmployee 
                LEFT JOIN  $this->_dept c ON b.IDDepartement = c.IDStructure
                ";
        $result= $this->_emp->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        } 
    }
    
    function truncate_changenip(){
       $this->_emp->truncate($this->_changenip);
    }
    
    function truncate_sendmail(){
       $this->_emp->truncate($this->_sendemail);
    }
    
    function checkdata($niplama) {
        $this->_emp->where('NIPLama', $niplama);
        $result = $this->_emp->get($this->_changenip);
        if ($result->num_rows() > 0) {
            return 'exist';
        } else {
            return 'empty';
        }
    }

    function insert($record) {
        $this->_emp->insert($this->_changenip, $record);
    }
    function insert_mail($record) {
        $this->_emp->insert($this->_sendemail, $record);
    }
    
    function insert_his($record){
         $this->_emp->insert($this->_changenip_his, $record);
    }
    
    function insertmail_his($record){
         $this->_emp->insert($this->_sendemail_his, $record);
    }


}
?>


