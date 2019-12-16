<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Historytable_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db_emp = $this->load->database('empcenter', TRUE);
        $this->_db_pbl = $this->load->database('public', TRUE);
        
        $this->_db_emp_his = $this->load->database('empcenterhis', TRUE);
        $this->_db_pbl_his = $this->load->database('publichis', TRUE);

        $this->_personal = 'm01personal';
        $this->_personal_d = 'm01personal_d';
        $this->_job = 'm01personal_job';
        $this->_family = 'm01personal_family';
        $this->_course = 'm01personal_course';
        $this->_education = 'm01personal_education';
        $this->_language = 'm01personal_language';
        $this->_workexp = 'm01personal_workexp';
        $this->_organization = 'm03organization';
        $this->_weekly = 't04weeklyactivity';
    }

	
      function getby_his_weekly($id){
        $sql = "SELECT a.*,b.FullName
                FROM $this->_weekly a
                JOIN  isib_employee.$this->_personal_d b ON a.TestedBy=b.IDEmployee
                WHERE 
                a.IDTable='$id' AND
                a.TestedNote is not nulL  AND TestedNote != ''";
        $result = $this->_db_emp_his->query($sql);
        if($result->num_rows()>0){
            return $result->result_array();
        }else{
            return 'empty';
        }
        
    }
	
	

    function get_emp_personal($nip) {
        $this->_db_emp->where('DeleteFlag', 'A');
        $this->_db_emp->where('IDEmployee', $nip);
        $result = $this->_db_emp->get($this->_personal);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_emp_personal_detail($nip) {
        $this->_db_emp->where('DeleteFlag', 'A');
        $this->_db_emp->where('IDEmployee', $nip);
        $result = $this->_db_emp->get($this->_personal_d);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_pbl_personal($nip) {
        $this->_db_pbl->where('DeleteFlag', 'A');
        $this->_db_pbl->where('IDEmployee', $nip);
        $result = $this->_db_pbl->get($this->_personal);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_job($nip) {
        $this->_db_pbl->where('DeleteFlag', 'A');
        $this->_db_pbl->where('IDEmployee', $nip);
        $result = $this->_db_pbl->get($this->_job);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_family($nip) {
        $this->_db_pbl->where('DeleteFlag', 'A');
        $this->_db_pbl->where('IDEmployee', $nip);
        $result = $this->_db_pbl->get($this->_family);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    function get_familyrow($where) {
        $this->_db_pbl->where($where);
        $result = $this->_db_pbl->get($this->_family);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_course($nip) {
        $this->_db_pbl->where('DeleteFlag', 'A');
        $this->_db_pbl->where('IDEmployee', $nip);
        $result = $this->_db_pbl->get($this->_course);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    function get_courserow($where) {
        $this->_db_pbl->where($where);        
        $result = $this->_db_pbl->get($this->_course);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_education($nip) {
        $this->_db_pbl->where('DeleteFlag', 'A');
        $this->_db_pbl->where('IDEmployee', $nip);
        $result = $this->_db_pbl->get($this->_education);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    function get_educationrow($where) {
        $this->_db_pbl->where($where);
        $result = $this->_db_pbl->get($this->_education);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_language($nip) {
        $this->_db_pbl->where('DeleteFlag', 'A');
        $this->_db_pbl->where('IDEmployee', $nip);
        $result = $this->_db_pbl->get($this->_language);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    function get_languagerow($where) {
        $this->_db_pbl->where($where);
        $result = $this->_db_pbl->get($this->_language);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function get_workexp($nip) {
        $this->_db_pbl->where('IDEmployee',$nip);
        $this->_db_pbl->where('DeleteFlag','A');
        $result = $this->_db_pbl->get($this->_workexp);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        } else {
            return null;
        }
    }
    function get_workexprow($where) {
        $this->_db_pbl->where($where);
        $result = $this->_db_pbl->get($this->_workexp);
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return null;
        }
    }

    function insert_his_personal_emp($record) {
        $this->_db_emp_his->insert($this->_personal, $record);
    }

    function insert_his_personal_d_emp($record) {
        $this->_db_emp_his->insert($this->_personal_d, $record);
    }

    function insert_his_personal_pbl($record) {
        $this->_db_pbl_his->insert($this->_personal, $record);
    }

    function insert_his_job($record) {
        $this->_db_pbl_his->insert($this->_job, $record);
    }

    function insert_his_family($record) {
        $this->_db_pbl_his->insert($this->_family, $record);
    }

    function insert_his_course($record) {
        $this->_db_pbl_his->insert($this->_course, $record);
    }

    function insert_his_education($record) {
        $this->_db_pbl_his->insert($this->_education, $record);
    }

    function insert_his_language($record) {
        $this->_db_pbl_his->insert($this->_language, $record);
    }

    function insert_his_workexp($record) {
        $this->_db_pbl_his->insert($this->_workexp, $record);
    }

    function insert_his_organization($record) {
        $this->_db_pbl_his->insert($this->_organization, $record);
    }
    function insert_weekly($record) {
        $this->_db_emp_his->insert($this->_weekly, $record);
    }

}
?>


