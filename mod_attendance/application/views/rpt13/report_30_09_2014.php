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
                SUMMARY OVERTIME STAFF REPORT
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
                        <td class="top" width="85%"><?php echo $jobgroup; ?></td>
                    </tr>
                </table>
                </th>
                </tr>
                <tr>
                    <th width="10px" >NO</th>                    
                    <th width="100px" >NIP</th>
                    <th width="180px" >NAME</th>                             
                    <th width="100px" >TOTAL HOUR</th>


                </tr>
                </thead>                
                <?php
                function cetak_summary($counter, $LastIDEmployee, $LastFullName, $summary_OverTime) {
                    $html = "<tr width='100%' class='subfoot'>";
                    $html.= "   <td align='center' colspan='1'>$counter</td>";
                    $html.= "   <td align='center' >$LastIDEmployee</td>";
                    $html.= "   <td align='left' >$LastFullName</td>";
                    $html.= "   <td align='center'>" . $summary_OverTime . "</td>";
                    $html.= "</tr>";
                    echo $html;
                }

                $n = 0;
                $counter = 0;

                $summary_OverTime = 0;
                $lastemp_idemployee = '';
                $lastemp_fullname = '';
                foreach ($resultdata as $row) {
                    if ($lastemp_idemployee <> $row['IDEmployee'] AND $n > 0) {
                        $counter++;
                        cetak_summary($counter, $lastemp_idemployee, $lastemp_fullname, $summary_OverTime);
                        $summary_OverTime = 0;
                    }
                    $n++;                   
                    $WorkDay = $row['WorkDay'];
                    $DescDay = $row['Description'];

                    $OvertimeIn = $row['OvertimeIn'];
                    $OvertimeOut = $row['OvertimeOut'];


                    if (is_null($OvertimeIn) OR $OvertimeIn == '0000-00-00 00:00:00') {
                        $OvertimeIn = "00-00-0000 00:00";
                        $OvertimeHour = 0;
                    } else {
                        if (is_null($OvertimeOut) OR $OvertimeOut == '0000-00-00 00:00:00') {
                            $OvertimeOut = "00-00-0000 00:00";
                            $OvertimeHour = 0;
                        } else {

                            $OvertimeIn = date('d-m-Y H:i', strtotime($row['OvertimeIn']));
                            $OvertimeOut = date('d-m-Y H:i', strtotime($row['OvertimeOut']));
                            $OvertimeHour = $this->libfun->subs_time($OvertimeIn, $OvertimeOut, 30);
                        }
                    }

                    /* di matikan berdasarkan catatan bu doris per tanggal 01-04-2014 */
                    //if ($OvertimeHour >=8 ) $OvertimeHour = $OvertimeHour - 1; /* di remark tanggal 11 november 2013 */
                    //request sally 11 november 2013
                    //if ($OvertimeHour >=5 ) $OvertimeHour = $OvertimeHour - 1;
                    /* di matikan berdasarkan catatan bu doris per tanggal 01-04-2014 */

                    if ($WorkDay == 'SUN' OR $WorkDay == 'OFF' AND $DescDay != 'ALD') {
                        // For WorkDay is sunday or offday dan bukan cuti bersama
                        /* di hidupkan di hari libur atau minggu berdasarkan catatan bu doris per tanggal 01-04-2014 */
                        if ($OvertimeHour >= 8)
                            $OvertimeHour = $OvertimeHour - 1;                       
                            $OvertimeTotalHour = $this->libfun->overtime_on_offday_staff($OvertimeHour);                       
                    } else {
                        // For WorkDay is normal day
                        $OvertimeTotalHour = $this->libfun->overtime_on_workday($OvertimeHour, 2);
                    }
                    $lastemp_idemployee = $row['IDEmployee'];
                    $lastemp_fullname = $row['FullName'];
                    $summary_OverTime+= $OvertimeTotalHour;
                }
                ?>
                <tr width="100%" class="subfoot">
                    <td align="center" colspan="1"><?php echo $counter + 1; ?></td>
                    <td align="center"><?php echo $lastemp_idemployee; ?></td>
                    <td align="left"><?php echo $lastemp_fullname; ?></td>
                    <td align="center"><?php echo $summary_OverTime; ?></td>                             

                </tr>
                </tbody>

                <tfoot>                
                    <tr>
                        <td align="center">(1)</td>
                        <td align="center">(2)</td>
                        <td align="center">(3)</td>
                        <td align="center">(4)</td>                        
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


