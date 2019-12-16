<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Specialslip_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('fieldpayroll', TRUE);
        $this->_table = 't05slip';
        $this->_mpayroll = 'm01fieldpayroll';
        $this->_employee = 'triasnet_employee.m01personal_d';
    }

    function getspecial_slip($posting, $id) {
        $query =
                "SELECT 
            b.FullName, b.IDUnitGroup,b.IDJobGroup,
            b.BankAccount AS BankAccountNo,
            a.* 
         FROM $this->_table a             
         JOIN $this->_employee b             
         ON b.IDEmployee=a.IDEmployee
         JOIN $this->_mpayroll c             
         ON c.IDEmployee=a.IDEmployee 
         WHERE a.PostingDate = '$posting' AND b.Status='A'  AND b.DeleteFlag='A'";
        if (strlen($id) > 0) {
            $query.= " AND a.IDEmployee='$id' ";
        }
        $query.=
                "ORDER BY 
            b.IDJobGroup DESC, 
            b.FullName ASC";
        $result = $this->_db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return NULL;
    }

}
?>



