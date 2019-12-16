<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Weeklyactivity_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('empcenter', TRUE);
        $this->_table = 't04weeklyactivity';
        $this->_employee_h = 'm01personal';
        $this->_employee = 'm01personal_d';
    }

    function get_child($iduser) {
        $query = "SELECT H.*, D.* 
                       FROM  $this->_employee_h H 
                       LEFT JOIN $this->_employee D ON H.IDEmployee = D.IDEmployee 
                       WHERE H.IDEmployeeParent = '$iduser'"
        ;
        return $this->_db->query($query);
    }

    function getdata($from, $until, $user, $status) {
        $a = $this->_table;
        $b = $this->_employee;
        $this->datatables->select("$a.ID AS ID,                                 
                                    $a.JobActivity AS JobActivity, 
				    $a.PIC AS PIC, 
                                    $b.FullName AS FullName, 	
                                    $a.DateLine AS DateLine, 
                                    $a.Tested AS Tested, 
				    $a.TestedNote AS TestedNote, 	
                                    $a.Note AS Note, 
                                    IF ($a.StatusActivity = '0', 'In Progress',
                                    IF ($a.StatusActivity = '1', 'Done',
                                    IF ($a.StatusActivity = '2', 'Pending',''
                                    ))) AS StatusActivity,     
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.PIC = $b.IDEmployee", 'left');
        if ($status == 'open') {
            $this->datatables->where("$a.DateLine >=", $from);
            $this->datatables->where("$a.DateLine <=", $until);
            $this->datatables->where("$a.DeleteFlag", "A");
        } else {

            $jumlaharray = count($user);
            if ($jumlaharray == '1') {
                $data = $user;
            } else {
                $filter = array_keys(array_flip($user));
                $data = implode(",", $filter);
            }

            $where = "$a.PIC IN($data)";


            $this->datatables->where("$a.DateLine >=", $from);
            $this->datatables->where("$a.DateLine <=", $until);
            $this->datatables->where($where);
            $this->datatables->where("$a.DeleteFlag", "A");
        }

        return $this->datatables->generate();
    }

    function getalldata($from, $until, $user, $status) {
        if ($status == 'open') {
            $where = "
                   WHERE
                    a.DeleteFlag='A' AND
                    a.Dateline BETWEEN '$from' AND '$until' 
                ";
        } else {

            $jumlaharray = count($user);
            if ($jumlaharray == '1') {
                $data = $user;
            } else {
                $filter = array_keys(array_flip($user));
                $data = implode(",", $filter);
            }


            $where = "
                   WHERE
                    a.DeleteFlag='A' AND
                    a.Dateline BETWEEN '$from' AND '$until' AND
                    a.PIC IN($data)    
                ";
        }


        $sql = "SELECT a.*,b.FullName FROM $this->_table a 
              LEFT Join $this->_employee b ON a.PIC = b.IDEmployee
              $where                
              ORDER BY
              b.FullName";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return 'empty';
        }
    }

    function getby_id($id) {
        $sql = "SELECT a.*,b.FullName FROM $this->_table a 
              LEFT Join $this->_employee b ON a.PIC = b.IDEmployee
              WHERE
              a.DeleteFlag='A' AND
              a.ID='$id'
              ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
    }

    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table, $record);
    }

}

?>
