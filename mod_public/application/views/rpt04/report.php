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

            <table style="page-break-after:always; ">
                <thead>
                <strong>PT TRIAS INDRA SAPUTRA</strong><br>
                LEAVE REPORT
                <tr>
                    <th colspan="42" align="left">
                <table border="0" width="100%" class="header">
                    <tr>
                        <td class="top" width="500px">PERIOD</td>
                        <td class="top" >: </td>
                        <td class="top" width="80%"><?php echo date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)); ?></td>
                    </tr>
                </table>
                </th>
                </tr>
                <tr>
                    <th width="10px" rowspan="2" >ID</th>
                    <th width="100px" rowspan="2" >IDEMPLOYEE</th>
                    <th width="100px" rowspan="2" >FULLNAME</th>
                    <th width="100px" rowspan="2" >DATE</th>
                    <th width="100px" rowspan="2" >DAY OF WEEK</th>
                    <th colspan="6" >TYPE LEAVE</th>
                    <th width="150px" rowspan="2" >NOTE</th>
                </tr>
                <tr width="100%">
                    <th align="center" width="20px">AL</th>
                    <th align="center" width="20px">MRL</th>
                    <th align="center" width="20px">MTL</th>
                    <th align="center" width="20px">CL</th>
                    <th align="center" width="20px">SL</th>
                    <th align="center" width="20px">OL</th>
                </tr>
                </thead>
                <!-- Dinamis -->

                <?php

                function cetak($n, $data) {
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td align=\"center\">" . $n . "</td>";
                    $html.= "    <td align=\"center\">" . $data['IDEmployee'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['FullName'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['PresenceDate'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['DayOfWeek'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['AL'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['MRL'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['MTL'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['CL'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['SL'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['OL'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['Note'] . "</td>";
                    $html.= "</tr>";
                    echo $html;
                }

                $n = $m = 0;
                $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
                $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

                $AL_Count = $MRL_Count = $MTL_Count = $CL_Count = $SL_Count = $OL_Count = 0;

                if ($resultdata != NULL) {
                    foreach ($resultdata as $row) {
                        if (substr($row['Description'], -1, 1) != 'L')
                            continue;
                        $n++;
                        $data['AL'] = $data['MRL'] = $data['MTL'] = $data['CL'] = $data['SL'] = $data['OL'] = '';
                        $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                        $data['DayOfWeek'] = $day[$row['DayOfWeek']];
                        $data['Note'] = $row['rDescription'];
                        $data['FullName'] = $row['FullName'];
                        $data['IDEmployee'] = $row['IDEmployee'];

                        if ($row['Description'] == 'AL') {
                            $AL_Count+= 1;
                            $data['AL'] = 1;
                        } elseif ($row['Description'] == 'MRL') {
                            $MRL_Count+= 1;
                            $data['MRL'] = 1;
                        } elseif (substr($row['Description'], -2, 2) == 'MTL') {
                            $MTL_Count+= 1;
                            $data['MTL'] = 1;
                        } elseif (substr($row['Description'], -1, 1) == 'CL') {
                            $CL_Count+= 1;
                            $data['CL'] = 1;
                        } elseif ($row['Description'] == 'SL') {
                            $SL_Count+= 1;
                            $data['SL'] = 1;
                        } elseif ($row['Description'] == 'OL') {
                            $OL_Count+= 1;
                            $data['OL'] = 1;
                        }

                        cetak($n, $data);
                    }
                }
                ?>
                <tr width="100%" class="subfoot">
                    <td align="right" colspan="5">TOTAL</td>
                    <td align="right"><?php echo $AL_Count; ?></td>
                    <td align="right"><?php echo $MRL_Count; ?></td>
                    <td align="right"><?php echo $MTL_Count; ?></td>
                    <td align="right"><?php echo $CL_Count; ?></td>
                    <td align="right"><?php echo $SL_Count; ?></td>
                    <td align="right"><?php echo $OL_Count; ?></td>
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
                    </tr>
                    <tr>
                        <td colspan="42">
                            <table width="100%">
                                <tr>
                                    <td colspan="2" align="left" style="font-size:xx-small"><b>Note:</b></td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small" width="50px">AL</td>
                                    <td style="font-size:xx-small">: Annual Leave (Cuti tahunan)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">MRL</td>
                                    <td style="font-size:xx-small">: Marriage Leave (Cuti menikah)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">MTL</td>
                                    <td style="font-size:xx-small">: Maternity Leave (Cuti melahirkan)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">CL</td>
                                    <td style="font-size:xx-small">: Condolence Leave (Cuti duka cita)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">SL</td>
                                    <td style="font-size:xx-small">: Sick Leave (Cuti sakit)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">OL</td>
                                    <td style="font-size:xx-small">: Other Leave (Cuti lainnya)</td>
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


