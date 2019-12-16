<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db_emp = $this->load->database('empcenter', TRUE);
        $this->_db_at = $this->load->database('attendance', TRUE);
        $this->_prs = 'm01personal';
        $this->_presence = 't03presence';
        $this->_overtime = 't04overtime';
        $this->_mleave = 'm02leave';
        $this->_leave = 't01leavetrx';
        $this->_officialtravel = 't07officialtravel';
        $this->_incomplete = 't05incomplete';
        $this->_suspension = 't10suspension';
        $this->_leavepermit = 't08leavepermit';
        $this->_personal = 'triasnet_employee.m01personal';
        $this->_typepresence = 'r04typeattendance';
        $this->_parampresence = 'parampresence';
        $this->_paramlate = 'paramlate';
        $this->_job = 'triasnet_public.m01personal_job';
    }
    
    function report_for_late($from, $until, $nip) {
      
       $where = "
            WHERE                        
                (a.IDEmployee ='$nip' AND a.PresenceDate BETWEEN '$from' AND '$until' AND WorkDay NOT IN('SUN','OFF')) AND
                  c.Position  NOT IN('DIRECTOR','ASSISTANT DIRECTOR','MANAGER') AND 
                ( 
                  (b.Status = 'A' AND b.DeleteFlag='A') OR 
                  (b.Status = 'P') AND (b.ResignDate BETWEEN '$from' AND '$until') 
                )
        ";
       


        $sql =
                "SELECT 
            a.IDEmployee,
            b.FullName,b.IDJobGroup,b.IDLocation,b.HireDate,b.ResignDate,
            a.PresenceDate,a.WorkDay,a.ActualIn,a.ActualOut,a.ManualIn,a.ManualOut,a.IMKOut,a.LateHour,a.WorkHour,
            a.Description, a.Necessity,
            c.Position
            
         FROM  $this->_presence a            
         JOIN  $this->_personal b            
         ON    b.IDEmployee=a.IDEmployee
         JOIN  $this->_job c            
         ON    c.IDEmployee=a.IDEmployee
         $where 
         ORDER BY 
            b.IDJobGroup DESC,
            b.FullName ASC,
            a.PresenceDate ASC
        ";
        $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return NULL;
        }
    }
    
    
    function get_paramlate($date,$idloc){
        $this->_db_at->where('DeleteFlag','A');
        $this->_db_at->where('ParamDate',$date);
        $this->_db_at->where('ParamSite',$idloc);
        $result = $this->_db_at->get($this->_paramlate);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return null;
        }        
    }
    
	function get_leavepermit($idemployee,$date){
       $this->_db_at->where('IDEmployee',$idemployee);
       $this->_db_at->where('OutDate',$date.' 08:00:00');
       $this->_db_at->order_by('OutDate','DESC');
       $result = $this->_db_at->get($this->_leavepermit);
       if($result->num_rows()>0){
           return $result->row();
       }else{
           return  null;
       }
       
   } 
    
    function get_leavepermitx($idemployee,$date){
       $this->_db_at->where('IDEmployee',$idemployee);
       $this->_db_at->where('OutDate',$date.' 08:00:00');
       $this->_db_at->order_by('OutDate','DESC');
       $result = $this->_db_at->get($this->_leavepermit);
       if($result->num_rows()>0){
           return $result->result_array();
       }else{
           return  null;
       }
       
   }
    
     function get_parampresence($date){
        $this->_db_at->where('DeleteFlag','A');
        $this->_db_at->where('ParamDate',$date);
        $result = $this->_db_at->get($this->_parampresence);
        if($result->num_rows()>0){
            return $result->row();
        }else{
            return null;
        }        
    }

    function get_employee($where = NULL, $auto = NULL) {
        if ($auto == "yes") {
            $this->_db_emp->select('FullName AS label, IDEmployee AS idemp, HireDate AS hdate');
        }
        if ($where != NULL) {
            $this->_db_emp->where($where);
        }
        return $this->_db_emp->get($this->_prs);
    }

    function get_emp_leave($uid, $masuk, $skr) {
        $query = $this->_db_emp->query("
        SELECT A.* FROM ( 
            SELECT 
                m.TglMaster as Tanggal, 
                m.Jml as Jml, 
                '' AS Dari, 
                '' AS Sampai,
                '' AS Pengganti,
                Keterangan AS Alasan,            
                'master' AS Ket
            FROM $this->_mleave m
            WHERE IDEmployee = '$uid' and TglMaster >= '$masuk' and TglMaster <= '$skr' 
            UNION ALL
            SELECT 
                t.TglCutiDari as Tanggal, 
                (-1)*t.TotalCuti as Jml, 
                TglCutiDari AS Dari, 
                TglCutiSampai AS Sampai,
                IDPengganti AS Pengganti,
                Alasan AS Alasan,
                'trx' AS Ket
            FROM $this->_leave t
            WHERE 
                IDEmployee = '$uid' and 
                TglCutiDari >= '$masuk' and 
                TglCutiDari <= '$skr' and 
                FPgt = 'true' and
                FAts = 'true' and
                FHrd = 'true' and
                Jenis = 'AL' and DeleteFlag = 'A'
        ) A
        ORDER BY A.Tanggal ASC;
        ");
//		return $query->row();
        if ($query->num_rows() != 0) {
            return $query->result();
        } else {
            return "kosong";
        }
    }

    function check_personalpresence($nip, $dfrom, $duntil) {
        $this->_db_at->where('IDEmployee', $nip);
        $this->_db_at->where('PresenceDate >=', $dfrom);
        $this->_db_at->where('PresenceDate <=', $duntil);
        $result = $this->_db_at->get($this->_presence);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }

    function check_allpresence($dfrom, $duntil, $dept, $group, $site) {
        $this->_db_at->select('a.*,b.FullName,b.IDJobGroup');
        $this->_db_at->from($this->_presence . ' a');
        $this->_db_at->join($this->_personal . ' b', 'a.IDEmployee = b.IDEmployee', 'left');

        $this->_db_at->where('a.PresenceDate >=', $dfrom);
        $this->_db_at->where('a.PresenceDate <=', $duntil);
        $this->_db_at->where('b.IDDepartement', $dept);

        $checkgroup = ($group == '' or $group == 'undefined') ? 'empty' : 'exist';
        $checksite = ($site == '' or $site == 'undefined') ? 'empty' : 'exist';
        $loc = ($site == 'Kapuk') ? '1' : '2';
        
        
        if ($checkgroup == 'exist') {
            if($checksite=='exist'){
                 $this->_db_at->where('b.IDJobGroup', $group);
                  $this->_db_at->where('b.IDLocation', $loc);
            }else{
                 $this->_db_at->where('b.IDJobGroup', $group);
            }
        }

        if($checksite=='exist'){
	   $this->_db_at->where('b.IDLocation', $loc);
	}      

        $result = $this->_db_at->get();
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }

    function report_summary($fromdate, $untildate, $dept, $job = '', $site = '') {
        $checkgroup = ($job == '' or $job == 'undefined') ? 'empty' : 'exist';
        $checksite = ($site == '' or $site == 'undefined') ? 'empty' : 'exist';
        $loc = ($site == 'Kapuk') ? '1' : '2';

        if ($checkgroup == 'exist') {
            if ($checksite == 'exist') {
                $where = "
                    WHERE
                    b.IDLocation ='$loc' AND
                    (b.IDJobGroup ='$job' AND  b.IDDepartement='$dept' AND b.DeleteFlag='A') AND
                    (    
                    (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
                    (b.Status='A')          
                    )   

                ";
            } else {
                $where = "
                    WHERE                    
                    (b.IDJobGroup ='$job' AND  b.IDDepartement='$dept' AND b.DeleteFlag='A') AND
                    (    
                    (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
                    (b.Status='A')            
                    )   

                ";
            }
        } else {

            if ($checksite == 'exist') {
                $where = "
                    WHERE
                    b.IDLocation='$loc' AND
                    (b.IDDepartement='$dept' AND  b.DeleteFlag='A') AND
                    (    
                    (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
                    (b.Status='A')            
                    )   

                ";
            } else {
                $where = "
                    WHERE
                    (b.IDDepartement='$dept' AND b.DeleteFlag='A') AND
                    (    
                    (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
                    (b.Status='A') OR ((b.Status='P') AND (b.ResignDate BETWEEN '$fromdate' AND '$untildate'))            
                    )   

                ";
            }
        }


        $query = "     
           SELECT  a.IDEmployee,b.FullName, b.IDJobGroup,a.Description,a.PresenceDate,a.ActualIn,a.ActualOut,a.ManualIn,
                   a.ManualOut,b.HireDate,b.ResignDate
           FROM    $this->_presence a
           JOIN    $this->_personal b 
           ON      b.IDEmployee = a.IDEmployee
            
            $where       
           ORDER BY  b.FullName ASC, a.PresenceDate ASC";

        $result = $this->_db_at->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    /*
      function report_summary($fromdate, $untildate, $job = '') {
      $query = "
      SELECT  a.IDEmployee,b.FullName, b.IDJobGroup,a.Description,a.PresenceDate,a.ActualIn,a.ActualOut,a.ManualIn,
      a.ManualOut,b.HireDate,b.ResignDate
      FROM    $this->_presence a
      JOIN    $this->_personal b
      ON      b.IDEmployee = a.IDEmployee
      WHERE
      (b.IDJobGroup ='$job') AND
      (
      (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
      (b.Status='A') OR ((b.Status='P') AND (b.ResignDate BETWEEN '$fromdate' AND '$untildate'))
      )
      ORDER BY  b.FullName ASC, a.PresenceDate ASC";

      $result = $this->_db_at->query($query);
      if ($result->num_rows() > 0) {
      return $result->result_array();
      }
      return NULL;
      }
     * 
     */

    function report_detail($nip, $fromdate, $untildate) {
        $this->_db_at->select($this->_presence . '.*,personal.FullName,personal.IDJobGroup,' . $this->_typepresence . '.Description AS rDescription');
        $this->_db_at->from($this->_presence);
        $this->_db_at->join($this->_personal . ' personal', $this->_presence . '.IDEmployee = personal.IDEmployee', 'left');
        $this->_db_at->join($this->_typepresence, $this->_presence . '.Description = ' . $this->_typepresence . '.IDType', 'left');
        $this->_db_at->where($this->_presence . '.IDEmployee', $nip);
        $this->_db_at->where('PresenceDate >=', $fromdate);
        $this->_db_at->where('PresenceDate <=', $untildate);
        $this->_db_at->order_by("PresenceDate", "asc");
        $result = $this->_db_at->get();
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }

    function report_overtime($fromdate, $untildate, $job, $nip) {
        $checknip = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            $where = " WHERE a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND a.IDEmployee ='$nip' AND b.IDJobGroup ='$job' AND DeleteFlag='A' AND ConfirmFlag='1'";
        } else {
            $where = " WHERE a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND b.IDJobGroup ='$job' AND DeleteFlag='A' AND ConfirmFlag='1' ";
        }

        $query = "     
             SELECT  a.ID,a.IDSPKL,a.IDEmployee,b.FullName,b.IDJobGroup,b.IDJobGroup,a.PresenceDate,
                     c.WorkDay,c.Description,a.OvertimeIn,a.OvertimeOut,a.Note
             FROM    $this->_overtime a     
             LEFT JOIN    $this->_personal b
             ON      b.IDEmployee=a.IDEmployee
             LEFT JOIN    $this->_presence c
             ON      c.IDEmployee=a.IDEmployee AND c.PresenceDate=a.PresenceDate
             $where
             ORDER BY  b.IDJobGroup DESC, b.FullName ASC,a.PresenceDate ASC";

        $result = $this->_db_at->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function report_sum_absen($fromdate, $untildate, $group, $nip) {
        $checknip = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            $where = "  WHERE   
                            (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
                            (b.IDEmployee ='$nip' AND b.IDJobGroup ='$group') AND
                            ((b.Status ='A') OR (b.Status ='P' AND ResignDate >='$fromdate')) AND 
                            ( (a.PresenceDate >= b.HireDate) AND (a.PresenceDate <= '$untildate') )
                    ";
        } else {
            $where = "  WHERE   
                            (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND 
                            (b.IDJobGroup ='$group') AND    
                            ((b.Status ='A') OR (b.Status ='P' AND ResignDate >='$fromdate')) AND 
                            ( (a.PresenceDate >= b.HireDate) AND (a.PresenceDate <= '$untildate') )
                    ";
        }


        $query = "     
                SELECT  a.IDEmployee , b.FullName, b.IDJobGroup, a.PresenceDate,          
                        a.ActualIn,a.ActualOut, a.DayOfWeek, a.Description,c.Description AS rDescription,
                        a.Note   
            
                FROM $this->_presence a
                JOIN $this->_personal b   
                ON   b.IDEmployee = a.IDEmployee
            	LEFT JOIN $this->_typepresence c   
                ON   a.Description= c.IDType  
                $where
                ORDER BY b.FullName ASC, a.PresenceDate ASC     
        ";

        $result = $this->_db_at->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function report_dailypresence($date, $nip, $group) {
        $checknip = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            $where = " WHERE  b.PresenceDate = '$date' AND a.IDEmployee ='$nip' AND a.IDJobGroup ='$group' AND  
                            a.Status = 'A'";
        } else {
            $where = " WHERE  b.PresenceDate = '$date' AND a.IDJobGroup ='$group' AND  
                            a.Status = 'A'";
        }

        $query = "     
            SELECT a.IDEmployee, a.FullName,a.IDJobGroup,b.PresenceDate,b.DayOfWeek,
                   b.ActualIn,b.ActualOut,b.ManualIn,b.ManualOut,b.ActualHour,b.WorkHour,
                   b.LateHour,b.Description,c. Description AS rDescription
            FROM   $this->_personal a
            JOIN   $this->_presence b 
            ON     b.IDEmployee=a.IDEmployee             
            LEFT JOIN $this->_typepresence c
            ON     b.Description= c.IDType 
            $where
            ORDER BY a.FullName ASC
        ";
        $result = $this->_db_at->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function report_late($fromdate, $untildate, $nip) {

        $query = "     
                SELECT  a.IDEmployee, b.FullName,b.IDJobGroup,a.PresenceDate,
                        a.ActualIn,a.LateHour,a.Description 
                FROM    $this->_presence a                        
                JOIN    $this->_personal b                        
                ON      b.IDEmployee = a.IDEmployee 
                WHERE   a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND  b.IDEmployee='$nip'
                ORDER BY     b.FullName ASC,a.PresenceDate ASC
                
        ";
        $result = $this->_db_at->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function report_absence($fromdate, $untildate, $group, $nip) {
        $checknip = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            $where = "WHERE   a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND b.Status='A' AND  b.IDJobGroup ='$group' AND a.IDEmployee='$nip' ";
        } else {
            $where = "WHERE   a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND b.Status='A' AND  b.IDJobGroup ='$group'";
        }
        $query = "     
             SELECT   a.IDEmployee ,a.Description, a.PresenceDate,b.FullName,
                     b.IDJobGroup,b.HireDate,b.ResignDate,c.Description AS rDescription                   
             FROM   $this->_presence a                    
             JOIN   $this->_personal b
             ON     b.IDEmployee = a.IDEmployee
             LEFT JOIN $this->_typepresence c
             ON     a.Description= c.IDType 
             $where
             ORDER BY b.FullName ASC,  a.PresenceDate ASC
        ";
        $result = $this->_db_at->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function report_incomplete($fromdate, $untildate, $group, $nip) {
        $checknip = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            $where = "WHERE a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND b.IDJobGroup='$group' AND a.IDEmployee='$nip'";
        } else {
            $where = " WHERE a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND b.IDJobGroup='$group'";
        }

        $query = "SELECT a.*,b.FullName,b.IDJobGroup,c.Description rDescription
                 FROM $this->_presence a
                 LEFT JOIN $this->_personal b    
                 ON a.IDEmployee = b.IDEmployee
                 LEFT JOIN $this->_typepresence c
                 ON a.Description = c.IDType   
                 $where
                 ORDER BY PresenceDate ASC    
                ";
        $result = $this->_db_at->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function report_incomplete_form($fromdate, $untildate, $group, $nip) {
        $f = date('Y-m-d', strtotime($fromdate));
        $u = date('Y-m-d', strtotime($untildate));
        $checkdata = ($nip == null or $nip == '') ? 'empty' : 'exist';

        if ($checkdata !== 'empty') {
            if ($group !== 'Al') {
                $where = "WHERE a.IDEmployee='$nip' AND a.IncompleteDate BETWEEN '$f' AND '$u' AND b.IDJobGroup ='$group' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
            } else {
                $where = "WHERE a.IDEmployee='$nip' AND a.IncompleteDate BETWEEN '$f' AND '$u' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
            }
        } else {
            if ($group !== 'Al') {
                $where = "WHERE a.IncompleteDate BETWEEN '$f' AND '$u' AND b.IDJobGroup ='$group' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
            } else {
                $where = "WHERE a.IncompleteDate BETWEEN '$f' AND '$u' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
            }
        }
        $sql = "SELECT
                    a.*,
                    b.FullName,
                    b.IDJobGroup
                FROM 
                    $this->_incomplete a
                LEFT JOIN
                   $this->_personal b
                ON
                    a.IDEmployee = b.IDEmployee 
               $where
                   ORDER BY b.FullName ASC,b.IDJobGroup DESC,a.IncompleteDate ASC
		     	
                     
                ";
        $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return null;
    }

    function report_leave($fromdate, $untildate, $dept) {
      
       $where = "WHERE a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND b.IDDepartement='$dept'";
       
        $query = "SELECT a.*,b.FullName,b.IDJobGroup
                 FROM $this->_presence a
                 LEFT JOIN $this->_personal b    
                 ON a.IDEmployee = b.IDEmployee
               
                 $where
                 ORDER BY b.FullName ASC,b.IDJobGroup DESC,a.PresenceDate ASC        
                ";

        $result = $this->_db_at->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function report_leavepermit($nip, $from, $until, $group, $necessity) {
        $f = date('Y-m-d', strtotime($from));
        $u = date('Y-m-d', strtotime($until));
        $type = ($necessity == 'Personal') ? '1' : '2';

        $checkdata = ($nip == null or $nip == '') ? 'empty' : 'exist';

        if ($checkdata !== 'empty') {
            if ($group !== 'Al') {
                if ($necessity !== 'All') {
                    $where = "WHERE a.IDEmployee='$nip' AND a.LeavePermitDate BETWEEN '$f' AND '$u' AND b.IDJobGroup ='$group' AND a.Necessity='$type' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
                } else {
                    $where = "WHERE a.IDEmployee='$nip' AND a.LeavePermitDate BETWEEN '$f' AND '$u' AND b.IDJobGroup ='$group' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
                }
            } else {
                if ($necessity !== 'All') {
                    $where = "WHERE a.IDEmployee='$nip' AND a.LeavePermitDate BETWEEN '$f' AND '$u' AND a.Necessity='$type' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
                } else {
                    $where = "WHERE a.IDEmployee='$nip' AND a.LeavePermitDate BETWEEN '$f' AND '$u' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
                }
            }
        } else {
            if ($group !== 'Al') {
                if ($necessity !== 'All') {
                    $where = "WHERE a.LeavePermitDate BETWEEN '$f' AND '$u' AND b.IDJobGroup ='$group' AND a.Necessity='$type' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
                } else {
                    $where = "WHERE a.LeavePermitDate BETWEEN '$f' AND '$u' AND b.IDJobGroup ='$group' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
                }
            } else {
                if ($necessity !== 'All') {
                    $where = "WHERE a.LeavePermitDate BETWEEN '$f' AND '$u' AND a.Necessity='$type' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
                } else {
                    $where = "WHERE a.LeavePermitDate BETWEEN '$f' AND '$u' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' ";
                }
            }
        }
        $sql = "SELECT
                    a.*, DATE_FORMAT(a.LeavePermitDate,'%d-%m-%Y') AS LPDate,
                    b.FullName,
                    b.IDJobGroup
                FROM 
                    $this->_leavepermit a
                LEFT JOIN
                    $this->_personal b
                ON
                    a.IDEmployee = b.IDEmployee 
               $where
                   ORDER BY
                     b.FullName ASC,
                     a.LeavePermitDate ASC
                ";
        $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return null;
    }

    function report_travel($from, $until, $nip) {
        $checkdata = ($nip == null or $nip == '') ? 'empty' : 'exist';
        if ($checkdata !== 'empty') {
            $where = " WHERE
                           a.OfficialTravelDate BETWEEN '$from' AND '$until' AND b.IDEmployee='$nip' AND b.Status='A' AND a.DeleteFlag='A' AND a.ConfirmFlag='1'";
        } else {
            $where = " WHERE
                           a.OfficialTravelDate BETWEEN '$from' AND '$until' AND a.DeleteFlag='A' AND a.ConfirmFlag='1' AND b.Status='A'";
        }

        $sql = "SELECT
                        a.*,b.FullName,b.IDJobGroup
                FROM $this->_officialtravel a
                LEFT JOIN $this->_personal b
                ON a.IDEmployee = b.IDEmployee
                $where
                ORDER BY b.FullName ASC, a.OfficialTravelDate ASC                            
                ";
        $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return null;
    }

    function report_sickness($from, $until, $group, $nip) {
        $checkdata = ($nip == null or $nip == '') ? 'empty' : 'exist';
        if ($checkdata !== 'empty') {
            $where = " WHERE
                            a.Jenis='SL' AND a.TglCutiDari BETWEEN '$from' AND '$until' AND a.FPgt='true' AND a.FAts='true' AND
                            a.FHrd='true' AND a.DeleteFlag='A' AND a.IDEmployee='$nip' AND b.IDJobGroup ='$group' AND b.Status='A' ";
        } else {
            $where = " WHERE
                            a.Jenis='SL' AND a.TglCutiDari BETWEEN '$from' AND '$until' AND a.FPgt='true' AND a.FAts='true' AND
                            a.FHrd='true' AND a.DeleteFlag='A' AND b.IDJobGroup ='$group' AND b.Status='A'";
        }
        $sql = "SELECT a.*,b.FullName,b.IDJobGroup
                FROM $this->_leave a
                LEFT JOIN $this->_personal b
                ON a.IDEmployee = b.IDEmployee
                $where
                 ORDER BY b.FullName ASC, a.TglCutiDari ASC        
                ";
        $result = $this->_db_emp->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return null;
    }

    function report_suspension($fromdate, $untildate, $group, $nip) {
        $checknip = ($nip == '' or $nip == null) ? 'empty' : 'exist';
        if ($checknip == 'exist') {
            $where = "WHERE a.SuspensionDate BETWEEN '$fromdate' AND '$untildate' AND a.IDEmployee='$nip' AND b.IDJobGroup='$group' AND b.Status='A' AND a.DeleteFlag='A'";
        } else {
            $where = "WHERE a.SuspensionDate BETWEEN '$fromdate' AND '$untildate' AND a.DeleteFlag='A' AND    b.IDJobGroup='$group' AND b.Status='A'";
        }

        $query = "SELECT a.*,b.FullName,b.IDJobGroup
                 FROM $this->_suspension a
                 LEFT JOIN $this->_personal b    
                 ON a.IDEmployee = b.IDEmployee 
                 $where
                 ORDER BY b.FullName ASC,b.IDJobGroup DESC,a.SuspensionDate ASC        
                ";
        $result = $this->_db_at->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function report_turnover($fromdate, $untildate) {
        $this->_db_emp->where('HireDate >=', $fromdate);
        $this->_db_emp->where('HireDate <=', $untildate);
        $count_hire = $this->_db_emp->count_all_results($this->_personal);
        $this->_db_emp->where('ResignDate >=', $fromdate);
        $this->_db_emp->where('ResignDate <=', $untildate);
        $count_resign = $this->_db_emp->count_all_results($this->_personal);
        $count = $count_hire + $count_resign;
        if ($count == 0) {
            return NULL;
        } else {
            $query = "
                SELECT  *, DATEDIFF('$untildate',HireDate) AS Priv
                FROM    $this->_personal
                WHERE   HireDate >= '$fromdate' AND HireDate <= '$untildate' 
                UNION
                SELECT  *, DATEDIFF(ResignDate,'$fromdate') AS Priv
                FROM    $this->_personal
                WHERE   ResignDate >= '$fromdate' AND ResignDate <= '$untildate' 
                ORDER BY FullName";

            $result = $this->_db_emp->query($query);
            return $result->result_array();
        }
    }

    function report_unpaid($from, $until, $nip) {
        $checkdata = ($nip == null or $nip == '') ? 'empty' : 'exist';
        if ($checkdata !== 'empty') {
            $where = " WHERE
                            a.Jenis='OL' AND a.TglCutiDari BETWEEN '$from' AND '$until' AND a.FPgt='true' AND a.FAts='true' AND
                            a.FHrd='true' AND a.DeleteFlag='A' AND a.IDEmployee='$nip' AND b.Status='A' ";
        } else {
            $where = " WHERE
                            a.Jenis='OL' AND a.TglCutiDari BETWEEN '$from' AND '$until' AND a.FPgt='true' AND a.FAts='true' AND
                            a.FHrd='true' AND a.DeleteFlag='A'  AND b.Status='A'";
        }
        $sql = "SELECT a.*,b.FullName,b.IDJobGroup
                FROM $this->_leave a
                LEFT JOIN $this->_personal b
                ON a.IDEmployee = b.IDEmployee
                $where
                 ORDER BY b.FullName ASC,b.IDJobGroup DESC, a.TglCutiDari ASC        
                ";
        $result = $this->_db_emp->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return null;
    }

}
?>

