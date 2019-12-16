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
    </style>       



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
            DETAIL OVERTIME REPORT
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
                    <th width="10px" >NO</th>
                    <th width="150px" >ID SPKL</th>
                    <th width="100px" >ID EMPLOYEE</th>
                    <th width="180px" >FULLNAME</th>
                    <th width="100px" >JOB GROUP</th>                    
                    <th width="50px" >DATE</th>
                    <th width="100px" >WORK DAY</th>
                    <th width="120px" >OVERTIME IN</th>
                    <th width="120px" >OVERTIME OUT</th >
                    <th width="100px" >OVERTIME TOTAL HOUR</th>
                   
                </tr>
                </thead>
                <!-- Dinamis -->
                        
                <?php
              //die(print_r($overtime)); 
                if ($overtime != NULL ){
                
                $c=0;
                foreach ($overtime as $row) {                  
                   $c++;
                    $html = "<tr width=\"100%\">";  
                    $html.= "    <td align=\"center\">" . $c . "</td>";
                    $html.= "    <td align=\"left\">"   . $row['ID'] . "/" . $row['IDSPKL'] . "</td>";
                    $html.= "    <td align=\"center\">" . $row['IDEmployee'] . "</td>";
                    $html.= "    <td align=\"left\">"   . $row['FullName'] . "</td>";
                    $html.= "    <td align=\"center\">" . $row['GROUP'] . "</td>";
                    $html.= "    <td align=\"center\">" . $row['PresenceDate'] . "</td>";
                    $html.= "    <td align=\"center\">" . $row['DayOfWeek'] . "</td>";
                    $html.= "    <td align=\"center\">" . $row['OvertimeIn'] . "</td>";
                    $html.= "    <td align=\"center\">" . $row['OvertimeOut'] . "</td>";  
                    $html.= "    <td align=\"center\">" . $row['OvertimeTotalHour'] . "</td>";                    
                    $html.= "</tr>";
                    echo $html;
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
                                    <td style="font-size:xx-small">: &nbsp;</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">A</td>
                                    <td style="font-size:xx-small">: &nbsp;</td>
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


