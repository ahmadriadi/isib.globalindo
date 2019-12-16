<style>
    table.printlpermit{
        border-collapse: collapse;
        /*border: #000 solid thin;*/
        font-family: Arial;
        font-size: 11px;
        /*height: 700px;*/
    }
    td.tbl{
        padding: 5px;
    }
    .atas_border{
        height: 20px;
        border-top: #000 solid thin;
    }
    .bawah_border{
        height: 20px;
        border-bottom: #000 solid thin;
    }
    .kiri_border{
        height: 20px;
        border-left: #000 solid thin;
    }
    .kanan_border{
        height: 20px;
        border-right: #000 solid thin;
    }
    .head{
        padding: 10px;
    }
    .title{
        padding: 10px;
        font-size: 15px;
    }
    .notenya{
        font-size: 11px;
    }
</style>
<?php $data = $data->row();?>
<table width="100%" class=" printlpermit">
    <tr >
            <td width="75%" colspan="3" class="tbl head atas_border kiri_border"  align="left" valign="middle">
                PT TRIAS INDRA SAPUTRA <br>
                JAKARTA
            </td>
            <td width="25%"  class="tbl head atas_border kanan_border" align="left" valign="middle">
                No. Dok :<br>
                No. Rev.: 
            </td>
        </tr>
        <tr>
            <td colspan="4" class="tbl title kiri_border kanan_border" align="center" valign="middle"><b>SURAT PERJALANAN DINAS</b></td>
        </tr>
    <tbody>
        <?php 
        $per    = $this->otr->get_personal($data->IDEmployee)->row();
        ?>
        <tr>
            <td class="tbl atas_border kiri_border" valign="middle">Nama: <?php echo $per->FullName;?></td>
            <td class="tbl atas_border kiri_border" valign="middle">NIP: <?php echo $per->IDEmployee;?></td>
            <td class="tbl atas_border kiri_border" valign="middle">Departemen: <?php echo $per->IDDepartement;?></td>
            <td class="tbl atas_border kiri_border kanan_border" valign="middle">Jabatan: <?php echo $per->IDJobPosition?></td>
        </tr>
        <tr>
            <td colspan="2" class="tbl atas_border kiri_border" valign="middle" align="center">Keluar</td>
            <td colspan="2" class="tbl atas_border kiri_border kanan_border" valign="middle" align="center">Masuk</td>
        </tr>
        <tr>
            <td colspan="2" class="tbl atas_border kiri_border" valign="middle">
                <?php $Out = explode(" ", $data->OfficialTravelDate);?>
                <table>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td><?php echo date('d-m-Y',strtotime($Out['0']));?></td>
                    </tr>
                </table>               
            </td>
            <td colspan="2" class="tbl atas_border kiri_border kanan_border" valign="middle">
                <?php $in = explode(" ", $data->UntilDate);?>
                <table>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td><?php echo date('d-m-Y',strtotime($in['0']));?></td>
                    </tr>
                </table> 
            </td>
        </tr>
        <tr>
            <td colspan="4" class=" atas_border kiri_border kanan_border" valign="middle">
                <table>
                    <tr>
                        <td>Tujuan</td>
                        <td>:</td>
                        <td><?php echo $data->Note;?></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="tbl atas_border kiri_border kanan_border" valign="middle">
                Nomor Kendaraan: <?php echo $data->VehicleNo;?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="tbl atas_border kiri_border" valign="middle">Diajukan Oleh:</td>
            <td colspan="2" class="tbl atas_border kiri_border kanan_border" valign="middle">Diketahui Oleh:</td>
        </tr>
        <tr>
           <td colspan="2" height="100px;" class="tbl atas_border kiri_border bawah_border" valign="middle" align="center">
                [ <?php echo $data->AddedDate;?> ]
                <br>
                <br>
                <?php echo $per->FullName; ?>
            </td>
            <td colspan="2" class="tbl atas_border kiri_border kanan_border bawah_border" valign="middle" align="center">
                <?php $conf    = $this->otr->get_personal($data->ConfirmBy)->row(); 
                echo "[ $data->ConfirmDate ]";
                ?>
                <br>
                <br>
                <?php echo $conf->FullName;?>
            </td>
        </tr>
    </tbody>
</table>
