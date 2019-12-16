<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cardmap_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_table = 'm02cardmap';
        $this->_employee = 'isib_employee.m01personal';
    }

    function allcard() {
        $a = $this->_table;
        $b = 'isib_employee.m01personal';
        $this->datatables->select("$a.ID AS ID,
                                 $a.IDCard AS IDCard,                              
                                 $a.IDEmployee AS IDEmployee,
				 $b.IDJobGroup AS IDJobGroup,
				 $a.LastStatus AS LastStatus,
                                 IF($a.CardType ='1','Barcode',
                                 IF($a.CardType ='2','RFID','-')) AS TypeCard, 
                                 IF($a.LastStatus ='T','Active',
                                 IF($a.LastStatus ='F','Passive','-')) AS Status,      
                                 $a.CardNumber AS CardNumber,                                                         
                                 $b.FullName AS FullName,
                                 IF($b.IDJobGroup ='ST','STAFF',    
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
				 IF($b.IDJobGroup ='OS','MITRA KERJA',  	
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN',
                                 IF($b.IDJobGroup ='MAG','MAGANG','-'))))))) AS JobGroup    
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->where("$a.DeleteFlag", "A");
        $this->datatables->where("$b.DeleteFlag", "A");
        return $this->datatables->generate();
    }

    function get_by_id($id) {
        $this->_db->select('a.*,b.FullName');
        $this->_db->from($this->_table . ' a');
        $this->_db->join($this->_employee . ' b', 'b.IDEmployee = a.IDEmployee', 'LEFT');
        $this->_db->where('a.ID', $id);
        $this->_db->where('a.DeleteFlag', 'A');
        $this->_db->where('b.DeleteFlag', 'A');
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return 'empty';
        }
    }

     function getall_data($group,$status) {
        $this->_db->from($this->_table . ' a');
        $this->_db->join($this->_employee . ' b', 'b.IDEmployee = a.IDEmployee', 'LEFT');
        $this->_db->where('a.DeleteFlag', 'A');
        $this->_db->where('b.DeleteFlag', 'A');
        ($group=='AL')?'':$this->_db->where('b.IDJobGroup',$group); 
        ($status=='AL')?'':$this->_db->where('a.LastStatus',$status);         
        $this->_db->order_by('b.IDJobGroup', 'DESC');
        $this->_db->order_by('b.FullName', 'ASC');
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return 'empty';
        }
    }

    function checkdata($enroll) {
        $this->_db->where('IDCard', $enroll);
        $result = $this->_db->get($this->_table);
        if ($result->num_rows() > 0) {
            return 'exist';
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


