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
                No. Dok : FR-PD-PP-04.01<br>
                No. Rev.: 2
            </td>
        </tr>
        <tr>
            <td colspan="4" class="tbl title kiri_border kanan_border" align="center" valign="middle"><b>IZIN MENINGGALKAN KANTOR</b></td>
        </tr>
    <tbody>
        <?php 
        $per    = $this->lpt->get_personal($data->IDEmployee)->row();
        ?>
        <tr>
            <td class="tbl atas_border kiri_border" valign="middle">Nama: <?php echo $per->FullName;?></td>
            <td class="tbl atas_border kiri_border" valign="middle">NIP: <?php echo $per->IDEmployee;?></td>
            <td class="tbl atas_border kiri_border" valign="middle">Departemen: <?php echo $per->IDDepartement;?></td>
            <td class="tbl atas_border kiri_border kanan_border" valign="middle">Jabatan: <?php echo $per->IDJobPosition?></td>
        </tr>
        <tr>
            <td class="tbl atas_border kiri_border" valign="middle" align="center">Keluar</td>
            <td class="tbl atas_border kiri_border" valign="middle" align="center">Paraf Satpam</td>
            <td class="tbl atas_border kiri_border" valign="middle" align="center">Masuk</td>
            <td class="tbl atas_border kiri_border kanan_border" valign="middle" align="center">Paraf Satpam</td>
        </tr>
        <tr>
            <td class="tbl atas_border kiri_border" valign="middle">
                <?php $Out = explode(" ", $data->OutDate);?>
                <table>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td><?php echo date('d-m-Y',strtotime($Out['0']));?></td>
                    </tr>
                    <tr>
                        <td>Jam</td>
                        <td>:</td>
                        <td><?php echo $Out['1'];?></td>
                    </tr>
                </table>
            </td>
            <td class="tbl atas_border kiri_border" valign="middle">
                
            </td>
            <td class="tbl atas_border kiri_border" valign="middle">
                <?php $in = explode(" ", $data->InDate);?>
                <table>
                    <tr>
                        <td>Tanggal</td>
                        <td>:</td>
                        <td><?php echo date('d-m-Y',strtotime($in['0']));?></td>
                    </tr>
                    <tr>
                        <td>Jam</td>
                        <td>:</td>
                        <td><?php echo $in['1'];?></td>
                    </tr>
                </table> 
            </td>
            <td class="tbl atas_border kiri_border kanan_border" valign="middle">
                
            </td>
        </tr>
        <tr>
            <td colspan="4" class=" atas_border kiri_border kanan_border" valign="middle">
                <table width="1000px">
                    <tr>
                        <td>Tujuan :</td>
                    </tr>
                    <tr>
                        <td width='20%'>
                            <input type="checkbox" <?php echo $data->Necessity == "1"?"checked='checked'":"";?> >Keperluan Pribadi
                        </td>
                        <td width='80%'>
                            <input type="checkbox" <?php echo $data->Necessity == "2"?"checked='checked'":"";?> >Keperluan Kantor
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="notenya">
                            <?php echo $data->Note;?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>

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
                <?php $conf    = $this->lpt->get_personal($data->ConfirmBy)->row(); 
                echo "[ $data->ConfirmDate ]";
                ?>
                <br>
                <br>
                <?php echo $conf->FullName;?>
            </td>
        </tr>
    </tbody>
</table>
<hr>
<table border="1" width="100%" style="border-collapse: collapse;">
    <thead>
        <tr>
            <td>No</td>
            <td>Tanggal</td>
            <td>Jam</td>
            <td>Keterangan</td>
            <td>Tempat</td>
        </tr>
    </thead>
    <tbody>
        <?php 
        $lpdate = explode(" ",$data->OutDate);
        $tap    = $this->lpt->get_tap($data->IDEmployee, $lpdate['0'])->result();
        $i=0;
        foreach($tap as $t){
            $i++;
            $dir    = $t->Direction == "0" ? "OUT" : "IN";
            $loc    = $t->Location  == "0" ? "Unknown" : ($t->Location == "1" ? "Kapuk" : "Bitung");
            echo "<tr>";
            echo "<td>$i</td>";
            echo "<td>".$t->EnrollDate."</td>";
            echo "<td>".$t->EnrollTime."</td>";
            echo "<td>".$dir."</td>";
            echo "<td>".$loc."</td>";
            echo "</tr>";            
        }
        ?>
    </tbody>
</table>

