<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Chart_Model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->_db   = $this->load->database('empcenter',TRUE);	
	$this->_pbl   = $this->load->database('public',TRUE);	
        $this->_tbl1 = 'm01personal';
	$this->_tbl2 = 'm01personal_job';
    }
    function get_available_emp(){
	$query	= "SELECT H.*,J.* FROM $this->_tbl1 H LEFT JOIN $this->_tbl2 J ON H.IDEmployee = J.IDEmployee WHERE J.ResignDate IS NULL";
        return $this->_pbl->query($query);
    }
    function get_all_emp(){
        return $this->_db->get($this->_tbl1);
    }
    function get_jml_gender($gen=NULL){
        if ($gen != NULL){
            $query = "SELECT COUNT(*) as jml FROM $this->_tbl1 WHERE Gender = '$gen'";
        }
        else{
            $query = "SELECT COUNT(*) as jml FROM $this->_tbl1 WHERE Gender IS NULL";
        }
        return $this->_db->query($query);
    }
    function get_aktif($bln,$thn){
        if ($bln == 0){
            $bln = "12";
            $thn = $thn-1;
        }else{
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol.$bln;
        }
        $query = "
            SELECT count( * ) as jml
            FROM $this->_tbl1
            WHERE HireDate <= '$thn-$bln-31'
            AND ResignDate IS NULL ";
        return $this->_db->query($query);
    }
    function get_new($bln,$thn){
        if ($bln == 0){
            $blnfr   = "12";
            $thnfr = $thn-1;
            $bln    = $bln+1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol.$bln;
        }else{
            $blnfr  = $bln;
            $thnfr  = $thn;
            $bln    = $bln+1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol.$bln;
        }
        $query = "
            SELECT count( * ) as jml
            FROM $this->_tbl1
            WHERE HireDate > '$thnfr-$blnfr-31'
            AND HireDate <= '$thn-$bln-31'
            ";
        return $this->_db->query($query);
    }
    function get_resign($bln,$thn){
        if ($bln == 0){
            $blnfr   = "12";
            $thnfr = $thn-1;
            $bln    = $bln+1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol.$bln;
        }else{
            $blnfr  = $bln;
            $thnfr  = $thn;
            $bln    = $bln+1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol.$bln;
        }        
        $query = "
            SELECT count( * ) as jml
            FROM $this->_tbl1
            WHERE ResignDate > '$thnfr-$blnfr-31'
            AND ResignDate <= '$thn-$bln-31'";
        return $this->_db->query($query);
    }
    function get_all_in($bln,$thn){
        if ($bln == 0){
            $bln   = "12";
            $thn = $thn-1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol.$bln;
        }else{
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol.$bln;
        }    
        $query = "SELECT COUNT(*) as jml FROM $this->_tbl1 WHERE HireDate <= '$thn-$bln-31'";
        return $this->_db->query($query);
    }
    function get_all_res($bln,$thn){
        if ($bln == 0){
            $bln   = "12";
            $thn = $thn-1;
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol.$bln;
        }else{
            $bln < 10 ? $nol = "0" : $nol = "";
            $bln = $nol.$bln;
        }  
        $query = "SELECT count(*) as jml FROM $this->_tbl1 WHERE ResignDate <= '$thn-$bln-31'";
        //ResignDate > '0000-00-00' and 
        return $this->_db->query($query);
    }

}


