<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('note_model', 'note');
    }

    function index() {
        // error_reporting(0);
        $fdate = ($this->session->userdata('fromdate') == '') ? 'empty' : $this->session->userdata('fromdate');
        $udate = ($this->session->userdata('untildate') == '') ? 'empty' : $this->session->userdata('fromdate');

        if ($fdate !== 'empty' and $udate !== 'empty') {
            $fromdate = $this->session->userdata('fromdate');
            $untildate = $this->session->userdata('untildate');
        } else {
            $date = $this->period();
            $fromdate = date('Y-m-d', strtotime($this->session->userdata('from')));
            $untildate = date('Y-m-d', strtotime($this->session->userdata('until')));

            $this->session->set_userdata('fromdate', $fromdate);
            $this->session->set_userdata('untildate', $untildate);
        }
        
        
        $user     = $this->session->userdata('sess_userid');
        $row      = $this->note->get_idparent($user);  
        $parent   = $row->IDEmployeeParent;
	$row2     = $this->note->get_idposition($user);  
	$position = $row2->IDJobPosition;

	$data['user'] = $user;
	$data['parent'] = $parent;      
        
        $result = $this->note->get_alldata($fromdate,$untildate,$user,$position);        
         
        if ($result == NULL) {
            $item['fdate'] = date('d-m-Y', strtotime($fromdate));
            $item['udate'] = date('d-m-Y', strtotime($untildate));
            $this->load->view('trx07/home_null', $item);
        } else {
            $i = 0;
            foreach ($result as $row) {
                $i++;
                $user = $row['User'];
                $date = date('Y-m-d', strtotime($row['DateCurrent']));
                $activity = $row['Activity'];
                $problem = $row['Problem'];
                $solution = $row['Solution'];
		$type	= $row['Type'];
		$level	= $row['LevelActivity'];
                
                $table = "<tr  class='selectable' width=\"100%\">";
                //$table.= "    <td align=\"left\">" . $i . "</td>";
                $table.= "    <td align=\"center\">" . $user . "</td>";
                $table.= "    <td align=\"center\">" . $date . "</td>";
                $table.= "    <td align=\"left\">" . $activity . "</td>";
                $table.= "    <td align=\"left\">" . $level . "</td>";
		$table.= "    <td align=\"left\">" . $type . "</td>";
                $table.= "    <td align=\"left\">" . $problem . "</td>";
                $table.= "    <td align=\"left\">" . $solution . "</td>";
                $table.= "</tr>";
                
                $data['activity'][$i]['tr'] =$table;
                  
            }

            $data['default']['from'] = date('d-m-Y', strtotime($fromdate));
            $data['default']['until'] = date('d-m-Y', strtotime($untildate));

            $this->load->view('trx07/home',$data);
        }
    }
    
    
 
    function period() {
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
    
     function periode_date() {
        $valid = "true";
        $fromdate = date('Y-m-d', strtotime($this->input->post('fromdate')));
        $untildate = date('Y-m-d', strtotime($this->input->post('untildate')));
        $this->session->set_userdata('fromdate', $fromdate);
        $this->session->set_userdata('untildate', $untildate);
        echo '{ "valid":"' . $valid . '"}';
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
  
    
    function add() {
        //customer 
        $data['f01'] ='';
        $data['f02'] ='';
        $data['f03'] ='';
        
        $data['default']['f05'][0]['value'] = "Low";
        $data['default']['f05'][0]['display'] = "<span style='color:white;'>" . "Low" . "</span>";
        $data['default']['f05'][0]['checked'] = "CHECKED";
        $data['default']['f05'][1]['value'] = "Medium";
        $data['default']['f05'][1]['display'] = "<span style='color:white;'>" . "Medium" . "</span>";
        $data['default']['f05'][1]['checked'] = "";
        $data['default']['f05'][2]['value'] = "High";
        $data['default']['f05'][2]['display'] = "<span style='color:white;'>" . "High" . "</span>";
        $data['default']['f05'][2]['checked'] = "";        

        $data['default']['f04'][0]['value'] = 'Routine';
        $data['default']['f04'][0]['display'] = 'Routine';
        $data['default']['f04'][1]['value'] = 'Request';
        $data['default']['f04'][1]['display'] = 'Request';
        $data['default']['f04'][2]['value'] = 'Complain';
        $data['default']['f04'][2]['display'] = 'Complain';         
        $data['url_post'] = site_url('trx07/home/addpost');          
        $this->load->view('trx07/form', $data);
    }
    
    function addpost(){
        $this->form_validation->set_rules('f01','Activity','required');
       if($this->form_validation->run() == TRUE){
           $f01    = $this->input->post('f01');
           $f02    = $this->input->post('f02');
           $f03    = $this->input->post('f03');
	   $f04    = $this->input->post('f04');
	   $f05    = $this->input->post('f05');
           $user   = $this->session->userdata('sess_userid');
           $row    = $this->note->get_idparent($user);  
           $rowposition = $this->note->get_idposition($user);  
           $prt      = $row->IDEmployeeParent;           
           $position = $rowposition->IDJobPosition;
           
           if($position =='MANAGER'){
               $parent = $user;
           }else{
               $parent = $prt;
           }
           
           $record = array(
                "UserID"=>$user,
                "ParentID"=>$parent,
                "DateCurrent"=>date('Y-m-d'),
                "Activity"=>$f01,
                "Problem"=>$f02,
                "Solution"=>$f03,
		"Type"=>$f04,	
		"LevelActivity"=>$f05	
           );           
           $this->note->insert($record);           
           
            $mesg = 'insert data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
           
       }else{
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
       }
            
           $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '", 
                       "err_f02":"' . $err_f02 . '", 
                       "err_f03":"' . $err_f03 . '"' .
                    '}';
            echo $json;
           
    }
    
    function edit($id) {
        $row = $this->note->get_by_id($id);
        $this->session->set_userdata('ID', $id);
       
        $data['default']['f01'] = $row->Activity;
        $data['default']['f02'] = $row->Problem;
        $data['default']['f03'] = $row->Solution;

        $data['default']['readonly_f01'] = 'READONLY';  
    
        $data['url_post'] = site_url('trx07/home/editpost');    

        $this->load->view('trx07/form', $data);
    }
    
    
    function editpost(){
        $this->form_validation->set_rules('f01','Activity','required');
       if($this->form_validation->run() == TRUE){
           $id = $this->session->userdata('ID');
           $f01 = $this->input->post('f01');
           $f02 = $this->input->post('f02');
           $f03 = $this->input->post('f03');
           
           $record = array(
                "UserID"=>$this->session->userdata('sess_userid'),
                "DateCurrent"=>date('Y-m-d'),
                "Activity"=>$f01,
                "Problem"=>$f02,
                "Solution"=>$f03
           );  
           
           $this->note->update($id,$record);           
           
            $mesg = 'update data, success';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
           
       }else{
            $mesg = 'update data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
       }
            
           $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '", 
                       "err_f02":"' . $err_f02 . '", 
                       "err_f03":"' . $err_f03 . '"' .
                    '}';
            echo $json;
           
    }
    
    function delete($id) {    
        $this->note->delete($id);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }
    
    
     function excel() {
       error_reporting(0);

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        // add class excel
        $excel = new PHPExcel();

        //add property
        $excel->getProperties()->setCreator("PHP Excel")
                ->setLastModifiedBy("PHP Excel")
                ->setTitle("Daily Activition")
                ->setSubject("Daily Activition")
                ->setDescription("Daily Activition")
                ->setKeywords("Daily Activition")
                ->setCategory("Daily Activition");

        //add style
        $worksheet = $excel->getActiveSheet();
        $worksheet->getStyle('A1:H15')->getFont()->setBold(true)->setSize(12);

	//text wrap
	$excel->getActiveSheet()->getStyle('D15:D'.$excel->getActiveSheet()->getHighestRow())
         ->getAlignment()->setWrapText(true); 
	$excel->getActiveSheet()->getStyle('F15:F'.$excel->getActiveSheet()->getHighestRow())
         ->getAlignment()->setWrapText(true); 
        $excel->getActiveSheet()->getStyle('G15:G'.$excel->getActiveSheet()->getHighestRow())
         ->getAlignment()->setWrapText(true); 

        //add header      
        $worksheet->getCell('A9')->setValue('PT. TRIAS INDRA SAPUTRA');
        $worksheet->getCell('A10')->setValue('REPORT DAILY ACTIVITION');
        $worksheet->getCell('A12')->setValue('DATE :' . date('d-m-Y'));


        //add sub header
   
        $worksheet->getCell('A15')->setValue('NO');
        $worksheet->getCell('B15')->setValue('User');
        $worksheet->getCell('C15')->setValue('Date');
        $worksheet->getCell('D15')->setValue('Activity');
        $worksheet->getCell('E15')->setValue('Level');
	$worksheet->getCell('F15')->setValue('On');	
        $worksheet->getCell('G15')->setValue('Problem');
        $worksheet->getCell('H15')->setValue('Solution');

        // add autofilter
        //$worksheet->setAutoFilter('B1:B15');
        // add tab color 
        $worksheet->getTabColor()->setRGB('47255114');

        // add margecell 
        //$worksheet = $excel->getActiveSheet();
        //$worksheet->mergeCells('N14:T14');

        //add center   
     
        $worksheet->getStyle('A15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('B15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('C15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('D15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('E15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('F15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('G15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $worksheet->getStyle('H15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       
       

        //add border
        $worksheet->getStyle('A15:G15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);       
        
    
		
        $from = $this->session->userdata('fromdate');
        $until = $this->session->userdata('untildate');
        
        $user     = $this->session->userdata('sess_userid'); 
	$row2     = $this->note->get_idposition($user);  
	$position = $row2->IDJobPosition;     
        
         $result = $this->note->get_alldata($from,$until,$user,$position);   
          $i = 15;
           
            foreach ($result as $row) {
                $i++;


                    $worksheet->getCell('A' . $i)->setValue($no);
                    $worksheet->getCell('B' . $i)->setValue($row['User']);
                    $worksheet->getCell('C' . $i)->setValue($row['DateCurrent']);
                    $worksheet->getCell('D' . $i)->setValue($row['Activity']);
                    $worksheet->getCell('E' . $i)->setValue($row['LevelActivity']);
		    $worksheet->getCell('F' . $i)->setValue($row['Type']);	
                    $worksheet->getCell('G' . $i)->setValue($row['Problem']);
                    $worksheet->getCell('H' . $i)->setValue($row['Solution']);
                 

                } 
            
        
        
        // body border 
        $worksheet->getStyle('A15:H' . $i)->getBorders()->getAllBorders()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A15:H' . $i)->getBorders()->getOutline()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $worksheet->getStyle('A15:H' . $i)->getBorders()->getBottom()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        // footer
        $j = $i + 3;
        $k = $i + 4;
        $l = $i + 8;
        $worksheet->getCell('E' . $j)->setValue('Jakarta, ' . date('d-F-Y'));
        $worksheet->getCell('E' . $k)->setValue('PT. Trias Indra Saputra');
        $worksheet->getCell('E' . $l)->setValue('System Development');

        ob_end_clean();

        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="reportdailyactivity.xlsx"');


        $objWriter = IOFactory::createWriter($excel, 'Excel2007');
        //$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        $excel->disconnectWorksheets();
        unset($excel);
    }
        
    

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */


