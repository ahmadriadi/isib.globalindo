<?php
//OVERTIME
class Home extends CI_Controller {

    public function __construct() {        
        parent ::__construct();
        $this->load->model('deduclate_model', 'late');
        $this->load->model('Employee_model', 'employee');
        $this->load->model('logs_model', 'logs');
	$this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('Param_model', 'param');
        $this->load->model('uac_model', 'uac');
        $this->load->model('libraryfunction_model', 'libfun');

	date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

   function index() {	        
        $fromd  = $this->session->userdata('fromdate');
        $untild = $this->session->userdata('untildate');   
        $check1 = ($fromd =='' or $fromd ==null)?'empty':'exist';
        $check2 = ($untild =='' or $untild ==null)?'empty':'exist';       
        
        $data['test1'] = $check1;
        $data['test2'] = $check2;
        
        if($check1 =='empty' and $check2=='empty'){
            $date = $this->libfun->periode_work();
            $fromdate = substr($date, 0, 10);
            $untildate = substr($date, 11, 10);                
            $this->session->set_userdata('datefrom', date('Y-m-d',strtotime($fromdate)));
            $this->session->set_userdata('dateuntil', date('Y-m-d',strtotime($untildate)));       
        }else{            
             $fromdate = $this->session->userdata('fromdate');
             $untildate = $this->session->userdata('untildate');  
             $this->session->set_userdata('datefrom', date('Y-m-d',strtotime($fromdate)));
             $this->session->set_userdata('dateuntil', date('Y-m-d',strtotime($untildate)));  
        }        
        
        $data['default']['from'] = date('d-m-Y', strtotime($fromdate));
        $data['default']['until'] = date('d-m-Y', strtotime($untildate));
	
	$query = $this->employee->get_rjob_field()->result();
        $i = 0;
        foreach ($query as $r) {
            $i++;
            $data['default']['group'][$i]['value'] = $r->IDJobGroup;
            $data['default']['group'][$i]['display'] = $r->GroupName;   
        }
	
        $idmenu                    = "76";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);	
        $this->load->view('trx08/home',$data);
    }

      function getdatatables() {	
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        
        $idmodule = '65';
        $rowlogin = $this->login->get_by_user($this->User);
        $rowmenu = $this->access->get_by_idmenu($idmodule);
        $rowparam = $this->param->get_param($this->User);

        $check1 = ($rowlogin == '' or $rowlogin == null) ? 'empty' : 'exist';
        $check2 = ($rowmenu == '' or $rowmenu == null) ? 'empty' : 'exist';

        $parameter = $rowparam->ParamValue;
        if ($parameter == $this->User) {
            $param = 'Y';
        } else {
            $param = 'N';
        }
        
        if ($check1 == 'exist' and $check2 == 'exist') {
            $role = $rowlogin->Role;            
            if ($role == '1' or $role == '2') {
                  echo $this->late->getdata($f,$u);
            } else if ($role == '0' and $param == 'Y') {
                 echo $this->late->getdata($f,$u);
            }
        }
        

    }
    
     function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '76';
        $row        = $this->uac->getdata_button($this->User,$idmenu,$button);
        $check      = ($row ==null or $row =='')?'empty':'exist';
        
        if($check !=='empty'){
                $access = $row->kdbutton;  
                $mesg = "Result Button";
                $valid = 'true';
        }else{           
                $access = '';  
                $mesg = "Result Is Null";
                $valid = 'false';
        }

        $json = '{ "mesg":"' . $mesg . '",
                   "btnaccess":"' . $access . '",
                   "valid":"' . $valid . '"' .
                '}';
        echo $json;
        
    }

    function set_pattern_date() {
        $valid = "true";

        $fromdate = date('Y-m-d', strtotime($this->input->post('fromdate')));
        $untildate = date('Y-m-d', strtotime($this->input->post('untildate')));
        $this->session->set_userdata('fromdate', $fromdate);
        $this->session->set_userdata('untildate', $untildate);

        echo '{ "valid":"' . $valid . '"}';
    }

    function exportdata($g) {
        $ext = '.xlsx';
        $path_file = '/tmp/';
        
        $fromdate = date('Y-m-d', strtotime($fromdate));
        $untildate = date('Y-m-d', strtotime($untildate));

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->helper('download');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("title")
                ->setDescription("description");

        // currency format, &euro; with < 0 being in red color
        $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        // number format, with thousands seperator and two decimal points.
        $numberFormat = '#,#0.##;[Red]-#,#0.##';

        // writer will create the first sheet for us, let's get it
        $objSheet = $objPHPExcel->getActiveSheet();
        // rename the sheet
        $objSheet->setTitle('late report');

        // let's bold and size the header font and write the header
        // as you can see, we can specify a range of cells, like here: cells from A1 to A4
        $objSheet->getStyle('A1:H1')->getFont()->setBold(true)->setSize(12);

        // write header       
        $objSheet->getCell('A1')->setValue('IDEmployee');
        $objSheet->getCell('B1')->setValue('FullName');
	$objSheet->getCell('C1')->setValue('Group'); 	
        $objSheet->getCell('D1')->setValue('Posting Date');
        $objSheet->getCell('E1')->setValue('Presence Date');
        $objSheet->getCell('F1')->setValue('Actual In');
        $objSheet->getCell('G1')->setValue('Deduct Hour');
        $objSheet->getCell('H1')->setValue('Deduct Amount');
        
        $f = $this->session->userdata('datefrom');
        $u = $this->session->userdata('dateuntil');
        $result = $this->late->getall_data($f,$u,$g);
        if ($result != NULL) {   
            $i = 1;
            foreach ($result as $row) {
                $i++;
                
                $group = $this->libfun->get_name_group($row['IDJobGroup']);
                
                $objSheet->getCell('A' . $i)->setValue("'".$row['IDEmployee']);
                $objSheet->getCell('B' . $i)->setValue($row['FullName']);
		$objSheet->getCell('C' . $i)->setValue($group);
                $objSheet->getCell('D' . $i)->setValue(date('d-m-Y',strtotime($row['PostingDate'])));
                $objSheet->getCell('E' . $i)->setValue(date('d-m-Y',strtotime($row['PresenceDate'])));
                $objSheet->getCell('F' . $i)->setValue($row['LateTime']);
                $objSheet->getCell('G' . $i)->setValue($row['LateHour']);
                $objSheet->getCell('H' . $i)->setValue(number_format($row['DeducAmount'],'2',',','.'));
                
            }

            // create some borders
            // first, create the whole grid around the table
            $objSheet->getStyle('A1:H' . $i)->getBorders()->
                    getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:H' . $i)->getBorders()->
                    getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
            $objSheet->getStyle('A1:H1')->getBorders()->
                    getBottom()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

            // autosize the columns
            $objSheet->getColumnDimension('A')->setAutoSize(true);
            $objSheet->getColumnDimension('B')->setAutoSize(true);
            $objSheet->getColumnDimension('C')->setAutoSize(true);
            $objSheet->getColumnDimension('D')->setAutoSize(true);
            $objSheet->getColumnDimension('E')->setAutoSize(true);
            $objSheet->getColumnDimension('F')->setAutoSize(true);       
            $objSheet->getColumnDimension('G')->setAutoSize(true);       
            $objSheet->getColumnDimension('H')->setAutoSize(true); 
	  

            if ($ext == ".xlsx") {
                // Save it as an excel 2007 file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
            } else {
                // Save it as an PDF file
                $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
            }

            ob_end_clean();
            $objWriter->save($path_file . "employeedeductlate" . $ext);
            $data = file_get_contents($path_file . "employeedeductlate" . $ext);
            force_download("employeedeductlate" . $ext, $data);
        }
    }
   

}

