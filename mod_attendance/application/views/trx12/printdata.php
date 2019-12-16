<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>LEAVE (CUTI)</title></head>
    <style>
        .warna
        {
            border:1px solid black;
        }
    </style>
    <body>
        <div align="center">
            <table width="100%" >
                <tr>
                    <td>PT.TRIAS INDRA SAPUTRA<small><br/>
                            <u>JAKARTA</u></small></td>
                    <td colspan="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td> 
                    <td><small>No. Dok. : FR-PD-PP-04.03 <br/> 
                            No. Rev.1</small></td> 
                </tr>               
            </table>
            <table width="100%" >
                <tr>
                    <td colspan="1" align="center"><b>APPLICATION FOR LEAVE</b></td>                    
                </tr> 
                <tr>
                    <td align="center"><b>FORMULIR PERMOHONAN CUTI</b></td>                    
                </tr>               
            </table>
            <table style="width:100% ;table-layout:fixed"  border="0" cellpadding="0" cellspacing="0" class="warna">
                <tr>
                    <td class="warna" colspan="1" height="22px" valign="top">Name<br /><i>Nama</i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="warna" colspan="1" ><?php echo $name;?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="warna" colspan="1" valign="top">Staff ID No.<br/><i>No. Identitas Karyawan</i></td>
                    <td class="warna" colspan="1" ><?php echo $nip;?> &nbsp;</td>
                </tr>
                <tr>
                    <td class="warna" colspan="1" height="22px" valign="top">Position<br/><i>Jabatan</i> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="warna" colspan="1" ><?php echo $position; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="warna" colspan="1" valign="top">Commenced Date<br/><i>Tgl.Masuk</i></td>
                    <td class="warna" colspan="1" ><?php echo $hiredate; ?>&nbsp;</td>
                </tr>
                <tr>
                    <td class="warna" colspan="1" height="22px" valign="top">Departemen<br/>
                        <em>Bagian</em>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="warna" colspan="1" ><?php echo $dept ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="warna" colspan="1" valign="top">Un-Taken Leave <br/>
                        <i>Cuti yang belum di ambil </i></td>
                    <td class="warna" colspan="1"><?php echo $amountleave; ?>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table width="100%"  >
                            <tr>
                                <td>Type of Leave Request<br/><i>Jenis Permohonan Cuti</i></td>
                                <td>(<?php echo $type1 ?>)</td>	
                                <td>Annual Leave<br/><i>Cuti Tahunan</i></td>
                                <td>(<?php echo $type2 ?>)</td>	
                                <td>Maternity Leave<br/><i>Cuti Melahirkan</i></td>
                                <td>(<?php echo $type3 ?>)</td>	
                                <td>Marriage Leave<br/><i>Cuti Pernikahan</i></td>	
                            </tr>
                            <tr>
                                <td colspan="1"></td>
                                <td>(<?php echo $type4 ?>)</td>
                                <td>Condolence Leave<br/><i>Cuti Duka Cita</i></td>
                                <td>(<?php echo $type5 ?>)</td>	
                                <td>Sick Leave<br/><i>Cuti Sakit</i></td>
                                <td>(<?php echo $type6 ?>)</td>	
                                <td>Unpaid Leave<br/><i>Cuti yang tidak dibayarkan</i></td>	
                            </tr>
                            <tr>
                                <td colspan="1"></td>
                                <td>(<?php echo $type7 ?>)</td>
                                <td>Circumcision Leave<br/><i>Cuti Khitanan</i></td>
                                <td>(<?php echo $type8 ?>)</td>	
                                <td>Other Leave<br/><i>Cuti Lain-lain</i></td>                          
                            </tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr>
                                <td>Leave Request<br/><i>Permohonan Cuti</i></td>                            	
                                <td colspan="2">From &nbsp; <?php echo $fromdate ?><br/><i>Dari</i></td>                            
                                <td colspan="2">Until &nbsp; <?php echo $untildate ?><br/><i>Sampai</i></td>                            
                                <td colspan="2">Total&nbsp; <?php echo $sumleave ?><br/><i>Jumlah</i></td>	
                            </tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="warna"  colspan="2" valign="top">Outstanding Leave after deducated with leave request<br/><i>Sisa Cuti setelah dikurangi permohonan cuti</i></td>
                    <td class="warna"  colspan="2" valign="top"><div align="center"><?php echo $cutleave;  ?>Days<br/><i>Hari</i></div></td>
                </tr>
                <tr>
                    <td class="warna"  colspan="2" valign="top">Reason of Leave <br/><i>Alasan Cuti</i></td>
                    <td class="warna"  colspan="2" valign="top"><div align="left"><?php echo $reasonleave; ?></div></td>
                </tr>
                <tr>
                    <td class="warna"  colspan="2" valign="top">Person in Charger during leave<br/><i>Pengganti selama cuti</i></td>
                    <td class="warna"  colspan="1" valign="top"><div align="left">Name : <?php echo $pengganti; ?></div></td>
                    <td class="warna"  colspan="1" valign="top"><div align="left">Sign : <?php echo $dateacccharge; ?></div></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table width="100%" >  
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr>
                                <td>Jakarta,&nbsp;<?php echo $daterequest; ?><br/>Proposed By,<br/><i>Diajukan Oleh</i></td> 
                            </tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><td colspan="4">&nbsp;</td></tr>
                            <tr><td colspan="4">(<?php echo $name ?>)</td></tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="warna" colspan="1" height="22px" valign="top"><div align="center">Acknowledged by<br/><i>Diketahui Oleh</i></div></td>
                    <td class="warna" colspan="1" valign="top"><div align="center">Approved by<br/><i>Disetujui oleh</i></div></td>
                    <td class="warna" colspan="1" valign="top"><div align="center">Verified & Recorded by<br/><i>Dicek & dicatat Oleh</i></div></td>                       
                    <td class="warna" colspan="1" valign="top"><div align="center">Approved by<br/><i>Disetujui oleh</div></td>
                </tr>
                <tr>
                    <td class="warna" colspan="1" height="22px" ><?php echo $supervisor ?><br/>&nbsp;</td>
                    <td class="warna" colspan="1"><?php echo $parent ?><br/>&nbsp;</td>
                    <td class="warna" colspan="1"><?php echo $hrd ?><br/>&nbsp;</td>                       
                    <td class="warna" colspan="1"><?php echo $hrm ?><br/>&nbsp;</td>
                </tr>
                <tr>
                    <td class="warna" colspan="1" height="22px" valign="top"><div align="center">Supervisor</div><br/>Date :</td>
                    <td class="warna" colspan="1" valign="top"><div align="center">Head of Departement</div><br/>Date :<?php echo $dateaccparent ?></td>
                    <td class="warna" colspan="1" valign="top"><div align="center">HR Admin</div><br/>Date : <?php echo $dateacchrd ?></td>                       
                    <td class="warna" colspan="1" valign="top"><div align="center">Human Resources Manager</div><br/>Date :</td>
                </tr>
            </table>     
        </div>
    </body>
</html>


