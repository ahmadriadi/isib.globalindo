<?php

class Menuaccess_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->_db = $this->load->database('security', TRUE);
        $this->_table = 'user_menuaccess';
    }

   
    function get_alldata() {
        $this->_db->where('Access', '1');
        $resutt = $this->_db->get($this->_table);
        if ($resutt->num_rows() > 0) {
            return $resutt;
        }
        return null;
    }

    function get_by_id($id) {
        $this->_db->where('ID', $id);
        $this->_db->where('Access', '1');   
        $resutt = $this->_db->get($this->_table);
        if ($resutt->num_rows() > 0) {
            return $resutt->row();
        }
        return null;
    }
    
    function get_by_idmenu($id) {
        $this->_db->where('IDMenu', $id);
        $this->_db->where('Access', '1');
        $resutt = $this->_db->get($this->_table);
        if ($resutt->num_rows() > 0) {
            return $resutt->row();
        }
        return null;
    }
   

    function insert($record) {
        $this->_db->insert($this->_table, $record);
    }

    function update($id, $record) {
        $this->_db->where('ID', $id);
        $this->_db->where('Access', '1');
        $this->_db->update($this->_table, $record);
    }

}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
