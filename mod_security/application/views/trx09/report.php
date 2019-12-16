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
                thead {border:0px no-solid;color:black;padding: 2px}
                tbody {color:black;height:10px;padding: 2px}
                .subfoot{color:black;padding: 2px; font-weight: bold;}
                .tablehead{border: none;}
                tfoot {color:red;padding: 2px}
            </style>
            <br/>           
            <table style="page-break-after:always; " width="100%">
                <thead>
                <strong>PT TRIAS INDRA SAPUTRA</strong><br>
                ROOT CAUSE
                <tr>
                    <th colspan="45" align="left">
                <table border="0" width="100%" class="header">                  
                </table>
                </th>
                </tr>
                <tr>
                    <th width="10px" >No</th>
                    <th width="10px" >ID</th>
                    <th width="200px" >User</th>
                    <th width="80px" >Root Cause</th>
                    <th width="150px" >PIC</th>
                    <th width="250px">Complain or Request</th>
                    <th width="100px">Request Time</th>
                    <th width="250px" >Problem</th>
                    <th width="80px" >Status</th>
                    <th width="250px" >Solution</th>
                    <th width="80px" >Progress Time</th>
                    <th width="80px" >Solved Time</th>
                    <th width="80px" >Suspension Time</th>
                    <th width="80px" >Unsolved  Time</th>
                    <th width="80px" >Solution Time</th>
                    <th width="80px" >Duration</th>
                </tr>
                </thead>
                <!-- Dinamis -->

                <?php

                function cetak($n, $data) {
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td align=\"center\">" . $n . "</td>";
                    $html.= "    <td align=\"center\">" . $data['ID'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['User'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['RootName'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['picoleh'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['ComplainNote'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['AddedDate'] . "</td>";
                    $html.= "    <td align=\"left\">" . $data['ProblemNote'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['StatusProblem']. "</td>";
                    $html.= "    <td align=\"left\">" . $data['SolutionNote'] ."</td>";
                    $html.= "    <td align=\"center\">" . $data['progress'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['solved'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['suspen'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['unsolved'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['solution'] . "</td>";
                    $html.= "    <td align=\"center\">" . $data['duration'] . "</td>";
                    $html.= "</tr>";
                    echo $html;       
                } 
                
                
                
                function user($data){
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td colspan ='16' align=\"left\">".'Computer Name : '.$data['Computer']. "</td>";                                   
                    $html.= "</tr>";
                    echo $html;  
                    
                }
                
                function createuser($data){    
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td colspan='4' align=\"left\">".'User ID : '.$data['userid']. "</td>";
                    $html.= "    <td align=\"center\">".'Status : '.$data['statususer']. "</td>";
                    $html.= "    <td colspan='3' align=\"left\">".'Internal Email : '.$data['emailinternal']. "</td>";
                    $html.= "    <td colspan='3' align=\"left\">".'External Email : '.$data['emailexternal']. "</td>";
                    $html.= "    <td colspan='5'align=\"center\">".'Status Internet :'.$data['statusinternet']. "</td>";                                  
                    $html.= "</tr>";
                    echo $html;  
                    
                }
                
                function software($data){
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td colspan='7' align=\"left\">".'Software  :'.$data['software'] . "</td>";
                    $html.= "    <td colspan='9' align=\"left\">".'Status : '.$data['statussoftware']. "</td>";                                                 
                    $html.= "</tr>";
                    echo $html;  
                    
                }
                
                function createfolder($data){
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td  colspan='7' align=\"left\">".'Folder Name  :'.$data['foldername']. "</td>";
                    $html.= "    <td  colspan='9' align=\"left\">".'Status :'.$data['folderstatus']. "</td>";                                                 
                    $html.= "</tr>";
                    echo $html;  
                    
                }
                
                
                 function accessfolder($data){
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td colspan='7' align=\"left\">".'Access Folder  : '.$data['folderaccess']. "</td>";
                    $html.= "    <td colspan='9' align=\"left\">".'Status : '.$data['folderstatus']. "</td>";                                                 
                    $html.= "</tr>";
                    echo $html;  
                    
                }
                
                function useragreement($data){
                    $html = "<tr width=\"100%\">";
                    $html.= "    <td colspan='16' align=\"left\">".'Status  Agreement : '.$data['statusagreement']. "</td>";                                                 
                    $html.= "</tr>";
                    echo $html;  
                    
                }
                
                 if($resultdata !=='empty'){
                    $no=0;
                     foreach ($resultdata as $row) {
                         
                         
                         $no++;
                         if($row['StatusProblem'] =='0'){
                             $status = 'Waiting';                             
                         }else if ($row['StatusProblem'] =='1'){
                             $status = 'Solved';  
                         }else if($row['StatusProblem'] =='2'){
                             $status = 'Unsolved';  
                         }else if($row['StatusProblem'] =='3'){
                              $status = 'In Progess';  
                         }
                             
                         if($row['RootName']=='REQUEST'){                             
                            $data['ID']=  $row['ID'];                       
                            $data['User']=  $row['diadd'];                       
                            $data['RootName']=  $row['RootName'];                       
                            $data['picoleh']=  $row['picoleh'];                       
                            $data['ComplainNote']=  strip_tags($row['ComplainNote']);                       
                            $data['AddedDate']=  $row['AddedDate'];                       
                            $data['ProblemNote']=  strip_tags($row['ProblemNote']);                       
                            $data['ComplainNote']=  strip_tags($row['ComplainNote']);                       
                            $data['StatusProblem']=  $status;                       
                            $data['progress']=  $row['ProgressDate'];                       
                            $data['solved']=  $row['SolvedDate'];                       
                            $data['suspen']=  $row['SuspendedDate'];                       
                            $data['unsolved']=  $row['UnsolvedDate'];                       
                            $data['solution']=  $row['SolutionDate'];                       
                            $data['SolutionNote']=  strip_tags($row['SolutionNote']);                       
                            $data['ComplainNote']=  strip_tags($row['ComplainNote']);  
                            
                             $duration = ($this->libfun->selisihwaktu($row['AddedDate'],$row['SolutionDate']));
                            
                             $data['duration']= $duration; 
                                                       
                           
                            cetak($no, $data);
                            
                            $rowuser = $this->rootcause->getuser($row['ID']);    
                            if($rowuser !=='empty'){
                               $data['Computer'] = $rowuser->ComputerName; 
                               user($data);
                            }
                            
                            $rowcreateuser = $this->rootcause->getcreateuser($row['ID']);
                            if($rowcreateuser !=='empty'){
                                $data['userid'] = ($rowcreateuser->UserID =='undefined')?'-':$rowcreateuser->UserID;
                                $data['emailinternal'] = ($rowcreateuser->InternalEmail =='undefined')?'-':$rowcreateuser->InternalEmail;
                                $data['emailexternal'] = ($rowcreateuser->ExternalEmail =='undefined')?'-':$rowcreateuser->ExternalEmail;
                                $data['statususer'] = ($rowcreateuser->StatusUser=='1')?'Create':'Banned';
                                $data['statusinternet'] = ($rowcreateuser->InternetStatus =='1')?'Internet Access':'No Internet';
                                createuser($data);
                             }
                             
                             $rowinstall = $this->rootcause->getinstall($row['ID']); 
                             if($rowinstall !=='empty'){                                  
                                if($rowinstall->SoftwareName=='undefined'){             
                                     $software = '-';  
                                }else{
                                     $software = $rowinstall->SoftwareName;  
                                }
                                
                                $data['software'] = $software;
                                $data['statussoftware'] = ($rowinstall->SoftwareStatus=='1')?'Install':'Uninstall';  
                                software($data);
                                  
                              }
                              
                              
                             $rowcreatefolder = $this->rootcause->getcreatefolder($row['ID']); 
                              if($rowcreatefolder !=='empty'){                               
                              if($rowcreatefolder->FolderName =='undefined'){             
                                   $foldername = '-';  
                              }else{
                                   $foldername = $rowcreatefolder->FolderName;  
                              }  
                               
                               $data['foldername'] = $foldername;
                               $data['folderstatus'] = ($rowcreatefolder->FolderStatus=='1')?'Create':'Delete';
                               createfolder($data); 
                               
                                  
                              }
                              
                              $rowaccessfolder = $this->rootcause->getaccessfolder($row['ID']); 
                              if($rowaccessfolder !=='empty'){                              
                                  
                                if($rowaccessfolder->AccessStatus=='0'){
                                    $accesstatus ='N/A';
                                }else if($rowaccessfolder->AccessStatus=='1'){
                                    $accesstatus ='R/O';
                               }else if($rowaccessfolder->AccessStatus=='2'){
                                    $accesstatus ='R/W';
                               }
                               
                               $data['folderaccess'] =  ($rowaccessfolder->FolderAccess =='undefined')?'-':$rowaccessfolder->FolderAccess;
                               $data['folderstatus'] = $accesstatus;
                               accessfolder($data);
                              }
                              
                              $rowagreement = $this->rootcause->getagreement($row['ID']);
                              if($rowagreement !=='empty'){ 
                                  $data['statusagreement'] = ($rowagreement->StatusAgreement=='1')?'Accept':'Reject';
                                  useragreement($data);
                              }  
                            }else{
                              
                            $data['ID']=  $row['ID'];    
                            $data['User']=  $row['diadd'];  
                            $data['RootName']=  $row['RootName'];                       
                            $data['picoleh']=  $row['picoleh'];                       
                            $data['ComplainNote']=  strip_tags($row['ComplainNote']);                       
                            $data['AddedDate']=  $row['AddedDate'];                       
                            $data['ProblemNote']=  strip_tags($row['ProblemNote']);                       
                            $data['ComplainNote']=  strip_tags($row['ComplainNote']);                       
                            $data['StatusProblem']=  $status;                       
                            $data['progress']=  $row['ProgressDate'];                       
                            $data['solved']=  $row['SolvedDate'];                       
                            $data['suspen']=  $row['SuspendedDate'];                       
                            $data['unsolved']=  $row['UnsolvedDate'];                       
                            $data['solution']=  $row['SolutionDate'];                      
                            $data['SolutionNote']=  strip_tags($row['SolutionNote']);                       
                            $data['ComplainNote']=  strip_tags($row['ComplainNote']);
                           
                            $duration = ($this->libfun->selisihwaktu($row['AddedDate'],$row['SolutionDate']));
                            $data['duration']= $duration; 
                            cetak($no, $data);
                            
                         }
                         
                                           
                         
                        
                         
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
                        <td align="center">(11)</td>
                        <td align="center">(12)</td>
                        <td align="center">(13)</td>
                        <td align="center">(14)</td>
                        <td align="center">(15)</td>
                      
                    </tr>
                    <tr>
                        <td colspan="45">
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



        </script>

    </body>

</html>





