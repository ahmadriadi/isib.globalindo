<?php

/*Intruksi Remalya untuk kebutuhan ibu Intan dalam 
 * penggajian staff periode 24 September 2014
 * data absensi actualin di masukan ke manualin, dan
 * data absensi actualout, di masukan berdasarakan jam kerja hari ini (24 septemnenr 2014) 
*/
class Outbond extends CI_Controller {

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
       $from = '2015-03-19';  
       $until = '2015-03-21';  
       $result = $this->synchron->getdata_outbond($from,$until);
       $checkresult = ($result =='' or $result ==NULL)?'empty':'exist';
       if($checkresult !=='empty'){
           $no =0;
           foreach ($result as $row) {
               $no++;
               $workday = $row['WorkDay'];
               
               if($workday =='N1'){
                   $timein = '08:00:00';
                   $timeout = '16:00:00';
               }else if($workday =='N2'){
                    $timein = '08:00:00';
                    $timeout = '16:30:00';
               }else if($workday =='N3'){
                    $timein = '08:00:00';
                    $timeout = '13:00:00';
               }
               
               $checkin = ($row['ActualIn']=='' or $row['ActualIn']==NULL)?'empty':'exist';
               $checkout = ($row['ActualOut']=='' or $row['ActualOut']==NULL)?'empty':'exist';
              
               if($checkin !=='empty'){
                   $in = $row['ActualIn'];
               }else{
                   $in = $row['PresenceDate'].' '.$timein;
                   
               }
               
               if($checkout !=='empty'){
                   $out = $row['ActualOut'];
               }else{
                   $out = $row['PresenceDate'].' '.$timeout;
                   
               }
               
             
               $noteproses ='Proses ini dilakukan karena karyawan mengikuti 
                            outboand pertanggal 19-03-2015 s/d 22-03-2015';
               
               
               $record = array(                
                   "ManualIn"=>$in,
                   "ManualOut"=>$out,
                   "Description"=>'MP',
                   "Note"=>'(GENERATE BY SYSTEM)',
                   "CatatanProses"=>$noteproses,
               );  
                 
                  $this->synchron->update_presence($row['IDEmployee'],$row['PresenceDate'],$record);
             
           }
           
       }
       
        
    }

    
}
