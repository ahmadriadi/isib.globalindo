<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monthlyprocess_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('fieldpayroll', TRUE);

        //table master employee
        $this->_personal = 'triasnet_employee.m01personal';

        //table master field payroll
        $this->_fieldpersonal = 'm01fieldpayroll';
        $this->_loan_h = 'm02personalloan_h';
        $this->_loan_d = 'm02personalloan_d';

        //table transaction field payroll
        $this->_dailysalary = 't01dailysalary';
        $this->_dailyovertime = 't02dailyovertime';
        $this->_additional = 't03addition';
        $this->_deduction = 't04deduction';
        $this->_mandeduction = 't04deductionmanual';
        $this->_payslip = 't05payrollslip';
        $this->_insentif = 't06insentif';

        //table transaction attendance
        $this->_presence = 'triasnet_attendance.t03presence';
        $this->_overtime = 'triasnet_attendance.t04overtime';
    }

    function delete_dailysalary($posting, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($chekdata == 'exist') {
            $where = "WHERE  PostingDate ='$posting' AND IDEmployee ='$nip'";
        } else {
            $where = "WHERE  PostingDate ='$posting'";
        }
        $sql = "DELETE FROM $this->_dailysalary $where ";
        $this->_db->query($sql);
    }

    function delete_dailyovertime($posting, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($chekdata == 'exist') {
            $where = "WHERE  PostingDate ='$posting' AND IDEmployee ='$nip'";
        } else {
            $where = "WHERE  PostingDate ='$posting'";
        }
        $sql = "DELETE FROM  $this->_dailyovertime $where ";
        $this->_db->query($sql);
    }

    function delete_deduction($posting, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($chekdata == 'exist') {
            $where = "WHERE  PostingDate ='$posting' AND IDEmployee ='$nip'";
        } else {
            $where = "WHERE  PostingDate ='$posting'";
        }
        $sql = "DELETE FROM $this->_deduction $where ";
        $this->_db->query($sql);
    }

    function delete_payslip($posting, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($chekdata == 'exist') {
            $where = "WHERE  PostingDate ='$posting' AND IDEmployee ='$nip'";
        } else {
            $where = "WHERE  PostingDate ='$posting'";
        }
        $sql = "DELETE FROM $this->_payslip $where ";
        $this->_db->query($sql);
    }

    function getperiode_loanmanual($from, $until, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';

        if ($chekdata == 'exist') {
            $where = "  WHERE
                        a.IDEmployee ='$nip' AND a.DeleteFlag='A' AND
                        a.PostingDate BETWEEN '$from' AND '$until' AND
                        (b.IDJobGroup IN ('LT','LK','HL','MAG','OS')) AND
                        (b.Status='A' OR (b.Status='P' AND (b.ResignDate BETWEEN '$from' AND '$until')))
                    ";
        } else {
            $where = "  WHERE                       
                         a.PostingDate BETWEEN '$from' AND '$until' AND a.DeleteFlag='A' AND
                         (b.IDJobGroup IN ('LT','LK','HL','MAG','OS')) AND
                         (b.Status='A' OR (b.Status='P' AND (b.ResignDate BETWEEN '$from' AND '$until')))
                    ";
        }

        $query = "
          SELECT  a.*, b.IDEmployee
          FROM  $this->_mandeduction a
          LEFT JOIN $this->_personal b
          ON b.IDEmployee = a.IDEmployee
          $where  ";

        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return false;
    }

    function getperiode_loansystem($from, $until, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';

        if ($chekdata == 'exist') {
            $where = "
                     WHERE
                        a.IDEmployee ='$nip' AND 
                        a.InstallmentDate BETWEEN '$from' AND '$until' AND
                        (b.IDJobGroup IN ('LT','LK','HL','MAG','OS')) AND
                        (b.Status='A' OR (b.Status='P' AND (b.ResignDate BETWEEN '$from' AND '$until')))
                    ";
        } else {
            $where = "
                     WHERE
                        a.InstallmentDate BETWEEN '$from' AND '$until' AND
                        (b.IDJobGroup IN ('LT','LK','HL','MAG','OS')) AND
                        (b.Status='A' OR (b.Status='P' AND (b.ResignDate BETWEEN '$from' AND '$until')))
                    ";
        }


        $query = "
          SELECT  a.*, b.IDEmployee
          FROM  $this->_loan_d a
          LEFT JOIN $this->_personal b
          ON b.IDEmployee = a.IDEmployee
          $where ";

        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return false;
    }

    function check_deduction_system($posting, $id, $parameter, $flag, $idrecord) {
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('Parameter', $parameter);
        $this->_db->where('FlagLoan', $flag);
        $this->_db->where('IDRecord', $idrecord);
        $query = $this->_db->get($this->_deduction);
        if ($query->num_rows() > 0) {
            return 'exist';
        } else {
            return null;
        }
    }

    function update_deduction_system($posting, $id, $parameter, $flag, $idrecord, $record) {
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('Parameter', $parameter);
        $this->_db->where('FlagLoan', $flag);
        $this->_db->where('IDRecord', $idrecord);
        $this->_db->update($this->_deduction, $record);
    }

    function insert_deduction($record) {
        $this->_db->insert($this->_deduction, $record);
    }

    function update_loan_system($id, $nip, $date, $record) {
        $this->_db->where('IDHeader', $id);
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('InstallmentDate', $date);
        $this->_db->update($this->_loan_d, $record);
    }

    function sum_deduc($posting, $from, $param, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        $chekdata2 = ($param == '' or $param == null) ? 'empty' : 'exist';
        if ($chekdata == 'exist') {
            if ($chekdata2 == 'exist') {
                $where = "WHERE PostingDate BETWEEN '$from' AND '$posting' AND IDEmployee='$nip' AND Parameter='$param'";
            } else {
                $where = "WHERE PostingDate BETWEEN '$from' AND '$posting' AND IDEmployee='$nip'";
            }
        } else {
            if ($chekdata2 == 'exist') {
                $where = "WHERE DeleteFlag='A' AND PostingDate BETWEEN '$from' AND '$posting' AND Parameter='$param'";
            } else {
                $where = "WHERE DeleteFlag='A' AND PostingDate BETWEEN '$from' AND '$posting'";
            }
        }


        $query =
                "SELECT 
            IDEmployee,
            sum(Amount)  AS Amount 
         FROM 
            $this->_deduction  
            $where    
        GROUP BY
            IDEmployee";

        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_insentif($nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($chekdata == 'exist') {
            $where = "WHERE DeleteFlag='A' AND IDEmployee =$nip AND Status='A'";
        } else {
            $where = "WHERE DeleteFlag='A' AND Status='A'";
        }

        $sql = "SELECT * FROM $this->_insentif $where";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return false;
    }

    function check_insentif($nip, $date, $param, $flag) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PostingDate', $date);
        $this->_db->where('Parameter', $param);
        $this->_db->where('FlagEntry', $flag);
        $this->_db->where('DeleteFlag', 'A');
        $result = $this->_db->get($this->_additional);
        if ($result->num_rows() > 0) {
            return 'exist';
        } else {
            return null;
        }
    }

    function insert_insentif_system($record) {
        $this->_db->insert($this->_additional, $record);
    }

    function update_insentif_system($nip, $date, $param, $flag, $record) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PostingDate', $date);
        $this->_db->where('Parameter', $param);
        $this->_db->where('FlagEntry', $flag);
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->update($this->_additional, $record);
    }

    function sum_addition($posting, $param, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        $chekdata2 = ($param == '' or $param == null) ? 'empty' : 'exist';
        if ($chekdata == 'exist') {
            if ($chekdata2 == 'exist') {
                $where = "WHERE DeleteFlag='A' AND PostingDate ='$posting' AND IDEmployee='$nip' AND Parameter='$param'";
            } else {
                $where = "WHERE DeleteFlag='A' AND PostingDate ='$posting' AND IDEmployee='$nip'";
            }
        } else {
            if ($chekdata2 == 'exist') {
                $where = "WHERE DeleteFlag='A' AND PostingDate ='$posting' AND Parameter='$param'";
            } else {
                $where = "WHERE DeleteFlag='A' AND PostingDate ='$posting'";
            }
        }

        $query =
                "SELECT 
            PostingDate,     
            IDEmployee,
            sum(Amount)  AS Amount 
         FROM 
            $this->_additional
            $where 
        GROUP BY
            IDEmployee,
            PostingDate";

        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function getdata_presence($from, $until, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';

        if ($chekdata == 'exist') {
            $where = "
                    WHERE                        
                        (a.IDEmployee ='$nip' AND a.PresenceDate BETWEEN '$from' AND '$until') AND
                        (b.IDJobGroup IN ('LT','LK','HL','MAG','OS'))  AND
                        ( 
                          (b.Status = 'A') OR 
                          (b.Status = 'P') AND (b.ResignDate BETWEEN '$from' AND '$until') 
                        )
                ";
        } else {
            $where = "
                    WHERE 
                        (a.PresenceDate BETWEEN '$from' AND '$until') AND
                        (b.IDJobGroup IN ('LT','LK','HL','MAG','OS'))  AND
                        ( 
                          (b.Status = 'A') OR 
                          (b.Status = 'P') AND (b.ResignDate BETWEEN '$from' AND '$until') 
                        )
                ";
        }


        $sql =
                "SELECT 
            a.IDEmployee,
            b.FullName,b.IDJobGroup,b.HireDate,b.ResignDate,
            a.PresenceDate,a.WorkDay,a.ActualIn,a.ActualOut,a.ManualIn,a.ManualOut,
            a.Description, a.Necessity,
	    c.DailySalary,c.MonthlySalary,c.Insurance  
            
         FROM  $this->_presence a            
         JOIN  $this->_personal b            
         ON    b.IDEmployee=a.IDEmployee 
         JOIN  $this->_fieldpersonal c           
         ON    c.IDEmployee=a.IDEmployee
         $where 
         ORDER BY 
            b.IDJobGroup DESC,
            b.FullName ASC,
            a.PresenceDate ASC
        ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function check_dailysalary($posting, $id, $presence) {
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('PresenceDate', $presence);
        $query = $this->_db->get($this->_dailysalary);
        if ($query->num_rows() > 0) {
            return 'exist';
        } else {
            return null;
        }
    }

    function insert_dailysalary($record) {
        $this->_db->insert($this->_dailysalary, $record);
    }

    function update_dailysalary($posting, $id, $presence, $record) {
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('PresenceDate', $presence);
        $this->_db->update($this->_dailysalary, $record);
    }

    function sum_salary($posting, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($chekdata == 'exist') {
            $where = "WHERE DeleteFlag ='A' AND PostingDate='$posting'  AND IDEmployee='$nip'";
        } else {
            $where = "WHERE DeleteFlag ='A' AND PostingDate='$posting'";
        }

        $query =
                "SELECT 
            PostingDate,     
            IDEmployee,
            sum(DailySalaryPayment) AS AbsenPayment,
            sum(MontlyPayment) AS MontlySalary,          
            sum(DailyIncentiveShift) AS SumIncentiveShift,
            sum(InsurancePayment)    AS SumInsurancePayment 
         FROM 
            $this->_dailysalary         
            $where
         GROUP BY
            IDEmployee,
            PostingDate";
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function getdata_overtime($from, $until, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';

        if ($chekdata == 'exist') {
            $where = "  
                    WHERE
                    a.IDEmployee='$nip' AND
                    a.DeleteFlag ='A' AND ConfirmFlag='1' AND    
                    a.PresenceDate BETWEEN '$from' AND '$until' AND
                    (b.IDJobGroup IN ('LT','LK','HL','MAG','OS')) AND
                    (b.Status='A' OR (b.Status='P' AND (b.ResignDate BETWEEN '$from' AND '$until')))
                  
                    ";
        } else {
            $where = "  
                    WHERE 
                    a.DeleteFlag ='A' AND ConfirmFlag='1' AND
                    a.PresenceDate BETWEEN '$from' AND '$until' AND
                    (b.IDJobGroup IN ('LT','LK','HL','MAG','OS')) AND
                    (b.Status='A' OR (b.Status='P' AND (b.ResignDate BETWEEN '$from' AND '$until')))
                  
                    ";
        }

        $query =
                "SELECT
           a.ID AS NO_SPKL, a.IDSPKL AS SUB_SPKL, a.IDEmployee AS IDEmployee,
           b.FullName AS FullName,b.IDJobGroup AS IDJobGroup,
           a.PresenceDate AS PresenceDate,a.OvertimeIn AS OvertimeIn,a.OvertimeOut AS OvertimeOut,a.Note AS Notes,
           c.ActualIn AS ActualIn,c.ActualOut AS ActualOut,c.ManualIn AS ManualIn,c.ManualOut AS ManualOut,c.WorkDay AS WorkDay,c.Description AS Description,           
           d.OvertimePerHour AS OvertimePerHour 
           
         FROM $this->_overtime a           
         LEFT JOIN $this->_personal b          
         ON b.IDEmployee = a.IDEmployee           
         LEFT JOIN $this->_presence c          
         ON
           c.IDEmployee   = a.IDEmployee AND
           c.PresenceDate = a.PresenceDate           
         LEFT JOIN $this->_fieldpersonal d           
         ON  d.IDEmployee = a.IDEmployee          
         $where      
         ORDER BY
           b.IDJobGroup DESC,
           b.FullName ASC,
           a.PresenceDate ASC";

        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function check_dailyovertime($posting, $id, $presence, $OvertimeIn) {
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('PresenceDate', $presence);
        $this->_db->where('OvertimeIn', $OvertimeIn);
        $query = $this->_db->get($this->_dailyovertime);
        if ($query->num_rows() > 0) {
            return 'exist';
        } else {
            return null;
        }
    }

    function insert_dailyovertime($record) {
        $this->_db->insert($this->_dailyovertime, $record);
    }

    function update_dailyovertime($posting, $id, $presence, $OvertimeIn, $record) {
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('PresenceDate', $presence);
        $this->_db->where('OvertimeIn', $OvertimeIn);
        $this->_db->update($this->_dailyovertime, $record);
    }

    function sum_overtime($posting, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($chekdata == 'exist') {
            $where = "WHERE DeleteFlag ='A' AND PostingDate='$posting'  AND IDEmployee='$nip'";
        } else {
            $where = "WHERE DeleteFlag ='A' AND PostingDate='$posting'";
        }

        $query =
                "SELECT 
            PostingDate,     
            IDEmployee,
            sum(DailyOvertimePayment) AS SumOvertimePayment
         FROM 
           $this->_dailyovertime
           $where
         GROUP BY
            IDEmployee,
            PostingDate";
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function getdata_payslip($posting) {
        $this->_db->where('PostingDate', $posting);
        $query = $this->_db->get($this->_payslip);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return NULL;
        }
    }

    function check_payslip($posting, $id) {
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $id);
        $query = $this->_db->get($this->_payslip);
        if ($query->num_rows() > 0) {
            return 'exist';
        } else {
            return null;
        }
    }

    function insert_payslip($record) {
        $this->_db->insert($this->_payslip, $record);
    }

    function update_payslip($posting, $id, $record) {
        $this->_db->where('PostingDate', $posting);
        $this->_db->where('IDEmployee', $id);
        $this->_db->update($this->_payslip, $record);
    }

}
?>


