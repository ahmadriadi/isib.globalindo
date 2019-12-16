<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Happybday_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('empcenter', TRUE);
        $this->_personal = 'm01personal_d';    
        $this->_departement = 'm03organization';
    }
    
    
   function get_hbd($month) {
        $a = $this->_personal;       
        $b = $this->_departement;
        $this->datatables->select("$a.IDEmployee AS IDEmployee,                                 
                                 $a.FullName AS FullName,                                  
                                 DATE_FORMAT($a.BirthDate,'%M %d') AS BirthDate,
                                 $a.HireDate AS HireDate,                                                             
                                 $a.IDJobPosition AS Position,
                                 IF($a.IDJobGroup ='ST','STAFF',	
                                 IF($a.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($a.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($a.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($a.IDJobGroup ='LL','LAIN-LAIN',
                                 IF($a.IDJobGroup ='OS','MITRA KERJA',
                                 IF($a.IDJobGroup ='MAG','MAGANG','-'))))))) AS JGroup,  
                                 $b.DescStructure AS DescStructure                                  
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDDepartement = $b.IDStructure", 'left');
        $this->datatables->where("MONTH($a.BirthDate)",$month);       
        $this->datatables->where("$a.Status", "A");
        return $this->datatables->generate();
    }

}
?>

