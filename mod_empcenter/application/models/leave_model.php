<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Leave_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->_db = $this->load->database('empcenter', TRUE);
        $this->_pbl = $this->load->database('public', TRUE);
        $this->_at = $this->load->database('attendance', TRUE);
        $this->_tbl1 = 'm01personal';
        $this->_tbl2 = 'm02leave';
        $this->_tbl3 = 't01leavetrx';
        $this->_tbl4 = 'r02holiday';
        $this->_tbl5 = 'hist01leavetrx';
        $this->_tbl6 = 'm03organization';
        $this->_tbl7 = 'm04param';
        $this->_tbl8 = 'm02leave_reserve';
        $this->_job = 'isib_public.m01personal_job';
        $this->_presence = 't03presence';
        $this->_picket = 't12employeepicket';
        $this->_holiday = 'isib_employee.r02holiday';
    }

    function get_mastercuti($nip) {
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('Keterangan', 'Cuti Tahunan');
        $this->_db->order_by('ID', 'DESC');
        $result = $this->_db->get($this->_tbl2);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
    }

    /* 	
      function checkald_on_thisyears($nip, $fromdate, $untildate) {
      $sql = "SELECT * FROM  $this->_presence"
      . " WHERE IDEmployee ='$nip'  AND PresenceDate BETWEEN '$fromdate' AND '$untildate' AND Description='ALD'";

      $result = $this->_at->query($sql);
      if ($result->num_rows() > 0) {
      return 'exist';
      } else {
      return 'empty';
      }
      }
     */

    function checkald_on_thisyears($nip, $fromdate, $untildate) {
        $sql = "SELECT * FROM  $this->_tbl3 
                WHERE IDEmployee ='$nip' AND
                      TglPengajuan BETWEEN '$fromdate' AND '$untildate' AND
                      FlagInput='sys' AND
                      Jenis='AL' AND
                      Alasan like '%CUTI MASSAL LEBARAN%' ";

        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return 'exist';
        } else {
            return 'empty';
        }
    }

    function checkpicket($nip, $fromdate, $untildate) {
        $checkdate = ($fromdate == '' or $fromdate == null) ? 'empty' : 'exist';


        if ($checkdate !== 'empty') {
            $this->_at->where('FromDate >=', $fromdate);
            $this->_at->where('UntilDate <=', $untildate);
            $this->_at->where('IDEmployee', $nip);
            $this->_at->where('DeleteFlag', 'A');
            $this->_at->where('StatusPicket', 'A');
            $result = $this->_at->get($this->_picket);
            if ($result->num_rows() > 0) {
                return 'exist';
            } else {
                return 'empty';
            }
        }else{            
            return 'empty';
        }
    }

    function rowpicket($nip, $fromdate, $untildate, $param) {
        $checkdate = ($fromdate == '' or $fromdate == null) ? 'empty' : 'exist';

        if ($checkdate !== 'empty') {
            $this->_at->where('FromDate >=', $fromdate);
            $this->_at->where('UntilDate <=', $untildate);
            $this->_at->where('IDEmployee', $nip);
            $this->_at->where('DeleteFlag', 'A');
            if ($param == 'from') {
                $this->_at->order_by('FromDate', 'ASC');
            } else if ($param == 'until') {
                $this->_at->order_by('UntilDate', 'DESC');
            }

            $result = $this->_at->get($this->_picket);
            if ($result->num_rows() > 0) {
                $row = $result->row();
                if ($param == 'from') {
                    $data = date('Y-m-d', strtotime($row->FromDate));
                } else {

                    $data = date('Y-m-d', strtotime($row->UntilDate));
                }

                return $data;
            } else {
                return 'empty';
            }
        }else{
            return 'empty';
            
        }
    }

    function sumpresecepicket($nip, $from, $until) {
        $sql = "SELECT COUNT(PresenceDate) AS AmountPicket FROM  $this->_presence"
                . " WHERE IDEmployee ='$nip'  AND PresenceDate BETWEEN '$from' AND '$until'  AND"
                . " WorkDay NOT IN('OFF','SUN') ";

        $result = $this->_at->query($sql);
        if ($result->num_rows() > 0) {
            $row = $result->row();
            $amount = $row->AmountPicket;
            if ($amount > 0) {
                return $amount;
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function get_holidaypicket($from, $until) {
        $sql = "SELECT COUNT(*) AS jumlahlibur FROM $this->_holiday WHERE Date BETWEEN '$from' AND '$until'"
                . " AND Flag NOT IN ('ALD') AND DeleteFlag='A' ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_param($idparam) {
        $this->_db->where("IDParam", $idparam);
        return $this->_db->get($this->_tbl7);
    }

    function get_holiday() {
        $this->_db->select("Date");
        return $this->_db->get($this->_tbl4);
    }

    function getleave($wh) {
        $this->_db->where($wh);
        return $this->_db->get($this->_tbl3);
    }

    function get_data($uid, $masuk, $skr) {
        $query = $this->_db->query("
        SELECT A.* FROM (
            SELECT m.TglMaster as Tanggal, m.Jml as Jml
            FROM $this->_tbl2 m
            WHERE IDEmployee = '$uid' and DeleteFlag = 'A' and TglMaster >= '$masuk' and TglMaster <= '$skr' 
            UNION ALL
            SELECT t.TglCutiDari as Tanggal, (-1)*t.TotalCuti as Jml
            FROM $this->_tbl3 t
            WHERE 
                IDEmployee = '$uid' and 
                TglCutiDari >= '$masuk' and 

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
            return NULL;
        }
    }

    function get_employee($userid) {
        $this->_db->where('IDEmployee', $userid);
        return $this->_db->get($this->_tbl1);
    }

    function get_pengganti($idpengganti = NULL, $userid = NULL) {
        if ($idpengganti == NULL) {
            $query = $this->_db->query("
            SELECT IDEmployee, FullName as label 
            FROM $this->_tbl1
            WHERE Status = 'A' and IDEmployee != '$userid'
            ORDER BY FullName ASC
            ");
        } else {
            $this->_db->where("IDEmployee", $idpengganti);
            $query = $this->_db->get($this->_tbl1);
        }
        return $query;
    }

    function get_tglterakhir($uid) {
        $query = $this->_db->query("
        SELECT MAX(A.Tanggal) as Tanggal
        FROM (
            SELECT TglMaster AS Tanggal
            FROM $this->_tbl2
            WHERE IDEmployee = '$uid'
                AND DeleteFlag = 'A'
            UNION ALL
            SELECT TglCutiDari AS Tanggal
            FROM $this->_tbl3
            WHERE IDEmployee = '$uid' and Jenis = 'AL' and DeleteFlag = 'A'
        ) A		
        ");
        return $query;
    }

    function get_transaksi_all($uid) {
        $query = $this->_db->query("
        SELECT T.*, M.FullName as Pengganti 
        FROM $this->_tbl3 T
        LEFT JOIN $this->_tbl1 M
        ON T.IDPengganti = M.IDEmployee
        WHERE T.IDEmployee = '$uid' AND T.DeleteFlag = 'A'
        ORDER BY T.TglPengajuan ASC
        ");
        return $query;
    }

    function get_accepted($user, $state) {
        $as = $state == "pic" ? "FPgt_tgl" : ($state == "hod" ? "FAts_tgl" : "FHrd_tgl");
        $aswh = $state == "pic" ? "PgtID" : ($state == "hod" ? "AtsID" : "HrdID");
        $this->datatables->select("B.FullName AS Name, A.TglCutiDari AS Dari, A.TglCutiSampai AS Sampai, A.TotalCuti AS Total, A.Alasan AS Reason, A.Jenis As Jenis, A.$as AS ApprovalDate");
        $this->datatables->from($this->_tbl3 . " AS A");
        $this->datatables->join($this->_tbl1 . " AS B", "A.IDEmployee = B.IDEmployee");
        $this->datatables->where("A.$aswh = '$user' AND A.DeleteFlag = 'A'");
        return $this->datatables->generate();
    }

    function get_transaksi($where) {
        $this->_db->where($where);
        return $this->_db->get($this->_tbl3);
    }

    function get_depart($where) {
        $this->_db->where($where);
        return $this->_db->get($this->_tbl6);
    }

    function get_by_id_request($id) {
        $sql = "SELECT a.*,b.FullName AS Name,b.HireDate,c.Position,g.DescStructure,d.Jml,e.FullName AS Pengganti,f.FullName AS Parent
             FROM $this->_tbl3 a
             LEFT JOIN $this->_tbl1 b
             ON a.IDEmployee = b.IDEmployee
             LEFT JOIN $this->_job c
             ON a.IDEmployee = c.IDEmployee
             LEFT JOIN $this->_tbl2 d
             ON a.IDEmployee = d.IDEmployee
             LEFT JOIN $this->_tbl1 e
             ON a.IDPengganti = e.IDEmployee
             LEFT JOIN $this->_tbl1 f
             ON b.IDEmployeeParent = f.IDEmployee
             LEFT JOIN $this->_tbl6 g
             ON c.Department = g.IDStructure
             WHERE
                a.IDLeave='$id' AND
                a.DeleteFlag='A' AND
                b.Status='A'     AND
                d.DeleteFlag='A'
              ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_res($where) {
        $this->_db->where($where);
        return $this->_db->get($this->_tbl8);
    }

    function upd_res($wh, $rec) {
        $this->_db->where($wh);
        $this->_db->update($this->_tbl8, $rec);
    }

    function ins_hist($idleave) {
        $this->_db->query("
        INSERT INTO $this->_tbl5
        SELECT '' AS ID, $this->_tbl3.* 
                FROM $this->_tbl3
                WHERE IDLeave = $idleave
        ");
    }

    function insert($record) {
        $this->_db->insert($this->_tbl3, $record);
    }

    function update($where, $record) {
        $this->_db->where($where);
        $this->_db->update($this->_tbl3, $record);
    }

    function delete($where) {
        $this->_db->where($where);
        $this->_db->delete($this->_tbl3);
    }

    function get_prs_public($iduser) {
        $this->_pbl->where("IDEmployee", $iduser);
        return $this->_pbl->get($this->_tbl1);
    }

}

