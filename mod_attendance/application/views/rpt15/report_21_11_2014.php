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
		.black{color:black;padding: 2px; font-weight: bold;}
                .tablehead{border: none;}
                tfoot {color:red;padding: 2px}
		.red{ background-color:#E9967A;}
            </style>
            <br/>
            <table style="page-break-after:always; ">
                <thead>
                <strong>PT TRIAS INDRA SAPUTRA</strong><br>
                ABSENCE FIELD REPORT
                <tr>
                    <th colspan="8" align="left">
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
                    <th colspan="5" >TYPE ABSENCE</th>

                </tr>
                <tr width="100%">
                    <th align="center" width="20px">A</th>
                    <th align="center" width="20px">SP</th>  	
                    <th align="center" width="20px">OL</th>
                    <th align="center" width="20px">SN</th>
                    <th align="center" width="20px">ALD</th>
                </tr>
                </thead>
                <!-- Dinamis -->

                <?php

                function cetak($n, $data) {
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td align=\"center\">" . $n . "</td>";
                    $html.= "    <td align=\"center\">" . $data['IDEmployee'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['FullName'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['A'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['SP'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['OL'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['SN'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['Note'] . "</td>";
                    $html.= "</tr>";
                    echo $html;
                }

                function cetak_summary($unit,$counter, $LastIDEmployee, $LastFullName, $A_Count, $SP_Count, $OL_Count, $SN_Count, $ALD_Count) {
                    if($unit=='SECURITY'){
                        $html = "<tr width='100%' class='red black'>";
                        $html.= "   <td align='center' colspan='1'>$counter</td>";
                        $html.= "   <td align='center' >$LastIDEmployee</td>";
                        $html.= "   <td align='left' >$LastFullName</td>";
                        $html.= "   <td align='right'>" . $A_Count .' (Confirm to HRD)'. "</td>";
                        $html.= "   <td align='right'>" . $SP_Count . "</td>";
                        $html.= "   <td align='right'>" . $OL_Count . "</td>";
                        $html.= "   <td align='right'>" . $SN_Count . "</td>";
                        $html.= "   <td align='right'>" . $ALD_Count . "</td>";
                        $html.= "</tr> ";
                    }else{                        
                        $html = "<tr width='100%' class='subfoot'>";
                        $html.= "   <td align='center' colspan='1'>$counter</td>";
                        $html.= "   <td align='center' >$LastIDEmployee</td>";
                        $html.= "   <td align='left' >$LastFullName</td>";
                        $html.= "   <td align='right'>" . $A_Count . "</td>";
                        $html.= "   <td align='right'>" . $SP_Count . "</td>";
                        $html.= "   <td align='right'>" . $OL_Count . "</td>";
                        $html.= "   <td align='right'>" . $SN_Count . "</td>";
                        $html.= "   <td align='right'>" . $ALD_Count . "</td>";
                        $html.= "</tr>";
                    }
                    echo $html;
                }

                $n = $m = 0;
                $hari = array('Mn', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb');
                $day = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

                $A_Count = $SP_Count = $OL_Count = $SN_Count = $ALD_Count = 0;
                $counter = 0;
                $lastemp_idemployee = '';
                $lastemp_fullname = '';
		$lastemp_unit = '';

                if ($resultdata != NULL) {
                    foreach ($resultdata as $row) {
                        if ($row['Description'] != 'A' &&
                                $row['Description'] != 'P' &&
				$row['Description'] != 'SP' &&
                                $row['Description'] != 'OL' &&
                                $row['Description'] != 'SN' &&
                                $row['Description'] != 'ALD')
                            continue;
                        if ($row['IDEmployee'] != $lastemp_idemployee && $n > 0) {
                            $counter++;
                            cetak_summary($lastemp_unit,$counter, $lastemp_idemployee, $lastemp_fullname, $A_Count, $SP_Count, $OL_Count, $SN_Count, $ALD_Count);
                            $A_Count = $SP_Count = $OL_Count = $SN_Count = $ALD_Count = 0;
                        }
                        $n++;
                        $data['A'] = $data['OL'] = $data['SN'] = '';
                        $data['FullName'] = $row['FullName'];
                        $data['IDEmployee'] = $row['IDEmployee'];

			$ain = $row['ActualIn'];
                        $aout = $row['ActualOut'];    
                       
                        $actualhour = (strtotime($aout) - strtotime($ain)) / 3600; 

                        if ($row['Description'] == 'A') {
                            $A_Count+= 1;
                            $data['A'] = 1;
                        } elseif ($row['Description'] == 'SP') {
                            $SP_Count+= 1;
                            $data['SP'] = 1;
                        } elseif ($row['Description'] == 'OL') {
                            $OL_Count+= 1;
                            $data['OL'] = 1;
                        } elseif ($row['Description'] == 'SN') {
                            $SN_Count+= 1;
                            $data['SN'] = 1;
                        } elseif ($row['Description'] == 'ALD') {
                            if ($row['IDJobGroup'] == 'ST' and ($row['ActualIn'] !== null and $row['ActualOut'] !== null )) {
                                $ALD_Count+= '';
                            } else {
                                $ALD_Count+= 1;
                            }
                        } else if($row['Description'] == 'P'){
                             if ($actualhour <= 4) {                           
                                $A_Count+= 1;
                                $data['A'] = 1;
                             }
                        
                        }
                        $lastemp_idemployee = $row['IDEmployee'];
                        $lastemp_fullname = $row['FullName'];
			$lastemp_unit = $row['IDUnitGroup'];

                        //cetak($n, $data);
                    }
                }
                ?>
                <tr width="100%" class="subfoot">
                    <td align="center" colspan="1"><?php echo $counter + 1; ?></td>
                    <td align="center"><?php echo $lastemp_idemployee; ?></td>
                    <td align="left"><?php echo $lastemp_fullname; ?></td>
                    <td align="right"><?php echo $A_Count; ?></td>
                    <td align="right"><?php echo $SP_Count; ?></td>
                    <td align="right"><?php echo $OL_Count; ?></td>
                    <td align="right"><?php echo $SN_Count; ?></td>
                    <td align="right"><?php echo $ALD_Count; ?></td>

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
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small" width="50px">SP</td>
                                    <td style="font-size:xx-small">: Suspension (Skorsing)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">OL</td>
                                    <td style="font-size:xx-small">: Other leave (Cuti potong gaji)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">SN</td>
                                    <td style="font-size:xx-small">: Sickness (Sakit tanpa surat dokter)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">ALD</td>
                                    <td style="font-size:xx-small">: Annual Leave Deduction (Potong Cuti Tahunan)</td>
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


