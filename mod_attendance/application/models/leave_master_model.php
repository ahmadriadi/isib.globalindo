<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leave_master_model extends CI_Model{
    public function __construct() {
        parent::__construct();
        date_default_timezone_set('Asia/Jakarta');
        $this->_db  = $this->load->database('empcenter',TRUE);
        $this->tblmaster    = "m02leave";
        $this->tblreserve   = "m02leave_reserve";
        $this->tblpersonal  = "m01personal";
        $this->tblleave     = "t01leavetrx";
    }
    function sisa_cuti($uid,$masuk,$skr){
        $query = $this->_db->query("	
        SELECT A.* FROM (
            SELECT m.TglMaster as Tanggal, m.Jml as Jml
            FROM $this->tblmaster m
            WHERE m.IDEmployee = '$uid' and m.DeleteFlag = 'A' and m.TglMaster >= '$masuk' and m.TglMaster < '$skr'
            UNION ALL
            SELECT t.TglPengajuan as Tanggal, (-1)*t.TotalCuti as Jml
            FROM $this->tblleave t
            WHERE 
                t.IDEmployee = '$uid' and 
                t.TglPengajuan >= '$masuk' and 
                t.TglPengajuan < '$skr' and
                t.FPgt = 'true' and
                t.FAts = 'true' and
                t.FHrd = 'true' and
                t.Jenis = 'AL' and t.DeleteFlag = 'A'
        ) A
        ORDER BY A.Tanggal ASC;
        ");
        return $query;
//        penghitungan sisa cuti di sini berbeda dengan penghitungan sisa cuti di aplikasi lain (leave request, report)
//        penghitungan di sini menghitung berapa banyak jumlah cuti yang diajukan dari pertama masuk sampai masa yang akan datang tanpa batasan waktu
//        mengapa demikian? karena jumlah di sini akan menjadi pengurang sisa cuti sekarang
//        sehingga cuti yang diajukan untuk periode selanjutnya
//        tidak akan mengurangi jumlah hak cuti baru pada periode selanjutnya tersebut,
//        kecuali jika sisa cuti di periode ini telah habis (jumlah cuti yang diajukan lebih besar dari jumlah hak cuti)
    }    
    function get_employee($where){
        $this->_db->where($where);
        return $this->_db->get($this->tblpersonal);
    }
    function get_emp_suggest($where=''){
        $query  = "
            SELECT IDEmployee AS idemp, FullName as label
            FROM $this->tblpersonal
                WHERE 
                Status = 'A'
                $where
            ";
        return $this->_db->query($query);
    }    
    function get_addition($where=''){
        $query  = "SELECT A.*,B.FullName FROM $this->tblmaster A JOIN $this->tblpersonal B ON A.IDEmployee = B.IDEmployee WHERE A.DeleteFlag = 'A' $where ";
        return $this->_db->query($query);
    }
    function add_addition($rec){
        if ($this->_db->insert($this->tblmaster,$rec)){
            return "oke";
        }else{
            return "bad";
        }
    }
    function add_deletion($rec){
        if ($this->_db->insert($this->tblleave,$rec)){
            return "oke";
        }else{
            return "bad";
        }
    }
    function add_reservation($rec){
        if ($this->_db->insert($this->tblreserve,$rec)){
            return "oke";
        }else{
            return "bad";
        }
    }
    function upd_addition($wh,$rec){
        $this->_db->where($wh);
        if ($this->_db->update($this->tblmaster,$rec)){
            return "oke";
        }else{
            return "bad";
        }
    }
    function upd_deletion($wh,$rec){
        $this->_db->where($wh);
        if ($this->_db->update($this->tblleave,$rec)){
            return "oke";
        }else{
            return "bad";
        }
    }
    function upd_reservation($wh,$rec){
        $this->_db->where($wh);
        if ($this->_db->update($this->tblreserve,$rec)){
            return "oke";
        }else{
            return "bad";
        }
    }
    function del_addition($wh,$rec){
        $this->_db->where($wh);
        if ($this->_db->update($this->tblmaster,$rec)){
            return "oke";
        }else{
            return "bad";
        }
    }
    function del_deletion($wh,$rec){
        $this->_db->where($wh);
        if ($this->_db->update($this->tblleave,$rec)){
            return "oke";
        }else{
            return "bad";
        }
    }
    function del_reservation($wh,$rec){
        $this->_db->where($wh);
        if ($this->_db->update($this->tblreserve,$rec)){
            return "oke";
        }else{
            return "bad";
        }
    }
    function get_deletion($where=''){
        $query = "SELECT A.*, B.FullName FROM $this->tblleave A JOIN $this->tblpersonal B ON A.IDEmployee = B.IDEmployee WHERE A.DeleteFlag = 'A' $where";
        return $this->_db->query($query);
    }
    function get_reserve($where=''){
        $query = "SELECT A.*, B.FullName FROM $this->tblreserve A JOIN $this->tblpersonal B ON A.IDEmployee = B.IDEmployee WHERE A.Status = '1' $where";
        return $this->_db->query($query);
    }
    function get_clearance($where){
        $this->_db->where($where);
        return $this->_db->get($this->tblreserve);
    }
    function cek_rejected($where){
        $this->_db->where($where);
        return $this->_db->get($this->tblleave);
    }    
}

