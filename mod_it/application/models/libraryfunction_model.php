<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Libraryfunction_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function presence_status($ActualIn,$ActualOut,$ManualIn,$ManualOut) {
        
        if(is_null($ActualIn)) {
            if(is_null($ManualIn)) {
                $in = NULL;
            } else {
                $in = $ManualIn;
            }
        } else {
            $in = $ActualIn;
        }
        if(is_null($ActualOut)) {
            if(is_null($ManualOut)) {
                $out = NULL;
            } else {
                $out = $ManualOut;
            }
        } else {
            $out = $ActualOut;
        }
        $status = array('IN'=>$in,'OUT'=>$out);
        return $status;
        
    }

    function subs_time($date1, $date2, $nearest = 1) {
        $time1 = $this->floor_date($date1, $nearest);
        $time2 = $this->floor_date($date2, $nearest);
        return ($time2-$time1)/3600; 
    }

    function floor_date($date, $nearest = 1) {
        // round down to nearest minute (0-29=0 30-59=30)
        $date = strtotime($date);
        return floor($date / (60 * $nearest)) * (60 * $nearest);
    }

    function isEmptyString($data) {
        return (trim($data) === "" or $data === null);
    }

    function createDateRangeArray($start, $end) {
        $range = array();
        if (is_string($start) === true)
            $start = strtotime($start);
        if (is_string($end) === true)
            $end = strtotime($end);
        if ($start > $end)
            return createDateRangeArray($end, $start);
        do {
            $range[] = date('Y-m-d', $start);
            $start = strtotime("+ 1 day", $start);
        } while ($start <= $end);
        return $range;
    }

    function round_up($number, $digit = 1) {
        $nearest_to = 10;
        if ($digit == 2)
            $nearest_to = 100;
        if ($digit == 3)
            $nearest_to = 1000;
        return ceil($number / $nearest_to) * $nearest_to;
    }

    function number_to_time($number) {
        return sprintf("%d:%02d:%02d", $number, 0, 0);
    }

    function time_to_sec($time) {
        $hours = substr($time, 0, -6);
        $minutes = substr($time, -5, 2);
        $seconds = substr($time, -2);
        return $hours * 3600 + $minutes * 60 + $seconds;
    }

    function sec_to_time($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor($seconds % 3600 / 60);
        $seconds = $seconds % 60;

        return sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);
    }

// overtime untuk lapangan tetap (senin s/d sabtu), index = 1 = hidup
// overtime untuk lapangan kontrak dan harian lepas (senin s/d minggu), index != 1 
    function overtime_on_workday($excesshour, $index_or_flat) {
        if ($index_or_flat == 1) {
            if ($excesshour > 1) {
                $firsthour = 1.5;
                $lasthour = $excesshour - 1;
            } else {
                $firsthour = $excesshour * 1.5;
                $lasthour = 0;
            }
            $secondhour = $lasthour * 2;
            return ($firsthour + $secondhour);
        } else {
            return $excesshour;
        }
    }

// overtime untuk lapangan tetap (minggu/libur nasional), index = 1 = hidup
// overtime untuk lapangan kontrak dan harian lepas (libur nasional), index != 1 
    function overtime_on_offday($excesshour, $index_or_flat) {
        if ($index_or_flat == 1) {
            if ($excesshour > 7) {
                $firsthour = 7 * 2;
                $lasthour = $excesshour - 7;
                if ($lasthour > 1) {
                    $secondhour = 3;
                    $lasthour -= 1;
                } else {
                    $secondhour = $lasthour * 3;
                    $lasthour = 0;
                }
            } else {
                $firsthour = $excesshour * 2;
                $secondhour = 0;
                $lasthour = 0;
            }
            $thirdhour = $lasthour * 4;
            return ($firsthour + $secondhour + $thirdhour);
        } else {
            return $excesshour * 2;
        }
    }
    
    
    // overtime untuk staff
    function overtime_on_offday_staff($excesshour) {        
            
          return $excesshour * 2;
        
    }

    /**
     *  function _push_file($path, $name)
     * This function pushes a file out to a user for download.
     * @param    STRING    $path    The full absolute path to the file to be pushed.
     * @param    STRING    $name    The file name of the file to be pushed.
     * @author   Matthew Craig 
     * @copyright 2010 Matthew Craig.
     */
    function _push_file($path, $name) {
        // make sure it's a file before doing anything!
        if (is_file($path . "/" . $name)) {
            // required for IE
            if (ini_get('zlib.output_compression')) {
                ini_set('zlib.output_compression', 'Off');
            }

            // get the file mime type using the file extension
            $this->load->helper('file');

            /**
             * This uses a pre-built list of mime types compiled by Codeigniter found at
             * /system/application/config/mimes.php 
             * Codeigniter says this is prone to errors and should not be dependant upon
             * However it has worked for me so far. 
             * You can also add more mime types as needed.
             */
            $mime = get_mime_by_extension($path . "/" . $name);

            // Build the headers to push out the file properly.
            header('Pragma: public');     // required
            header('Expires: 0');         // no cache
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($path . "/" . $name)) . ' GMT');
            header('Cache-Control: private', false);
            header('Content-Type: ' . $mime);  // Add the mime type from Code igniter.
            header('Content-Disposition: attachment; filename="' . basename($name) . '"');  // Add the file name
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: ' . filesize($path . "/" . $name)); // provide file size
            header('Connection: close');
            readfile($path . "/" . $name); // push it out
            exit();
        }
    }

      function get_name_group($code) {
        if ($code == 'ST') {
            $name = 'STAFF';
        } else if ($code == 'LT') {
            $name = 'LAPANGAN TETAP';
        } else if ($code == 'LK') {
            $name = 'LAPANGAN KONTRAK';
        } else if ($code == 'HL') {
            $name = 'HARIAN LEPAS';
        } else if ($code == 'MAG') {
            $name = 'MAGANG';
        } else if ($code == 'OS') {
            $name = 'MITRA KERJA';
        }else if ($code == 'Al') {
            $name = 'ALL EMPLOYEE';
        } else if ($code == 'LL') {
            $name = 'LAIN-LAIN';
        }

        return $name;
    }

    function get_location($code) {
        if ($code == '1') {
            $site = 'KAPUK';
        } else if ($code == '2') {
            $site = 'BITUNG';
        } else {
            $site = '-';
        }

        return $site;
    }

    function get_gender($gen) {
        if ($gen == 'F') {
            $gender = 'FEMALE';
        } else if ($gen == 'P') {
            $gender = 'FEMALE';
        } else if ($gen == 'L') {
            $gender = 'MALE';
        } else if ($gen == 'M') {
            $gender = 'MALE';
        } else {
            $gender = '-';
        }

        return $gender;
    }

    function check_value_date($date) {
        if ($date == '' or $date == null or $date == '0000-00-00' or $date = '1970-01-01') {
            $valdate = '';
        } else {
            $valdate = $date;
        }

        return $valdate;
    }

    function periode_posting() {
        // Periode Staff 25-24
        if (date('d') >= 25) {
            $selisih = date('d') - 25;
            //eg: 27-04-2012
            $from = date('d-m-Y', strtotime("-" . $selisih . " days", strtotime(date('d-m-Y'))));
            $until = date('d-m-Y', strtotime("+1 month -1 day", strtotime($from)));
        } else {
            // eg: 04-02-2012
            $selisih = 25 - date('d');
            $from = date('d-m-Y', strtotime("-1 month +" . $selisih . "days", strtotime(date('d-m-Y'))));
            $until = date('d-m-Y', strtotime("+1 month -1 day", strtotime($from)));
        }
        return $from . " " . $until;
    }

    function periode_work() {
        $today = array('Year' => date('Y'), 'Month' => date('m'));
        $untildate = date('d-m-Y', strtotime($today['Year'] . "-" . $today['Month'] . "-24"));
        $from = date('d-m-Y', strtotime("-1 month +1 day", strtotime($untildate)));
        return $from . " " . $untildate;
    }

    function periode_one_month() {
        $day = '01';
        $month = date('m');
        $year = date('Y');
        $countday = $this->sum_day($month, $year);
        $date1 = $day . "-" . $month . "-" . $year;
        $date2 = $countday . "-" . $month . "-" . $year;
        $from = date('d-m-Y', strtotime($date1));
        $until = date('d-m-Y', strtotime($date2));

        $this->session->set_userdata('from', $from);
        $this->session->set_userdata('until', $until);

        return $from . " " . $until;
    }

    function sum_day($bulan = 0, $tahun = '') {
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
    
     function decimaltominutes($dec) {
                    // start by converting to seconds
                    $seconds = $dec * 3600;
                    // we're given hours, so let's get those the easy way
                    $hours = floor($dec);
                    // since we've "calculated" hours, let's remove them from the seconds variable
                    $seconds -= $hours * 3600;
                    // calculate minutes left
                    $minutes = floor($seconds / 60);
                    // remove those from seconds as well
                    $seconds -= $minutes * 60;
                    // return the time formatted HH:MM:SS
                    return lz($hours) . lz($minutes);
                }

                function lz($num) {
                    return (strlen($num) < 2) ? "0{$num}" : $num;
                }

  	


function array2json($arr) {
    if(function_exists('json_encode')) return json_encode($arr); //Lastest versions of PHP already has this functionality.
    $parts = array();
    $is_list = false;

    //Find out if the given array is a numerical array
    $keys = array_keys($arr);
    $max_length = count($arr)-1;
    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
        $is_list = true;
        for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
            if($i != $keys[$i]) { //A key fails at position check.
                $is_list = false; //It is an associative array.
                break;
            }
        }
    }

    foreach($arr as $key=>$value) {
        if(is_array($value)) { //Custom handling for arrays
            if($is_list) $parts[] = $this->array2json($value); /* :RECURSION: */
            else $parts[] = '"' . $key . '":' . $this->array2json($value); /* :RECURSION: */
        } else {
            $str = '';
            if(!$is_list) $str = '"' . $key . '":';

            //Custom handling for multiple data types
            if(is_numeric($value)) $str .= $value; //Numbers
            elseif($value === false) $str .= 'false'; //The booleans
            elseif($value === true) $str .= 'true';
            else $str .= '"' . addslashes($value) . '"'; //All other things
            // :TODO: Is there any more datatype we should be in the lookout for? (Object?)

            $parts[] = $str;
        }
    }
    $json = implode(',',$parts);
    
    if($is_list) return '[' . $json . ']';//Return numerical JSON
    return '{' . $json . '}';//Return associative JSON
 } 



}

?>
