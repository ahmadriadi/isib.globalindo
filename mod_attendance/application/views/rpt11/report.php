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
                TURNOVER REPORT
                <tr>
                    <th colspan="42" align="left">
                <table border="0" width="100%" class="header">
                    <tr>
                        <td class="top" width="500px">PERIOD</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)); ?></td>
                    </tr>
                    <tr>
                        <td class="top" width="500px">JOB GROUP</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo $jobgroup ?></td>
                    </tr>
                </table>
                </th>
                </tr>
                <tr>
                    <th width="10px" >ID</th>
                    <th width="50px" >ID EMPLOYEE</th>
                    <th width="200px">FULLNAME</th>
                    <th width="200px" >ID JOB GROUP</th>
                    <th width="200px">UNIT GROUP</th>
                    <th width="100px" >HIRE DATE</th>
                    <th width="100px" >RESIGN DATE</th>
                    <th width="40px" >INTERVAL DAYS</th>
                    
                </tr>
                </thead>
                <!-- Dinamis -->

                <?php

                function cetak($n, $data) {
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td align=\"center\">" . $n . "</td>";
                    $html.= "    <td align=\"center\">" . $data['IDEmployee'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['FullName'] . "</td>";
                     $html.= "    <td align=\"left\">" . $data['IDJobGroup'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['IDUnitGroup'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['HireDate'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['ResignDate'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['Priv'] . "</td>";
                   
                    $html.= "</tr>";
                    echo $html;
                }

                $n = $m = 0;
                $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
                $day = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

                if ($resultdata != NULL) {
                    foreach ($resultdata as $row) {
                        $n++;
                        $data['IDEmployee'] = $row['IDEmployee'];
                        $data['FullName'] = $row['FullName'];
                        if ($row['HireDate'] == NULL) {
                            $data['HireDate'] = '';
                        } else {
                            $data['HireDate'] = date('d-m-Y', strtotime($row['HireDate']));
                        }
                        if ($row['ResignDate'] == NULL) {
                            $data['ResignDate'] = '';
                        } else {
                            $data['ResignDate'] = date('d-m-Y', strtotime($row['ResignDate']));
                        }

                        $data['Priv'] = $row['Priv']+1;
                        $data['IDJobGroup'] = $this->libfun->get_name_group($row['IDJobGroup']);
                        $data['IDUnitGroup'] = $row['IDUnitGroup'];
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



