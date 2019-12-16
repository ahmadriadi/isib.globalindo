<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Personal_model extends Model {

    public function __construct() {
        parent::Model();
        $this->_empcenter = $this->load->database('empcenter', TRUE);
        $this->_table = 'm01personal';
    }

	
 	
	

    
    public function search($term) {
        $this->_empcenter->select('IDEmployee, FullName');
        $this->_empcenter->where('Status', 'A');
        $this->_empcenter->like('FullName', $term, 'both');
        $query = $this->_empcenter->get($this->_table);
        return $query;
    }

    function get_all($status='') {
        if($status != ''){
            $this->_empcenter->where('Status', $status);
            $jml = $this->_empcenter->count_all_results($this->_table);
        }else{
            $jml = $this->_empcenter->count_all($this->_table);
        }
        
        if ($jml == 0) {
            return NULL;
        } else {
            $this->_empcenter->order_by("IDEmployee", "asc");
            if($status != ''){
                $this->_empcenter->where('Status', $status);
            }
            return $this->_empcenter->get($this->_table);
        }
    }

    function get_all_by_idjobgroup($idjobgroup, $status='') {
        if($status != ''){
            $this->_empcenter->where('Status', $status);
            $this->_empcenter->where('IDJobGroup', $idjobgroup);
            $jml = $this->_empcenter->count_all_results($this->_table);
        }else{
            $this->_empcenter->where('IDJobGroup', $idjobgroup);
            $jml = $this->_empcenter->count_all_results($this->_table);
        }
        if ($jml == 0) {
            return NULL;
        } else {
            $this->_empcenter->order_by("IDEmployee", "asc");
            if($status != ''){
                $this->_empcenter->where('Status', $status);
            }
            $this->_empcenter->where('IDJobGroup', $idjobgroup);
            return $this->_empcenter->get($this->_table);
        }
    }

    function get_turnover($fromdate,$untildate){
       
        $this->_empcenter->where('HireDate >=', $fromdate);
        $this->_empcenter->where('HireDate <=', $untildate);
  
        $count_hire = $this->_empcenter->count_all_results($this->_table);
        
        
        $this->_empcenter->where('ResignDate >=', $fromdate);
        $this->_empcenter->where('ResignDate <=', $untildate);
        
        $count_resign = $this->_empcenter->count_all_results($this->_table);
        
        $count = $count_hire+$count_resign;
        if ($count == 0) {
            return NULL;
        } else {
            $query = $this->_empcenter->query("
                SELECT  *, DATEDIFF('$untildate',HireDate) AS Priv
                FROM    m01personal
                WHERE   HireDate >= '$fromdate' AND HireDate <= '$untildate' 
                UNION
                SELECT  *, DATEDIFF(ResignDate,'$fromdate') AS Priv
                FROM    m01personal
                WHERE   ResignDate >= '$fromdate' AND ResignDate <= '$untildate' 
                ORDER BY FullName");
            //$result = $query->result();
            return $query;
            //$this->_empcenter->where('ID', $id);
            //return $this->_empcenter->get($this->_table);
        }
        
    }
    function get_turnover_staff($fromdate,$untildate){
       
        $this->_empcenter->where('HireDate >=', $fromdate);
        $this->_empcenter->where('HireDate <=', $untildate);       
        $this->_empcenter->where('IDJobGroup','ST');       
        $count_hire = $this->_empcenter->count_all_results($this->_table);
        
        
        $this->_empcenter->where('ResignDate >=', $fromdate);
        $this->_empcenter->where('ResignDate <=', $untildate);
        $this->_empcenter->where('IDJobGroup','ST');       
        $count_resign = $this->_empcenter->count_all_results($this->_table);
        
        $count = $count_hire+$count_resign;
        if ($count == 0) {
            return NULL;
        } else {
            $query = $this->_empcenter->query("
                SELECT  *, DATEDIFF('$untildate',HireDate) AS Priv
                FROM    m01personal
                WHERE   HireDate >= '$fromdate' AND HireDate <= '$untildate' AND IDJobGroup ='ST'
                UNION
                SELECT  *, DATEDIFF(ResignDate,'$fromdate') AS Priv
                FROM    m01personal
                WHERE   ResignDate >= '$fromdate' AND ResignDate <= '$untildate' AND IDJobGroup ='ST'
                ORDER BY FullName");
            //$result = $query->result();
            return $query;
            //$this->_empcenter->where('ID', $id);
            //return $this->_empcenter->get($this->_table);
        }
        
    }
    
    function get_turnover_email($fromdate,$untildate){
       
        $this->_empcenter->where('HireDate >=', $fromdate);
        $this->_empcenter->where('HireDate <=', $untildate);       
        $count_hire = $this->_empcenter->count_all_results($this->_table);
        
        
        $this->_empcenter->where('ResignDate >=', $fromdate);
        $this->_empcenter->where('ResignDate <=', $untildate);      
        $count_resign = $this->_empcenter->count_all_results($this->_table);
        
        $count = $count_hire+$count_resign;
        if ($count == 0) {
            return NULL;
        } else {
            $query = $this->_empcenter->query("
                SELECT  *, DATEDIFF('$untildate',HireDate) AS Priv
                FROM    m01personal
                WHERE   HireDate >= '$fromdate' AND HireDate <= '$untildate' AND IDJobGroup IN ('LT','LK','HL')
                UNION
                SELECT  *, DATEDIFF(ResignDate,'$fromdate') AS Priv
                FROM    m01personal
                WHERE   ResignDate >= '$fromdate' AND ResignDate <= '$untildate' AND IDJobGroup IN ('LT','LK','HL')
                ORDER BY FullName");
            //$result = $query->result();
            return $query;
            //$this->_empcenter->where('ID', $id);
            //return $this->_empcenter->get($this->_table);
        }
        
    }
    
    function get_by_id($id) {
        $this->_empcenter->where('ID', $id);
        $jml = $this->_empcenter->count_all_results($this->_table);
        if ($jml == 0) {
            return NULL;
        } else {
            $this->_empcenter->where('ID', $id);
            return $this->_empcenter->get($this->_table);
        }
    }
    
    function get_by_idemployee($id) {
        $this->_empcenter->where('IDEmployee', $id);
        $jml = $this->_empcenter->count_all_results($this->_table);
        if ($jml == 0) {
            return NULL;
        } else {
            $this->_empcenter->where('IDEmployee', $id);
            return $this->_empcenter->get($this->_table);
        }
    }
    
    function get_fullname($id) {
        $this->_empcenter->where('IDEmployee', $id);
        $jml = $this->_empcenter->count_all_results($this->_table);
        if ($jml == 0) {
            return NULL;
        } else {
            $this->_empcenter->where('IDEmployee', $id);
            $query = $this->_empcenter->get($this->_table)->result_array();
            return $query[0]['FullName'];
        }
    }

   function get_by_nip($id){
        
        $sql = "SELECT 
                a.FullName
                FROM
                m01personal a
                WHERE
                a.IDEmployee ='$id'";
        $result = $this->_empcenter->query($sql);
        
        if($result->num_rows() > 0){
            return $result->row();
        }
            return null;
    }

    function insert($record) {
        $this->_empcenter->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_empcenter->where('ID', $id);
        $this->_empcenter->update($this->_table, $record);
    }

    function delete($id) {
        $this->_empcenter->delete($this->_table, array('ID' => $id));
    }

    function count_by_id($id) {
        $this->_empcenter->where('ID', $id);
        return $this->_empcenter->count_all_results($this->_table);
    }

    function count_by_idemployee($id) {
        $this->_empcenter->where('IDEmployee', $id);
        return $this->_empcenter->count_all_results($this->_table);
    }
    
    function importdata($id, $record) {
        // cek, jika sdh ada, update
        // kalau blm ada, insert
        $this->_empcenter->where('ID', $id);
        if ($this->_empcenter->count_all_results($this->_table) > 0) {
            $this->update($id, $record);
        } else {
            $this->insert($record);
        }
    }

}

?>

