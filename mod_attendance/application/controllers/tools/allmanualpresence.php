<?php

/*
   periode 26 juni 2015 rawdata tidak ada untuk actual outnya dan tanggal 27 tidak ada actual innya 
   hal ini dikarenakan server absensi atau mesin absensi error sehingga tidak menyimpan data 
   hasil tap in atau out di periode tersebut.
 
*/



/*Intruksi aryana untuk kebutuhan proses penggajian 
 * penggajian karyawan periode 24 juni 2015
 * data absensi actualin di masukan ke manualin, dan
 * data absensi actualout, di masukan berdasarakan jam kerja hari ini (24 juni 2015) 
*/



/*Intruksi Ibu Doris untuk kebutuhan proses penggajian 
 * penggajian karyawan periode 24 oktober 2014
 * data absensi actualin di masukan ke manualin, dan
 * data absensi actualout, di masukan berdasarakan jam kerja hari ini (24 Oktober 2014) 
*/



class Allmanualpresence extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('Synchrondata_model', 'synchron');
        date_default_timezone_set("Asia/Jakarta");
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $this->updatepresence();
        
    }

    
    function updatepresence(){
       $date = '2015-06-26';  
       $result = $this->synchron->getpresence_oncondition_all($date);
       $checkresult = ($result =='' or $result ==NULL)?'empty':'exist';
       if($checkresult !=='empty'){
           $no =0;
           foreach ($result as $row) {
               $no++;
               $nip = $row['IDEmployee'];   
               $name = $row['FullName'];   
               $presencedate = $row['PresenceDate']; 
               $actualin = $row['ActualIn']; 
               $manualout  = '2015-06-24 16:30:00';  
               
               //echo $no.' - '.$nip.' - '.$name.' - '.$presencedate.' - '.$actualin.'<br/>';
               
               $record = array(                
                   "ManualIn"=>$actualin,
                   "ManualOut"=>$manualout,
                   "Description"=>'MP',
                   "Note"=>'(GENERATE BY SYSTEM)',
               );  
                      
              $this->synchron->update_presence($nip,$presencedate,$record);
               
           }
           
       }
       
        
    }

    
}
