<html>
<head>
    <title>Payslip Special</title>
</head>
<body">
<table width="100%" border="0" cellpadding="4" cellspacing="2" style="border-collapse:collapse;">
    <tr>
        <td><strong>SLIP KHUSUS "PT.TRIAS INDRA SAPUTRA"</strong></td>
    </tr>
    <tr>
        <td>
            <table width="100%" border="0" cellpadding="2" cellspacing="2" style="border-collapse:collapse;">
                <tr>
                    <td width="12%">Periode</td>
                    <td width="2%">:</td>
                    <td width="37%"><?php echo $slip['StartDate'];?> / <?php echo $slip['UntilDate'];?></td>
                    <td width="5%">&nbsp;</td>
                    <td width="12%">Status</td>
                    <td width="2%">:</td>
                    <td width="37%"><?php echo $slip['IDJobGroup'];?>-<?php echo $slip['GroupName'];?></td>
                </tr>
                <tr>
                    <td width="12%">Pegawai</td>
                    <td width="2%">:</td>
                    <td width="37%"><?php echo $slip['IDEmployee'];?>-<?php echo $slip['FullName'];?></td>
                    <td width="5%">&nbsp;</td>
                    <td width="12%">Unit Kerja</td>
                    <td width="2%">:</td>
                    <td width="37%"><?php echo $slip['IDUnitGroup'];?></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td width="35%" align="left" valign="top">&nbsp;
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                            <tr>
                                <td width="35%" align="left"><strong>INCOME</strong></td>
                            </tr>
                            <tr>
                                <td width="40%" align="left">T.H.R</td>
                                <td width="10%" align="center">:</td>
                                <td width="40%" align="right"><?php echo number_format($slip['SumDailySalaryPayment']);?></td>
                            </tr> 
                            <tr>
                                <td width="40%" align="left">&nbsp;</td>
                                <td width="10%" align="right">&nbsp;</td>
                                <td width="40%" align="right">--------------------</td>
                            </tr>
                            <tr>
                                <td width="40%" align="left">TOTAL INCOME</td>
                                <td width="10%" align="center">:</td>
                                <td width="40%" align="right"><?php echo number_format($slip['TotalIncome']);?></td>
                            </tr>
                        </table>
                    </td>
                    <td width="5%" align="center">&nbsp;
                    </td>
                    <td width="35%" align="left" valign="top">&nbsp;
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                            <tr>
                                <td width="35%" align="left"><strong>DEDUCTION</strong></td>
                            </tr> 
                            <tr>
                                <td width="40%" align="left">Potongan Pajak</td>
                                <td width="10%" align="center">:</td>
                                <td width="40%" align="right"><?php echo number_format($slip['LoanPayment']);?></td>
                            </tr>
                            <tr>
                                <td width="40%" align="left">&nbsp;</td>
                                <td width="10%" align="right">&nbsp;</td>
                                <td width="40%" align="right">--------------------</td>
                            </tr>
                            <tr>
                                <td width="40%" align="left">TOTAL DEDUCTION</td>
                                <td width="10%" align="center">:</td>
                                <td width="40%" align="right"><?php echo number_format($slip['TotalDeduction']);?></td>
                            </tr>
                        </table>
                    </td>
                    <td width="5%" align="center">&nbsp;
                    </td>
                    <td width="20%" align="left" valign="top">&nbsp;
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                            <tr>
                                <td width="35%" align="left"><strong>TAKE HOME PAY</strong></td>
                            </tr>
                            <tr>
                                <td width="40%" align="center"><?php echo number_format($slip['TakeHomePay']);?></td>
                                <td width="30%" align="left">&nbsp;</td>
                                <td width="30%" align="right">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="40%" align="center">====================</td>
                                <td width="10%" align="center">&nbsp;</td>
                                <td width="40%" align="right">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="40%" align="center">TTD</td>
                                <td width="10%" align="center">&nbsp;</td>
                                <td width="40%" align="right">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr >
                <!-- TOTAL -->                
            </table>	
        </td>
    </tr>
    <tr>
        <td>
        <small>#<?php echo $slip['Footer'];?>#</small>
<!--
            <table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <tr>
                    <td width="100%">
                    <small>
                    <strong>Merujuk pada IM No: TIS/006/IM-DIR/I/10</strong><br>
                    Dengan ini kami yang bertanda tangan di samping ini memberikan pernyataan sebagai  berikut, Bahwa:
                    <ol>
                    <li>Mengetahui dan Menyetujui bahwa penyalahgunaan Slip Gaji akan berakibat pemutusan hubungan kerja tanpa syarat akibat pelanggaran  berat dan tidak akan  mendapatkan apapun dari perusahaan.</li>
                    <li>Apabila ada kesalahan dalam Slip Gaji maupun jumlah gaji yang telah di terima,  utk permohonan peninjauan harap di lakukan secara tertulis, di lengkapi oleh  dokumen pendukung seperti copy rek bank, copy slip gaji dll di berikan di dalam amplop tertutup ke:<br>
<center>Up: HR Manager, Perihal: Review Penggajian,  Nama Pemohon:  ____________________</center>
<br> 
Setelah Permohonan Peninjauan di terima oleh HR Manager,  maka peninjauan akan di lakukan selambat lambatnya 14 hari dari permohonan</li>
<li>Telah Menerima Slip Gaji dalam kondisi tersegel dan baik</li>
                    <li> Dalam waktu 14 hari kalender, tidak ada laporan tentang kesalahan  dari Slip  Gaji tersebut, maka Slip Gaji tersebut di anggap benar dan segala permohonan  perbaikan setelah penerimaan slip gaji tidak akan  di layani oleh pihak  manajemen.</li>
                    </ol>
                    </small>
                    <td>
                </tr>
            </table>
-->
        </td>
    </tr>
</table>
</body>
</html>


