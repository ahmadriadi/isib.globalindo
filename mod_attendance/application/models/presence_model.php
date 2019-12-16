<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Presence_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_table = 't03presence';
        $this->_m_table = 'm01personal';
        $this->_r_table = 'r04typeattendance';
        $this->_personal = 'isib_employee.m01personal';
    }

    function get_data($param, $fromdate, $untildate, $manual = '') {
        if ($param['search'] != null && $param['search'] === 'true') {
            $wh = $param['search_field'];
            switch ($param['search_operator']) {
                case "bw": // begin with
                    $wh .= " LIKE '" . $param['search_str'] . "%'";
                    break;
                case "ew": // end with
                    $wh .= " LIKE '%" . $param['search_str'] . "'";
                    break;
                case "cn": // contain %param%
                    $wh .= " LIKE '%" . $param['search_str'] . "%'";
                    break;
                case "eq": // equal =
                    if (is_numeric($param['search_str'])) {
                        $wh .= " = " . $param['search_str'];
                    } else {
                        $wh .= " = '" . $param['search_str'] . "'";
                    }
                    break;
                case "ne": // not equal
                    if (is_numeric($param['search_str'])) {
                        $wh .= " <> " . $param['search_str'];
                    } else {
                        $wh .= " <> '" . $param['search_str'] . "'";
                    }
                    break;
                case "lt":
                    if (is_numeric($param['search_str'])) {
                        $wh .= " < " . $param['search_str'];
                    } else {
                        $wh .= " < '" . $param['search_str'] . "'";
                    }
                    break;
                case "le":
                    if (is_numeric($param['search_str'])) {
                        $wh .= " <= " . $param['search_str'];
                    } else {
                        $wh .= " <= '" . $param['search_str'] . "'";
                    }
                    break;
                case "gt":
                    if (is_numeric($param['search_str'])) {
                        $wh .= " > " . $param['search_str'];
                    } else {
                        $wh .= " > '" . $param['search_str'] . "'";
                    }
                    break;
                case "ge":
                    if (is_numeric($param['search_str'])) {
                        $wh .= " >= " . $param['search_str'];
                    } else {
                        $wh .= " >= '" . $param['search_str'] . "'";
                    }
                    break;
                default :
                    $wh = "";
            }


            $this->_db->where($wh);
        }

        $this->_db->select('presence.*,personal.FullName,editor.FullName AS Name');
        $this->_db->from('isib_attendance.t03presence presence');
        $this->_db->join('isib_employee.m01personal personal', 'presence.IDEmployee = personal.IDEmployee', 'left');
        $this->_db->join('isib_employee.m01personal editor', 'editor.IDEmployee = presence.AddedBy', 'left');
        $this->_db->where('presence.PresenceDate >=', $fromdate);
        $this->_db->where('presence.PresenceDate <=', $untildate);
        if ($manual == 'M') {
            $this->_db->where('ManualIn IS NOT NULL', null, false);
        }


        /*
          $this->_db->select($this->_table . '.*, ' . $this->_m_table . '.FullName');
          $this->_db->from($this->_table);
          $this->_db->join($this->_m_table, $this->_table . '.IDEmployee = ' . $this->_m_table . '.IDEmployee');

          $this->_db->where('PresenceDate >=', $fromdate);
          $this->_db->where('PresenceDate <=', $untildate);
          if ($manual == 'M') {
          $this->_db->where('ManualIn IS NOT NULL', null, false);
          }
         */

        ($param['limit'] != null ? $this->_db->limit($param['limit']['end'], $param['limit']['start']) : '');
        ($param['sort_by'] != null) ? $this->_db->order_by($param['sort_by'], $param['sort_direction']) : '';

        return $this->_db->get();
    }

    function get_all_by_period($fromdate, $untildate, $order = '', $manual = '') {
        $this->_db->where('PresenceDate >=', $fromdate);
        $this->_db->where('PresenceDate <=', $untildate);
        if ($manual == 'M') {
            $this->_db->where('ManualIn IS NOT NULL', null, false);
        }
        $count = $this->_db->count_all_results($this->_table);

        if ($count == 0) {
            return NULL;
        } else {
            $this->_db->select($this->_table . '.*,personal.FullName');
            $this->_db->from($this->_table);
            $this->_db->join('isib_employee.m01personal personal', $this->_table . '.IDEmployee = personal.IDEmployee');

            $this->_db->where('PresenceDate >=', $fromdate);
            $this->_db->where('PresenceDate <=', $untildate);
            if ($manual == 'M') {
                $this->_db->where('ManualIn IS NOT NULL', null, false);
            }
            if ($order != '') {
                $this->_db->order_by($order, "asc");
            }
            $this->_db->order_by("FullName", "asc");

            return $this->_db->get();
        }
    }

    function get_staff_by_period($fromdate, $untildate, $order = '', $manual = '') {
        $this->_db->select($this->_table . '.*,personal.FullName,personal.IDJobGroup,' . $this->_r_table . '.Description AS rDescription');
        $this->_db->from($this->_table);
        $this->_db->join('isib_employee.m01personal personal', $this->_table . '.IDEmployee = personal.IDEmployee');
        $this->_db->join($this->_r_table, $this->_table . '.Description = ' . $this->_r_table . '.IDType', 'left');

        $this->_db->where('PresenceDate >=', $fromdate);
        $this->_db->where('PresenceDate <=', $untildate);
        $this->_db->where('personal.Status', 'A');
        $this->_db->where('personal.IDJobGroup', 'ST');
        if ($manual == 'M') {
            $this->_db->where('ManualIn IS NOT NULL', null, false);
        }
        $count = $this->_db->count_all_results();

        if ($count == 0) {
            return NULL;
        } else {
            $this->_db->select($this->_table . '.*,personal.FullName,personal.IDJobGroup,' . $this->_r_table . '.Description AS rDescription');
            $this->_db->from($this->_table);
            $this->_db->join('isib_employee.m01personal personal', $this->_table . '.IDEmployee = personal.IDEmployee');
            $this->_db->join($this->_r_table, $this->_table . '.Description = ' . $this->_r_table . '.IDType', 'left');

            $this->_db->where('PresenceDate >=', $fromdate);
            $this->_db->where('PresenceDate <=', $untildate);
            $this->_db->where('personal.Status', 'A');
            $this->_db->where('personal.IDJobGroup', 'ST');
            if ($manual == 'M') {
                $this->_db->where('ManualIn IS NOT NULL', null, false);
            }
            if ($order != '') {
                $this->_db->order_by($order, "asc");
            }
            $this->_db->order_by("IDPresence", "asc");

            return $this->_db->get();
        }
    }

    function get_field_by_period($fromdate, $untildate, $order = '', $manual = '') {
        $this->_db->select($this->_table . '.*,personal.FullName,personal.IDJobGroup,' . $this->_r_table . '.Description AS rDescription');
        $this->_db->from($this->_table);
        $this->_db->join('isib_employee.m01personal personal', $this->_table . '.IDEmployee = personal.IDEmployee');
        $this->_db->join($this->_r_table, $this->_table . '.Description = ' . $this->_r_table . '.IDType', 'left');

        $this->_db->where('PresenceDate >=', $fromdate);
        $this->_db->where('PresenceDate <=', $untildate);
        $this->_db->where('personal.Status', 'A');
        $this->_db->where('IDJobGroup', 'F');
        if ($manual == 'M') {
            $this->_db->where('ManualIn IS NOT NULL', null, false);
        }
        $count = $this->_db->count_all_results();

        if ($count == 0) {
            return NULL;
        } else {
            $this->_db->select($this->_table . '.*,personal.FullName,personal.IDJobGroup,' . $this->_r_table . '.Description AS rDescription');
            $this->_db->from($this->_table);
            $this->_db->join('isib_employee.m01personal personal', $this->_table . '.IDEmployee = personal.IDEmployee');
            $this->_db->join($this->_r_table, $this->_table . '.Description = ' . $this->_r_table . '.IDType', 'left');

            $this->_db->where('PresenceDate >=', $fromdate);
            $this->_db->where('PresenceDate <=', $untildate);
            $this->_db->where('personal.Status', 'A');
            $this->_db->where('IDJobGroup', 'F');
            if ($manual == 'M') {
                $this->_db->where('ManualIn IS NOT NULL', null, false);
            }
            if ($order != '') {
                $this->_db->order_by($order, "asc");
            }
            $this->_db->order_by("IDPresence", "asc");

            return $this->_db->get();
        }
    }

    function get_daily_detail($fromdate, $untildate) {
        $query = "
            SELECT 	t03presence.IDEmployee AS 'NIP',
	m01personal.FullName AS 'Nama',
	IFNULL(t03presence.ActualIn, t03presence.ManualIn) AS 'JamMasuk',
	IFNULL(t03presence.ActualOut, t03presence.ManualOut) AS 'JamKeluar',
	IFNULL(T.OvertimeIn, '') AS 'LemburMasuk',
	IFNULL(T.IDSPKL, '') AS 'NoSPKL',
	( 	SELECT 	IDCard 
		FROM 	t02rawdata 
		WHERE 	t03presence.IDEmployee = t02rawdata .IDEmployee AND 
			CONCAT_WS(' ',t02rawdata .PresenceDate,t02rawdata .PresenceTime) = t03presence.ActualIn
		LIMIT	1
	) AS 'EnrollNumber'
FROM	t03presence
LEFT JOIN isib_employee.m01personal ON t03presence.IDEmployee = m01personal.IDEmployee
LEFT JOIN (
	SELECT	t04overtime.IDSPKL, t04overtime.OvertimeDate, t04overtime.OvertimeIn, t04overtime_detail.IDEmployee
	FROM	t04overtime_detail
	LEFT JOIN t04overtime ON t04overtime_detail.IDSPKL = t04overtime.IDSPKL
) AS T ON 
	t03presence.PresenceDate = T.OvertimeDate AND 
	t03presence.IDEmployee = T.IDEmployee
WHERE t03presence.PresenceDate >='$fromdate' AND t03presence.PresenceDate <='$untildate'
";
        return $this->_db->query($query);
    }

    function get_by_id($id) {
        $this->_db->where('IDPresence', $id);
        $jml = $this->_db->count_all_results($this->_table);
        if ($jml == 0) {
            return NULL;
        } else {
            $this->_db->from($this->_table . '*,personal.FullName');
            $this->_db->join('isib_employee.m01personal', $this->_table . '.IDEmployee = personal.IDEmployee', 'left');
            $this->_db->where('IDPresence', $id);
            return $this->_db->get();
        }
    }

    function check_allpresence($group, $dfrom, $duntil) {
        $this->_db->select('a.*,b.FullName,b.IDJobGroup');
        $this->_db->from($this->_table . ' a');
        $this->_db->join($this->_personal . ' b', 'a.IDEmployee = b.IDEmployee', 'left');
        $this->_db->where('b.IDJobGroup', $group);
        $this->_db->where('a.PresenceDate >=', $dfrom);
        $this->_db->where('a.PresenceDate <=', $duntil);
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }

    function check_presence($nip, $dfrom, $duntil) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PresenceDate >=', $dfrom);
        $this->_db->where('PresenceDate <=', $duntil);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }

    function get_by_nipperiod($nip, $fromdate, $untildate) {
        $this->_db->select($this->_table . '.*,personal.FullName,personal.IDJobGroup,' . $this->_r_table . '.Description AS rDescription');
        $this->_db->from($this->_table);
        $this->_db->join('isib_employee.m01personal personal', $this->_table . '.IDEmployee = personal.IDEmployee', 'left');
        $this->_db->join($this->_r_table, $this->_table . '.Description = ' . $this->_r_table . '.IDType', 'left');
        $this->_db->where($this->_table . '.IDEmployee', $nip);
        $this->_db->where('PresenceDate >=', $fromdate);
        $this->_db->where('PresenceDate <=', $untildate);
        $this->_db->order_by("PresenceDate", "asc");
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }

    function get_curr_by_nipdate($nip, $date) {
        $this->_db->where('PresenceDate', $date);
        $this->_db->where('IDEmployee', $nip);

        $count = $this->_db->count_all_results($this->_table);

        if ($count == 0) {
            return NULL;
        } else {
            $this->_db->where('PresenceDate', $date);
            $this->_db->where('IDEmployee', $nip);
            return $this->_db->get($this->_table);
        }
    }

    function get_prev_by_nipdate($nip, $date) {
        $prevdate = date('Y-m-d', strtotime("-1 day", strtotime($date)));
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PresenceDate', $prevdate);

        $count = $this->_db->count_all_results($this->_table);
        if ($count == 0) {
            return NULL;
        } else {
            $this->_db->where('PresenceDate', $prevdate);
            $this->_db->where('IDEmployee', $nip);
            return $this->_db->get($this->_table);
        }
    }

    function check_in_exist($nip, $date) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PresenceDate', $date);
        $this->_db->where('ActualIn IS NOT NULL', null, false);
        $count = $this->_db->count_all_results($this->_table);
        if ($count == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function unloadperiod($nip, $date, $record) {
        // cek, jika sdh ada, abaikan
        // kalau blm ada, insert
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PresenceDate', $date);
        if ($this->_db->count_all_results($this->_table) == 0) {
            $this->insert($record);
        }
    }

    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db->where('IDPresence', $id);
        $this->_db->update($this->_table, $record);
    }

    function update_by_nipdate($nip, $date, $record) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PresenceDate', $date);
        $this->_db->update($this->_table, $record);
    }

    function delete_by_period($fromdate, $untildate) {
        $this->_db->delete($this->_table, array('PresenceDate >=' => $fromdate, 'PresenceDate <=' => $untildate));
    }

    function delete($id) {
        $this->_db->delete($this->_table, array('IDPresence' => $id));
    }

    function count_by_id($id) {
        $this->_db->where('IDPresence', $id);
        return $this->_db->count_all_results($this->_table);
    }

    function importdata($id, $record) {
        // cek, jika sdh ada, update
        // kalau blm ada, insert
        $this->_db->where('IDPresence', $id);
        if ($this->_db->count_all_results($this->_table) > 0) {
            $this->update($id, $record);
        } else {
            $this->insert($record);
        }
    }

    function get_presence_date($id, $date) {
        $actual_in = $actual_out = $manual_in = $manual_out = $workday = NULL;
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('PresenceDate', $date);
        $query = $this->_db->get($this->_table);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $actual_in = $row->ActualIn;
            $actual_out = $row->ActualOut;
            $manual_in = $row->ManualIn;
            $manual_out = $row->ManualOut;
            $workday = $row->WorkDay;
        }
        return array("ActualIn" => $actual_in, "ActualOut" => $actual_out, "ManualIn" => $manual_in, "ManualOut" => $manual_out, "WorkDay" => $workday);
    }

    function get_all_join_1($fromdate, $untildate, $job = '') {
        $query = "     
            SELECT  

                    a.IDEmployee ,
                    a.Description,
		    a.ActualIn,	
		    a.ActualOut,
                    a.ManualIn,	    
                    a.ManualOut,	    
                    a.Necessity,
                    a.PresenceDate,
                    b.FullName,
                    b.IDJobGroup,
                    b.HireDate,
                    b.ResignDate,
                    c.Description AS rDescription

            FROM 
                    t03presence a
            JOIN 
                    isib_employee.m01personal  b
            ON
                    b.IDEmployee = a.IDEmployee
            LEFT JOIN 
                    r04typeattendance c
            ON
                    a.Description= c.IDType  
            WHERE             
                     (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
                     (b.Status='A') AND
                     (b.HireDate <= '$untildate') AND
                     (b.IDJobGroup ='$job')

            ORDER BY

                    b.FullName ASC,
                    a.PresenceDate ASC

        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_all_join($fromdate, $untildate, $job = '') {
        $query = "     
    SELECT 
            
            a.IDEmployee ,
            a.Description,
            a.PresenceDate,
            a.ActualIn,
            a.ActualOut,
            a.ManualIn,
            a.ManualOut,
            b.FullName,
            b.IDJobGroup,
            b.HireDate,
            b.ResignDate
            
    FROM 
            t03presence a
    JOIN 
            isib_employee.m01personal  b
    ON
            b.IDEmployee = a.IDEmployee
    WHERE             
            (b.IDJobGroup ='$job') AND
            (    
            (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
            (b.Status='A') OR ((b.Status='P') AND (b.ResignDate BETWEEN '$fromdate' AND '$untildate'))            
            )    
    ORDER BY
            
            b.FullName ASC,
            a.PresenceDate ASC
     
        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_all_join_new($fromdate, $untildate, $job = '') {
        $query = "     
            SELECT  

                    a.IDEmployee ,
                    a.Description,
                    a.PresenceDate,
                    b.FullName,
                    b.IDJobGroup,
                    b.HireDate,
                    b.ResignDate,
                    c.Description AS rDescription

            FROM 
                    t03presence a
            JOIN 
                    isib_employee.m01personal  b
            ON
                    b.IDEmployee = a.IDEmployee
            LEFT JOIN 
                    r04typeattendance c
            ON
                    a.Description= c.IDType  
            WHERE             
                     a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND
                     b.Status='A' AND
                     b.IDJobGroup ='$job'

            ORDER BY

                    b.FullName ASC,
                    a.PresenceDate ASC

        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_all_join_staff($fromdate, $untildate) {
        $query = "     
        SELECT
                a.IDEmployee AS IDEmployee,
                b.FullName   AS FullName,
                a.PresenceDate AS PresenceDate,
                a.WorkDay    AS WorkDay,
                a.ActualIn   AS ActualIn,
                a.ActualOut  AS ActualOut,
                a.ManualIn   AS ManualIn,
                a.ManualOut  AS ManualOut,
                b.HireDate   AS HireDate,
                b.ResignDate AS ResignDate,
                a.Description AS Description
               

        FROM 
                isib_attendance.t03presence a
        JOIN
                isib_employee.m01personal b
        ON
                b.IDEmployee = a.IDEmployee           
        WHERE 
                (b.IDJobGroup = 'ST') AND
                (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
                (ISNULL(a.ActualIn) OR ISNULL(a.ActualOut)) AND
                (ISNULL(a.ManualIn) OR ISNULL(a.ManualOut)) AND
                (a.WorkDay <> 'SUN') AND
                ((b.Status = 'A') OR ((b.Status = 'P') AND (b.ResignDate BETWEEN '$fromdate' AND '$untildate')) )
        ORDER BY
        b.IDJobGroup DESC,
        b.FullName ASC,
        a.PresenceDate ASC


        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_all_join_staff_cuti($fromdate, $untildate) {
        $query = "     
    SELECT
            a.IDEmployee,
            b.FullName,
            b.IDJobGroup,
            a.LeaveDate,
            a.UntilDate,
            a.TypeLeave
     FROM
            t09leave a
     JOIN 
            isib_employee.m01personal b
     ON
            b.IDEmployee=a.IDEmployee
     WHERE 
            (b.IDJobGroup='ST') AND
            (a.LeaveDate BETWEEN '$fromdate' AND '$untildate') 
    ORDER BY
            b.IDJobGroup DESC,
            b.FullName ASC,
            a.LeaveDate ASC
     
        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_all_join_staff_sakit($fromdate, $untildate) {
        $query = "     
        SELECT
                a.IDEmployee,
                b.FullName,
                b.IDJobGroup,
                a.SicknessDate,
                a.UntilDate
         FROM
                t06sicknessleave a
         JOIN 
                isib_employee.m01personal b
         ON
                b.IDEmployee=a.IDEmployee
         WHERE  
                (b.IDJobGroup='ST') AND
                (a.SicknessDate BETWEEN '$fromdate' AND '$untildate') AND 
                (a.SicknessLetter='Y')
         ORDER BY
                b.IDJobGroup DESC,
                b.FullName ASC
     
        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_all_join_staff_dinas($fromdate, $untildate) {
        $query = "     
            SELECT
                    a.IDEmployee,
                    b.FullName,
                    b.IDJobGroup,
                    a.OfficialTravelDate,
                    a.UntilDate
            FROM
                    t07officialtravel a
            JOIN 
                    isib_employee.m01personal b
            ON
                    b.IDEmployee=a.IDEmployee
            WHERE 
                    (b.IDJobGroup='ST') AND
                    (a.OfficialTravelDate BETWEEN '$fromdate' AND '$untildate') 
            ORDER BY
                    b.IDJobGroup DESC,
                    b.FullName ASC
     
        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_all_join_report03($idemployee, $fromdate, $untildate) {
        $query = "     
    SELECT  
            
            a.IDEmployee ,
            b.FullName,
            b.IDJobGroup,
            a.PresenceDate,
            a.ActualIn,           
            a.LateHour            
            
    FROM 
            t03presence a
    JOIN 
            isib_employee.m01personal  b
    ON
            b.IDEmployee = a.IDEmployee   
    WHERE             
            a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND
            a.IDEmployee ='$idemployee'
                
    ORDER BY
            
            b.FullName ASC,
            a.PresenceDate ASC
     
        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_all_join_report05($fromdate, $untildate, $idjobgroup) {
        $query = "     
    SELECT  
            
            a.IDEmployee ,
            b.FullName,
            b.IDJobGroup,
            a.PresenceDate,
            a.DayOfWeek,
            a.Description,
            a.Note            
            
    FROM 
            t03presence a
    JOIN 
            isib_employee.m01personal  b
    ON
            b.IDEmployee = a.IDEmployee   
    WHERE             
            a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND
            b.IDJobGroup ='$idjobgroup'
                
    ORDER BY
            
            b.FullName ASC,
            a.PresenceDate ASC
     
        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_join_presence_personal_rpt14($idemployee, $fromdate, $untildate) {
        $query = "     
   SELECT              
            a.IDEmployee ,
            b.FullName,
            b.IDJobGroup,
            a.PresenceDate,
            a.ActualIn,
            a.ActualOut,	    
            a.DayOfWeek,
            a.Description,
            c.Description AS rDescription,
            a.Note            
            
    FROM 
            t03presence a
    JOIN 
            isib_employee.m01personal  b
    ON
            b.IDEmployee = a.IDEmployee            
    LEFT JOIN 
            r04typeattendance c
    ON
            a.Description= c.IDType  
    WHERE   
           (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
           (b.IDEmployee ='$idemployee') AND
           (b.Status ='A') AND
           ( (a.PresenceDate >= b.HireDate) AND (a.PresenceDate <= '$untildate') )
    ORDER BY           
            b.FullName ASC,
            a.PresenceDate ASC
     
        ";
//            a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND
//            b.Status ='A' AND
//            b.IDEmployee ='$idemployee'            
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_join_presence_personal($idemployee, $fromdate, $untildate) {
        $query = "     
   SELECT              
            a.IDEmployee ,
            b.FullName,
            b.IDJobGroup,
            a.PresenceDate,
            a.ActualIn,
            a.ActualOut,	    
            a.ManualIn,	    
            a.ManualOut,	    
            a.Necessity,	    
            a.DayOfWeek,
            a.Description,
            c.Description AS rDescription,
            a.Note            
            
    FROM 
            t03presence a
    JOIN 
            isib_employee.m01personal  b
    ON
            b.IDEmployee = a.IDEmployee            
    LEFT JOIN 
            r04typeattendance c
    ON
            a.Description= c.IDType  
    WHERE             
            a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND
            b.Status ='A' AND
            b.IDEmployee ='$idemployee'            
    ORDER BY           
            b.FullName ASC,
            a.PresenceDate ASC
     
        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_presence_nowdate_all($nowdate, $idjobgroup) {
        $query = "     
    SELECT
            a.IDEmployee,
            a.FullName,
            a.IDJobGroup,
            b.PresenceDate,
            b.DayOfWeek,
            b.ActualIn,
            b.ActualOut,
            b.ManualIn,
            b.ManualOut,
            b.ActualHour,
            b.WorkHour,
            b.LateHour,
            b.Description,
            c. Description AS rDescription
     FROM
            isib_employee.m01personal a
     JOIN 
            t03presence b
     ON
            b.IDEmployee=a.IDEmployee
     LEFT JOIN 
            r04typeattendance c
     ON
            b.Description= c.IDType     
     WHERE 
                      
            b.PresenceDate = '$nowdate' AND
            a.IDJobGroup ='$idjobgroup' AND
            a.Status = 'A'
    ORDER BY            
            a.FullName ASC
            
     
        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_presence_nowdate_idemployee($nowdate, $idemployee) {
        $query = "     
    SELECT
            a.IDEmployee,
            a.FullName,
            a.IDJobGroup,
            b.PresenceDate,
            b.DayOfWeek,
            b.ActualIn,
            b.ActualOut,
            b.ManualIn,
            b.ManualOut,
            b.ActualHour,
            b.WorkHour,
            b.LateHour,
            b.Description,
            c. Description AS rDescription
     FROM
            isib_employee.m01personal a
     JOIN 
            t03presence b
     ON
            b.IDEmployee=a.IDEmployee
     LEFT JOIN 
            r04typeattendance c
     ON
            b.Description= c.IDType     
     WHERE 
             b.PresenceDate = '$nowdate' AND 
             a.IDEmployee ='$idemployee' AND
             a.Status = 'A'
           
    ORDER BY            
            a.FullName ASC
            
     
        ";
        //die($query);
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_imk($id, $presence) {
        $query = "
                   SELECT
                        a.IDEmployee,
                        a.PresenceDate,
                        a.IMKOut,
                        a.IMKIn
                    FROM
                        t03presence a
                    WHERE
                        a.IDEmployee='$id' AND
                        a.PresenceDate='$presence'
                ";
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result;
        }
        return NULL;
    }

    function update_lastpresence($id, $presence, $record) {
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('PresenceDate', $presence);
        $this->_db->update($this->_table, $record);
    }

    function update_necessity($id, $presence, $record) {
        $this->_db->where('IDEmployee', $id);
        $this->_db->where('PresenceDate', $presence);
        $this->_db->update($this->_table, $record);
    }

    function get_all_rpt14($fromdate, $untildate, $job = '') {
        $query = "     
            SELECT  

                    a.IDEmployee ,
                    a.Description,
		    a.ActualIn,	
		    a.ActualOut,	
                    a.PresenceDate,
                    b.FullName,
                    b.IDJobGroup,
                    b.HireDate,
                    b.ResignDate,
                    c.Description AS rDescription

            FROM 
                    t03presence a
            JOIN 
                    isib_employee.m01personal  b
            ON
                    b.IDEmployee = a.IDEmployee
            LEFT JOIN 
                    r04typeattendance c
            ON
                    a.Description= c.IDType  
            WHERE     
            
                    (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
                    (b.Status ='A') AND
                    (b.IDJobGroup ='$job') AND
                    ((a.PresenceDate >= b.HireDate) AND (a.PresenceDate <= '$untildate') )
                         

            ORDER BY

                    b.FullName ASC,
                    a.PresenceDate ASC

        ";
        //die($query);
//      (a.PresenceDate BETWEEN '$fromdate' AND '$untildate') AND
//      (b.Status='A') AND
//      (b.HireDate <= '$untildate') AND
//      (b.IDJobGroup ='$job')

        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function get_by_periode($fromdate, $untildate) {
        $sql = "
                    SELECT 
                        a.IDEmployee,
                        a.PresenceDate,
			a.Description
                    FROM
                        t03presence a
                    WHERE
                        a.PresenceDate BETWEEN '$fromdate' AND '$untildate'
                    ORDER BY 
                         a.PresenceDate ASC    
                    ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

    function update_holiday($nip, $date, $record) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PresenceDate', $date);
        $this->_db->update($this->_table, $record);
    }

    function get_date($date) {
        $query = "SELECT
                a.*
               FROM
               t03presence a
               WHERE            
               a.PresenceDate ='$date'         
            ";
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return null;
    }

    function troubledate($date, $location) {
        $rdate = date('Y-m-d', strtotime($date));
        if ($location == 'All') {
            $where = "
                     WHERE 
                        (a.PresenceDate = '$rdate') AND
                        (b.IDJobGroup IN ('ST','LT','LK','HL','MAG','OS')) AND
                        (( (isnull(a.ActualIn) OR isnull(a.ActualOut)) ) AND ( (isnull(a.ManualIn) OR isnull(a.ManualOut)) )) AND
                        (a.Description = 'A') AND
                        ( (b.Status='A') AND (b.HireDate <= '$rdate') OR (b.Status='P') AND (b.ResignDate >= '$rdate') )
                ";
        } else if ($location == 'Kapuk') {
            $where = "
                     WHERE 
                        (a.PresenceDate = '$rdate') AND
                        (b.IDJobGroup IN ('ST','LT','LK','HL','MAG','OS') AND IDLocation='1') AND
                        (( (isnull(a.ActualIn) OR isnull(a.ActualOut)) ) AND ( (isnull(a.ManualIn) OR isnull(a.ManualOut)) )) AND
                        (a.Description = 'A') AND
                        ( (b.Status='A') AND (b.HireDate <= '$rdate') OR (b.Status='P') AND (b.ResignDate >= '$rdate') )
                ";
        } else if ($location == 'Bitung') {
            $where = "
                     WHERE 
                        (a.PresenceDate = '$rdate') AND
                        (b.IDJobGroup IN ('ST','LT','LK','HL','MAG','OS') AND IDLocation='2') AND
                        (( (isnull(a.ActualIn) OR isnull(a.ActualOut)) ) AND ( (isnull(a.ManualIn) OR isnull(a.ManualOut)) )) AND
                        (a.Description = 'A') AND
                        ( (b.Status='A') AND (b.HireDate <= '$rdate') OR (b.Status='P') AND (b.ResignDate >= '$rdate') )
                ";
        }

        $query =
                "SELECT 
                    a.IDEmployee, 
                    b.FullName, 
                    b.IDJobGroup, 
                    b.Status, 
                    b.HireDate, 
                    ifnull(b.ResignDate,'2020-02-02'), 
                    a.PresenceDate, 
                    a.WorkDay, 
                    a.ActualIn, 
                    a.ActualOut, 
                    a.ManualIn,
                    a.ManualOut, 
                    a.Description 
                FROM 
                     isib_attendance.t03presence a 
                JOIN 
                     isib_employee.m01personal b 
                ON 
                     b.IDEmployee=a.IDEmployee 
                     
                $where
                    
                ORDER BY 
                    b.IDJobGroup DESC, 
                    b.FullName ASC, 
                    a.PresenceDate ASC 
                        ";

        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }

        return null;
    }

    function troubledate_rawdata($date, $location) {
        $rdate = date('Y-m-d', strtotime($date));
        if ($location == 'All') {
            $this->absencein_all($rdate);
        } else if ($location == 'Kapuk') {
            $this->absencein_kapuk($rdate);
        } else if ($location == 'Bitung') {
            $this->absencein_bitung($rdate);
        }
    }

    function checkdataatl($idemp, $date, $timein, $timeout, $note, $record) {
        $this->_db->where('IDEmployee', $idemp);
        $this->_db->where('IncompleteDate', $date);
        $this->_db->where('TimeIn', $timein);
        $this->_db->where('TimeOut', $timeout);
        $this->_db->where('Note', $note);

        $result = $this->_db->get('t05incomplete');
        if ($result->num_rows() > 0) {
            $this->updateatl($idemp, $date, $timein, $timeout, $note, $record);
        } else {
            $this->insertatl($record);
        }
    }

    function insertatl($record) {
        $this->_db->insert('t05incomplete', $record);
    }

    function updateatl($idemp, $date, $timein, $timeout, $note, $record) {
        $this->_db->where('IDEmployee', $idemp);
        $this->_db->where('IncompleteDate', $date);
        $this->_db->where('TimeIn', $timein);
        $this->_db->where('TimeOut', $timeout);
        $this->_db->where('Note', $note);

        $this->_db->update('t05incomplete', $record);
    }

    function absencein_all($rdate) {
        $query =
                "SELECT 
                    a.IDEmployee, 
                    b.FullName, 
                    b.IDJobGroup, 
                    b.Status, 
                    b.HireDate, 
                    ifnull(b.ResignDate,'2020-02-02'), 
                    a.PresenceDate, 
                    a.PresenceTime                   
                FROM 
                     isib_attendance.t02rawdata a 
                JOIN 
                     isib_employee.m01personal b 
                ON 
                     b.IDEmployee=a.IDEmployee 
                     
                WHERE 
                        (a.PresenceDate = '$rdate' AND a.Direction='0') AND
                        (b.IDJobGroup IN ('ST','LT','LK','HL','MAG','OS')) AND                       
                        ( (b.Status='A') AND (b.HireDate <= '$rdate') OR (b.Status='P') AND (b.ResignDate >= '$rdate') )
                    
                GROUP BY
                  a.IDEmployee                   
                ORDER BY
                  a.PresenceTime Desc  
                     ";

        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }

        return null;
    }

    function absencein_kapuk($rdate) {
        $query =
                "SELECT 
                    a.IDEmployee, 
                    b.FullName, 
                    b.IDJobGroup, 
                    b.Status, 
                    b.HireDate, 
                    ifnull(b.ResignDate,'2020-02-02'), 
                    a.PresenceDate, 
                    a.PresenceTime                   
                FROM 
                     isib_attendance.t02rawdata a 
                JOIN 
                     isib_employee.m01personal b 
                ON 
                     b.IDEmployee=a.IDEmployee 
                     
                WHERE 
                        (a.PresenceDate = '$rdate' AND a.Location='1' AND a.Direction='0') AND
                        (b.IDJobGroup IN ('ST','LT','LK','HL','MAG','OS')) AND                       
                        ( (b.Status='A') AND (b.HireDate <= '$rdate') OR (b.Status='P') AND (b.ResignDate >= '$rdate') )
                
               GROUP BY
                  a.IDEmployee                   
                ORDER BY
                  a.PresenceTime Desc  
                 

                     ";

        $result = $this->_attendance->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }

        return null;
    }

    function absencein_bitung($rdate) {
        $query =
                "SELECT 
                    a.IDEmployee, 
                    b.FullName, 
                    b.IDJobGroup, 
                    b.Status, 
                    b.HireDate, 
                    ifnull(b.ResignDate,'2020-02-02'), 
                    a.PresenceDate, 
                    a.PresenceTime                   
                FROM 
                     isib_attendance.t02rawdata a 
                JOIN 
                     isib_employee.m01personal b 
                ON 
                     b.IDEmployee=a.IDEmployee 
                     
                WHERE 
                        (a.PresenceDate = '$rdate' AND a.Location='2' AND a.Direction='0') AND
                        (b.IDJobGroup IN ('ST','LT','LK','HL','MAG','OS')) AND                       
                        ( (b.Status='A') AND (b.HireDate <= '$rdate') OR (b.Status='P') AND (b.ResignDate >= '$rdate') )
                    
                GROUP BY
                  a.IDEmployee                   
                ORDER BY
                  a.PresenceTime Desc  
                     ";

        $result = $this->_attendance->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }

        return null;
    }

}
?>

