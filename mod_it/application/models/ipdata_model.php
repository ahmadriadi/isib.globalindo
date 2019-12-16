<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ipdata_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('itsysdev', TRUE);
        $this->_table = 'm02ipaddress';
        $this->_employee = 'triasnet_employee.m01personal_d';
        $this->_dept = 'triasnet_employee.m03organization';
        $this->_group = 'triasnet_attendance.r05jobgroup';
       
    }

   
    
    function getdata($param) {	
		
	
			
        $a = $this->_table;
        $b = $this->_employee; 
        $c = $this->_dept;   
        $d = $this->_group;    
        $this->datatables->select("$a.ID AS ID,     
								   $a.MacAddress AS MacAddress,		
                                   $a.IPAddress AS IPAddress,
                                   $a.GateWay AS GateWay,
                                   $a.DomainServer AS DomainServer,
                                   $a.ComputerName AS ComputerName, 
                                   $a.IDEmployee AS IDEmployee, 
                                   $b.FullName AS FullName, 
                                   $b.Status AS Status, 
                                   $c.DescStructure AS Departement, 
                                   $d.GroupName AS GroupName,       
                                   $a.Note AS Note
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDEmployee = $b.IDEmployee", 'left');
        $this->datatables->join($c, "$b.IDDepartement = $c.IDStructure", 'left');
        $this->datatables->join($d, "$b.IDJobGroup = $d.IDJobGroup", 'left');
        
        if($param==='ipactive'){	
			$this->datatables->where("$a.GateWay IS NOT NULL");
			$this->datatables->where("$a.DomainServer IS NOT NULL");
			$this->datatables->where("$a.DeleteFlag", "A");			
	    }else{		
			$this->datatables->where("$a.GateWay IS NULL");	
			$this->datatables->where("$a.DeleteFlag", "A");			
		}	
      
        return $this->datatables->generate();
    }
    
    
    
    
    function getall_data($param){
		
		if($param=='ipactive'){
			
			$where = " 
					WHERE
					a.DomainServer IS NOT NULL AND
					a.GateWay IS NOT NULL AND
					a.DeleteFlag = 'A'	       
		         ";
			
		}else{
			
			 $where = " 
					WHERE
					a.GateWay IS NULL AND
					a.DeleteFlag = 'A' 	       
		         ";			
		}
		
		
		
		
		$sql = "
				SELECT 
						a.ID,a.MacAddress,a.IPAddress,a.GateWay,a.DomainServer,a.ComputerName,a.IDEmployee,a.Note,
						b.FullName,b.IDJobGroup,b.Status,b.IDDepartement,
						c.IDStructure,c.DescStructure,
						d.GroupName
						
				FROM  $this->_table a
				
				LEFT JOIN $this->_employee b ON a.IDEmployee = b.IDEmployee	
				LEFT JOIN $this->_dept c ON b.IDDepartement = c.IDStructure
				LEFT JOIN $this->_group d ON b.IDJobGroup = d.IDJobGroup	
				
				$where 
				 	
			   ";
			   
			   $result = $this->_db->query($sql);
			   if($result->num_rows()>0){
					return $result->result_array();
				}else{
					return 'empty'; 
				}		
	}
    
    
     function getby_id($id) {
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_table);
        if($result->num_rows()>0){
			return $result->row();
		}else{
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
