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
                    <th width="40px" >LATE HOUR</th>           
                    <th width="50px">LATE HOUR SUM</th>

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
                    $html.= "    <td align=\"center\">" . $data['LateHour_Sum'] . "</td>";
                    $html.= "</tr>";
                    echo $html;
                }

                $n = $m = 0;
                $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
                $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                $last_idemployee = $resultdata[0]['IDEmployee'];

                if ($resultdata != NULL) {
                    $TotLH = 0;
                    foreach ($resultdata as $row) {
                        $data['LateHour_Sum'] = 0;
                        if ($row['LateHour'] == 0)
                            continue;
                        if ($row['IDEmployee'] != $last_idemployee) {
                            $data['LateHour_Sum'] = 0;
                            $TotLH = 0;
                        }
                        $keterangan_ijin = $row['Description'];

                        if ($keterangan_ijin == 'LP') {
                            
                        } else {
                            $n++;
                            $TotLH = $TotLH + $row['LateHour'];
                            $data['IDEmployee'] = $row['IDEmployee'];
                            $data['FullName'] = $row['FullName'];
                            $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                            $data['DayOfWeek'] = $day[date('w', strtotime($row['PresenceDate']))];
                            $data['ActualIn'] = date('H:i', strtotime($row['ActualIn']));
                            $data['LateHour_Sum'] = decimaltominutes($TotLH);
                            $data['LateHour'] = decimaltominutes($row['LateHour']);
                            $last_idemployee = $data['IDEmployee'];

                            cetak($n, $data);
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


