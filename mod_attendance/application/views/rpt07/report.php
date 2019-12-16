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
                LEAVERMIT FORM REPORT
                <tr>
                    <th colspan="12" align="left">
                <table border="0" width="100%" class="header">
                    <tr>
                        <td class="top" width="500px">PERIOD</td>
                        <td class="top" >: </td>
                        <td class="top" width="100%"><?php echo date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)); ?></td>
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
                    <th width="10px" rowspan="2" >ID</th>
                    <th width="100px" rowspan="2" >IDEMPLOYEE</th>
                    <th width="200px" rowspan="2" >FULLNAME</th>
                    <th width="100px" rowspan="2" >DATE</th>
                    <th width="100px" rowspan="2" >DAY OF WEEK</th>
                    <th colspan="5" >IMK</th>                   
                    <th width="150px" rowspan="2" >NOTE</th>
                </tr>
                <tr width="100%">
                    <th align="center" width="20px">Personal</th>
                    <th align="center" width="20px">Office</th>
                    <th align="center" width="20px">Out</th>
                    <th align="center" width="20px">In</th>
                    <th align="center" width="20px">Sum Hour</th>
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
                    $html.= "    <td align=\"center\">" . $data['nip'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['name'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['date'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['day'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['personal'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['office'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['out'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['in'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['sumhour'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['note'] . "</td>";
                    $html.= "</tr>";
                    echo $html;
                }

                function cetak_summary($pesonal, $office) {
                    $html = "<tr width='100%' class='subfoot'>";
                    $html.= "   <td align='right' colspan='5'>TOTAL</td>";
                    $html.= "   <td align='center'>" . $pesonal . "</td>";
                    $html.= "   <td align='center'>" . $office . "</td>";
                    $html.= "   <td align='right' colspan='4'>&nbsp;</td>";
                    $html.= "</tr>";
                    echo $html;
                }

                $n = $m = 0;
                $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
                $day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

                $pesonal = $office = 0;
                $lastemp = '';

                if ($resultdata != NULL) {
                    foreach ($resultdata as $row) {
                        if ($row['Necessity'] != '1' &&
                                $row['Necessity'] != '2')
                            continue;
                        if ($row['IDEmployee'] != $lastemp && $n > 0) {
                            cetak_summary($pesonal, $office);
                            $pesonal = $office = 0;
                        }
                        $n++;
                        $data['personal'] = $data['office'] = $data['out'] = $data['in'] = $data['sumhour'] = '';
                        $data['date'] = $row['LPDate'];
                        $data['day'] = $day[date('w', strtotime($row['LeavePermitDate']))];
                        $data['note'] = $row['Note'];
                        $data['name'] = $row['FullName'];
                        $data['nip'] = $row['IDEmployee'];

                        $out = (date('H:i', strtotime($row['OutDate'])) == '00:00') ? '-' : date('H:i', strtotime($row['OutDate']));
                        $in = (date('H:i', strtotime($row['InDate'])) == '00:00') ? '-' : date('H:i', strtotime($row['InDate']));

                        $data['out'] = $out;
                        $data['in'] = $in;
                        $hour = decimaltominutes($row['IMKHour']);
                        $imkhour = ($hour == '-10000:00') ? '-' : $hour;
                        $data['sumhour'] = $imkhour;



                        if ($row['Necessity'] == '1') {
                            $pesonal+= 1;
                            $data['personal'] = 1;
                        } elseif ($row['Necessity'] == '2') {
                            $office+= 1;
                            $data['office'] = 1;
                        }

                        $lastemp = $data['nip'];

                        cetak($n, $data);
                    }
                }
                ?>
                <tr width="100%" class="subfoot">
                    <td align="right" colspan="5">TOTAL</td>
                    <td align="center"><?php echo $pesonal; ?></td>
                    <td align="center"><?php echo $office; ?></td>                   
                    <td align="right" colspan="4">&nbsp;</td>
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
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small" width="50px">A</td>
                                    <td style="font-size:xx-small">: Absence (Tidak hadir)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">OL</td>
                                    <td style="font-size:xx-small">: Other leave (Cuti potong gaji)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">SN</td>
                                    <td style="font-size:xx-small">: Sickness (Sakit tanpa surat dokter)</td>
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


