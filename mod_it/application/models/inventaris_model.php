<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inventaris_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('itsysdev', TRUE);
        $this->_mst01 = 'mst01inventaris';
        $this->_trx01 = 'trx01inventaris';
        $this->_r01 = 'r01inventaris';
        $this->_r02 = 'r02location';
        $this->_r03 = 'r03printbarcode';
    }

    function getdata_counter($itemcode){
        $this->_db->like('CounterCode',$itemcode);
        $result = $this->_db->get($this->_mst01);
        if ($result->num_rows() > 0) {
            return $result->num_rows()+1;
        } else {
            return 1;
        }
        
    }
    
    function getdata_mst01() {
        $a = $this->_mst01;
        $b = $this->_r01;
        $this->datatables->select("$a.ID AS ID,                                 
                                   $a.CounterCode AS CounterCode,
                                   $a.ItemCode AS ItemCode,
                                   $b.ItemName AS ItemName,    
                                   $a.Note AS Note,
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.ItemCode = $b.ItemCode", 'left');
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }

    function getdata_trx01() {
        $a = $this->_trx01;
        $b = $this->_mst01;
        $c = $this->_r01;
        $d = $this->_r02;
        $this->datatables->select("$a.ID AS ID,                                 
                                   $a.IDInventaris AS IDInventaris,
                                   $b.CounterCode AS CounterCode,    
                                   $c.ItemName AS ItemName,    
				   $a.IDLocation AS IDLocation,	
                                   $d.Location AS Location,    
                                   $a.Note AS Note,
                                  
                            ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->join($b, "$a.IDInventaris = $b.CounterCode", 'left');
        $this->datatables->join($c, "$b.ItemCode = $c.ItemCode", 'left');
        $this->datatables->join($d, "$a.IDLocation = $d.IDLoc", 'left');
        $this->datatables->where("$a.DeleteFlag", "A");
        $this->datatables->where("$b.DeleteFlag", "A");
        $this->datatables->where("$c.DeleteFlag", "A");
        $this->datatables->where("$d.DeleteFlag", "A");
        return $this->datatables->generate();
    }

    function getdata_r01() {
        $a = $this->_r01;
        $this->datatables->select("$a.ID AS ID,                                 
                                   $a.ItemName AS ItemName,
                                   $a.ItemCode AS ItemCode,
                                   $a.Note AS Note,
                                ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }
    function getdata_r02() {
        $a = $this->_r02;
        $this->datatables->select("$a.IDLoc AS IDLoc,                                 
                                   $a.Location AS Location,
                                ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }
    
     function getdata_r03() {
        $a = $this->_r03;
        $this->datatables->select("$a.ID AS ID,                                 
                                   $a.ComputerName AS ComputerName,
                                   $a.IPAddress AS IPAddress,
                                   $a.PrinterName AS PrinterName,
                                   $a.PortNumber AS PortNumber,
                                ", FALSE);
        $this->datatables->from("$a");
        $this->datatables->where("$a.DeleteFlag", "A");
        return $this->datatables->generate();
    }

    function getall_mst01() {
        $sql = "SELECT a.*,b.ItemName FROM $this->_mst01 a
                LEFT JOIN $this->_r01 b ON a.ItemCode = b.ItemCode
                WHERE 
                a.DeleteFlag='A'";
        
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

     function getall_trx01($id) {
         $sql = "SELECT a.*,b.ItemCode,c.ItemName,d.Location FROM $this->_trx01 a
                LEFT JOIN $this->_mst01 b ON a.IDInventaris = b.CounterCode
                LEFT JOIN $this->_r01 c ON b.ItemCode = c.ItemCode
                LEFT JOIN $this->_r02 d ON a.IDLocation = d.IDLoc
                WHERE 
                a.DeleteFlag='A'
                 ";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        } 
    }

    function getall_r01() {
        $this->_db->where('DeleteFlag', 'A');
        $result = $this->_db->get($this->_r01);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    function getall_r02() {
        $this->_db->where('DeleteFlag', 'A');
        $result = $this->_db->get($this->_r02);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    
     function getall_r03() {
        $this->_db->where('DeleteFlag', 'A');
        $result = $this->_db->get($this->_r03);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function getby_idmst01($id) {
        $sql = "SELECT a.*,b.ItemName FROM $this->_mst01 a
                LEFT JOIN $this->_r01 b ON a.ItemCode = b.ItemCode
                WHERE 
                a.ID='$id'";
        
       $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        } 
                
    }

   
    function getby_idtrx01($id) {
         $sql = "SELECT a.*,b.ItemCode,c.ItemName FROM $this->_trx01 a
                LEFT JOIN $this->_mst01 b ON a.IDInventaris = b.CounterCode
                LEFT JOIN $this->_r01 c ON b.ItemCode = c.ItemCode
                WHERE 
                a.ID='$id'";
        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        } 
    }

    function getby_idr01($id) {
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_r01);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
    function getby_idr02($id) {
        $this->_db->where('IDLoc', $id);
        $result = $this->_db->get($this->_r02);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
    
    function getby_idr03($id) {
        $this->_db->where('ID', $id);
        $result = $this->_db->get($this->_r03);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function checkmst01($item,$counter) {
        $this->_db->where('ItemCode', $item);
        $this->_db->where('CounterCode', $counter);
        $result = $this->_db->get($this->_mst01);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function checktrx01($inventaris) {
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('IDInventaris', $inventaris);
        $result = $this->_db->get($this->_trx01);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function checkr01($name,$code) {
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('ItemName', $name);
        $this->_db->where('ItemCode', $code);
        $result = $this->_db->get($this->_r01);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
    function checkr02($loc) {
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('Location', $loc);
        $result = $this->_db->get($this->_r02);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }
    
     function checkr03($computername) {
        $this->_db->where('DeleteFlag', 'A');
        $this->_db->where('ComputerName', $computername);
        $result = $this->_db->get($this->_r03);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function insert_mst01($record) {
        $this->_db->insert($this->_mst01, $record);
    }

    function insert_trx01($record) {
        $this->_db->insert($this->_trx01, $record);
    }

    function insert_r01($record) {
        $this->_db->insert($this->_r01, $record);
    }
    function insert_r02($record) {
        $this->_db->insert($this->_r02, $record);
    }
     function insert_r03($record) {
        $this->_db->insert($this->_r03, $record);
    }

    function update_mst01($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_mst01, $record);
    }

    function update_trx01($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_trx01, $record);
    }

    function update_r01($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_r01, $record);
    }
    
    function update_r02($id, $record) {
        $this->_db->where('IDLoc', $id);
        $this->_db->update($this->_r02, $record);
    }
    
    function update_r03($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_r03, $record);
    }

}
?>
