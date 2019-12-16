<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Periodfield_model extends Model {
    public function __construct() {
        parent::Model();
        $this->_attendance = $this->load->database('attendance', TRUE);
        $this->_table = 'r03periodfield';
    }
    
    function get_data($param) {
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
            
            $this->_attendance->select('*');
            $this->_attendance->from($this->_table);
            $this->_attendance->order_by('IDPeriod','desc');
            $this->_attendance->where($wh);
        }else{
            $this->_attendance->select('*');
            $this->_attendance->from($this->_table);
            $this->_attendance->order_by('IDPeriod','desc');
        }
        
        ($param['limit'] != null ? $this->_attendance->limit($param['limit']['end'], $param['limit']['start']) : '');
        ($param['sort_by'] != null) ? $this->_attendance->order_by($param['sort_by'], $param['sort_direction']) : '';

        return $this->_attendance->get();
    }

    function get_all() {
        $jml = $this->_attendance->count_all($this->_table);
        if ($jml == 0) {
            return 'NULL';
        } else {
            $this->_attendance->order_by("IDPeriod", "desc");
            return $this->_attendance->get($this->_table);
        }
    }
    
    function get_by_id($id) {
        $this->_attendance->where('IDPeriod', $id);
        $count = $this->_attendance->count_all_results($this->_table);
        if ($count == 0) {
            return 'NULL';
        } else {
            $this->_attendance->where('IDPeriod', $id);
            return $this->_attendance->get($this->_table);
        }
    }

    function get_last(){
        $count = $this->_attendance->count_all_results($this->_table);
        if ($count == 0) {
            return 'NULL';
        } else {
            $this->_attendance->limit(1,0);
            $this->_attendance->order_by('IDPeriod','desc');
            return $this->_attendance->get($this->_table);
        }
    }
    
    function get_period($date){
        $this->_attendance->where('StartPeriod <=', $date);
        $this->_attendance->where('EndPeriod >=', $date);
        $count = $this->_attendance->count_all_results($this->_table);
        if ($count == 0) {
            return 'NULL';
        } else {
            $this->_attendance->where('StartPeriod <=', $date);
            $this->_attendance->where('EndPeriod >=', $date);
            return $this->_attendance->get($this->_table);
        }
    }
    
    function check_period($id, $date){
        $this->_attendance->where('IDPeriod', $id);
        $this->_attendance->where('StartPeriod <=', $date);
        $this->_attendance->where('EndPeriod >=', $date);
        $count = $this->_attendance->count_all_results($this->_table);
        if ($count == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function insert($record) {
        $this->_attendance->insert($this->_table, $record);
    }

    function count_by_id($id) {
        $this->_attendance->where('IDPeriod', $id);
        return $this->_attendance->count_all_results($this->_v_table);
    }
}

?>
