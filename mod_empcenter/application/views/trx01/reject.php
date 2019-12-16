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
<b>jangan reload halaman ini!</b><hr>
<?php if ($step == '1'){ ?>
<table >
<tr class='row1'>
    <td>NIP</td><td>:</td><td><?php echo $r->IDEmployee;?></td>
</tr>
<tr class='row2'>
    <td>Nama</td><td>:</td><td><?php echo $r->FullName;?></td>
</tr>
<tr class='row1'>
    <td>Divisi</td><td>:</td><td><?php echo $r->IDDepartement;?></td>
</tr>
<tr class='row2'>
    <td>Jabatan</td><td>:</td><td><?php echo $r->IDUnitGroup;?></td>
</tr>
</table>
Anda tidak menyetujui permohonan cuti dari nama tersebut di atas sebagaimana detail berikut :
<table width='100%'>
    <tr class='row2'>
        <td align='center' width='20%' >Jenis Cuti</td>
        <td align='center' width='10%' >Dari</td>
        <td align='center' width='10%' >Sampai</td>
        <td align='center' width='5%' >Total</td>
        <td align='center'>Alasan</td>
    </tr>
    <tr class='row1'>
    <?php
        switch ($det->Jenis){
            case 'SL'   : $jenis = 'Cuti Sakit'; break;
            case 'AL'   : $jenis = 'Cuti Tahunan'; break;
            case 'MRL'  : $jenis = 'Cuti Menikah'; break;
            case 'MTL'  : $jenis = 'Cuti Melahirkan'; break;
            case 'CL'   : $jenis = 'Cuti Duka Cita'; break;
            case 'OL'   : $jenis = 'Cuti Tidak Dibayarkan'; break;
            case 'CIR'  : $jenis = 'Cuti Khitanan'; break;
        }
        echo "<td align='center'>".$jenis."</td>";
        echo "<td align='center'>".date("d-m-Y",strtotime($det->TglCutiDari))."</td>";
        echo "<td align='center'>".date("d-m-Y",strtotime($det->TglCutiSampai))."</td>";
        echo "<td align='center'>".$det->TotalCuti." hari</td>";
        echo "<td align='left'>".$det->Alasan."</td>";
     ?>
    </tr>
</table>
Mohon isi alasannya di formulir ini : <br>
<form action="<?php echo site_url()."/trx01/home/confirm/true/$ckode/reject/$userid"?>" method="post">
    <textarea name="r_reason" required></textarea>
    <input type='hidden' name='from_step' value='<?php echo $step;?>'>
    <input type='hidden' name='tgl_reject' value='<?php echo date('d-m-Y');?>'>
    <input type='submit' value="kirim">
</form>

<?php 
}
if ($step == "2"){
?>
<table >
<tr class='row1'>
    <td>NIP</td><td>:</td><td><?php echo $r->IDEmployee;?></td>
</tr>
<tr class='row2'>
    <td>Nama</td><td>:</td><td><?php echo $r->FullName;?></td>
</tr>
<tr class='row1'>
    <td>Divisi</td><td>:</td><td><?php echo $r->IDDepartement;?></td>
</tr>
<tr class='row2'>
    <td>Jabatan</td><td>:</td><td><?php echo $r->IDUnitGroup;?></td>
</tr>
</table>
Anda tidak menyetujui permohonan cuti dari nama tersebut di atas sebagaimana detail berikut :
<table width='100%'>
    <tr class='row2'>
        <td align='center' width='20%' >Jenis Cuti</td>
        <td align='center' width='10%' >Dari</td>
        <td align='center' width='10%' >Sampai</td>
        <td align='center' width='5%' >Total</td>
        <td align='center'>Alasan</td>
        <td align='center'>Pengganti</td>
    </tr>
    <tr class='row1'>
    <?php
        switch ($det->Jenis){
            case 'SL'   : $jenis = 'Cuti Sakit'; break;
            case 'AL'   : $jenis = 'Cuti Tahunan'; break;
            case 'MRL'  : $jenis = 'Cuti Menikah'; break;
            case 'MTL'  : $jenis = 'Cuti Melahirkan'; break;
            case 'CL'   : $jenis = 'Cuti Duka Cita'; break;
            case 'OL'   : $jenis = 'Cuti Tidak Dibayarkan'; break;
            case 'CIR'  : $jenis = 'Cuti Khitanan'; break;
        }
        echo "<td align='center'>".$jenis."</td>";
        echo "<td align='center'>".date("d-m-Y",strtotime($det->TglCutiDari))."</td>";
        echo "<td align='center'>".date("d-m-Y",strtotime($det->TglCutiSampai))."</td>";
        echo "<td align='center'>".$det->TotalCuti." hari</td>";
        echo "<td align='left'>".$det->Alasan."</td>";
        echo "<td align='left'>".$this->lvm->get_employee($det->IDPengganti)->row()->FullName."</td>";
     ?>
    </tr>
</table>
Mohon isi alasannya di formulir ini : <br>
<form action="<?php echo site_url()."/trx01/home/confirm/true/$ckode/reject/$userid"?>" method="post">
    <textarea name="r_reason" required="true">
    </textarea>
    <input type='hidden' name='from_step' value='<?php echo $step;?>'>
    <input type='hidden' name='tgl_reject' value='<?php echo date('d-m-Y');?>'>
    <input type='submit' value="kirim">
</form>

<?php
}
if ($step == "3"){
?>
<table >
<tr class='row1'>
    <td>NIP</td><td>:</td><td><?php echo $r->IDEmployee;?></td>
</tr>
<tr class='row2'>
    <td>Nama</td><td>:</td><td><?php echo $r->FullName;?></td>
</tr>
<tr class='row1'>
    <td>Divisi</td><td>:</td><td><?php echo $r->IDDepartement;?></td>
</tr>
<tr class='row2'>
    <td>Jabatan</td><td>:</td><td><?php echo $r->IDUnitGroup;?></td>
</tr>
</table>
Anda tidak menyetujui permohonan cuti dari nama tersebut di atas sebagaimana detail berikut :
<table width='100%'>
    <tr class='row2'>
        <td align='center' width='20%' >Jenis Cuti</td>
        <td align='center' width='10%' >Dari</td>
        <td align='center' width='10%' >Sampai</td>
        <td align='center' width='5%' >Total</td>
        <td align='center'>Alasan</td>
        <td align='center'>Pengganti</td>
        <td align='center'>Disetujui</td>
    </tr>
    <tr class='row1'>
    <?php
        switch ($det->Jenis){
            case 'SL'   : $jenis = 'Cuti Sakit'; break;
            case 'AL'   : $jenis = 'Cuti Tahunan'; break;
            case 'MRL'  : $jenis = 'Cuti Menikah'; break;
            case 'MTL'  : $jenis = 'Cuti Melahirkan'; break;
            case 'CL'   : $jenis = 'Cuti Duka Cita'; break;
            case 'OL'   : $jenis = 'Cuti Tidak Dibayarkan'; break;
            case 'CIR'  : $jenis = 'Cuti Khitanan'; break;
        }
        echo "<td align='center'>".$jenis."</td>";
        echo "<td align='center'>".date("d-m-Y",strtotime($det->TglCutiDari))."</td>";
        echo "<td align='center'>".date("d-m-Y",strtotime($det->TglCutiSampai))."</td>";
        echo "<td align='center'>".$det->TotalCuti." hari</td>";
        echo "<td align='left'>".$det->Alasan."</td>";
        echo "<td align='left'>".$this->lvm->get_employee($det->IDPengganti)->row()->FullName."</td>";
        $idparent = $this->lvm->get_employee($r->IDEmployee)->row()->IDEmployeeParent;
        echo "<td align='left'>".$this->lvm->get_employee($idparent)->row()->FullName."</td>";
     ?>
    </tr>
</table>
Mohon isi alasannya di formulir ini : <br>
<form action="<?php echo site_url()."/trx01/home/confirm/true/$ckode/reject/$userid"?>" method="post">
    <textarea name="r_reason" required="true">
    </textarea>
    <input type='hidden' name='from_step' value='<?php echo $step;?>'>
    <input type='hidden' name='tgl_reject' value='<?php echo date('d-m-Y');?>'>
    <input type='submit' value="kirim">
</form>
<?php
}
?>
