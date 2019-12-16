<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <title>ATL</title>
    </head>
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
                    <td>PT.TRIAS INDRA SAPUTRA<br/><small>Jl.Kamal Muara VII Blok A No 6<br/><u>JAKARTA 14470</u></small></td>
                    <td></td>   

                    <td><small>No. Dok. : FR-PD-PP-04.15 <br/> No. Rev.0</small></td> 
                </tr>               
            </table>
            <table style="width:100% ;table-layout:fixed"  border="0" cellpadding="0" cellspacing="0" class="warna">

                <tr>
                    <td class="warna" height="24px" colspan="6" valign="top" align='center'><div align="center"><strong>ABSENSI TIDAK LENGKAP </strong></div></td>
                </tr>
                <tr>
                    <td class="warna" height="22px" valign="top">Hari/Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="warna" colspan="2" valign="top"><?php echo $day.','.$incompletedate; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="warna" valign="top">Departement</td>
                    <td class="warna" colspan="2" valign="top"><?php echo $departement; ?></td>
                </tr>
                <tr>
                    <td class="warna" height="22px" valign="top">Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="warna" colspan="2" valign="top"><?php echo $name; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                    <td class="warna" valign="top">Jabatan</td>
                    <td class="warna" colspan="2" valign="top"><?php echo $position; ?></td>
                </tr>
                <tr>
                    <td class="warna" rowspan="2" valign="top">Jam Masuk <br><?php echo $timein ?></br> </td>
                    <td class="warna" height="25px" colspan="2" valign="top" align='center'><div align="center">Paraf Satpam </div></td>
                    <td class="warna" rowspan="2" valign="top">Jam Keluar <br><?php echo $timeout ?></br> </td>
                    <td class="warna" colspan="2" valign="top" align='center'><div align="center">Paraf Satpam </div></td>
                </tr>
                <tr>
                    <td class="warna" height="89px" colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                    <td class="warna" colspan="2" valign="top"><!--DWLayoutEmptyCell-->&nbsp;</td>
                </tr>
                <tr>
                    <td class="warna" height="97px" colspan="6" valign="top">Keterangan : <br/><?php echo $note ?></td>
                </tr>
                <tr>
                    <td class="warna" height="34px" colspan="3" valign="top" align='center'><div align="center">Di Ajukan Oleh</div></td>
                    <td class="warna" colspan="3" valign="top" align='center'><div align="center">Di Ketahui Oleh</div></td>
                </tr>
                <tr>
                    <td class="warna" colspan="3" rowspan="2" valign="bottom" align='center'><div align="center"><?php echo $name ?></td>
                    <td class="warna" height="128px" valign="bottom" align='center'><div align="center"><?php echo $parent ?></td>
                    <td class="warna" colspan="2" valign="top"></td>
                </tr>
                <tr>
                    <td class="warna" height="26px" valign="top" align='center'><div align="center">Head Of Departement</div></td>
                    <td class="warna" colspan="2" valign="top" align='center'><div align="center">Human Resources Manager</div></td>
                </tr>

            </table>       
    </body>
</html>


