<?php

//OVERTIME
class Home extends CI_Controller {

    public function __construct() {
        parent ::__construct();
        $this->load->model('loan_model', 'loan');
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
        $idmenu                    = "72";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('mst02/home',$data);
    }

    function index_detail($id) {
        $result = $this->loan->getall_detail($id);
        $check = ($result == '' or $result == null) ? "empty" : "exist";
        if ($check !== 'empty') {
            $i = 0;
            foreach ($result as $row) {
                $i++;
                $installmendate = date('d-m-Y', strtotime($row['InstallmentDate']));
                $installment = number_format($row['Installment'], '3', ',', '.');
                $note = $row['Note'];
                $flag = $row['Flag'];

                $data['fullname'] = $row['FullName'];
                $status = ($flag == '1') ? 'TERPOTONG' : 'BELUM';

                $table = "<tr  class='selectable' width=\"100%\">";
                $table.= "    <td align=\"left\">" . $i . "</td>";
                $table.= "    <td align=\"center\">" . $installmendate . "</td>";
                $table.= "    <td align=\"center\">" . $installment . "</td>";
                $table.= "    <td align=\"left\">" . $status . "</td>";
                $table.= "    <td align=\"left\">" . $note . "</td>";
                $table.= "</tr>";
                $data['activity'][$i]['tr'] = $table;
            }
        }

        $this->load->view('mst02/home_detail', $data);
    }

    function resultloanh() {
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
                echo $this->loan->loanemployee_h();
            } else if ($role == '0' and $param == 'Y') {
                echo $this->loan->loanemployee_h();
            }
        }
        
       
    }
    
    function get_access(){
        $button     = $this->input->post('btn');
        $idmenu     = '72';
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

     function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->search_employee_fieldpayroll($q);
        $data['response'] = 'true';
        $data['message'] = array();
        foreach ($result->result() as $row) {           
            $nip = $row->IDEmployee;
            $user = $this->User;
            if ($nip !== $user) {                
                $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                    'value' => $row->FullName,
                    'idemployee' => $row->IDEmployee
                );               
            }
        }
        echo json_encode($data);
    }

    function addnew() {
        $data['default']['f01'] = ''; //IDEmployee
        $data['default']['f02'] = ''; //FullName
        $data['default']['f03'] = ''; //LoanDate
        $data['default']['f04'] = ''; //Amount
        $data['default']['f05'] = ''; //Term      
        $data['default']['f06'] = ''; //Instalment  
        $data['default']['f07'] = ''; //DateInstalment
        $data['default']['f08'] = ''; //Note
	
	$data['datepicker']='ON';
        $data['default']['readonly_f01'] = 'READONLY';
	$data['default']['readonly_f06'] = 'READONLY';
        $data['url_post'] = site_url('mst02/home/addpost');
        $this->load->view('mst02/form', $data);
    }

    function addpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f02', 'FullName', 'required');
        $this->form_validation->set_rules('f03', 'LoanDate', 'required');
        $this->form_validation->set_rules('f04', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('f05', 'Term', 'required|numeric');
        $this->form_validation->set_rules('f06', 'Installment', 'required|numeric');
        $this->form_validation->set_rules('f07', 'DateInstalment', 'required');
        if ($this->form_validation->run() == TRUE) {
            $f01 = $this->input->post('f01');
            $f03 = date('Y-m-d', strtotime($this->input->post('f03')));
            $f04 = $this->input->post('f04');
            $f05 = $this->input->post('f05');
            $f06 = $this->input->post('f06');
            $f07 = date('Y-m-d', strtotime($this->input->post('f07')));
            $f08 = trim($this->input->post('f08'));

            $record = array(
                'IDEmployee' => $f01,
                'LoanDate' => $f03,
                'Amount' => $f04,
                'Term' => $f05,
                'Instalment' => $f06,
                'DateInstalment' => $f07,
                'Note' => $f08,
                'AddedBy' => $this->User,
                'AddedDate' => $this->Datetime,
                'AddedIP' => $this->Ip
            );

            $recordlog = array(
                'ID' => NULL,
                'username' => $this->User,
                'log_date' => $this->Datetime,
                'log_ip' => $this->Ip,
                'log_agent' => $this->Browser,
                'controller' => site_url('mst02/home/addnew'),
                'activities' => 'add new ' . $f01
            );

            $this->loan->insert($record);
            $this->logs->insert($recordlog);
            $mesg = 'Insert Data, SUccess';
            $valid = 'true';
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
            $err_f06 = '';
            $err_f07 = '';
        } else {
            $mesg = 'insert data, failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
            $err_f07 = form_error('f07');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
                       "err_f02":"' . $err_f02 . '",                    
                       "err_f03":"' . $err_f03 . '",                    
                       "err_f04":"' . $err_f04 . '",                    
                       "err_f05":"' . $err_f05 . '",                    
                       "err_f06":"' . $err_f06 . '",  
                       "err_f07":"' . $err_f07 . '"' .
                '}';
        echo $json;
    }

    function edit($id) {
        $this->session->set_userdata('id', $id);
        $row = $this->loan->get_by_id($id);

        $data['default']['f01'] = $row->IDEmployee;
        $data['default']['f02'] = $row->FullName;
        $data['default']['f03'] = $row->LoanDate;
        $data['default']['f04'] = $row->Amount;
        $data['default']['f05'] = $row->Term;
        $data['default']['f06'] = $row->Instalment;
        $data['default']['f07'] = $row->DateInstalment;
        $data['default']['f08'] = $row->Note;
	
        $data['datepicker']='OFF';
	
        $data['default']['readonly_f01'] = 'READONLY';
        $data['default']['readonly_f02'] = 'READONLY';
        $data['default']['readonly_f04'] = 'READONLY';
        $data['default']['readonly_f05'] = 'READONLY';
        $data['default']['readonly_f06'] = 'READONLY';
        $data['url_post'] = site_url('mst02/home/editpost');

        $this->load->view('mst02/form', $data);
    }

    function editpost() {
        $this->form_validation->set_rules('f01', 'IDEmployee', 'required');
        $this->form_validation->set_rules('f02', 'FullName', 'required');
        $this->form_validation->set_rules('f03', 'LoanDate', 'required');
        $this->form_validation->set_rules('f04', 'Amount', 'required|numeric');
        $this->form_validation->set_rules('f05', 'Term', 'required|numeric');
        $this->form_validation->set_rules('f06', 'Installment', 'required|numeric');
        $this->form_validation->set_rules('f07', 'DateInstalment', 'required');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->session->userdata('id');
            $f03 = date('Y-m-d', strtotime($this->input->post('f03')));
            $f04 = $this->input->post('f04');
            $f05 = $this->input->post('f05');
            $f06 = $this->input->post('f06');
            $f07 = date('Y-m-d', strtotime($this->input->post('f07')));
            $f08 = trim($this->input->post('f08'));

            $record = array(
                'LoanDate' => $f03,
                'Amount' => $f04,
                'Term' => $f05,
                'Instalment' => $f06,
                'DateInstalment' => $f07,
                'Note' => $f08,
                'EditedBy' => $this->User,
                'EditedDate' => $this->Datetime,
                'EditedIP' => $this->Ip
            );

            $recordlog = array(
                'ID' => NULL,
                'username' => $this->User,
                'log_date' => $this->Datetime,
                'log_ip' => $this->Ip,
                'log_agent' => $this->Browser,
                'controller' => site_url('mst02/home/addnew'),
                'activities' => 'edit data id ' . $id
            );

            $this->loan->update($id, $record);
            $this->logs->insert($recordlog);

            $mesg = "Update Data, Success";
            $valid = "true";
            $err_f01 = '';
            $err_f02 = '';
            $err_f03 = '';
            $err_f04 = '';
            $err_f05 = '';
            $err_f06 = '';
            $err_f07 = '';
        } else {
            $mesg = 'Update Data, Failed';
            $valid = 'false';
            $err_f01 = form_error('f01');
            $err_f02 = form_error('f02');
            $err_f03 = form_error('f03');
            $err_f04 = form_error('f04');
            $err_f05 = form_error('f05');
            $err_f06 = form_error('f06');
            $err_f07 = form_error('f07');
        }
        $json = '{ "mesg":"' . $mesg . '", 
                       "valid":"' . $valid . '", 
                       "err_f01":"' . $err_f01 . '",
                       "err_f02":"' . $err_f02 . '",                    
                       "err_f03":"' . $err_f03 . '",                    
                       "err_f04":"' . $err_f04 . '",                    
                       "err_f05":"' . $err_f05 . '",                    
                       "err_f06":"' . $err_f06 . '",  
                       "err_f07":"' . $err_f07 . '"' .
                '}';
        echo $json;
    }

    function delete($id) {
        $record = array(
            "DeleteBy" => $this->User,
            "DeleteFlag" => 'D',
            "DeleteDate" => $this->Datetime,
            "DeleteIP" => $this->Ip
        );

        $this->loan->update($id, $record);
        $mesg = "Delete Data, Success";
        $valid = 'true';

        $json = '{ "mesg":"' . $mesg . '",
                           "valid":"' . $valid . '"' .
                '}';
        echo $json;
    }

    function exportdata() {
        $ext = '.xlsx';
        $path_file = 'tmp';
        $name_file = 'export';

        $this->load->library('PHPExcel');
        $this->load->library('PHPExcel/IOFactory');
        $this->load->helper('download');

        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("title")
                ->setDescription("description");

        $currencyFormat = '#,#0.## \€;[Red]-#,#0.## \€';
        $numberFormat = '#,#0.##;[Red]-#,#0.##';

        $objSheet = $objPHPExcel->getActiveSheet();
        $objSheet->setTitle($name_file);
        $objSheet->getStyle('A1:N15')->getFont()->setBold(true)->setSize(12);

        // header 

        $objSheet->getCell('A9')->setValue('PT. TRIAS INDRA SAPUTRA');
        $objSheet->getCell('A10')->setValue('LAPORAN PERSONAL LOAN');
        $objSheet->getCell('A11')->setValue('PERIODE :' . date('d-m-Y'));

        //add sub header
        $objSheet->getCell('A14')->setValue('NO.');
        $objSheet->getCell('B14')->setValue('PERSONAL');
        $objSheet->getCell('B15')->setValue('IDEmployee');
        $objSheet->getCell('C15')->setValue('FullName');
        $objSheet->getCell('D15')->setValue('Group');
        $objSheet->getCell('E14')->setValue('LOAN DATE');
        $objSheet->getCell('F14')->setValue('AMOUNT');
        $objSheet->getCell('G14')->setValue('TERM');
        $objSheet->getCell('H14')->setValue('INSTALLMENT');
        $objSheet->getCell('I14')->setValue('FIRST INSTALLMENT');
        $objSheet->getCell('J14')->setValue('NOTE');
        $objSheet->getCell('K14')->setValue('LOAN');
        $objSheet->getCell('K15')->setValue('INSTALLMENT DATE');
        $objSheet->getCell('L15')->setValue('INSTALLMENT');
        $objSheet->getCell('M15')->setValue('STATUS');
        $objSheet->getCell('N15')->setValue('NOTE DETAIL');

        // add margecell 
        $sheet = $objPHPExcel->getActiveSheet();
        $sheet->mergeCells('B14:D14');
        $sheet->mergeCells('K14:N14');

        //add center   
        $sheet->getStyle('B14:D14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K14:N14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('M15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N15')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //add border
        $objSheet->getStyle('A13:N15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A13:A15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('B13:D15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('E13:E15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('F13:F15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('G13:G15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('H13:H15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('I13:I15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('K13:N15')->getBorders()->
                getOutline()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        $result = $this->loan->get_alldata();
        if ($result != NULL) {
            $i = 15;
            $sw = 0;
            $id = "";
            $no = 0;
            foreach ($result as $row) {

                $i++;
                $group = $this->libfun->get_name_group($row->IDJobGroup);
                $status = ($row->Flag == 1) ? 'TERPOTONG' : 'BELUM';
                if ($sw == 0) {
                    $sw = 1;
                    $no++;
                    $id = $row->IDEmployee;
                }
                if (($row->IDEmployee != $id) OR ($no == 1)) {
                    $objSheet->getCell('A' . $i)->setValue($no);
                    $objSheet->getCell('B' . $i)->setValue("'" . $row->IDEmployee);
                    $objSheet->getCell('C' . $i)->setValue($row->FullName);
                    $objSheet->getCell('D' . $i)->setValue($group);
                    $objSheet->getCell('E' . $i)->setValue(date('d-m-Y', strtotime($row->LoanDate)));
                    $objSheet->getCell('F' . $i)->setValue($row->Amount);
                    $objSheet->getCell('G' . $i)->setValue($row->Term);
                    $objSheet->getCell('H' . $i)->setValue($row->Instalment);
                    $objSheet->getCell('I' . $i)->setValue(date('d-m-Y', strtotime($row->DateInstalment)));
                    $objSheet->getCell('J' . $i)->setValue($row->Note);
                    $objSheet->getCell('K' . $i)->setValue(date('d-m-Y', strtotime($row->InstallmentDate)));
                    $objSheet->getCell('L' . $i)->setValue($row->Installment);
                    $objSheet->getCell('M' . $i)->setValue($status);
                    $objSheet->getCell('N' . $i)->setValue($row->NoteDetail);
                    $id = $row->IDEmployee;
                    $no++;
                } else {
                    $objSheet->getCell('A' . $i)->setValue();
                    $objSheet->getCell('B' . $i)->setValue("'" . $row->IDEmployee);
                    $objSheet->getCell('C' . $i)->setValue($row->FullName);
                    $objSheet->getCell('D' . $i)->setValue($group);
                    $objSheet->getCell('E' . $i)->setValue(date('d-m-Y', strtotime($row->LoanDate)));
                    $objSheet->getCell('F' . $i)->setValue($row->Amount);
                    $objSheet->getCell('G' . $i)->setValue($row->Term);
                    $objSheet->getCell('H' . $i)->setValue($row->Instalment);
                    $objSheet->getCell('I' . $i)->setValue(date('d-m-Y', strtotime($row->DateInstalment)));
                    $objSheet->getCell('J' . $i)->setValue($row->Note);
                    $objSheet->getCell('K' . $i)->setValue(date('d-m-Y', strtotime($row->InstallmentDate)));
                    $objSheet->getCell('L' . $i)->setValue($row->Installment);
                    $objSheet->getCell('M' . $i)->setValue($status);
                    $objSheet->getCell('N' . $i)->setValue($row->NoteDetail);
                }
            }
        }
        // body border 
        $objSheet->getStyle('A16:N' . $i)->getBorders()->getAllBorders()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A16:N' . $i)->getBorders()->getOutline()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        $objSheet->getStyle('A16:N' . $i)->getBorders()->getBottom()->
                setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        // footer
        $j = $i + 3;
        $k = $i + 4;
        $objSheet->getCell('N' . $j)->setValue('Jakarta, ' . date('d-F-Y'));
        $objSheet->getCell('N' . $k)->setValue('System Development');
        ob_end_clean();
        if ($ext == ".xlsx") {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'Excel2007');
        } else {
            $objWriter = IOFactory::createWriter($objPHPExcel, 'PDF');
        }
        $tmpfile = "/" . $path_file . "/" . $name_file . $this->input->ip_address() . $ext;
        $special = array(" ", ":");
        $tmpfile = str_replace($special, "", $tmpfile);

        ob_end_clean();
        $objWriter->save($tmpfile);
        $data = file_get_contents($tmpfile);
        force_download('loan' . $ext, $data);
    }

    function schedule($id = 0) {
        if ($id != 0) {
            $header = $this->loan->get_by_id($id);
        } else {
            $id = $this->input->post('id');
            $header = $this->loan->get_by_id($id);
        }
        $nip = $header->IDEmployee;
        $term = $header->Term;
        $date = $header->DateInstalment;
        $installment = $header->Instalment;
        $j = 0;
        for ($i = 0; $i < $term; $i++) {
            $j++;
            $duedate = strtotime("+ $i month", strtotime($date));
            $record = array(
                "IDHeader" => $id,
                "IDEmployee" => $nip,
                "InstallmentDate" => date("Y-m-d", $duedate),
                "Installment" => $installment,
                "Note" => "CICILAN KE " . $j . " DARI " . $term
            );
            $duedate = date("Y-m-d", $duedate);
            $this->loan->add_update_d($id, $nip, $duedate, $record);
            //echo "$i $id $nip $duedate $installment"."<br>";
        }
        $valid = 'true';
        $mesg = "ID=" . $id;
        $json = '{ "mesg":"' . $mesg . '", "valid":"' . $valid . '" }';
        echo $json;
    }

    function removedata($id = 0) {
        if ($id != 0) {
            $header = $this->loan->get_by_id($id);
        } else {
            $id = $this->input->post('id');
            $header = $this->loan->get_by_id($id);
        }

        $flag0 = $this->loan->get_headerflag0($id, 0);
        $flag1 = $this->loan->get_headerflag1($id, 1);
        $term = $header->Term;
        /*
          echo 'IDHeader :'.$id.'<br/>';
          echo 'term :'.$term.'<br/>';
          echo 'flag 0 :'.$flag0.'<br/>';
          echo 'flag 1 :'.$flag1.'<br/>';
         * 
         */

        if ($term == $flag0 or $term == $flag1) {
            $this->loan->delete_d($id);
            $this->loan->delete_h($id);
            $valid = 'true';
            $mesg = "Data Berhasil Di Delete";
        } else if ($flag0 == null and $flag1 == null) {
            $this->loan->delete_d($id);
            $this->loan->delete_h($id);
            $valid = 'true';
            $mesg = "Data Berhasil Di Delete";
        } else {
            $valid = 'false';
            $mesg = "Delete gagal karena Cicilan sedang berjalan";
        }
        $json = '{ "mesg":"' . $mesg . '", "valid":"' . $valid . '" }';
        echo $json;
    }

}

