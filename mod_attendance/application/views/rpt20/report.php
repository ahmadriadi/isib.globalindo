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
                UNPAID LEAVE REPORT
                <tr>
                    <th colspan="42" align="left">
                <table border="0" width="100%" class="header">
                    <tr>
                        <td class="top" width="500px">PERIOD</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)); ?></td>
                    </tr>
                </table>
                </th>
                </tr>
                <tr>
                    <th width="10px" >ID</th>
                    <th width="50px" >ID EMPLOYEE</th>
                    <th width="200px">FULLNAME</th>
                    <th width="200px">UNIT GROUP</th>
                    <th width="100px" >FROM DATE</th>
                    <th width="100px" >UNTIL DATE</th>
                    <th width="40px" >NOTE</th>                   
                </tr>
                </thead>
                <!-- Dinamis -->

                <?php

                function cetak($n, $data) {
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td align=\"center\">" . $n . "</td>";
                    $html.= "    <td align=\"center\">" . $data['Nip'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['Name'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['Group'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['From'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['Until'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['Note'] . "</td>";
                    $html.= "</tr>";
                    echo $html;
                }

                $n = $m = 0;
                $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
                $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

                if ($resultdata != NULL) {
                    foreach ($resultdata as $row) {
                        $n++;
                        $data['Nip'] = $row['IDEmployee'];
                        $data['Name'] = $row['FullName'];
                        $data['From'] = date('d-m-Y', strtotime($row['TglCutiDari']));                     
                        $data['Until'] = date('d-m-Y', strtotime($row['TglCutiSampai']));
                        $gj = $row['IDJobGroup'];
                        if($gj=='ST'){
                            $gname = 'STAFF';                           
                        }else if($gj=='LT'){
                             $gname = 'LAPANGAN TETAP';  
                        }else if($gj=='LK'){
                             $gname = 'LAPANGAN KONTRAK';  
                        }else if($gj=='HL'){
                             $gname = 'HARIAN LEPAS';  
                        }else if($gj=='LL'){
                             $gname = 'LAIN-LAIN';  
                        }
                        
                        $data['Group'] = $gname;
                        $data['Note'] = $row['Alasan'];
                        cetak($n, $data);
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


