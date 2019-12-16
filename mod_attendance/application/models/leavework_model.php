<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Leavework_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('empcenter', TRUE);
        $this->_db_at = $this->load->database('attendance', TRUE);
        $this->_table = 't11leavework';
        $this->_job = 'isib_public.m01personal_job';
        $this->_personal = 'isib_employee.m01personal';
        $this->_holiday = 'r02holiday';
        $this->_organization = 'm03organization';
    }

    function getdata_leavework($from, $until) {
        $a = $this->_table;
        $b = $this->_personal;
        $this->datatables->select("$a.ID AS ID,                                 
                                 $a.IDEmployee AS IDEmployee,
                                 $a.StartDate AS StartDate, 
                                 $a.FinishDate AS FinishDate,                                      
                                 CONCAT(IF($a.Day0='0','Sunday',''),' ',
                                        IF($a.Day1='1','Monday',''),' ', 
                                        IF($a.Day2='2','Tuesday',''),' ', 
                                        IF($a.Day3='3','Wednesday',''),' ', 
                                        IF($a.Day4='4','Thursday',''),' ', 
                                        IF($a.Day5='5','Friday',''),' ', 
                                        IF($a.Day6='6','Saturday','')                                             
                                        )AS OnDay,
                                 $a.LeaveHour AS LeaveHour, 
                                 $a.Note AS Note,                                                       
                                 $b.FullName AS FullName,
				 $b.IDJobGroup AS IDJobGroup,	
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
        $this->datatables->where("$a.StartDate >=", date('Y-m-d', strtotime($from)));
        $this->datatables->where("$a.StartDate <=", date('Y-m-d', strtotime($until)));
        $this->datatables->where("$a.DeleteFlag", "A");
        $this->datatables->where("$b.DeleteFlag", "A");
        return $this->datatables->generate();
    }

    function get_by_id($id) {
        $this->_db_at->select("a.*,b.FullName");
        $this->_db_at->from($this->_table . ' a');
        $this->_db_at->join($this->_personal . ' b', 'b.IDEmployee = a.IDEmployee', 'LEFT');
        $this->_db_at->where('a.ID', $id);
        $this->_db_at->where('a.DeleteFlag', 'A');
        $this->_db_at->where('b.DeleteFlag', 'A');
        $result = $this->_db_at->get();


        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
    }

   function getall_data($from, $until,$g) {
        $f = date('Y-m-d', strtotime($from));
        $u = date('Y-m-d', strtotime($until));
        $group = ($g=='AL')?'':" AND b.IDJobGroup='$g'";
        $sql = "  
               SELECT  a.*,
                        CONCAT(IF(a.Day0='0','Sunday',''),' ',
                               IF(a.Day1='1','Monday',''),' ', 
                               IF(a.Day2='2','Tuesday',''),' ', 
                               IF(a.Day3='3','Wednesday',''),' ', 
                               IF(a.Day4='4','Thursday',''),' ', 
                               IF(a.Day5='5','Friday',''),' ', 
                               IF(a.Day6='6','Saturday','')                                             
                          )AS OnDays,
                          b.FullName AS Name,
                          b.IDJobGroup
               FROM  $this->_table  a
               LEFT JOIN $this->_personal b ON a.IDEmployee = b.IDEmployee  
               WHERE
               a.DeleteFlag ='A' AND    
               b.DeleteFlag ='A' AND    
               a.StartDate BETWEEN '$f' AND '$u' $group
               ORDER BY
               b.IDJobGroup DESC,
               b.FullName ASC 
                

              ";
        $result = $this->_db_at->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return 'empty';
        }
    }

    function insert($record) {
        $this->_db_at->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db_at->where('ID', $id);
        $this->_db_at->update($this->_table, $record);
    }

}
?>


