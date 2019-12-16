<html>
    <head>
    </head>
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
         .black{color:black;padding: 2px; font-weight: bold;}
         .red{ background-color:#E9967A;}
    </style>       



    <body bgcolor="#FFFFFF">
        <div id="print_area" >
            <table style="page-break-after:always; ">
                <thead>
                <strong>PT TRIAS INDRA SAPUTRA</strong><br>
                DETAIL PRESENCE REPORT
                <tr>
                    <th colspan="42" align="left">
                <table border="0" width="100%" class="header">
                    <tr>
                        <td class="top" width="500px">PERIOD</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo date('d-m-Y', strtotime($from)) . ' to ' . date('d-m-Y', strtotime($until)); ?></td>
                    </tr>

                    <tr>
                        <td class="top" width="500px">NIP</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo $nip; ?></td>
                    </tr>
                    <tr>
                        <td class="top" width="500px">NAME</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo $name; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="top" width="500px">JOB GROUP</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo $group; ?>
                        </td>
                    </tr>
                </table>
                </th>
                </tr>
                <tr>
                    <th width="10px" rowspan="2" >ID</th>
                    <th width="50px" rowspan="2" >DATE</th>
                    <th width="50px" rowspan="2" >DAY OF WEEK</th>
                    <th colspan="2" >ACTUAL</th>
                    <th colspan="2" >MANUAL</th>
                    <th colspan="2" >IMK</th>
                    <th width="40px" rowspan="2" >MAN HOUR</th>                    
                    <th width="40px" rowspan="2" >LATE HOUR</th>                     
                    <th colspan="11" >TYPE PRESENCE</th>
                    <th rowspan="2"  >NOTE</th>
                </tr>
                <tr width="100%">
                    <th align="center">TIME IN</th>
                    <th align="center">TIME OUT</th>
                    <th align="center">TIME IN</th>
                    <th align="center">TIME OUT</th>
                    <th align="center">TIME OUT </th>
                    <th align="center">TIME IN</th>
                    <th align="center" width="20px">P</th>
                    <th align="center" width="20px">PLW</th>
                    <th align="center" width="20px">A</th>
                    <th align="center" width="20px">SP</th>	
                    <th align="center" width="20px">SN</th>
                    <th align="center" width="20px">L</th>
                    <th align="center" width="20px">LP</th>
                    <th align="center" width="20px">OT</th>
                    <th align="center" width="20px">NC</th>
                    <th align="center" width="20px">ALD</th>
                    <th align="center" width="20px">- 4</th>
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

                function cetak($unit,$n, $data) {
                    if($unit=='SECURITY' and $data['A']=='1' or $data['MinusHour']=='1'){
                        $html = "<tr class ='red black' width=\"100%\">";  
                        $html.= "    <td align=\"center\">" . $n . "</td>";
                        $html.= "    <td align=\"center\">" . $data['PresenceDate'] . "</td>";
                        $html.= "    <td align=\"left\">" . $data['DayOfWeek'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['ActualIn'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['ActualOut'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['ManualIn'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['ManualOut'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['IMKOut'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['IMKIn'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['ActualHour'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['LateHour'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['Presence'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['PLW'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['A'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['SP'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['SN'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['L'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['LeavePermit'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['OT'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['NC'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['ALD'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['MinusHour'] . "</td>";
                        $html.= "    <td align=\"left\">" . $data['Note'] . "</td>";
                        $html.= "</tr>";
                    }else{
                        $html = "<tr width=\"100%\">";
                        $html.= "    <td align=\"center\">" . $n . "</td>";
                        $html.= "    <td align=\"center\">" . $data['PresenceDate'] . "</td>";
                        $html.= "    <td align=\"left\">" . $data['DayOfWeek'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['ActualIn'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['ActualOut'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['ManualIn'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['ManualOut'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['IMKOut'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['IMKIn'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['ActualHour'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['LateHour'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['Presence'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['PLW'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['A'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['SP'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['SN'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['L'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['LeavePermit'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['OT'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['NC'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['ALD'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['MinusHour'] . "</td>";
                        $html.= "    <td align=\"left\">" . $data['Note'] . "</td>";
                        $html.= "</tr>";
                    }
                       
                    echo $html;
                }

                $n = $m = 0;
                $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
                $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

                $ActualHour_Sum = $LateHour_Sum = 0;
                $P_Count = $PLW_Count = $SP_Count = $A_Count = $SN_Count = $L_Count = $LP_Count = $OT_Count = $NC_Count = $ALD_Count = $Minus_Count = 0;


                foreach ($resultdata as $row) {
                    $n++;
                    $data['Presence'] = $data['PLW'] = $data['A'] = $data['SP'] = $data['SN'] = $data['L'] = $data['LeavePermit'] = $data['OT'] = $data['NC'] = $data['ALD'] = $data['MinusHour'] = '';
                    $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                    $data['DayOfWeek'] = $day[$row['DayOfWeek']];
                    $data['ActualIn'] = substr($row['ActualIn'], 11, 5);
                    $data['ActualOut'] = substr($row['ActualOut'], 11, 5);
                    $data['ManualIn'] = substr($row['ManualIn'], 11, 5);
                    $data['ManualOut'] = substr($row['ManualOut'], 11, 5);
                    $data['IMKOut'] = substr($row['IMKOut'], 11, 5);
                    $data['IMKIn'] = substr($row['IMKIn'], 11, 5);
                    $data['ActualHour'] = $row['ActualHour'];
                    $ActualHour_Sum+= $data['ActualHour'];
                    $late = $row['LateHour'];
                    $data['LateHour'] = decimaltominutes($row['LateHour']+0.01);
                    $LateHour_Sum+= $late;
                    $data['Note'] = $row['rDescription'].' '.$row['Note'];

                    $in = substr($row['ActualIn'], 11, 5);
                    $out = substr($row['ActualOut'], 11, 5);
                    $min = substr($row['ManualIn'], 11, 5);
                    $mout = substr($row['ManualOut'], 11, 5);

                    $ain = $row['ActualIn'];
                    $aout = $row['ActualOut'];    
                       
                    $actualhour = (strtotime($aout) - strtotime($ain)) / 3600; 
                    
                    //echo  date('d-m-Y', strtotime($row['PresenceDate'])).' - '.$actualhour.'<br/>';

                    if (substr($row['Description'], 0, 2) == 'P') {
                        $P_Count+= 1;
                        $data['Presence'] = 1;
                        $data['Note'] = '';
                        
                        if ($actualhour <= 4 and is_null($min) and is_null($mout)) {                           
                                $Minus_Count+= 1;
                                $data['MinusHour'] = 1;
                                $data['Note'] = 'LESS WORK HOUR';
                          }
                        
                    } elseif (substr($row['Description'], -2, 2) == 'SN') {
                        $SN_Count+= 1;
                        $data['SN'] = 1;
                    } elseif (substr($row['Description'], -1, 1) == 'L') {
                        $L_Count+= 1;
                        $data['L'] = 1;
                    } elseif ($row['Description'] == 'LP') {
                        $LP_Count+= 1;
                        $data['LeavePermit'] = 1;

                        $status = $row['Necessity'];

                        if ($status == '1') {
                            $data['Note'] = 'LEAVE PERMIT (personal)';
                            if ($actualhour <= 4) {                           
                                  $Minus_Count+= 1;
                                  $data['MinusHour'] = 1;
                            }
                        } else if ($status == '2') {
                            $data['Note'] = 'LEAVE PERMIT (office)';
                            
                        }

                        //penambahan filter untuk mengecek kondisi absensi
                        if (($in != null and $out != null) or ($min != null and $mout != null)) {

                            $data['Presence'] = 1;
                            $P_Count+= 1;
                        }


                        //meremark agar tidak timbul pertanyaan
                        //$data['Presence'] = 1;
                    } elseif ($row['Description'] != NULL AND ($in AND $out) != NULL) {
                        $P_Count+= 1;
                        $data['Presence'] = 1;
                        $data['Note'] = '';
                    } elseif ($row['Description'] == 'OT') {
                        $OT_Count+= 1;
                        $data['OT'] = 1;
                    } elseif ($row['Description'] == 'PLW') {
                        $PLW_Count+= 1;
                        $data['PLW'] = 1;
                    } elseif ($row['Description'] == 'NC') {
                        $NC_Count+= 1;
                        $data['NC'] = 1;
                        $P_Count+= 1;
                        $data['Presence'] = 1;                        
                    } elseif ($row['Description'] == 'A') {
                        $A_Count+= 1;
                        $data['A'] = 1;
                    } elseif ($row['Description'] == 'SP') {
                        $SP_Count+= 1;
                        $data['SP'] = 1;
                        $data['Note'] = 'SKORSING';
                    } elseif ($row['Description'] == 'CIR') {
                        $L_Count+= 1;
                        $data['L'] = 1;
                        $data['Note'] = 'CIRCUMCISION LEAVE';
                    } else if ($row['Description'] == 'ALD') {
                        if ($row['IDJobGroup'] == 'ST' and ($row['ActualIn'] !== null and $row['ActualOut'] !== null )) {
                            $P_Count+= 1;
                            $ALD_Count+= '';
                            $data['Presence'] = 1;
                            $data['ALD'] = '';
                            $data['Note'] = '';
                        } else if ($row['IDJobGroup'] !== 'ST' and ($row['ActualIn'] !== null and $row['ActualOut'] !== null )) {
                            $P_Count+= 1;
                            $ALD_Count+= '';
                            $data['Presence'] = 1;
                            $data['ALD'] = '';
                            $data['Note'] = '';
                        } else if ($row['IDJobGroup'] == 'ST') {
                            $A_Count += '';
                            $ALD_Count+= 1;
                            $data['Presence'] = '';
                            $data['ALD'] = 1;
                            $data['Note'] = 'ANNUAL LEAVE DEDUCTION';
                        } else {
                            $A_Count += '';
                            $ALD_Count+= 1;
                            $data['A'] = '';
                            $data['ALD'] = 1;
                            $data['Note'] = 'ANNUAL LEAVE DEDUCTION';
                        }
                    }
                    
                    

                    cetak($row['IDUnitGroup'],$n, $data);
                }
                ?> 
                <tr width="100%" class="subfoot">                    
                    <td align="right">&nbsp;</td>
                    <td align="right">&nbsp;</td>
                    <td align="right" colspan="7">TOTAL</td>
                    <td align="right"><?php echo $ActualHour_Sum; ?></td>                    
                    <td align="right"><?php echo decimaltominutes($LateHour_Sum); ?></td>                    
                    <td align="right"><?php echo $P_Count; ?></td>
                    <td align="right"><?php echo $PLW_Count; ?></td>
                    <td align="right"><?php echo $A_Count; ?></td>
                    <td align="right"><?php echo $SP_Count; ?></td>	
                    <td align="right"><?php echo $SN_Count; ?></td>
                    <td align="right"><?php echo $L_Count; ?></td>
                    <td align="right"><?php echo $LP_Count; ?></td>
                    <td align="right"><?php echo $OT_Count; ?></td>
                    <td align="right"><?php echo $NC_Count; ?></td>
                    <td align="right"><?php echo $ALD_Count; ?></td>
                    <td align="right"><?php echo $Minus_Count; ?></td>
                    <td align="right">&nbsp;</td>

                </tr>
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
                        <td align="center">(9)</td>
                        <td align="center">(10)</td>
                        <td align="center">(11)</td>
                        <td align="center">(12)</td>
                        <td align="center">(13)</td>
                        <td align="center">(14)</td>
                        <td align="center">(15)</td>
                        <td align="center">(16)</td>
                        <td align="center">(17)</td>
                        <td align="center">(18)</td>
                        <td align="center">(19)</td>
                        <td align="center">(20)</td>
                        <td align="center">(21)</td>
                        <td align="center">(22)</td>
                        <td align="center">(23)</td>

                    </tr>
                    <tr>
                        <td colspan="42">
                            <table width="100%">
                                <tr>
                                    <td colspan="2" align="left" style="font-size:xx-small"><b>Note:</b></td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small" width="50px">P</td>
                                    <td style="font-size:xx-small">: Presence (Hadir)</td>
                                </tr>
                                  <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small" width="50px">PLW</td>
                                     <td style="font-size:xx-small">: Permission  to Leave Work (Ijin Kuliah / Kursus / Training)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">A</td>
                                    <td style="font-size:xx-small">: Absence (Mangkir)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">SP</td>
                                    <td style="font-size:xx-small">: Suspension (Skorsing)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">SN</td>
                                    <td style="font-size:xx-small">: Sickness Leave (Sakit)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">L</td>
                                    <td style="font-size:xx-small">: Leave (Cuti)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">LP</td>
                                    <td style="font-size:xx-small">: Leave Permit (Ijin)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">OT</td>
                                    <td style="font-size:xx-small">: Official Travel (Dinas Luar)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">NC</td>
                                    <td style="font-size:xx-small">: Not Complete (Presensi Tidak Lengkap)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">CIR</td>
                                    <td style="font-size:xx-small">: Circumcision Leave(Cuti Khitanan)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">ALD</td>
                                    <td style="font-size:xx-small">Annual Leave Deduction (Potong Cuti Tahunan)</td>
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
                window.location.href = '<?php echo site_url('rpt02/home/excel/'.$nip.'/'. $from . '/' . $until); ?>';
            }

        </script>

    </body>

</html>


