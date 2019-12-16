<?php

class Rpt_streff_model extends CI_Model {

    public function __construct() {
        parent ::__construct();
        $this->dbmc = $this->load->database("production",TRUE);
        $this->tbmc = "mst01machine";
    }    
    function get_machine($where=NULL){
        if ($where != NULL){
            $this->dbmc->where($where);
        }
        return $this->dbmc->get($this->tbmc);
    }
}