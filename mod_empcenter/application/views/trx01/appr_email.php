<style>
    table{
        font-family: Arial;
        font-size: 16px;
    }
    .row1{
        background: skyblue;
    }
    .row2{
        background: steelblue;
    }
</style>
<?php if ($step == "1"){?>
<table >
    <tr class="row1">
        <td>NIP</td><td>:</td><td><?php echo $pengaju->IDEmployee;?></td>
    </tr>
    <tr class="row2">
        <td>Nama</td><td>:</td><td><?php echo $pengaju->FullName;?></td>
    </tr>
    <tr class="row1">
        <td>Divisi</td><td>:</td><td><?php echo $pengaju->IDDepartement;?></td>
    </tr>
    <tr class="row2">
        <td>Jabatan</td><td>:</td><td><?php echo $pengaju->IDUnitGroup;?></td>
    </tr>
</table>
Nama tersebut di atas meminta Anda untuk menggantikannya selama cuti sebagaimana detail berikut:<br>
<table width='100%'>
    <tr class="row2">
        <td align='center' width='20%' >Jenis Cuti</td>
        <td align='center' width='10%' >Dari</td>
        <td align='center' width='10%' >Sampai</td>
        <td align='center' width='5%' >Total</td>
        <td align='center'>Alasan</td>
    </tr>
    <tr class="row1">
        <?php 
        switch ($det->Jenis){
            case "SL"   : $jenis = "Cuti Sakit"; break;
            case "AL"   : $jenis = "Cuti Tahunan"; break;
            case "MRL"  : $jenis = "Cuti Menikah"; break;
            case "MTL"  : $jenis = "Cuti Melahirkan"; break;
            case "CL"   : $jenis = "Cuti Duka Cita"; break;
            case "OL"   : $jenis = "Cuti Tidak Dibayarkan"; break;
            case "CIR"  : $jenis = "Cuti Khitanan"; break;
        }
        echo "<td align='center'>".$jenis."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiDari))."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiSampai))."</td>";
        echo "<td align='center'>".$det->TotalCuti." hari</td>";
        echo "<td align='left'>".$det->Alasan."</td>";
        ?>
    </tr>
</table>
<br>
Klik "Accept" jika Anda setuju atau klik "Reject" jika tidak setuju, kemudian isi alasannya.<br>
<a style="color:green; font-weight: bold;" href="http://192.168.0.62/~triasnet/triasnet/mod_empcenter/index.php/trx01/home/load_confirm/<?php echo $ckode."/".$step."/".$userid;?>/email">ACCEPT</a>
<a style="color:red;   font-weight: bold;" href="http://192.168.0.62/~triasnet/triasnet/mod_empcenter/index.php/trx01/home/check_confirm/false/<?php echo $ckode."/".$step."/".$userid;?>">REJECT</a>
<?php 
}
if ($step == "2"){
    ?>
<table>
    <tr class="row1">
        <td>NIP</td><td>:</td><td><?php echo $pengaju->IDEmployee;?></td>
    </tr>
    <tr class="row2">
        <td>Nama</td><td>:</td><td><?php echo $pengaju->FullName;?></td>
    </tr>
    <tr class="row1">
        <td>Divisi</td><td>:</td><td><?php echo $pengaju->IDDepartement;?></td>
    </tr>
    <tr class="row2">
        <td>Jabatan</td><td>:</td><td><?php echo $pengaju->IDUnitGroup;?></td>
    </tr>
</table>
Nama tersebut di atas mengajukan permohonan cuti sebagaimana detail berikut:<br>
<table width='100%'>
    <tr class="row2">
        <td align='center' width='20%' >Jenis Cuti</td>
        <td align='center' width='10%' >Dari</td>
        <td align='center' width='10%' >Sampai</td>
        <td align='center' width='5%'  >Total</td>
        <td align='center' width='35%' >Alasan</td>
        <td align='center' width='20%' >Pengganti</td>
    </tr>
    <tr class="row1">
        <?php 
        switch ($det->Jenis){
            case "SL"   : $jenis = "Cuti Sakit"; break;
            case "AL"   : $jenis = "Cuti Tahunan"; break;
            case "MRL"  : $jenis = "Cuti Menikah"; break;
            case "MTL"  : $jenis = "Cuti Melahirkan"; break;
            case "CL"   : $jenis = "Cuti Duka Cita"; break;
            case "OL"   : $jenis = "Cuti Tidak Dibayarkan"; break;
            case "CIR"  : $jenis = "Cuti Khitanan"; break;
        }
        echo "<td align='center'>".$jenis."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiDari))."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiSampai))."</td>";
        echo "<td align='center'>".$det->TotalCuti." hari</td>";
        echo "<td align='left'>".$det->Alasan."</td>";
        $r = $this->lvm->get_employee($det->IDPengganti)->row();
        echo "<td align='left'>".$r->FullName."</td>";
        ?>
    </tr>
</table>
<br>
Klik "Accept" jika Anda setuju atau klik "Reject" jika tidak setuju, kemudian isi alasannya.<br>
<a style="color:green; font-weight: bold;" href="http://192.168.0.62/~triasnet/triasnet/mod_empcenter/index.php/trx01/home/load_confirm/<?php echo $ckode."/".$step."/".$userid;?>/email">ACCEPT</a>
<a style="color:red;   font-weight: bold;" href="http://192.168.0.62/~triasnet/triasnet/mod_empcenter/index.php/trx01/home/check_confirm/false/<?php echo $ckode."/".$step."/".$userid;?>">REJECT</a>

<?php
}
if ($step == "3"){
?>

<table>
    <tr class="row1">
        <td>NIP</td><td>:</td><td><?php echo $pengaju->IDEmployee;?></td>
    </tr>
    <tr class="row2">
        <td>Nama</td><td>:</td><td><?php echo $pengaju->FullName;?></td>
    </tr>
    <tr class="row1">
        <td>Divisi</td><td>:</td><td><?php echo $pengaju->IDDepartement;?></td>
    </tr>
    <tr class="row2">
        <td>Jabatan</td><td>:</td><td><?php echo $pengaju->IDUnitGroup;?></td>
    </tr>
</table>
Nama tersebut di atas mengajukan permohonan cuti sebagaimana detail berikut:<br>
<table width='100%'>
    <tr class="row2">
        <td align='center' width='20%' >Jenis Cuti</td>
        <td align='center' width='10%' >Dari</td>
        <td align='center' width='10%' >Sampai</td>
        <td align='center' width='5%'  >Total</td>
        <td align='center' width='25%' >Alasan</td>
        <td align='center' width='15%' >Pengganti</td>
        <td align='center' width='15%' >Disetujui</td>
    </tr>
    <tr class="row1">
        <?php 
        switch ($det->Jenis){
            case "SL"   : $jenis = "Cuti Sakit"; break;
            case "AL"   : $jenis = "Cuti Tahunan"; break;
            case "MRL"  : $jenis = "Cuti Menikah"; break;
            case "MTL"  : $jenis = "Cuti Melahirkan"; break;
            case "CL"   : $jenis = "Cuti Duka Cita"; break;
            case "OL"   : $jenis = "Cuti Tidak Dibayarkan"; break;
            case "CIR"  : $jenis = "Cuti Khitanan"; break;
        }
        echo "<td align='center'>".$jenis."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiDari))."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiSampai))."</td>";
        echo "<td align='center'>".$det->TotalCuti." hari</td>";
        echo "<td align='left'>".$det->Alasan."</td>";
        $r = $this->lvm->get_employee($det->IDPengganti)->row();
        echo "<td align='left'>".$r->FullName."</td>";
        $r = $this->lvm->get_employee($pengaju->IDEmployeeParent)->row();
        echo "<td align='left'>".$r->FullName."</td>";
        ?>
    </tr>
</table>
<br>
Klik "Accept" jika Anda setuju atau klik "Reject" jika tidak setuju, kemudian isi alasannya.<br>
<a style="color:green; font-weight: bold;" href="http://192.168.0.62/~triasnet/triasnet/mod_empcenter/index.php/trx01/home/load_confirm/<?php echo $ckode."/".$step."/".$userid;?>/email">ACCEPT</a>
<a style="color:red;   font-weight: bold;" href="http://192.168.0.62/~triasnet/triasnet/mod_empcenter/index.php/trx01/home/check_confirm/false/<?php echo $ckode."/".$step."/".$userid;?>">REJECT</a>
<?php 
}
if ($step == "f"){
?>

Permohonan cuti Anda yang diajukan pada tanggal <?php echo date('d-m-Y',strtotime($det->TglPengajuan));?> sebagaimana detail berikut:<br>
<table width='100%'>
    <tr class="row2">
        <td align='center' width='25%' >Jenis Cuti</td>
        <td align='center' width='20%' >Dari</td>
        <td align='center' width='20%' >Sampai</td>
        <td align='center' width='5%'  >Total</td>
        <td align='center' width='30%' >Alasan</td>
    </tr>
    <tr class="row1">
        <?php 
        switch ($det->Jenis){
            case "SL"   : $jenis = "Cuti Sakit"; break;
            case "AL"   : $jenis = "Cuti Tahunan"; break;
            case "MRL"  : $jenis = "Cuti Menikah"; break;
            case "MTL"  : $jenis = "Cuti Melahirkan"; break;
            case "CL"   : $jenis = "Cuti Duka Cita"; break;
            case "OL"   : $jenis = "Cuti Tidak Dibayarkan"; break;
            case "CIR"  : $jenis = "Cuti Khitanan"; break;
        }
        echo "<td align='center'>".$jenis."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiDari))."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiSampai))."</td>";
        echo "<td align='center'>".$det->TotalCuti." hari</td>";
        echo "<td align='left'>".$det->Alasan."</td>";

        ?>
    </tr>
</table>
Telah diterima dan disetujui oleh :
<table>
    <tr class="row2">
        <td align="center">
            Pengganti
        </td>
        <td align="center">
            Atasan
        </td>
        <td align="center">
            HRD
        </td>
    </tr>
    <tr class="row1">
        <?php 
            $r = $this->lvm->get_employee($det->IDPengganti)->row();
            echo "<td align='left'>".$r->FullName."</td>";
            $r = $this->lvm->get_employee($pengaju->IDEmployeeParent)->row();
            echo "<td align='left'>".$r->FullName."</td>";
            echo "<td align='left'>HRD</td>"
        ?>
    </tr>
</table>
Simpan email ini sebagai bukti persetujuan permohonan cuti Anda.
<?php
}
if($step == "reject"){
    if ($from_step == "1"){
?>
Permohonan cuti Anda yang diajukan pada tanggal <?php echo date('d-m-Y',  strtotime($det->TglPengajuan));?> dengan detail sebagai berikut :
<table width='100%'>
    <tr class="row2">
        <td align='center' width='25%' >Jenis Cuti</td>
        <td align='center' width='20%' >Dari</td>
        <td align='center' width='20%' >Sampai</td>
        <td align='center' width='5%'  >Total</td>
        <td align='center' width='30%' >Alasan</td>
    </tr>
    <tr class="row1">
        <?php 
        switch ($det->Jenis){
            case "SL"   : $jenis = "Cuti Sakit"; break;
            case "AL"   : $jenis = "Cuti Tahunan"; break;
            case "MRL"  : $jenis = "Cuti Menikah"; break;
            case "MTL"  : $jenis = "Cuti Melahirkan"; break;
            case "CL"   : $jenis = "Cuti Duka Cita"; break;
            case "OL"   : $jenis = "Cuti Tidak Dibayarkan"; break;
            case "CIR"  : $jenis = "Cuti Khitanan"; break;
        }
        echo "<td align='center'>".$jenis."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiDari))."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiSampai))."</td>";
        echo "<td align='center'>".$det->TotalCuti." hari</td>";
        echo "<td align='left'>".$det->Alasan."</td>";

        ?>
    </tr>
</table>
telah ditolak oleh  saudara/i <?php echo $this->lvm->get_employee($det->IDPengganti)->row()->FullName;?>
&nbsp;dengan alasan :
<p>"<?php echo $r_reason;?>"</p><i><?php echo $tgl_reject;?></i>
Segera hubungi rekan Anda yang lainnya dan/atau perbarui keterangan cuti Anda.
<?php
    }
    if ($from_step == "2"){
?>
Permohonan cuti Anda yang diajukan pada tanggal <?php echo date('d-m-Y',  strtotime($det->TglPengajuan));?> dengan detail sebagai berikut :
<table width='100%'>
    <tr class="row2">
        <td align='center' width='25%' >Jenis Cuti</td>
        <td align='center' width='10%' >Dari</td>
        <td align='center' width='10%' >Sampai</td>
        <td align='center' width='5%'  >Total</td>
        <td align='center' width='25%' >Alasan</td>
        <td align='center' width='25%' >Pengganti</td>
    </tr>
    <tr class="row1">
        <?php 
        switch ($det->Jenis){
            case "SL"   : $jenis = "Cuti Sakit"; break;
            case "AL"   : $jenis = "Cuti Tahunan"; break;
            case "MRL"  : $jenis = "Cuti Menikah"; break;
            case "MTL"  : $jenis = "Cuti Melahirkan"; break;
            case "CL"   : $jenis = "Cuti Duka Cita"; break;
            case "OL"   : $jenis = "Cuti Tidak Dibayarkan"; break;
            case "CIR"  : $jenis = "Cuti Khitanan"; break;
        }
        echo "<td align='center'>".$jenis."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiDari))."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiSampai))."</td>";
        echo "<td align='center'>".$det->TotalCuti." hari</td>";
        echo "<td align='left'>".$det->Alasan."</td>";
        echo "<td align='left'>".$this->lvm->get_employee($det->IDPengganti)->row()->FullName."</td>";

        ?>
    </tr>
</table>
telah ditolak oleh atasan Anda saudara/i <?php echo $this->lvm->get_employee($pengaju->IDEmployeeParent)->row()->FullName;?>
&nbsp;dengan alasan :
<p>"<?php echo $r_reason;?>"</p><i><?php echo $tgl_reject;?></i>
Hubungi yang bersangkutan untuk keterangan lebih lanjut.
<?php
    }
    if ($from_step == "3"){
?>
Permohonan cuti Anda yang diajukan pada tanggal <?php echo date('d-m-Y',  strtotime($det->TglPengajuan));?> dengan detail sebagai berikut :
<table width='100%'>
    <tr class="row2">
        <td align='center' width='25%' >Jenis Cuti</td>
        <td align='center' width='10%' >Dari</td>
        <td align='center' width='10%' >Sampai</td>
        <td align='center' width='5%'  >Total</td>
        <td align='center' >Alasan</td>
        <td align='center' >Pengganti</td>
        <td align='center' >Disetujui</td>
    </tr>
    <tr class="row1">
        <?php 
        switch ($det->Jenis){
            case "SL"   : $jenis = "Cuti Sakit"; break;
            case "AL"   : $jenis = "Cuti Tahunan"; break;
            case "MRL"  : $jenis = "Cuti Menikah"; break;
            case "MTL"  : $jenis = "Cuti Melahirkan"; break;
            case "CL"   : $jenis = "Cuti Duka Cita"; break;
            case "OL"   : $jenis = "Cuti Tidak Dibayarkan"; break;
            case "CIR"  : $jenis = "Cuti Khitanan"; break;
        }
        echo "<td align='center'>".$jenis."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiDari))."</td>";
        echo "<td align='center'>".date('d-m-Y',strtotime($det->TglCutiSampai))."</td>";
        echo "<td align='center'>".$det->TotalCuti." hari</td>";
        echo "<td align='left'>".$det->Alasan."</td>";
        echo "<td align='left'>".$this->lvm->get_employee($det->IDPengganti)->row()->FullName."</td>";
        $idparent = $this->lvm->get_employee($userid)->row()->IDEmployeeParent;
        echo "<td align='left'>".$this->lvm->get_employee($idparent)->row()->FullName."</td>";

        ?>
    </tr>
</table>
telah ditolak oleh <?php echo "HRD";?>
&nbsp;dengan alasan :
<p>"<?php echo $r_reason;?>"</p><i><?php echo $tgl_reject;?></i>

<?php
    }
}
?>