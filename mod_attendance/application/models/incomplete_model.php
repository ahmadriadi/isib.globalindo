<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Incomplete_model extends Model {

    public function __construct() {
        parent::Model();
        $this->_attendance = $this->load->database('attendance', TRUE);
        $this->_table = 't05incomplete';
        $this->_m_table = 'm01personal';
    }

    function get_data($param, $fromdate, $untildate) {
        if ($param['search'] != null && $param['search'] === 'true') {
            $wh = $param['search_field'];
            switch ($param['search_operator']) {
                case "bw": // begin with
                    $wh .= " LIKE '" . $param['search_str'] . "%'";
                    break;
                case "ew": // end with
                    $wh .= " LIKE '%" . $param['search_str'] . "'";
                    break;
                case "cn": // contain %param%
                    $wh .= " LIKE '%" . $param['search_str'] . "%'";
                    break;
                case "eq": // equal =
                    if (is_numeric($param['search_str'])) {
                        $wh .= " = " . $param['search_str'];
                    } else {
                        $wh .= " = '" . $param['search_str'] . "'";
                    }
                    break;
                case "ne": // not equal
                    if (is_numeric($param['search_str'])) {
                        $wh .= " <> " . $param['search_str'];
                    } else {
                        $wh .= " <> '" . $param['search_str'] . "'";
                    }
                    break;
                case "lt":
                    if (is_numeric($param['search_str'])) {
                        $wh .= " < " . $param['search_str'];
                    } else {
                        $wh .= " < '" . $param['search_str'] . "'";
                    }
                    break;
                case "le":
                    if (is_numeric($param['search_str'])) {
                        $wh .= " <= " . $param['search_str'];
                    } else {
                        $wh .= " <= '" . $param['search_str'] . "'";
                    }
                    break;
                case "gt":
                    if (is_numeric($param['search_str'])) {
                        $wh .= " > " . $param['search_str'];
                    } else {
                        $wh .= " > '" . $param['search_str'] . "'";
                    }
                    break;
                case "ge":
                    if (is_numeric($param['search_str'])) {
                        $wh .= " >= " . $param['search_str'];
                    } else {
                        $wh .= " >= '" . $param['search_str'] . "'";
                    }
                    break;
                default :
                    $wh = "";
            }
            $this->_attendance->where($wh);
        }
      
        $this->_attendance->select($this->_table.'.*,personal.FullName');
        $this->_attendance->join('trias_empcenter.m01personal personal', $this->_table.'.IDEmployee = personal.IDEmployee');
        $this->_attendance->where('IncompleteDate >=', $fromdate);
        $this->_attendance->where('IncompleteDate <=', $untildate);
        
        ($param['limit'] != null ? $this->_attendance->limit($param['limit']['end'], $param['limit']['start']) : '');
        ($param['sort_by'] != null) ? $this->_attendance->order_by($param['sort_by'], $param['sort_direction']) : '';

        return $this->_attendance->get($this->_table, 'trias_empcenter.m01personal');
    }

    function get_all_by_period($fromdate, $untildate) {
        $this->_attendance->where('IncompleteDate >=', $fromdate);
        $this->_attendance->where('IncompleteDate <=', $untildate);
        $count = $this->_attendance->count_all_results($this->_table);
        if ($count == 0) {
            return NULL;
        } else {
            $this->_attendance->select($this->_table.'.*,personal.FullName');
            $this->_attendance->from($this->_table);
            $this->_attendance->join('trias_empcenter.m01personal personal', $this->_table.'.IDEmployee = personal.IDEmployee');            
            $this->_attendance->where('IncompleteDate >=', $fromdate);
            $this->_attendance->where('IncompleteDate <=', $untildate);
            
            return $this->_attendance->get();
        }
    }
    
    function get_by_id($id) {
        $this->_attendance->where('ID', $id);
        $jml = $this->_attendance->count_all_results($this->_table);
        if ($jml == 0) {
            return NULL;
        } else {
            $this->_attendance->select($this->_table.'.*,personal.FullName');
            $this->_attendance->from($this->_table);
            $this->_attendance->join('trias_empcenter.m01personal personal', $this->_table.'.IDEmployee = personal.IDEmployee');
            $this->_attendance->where($this->_table.'.ID', $id);
            return $this->_attendance->get();
        }
    }
    
    function insert($record) {
        $this->_attendance->insert($this->_table, $record);
    }
    
    function update($id, $record) {
        $this->_attendance->where('ID', $id);
        $this->_attendance->update($this->_table, $record);
    }
    
    function delete($id) {
        $this->_attendance->delete($this->_table, array('ID' => $id));
    }
    
    function count_by_id($id) {
        $this->_attendance->where('ID', $id);
        return $this->_attendance->count_all_results($this->_table);
    }

    function importdata($id, $record) {
        // cek, jika sdh ada, update
        // kalau blm ada, insert
        $this->_attendance->where('ID', $id);
        if ($this->_attendance->count_all_results($this->_table) > 0) {
            $this->update($id, $record);
        } else {
            $this->insert($record);
        }
    }
}

?>
