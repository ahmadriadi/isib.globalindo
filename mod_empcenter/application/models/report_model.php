<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db_emp = $this->load->database('empcenter', TRUE);
        $this->_db_at = $this->load->database('attendance', TRUE);
        $this->_personal = 'm01personal';
        $this->_personal_d = 'm01personal_d';
        $this->_job = 'm01personal_job';
        $this->_presence = 't03presence';
        $this->_overtime = 't04overtime';
    }

    function getcount_presence($date, $dep, $loc) {
        $sql = "
                SELECT 
                    Count(a.IDPresence) AS JumlahMasuk,a.IDEmployee,
                    c.FullName,c.IDLocation,a.PresenceDate,
                    a.Description 
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

    function getcount_presence_note($date, $dep, $loc) {
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
            return $row->Note;
        } else {
            return 0;
        }
    }

    function getcount_overtime($date, $dep, $loc) {
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
                c.IDLocation ='$loc' AND
                a.ConfirmFlag ='1' AND
                a.DeleteFlag  ='A'
                ";
        $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            return $row->JumlahLembur;
        } else {
            return 0;
        }
    }

    function getmax_overtime($date, $dep, $loc) {
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
                c.IDLocation ='$loc' AND
                a.ConfirmFlag ='1' AND
                a.DeleteFlag  ='A'
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
?>


