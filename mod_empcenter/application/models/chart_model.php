<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Chart_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('empcenter', TRUE);
        $this->_pbl = $this->load->database('public', TRUE);
        $this->_db_at = $this->load->database('attendance', TRUE);
        $this->_personal = 'm01personal';
        $this->_personal_d = 'm01personal_d';
        $this->_job = 'm01personal_job';
        $this->_presence = 't03presence';
        $this->_overtime = 't04overtime';
        $this->_tbl1 = 'm01personal';
        $this->_tbl2 = 'm01personal_job';
    }

    function get_available_emp() {
        $query = "SELECT H.*,J.* FROM $this->_tbl1 H LEFT JOIN $this->_tbl2 J ON H.IDEmployee = J.IDEmployee WHERE J.ResignDate IS NULL";
        return $this->_pbl->query($query);
    }

    function get_all_emp() {
        return $this->_db->get($this->_tbl1);
    }

    function get_jml_gender($gen = NULL) {
        if ($gen != NULL) {
            $query = "SELECT COUNT(*) as jml FROM $this->_tbl1 WHERE Gender = '$gen'";
        } else {
            $query = "SELECT COUNT(*) as jml FROM $this->_tbl1 WHERE Gender IS NULL";
        }
        return $this->_db->query($query);
    }

    function get_aktif($bln, $thn) {
        if ($bln == 0) {
            $bln = "12";
            $thn = $thn - 1;
        } else {
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol . $bln;
        }
        $lastdate   = date("t",  strtotime("$thn-$bln-01"));
        $query = "
            SELECT count( * ) as jml
            FROM $this->_tbl1
            WHERE HireDate <= '$thn-$bln-$lastdate'
            ";
        return $this->_db->query($query);
    }
    
    
    function getpresence_year_asc(){
        $sql = "SELECT PresenceDate FROM $this->_presence
                WHERE PresenceDate NOT IN(0000-00-00)              
                ORDER BY PresenceDate ASC
                LIMIT 1";
        
         $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            $year = date('Y',  strtotime($row->PresenceDate));
        } else {
            $year =date('Y'); 
        }       
       
        return  $year;     
    }
    
    function getpresence_year_desc(){       
        $sql = " SELECT PresenceDate FROM $this->_presence
                 WHERE PresenceDate NOT IN(0000-00-00)               
                 ORDER BY PresenceDate DESC
                 LIMIT 1";
        
         $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            $year = date('Y',  strtotime($row->PresenceDate));
        } else {
             $year =date('Y'); 
        }        
        return $year;
        
    }

    function get_new($bln, $thn) {
        if ($bln == 0) {
            $blnfr = "12";
            $thnfr = $thn - 1;
            $bln = $bln + 1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol . $bln;
        } else {
            $blnfr = $bln;
            $thnfr = $thn;
            $bln = $bln + 1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol . $bln;
        }
        $last1  = date("t",  strtotime("$thnfr-$blnfr-01"));
        $last2  = date("t",  strtotime("$thn-$bln-01"));
        $query = "
            SELECT count( * ) as jml
            FROM $this->_tbl1
            WHERE HireDate >= '$thn-$bln-01'
            AND HireDate <= '$thn-$bln-$last2'
            ";
        return $this->_db->query($query);
    }

    function get_resign($bln, $thn) {
        if ($bln == 0) {
            $blnfr = "12";
            $thnfr = $thn - 1;
            $bln = $bln + 1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol . $bln;
        } else {
            $blnfr = $bln;
            $thnfr = $thn;
            $bln = $bln + 1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol . $bln;
        }
        $last1  = date("t",  strtotime("$thnfr-$blnfr-01"));
        $last2  = date("t",  strtotime("$thn-$bln-01"));
        $query = "
            SELECT count( * ) as jml
            FROM $this->_tbl1
            WHERE ResignDate >= '$thn-$bln-01'
            AND ResignDate <= '$thn-$bln-$last2'";
        return $this->_db->query($query);
    }

    function get_all_in($bln, $thn) {
        if ($bln == 0) {
            $bln = "12";
            $thn = $thn - 1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol . $bln;
        } else {
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol . $bln;
        }
        $lastdate  = date("t",  strtotime("$thn-$bln-01"));
        $query = "SELECT COUNT(*) as jml FROM $this->_tbl1 WHERE HireDate <= '$thn-$bln-$lastdate'";
        return $this->_db->query($query);
    }
    function get_all_res($bln, $thn) {
        if ($bln == 0) {
            $bln = "12";
            $thn = $thn - 1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol . $bln;
        } else {
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol . $bln;
        }
        $lastdate  = date("t",  strtotime("$thn-$bln-01"));
        $query = "SELECT count(*) as jml FROM $this->_tbl1 WHERE ResignDate <= '$thn-$bln-$lastdate'";
        //ResignDate > '0000-00-00' and 
        return $this->_db->query($query);
    }

    function getcount_presence($date,$loc,$dep) {
        $sql = "
                SELECT 
                    Count(a.IDPresence) AS JumlahMasuk,a.IDEmployee,
                    c.FullName,c.IDLocation,a.PresenceDate,
                    a.Description,a.Note 
                FROM  $this->_presence  a
                LEFT JOIN isib_public.$this->_job b
                ON a.IDEmployee = b.IDEmployee
                LEFT JOIN isib_employee.$this->_personal_d c
                ON a.IDEmployee = c.IDEmployee
                WHERE 
                a.Description IN('P','NC') AND 
                a.PresenceDate ='$date' AND
                b.Department ='$dep' AND
                c.IDLocation ='$loc'    
                ";
        $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            return $row->JumlahMasuk;
        } else {
            return 0;
        }
    }

    function getcount_overtime($date,$loc,$dep) {
        $sql = "
                SELECT
                    Count(a.ID) AS JumlahLembur,a.IDEmployee,
                    c.FullName,c.IDLocation,a.IDSPKL,a.PresenceDate,
                    a.OvertimeIn,a.OvertimeOut,a.OvertimeHour,a.Note
                FROM $this->_overtime a
                LEFT JOIN isib_public.$this->_job b
                ON a.IDEmployee = b.IDEmployee
                LEFT JOIN isib_employee.$this->_personal_d c
                ON a.IDEmployee = c.IDEmployee
                WHERE 
                a.PresenceDate ='$date' AND
                b.Department ='$dep' AND            
                a.ConfirmFlag ='1' AND
                a.DeleteFlag  ='A' AND
                c.IDLocation ='$loc'
                ";
        $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            return $row->JumlahLembur;
        } else {
            return 0;
        }
    }

    function getmax_overtime($date,$loc, $dep) {
        $sql = "
                SELECT
                    MAX(a.OvertimeHour) AS JamMaksimal,a.IDEmployee,
                    c.FullName,c.IDLocation,a.IDSPKL,a.PresenceDate,
                    a.OvertimeIn,a.OvertimeOut,a.OvertimeHour,a.Note
                FROM $this->_overtime a
                LEFT JOIN isib_public.$this->_job b
                ON a.IDEmployee = b.IDEmployee
                LEFT JOIN isib_employee.$this->_personal_d c
                ON a.IDEmployee = c.IDEmployee
                WHERE 
                a.PresenceDate ='$date' AND
                b.Department ='$dep' AND            
                a.ConfirmFlag ='1' AND
                a.DeleteFlag  ='A' AND
                c.IDLocation ='$loc'
                ";
        $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            return $row->JamMaksimal;
        } else {
            return 0;
        }
    }

}

