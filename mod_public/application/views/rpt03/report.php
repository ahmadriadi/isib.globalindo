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
                thead {border:0px no-solid;color:black;padding: 2px}
                tbody {color:black;height:10px;padding: 2px}
                .subfoot{color:black;padding: 2px; font-weight: bold;}
                .tablehead{border: none;}
                tfoot {color:red;padding: 2px}
            </style>
            <br/>
            <?PHP
            for ($i = 0; $i < count($resultdata); $i++) {
                //IDJobGroup
                if ($resultdata[$i]['IDJobGroup'] == 'ST') {
                    $NMJobGroup = "STAFF";
                } else {
                    if ($resultdata[$i]['IDJobGroup'] == 'LT') {
                        $NMJobGroup = "LAPANGAN TETAP";
                    } else {
                        if ($resultdata[$i]['IDJobGroup'] == 'LK') {
                            $NMJobGroup = "LAPANGAN KONTRAK";
                        } else {
                            if ($resultdata[$i]['IDJobGroup'] == 'HL') {
                                $NMJobGroup = "HARIAN LEPAS";
                            } else {
                                $NMJobGroup = "LAIN-LAIN";
                            }
                        }
                    }
                }
            }
            ?>
            <table style="page-break-after:always; ">
                <thead><strong>PT TRIAS INDRA SAPUTRA</strong><br>
                SUMMARY PRESENCE EMPLOYEE REPORT
                <tr>
                    <th colspan="<?php echo $selisih+13 ?>" align="left">
                <table border="0" width="100%" class="header">
                    <tr>
                        <td class="top" width="500px">PERIOD</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo date('d-m-Y', strtotime($fromdate)) . ' to ' . date('d-m-Y', strtotime($untildate)); ?></td>
                    </tr>
		    <tr>
                        <td class="top" width="500px">Location</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php  
                        if($site =='undefined'){
                            $sitedata = 'All Site';
                        }else{
                            $sitedata = $site;
                            
                        }
                        echo $sitedata
                        ; ?></td>
                    </tr>	
                    <tr>
                        <td class="top" width="500px">DEPARTEMENT</td>
                        <td class="top" >: </td>
                        <td class="top" width="85%"><?php echo $departement; ?></td>
                    </tr>
                </table>
                </th>
                </tr>
                <tr>
                    <th width="50px" rowspan="2" >ID EMPLOYEE</th>
                    <th width="300px" rowspan="2" >FULLNAME</th>
                    <th width="100px" rowspan="2" >ID JOBGROUP</th>
                    <th colspan="<?php echo $selisih ?>">DATE</th>
                    <th colspan="10" >TOTAL</th>
                </tr>
                <tr width="100%">
                    <?php
                    $perioddate = $fromdate;
                    while ($perioddate <= $untildate) {
                        echo "<th width='20px' align='center'>" . date('d', strtotime($perioddate)) . "</th>";
                        $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
                    }
                    ?>

                    <th width="20px" align="center">P</th>
                    <th width="20px" align="center">PLW</th>
                    <th width="20px" align="center">A</th>
                    <th width="20px" align="center">SP</th> 	
                    <th width="20px" align="center">SN</th>
                    <th width="20px" align="center">L</th>
                    <th width="20px" align="center">LP</th>
                    <th width="20px" align="center">OT</th>
                    <th width="20px" align="center">NC</th>
                    <th width="20px" align="center">ALD</th>
                </tr>
                </thead>
                <!-- Dinamis -->
                <tbody>
                    <?php

                    function cetak($data) {
                        $html = "<tr width=\"100%\">";
                        $html.= "    <td align=\"center\">" . $data['IDEmployee'] . "</td>";
                        $html.= "    <td align=\"left\">" . $data['FullName'] . "</td>";
                        $html.= "    <td align=\"center\">" . $data['IDJobGroup'] . "</td>";

                        $perioddate = $data['fromdate'];
                        while ($perioddate <= $data['untildate']) {
                            $html.= "    <td align=\"center\">" . $data[date('d-m-Y', strtotime($perioddate))] . "</td>";
                            $perioddate = date('Y-m-d', strtotime("+1 day", strtotime($perioddate)));
                        }

                        $html.= "    <td align=\"right\">" . $data['P_Count'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['PLW_Count'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['A_Count'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['SP_Count'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['SN_Count'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['L_Count'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['Permit_Count'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['OT_Count'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['NC_Count'] . "</td>";
                        $html.= "    <td align=\"right\">" . $data['ALD_Count'] . "</td>";
                        $html.= "</tr>";
                        echo $html;
                    }

                    $i = 7;
                    $lastnip = $resultdata[0]['IDEmployee'];
                    $data['P_Count'] = $data['PLW_Count'] = $data['A_Count'] = $data['SP_Count'] = $data['SN_Count'] = $data['Permit_Count'] = $data['L_Count'] = $data['OT_Count'] = $data['NC_Count'] = $data['ALD_Count'] = 0;

                    if ($resultdata != NULL) {

                        foreach ($resultdata as $row) {
                            if ($lastnip != $row['IDEmployee']) {
                                // Cetak
                                $data['fromdate'] = $fromdate;
                                $data['untildate'] = $untildate;
                                cetak($data);
                                unset($data);
                                $data['P_Count'] = $data['PLW_Count'] = $data['A_Count'] = $data['SP_Count'] = $data['SN_Count'] = $data['Permit_Count'] = $data['L_Count'] = $data['OT_Count'] = $data['NC_Count'] = $data['ALD_Count'] = 0;
                            }
                            // Set
                            $data['IDEmployee'] = $row['IDEmployee'];
                            $data['FullName'] = $row['FullName'];
                            $data['IDJobGroup'] = $row['IDJobGroup'];
                            $presence = $row['PresenceDate'];
                            $hire = $row['HireDate'];
                            $resign = $row['ResignDate'];
			    
			    $ain = $row['ActualIn'];
                            $aout = $row['ActualOut'];  
			    $min = $row['ManualIn'];
                            $mout = $row['ManualOut'];						
			
			    $actualhour = (strtotime($aout) - strtotime($ain)) / 3600; 	
			    $checkmin = ($min=='' or $min==null)?'empty':'exist';
                            $checkmout = ($mout=='' or $mout==null)?'empty':'exist';			   
 	

                            if ($hire > $presence) {
                                $data[date('d-m-Y', strtotime($row['PresenceDate']))] = "New";
                            } elseif ((!is_null($resign) AND ($presence >= $resign))) {
                                $data[date('d-m-Y', strtotime($row['PresenceDate']))] = "Resign";
                            } else {


                                $description = $row['Description'];

                                if ($description == 'P') {

				   if ($actualhour <= 4) { 
					    if($checkmin !=='exist' and $checkmout !=='exist'){
						 $data['A_Count'] += 1;
                                                 $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'A';	
					    } 

				       }else{
					      $data['P_Count'] += 1;
                                              $data[date('d-m-Y', strtotime($row['PresenceDate']))] = '';
					   
				       } 					

                                   
                                } else if ($description == 'PLW') {
                                    $data['PLW_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'PLW';
                                } else if ($description == 'A') {
                                    $data['A_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'A';
                                } else if ($description == 'SP') {
                                    $data['SP_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'SP';
                                } else if ($description == 'SN') {
                                    $data['SN_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'SN';
                                } else if ($description == 'LP') {

				     //$data['P_Count'] += 1;	
                                    //$data['Permit_Count'] += 1;
                                    //$data[date('d', strtotime($row['PresenceDate']))] = 'LP'; 

				$status = $row['Necessity'];
				 if ($status == '1') {

				 } else if ($status == '2') {
						
				  $data['P_Count'] += 1;
                                  $data[date('d-m-Y', strtotime($row['PresenceDate']))] = '';
				}
				
                                } else if ($description == 'AL') {
                                    $data['L_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'L';
                                } else if ($description == 'MTL') {
                                    $data['L_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'L';
                                } else if ($description == 'MRL') {
                                    $data['L_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'L';
                                } else if ($description == 'CL') {
                                    $data['L_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'L';
                                } else if ($description == 'SL') {
                                    $data['L_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'L';
                                } else if ($description == 'OL') {
                                    $data['L_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'L';
                                } else if ($description == 'FML') {
                                    $data['L_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'L';
                                } else if ($description == 'CIR') {
                                    $data['L_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'L';
                                } else if ($description == 'OT') {
                                    $data['OT_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'OT';
                                } else if ($description == 'NC') {
                                    $data['NC_Count'] += 1;
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'NC';
                                    $data['P_Count'] += 1;
                                } else if ($description == 'ALD') {
                                    if ($row['IDJobGroup'] == 'ST' and ($row['ActualIn'] !== null and $row['ActualOut'] !== null )) {
                                        $data['P_Count'] += 1;
                                        $data['ALD_Count'] += '';
                                        $data[date('d-m-Y', strtotime($row['PresenceDate']))] = '';
                                    } else if ($row['IDJobGroup'] !== 'ST' and ($row['ActualIn'] !== null and $row['ActualOut'] !== null )) {
                                        $data['P_Count'] += 1;
                                        $data['ALD_Count'] += '';
                                        $data[date('d-m-Y', strtotime($row['PresenceDate']))] = '';
                                    } else if ($row['IDJobGroup'] == 'ST') {
                                        $data['A_Count'] += '';
                                        $data['ALD_Count'] += 1;
                                        $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'ALD';
                                    } else {
                                        $data['A_Count'] += '';
                                        $data['ALD_Count'] += 1;
                                        $data[date('d-m-Y', strtotime($row['PresenceDate']))] = 'ALD';
                                    }
                                } else {
                                    $data[date('d-m-Y', strtotime($row['PresenceDate']))] = '-';
                                }
                            }



                            $lastnip = $row['IDEmployee'];
                        }
                        $data['fromdate'] = $fromdate;
                        $data['untildate'] = $untildate;
                        cetak($data);
                    }
                    ?>

                </tbody>

                <tfoot>      
                    <tr>
                        <td colspan="<?php echo $selisih+13 ?>">
                            <table width="100%">
                                <tr>
                                    <td colspan="2" align="left" style="font-size:xx-small"><b>Note:</b></td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small" width="50px">P</td>
                                    <td style="font-size:xx-small">: Presence (Hadir)</td>
                                </tr>
                                 <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small" width="50px">PLW</td>
                                     <td style="font-size:xx-small">: Permission  to Leave Work (Ijin Kuliah / Kursus / Training)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">A</td>
                                    <td style="font-size:xx-small">: Absence (Mangkir)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">SP</td>
                                    <td style="font-size:xx-small">: Suspension (Skorsing)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">SN</td>
                                    <td style="font-size:xx-small">: Sickness Leave (Sakit)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">L</td>
                                    <td style="font-size:xx-small">: Leave (Cuti)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">LP</td>
                                    <td style="font-size:xx-small">: Leave Permit (Ijin)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">OT</td>
                                    <td style="font-size:xx-small">: Official Travel (Dinas Luar)</td>
                                </tr>
                                <tr>                    
                                    <td align="left" nowrap="nowrap" style="font-size:xx-small">NC</td>
                                    <td style="font-size:xx-small">: Not Complete (Presensi Tidak Lengkap)</td>
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
                window.location.href = '<?php echo site_url('rpt03/home/excel/'. $fromdate . '/' . $untildate.'/'.$dept.'/'.$jobgroup.'/'.$site); ?>';
            }

        </script>

    </body>

</html>


