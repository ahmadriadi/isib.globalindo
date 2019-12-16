<html>
    <head>
    </head>

    <body bgcolor="#FFFFFF">
        <div id="print_area" >
            <style type="text/css">
                /*"Trebuchet MS","New Century Schoolbook"*/
                body {font-size: 11px; }
                table{border-collapse:collapse;padding: 2px}
                tabel,td,th{border:1px solid black;font-size: 10px;padding: 2px}
                tabel.top,td.top,th.top{border:0px transparent;font-size: 10px;padding: 2px}
                thead {border:0px no-solid;color:green;padding: 2px}
                tbody {color:blue;height:10px;padding: 2px}
                .subfoot{color:green;padding: 2px; font-weight: bold;}
                .tablehead{border: none;}
                tfoot {color:red;padding: 2px}
            </style>
            <br/>
            <?php
            for ($i = 0; $i < count($resultdata); $i++) {
                //IDJobGroup
                if ($resultdata[$i]['IDJobGroup'] == 'ST') {
                    $NMJobGroup = "STAFF";
                } else {
                    if ($resultdata[$i]['IDJobGroup'] == 'LT') {
                        $NMJobGroup = "LAPANGAN TETAP";
                    } else {
                        if ($resultdata[$i]['IDJobGroup'] == 'LK') {
                            $NMJobGroup = "LAPANGAN KONTRAK";
                        } else {
                            if ($resultdata[$i]['IDJobGroup'] == 'HL') {
                                $NMJobGroup = "HARIAN LEPAS";
                            } else {
                                $NMJobGroup = "LAIN-LAIN";
                            }
                        }
                    }
                }
            }
            ?>
            <table style="page-break-after:always; ">
                <thead>
                <strong>PT TRIAS INDRA SAPUTRA</strong><br>
                LATE ARRIVAL REPORT
                <tr>
                    <th colspan="42" align="left">
                <table border="0" width="100%" class="header">
                    <tr>
                        <td class="top" width="500px">PERIOD</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)); ?></td>
                    </tr>
                    <tr>
                        <td class="top" width="500px">GROUP</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo $NMJobGroup; ?></td>
                    </tr>
                </table>
                </th>
                </tr>
                <tr>
                    <th width="10px" >ID</th>
                    <th width="50px" >ID EMPLOYEE</th>
                    <th width="200px">FULLNAME</th>
                    <th width="100px" >DATE</th>
                    <th width="40px" >DAY OF WEEK</th>
                    <th width="40px" >ACTUAL IN</th>
                    <th width="40px" >LATE (HOUR)</th>           
                   <!-- <th width="50px">LATE HOUR SUM</th>-->
                    <th width="50px">DEDUCT (HOUR)</th>
                    <!--  <th width="50px">DEDUCT HOUR SUM</th> -->
                </tr>
                </thead>
                <!-- Dinamis -->

                <?php

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
                    return lz($hours) . ":" . lz($minutes);
                }

                function lz($num) {
                    return (strlen($num) < 2) ? "0{$num}" : $num;
                }

                function cetak($n, $data) {
                    
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td align=\"center\">" . $n . "</td>";
                    $html.= "    <td align=\"center\">" . $data['IDEmployee'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['FullName'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['PresenceDate'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['DayOfWeek'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['ActualIn'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['LateHour'] . "</td>";
                    //$html.= "    <td align=\"center\">" . $data['LateHour_Sum'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['DeducHour'] . ' Hour' . "</td>";
                    //$html.= "    <td align=\"center\">" . $data['DeducHourSum'] . ' Hour' . "</td>";
                    $html.= "</tr>";
                    echo $html;
                }
                
                
                
                function cetak_summary($sumlatehour,$sumhour){
                    $html = "<tr width='100%' class='subfoot'>";
                    $html.= "   <td align='right' colspan='5'>TOTAL</td>";
                    $html.= "   <td align='center'>&nbsp</td>";
                    $html.= "   <td align='center'>".$sumlatehour."</td>";
                    $html.= "   <td align='center'>".$sumhour."</td>";
                    $html.= "</tr>";
                    echo $html;
                }

                $n = $m = 0;
                $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
                $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                $last_idemployee = '';
                $TotLH = $DeducSum = 0;
                if ($resultdata != NULL) {
                  
                    foreach ($resultdata as $row) {

                        $nip = $row['IDEmployee'];
                        $presencedate = $row['PresenceDate'];
                        $idloc = $row['IDLocation'];
                        $act = ($row['ActualIn'] == '' or $row['ActualIn'] == NULL) ? 'empty' : 'exist';
                        $man = ($row['ManualIn'] == '' or $row['ManualIn'] == NULL) ? 'empty' : 'exist';

                        if ($act == 'exist' and $man == 'empty') {
                            $actualin = date('Hi', strtotime($row['ActualIn']));
                            $prindata = 'yes';
                        } else if ($act == 'exist' and $man == 'exist') {
                            $actualin = date('Hi', strtotime($row['ManualIn']));
                            $prindata = 'no';
                        } else if ($act == 'empty' and $man == 'exist') {
                            $actualin = date('Hi', strtotime($row['ManualIn']));
                            $prindata = 'no';
                        } else if ($act == 'empty' and $man == 'empty') {
                            $actualin = date('Hi', strtotime('00:00'));
                            $prindata = 'no';
                        }
                        
                        
                        $rowplate = $this->report->get_paramlate($presencedate,$idloc);
                        $flag = ($rowplate=='' or $rowplate==null)? 'empty' : 'exist';
                        $workday = ($rowplate=='' or $rowplate==null)? $row['WorkDay'] : $rowplate->StartTimeLate;
                        
                        
                        $workhour = $row['WorkHour'];
                        $lhour = $row['LateHour'];
                        $dailysalary = $row['DailySalary'];
                        $timelp = date('Hi', strtotime($row['IMKOut']));
                        $lp = ($row['IMKOut'] == '' or $row['IMKOut'] == NULL) ? 'empty' : 'exist';


                        $t_in = date('H:i:s', strtotime($row['ActualIn']));
			$workinshift1 = date('H:i:s', strtotime("08:00:00"));
                        $workinshift2 = date('H:i:s', strtotime("16:00:00"));
                        $workinshift2_2 = date('H:i:s', strtotime("16:30:00"));
                        $workinshift2_3 = date('H:i:s', strtotime("20:00:00"));
                        $workinshift2_4 = date('H:i:s', strtotime("13:00:00"));
                        $workinshift3 = date('H:i:s', strtotime("00:00:00"));
			

			if($workday =='N3'){
		            $range1 ='1200';
		        }else{
                            $range1 ='1300';
                        }

                        if (($actualin >= '0400' AND $actualin <= $range1)) {
                            if ((strtotime($t_in) - strtotime($workinshift1)) / 3600 > 0) {
			        $latehour = (strtotime($t_in) - strtotime($workinshift1)) / 3600;
			    } else {
			        $latehour = 0.00;
			    }
                            
			    $shift = '1';
			    $lateh = $latehour;
                            
                            $test ='1';
                            //$lateh = $lhour;
                        } else if ($actualin >= '1300' AND $actualin <= '1800') {

                            if ($workday == 'N2') {
                                $workin = $workinshift2_2;
                            } else if ($workday == 'N3') {
                                $workin = $workinshift2_4;
                            } else {
                                $workin = $workinshift2;
                            }

                            if ((strtotime($t_in) - strtotime($workin)) / 3600 > 0) {
                                $latehour = (strtotime($t_in) - strtotime($workin)) / 3600;
                            } else {
                                $latehour = 0.00;
                            }

                            $shift = '2';
                            $lateh = $latehour;
                            
                            $test ='2';
                        } else if ($actualin >= '1800' AND $actualin <= '2100') {
                            if ((strtotime($t_in) - strtotime($workinshift2_3)) / 3600 > 0) {
                                $latehour = (strtotime($t_in) - strtotime($workinshift2_3)) / 3600;
                            } else {
                                $latehour = 0.00;
                            }

                            $shift = '2-1';
                            $lateh = $latehour;
                            
                            $test ='3';
                        }/* else if ($actualin >= '0000' AND $actualin <= '0200') {
                            if ((strtotime($t_in) - strtotime($workinshift3)) / 3600 > 0) {
                                $latehour = (strtotime($t_in) - strtotime($workinshift3)) / 3600;
                            } else {
                                $latehour = 0.00;
                            }

                            $shift = '3';
                            $lateh = $latehour;
                        } */
			
			$tmplt = $lateh+0.01;	
                        $late = decimaltominutes($tmplt);
                        
                        $tmplate = explode(':', $late);
                        $timelate = $tmplate[0] . $tmplate[1];
                        
                        
                        if (floor($workhour) == '5') {
                            $timehour = '7';
                        } else {
                            $timehour = '7';
                        }

                        $hoursalary = (($dailysalary) / ($timehour));
                        
                        if ($prindata == 'yes') {
                                
                           


                        if ($late !== '00:00') {
                            // echo $presencedate.' '.$actualin.'-'.$flag.'-'.$range1.'-'.$test.'<br/>';
                             
                                
                                if ($workhour == 7.00) {
                                    if ($timelate >= '0001' and $timelate <= '0010') {
                                        $deduclate = $hoursalary;
                                        $deducstatus = '1';
                                    } else if ($timelate >= '0011' and $timelate <= '0020') {
                                        $deduclate = $hoursalary * 2;
                                        $deducstatus = '2';
                                    } else if ($timelate >= '0021' and $timelate <= '0030') {
                                        $deduclate = $hoursalary * 3;
                                        $deducstatus = '3';
                                    } else if ($timelate >= '0031' and $timelate <= '0040') {
                                        $deduclate = $hoursalary * 4;
                                        $deducstatus = '4';
                                    } else if ($timelate >= '0041' and $timelate <= '0050') {
                                        $deduclate = $hoursalary * 5;
                                        $deducstatus = '5';
                                    } else if ($timelate >= '0051' and $timelate <= '0059') {
                                        $deduclate = $hoursalary * 6;
                                        $deducstatus = '6';
                                    } else if ($timelate >= '0100' and $timelate <= '0110') {
                                        $deduclate = $hoursalary * 7;
                                        $deducstatus = '7';
                                    } else if ($timelate >= '0111') {
                                        $deduclate = $hoursalary * 7;
                                        $deducstatus = '7';
                                    }

                                    $status = '7';
                                } else if ($workhour == 5.00) {
                                    if ($timelate >= '0001' and $timelate <= '0010') {
                                        $deduclate = $hoursalary;
                                        $deducstatus = '1';
                                    } else if ($timelate >= '0011' and $timelate <= '0020') {
                                        $deduclate = $hoursalary * 2;
                                        $deducstatus = '2';
                                    } else if ($timelate >= '0021' and $timelate <= '0030') {
                                        $deduclate = $hoursalary * 3;
                                        $deducstatus = '3';
                                    } else if ($timelate >= '0031' and $timelate <= '0040') {
                                        $deduclate = $hoursalary * 4;
                                        $deducstatus = '4';
                                    } else if ($timelate >= '0041' and $timelate <= '0050') {
                                        $deduclate = $hoursalary * 5;
                                        $deducstatus = '5';
                                    } else if ($timelate >= '0051' and $timelate <= '0059') {
                                        $deduclate = $hoursalary * 6;
                                        $deducstatus = '6';
                                    } else if ($timelate >= '0100' and $timelate <= '0110') {
                                        $deduclate = $hoursalary * 7;
                                        $deducstatus = '7';
                                    } else if ($timelate >= '0111') {
                                        $deduclate = $hoursalary * 7;
                                        $deducstatus = '7';
                                    }

                                    $status = ' 5 ';
                                }

                                if($row['IDEmployee'] !=$last_idemployee and $row['Description'] !=='LP' and $n>0){
                                    cetak_summary($sumlate, $DeducSum);
                                    $TotLH =$DeducSum=0;
                                    
                                  
                                }
                                                                 
                               if($row['Description'] =='P' or ['Description'] =='LP' or ['Description'] =='A' ){
                                  $TotLH+=$lateh;                                 
                                  $sumlate = decimaltominutes($TotLH);
                                  $DeducSum+= $deducstatus; 
                                  
                              // }
                             
//                                 
//                               if($nip=='0557250313' and $row['Description'] =='P'){
//                                      echo $presencedate.'----------'.$lateh.'<br/>';
//                               }
                                  
                                $data['IDEmployee'] = $nip;
                                $data['FullName'] = $row['FullName'];
                                $data['PresenceDate'] = date('d-m-Y', strtotime($presencedate));
                                $data['DayOfWeek'] = $day[date('w', strtotime($presencedate))];
                                $data['ActualIn'] = date('H:i', strtotime($actualin));
                                $data['LateHour_Sum'] = $sumlate;
                                $data['LateHour'] = date('H:i', strtotime($timelate));
                                $data['DeducHour'] = $deducstatus;
                                $data['DeducHourSum'] = $DeducSum;
                                $last_idemployee = $row['IDEmployee'];
                             

                                if (($row['Position'] == 'DIRECTOR') OR ($row['Position'] == 'ASSISTANT DIRECTOR') OR ($row['Position'] == 'MANAGER')) {
                                    //$print ='no';
                                } else {
                                    if ($lp == 'empty' and $shift !== '2-1' and $flag=='empty') {
                                        $n++;
                                        cetak($n, $data);
                                    } else {
                                        /*
                                          if (($shift !== '2-1') and ($timelp >= '1000' and $timelp <= '1600')) {
                                          cetak($n, $data);
                                          } else if (($shift !== '2-1') and ($timelp >= '1900' and $timelp <= '2359')) {
                                          cetak($n, $data);
                                          } else if (($shift !== '2-1') and ($timelp >= '0200' and $timelp <= '0600')) {
                                          cetak($n, $data);
                                          }
                                         * 
                                         */

					
					 error_reporting(0);
                                         $resultlp = $this->report->get_leavepermit($row['IDEmployee'],$row['PresenceDate']);
                                         foreach ($resultlp as $rowlp) {
					 $imkkeluar =  date('Hi',  strtotime($rowlp['OutDate'])); 
					}	

                                        if (($shift !== '2-1') and ($timelp <= '0800' or $imkkeluar=='0800')) {
                                            
                                        } else if($shift !== '2-1' and $flag=='empty') {
                                            $n++;
                                            cetak($n, $data);
                                           
                                            
                                        }
                                    }
                                }
                               
                                }
                            }
                        }
                    }
                }
                ?>
                </tbody>

                <tfoot>                
                    <tr>
                        <td align="center">(1)</td>
                        <td align="center">(2)</td>
                        <td align="center">(3)</td>
                        <td align="center">(4)</td>
                        <td align="center">(5)</td>
                        <td align="center">(6)</td>
                        <td align="center">(7)</td>
                        <td align="center">(8)</td>
                         <!--<td align="center">(9)</td> -->
                        <!-- <td align="center">(10)</td> -->
                    </tr>
                    <tr>
                        <td colspan="42">
                            <table width="100%">
                                <tr>
                                    <td colspan="2" align="left" style="font-size:xx-small"><b>Note:</b></td>
                                </tr>
                                <tr>                    
                                    <td colspan="2" align="left" style="font-size:xx-small">&nbsp;</td>
                                </tr>
                                <tr>                    
                                    <td colspan="2" align="left" style="font-size:xx-small">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tfoot>
            </table>

        </div>

        <button type="button" onclick="processXPrint()" id="btn_process" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Print</button>
        <button type="button" onclick="exporttoexcel()" id="btn_process" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Excel</button>

        <!--<a href="#" onclick="processXPrint()" title="Click here to print.">Print</a> -->
        <script language="javascript">
            var gAutoPrint = true;
            function processXPrint() {
                if (document.getElementById != null) {
                    var html = '<HTML>\n<HEAD>\n';
                    if (document.getElementsByTagName != null) {
                        var headTags = document.getElementsByTagName("head");
                        if (headTags.length > 0)
                            html += headTags[0].innerHTML;
                    }

                    html += '\n</HE' + 'AD>\n<BODY>\n';
                    var printReadyElem = document.getElementById("print_area");
                    if (printReadyElem != null)
                        html += printReadyElem.innerHTML;
                    else {
                        alert("Error, no contents.");
                        return;
                    }

                    html += '\n</BO' + 'DY>\n</HT' + 'ML>';
                    var printWin = window.open("", "processPrint");
                    printWin.document.open();
                    printWin.document.write(html);
                    printWin.document.close();
                    if (gAutoPrint)
                        printWin.print();
                } else
                    alert("Browser not supported.");
            }


            function exporttoexcel() {
                window.location.href = '<?php echo $url_excel; ?>';
            }

        </script>

    </body>

</html>




