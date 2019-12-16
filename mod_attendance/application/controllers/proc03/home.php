<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('presence_model', 'presence');
	$this->load->model('uac_model', 'uac');
        
        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $data['default']['group1'] = 'In';
        $data['default']['group2'] = 'Out';
        $data['default']['group3'] = 'All';
        $data['default']['group4'] = 'Kapuk';
        $data['default']['group5'] = 'Bitung';

        $data['default']['checked_group1'] = '';
        $data['default']['checked_group2'] = '';
        $data['default']['checked_group3'] = '';
        $data['default']['checked_group4'] = '';
        $data['default']['checked_group5'] = '';

	$idmenu                    = "93";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);        
        $this->load->view('proc03/home', $data);
    }

    function processdata() {
        $this->form_validation->set_rules('f01', 'Request Date', 'required');
        if ($this->form_validation->run() == TRUE) {
            $date = date('Y-m-d', strtotime($this->input->post('f01')));
            $datetime = date('Y-m-d H:i', strtotime($this->input->post('f01')));
            $absence = $this->input->post('f02');
            $location = $this->input->post('f03');

            $result = $this->presence->troubledate($date, $location);
            $check = ($result == null or $result == '') ? 'empty' : 'exist';

            if ($check == 'exist') {                
                if ($absence == 'In') {
                    $this->presencein($date, $location, $datetime);
                } else if ($absence == 'Out') {
                    $this->presenceout($date, $location, $datetime);
                }

                $mesg = 'ATL BY SYSTEM SUCCESS';
                $valid = 'true';
                $err_f01 = '';
                $err_f02 = '';
            } else if ($check == 'empty') {
                $mesg = 'ATL BY SYSTEM FAILED';
                $valid = 'false';
                $err_f01 = '';
                $err_f02 = '';
            }

            $mesg = $mesg;
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
        } else {
            $mesg = 'data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
        }
        $json = '{ "mesg":"' . $mesg . '",
                   "valid":"' . $valid . '",
                   "err_f01":"' . $err_f01 . '",
                   "err_f02":"' . $err_f02 . '"' .
                '}';

        echo $json;
    }
    
      function presencein($date, $location, $datetime) {        
       if($location =='All'){
            $result = $this->presence->absencein_all($date); 
        }else if($location =='Kapuk'){
           $result = $this->presence->absencein_kapuk($date); 
        }else if($location =='Bitung'){         
            $result = $this->presence->absencein_bitung($date); 
        }  
                
         foreach ($result as $row) {
            $idemp = $row['IDEmployee'];            
            $dateraw = $row['PresenceDate'];
            $timeraw = $row['PresenceTime'];
            $outtime = $dateraw.' '.$timeraw;    
            
            $out = $outtime;
            $note = 'ATL BY SYSTEM';
            $desc = 'NC';
            
            //echo  'Nip :'.$idemp.'Time :'.$outtime.'<br/>';
           
            if (!is_null($out)) {
                $man_in = $datetime;
                $man_out = $out;  
                
                $recordatl = array(
                    'IDEmployee ' => $idemp,
                    'IncompleteDate ' => $date,
                    'ConfirmFlag ' => '1',
                    'TimeIn ' => $man_in,
                    'TimeOut ' => $man_out,
                    'Note ' => $note,
                    'AddedBy'=>$this->User,
                    'AddedDate'=>$this->Datetime,
                    'AddedIP'=>$this->Ip
                );
                
                $recordpresence = array(
                    'ManualIn ' => $man_in,
                    'ManualOut ' => $man_out,
                    'Description ' => $desc,
                    'AddedBy'=>$this->User,
                    'AddedDate'=>$this->Datetime,
                    'AddedIP'=>$this->Ip
                );
               
                $this->presence->checkdataatl($idemp, $date, $man_in, $man_out, $note, $recordatl);              
                $this->presence->update_by_nipdate($idemp, $date, $recordpresence);
            
            } 
             
         }
         
        } 

    function presenceout($date, $location, $datetime) {
        $result = $this->presence->troubledate($date, $location);
        foreach ($result as $row) {
            $idemp = $row['IDEmployee'];
            $in = $row['ActualIn'];
            $note = 'ATL BY SYSTEM';
            $desc = 'NC';

            if (!is_null($in)) {
                $man_in = $in;
                $man_out = $datetime;


                $recordatl = array(
                    'IDEmployee ' => $idemp,
                    'IncompleteDate ' => $date,
                    'ConfirmFlag ' => '1',
                    'TimeIn ' => $man_in,
                    'TimeOut ' => $man_out,
                    'Note ' => $note,
                    'AddedBy'=>$this->User,
                    'AddedDate'=>$this->Datetime,
                    'AddedIP'=>$this->Ip
                );


                $recordpresence = array(
                    'ManualIn ' => $man_in,
                    'ManualOut ' => $man_out,
                    'Description ' => $desc,
                    'AddedBy'=>$this->User,
                    'AddedDate'=>$this->Datetime,
                    'AddedIP'=>$this->Ip
                );

                $this->presence->checkdataatl($idemp, $date, $man_in, $man_out, $note, $recordatl);
                $this->presence->update_by_nipdate($idemp, $date, $recordpresence);
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


