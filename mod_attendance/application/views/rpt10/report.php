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
                INCOMPLETE PRESENCE REPORT
                <tr>
                    <th colspan="11" align="left">
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
                    <th width="250px" rowspan="2" >FULLNAME</th>
                    <th width="250px" rowspan="2" >GROUP</th>
                    <th width="100px" rowspan="2" >DATE</th>
                    <th width="100px" rowspan="2" >DAY OF WEEK</th>
                    <th width="100px" rowspan="2" >ACTUAL IN</th>
                    <th width="100px" rowspan="2" >ACTUAL OUT</th>
                    <th colspan="2" >STATUS</th>
                    <th width="150px" rowspan="2" >NOTE</th>
                </tr>
                
		 <tr width="100%">
                      <th align="center">Data Incomplete</th>
                    <th align="center">Form Incompete</th>
		 </tr>	
                </thead>
                <!-- Dinamis -->

                <?php

                function cetak($n, $data) {
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td align=\"center\">" . $n . "</td>";
                    $html.= "    <td align=\"center\">" . $data['IDEmployee'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['FullName'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['GroupName'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['PresenceDate'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['DayOfWeek'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['ActualIn'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['ActualOut'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['Incomplete'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['Formdata'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['Note'] . "</td>";
                    $html.= "</tr>";
                    echo $html;
                }

                $n = $m = 0;
                $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
                $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

                if ($resultdata != NULL) {

                    $count_incomplete = $count_presence =0;
                    foreach ($resultdata as $row) {
                        if ($row['Description'] != 'NC')
                            continue;
                        $n++;
                        $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                        $data['DayOfWeek'] = $day[$row['DayOfWeek']];
                        $data['Note'] = $row['rDescription'];
                        $data['FullName'] = $row['FullName'];
                        $data['GroupName'] = $row['GroupName'];
                        $data['IDEmployee'] = $row['IDEmployee'];
                        if ($row['ActualIn'] == NULL) {
                            $data['ActualIn'] = '';
                        } else {
                            $data['ActualIn'] = date('H:i', strtotime($row['ActualIn']));
                        }
                        if ($row['ActualOut'] == NULL) {
                            $data['ActualOut'] = '';
                        } else {
                            $data['ActualOut'] = date('H:i', strtotime($row['ActualOut']));
                        }

                        $checkincomplete= $this->report->check_dataincomplete($row['IDEmployee'],$row['PresenceDate']);
                        if($checkincomplete=='exist'){
                            $data['Incomplete']='';
                            $data['Formdata']=1;
                            $data['Note'] = 'PRESENCE COMPLETE';
                            $count_presence+= 1;
                        }else{
                            $data['Note'] = $row['rDescription'];
                            $data['Incomplete']=1;
                            $data['Formdata']='';
                            $count_incomplete+= 1;
                        }


                        cetak($n, $data);
                    }
                }
                ?>

                <tr width="100%" class="subfoot">
                    <td align="right" colspan="8">TOTAL</td>
                    <td align="center"><?php echo $count_incomplete; ?></td>
                    <td align="center"><?php echo $count_presence; ?></td>
                    <td align="center">&nbsp;</td>
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
                    </tr>
                    <tr>
                        <td colspan="42">
                            <table width="100%">
                                <tr>
                                    <td colspan="2" align="left" style="font-size:xx-small"><b>Note:</b></td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small" width="50px"><br/></td>
                                    <td style="font-size:xx-small"></td>
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



