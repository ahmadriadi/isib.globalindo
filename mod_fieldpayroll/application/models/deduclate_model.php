<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Deduclate_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('fieldpayroll', TRUE);
        $this->_db_at = $this->load->database('attendance', TRUE);
        $this->_dailysalary = 't01dailysalary';
        $this->_personal = 'triasnet_employee.m01personal';
        $this->_fieldpersonal = 'm01fieldpayroll';
        $this->_presence = 'triasnet_attendance.t03presence';
        $this->_table = 't07deduclate';
    }

    function getdata($from, $until) {
        $a = $this->_table;
        $b = 'triasnet_employee.m01personal';
        $this->datatables->select("$a.ID AS ID,
                                 $a.IDEmployee AS IDEmployee,
				 $b.IDJobGroup AS IDJobGroup,
                                 $a.EstimateShift AS EstimateShift,
                                 $a.PresenceDate AS PresenceDate,
                                 $a.PostingDate AS PostingDate,
                                 $a.LateTime AS LateTime,
                                 $a.LateHour AS LateHour,
                                 $b.FullName AS FullName,
                                 $a.DeducAmount AS DeducAmount,
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
				 IF($b.IDJobGroup ='MAG','MAGANG',
				 IF($b.IDJobGroup ='OS','MITRA KERJA',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN','-')))))) AS JobGroup       
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$a.PresenceDate >=", $from);
        $this->datatables->where("$a.PresenceDate <=", $until);
        $this->datatables->where("$b.DeleteFlag","A"); 

        $this->datatables->edit_column('DeducAmount', '$1', "number_format(DeducAmount,'2',',','.')");

        return $this->datatables->generate();
    }

    function getall_data($from, $until, $g) {
        $this->_db->from($this->_table . ' a');
        $this->_db->join($this->_personal . ' b', 'b.IDEmployee = a.IDEmployee', 'LEFT');
        $this->_db->where('a.PresenceDate >=', $from);
        $this->_db->where('a.PresenceDate  <=', $until);
        $this->_db->where("$b.DeleteFlag","A"); 
        ($g == 'AL') ? $this->_db->where_in('b.IDJobGroup', array('LT', 'LK', 'MAG', 'OS')) : $this->_db->where('b.IDJobGroup', $g);
        $this->_db->order_by('b.IDJobGroup', 'DESC');
        $this->_db->order_by('b.FullName', 'ASC');
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return 'empty';
        }
    }

    function getdata_presence_for_late($from, $until, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';

        if ($chekdata == 'exist') {
            $where = "
                    WHERE                        
                        (a.IDEmployee ='$nip' AND a.PresenceDate BETWEEN '$from' AND '$until' AND WorkDay NOT IN('SUN','OFF')) AND
                        (b.IDJobGroup IN ('LT','LK','HL','MAG','OS'))  AND
                        ( 
                          (b.Status = 'A' AND b.DeleteFLag='A') OR 
                          (b.Status = 'P') AND (b.ResignDate BETWEEN '$from' AND '$until') 
                        )
                ";
        } else {
            $where = "
                    WHERE 
                        (a.PresenceDate BETWEEN '$from' AND '$until' AND WorkDay NOT IN('SUN','OFF')) AND
                        (b.IDJobGroup IN ('LT','LK','HL','MAG','OS'))  AND
                        ( 
                          (b.Status = 'A' AND b.DeleteFLag='A') OR 
                          (b.Status = 'P') AND (b.ResignDate BETWEEN '$from' AND '$until') 
                        )
                ";
        }


        $sql =
                "SELECT 
            a.IDEmployee,
            b.FullName,b.IDJobGroup,b.HireDate,b.ResignDate,
            a.PresenceDate,a.WorkDay,a.Necessity,a.ActualIn,a.ActualOut,a.ManualIn,a.ManualOut,a.IMKOut,a.LateHour,a.WorkHour,
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
        } else {
            return NULL;
        }
    }

    function get_summarylate($posting, $nip) {
        $chekdata = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($chekdata == 'exist') {
            $where = "WHERE PostingDate='$posting' AND IDEmployee='$nip'";
        } else {
            $where = "WHERE PostingDate='$posting'";
        }

        $sql = " SELECT IDEmployee, sum(DeducAmount)  AS Amount FROM $this->_table $where GROUP BY IDEmployee";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return NULL;
        }
    }

    function insertorupdate($idemployee, $postingdate, $date, $record) {
        $this->_db->where('IDEmployee', $idemployee);
        $this->_db->where('PostingDate', $postingdate);
        $this->_db->where('PresenceDate', $date);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            $this->update($idemployee, $postingdate, $date, $record);
        } else {
            $this->insert($record);
        }
    }

    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }

    function update($idemployee, $postingdate, $date, $record) {
        $this->_db->where('IDEmployee', $idemployee);
        $this->_db->where('PostingDate', $postingdate);
        $this->_db->where('PresenceDate', $date);
        $this->_db->update($this->_table, $record);
    }

}
?>


