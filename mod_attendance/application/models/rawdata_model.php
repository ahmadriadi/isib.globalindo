<?php

class Rawdata_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('attendance', TRUE);
        $this->_table = 't02rawdata';
        $this->_employee = 'isib_employee.m01personal';
    }

    function allrawdata($from, $until) {
        $a = $this->_table;
        $b = 'isib_employee.m01personal';
        $this->datatables->select("$a.DataText AS DataText,
                                 $a.IDCard AS IDCard,
                                 $a.IDEmployee AS IDEmployee,
                                 $a.PresenceDate AS PresenceDate,
                                 $a.PresenceTime AS PresenceTime,
                                 $b.IDJobGroup AS IDJobGroup,    
                                 IF($a.Direction='1','IN',
                                 IF($a.Direction='0','OUT','-')) AS AbsenStatus, 
                                 IF($a.Location='1','KAPUK',
                                 IF($a.Location='2','BITUNG','-')) AS AbsenLocation,    
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
        $this->datatables->where("$a.PresenceDate >=", date('Y-m-d', strtotime($from)));
        $this->datatables->where("$a.PresenceDate <=", date('Y-m-d', strtotime($until)));
        $this->datatables->where("$b.DeleteFlag",'A');
        return $this->datatables->generate();
    }

    function getall_data($from,$until,$g) {
        $this->_db->select("a.*,b.FullName,b.IDJobGroup");
        $this->_db->from($this->_table . ' a');
        $this->_db->join($this->_employee . ' b', 'b.IDEmployee = a.IDEmployee', 'LEFT');
        $this->_db->where('b.DeleteFlag','A');
        $this->_db->where("a.PresenceDate >=", date('Y-m-d', strtotime($from)));
        $this->_db->where("a.PresenceDate <=", date('Y-m-d', strtotime($until)));
        ($g=='AL')?'':$this->_db->where('b.IDJobGroup',$g);        
        $this->_db->order_by('b.IDJobGroup','DESC');
        $this->_db->order_by('b.FullName','ASC');        
        $this->_db->order_by('a.PresenceDate','ASC');        
        $result = $this->_db->get();
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

}

?>


