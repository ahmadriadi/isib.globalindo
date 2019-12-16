<!DOCTYPE HTML>
<html>
    <head>
        <noscript>Your browser does not support JavaScript! or has disabled...</noscript>
        <style>
            body {
                font-size: 70%;
                font-family: "Lucida Sans Unicode","Trebuchet MS", "Arial", "Helvetica", "Verdana", "sans-serif";
            }

            table {
                font-size: 1em;
            }

            .yellow{
                background-color:#d5c368;
                font-weight: bold;
            }

        </style>
    </head>
    <body bgcolor="#FFFFFF">
        <div id="print_area" >
            <table width="100%" border="0" cellpadding="4" cellspacing="2" style="border-collapse:collapse;">
                <THEAD>

                    <TR> 
                        <TH align="left">PT TRIAS INDRA SAPUTRA</TH> 
                    </TR>
                    <TR>                        
                        <TH align="left">LATE ARRIVAL REPORT </TH> 
                    </TR>  
                    <TR> 
                        <TH align="left">PERIODE :<?php echo date('d-m-Y', strtotime($fromdate)) . ' s/d ' . date('d-m-Y', strtotime($untildate)); ?> </TH> 
                    </TR>
                </THEAD>
            </table>    

            <table width="100%" border="1" cellpadding="4" cellspacing="2" style="border-collapse:collapse;">
                <thead>
                    <tr> 
                        <th>NO</th>
                        <th>IDEmployee</th>       
                        <th>FullName</th>       
                        <th>Date</th>       
                        <th>Day of Week</th>       
                        <th>Actual In</th>
                        <th>Late (Hour)</th>
                        <th>Deduct (Hour)</th>                       
                    </tr>
                </thead>
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
                    </tr>
                    </tfoot>
                <tbody>
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

                    //error_reporting(0);
                    $counter = 0;
                    $lastid = "";
                    $lastnip = "";
                    $lastdate = "";
                    $lastname = "";
                    $no =  0;
                    $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                    $TotLH = $DeducSum = 0;
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


                        $rowplate = $this->report->get_paramlate($presencedate, $idloc);
                        $flag = ($rowplate == '' or $rowplate == null) ? 'empty' : 'exist';
                        $workday = ($rowplate == '' or $rowplate == null) ? $row['WorkDay'] : $rowplate->StartTimeLate;
                        
                        
                        
                        

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

                        
                        

                        
                        if ($workday == 'N3') {
                            $range1 = '1200';
                        } else {
                            $range1 = '1300';
                        }

                        if (($actualin >= '0400' AND $actualin <= $range1)) {
                            if ((strtotime($t_in) - strtotime($workinshift1)) / 3600 > 0) {
                                $latehour = (strtotime($t_in) - strtotime($workinshift1)) / 3600;
                            } else {
                                $latehour = 0.00;
                            }

                            $shift = '1';
                            $lateh = $latehour;

                            $test = '1';
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

                            $test = '2';
                        } else if ($actualin >= '1800' AND $actualin <= '2100') {
                            if ((strtotime($t_in) - strtotime($workinshift2_3)) / 3600 > 0) {
                                $latehour = (strtotime($t_in) - strtotime($workinshift2_3)) / 3600;
                            } else {
                                $latehour = 0.00;
                            }

                            $shift = '2-1';
                            $lateh = $latehour;

                            $test = '3';
                        }/* else if ($actualin >= '0000' AND $actualin <= '0200') {
                          if ((strtotime($t_in) - strtotime($workinshift3)) / 3600 > 0) {
                          $latehour = (strtotime($t_in) - strtotime($workinshift3)) / 3600;
                          } else {
                          $latehour = 0.00;
                          }

                          $shift = '3';
                          $lateh = $latehour;
                          } */

                        $tmplt = $lateh + 0.01;
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
                                $counter++;


                                if ($row['IDEmployee'] != $lastid and $no > 1 and $sumdeduchour !== '-') {

                                    echo "<tr class='yellow'>";
                                    echo"<td colspan ='6' align='right'>" . 'SUM PRESENCE LATE : ' . $lastname . "</td>";
                                    echo"<td>" . decimaltominutes($sumhour + 0.01) . "</td>";
                                    echo"<td>" . $sumdeduchour . " Hour </td>";
                                    echo "</tr>";
                                    $sumdeduchour = $sumhour = '-';
                                }


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



                                
                                if ($lp == 'empty' and $shift !== '2-1' and $flag == 'empty') {
                                    $statusdata = 'cetak';
                                    
                                    
                                } else {
                                    
                              
                                    
                                    
                                    error_reporting(0);
                                    $resultlp = $this->report->get_leavepermit($row['IDEmployee'], $row['PresenceDate']);
                                    foreach ($resultlp as $rowlp) {
                                        $imkkeluar = date('Hi', strtotime($rowlp['OutDate']));
                                        
                                    }
                                    if (($shift !== '2-1') and ($timelp <= '0800' and $imkkeluar == '0800')) {
                                        $statusdata = 'jangan';
                                        
                                       // echo $flag.'-'.$row['FullName'].'-'.$presencedate.'Time Leave Permit :'.$timelp.'<br/>';
                                        
                                        
                                        
                                    } else if ($shift !== '2-1' and $flag == 'empty') {
                                        $statusdata = 'cetak';
                                        
                                       
                                        
                                    }
                                }

                                if ($statusdata == 'cetak') {
                                    $no++;
                                    echo "<tr>";
                                    echo"<td>" . $no . "</td>";
                                    echo"<td>" . $nip . "</td>";
                                    echo"<td>" . $row['FullName'] . "</td>";
                                    echo "<td>" . date('d-m-Y', strtotime($presencedate)) . "</td>";
                                    echo "<td>" . $day[date('w', strtotime($presencedate))] . "</td>";
                                    echo "<td>" . date('H:i', strtotime($actualin)) . "</td>";
                                    echo "<td>" . date('H:i', strtotime($timelate)) . "</td>";
                                    echo "<td>" . $deducstatus . " Hour </td>";
                                    echo "</tr>";

                                    $sumhour +=$lateh;
                                    $sumdeduchour +=$deducstatus;
                                    $lastid = $row['IDEmployee'];
                                    $lastname = $row['FullName'];
                                }
                            }
                        }
                    }
                    ?>
                    
                    
            <tr class="yellow">
                <td align="right" colspan="6">SUM PRESENCE LATE : <?php echo $lastname; ?></td>
                <td align="left"><?php echo decimaltominutes($sumhour); ?></td>
                <td align="left"><?php echo $sumdeduchour.' Hour' ; ?></td>
            </tr>
                </tbody>
            </table>        
        </div>

        <button type="button" onclick="processXPrint()" id="btn_rprint" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Print</button>
        <button type="button" onclick="exporttoexcel()" id="btn_rexcel" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i>Excel</button>

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



