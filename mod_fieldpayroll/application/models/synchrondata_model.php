<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Synchrondata_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('fieldpayroll', TRUE);
        $this->_personal_employee = 'triasnet_employee.m01personal_d';
        $this->_personal_public = 'triasnet_public.m01personal';
        $this->_personal_payroll = 'm01fieldpayroll';
        $this->_personbpjs = 'tmp01userbpjs';
        $this->_tmpbpjs = "tmp03userbpjs24062015";
        $this->_point = "prm02payroll";
        $this->_paid = "tmpnotyetpaid";
        $this->_loan_d = "m02personalloan_d";
        $this->_loan_h = "m02personalloan_h";	
    }
    
        
    
    
    function getloan_h(){
        $this->_db->where('DeleteFlag','A'); 
        $this->_db->where('FlagPaid','0');  	
        return $this->_db->get($this->_loan_h)->result_array();
    }
    
    function getterm_detail($id){
    	$this->_db->where('IDHeader',$id);
        $this->_db->where('DeleteFlag','A'); 
        $this->_db->where('Flag','1');  	
        return $this->_db->count_all_results($this->_loan_d);    
    }
    
     function update_loan_h($id,$record) {
        $this->_db->where('ID', $id);       
        $this->_db->update($this->_loan_h , $record);
    }    
    
     function update_loan_d($nip,$posting,$record) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('InstallmentDate', $posting);
        $this->_db->update($this->_loan_d , $record);
    }
    
    function gettmpt(){
       return $this->_db->get($this->_paid)->result_array();
    }
    
    function getpoint($param){
        if($param=='bpjs'){
            $on = 'BPJSPercent';
        }else if($param=='insurance'){
             $on = 'InsurancePercent';
        }else if($param=='daily'){
             $on = 'SumDaySalary';
        }else if($param=='overtime'){
             $on = 'OvertimeWorkHour';
        }
        
        $sql = "SELECT * FROM $this->_point";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
           $row = $result->row();
           return $row->$on;
        } else {
            return 'empty';
        }
        
    }
    
    
        
    function getpersonal_bpjs() {
        $sql = "SELECT * FROM m01fieldpayroll WHERE BPJS NOT IN ('','0')";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return 'empty';
        }
    }

    function getpersonal_frombpjs($name) {
        $this->_db->where('FullName', $name);
        $this->_db->order_by('ID', 'DESC');
        $result = $this->_db->get($this->_personal_employee);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
    }

    function gettemp_bpjs() {
        $result = $this->_db->get($this->_personbpjs);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return 'empty';
        }
    }

    function sendtotemp_bpjs($nip, $record) {
        $this->_db->where('IDEmployee', $nip);
        $result = $this->_db->get($this->_tmpbpjs, $record);
        if ($result->num_rows() > 0) {
            $this->update_tmpbpjs($nip, $record);
        } else {
            $this->insert_tmpbpjs($record);
        }
    }

    function insert_tmpbpjs($record) {
        $this->_db->insert($this->_tmpbpjs, $record);
    }

    function update_tmpbpjs($nip, $record) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->update($this->_tmpbpjs, $record);
    }

    function get_tmpbpjs() {
//$result = $this->_db->get($this->_tmpbpjs);

        $sql = "SELECT a.IDEmployee,a.FullName,b.IDJobGroup,a.MonthlySalary FROM $this->_tmpbpjs a "
                . " INNER JOIN $this->_personal_employee b  ON a.IDEmployee=b.IDEmployee "
                . " WHERE b.IDJobGroup NOT IN ('ST') ";

        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return 'empty';
        }
    }

    function check_personalpayroll($nip) {
        $this->_db->where('IDEmployee', $nip);
        $result = $this->_db->get($this->_personal_payroll);
        if ($result->num_rows() > 0) {
            return 'exist';
        } else {
            return 'empty';
        }
    }

    function getpersonal_payroll() {
        return $this->_db->get($this->_personal_payroll)->result_array();
    }

    function get_employee() {
        $sql = " SELECT a.*,b.IDEmployee,b.FullName FROM $this->_tmpbpjs a
                    JOIN $this->_personal_employee b ON a.IDEmployee = b.IDEmployee
                    WHERE 
                    b.DeleteFlag ='A' AND
                    b.Status ='A'
                   ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function getall_employee_bitung() {
        $sql = "
            SELECT a.IDEmployee,b.FullName,b.IDLocation,b.IDJobGroup,a.MonthlySalary 
            FROM $this->_personal_payroll a
            LEFT JOIN $this->_personal_employee b
            ON a.IDEmployee = b.IDEmployee
            WHERE 
            a.MonthlySalary='2441000' AND 
            a.DeleteFlag ='A' AND
            b.DeleteFlag ='A' AND
            b.Status ='A' AND
            b.IDLocation ='2'            
          ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function update_monthly($nip, $record) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->update($this->_personal_payroll, $record);
    }

}
?>


