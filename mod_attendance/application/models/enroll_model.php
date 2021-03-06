<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Enroll_model extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_db_emp = $this->load->database('empcenter', TRUE);
        $this->_personal = 'm01personal';
        $this->_card = 'm02cardmap';
        $this->_table = 'm03machine';
        $this->_employee = 'isib_employee.m01personal';
    }
    
    
   function find_employee_active(){     
	$sql = "
                SELECT a.*,b.IDCard,b.CardNumber FROM isib_employee.$this->_personal a
                LEFT JOIN isib_attendance.$this->_card b ON a.IDEmployee=b.IDEmployee
                WHERE
                a.Status ='A' AND
                b.LastStatus ='T' AND
                b.DeleteFlag ='A'

              ";
        $query = $this->_db_emp->query($sql);
        return $query;    
     
 } 
    
   function getall_data() {
        $a = $this->_table;       
        $this->datatables->select("$a.ID AS ID,
                                 $a.EnrollNumber AS EnrollNumber,                              
                                 $a.Name AS Name,                                   
                                 $a.Location AS Location                                   
                            ", FALSE);
        $this->datatables->from("$a");     
        return $this->datatables->generate();
    }

    function get_by_id($id) {
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
    
    function get_data(){  
       $this->_db->order_by('Name','ASC');
       $this->_db->order_by('Location','Desc');
       $result = $this->_db->get($this->_table);
       
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        } 
    }

    function checkdata($enroll, $record) {
        $this->_db->where('EnrollNumber', $enroll);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            $this->update($enroll, $record);
        } else {
            $this->insert($record);
        }
    }

    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table, $record);
    }    
    
    function delete($enroll,$loc){
      $sql = "DELETE FROM m03machine WHERE EnrollNumber='$enroll' and Location='$loc'";  
      $this->_db->query($sql);
         
    }

}
?>


