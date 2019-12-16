<?php

/*Intruksi Ibu Doris untuk kebutuhan proses absensi
 * karena pekerja di bitung di liburkan pada tanggal 6 nov 2014
 * aksi demo buruh, namun absensi pada tanggal tersebut di anggap hadir semua
 * dan di gantikan pada tanggal 10-11-2014 dan 11-11-2014,
 * jam kerja mereka menjadi 08:00 s/d 20:00, jika jam kerja kurang walau 1 menit maka
 * pada saat tanggal tersebut gaji mereka di potong / tidak di bayarkan. 
*/
class Manualsite extends CI_Controller {

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
       $location = '1';  
       $date = '2015-03-17';  
       $noteproses = "proses ini dilakukan karena mesin absensi in pada tanggal 17-03-2015 Mati,
        dan di intruksi oleh hrd";
       
       $result = $this->synchron->getpresence_onsite($location,$date);
       $checkresult = ($result =='' or $result ==NULL)?'empty':'exist';
       if($checkresult !=='empty'){
           $no =0;
           foreach ($result as $row) {
               $no++;
               $nip = $row['IDEmployee'];   
               $name = $row['FullName'];   
	       $manout_onactualout = $row['ActualOut'];   	
               $presencedate = $row['PresenceDate']; 
               $manin = $date.' 08:00:00';
               $manout = $date.' 16:00:00';
               
               //echo $no.' - '.$nip.' - '.$name.' - '.$presencedate.'<br/>';
               
              
               $record = array(                
                   "ManualIn"=>$manin,
                   "ManualOut"=>$manout_onactualout,
                   "Description"=>'MP',
                   "Note"=>'(GENERATE BY SYSTEM)',
                   "CatatanProses"=>$noteproses,
               );  
                      
              $this->synchron->update_presence($nip,$presencedate,$record);
              
               
           }
           
       }
       
        
    }

    
}
