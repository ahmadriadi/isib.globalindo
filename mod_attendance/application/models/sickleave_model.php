<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sickleave_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('empcenter', TRUE);
        $this->_table = 'isib_employee.t01leavetrx';
        $this->_job = 'isib_public.m01personal_job';
        $this->_masterleave = 'm02leave';
        $this->_reference = 'r03typeleave';
        $this->_personal = 'm01personal';
        $this->_holiday = 'isib_employee.r02holiday';
        $this->_organization = 'm03organization';  
    }

    function sickleaveforhrd($from, $until) {
        $a = $this->_table;
        $b = 'isib_employee.'.$this->_personal;
        $this->datatables->select("$a.IDLeave AS IDLeave,                                 
                                 $a.IDPengganti AS IDPengganti,
                                 $a.IDEmployee AS IDEmployee,   
                                 $a.TglPengajuan AS TglPengajuan,
                                 $a.TglCutiDari AS TglCutiDari,
                                 $a.TglCutiSampai AS TglCutiSampai,  
				 $b.IDJobGroup AS IDJobGroup,                              
                                 $a.Jenis  AS Jenis, 
                                 IF($a.SickLetter='1','With Letter','Without Letter')  AS SickLetter, 
                                 $a.TotalCuti AS TotalCuti,
                                 $a.SisaCuti AS SisaCuti,
                                 $a.Alasan AS Alasan,                              
                                 $b.FullName AS FullName,
                                 IF($b.IDJobGroup ='ST','STAFF',    
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN',
				 IF($b.IDJobGroup ='OS','MITRA KERJA',
                                 IF($b.IDJobGroup ='MAG','MAGANG','-'))))))) AS JobGroup   
                                 
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$a.TglCutiDari >=", date('Y-m-d', strtotime($from)));
        $this->datatables->where("$a.TglCutiDari <=", date('Y-m-d', strtotime($until)));
        $this->datatables->where("$a.DeleteFlag", "A");
        $this->datatables->where("$b.DeleteFlag", "A");
        $this->datatables->where("$a.Jenis", "SL");
        $this->datatables->where("$a.FPgt", "true");
        $this->datatables->where("$a.FAts", "true");
        $this->datatables->where("$a.FHrd", "true");
        return $this->datatables->generate();
    }

    function get_by_id($id) {
        $this->_db->select("a.*,b.FullName");
        $this->_db->from($this->_table . ' a');
        $this->_db->join($this->_personal . ' b', 'b.IDEmployee = a.IDEmployee', 'LEFT');
        $this->_db->where('a.IDLeave', $id);
        $this->_db->where('a.DeleteFlag', 'A');
        $this->_db->where('b.DeleteFlag', 'A');
        $this->_db->where('a.FlagInput', 'hrd');
        $result = $this->_db->get();


        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
    }
    
    function get_by_id_request($id) {
        $sql = "SELECT a.*,b.FullName AS Name,b.HireDate,c.Position,g.DescStructure,d.Jml,e.FullName AS Pengganti,f.FullName AS Parent
             FROM $this->_table a
             LEFT JOIN $this->_personal b
             ON a.IDEmployee = b.IDEmployee
             LEFT JOIN $this->_job c
             ON a.IDEmployee = c.IDEmployee
             LEFT JOIN $this->_masterleave d
             ON a.IDEmployee = d.IDEmployee
             LEFT JOIN $this->_personal e
             ON a.IDPengganti = e.IDEmployee
             LEFT JOIN $this->_personal f
             ON b.IDEmployeeParent = f.IDEmployee
             LEFT JOIN $this->_organization g
             ON c.Department = g.IDStructure
             WHERE
                a.IDLeave='$id' AND
                a.DeleteFlag='A' AND
                b.DeleteFlag='A' AND
                b.Status='A'             
              ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_type_sickleave($id) {
        $this->_db->where('IDLeave', $id);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
    }

    function getall_data($from, $until,$g) {
        $f = date('Y-m-d',  strtotime($from));
        $u = date('Y-m-d',  strtotime($until));
        $group = ($g=='AL')?'':" AND b.IDJobGroup ='$g'";
        $sql = "SELECT a.*,b.FullName AS Name,b.IDJobGroup,c.DescType,d.FullName AS PersonCharge
               FROM $this->_table a
               LEFT JOIN $this->_personal b ON a.IDEmployee = b.IDEmployee
               LEFT JOIN $this->_reference c ON a.Jenis = c.CodeType
               LEFT JOIN $this->_personal d ON a.IDPengganti = d.IDEmployee
               WHERE
               b.DeleteFlag ='A' AND
               a.DeleteFlag ='A' AND
               a.Jenis ='SL' AND
               a.FPgt ='true' AND a.FAts ='true' AND a.FHrd='true' AND
               a.TglCutiDari BETWEEN '$f' AND '$u' $group
               ORDER BY
               b.IDJobGroup DESC,
               b.FullName ASC,
               a.TglCutiDari DESC
              ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return 'empty';
        }
    }

    function getall_reference() {
        $this->_db->order_by('CodeType', 'ASC');
        $result = $this->_db->get($this->_reference);
        if ($result->num_rows() > 0) {
            return $result;
        } else {
            return null;
        }
    }

    function get_holiday($from, $until) {
        $sql = "SELECT COUNT(*) AS jumlahlibur FROM $this->_holiday WHERE Date BETWEEN '$from' AND '$until'";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_rest_sickleave_employee($nip) {
        $this->_db->where('IDEmployee', $nip);
        $result = $this->_db->get($this->_masterleave);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db->where('IDLeave', $id);
        $this->_db->update($this->_table, $record);
    }

    function update_master($id, $record) {
        $this->_db->where('IDEmployee', $id);
        $this->_db->update($this->_masterleave, $record);
    }

    function selisihHari($tglAwal, $tglAkhir) {
        // memecah string tanggal awal untuk mendapatkan
        // tanggal, bulan, tahun
        $pecah1 = explode("-", $tglAwal);
        $date1 = $pecah1[2];
        $month1 = $pecah1[1];
        $year1 = $pecah1[0];

        // memecah string tanggal akhir untuk mendapatkan
        // tanggal, bulan, tahun
        $pecah2 = explode("-", $tglAkhir);
        $date2 = $pecah2[2];
        $month2 = $pecah2[1];
        $year2 = $pecah2[0];

        // mencari selisih hari dari tanggal awal dan akhir
        $jd1 = GregorianToJD($month1, $date1, $year1);
        $jd2 = GregorianToJD($month2, $date2, $year2);

        $selisih = $jd2 - $jd1;

        // menghitung selisih hari
        return $selisih;
    }

}
?>


