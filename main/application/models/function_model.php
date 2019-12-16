<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Function_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function sumday($bulan = 0, $tahun = '') {
        if ($bulan < 1 OR $bulan > 12) {
            return 0;
        }
        if (!is_numeric($tahun) OR strlen($tahun) != 4) {
            $tahun = date('Y');
        }
        if ($bulan == 2) {
            if ($tahun % 400 == 0 OR ($tahun % 4 == 0 AND $tahun % 100 != 0)) {
                return 29;
            }
        }
        $jumlah_hari = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        return $jumlah_hari[$bulan - 1];
    }

}

?>
