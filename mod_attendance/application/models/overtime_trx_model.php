<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Overtime_trx_model extends CI_Model {

    public function __construct() {
        parent::__construct();        
        $this->_db      =  $this->load->database('attendance', TRUE);
        $this->_db2     =  $this->load->database('empcenter', TRUE);
        $this->_pbl     =  $this->load->database('public', TRUE);
        $this->_table   = 't04overtime';
        $this->_m_table = 'm01personal';
	$this->_personal = 'isib_employee.m01personal';	
    }
    function get_accepted($iduser){
        $att    = "isib_attendance";
        $emp    = "isib_employee";
        $this->datatables->select("E.FullName AS Name, O.IDSPKL AS IDSPKL, O.PresenceDate AS PresenceDate, O.OvertimeIn AS OvertimeIn, O.OvertimeOut AS OvertimeOut, O.Note AS Note, O.ConfirmDate AS ApprovalDate, O.AddedDate AS AddedDate");
        $this->datatables->from("$att.$this->_table AS O");
        $this->datatables->join("$emp.$this->_m_table AS E","O.IDEmployee = E.IDEmployee");
        $this->datatables->where("O.ConfirmBy = '$iduser' AND O.DeleteFlag = 'A'");
        return $this->datatables->generate();
    }    
  function allovertime($from,$until){
      $a = $this->_table;   
      $b = 'isib_employee.m01personal';
      $this->datatables->select("$a.ID AS ID,
                                 $a.IDSPKL AS IDSPKL,
                                 $a.IDEmployee AS IDEmployee,
				 $b.IDJobGroup AS IDJobGroup,  
				 $b.IDLocation AS IDLocation,	    
                                 $a.PresenceDate AS PresenceDate,
                                 $a.OvertimeIn AS OvertimeIn,
                                 $a.OvertimeOut AS OvertimeOut,
                                 $a.Note AS Note,
                                 $a.OvertimeHour AS OvertimeHour,                                
                                 $a.CheckData AS CheckData,
                                 IF($a.ConfirmFlag ='0','<span><b  class=\"waiting\">Waiting</b></span>',
                                 IF($a.ConfirmFlag ='1','<span><b class=\"accept\">Accepted</b></span>',
                                 IF($a.ConfirmFlag ='2',concat('<span class=\"reject\" data-toggle=\"popover\" data-title=\"Reason of rejection\" data-content=\"',$a.RejectReason,'\" data-placement=\"left\"><b data-toggle=\"tooltip\" data-original-title=\"click to view the reason\" data-placement=\"top\" >Rejected</b></span>'),''))) AS Status,    
                                 $b.FullName AS FullName,
				 IF($b.IDLocation='1','KAPUK',
                                 IF($b.IDLocation='2','BITUNG','-')) AS Location,  
                                 IF($b.IDJobGroup ='ST','STAFF',    
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN',
				 IF($b.IDJobGroup ='OS','MITRA KERJA', 
                                 IF($b.IDJobGroup ='MAG','MAGANG','-'))))))) AS JobGroup   
                            ",FALSE);
      $this->datatables->from("$a"); 
      $this->datatables->join($b,"$a.IDEmployee = $b.IDEmployee",'left');  
      $this->datatables->where("$a.PresenceDate >=",date('Y-m-d',strtotime($from)));
      $this->datatables->where("$a.PresenceDate <=",date('Y-m-d',strtotime($until))); 
      $this->datatables->where("$a.DeleteFlag","A");       
      $this->datatables->where("$b.DeleteFlag","A");       
      $this->datatables->where("$a.ConfirmFlag","1");       
     // $this->datatables->order("$a.PresenceDate","DESC"); 	
     // $this->datatables->edit_column('OvertimeHour', '$1', "number_format(OvertimeHour)");
     // $this->datatables->edit_column('OvertimeTotalHour', '$1', "number_format(OvertimeTotalHour)");
      
      
//     return $this->datatables->get(); 
      return $this->datatables->generate();       
  }     
    
    
 function overtimeemployee($from,$until,$user){            
      $a = $this->_table;   
      $b = 'isib_employee.m01personal';
      $this->datatables->select("$a.ID AS ID,
                                 $a.IDSPKL AS IDSPKL,
                                 $a.IDEmployee AS IDEmployee,
                                 $a.PresenceDate AS PresenceDate,
				 $b.IDJobGroup AS IDJobGroup,
				 $b.IDLocation AS IDLocation,		
                                 $a.OvertimeIn AS OvertimeIn,
                                 $a.OvertimeOut AS OvertimeOut,
                                 $a.Note AS Note,
                                 $a.OvertimeHour AS OvertimeHour,                                
                                 $a.CheckData AS CheckData,
                                 IF($a.ConfirmFlag ='0','<span><b  class=\"waiting\">Waiting</b></span>',
                                 IF($a.ConfirmFlag ='1','<span><b class=\"accept\">Accepted</b></span>',
                                 IF($a.ConfirmFlag ='2',concat('<span class=\"reject\" data-toggle=\"popover\" data-title=\"Reason of rejection\" data-content=\"',$a.RejectReason,'\" data-placement=\"left\"><b data-toggle=\"tooltip\" data-original-title=\"click to view the reason\" data-placement=\"top\" >Rejected</b></span>'),''))) AS Status,    
                                 $b.FullName AS FullName,
                                 IF($b.IDLocation='1','KAPUK',IF($b.IDLocation='2','BITUNG','-')) AS Location,
				 IF($b.IDJobGroup ='ST','STAFF',
                                 IF($b.IDJobGroup ='LT','LAPANGAN TETAP',
                                 IF($b.IDJobGroup ='LK','LAPANGAN KONTRAK',
                                 IF($b.IDJobGroup ='HL','HARIAN LEPAS',
                                 IF($b.IDJobGroup ='LL','LAIN-LAIN',
				 IF($b.IDJobGroup ='OS','MITRA KERJA', 	
                                 IF($b.IDJobGroup ='MAG','MAGANG','-'))))))) AS JobGroup    
                            ",FALSE);
      $this->datatables->from("$a"); 
      $this->datatables->join($b,"$a.IDEmployee = $b.IDEmployee",'left');  
      $this->datatables->where("$a.PresenceDate >=",date('Y-m-d',strtotime($from)));
      $this->datatables->where("$a.PresenceDate <=",date('Y-m-d',strtotime($until))); 
      $this->datatables->where("$a.DeleteFlag","A"); 
      $this->datatables->where("$b.DeleteFlag","A"); 
      $this->datatables->where("$a.IDEmployee",$user); 
      //$this->datatables->where("$a.ConfirmFlag","1"); 	
     // $this->datatables->order("$a.PresenceDate","DESC"); 	
     // $this->datatables->edit_column('OvertimeHour', '$1', "number_format(OvertimeHour)");
     // $this->datatables->edit_column('OvertimeTotalHour', '$1', "number_format(OvertimeTotalHour)");
      
      
//     return $this->datatables->get(); 
      return $this->datatables->generate();       
  }  
     
   

  function overtimedata_hrd($from,$until,$g) {
        $f = date('Y-m-d', strtotime($from));
        $u = date('Y-m-d', strtotime($until));
        $group = ($g=='AL')?'':" AND b.IDJobGroup='$g'";

        $sql = "SELECT a.*,b.FullName,b.IDJobGroup
            FROM $this->_table a 
            LEFT JOIN $this->_personal b ON a.IDEmployee = b.IDEmployee
            WHERE a.PresenceDate BETWEEN '$f' AND '$u' AND b.DeleteFlag ='A' AND a.DeleteFlag ='A' AND a.ConfirmFlag='1' $group
            ";

        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }

    function overtimedata($from, $until, $userid) {
        $f = date('Y-m-d', strtotime($from));
        $u = date('Y-m-d', strtotime($until));
        $sql = "SELECT a.*,b.FullName,b.IDJobGroup,b.IDLocation
                FROM $this->_table a 
                LEFT JOIN $this->_personal b ON a.IDEmployee = b.IDEmployee
                WHERE a.PresenceDate BETWEEN '$f' AND '$u' AND a.IDEmployee ='$userid' AND a.ConfirmFlag='1' AND a.DeleteFlag='A' AND b.DeleteFlag ='A'
                UNION 
                SELECT a.*,b.FullName,b.IDJobGroup,b.IDLocation
                FROM $this->_table a 
                LEFT JOIN $this->_personal b ON a.IDEmployee = b.IDEmployee
                WHERE a.PresenceDate BETWEEN '$f' AND '$u' AND b.IDEmployeeParent ='$userid' AND a.ConfirmFlag='1' AND a.DeleteFlag='A' AND b.DeleteFlag ='A'
                UNION 
                SELECT a.*,b.FullName,b.IDJobGroup,b.IDLocation
                FROM $this->_table a 
                LEFT JOIN $this->_personal b ON a.IDEmployee = b.IDEmployee
                WHERE a.PresenceDate BETWEEN '$f' AND '$u' AND a.AddedBy ='$userid' AND a.ConfirmFlag='1' AND a.DeleteFlag='A' AND b.DeleteFlag ='A'
                ";

        $result = $this->_db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    
    

   
 function get_all_join($fromdate,$untildate,$job='') {
    $query= "     
    SELECT  a.ID,
            a.IDSPKL,
            a.IDEmployee,
            b.FullName,
            b.IDJobGroup,
            a.PresenceDate,
            c.WorkDay,
            c.Description,
            a.OvertimeIn,
            a.OvertimeOut, 
            a.Note
            
    FROM 
            trias_db.t04overtime a
    JOIN 
            trias_empcenter.m01personal b
    ON
             b.IDEmployee=a.IDEmployee
    JOIN
            trias_db.t03presence c
    ON 
            c.IDEmployee=a.IDEmployee AND
            c.PresenceDate=a.PresenceDate  
    
    WHERE 
            a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND
            b.IDJobGroup ='$job'            
    ORDER BY
            b.IDJobGroup DESC,
            b.FullName ASC,
            a.PresenceDate ASC
     
        ";   
    //die($query);
       $result = $this->_db->query($query);       
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        return NULL;		
    } 
 function get_all_join_field($fromdate,$untildate,$group) {
    $query= "     
    SELECT  a.ID,
            a.IDSPKL,
            a.IDEmployee,
            b.FullName,
            b.IDJobGroup,
            a.PresenceDate,
            c.WorkDay,
            a.OvertimeIn,
            a.OvertimeOut, 
            a.Note
            
    FROM 
            trias_db.t04overtime a
    JOIN 
            trias_empcenter.m01personal b
    ON
             b.IDEmployee=a.IDEmployee
    JOIN
            trias_db.t03presence c
    ON 
            c.IDEmployee=a.IDEmployee AND
            c.PresenceDate=a.PresenceDate  
    
    WHERE 
            a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND
            b.IDJobGroup ='$group'            
    ORDER BY
            
            b.FullName ASC
            
     
        ";   
    //die($query);
       $result = $this->_db->query($query);       
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        return NULL;		
    } 
                
        
function get_all_join_overtime_personal($idemployee,$fromdate,$untildate) {
    $query= "     
    SELECT  a.ID,
            a.IDSPKL,
            a.IDEmployee,
            b.FullName,
            b.IDJobGroup,
            a.PresenceDate,
            c.WorkDay,
            c.Description,
            a.OvertimeIn,
            a.OvertimeOut,
            a.Note
            
    FROM 
            trias_db.t04overtime a
    JOIN 
            trias_empcenter.m01personal b
    ON
             b.IDEmployee=a.IDEmployee
    JOIN
            trias_db.t03presence c
    ON 
            c.IDEmployee=a.IDEmployee AND
            c.PresenceDate=a.PresenceDate  
    
    WHERE 
            a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND
            b.IDEmployee ='$idemployee'            
    ORDER BY           
            b.FullName ASC,
            a.PresenceDate ASC
     
        ";   
    //die($query);
       $result = $this->_db->query($query);       
        if ($result->num_rows() > 0)
        {
            return $result->result_array();
        }
        return NULL;		
    }    
    
   

    function get_all_by_period_overtime($fromdate, $untildate) {              
        $count = $this->_db->count_all($this->_table);
        if ($count == 0) {
            return NULL;
        } else {
                    $this->_db->select('overtime.*,
                                                           personal.FullName AS FullName,
                                                           personal.IDJobGroup AS IDJobGroup,
                                                           presence.WorkDay AS WorkDay'); 
                    $this->_db->from('trias_db.t04overtime overtime'); 
                    $this->_db->join('trias_empcenter.m01personal personal','overtime.IDEmployee = personal.IDEmployee','left');
                    $this->_db->join('trias_db.t03presence presence','overtime.IDEmployee = presence.IDEmployee','left');
                    $this->_db->where('overtime.PresenceDate >=', $fromdate);
                    $this->_db->where('overtime.PresenceDate <=', $untildate);     
                    $this->_db->Order_by('personal.IDJobGroup','DESC'); 
                    $this->_db->Order_by('personal.FullNames','ASC'); 
                    $this->_db->Order_by('overtime.PresenceDate','ASC'); 
                  
            return $this->_db->get();
        }
    }
    

    function get_all_by_period($f, $u,$user) {
        $fromdate = date('Y-m-d',  strtotime($f));
        $untildate = date('Y-m-d',  strtotime($u));        
        
        $sql= "
               SELECT
                    a.*,
                    b.FullName
                FROM
                    t04overtime a
                LEFT JOIN
                    m01personal b
                ON
                    a.IDEmployee = b.IDEmployee
                WHERE
                    a.PresenceDate BETWEEN '$fromdate' AND '$untildate' AND
                    a.DeleteFlag='A'  AND a.IDEmployee ='$user'  
                ";
        $result = $this->_db->query($sql);
        if($result->num_rows()>0){
            return $result->result_array();
            
        }
            return null;  
       
    }
    
    
    function get_by_id($id){
        $sql= "
               SELECT
                    a.*,
                    b.FullName
                FROM
                    t04overtime a
                LEFT JOIN
                    m01personal b
                ON
                    a.IDEmployee = b.IDEmployee
                WHERE
                    a.ID = '$id'                
                ";
        $result = $this->_db->query($sql);
        if($result->num_rows()>0){
            return $result->row();
            
        }
            return null;       
        
    }

  
   function checkovertime($nip,$timein,$timeout){
          $this->_db->where('IDEmployee', $nip);
          $this->_db->where('OvertimeIn', $timein);
          $this->_db->where('OvertimeOut', $timeout);
          $this->_db->where('DeleteFlag','A');
          $result = $this->_db->get($this->_table);        
         if($result->num_rows()>0){
             return $result->row();  
         }else{
             return null;
         }  
        
    }


    
     function checkdata($nip,$date,$overtimein){
        $this->_db->where('IDEmployee', $nip);
        $this->_db->where('PresenceDate', $date);
        $this->_db->where('OvertimeIn', $overtimein);
	$this->_db->where('DeleteFlag','A');
        $result = $this->_db->get($this->_table);        
         if($result->num_rows()>0){
             return $result->row();  
         }else{
             return null;
         }                
    }	
	
    
    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }
    
    function update($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->update($this->_table, $record);
    }
    
    function update_by_idspkl($id, $record) {
        $this->_db->where('IDSPKL', $id);
        $this->_db->update($this->_table, $record);
    }
    
    function delete($id) {
        $this->_db->delete($this->_table, array('ID' => $id));
    }
    
    function count_by_id($id) {
        $this->_db->where('ID', $id);
        return $this->_db->count_all_results($this->_table);
    }

    function count_by_idspkl($id) {
        $this->_db->where('IDSPKL', $id);
        return $this->_db->count_all_results($this->_table);
    }
    
    function importdata($id, $record) {
        // cek, jika sdh ada, update
        // kalau blm ada, insert
        $this->_db->where('ID', $id);
        if ($this->_db->count_all_results($this->_table) > 0) {
            $this->update($id, $record);
        } else {
            $this->insert($record);
        }
    }
    
    function getall_data($f,$u){
    $sql = "SELECT
                    a.ID AS ID,
                    a.IDSPKL AS IDSPKL,
                    a.IDEmployee AS IDEmployee,
                    DATE_FORMAT(a.PresenceDate,'%d-%m-%Y') AS PresenceDate,
                    a.OvertimeIn AS OvertimeIn,
                    a.OvertimeOut AS OvertimeOut,
                    a.Note AS Note,
                    a.OvertimeHour AS OvertimeHour,                                
                    a.CheckData AS CheckData,
                    b.FullName AS FullName
              FROM
                    t04overtime a
              LEFT JOIN
                    isib_employee.m01personal b
              ON
                    a.IDEmployee = b.IDEmployee
              WHERE                  
                    a.DeleteFlag ='A' AND
                    b.DeleteFlag ='A' AND
                    a.ConfirmFlag ='1' AND
                    a.PresenceDate BETWEEN '$f' AND '$u'
              ORDER BY  
                    a.PresenceDate DESC                    
         ";
    
         $result = $this->_db->query($sql);
         if($result->num_rows()>0){
             return $result->result_array();            
             
         }else{
             return 'empty';
         }
    
    } 

   function get_by_user($user){
    $sql = "SELECT
                    a.ID AS ID,
                    a.IDSPKL AS IDSPKL,
                    a.IDEmployee AS IDEmployee,
                    DATE_FORMAT(a.PresenceDate,'%d-%m-%Y') AS PresenceDate,
                    a.OvertimeIn AS OvertimeIn,
                    a.OvertimeOut AS OvertimeOut,
                    a.Note AS Note,
                    a.OvertimeHour AS OvertimeHour,                                
                    a.CheckData AS CheckData,
                    b.FullName AS FullName
              FROM
                    t04overtime a
              LEFT JOIN
                    isib_employee.m01personal b
              ON
                    a.IDEmployee = b.IDEmployee
              WHERE
                    a.IDEmployee ='$user' AND
                    a.DeleteFlag ='A' AND
                    b.DeleteFlag ='A'
              ORDER BY  
                    a.PresenceDate DESC                    
         ";
    
         $result = $this->_db->query($sql);
         if($result->num_rows()>0){
             return $result->result_array();            
             
         }else{
             return 'empty';
         }
    
    }
    function get_overtime($where){
        $this->_db->where($where);
        return $this->_db->get($this->_table);
    }
    function get_personal($iduser){
        $query      = "SELECT H.*, D.* FROM m01personal H LEFT JOIN m01personal_d D ON H.IDEmployee = D.IDEmployee WHERE H.IDEmployee = '$iduser'";
        return $this->_db2->query($query);
    }
    function get_prs_public($iduser){
        $this->_pbl->where("IDEmployee",$iduser);
        return $this->_pbl->get($this->_m_table);
    }
    function update_overtime($where,$record){
        $this->_db->where($where);
        $this->_db->update($this->_table,$record);
    }


}

?>


