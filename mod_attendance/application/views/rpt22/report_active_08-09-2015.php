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

            <table style="page-break-after:always; " width="100%">
                <thead>
                <strong>PT TRIAS INDRA SAPUTRA</strong><br>
                LIST OF EMPLOYEE REPORT 
                <tr>
                    <th colspan="42" align="left">
                <table border="0" width="100%" class="header">
                    <tr>
                        <td class="top" width="500px">PERIOD</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo date('d-m-Y', strtotime($date)); ?></td>
                    </tr>
                    <tr>
                        <td class="top" width="500px">FILTER</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo $jobgroup ?></td>
                    </tr>
                </table>
                </th>
                </tr>
                <tr width="100%">
                    <th width="50px" colspan="6">LIST OF EMPLOYEE</th>                                    
                </tr>
                <tr width="100%">
                    <th width="50px" colspan="6" >Active</th>                                    
                </tr>
                <tr width="100%">                    
                    <td colspan="6" valign="top">
                        <table width="100%" cellpadding="0" cellspacing="0" style="top:">
                            <tr>
                                <th width="15px">No</th>                                    
                                <th width="50px">IDEmployee</th>                                    
                                <th width="50px">Fullname</th>                                    
                                <th width="50px">Group</th>                                    
                                <th width="50px">Unit Job</th>                                    
                                <th width="50px">Hiredate</th>  
                            </tr>
                            <?php
                            if ($resultactive !== 'empty') {
                                $nohire = 0;
                                foreach ($resultactive as $row) {
                                    $nip = $row['IDEmployee'];
                                    $name = $row['FullName'];
                                    $group = $this->libfun->get_name_group($row['IDJobGroup']);
                                    $unit = $row['IDUnitGroup'];
                                    $hiredate = $row['HireDate'];

                                    $checkhire = ($hiredate == '' or $hiredate == NULL) ? 'empty' : 'exist';

                                    $nohire++;
                                    echo '<tr>';
                                    echo '<td>' . $nohire . '</td>';
                                    echo '<td>' . $nip . '</td>';
                                    echo '<td>' . $name . '</td>';
                                    echo '<td>' . $group . '</td>';
                                    echo '<td>' . $unit . '</td>';
                                    echo '<td>' . $hiredate . '</td>';
                                    echo '</tr>';
                                }
                            }
                            ?>
                        </table> 

                    </td>

                </tr>                
                </thead>
                </tbody>

                <tfoot> 
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



