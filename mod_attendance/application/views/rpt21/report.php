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
                REPORT ACTUAL EMPLOYEE'S 
                <tr>
                    <th colspan="11" align="left">
                <table border="0" width="100%" class="header">
                    <tr>
                        <td class="top" width="500px">PERIOD</td>
                        <td class="top" >: </td>
                        <td class="top" width="100%"><?php echo date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)); ?></td>
                    </tr>
                    <tr>
                        <td class="top" width="500px">GROUP</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo $jobgroup ;?></td>
                    </tr>
                    <tr>
                        <td class="top" width="500px">DATA</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo $view ;?></td>
                    </tr>
                </table>
                </th>
                </tr>
                <tr>
                    <th width="10px" rowspan="2" >ID</th>
                    <th width="100px" rowspan="2" >IDEMPLOYEE</th>
                    <th width="200px" rowspan="2" >FULLNAME</th>
                    <th width="100px" rowspan="2" >DATE</th>                   
                    <th width="100px" rowspan="2" >DAY OF WEEK</th>
                    <th width="100px" rowspan="2" >ACTUAL IN</th>
                    <th width="100px" rowspan="2" >ACTUAL OUT</th>
                    <th colspan="2" >TYPE ABSENCE</th>
                    <th width="150px" rowspan="2" >NOTE</th>
                </tr>
                <tr width="100%">
                    <th align="center" width="20px">P</th>
                    <th align="center" width="20px">A</th>
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
                    $html.= "    <td align=\"center\">" . $data['DayOfWeek'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['AIN'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['AOUT'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['P'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['A'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['Note'] . "</td>";
                    $html.= "</tr>";
                    echo $html;
                }

                function cetak_summary($A_Count,$P_Count){
                    $html = "<tr width='100%' class='subfoot'>";
                    $html.= "   <td align='right' colspan='7'>TOTAL</td>";
                    $html.= "   <td align='center'>".$P_Count."</td>";
                    $html.= "   <td align='center'>".$A_Count."</td>";                  
                    $html.= "   <td align='right'>&nbsp;</td>";
                    $html.= "</tr>";
                    echo $html;
                }
                
                $n = $m = 0;
                $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
                $day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

                $A_Count = $P_Count =0;
                $lastemp = '';
                
                if ($resultdata != NULL) {
                    foreach ($resultdata as $row) {                     
                        if ($row['IDEmployee'] != $lastemp && $n>0){
                            cetak_summary($A_Count,$P_Count);
                            $A_Count = $P_Count  = 0;
                        }
                        $n++;
                        $data['A'] = $data['P']= '';
                        $data['PresenceDate'] = date('d-m-Y', strtotime($row['PresenceDate']));
                        $data['DayOfWeek'] = $day[date('w', strtotime($row['PresenceDate']))];
                        $data['Note'] = $row['rDescription'];
                        $data['FullName'] = $row['FullName'];                        
                        $data['AIN'] = $row['ActualIn'];                        
                        $data['AOUT'] = $row['ActualOut'];                        
                        $data['IDEmployee'] = $row['IDEmployee'];

                        if ($type == 'A') {
                            $A_Count+= 1;
                            $data['A'] = 1;
                        } elseif ($type == 'P') {
                            $P_Count+= 1;
                            $data['P'] = 1;
                        } 
                        $lastemp = $data['IDEmployee'];                        
                        cetak($n, $data);
                    }
                }
                ?>
                <tr width="100%" class="subfoot">
                    <td align="right" colspan="7">TOTAL</td>
                    <td align="center"><?php echo $P_Count; ?></td>
                    <td align="center"><?php echo $A_Count; ?></td>
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
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small" width="50px">A</td>
                                    <td style="font-size:xx-small">: Absence (Tidak Hadir)</td>
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


