<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('monthlyprocess_model', 'monthlyprocess');
        $this->load->model('libraryfunction_model', 'libfun');
        $this->load->model('Employee_model', 'employee');
	$this->load->model('userlogin_model', 'login');
        $this->load->model('menuaccess_model', 'access');
        $this->load->model('Param_model', 'param');
        $this->load->model('uac_model', 'uac');

        date_default_timezone_set("Asia/Jakarta");
        $this->User = $this->session->userdata('sess_userid');
        $this->Datetime = date('Y-m-d H:i:s');
        $this->Ip = $this->input->ip_address();
        $this->Browser = $this->agent->agent_string();
    }

    function index() {
        $date = $this->periodemonthly();
        $data['default']['f01'] = substr($date, 0, 10);
        $data['default']['f02'] = substr($date, 11, 10);
        $this->session->set_userdata('fromdate', date('Y-m-d', strtotime($data['default']['f01'])));
        $this->session->set_userdata('untildate', date('Y-m-d', strtotime($data['default']['f02'])));

	$idmenu                    = "73";
        $data['buttons']           = $this->uac->get_btnaccess($this->User,$idmenu);
        $this->load->view('proc01/home', $data);
    }

    function suggest_employee() {
        $q = trim($this->input->post('term'));
        $result = $this->employee->search_employee_field($q);
        $data['response'] = 'true';
        $data['message'] = array();
        foreach ($result->result() as $row) {
            $status = $row->Status;
            $now = $row->Sekarang;
            $lock = $row->BatasFilter;

            if ($status == 'P') {
                if ($now <= $lock) {
                    $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                        'value' => $row->IDEmployee,
                        'idemployee' => $row->IDEmployee
                    );
                }
            } else {
                $data['message'][] = array('label' => $row->IDEmployee . " | " . $row->FullName,
                    'value' => $row->IDEmployee,
                    'idemployee' => $row->IDEmployee
                );
            }
        }
        echo json_encode($data);
    }

    function periodemonthly() {
        $today = array('Year' => date('Y'), 'Month' => date('m'));
        $untildate = date('d-m-Y', strtotime($today['Year'] . "-" . $today['Month'] . "-24"));
        $from = date('d-m-Y', strtotime("-1 month +1 day", strtotime($untildate)));
        return $from . " " . $untildate;
    }

    function postingmonthly() {
        $fromdate = date('Y-m-d', strtotime($this->input->post('f01')));
        $untildate = date('Y-m-d', strtotime($this->input->post('f02')));
        $posingdate = date('Y-m-d', strtotime($this->input->post('f02')));
        $nip = $this->input->post('f03');

        //for delete all data on periode payroll
        $this->prephare_dailysalary($posingdate, $nip);
        $this->prephare_dailyovertime($posingdate, $nip);
        $this->prephare_deduction($posingdate, $nip);
        $this->prephare_payslip($posingdate, $nip);
        //for create deduction                     
        $this->loan_manual($fromdate, $untildate, $nip);
        $this->loan_system($fromdate, $untildate, $nip);
        //for create payslip from summary deduction loan
        $this->sum_deduction_loan($posingdate, $fromdate, $nip);
        //for create payslip from summary deduction outstanding
        $this->sum_deduction_outstanding($posingdate, $fromdate, $nip);
        //for create payslip from summary deduction other
        $this->sum_deduction_other($posingdate, $fromdate, $nip);
        //for create additional every periode on status active
        $this->additional_permanen($posingdate, $nip);
        //for create payslip from summary additional
        $this->sum_additional($posingdate, $nip);
        //for create daily salary from table presence
        $this->dailysalary($posingdate, $fromdate, $untildate, $nip);
        //for create payslip from summary daily salary
        $this->sum_dailysalary($posingdate, $nip);
        //for create daily overtime from table overtime
        $this->dailyovertime($posingdate, $fromdate, $untildate, $nip);
        //for create payslip from summary daily overtime
        $this->sum_dailyovertime($posingdate, $nip);
        //for recalculate all data on periode posting
        $this->recalculate($posingdate);

        $mesg = "all done";
        $valid = 'true';
        $err_f01 = '';
        $err_f02 = '';
        $err_f03 = '';

        $json = '{ "mesg":"' . $mesg . '",
                      "valid":"' . $valid . '",
                      "err_f01":"' . $err_f01 . '",
                      "err_f02":"' . $err_f02 . '",
                      "err_f03":"' . $err_f03 . '"' .
                '}';

        echo $json;
    }

    function prephare_dailysalary($posting = '', $nip = '') {
        $this->monthlyprocess->delete_dailysalary($posting, $nip);
    }

    function prephare_dailyovertime($posting = '', $nip = '') {
        $this->monthlyprocess->delete_dailyovertime($posting, $nip);
    }

    function prephare_deduction($posting = '', $nip = '') {
        $this->monthlyprocess->delete_deduction($posting, $nip);
    }

    function prephare_payslip($posting = '', $nip = '') {
        $this->monthlyprocess->delete_payslip($posting, $nip);
    }

    function loan_manual($fromdate, $untildate, $nip = '') {
        $result = $this->monthlyprocess->getperiode_loanmanual($fromdate, $untildate, $nip = '');
        $check = ($result == '' or $result == null) ? 'empty' : 'exist';

        if ($check == 'exist') {
            $i = 0;
            foreach ($result as $row) {
                $i++;
                $IDRecord = $row['ID'];
                $Nip = $row['IDEmployee'];
                $PostingDate = $row['PostingDate'];
                $Parameter = $row['Parameter'];
                $Flag = $row['FlagLoan'];

                $record = array(
                    'IDEmployee' => $Nip,
                    'PostingDate' => $PostingDate,
                    'Amount' => $row['Amount'],
                    'Parameter' => $Parameter,
                    'FlagLoan' => $Flag,
                    'IDRecord' => $IDRecord,
                    'Note' => $row['Note'],
                    'AddedBy' => $row['AddedBy'],
                    'AddedDate' => $row['AddedDate'],
                    'AddedIP' => $row['AddedIP'],
                    'EditedBy' => $row['EditedBy'],
                    'EditedDate' => $row['EditedDate'],
                    'EditedIP' => $row['EditedIP'],
                );

                //this function if check result exist then update else insert to table deduction
                $resultdata = $this->monthlyprocess->check_deduction_system($IDRecord, $PostingDate, $Nip, $Parameter, $Flag);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_deduction_system($IDRecord, $PostingDate, $Nip, $Parameter, $Flag, $record);
                } else {
                    $this->monthlyprocess->insert_deduction($record);
                }
            }
        }
    }

    function loan_system($fromdate, $untildate, $nip = '') {
        $result = $this->monthlyprocess->getperiode_loansystem($fromdate, $untildate, $nip);
        $check = ($result == '' or $result == null) ? 'empty' : 'exist';

        if ($check == 'exist') {
            $i = 0;
            foreach ($result as $row) {
                $i++;

                $IDRecord = $row['ID'];
                $Nip = $row['IDEmployee'];
                $InstallmentDate = $row['InstallmentDate'];
                $Installment = $row['Installment'];
                $Parameter = 'LOAN';
                $Flag = 'System';
                $Note = $row['Note'];

                $record = array(
                    'PostingDate' => $InstallmentDate,
                    'IDEmployee' => $Nip,
                    'Amount' => $Installment,
                    'Parameter' => $Parameter,
                    'FlagLoan' => $Flag,
                    'IDRecord' => $IDRecord,
                    'Note' => $Note
                );

                $Header = $row['IDHeader'];
                $record_loan = array(
                    'Flag' => 1
                );

                $this->monthlyprocess->update_loan_system($Header, $Nip, $InstallmentDate, $record_loan);

                //this function if check result exist then update else insert to table deduction
                $resultdata = $this->monthlyprocess->check_deduction_system($IDRecord, $InstallmentDate, $Nip, $Parameter, $Flag);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_deduction_system($IDRecord, $InstallmentDate, $Nip, $Parameter, $Flag, $record);
                } else {
                    $this->monthlyprocess->insert_deduction($record);
                }
            }
        }
    }

    function sum_deduction_loan($posting = '', $from = '', $nip = '') {
        $param = 'LOAN';
        $result = $this->monthlyprocess->sum_deduc($posting, $from, $param, $nip);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {
            foreach ($result as $row) {
                $IDEmployee = $row['IDEmployee'];
                $loan = $row['Amount'];

                $record = array(
                    'PostingDate' => $posting,
                    'IDEmployee' => $IDEmployee,
                    'LoanPayment' => $loan
                );

                //this function if check result exist then update else insert to table payslip
                $resultdata = $this->monthlyprocess->check_payslip($posting, $IDEmployee);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_payslip($posting, $IDEmployee, $record);
                } else {
                    $this->monthlyprocess->insert_payslip($record);
                }
            }
        }
    }

    function sum_deduction_outstanding($posting, $from = '', $nip = '') {
        $param = 'OUTSTANDING';
        $result = $this->monthlyprocess->sum_deduc($posting, $from, $param, $nip);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {
            foreach ($result as $row) {
                $IDEmployee = $row['IDEmployee'];
                $outstanding = $row['Amount'];

                $record = array(
                    'PostingDate' => $posting,
                    'IDEmployee' => $IDEmployee,
                    'OutstandingPayment' => $outstanding
                );

                //this function if check result exist then update else insert to table payslip
                $resultdata = $this->monthlyprocess->check_payslip($posting, $IDEmployee);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_payslip($posting, $IDEmployee, $record);
                } else {
                    $this->monthlyprocess->insert_payslip($record);
                }
            }
        }
    }

    function sum_deduction_other($posting = '', $from = '', $nip = '') {
        $param = 'OTHER';
        $result = $this->monthlyprocess->sum_deduc($posting, $from, $param, $nip);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {
            foreach ($result as $row) {
                $IDEmployee = $row['IDEmployee'];
                $other = $row['Amount'];

                $record = array(
                    'PostingDate' => $posting,
                    'IDEmployee' => $IDEmployee,
                    'OtherPayment' => $other
                );

                //this function if check result exist then update else insert to table payslip
                $resultdata = $this->monthlyprocess->check_payslip($posting, $IDEmployee);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_payslip($posting, $IDEmployee, $record);
                } else {
                    $this->monthlyprocess->insert_payslip($record);
                }
            }
        }
    }

    //request sally untuk addtional secara system pertanggal
    // 26-03-2014 15:14      
    function additional_permanen($posting = '', $nip = '') {
        $result = $this->monthlyprocess->get_insentif($nip);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {
            foreach ($result as $row) {
                $nip = $row['IDEmployee'];
                $amount = $row['Amount'];
                $note = 'BY SYSTEM - ' . $row['Note'];
                $date = $posting;
                $param = 'INSENTIF';
                $flag = 'SYSTEM';

                $record = array(
                    "IDEmployee" => $nip,
                    "PostingDate" => $date,
                    "Amount" => $amount,
                    "Parameter" => $param,
                    "FlagEntry" => $flag,
                    "Note" => $note,
                );

                //this function if check result exist then update else insert to table deduction
                $resultdata = $this->monthlyprocess->check_insentif($nip, $date, $param, $flag);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_insentif_system($nip, $date, $param, $flag, $record);
                } else {
                    $this->monthlyprocess->insert_insentif_system($record);
                }
            }
        }
    }

    function sum_additional($posting = '', $nip = '') {
        $param = '';
        $result = $this->monthlyprocess->sum_addition($posting, $param, $nip);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {

            foreach ($result as $row) {
                $PostingDate = $row['PostingDate'];
                $IDEmployee = $row['IDEmployee'];
                $Amount = $row['Amount'];

                $record = array(
                    'PostingDate' => $PostingDate,
                    'IDEmployee' => $IDEmployee,
                    'OtherIncome' => $Amount
                );
                //this function if check result exist then update else insert to table payslip
                $resultdata = $this->monthlyprocess->check_payslip($PostingDate, $IDEmployee);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_payslip($posting, $IDEmployee, $record);
                } else {
                    $this->monthlyprocess->insert_payslip($record);
                }
            }
        }
    }

   function dailysalary($posting = '', $from = '', $until = '', $nip = '') {
        $result = $this->monthlyprocess->getdata_presence($from, $until, $nip);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {
            $counter = 0;
            $i = 0;
            $flag = 0;
            $fullweek = 0;
            $IDEmployeeLast = "";
            $CountSalary = 0;

            foreach ($result as $row) {
                $i++;
                if ($flag == 0) {
                    $flag = 1;
                    $IDEmployeeLast = $row['IDEmployee'];
                    $fullweek = 1;
                }
                if ($IDEmployeeLast <> $row['IDEmployee']) {
                    $counter++;
                    $IDEmployeeLast = $row['IDEmployee'];
                    $fullweek = 1;
                }

                $IDEmployee = $row['IDEmployee'];
		$group = $row['IDJobGroup'];
                $PresenceDate = $row['PresenceDate'];
                $ResignDate = $row['ResignDate'];
                $WorkDay = $row['WorkDay'];
                $ActualIn = $row['ActualIn'];
                $ActualOut = $row['ActualOut'];
                $ManualIn = $row['ManualIn'];
                $ManualOut = $row['ManualOut'];
                $DailySalary = $row['DailySalary'];
                $MonthlySalary = $row['MonthlySalary'];
                $Insurance = $row['Insurance'];
                $Description = $row['Description'];
                $Necessity = $row['Necessity'];

                if (!$ResignDate) {
                    $FlagResign = False;
                } else {
                    $FlagResign = True;
                }

                // Check Presence Status 
                $PresenceStatus = 0;
                if (is_null($ActualIn) OR is_null($ActualOut)) {


                    // when actual IN/OUT is null                
                    if ($Description == 'LP') {
                        //when leavepermit status = personal then check condition workhour
                        if ($Necessity == '1') {
                            $PresenceStatus = 1;
                            if (abs(((strtotime($ManualOut) - strtotime($ActualIn)) / 3600)) < 4) {
                                $PresenceStatus = 0;
                            } else if ($ActualIn == null and $ManualOut !== null) {
                                $PresenceStatus = 0;
                            }
                            //when leavepermit status = office, then automatic presence
                        } else if ($Necessity == '2') {
                            $PresenceStatus = 1;
                        }
                    }else if($Description == 'PLW'){
                            $PresenceStatus = 1;                        
                    }
                    //end leavepermit

                    if (is_null($ManualIn) OR is_null($ManualOut)) {
                        if ($WorkDay == 'SUN' OR $WorkDay == 'OFF' AND $Description !== 'ALD') {
                            $PresenceStatus = 1;
                            if ($Description == 'SP') {
                                $PresenceStatus = 0;
                            }
                            if ($FlagResign) {
                                if ($PresenceDate <= $ResignDate) {
                                    $PresenceStatus = 1;
                                    if ($Description == 'SP') {
                                        $PresenceStatus = 0;
                                    }
                                } else {
                                    $PresenceStatus = 0;
                                }
                            }
                        } else {
                            $PresenceStatus = 0;
                        }

                        if ($Description == 'LSN') {
                            $PresenceStatus = 1;
                            //start when leavepermit 
                        } else if ($Description == 'LP') {
                            //when leavepermit status = persona then check condition workhour
                            if ($Necessity == '1') {
                                $PresenceStatus = 1;
                                if (abs(((strtotime($ManualOut) - strtotime($ActualIn)) / 3600)) < 4) {
                                    $PresenceStatus = 0;
                                } else if ($ActualIn == null and $ManualOut !== null) {
                                    $PresenceStatus = 0;
                                }
                                //when leavepermit status = office, then automatic presence
                            } else if ($Necessity == '2') {
                                $PresenceStatus = 1;
                            }
                            //end leavepermit  
                        } else if ($Description == 'MRL') {
                            $PresenceStatus = 1;
                        } else if ($Description == 'PLW') {
                            $PresenceStatus = 1;
                        } else if ($Description == 'FML') {
                            $PresenceStatus = 1;
                        } else if ($Description == 'OT') {
                            $PresenceStatus = 1;
                        } else if ($Description == 'CIR') {
                            $PresenceStatus = 1;
                        } else if ($Description == 'CL') {
                            $PresenceStatus = 1;
                        } else if ($Description == 'MTL') {
                            $PresenceStatus = 1;
                        } else if ($Description == 'SP') {
                            $PresenceStatus = 0;
                        } else {
                            if ($WorkDay == 'SUN' OR $WorkDay == 'OFF' AND $Description !== 'ALD') {
                                $PresenceStatus = 1;
                                if ($Description == 'SP') {
                                    $PresenceStatus = 0;
                                }
                                if ($FlagResign) {
                                    if ($PresenceDate <= $ResignDate) {
                                        $PresenceStatus = 1;
                                        if ($Description == 'SP') {
                                            $PresenceStatus = 0;
                                        }
                                    } else {
                                        $PresenceStatus = 0;
                                    }
                                }
                            } else {
                                $PresenceStatus = 0;
                            }
                        }
                    } else {
                        // when actual IN/OUT is null                
                        if ($Description == 'LP') {
                            //when leavepermit status = persona then check condition workhour
                            if ($Necessity == '1') {
                                $PresenceStatus = 1;
                                if (abs(((strtotime($ManualOut) - strtotime($ActualIn)) / 3600)) < 4) {
                                    $PresenceStatus = 0;
                                } else if ($ActualIn == null and $ManualOut !== null) {
                                    $PresenceStatus = 0;
                                }
                                //when leavepermit status = office, then automatic presence
                            } else if ($Necessity == '2') {
                                $PresenceStatus = 1;
                            }
                        }
                        //end leavepermit
                        // when manual IN/OUT is not null
                        $PresenceStatus = 1;
                    }
                } else {

                    // when actual IN/OUT is null                
                    if ($Description == 'LP') {
                        //when leavepermit status = persona then check condition workhour
                        if ($Necessity == '1') {
                            $PresenceStatus = 1;
                            if (abs(((strtotime($ManualOut) - strtotime($ActualIn)) / 3600)) < 4) {
                                $PresenceStatus = 0;
                            } else if ($ActualIn == null and $ManualOut !== null) {
                                $PresenceStatus = 0;
                            }
                            //when leavepermit status = office, then automatic presence
                        } else if ($Necessity == '2') {
                            $PresenceStatus = 1;
                        }
                    }
                    //end leavepermit
                    // when actual IN/OUT is not null
                    $PresenceStatus = 1;
                    // when actual in/out below 4 hour is not count as presence
                    if (abs(((strtotime($ActualOut) - strtotime($ActualIn)) / 3600)) < 4) {

                        if ($WorkDay == 'SUN' OR $WorkDay == 'OFF' AND $Description !== 'ALD') {
                            $PresenceStatus = 1;

                            if ($Description == 'SP') {
                                $PresenceStatus = 0;
                            }

                            if ($FlagResign) {
                                if ($PresenceDate <= $ResignDate) {
                                    $PresenceStatus = 1;
                                    if ($Description == 'SP') {
                                        $PresenceStatus = 0;
                                    }
                                } else {
                                    $PresenceStatus = 0;
                                }
                            }
                        } else {

                            //when manual in and manual out not null and manual hour >= 4 hour then presence = 1	
                            if ($ManualIn !== null and $ManualOut !== null) {
                                $totalhour = (strtotime($ManualOut) - strtotime($ManualIn) / 3600 );
                                if ($totalhour >= 4) {
                                    $PresenceStatus = 1;
                                } else {
                                    $PresenceStatus = 0;
                                }
                            }
                        }

                        // when actual IN/OUT is null                
                        if ($Description == 'LP') {
                            //when leavepermit status = persona then check condition workhour
                            if ($Necessity == '1') {
                                $PresenceStatus = 1;
                                if (abs(((strtotime($ManualOut) - strtotime($ActualIn)) / 3600)) < 4) {
                                    $PresenceStatus = 0;
                                } else if ($ActualIn == null and $ManualOut !== null) {
                                    $PresenceStatus = 0;
                                }
                                //when leavepermit status = office, then automatic presence
                            } else if ($Necessity == '2') {
                                $PresenceStatus = 1;
                            }//end leavepermit
                        }else if($Description == 'PLW'){
                                 $PresenceStatus = 1;
                        }
                        
                    }
                }



                // Start Incentive Shift calculation
                if ((is_null($ActualIn)) and is_null($ManualIn)) {
                    $IncentiveShift = 0;
                } else {
                    if (is_null($ActualIn)) {
                        $shift_in = strtotime($ManualIn);
                    } else {
                        $shift_in = strtotime($ActualIn);
                    }
                    if ($WorkDay == 'N3') {
                        $shift = strtotime($PresenceDate . " 12:00:00");
                        if ($shift_in > $shift) {
                            if($group !=='OS'){
                                $IncentiveShift = 10000;
                            }else{
                                $IncentiveShift = 0;
                            }
                        } else {
                            $IncentiveShift = 0;
                        }
                    } else {
                        $shift2 = strtotime($PresenceDate . " 15:00:00");
                        $shift3 = strtotime($PresenceDate . " 01:00:00");
                        $IncentiveShift = 0;
                        if ($shift_in > $shift2) {
                            //request sally 			                       
                            if ($PresenceDate == '2013-12-03' OR
                                    $PresenceDate == '2013-12-08') {
                                $IncentiveShift = 0;
                            } else {
                                 if($group !=='OS'){
                               		 $IncentiveShift = 10000;
		                    }else{
		                         $IncentiveShift = 0;
		                    }
                            }
                            // $IncentiveShift = 10000;                        
                        }
                        if ($shift_in < $shift3) {
                            //request sally 			  	                      
                            if ($PresenceDate == '2013-12-03' OR
                                    $PresenceDate == '2013-12-08') {
                                $IncentiveShift = 0;
                            } else {
                                 if($group !=='OS'){
                                        $IncentiveShift = 10000;
		                    }else{
		                        $IncentiveShift = 0;
		                    }
                            }
                            //$IncentiveShift = 10000;
                        }
                    }
                }
                // End Incentive Shift calculation


                if ($PresenceStatus == 0) {
                    $DailySalaryPayment = -1 * ($MonthlySalary / 30);
                } else {
                    $DailySalaryPayment = 0;
                }


                $DataDaily = $DailySalary * $PresenceStatus;
                $CountSalary+=$DataDaily;

                if ($PresenceDate == $posting) {
                    $InsurancePayment = $Insurance;
                    $MontlyPayment = $MonthlySalary;
                } else {
                    $InsurancePayment = 0;
                    $MontlyPayment = 0;
                }

                // update insert daily salary                
                $record = array(
                    'PostingDate' => $posting,
                    'IDEmployee' => $IDEmployee,
                    'PresenceDate' => $PresenceDate,
                    'PresenceStatus' => $PresenceStatus,
                    'DailySalaryPayment' => $DailySalaryPayment,
                    'MontlyPayment' => $MontlyPayment,
                    'DailyIncentiveShift' => $IncentiveShift,
                    'InsurancePayment' => $InsurancePayment,
                    'AddedBy' => $this->User,
                    'AddedDate' => $this->Datetime,
                    'AddedIP' => $this->Ip
                );

                //this function if check result exist then update else insert to table dailysalary 
                $resultdata = $this->monthlyprocess->check_dailysalary($posting, $IDEmployee, $PresenceDate);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_dailysalary($posting, $IDEmployee, $PresenceDate, $record);
                } else {
                    $this->monthlyprocess->insert_dailysalary($record);
                }
            }
        }
    }

    function sum_dailysalary($posting = '', $nip = '') {
        $result = $this->monthlyprocess->sum_salary($posting, $nip);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {

            foreach ($result as $row) {
                $PostingDate = $row['PostingDate'];
                $IDEmployee = $row['IDEmployee'];

                $Absence = $row['AbsenPayment'];
                $MontlyPayment = $row['MontlySalary'];
                // $SumSalaryPayment    = $row['SumSalaryPayment'];
                $SumIncentiveShift = $row['SumIncentiveShift'];
                $SumInsurancePayment = $row['SumInsurancePayment'];

                $record = array(
                    'PostingDate' => $PostingDate,
                    'IDEmployee' => $IDEmployee,
                    'SumDailySalaryPayment' => $MontlyPayment,
                    'SumDailyIncentiveShift' => $SumIncentiveShift,
                    'InsurancePayment' => $SumInsurancePayment,
                    'AbsencePayment' => $Absence
                );
                //this function if check result exist then update else insert to table payslip
                $resultdata = $this->monthlyprocess->check_payslip($PostingDate, $IDEmployee);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_payslip($posting, $IDEmployee, $record);
                } else {
                    $this->monthlyprocess->insert_payslip($record);
                }
            }
        }
    }

    function dailyovertime($posting = '', $from = '', $until = '', $nip = '') {
        $result = $this->monthlyprocess->getdata_overtime($from, $until, $nip);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';


        if ($check == 'exist') {

            $counter = 0;
            foreach ($result as $row) {
                $counter++;
                $IDEmployee = $row['IDEmployee'];
                $IDJobGroup = $row['IDJobGroup'];
                $PresenceDate = $row['PresenceDate'];
                $OvertimeIn = $row['OvertimeIn'];
                $OvertimeOut = $row['OvertimeOut'];
                $WorkDay = $row['WorkDay'];
                $DescDay = $row['Description'];
                $OvertimePerHour = $row['OvertimePerHour'];



                $presence = $this->libfun->presence_status($row['ActualIn'], $row['ActualOut'], $row['ManualIn'], $row['ManualOut']);
                $in = $presence['IN'];
                $out = $presence['OUT'];

                if (is_null($in) OR is_null($out)) {
                    // cek di atl/dinas luar nya, ada ? ngga ada = 0 (untuk yg keluar kantor)
                    $PaymentStatus = 0;
                } else {
                    $PaymentStatus = 1;
                }

                $OvertimeHour = $this->libfun->subs_time($OvertimeIn, $OvertimeOut, 30);

                /* di matikan berdasarkan catatan bu doris per tanggal 01-04-2014 */

                //if ($OvertimeHour >=8 ) $OvertimeHour = $OvertimeHour - 1; /* di remark tanggal 11 november 2013 */
                //request sally tanggal 11 november 2013		
                // if ($OvertimeHour >= 5)
                ///     $OvertimeHour = $OvertimeHour - 1;

                /* di matikan berdasarkan catatan bu doris per tanggal 01-04-2014 */


                if ($WorkDay == 'SUN' OR $WorkDay == 'OFF' AND $DescDay != 'ALD') {
                    // For WorkDay is sunday or offday dan bukan cuti bersama

                    /* di hidupkan di hari libur atau minggu berdasarkan catatan bu doris per tanggal 01-04-2014 */
                    if ($OvertimeHour >= 8)
                        $OvertimeHour = $OvertimeHour - 1;

                    if ($IDJobGroup == 'LT') {
                        // Group 'LT' for sunday get life index
                        $OvertimeTotalHour = $this->libfun->overtime_on_offday($OvertimeHour, 1);
                    } else {
                        if ($WorkDay == 'SUN') {
                            $OvertimeTotalHour = $this->libfun->overtime_on_workday($OvertimeHour, 2);
                        } else {
                            $OvertimeTotalHour = $this->libfun->overtime_on_offday($OvertimeHour, 2);
                        }
                    }
                } else {
                    // For WorkDay is normal day
                    if ($IDJobGroup == 'LT') {
                        $OvertimeTotalHour = $this->libfun->overtime_on_workday($OvertimeHour, 1);
                    } else {
                        $OvertimeTotalHour = $this->libfun->overtime_on_workday($OvertimeHour, 2);
                    }
                }

                $OvertimePayment = $OvertimePerHour * $OvertimeTotalHour;

                $record = array(
                    'PostingDate' => $posting,
                    'IDEmployee' => $IDEmployee,
                    'PresenceDate' => $PresenceDate,
                    'OvertimeIn' => $OvertimeIn,
                    'OvertimeOut' => $OvertimeOut,
                    'OvertimeHour' => $OvertimeHour,
                    'OvertimeTotalHour' => $OvertimeTotalHour,
                    'OvertimePerHour' => $OvertimePerHour,
                    'PaymentStatus' => $PaymentStatus,
                    'DailyOvertimePayment' => $OvertimePayment,
                    'AddedBy' => $this->User,
                    'AddedDate' => $this->Datetime,
                    'AddedIP' => $this->Ip
                );


                //this function if check result exist then update else insert to table dailyovertime
                $resultdata = $this->monthlyprocess->check_dailyovertime($posting, $IDEmployee, $PresenceDate, $OvertimeIn);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_dailyovertime($posting, $IDEmployee, $PresenceDate, $OvertimeIn, $record);
                } else {
                    $this->monthlyprocess->insert_dailyovertime($record);
                }
            }
        }
    }

    function sum_dailyovertime($posting = '', $nip = '') {
        $result = $this->monthlyprocess->sum_overtime($posting, $nip);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {
            foreach ($result as $row) {
                $PostingDate = $row['PostingDate'];
                $IDEmployee = $row['IDEmployee'];
                $SumOvertimePayment = $row['SumOvertimePayment'];

                $record = array(
                    'PostingDate' => $PostingDate,
                    'IDEmployee' => $IDEmployee,
                    'SumDailyOvertimePayment' => $SumOvertimePayment
                );

                //this function if check result exist then update else insert to table payslip

                $resultdata = $this->monthlyprocess->check_payslip($PostingDate, $IDEmployee);
                $checkdata = ($resultdata == '' or $resultdata == null) ? 'empty' : 'exist';

                if ($checkdata == 'exist') {
                    $this->monthlyprocess->update_payslip($posting, $IDEmployee, $record);
                } else {
                    $this->monthlyprocess->insert_payslip($record);
                }
            }
        }
    }

    function recalculate($posting) {
        $result = $this->monthlyprocess->getdata_payslip($posting);
        $check = ($result == null or $result == '') ? 'empty' : 'exist';
        if ($check == 'exist') {
            foreach ($result as $row) {
                $PostingDate = $row['PostingDate'];
                $IDEmployee = $row['IDEmployee'];

                $absen = $row['AbsencePayment'];
                $absenfix = $absen * -1;

                $SumDailySalaryPayment = $row['SumDailySalaryPayment'];
                $SumDailyIncentiveShift = $row['SumDailyIncentiveShift'];
                $SumDailyOvertimePayment = $row['SumDailyOvertimePayment'];
                $OtherIncome = $row['OtherIncome'];
                $InsurancePayment = $row['InsurancePayment'];
                $LoanPayment = $row['LoanPayment'];
                $OutstandingPayment = $row['OutstandingPayment'];
                $OtherPayment = $row['OtherPayment'];
                $TotalIncome = $SumDailySalaryPayment +
                        $SumDailyIncentiveShift +
                        $SumDailyOvertimePayment +
                        $OtherIncome;
                $TotalDeduction =
                        $absenfix +
                        $InsurancePayment +
                        $LoanPayment +
                        $OutstandingPayment +
                        $OtherPayment;
                $TakeHomePay = $TotalIncome - $TotalDeduction;

                $record = array(
                    'PostingDate' => $PostingDate,
                    'IDEmployee' => $IDEmployee,
                    'TotalIncome' => $TotalIncome,
                    'TotalDeduction' => $TotalDeduction,
                    'TakeHomePay' => $TakeHomePay,
                    'AddedBy' => $this->User,
                    'AddedDate' => $this->Datetime,
                    'AddedIP' => $this->Ip
                );
                $this->monthlyprocess->update_payslip($PostingDate, $IDEmployee, $record);
            }
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */



